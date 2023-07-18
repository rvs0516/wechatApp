<?php
/**
+----------------------------------------------------------
* 业务逻辑模型
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
class business
{

	// 参数
	public $_corpId;
	public $_corpsecret;
	public $_wxcpt;
	public $_token;
	public $_encodingAESKey;
	public $_access_token;
	public $_customer_database_model;

	/**
     * 构造函数
     */
	public function __construct($token, $encodingAESKey) {

		$this->_corpid = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$this->_corpsecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 应用的凭证密钥t。自建应用secret。

		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$this->_token = $token;
		$this->_encodingAESKey = $encodingAESKey;

		// echo "<pre>";
		// var_dump($this->_token);
		// var_dump($this->_encodingAESKey);
		// exit;

		load('model.aes.WXBizMsgCrypt');
		$this->_wxcpt = new WXBizMsgCrypt($this->_token, $this->_encodingAESKey, $this->_corpid);

		// 获取access_token
		$this->_access_token = $this->getToken();

		// 引用客户数据表数据访问模型
		$this->_customer_database_model = getInstance('model.customer.database');

	}

	/**
	 * 解密企业微信回调事件加密信息
	 *
	 * @param [type] $sReqMsgSig
	 * @param [type] $sReqTimeStamp
	 * @param [type] $sReqNonce
	 * @param [type] $sReqData
	 * @param [type] $sMsg
	 * @return void
	 */
	public function decryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg)
	{
		$errCode = $this->_wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);

		$data = array(
			'errCode' => $errCode,
			'sMsg' => $sMsg // DecryptMsg函数的引用传递参数，可以在引用函数后直接使用。
		);
		
		return $data;
	}

	/**
	 * 添加企业客户事件
	 */
	public function addExternalContact($xMsg)
	{
		// 企业成员微信添加成功外部联系人、被客户删除重新发送请求添加成功、以及外部联系人主动添加了配置了客户联系功能且开启了免验证的成员（此时成员企业微信自动确认添加对方为好友）时，回调该事件：XML标签配置
		$xmlTag = array(
			'ToUserName', // 企业ID
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID',
			'WelcomeCode' // 欢迎语code，可用于发送欢迎语
		);

		$arrayData = $this->xmlToArray($xmlTag, $xMsg);

		// echo "<pre>";
		// var_dump($arrayData);
		// exit;

		/*{
			"ToUserName":"wwe6ce267036e47037",
			"FromUserName":"sys",
			"CreateTime":"1686056613",
			"MsgType":"event",
			"Event":"change_external_contact",
			"ChangeType":"add_external_contact",
			"UserID":"HeYongZhen",
			"ExternalUserID":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg",
			"WelcomeCode":"U1lkWes8bROtdWLYmgDYVcwl_KigV5QMQq1DB7xV_JE"
		}*/

		// 客户主动添加待员工同意
		// 二次添加成功
		// 客户主动添加成功

		// 获取当前客户是否存在于客户数据表中
		// 获取客户详情
		$customerData = $this->_customer_database_model->getSingleCustomerData($arrayData["UserID"], $arrayData["ExternalUserID"]);

		// echo "<pre>";
		// var_dump($arrayData);
		// var_dump($customerData);
		// exit;

		if (empty($customerData)) {

			// 针对在职转接情况，需要再查询一次客户数据表中的当前客户是否存在“正在转接中”的记录，存在则进入在职转接成功逻辑，不存在则进入新增客户逻辑
			// 获取正在转接状态的客户信息
			$customerData = $this->_customer_database_model->getDesignationStateCustomer($arrayData["UserID"], $arrayData["ExternalUserID"], "正在转接中");

			// echo "<pre>";
			// var_dump($arrayData);
			// var_dump($customerData);
			// exit;

			if ($customerData) {
				// 在职继承成功
				$state = "正常";

				// 把客户状态"正在转接中"修改为正常
				$updateData = array(
					'follow_userid' => $customerData["follow_userid"], // 使用客户数据表中原来跟进企业成员微信userid
					'external_userid' => $arrayData["ExternalUserID"], 
					'old_follow_userid' => $customerData["follow_userid"],  // 所属人变为原所属人
					'new_follow_userid' => "del", // 删除准所属人记录，删除标记为del
					'remark_follow_userid' => $arrayData["UserID"], // remark_follow_userid 用于接收更换所属人
					'state' => $state,
					'transfer_success_time' => time() // 最近在职转接成功时间
				);
				// echo "<pre>";
				// var_dump($updateData);
				// exit;
				$this->_customer_database_model->updateCustomerState($updateData);
			} else {
				// 添加新客户

				/* 
				【说明】
				判断当前客户的跟进企业成员微信下的客户信息是否曾经同步到客户数据表，先根据企业成员微信userid查找客户数据表中的一条记录，
				一条记录都不存在，可能是当前企业成员微信下没有客户或者有客户但没同步到客户数据表。那么先获取当前企业成员微信下的所有客户信息，存在客户信息则添加所有客户到客户数据表，不再单独添加当前客户
				*/
				$followSingleCustomer = $this->_customer_database_model->getFollowSingleCustomer($arrayData["UserID"]);

				if ( empty($followSingleCustomer) ) {
					// 添加当前企业成员微信下的所有客户到客户数据表

					// 获取当前企业成员微信下的所有客户
					$externalUseridList = $this->getExternalUserid($arrayData["UserID"]);

					// 	array(3) {
					// 		[0]=>
					// 		string(32) "woFrvHCQAA_CA2_E5Z1TJqw5G3UEuuJw"
					// 		[1]=>
					// 		string(32) "wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
					// 		[2]=>
					// 		string(32) "wmFrvHCQAAs_grwhgKbYa5LGmEmkBICQ"
					// 	}

					if ($externalUseridList) {
						// 企业成员微信下有客户

						foreach ($externalUseridList as $key => $value) {
							// 批量添加客户
							$this->addNewCustomer($value);
						}
						
						// 仅用于日志记录
						$state = "添加当前企业成员微信下的所有客户";

					} else {

						// 企业成员微信下没有客户，则添加当前回调的新客户
						$this->addNewCustomer($arrayData["ExternalUserID"]);

						// 仅用于日志记录
						$state = "添加当前回调的新客户";
					}
					
				} else {
					// 添加当前回调的新客户
					$this->addNewCustomer($arrayData["ExternalUserID"]);

					// 仅用于日志记录
					$state = "添加当前回调的新客户";
				}
				
			}

		} elseif($customerData && ($customerData['state'] == "被客户删除" || $customerData['state'] == "被员工删除" || $customerData['state'] == "双向删除")) {

			// 被客户删除、被员工删除、双向删除等情况下，重新发送请求添加成功
			$state = "重新发送请求添加成功";

			// 修改客户状态
			$updateData = array(
				'follow_userid' => $arrayData["UserID"], 
				'external_userid' => $arrayData["ExternalUserID"], 
				'state' => $state,
				'restart_createtime' => time()
			);
			$this->_customer_database_model->updateCustomerState($updateData);

		} elseif ( $customerData && ($customerData['state'] == "待企业成员同意" )) {

			$state = "正常";

			if ($customerData['state'] == '未知来源') {
				$add_way = '客户主动添加'; // 修改客户来源。包括客户可能是主动扫企业成员微信二维码、或者搜企业成员手机号等多种方式添加到企业成员微信，企业微信是标注此类客户为未知来源。
			} else {
				$add_way = $customerData['state'];
			}

			// 修改客户状态
			$updateData = array(
				'follow_userid' => $arrayData["UserID"], 
				'external_userid' => $arrayData["ExternalUserID"], 
				'state' => $state,
				'add_way' => $add_way,
				'agree_createtime' => time()
			);

			// echo "<pre>";
			// var_dump($updateData);
			// exit;

			$this->_customer_database_model->updateCustomerState($updateData);
		}

		// 记录xml数据和xml转为array数据
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n". "state: ". $state. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_addExternalContact_".date('Ymd').".txt");
	}

	/**
	 * 添加新客户
	 */
	public function addNewCustomer($externalUserid, $state = "正常")
	{
		// 表示企业成员微信第一次成功添加客户，或者外部联系人主动添加了配置了客户联系功能且开启了免验证的成员（此时成员企业微信自动确认添加对方为好友）
		$state = $state;

		// 向企业微信获取客户详情，然后保存客户信息到客户数据表
		$externalcontactDetail = $this->externalcontact($externalUserid);

		/*{
			"errcode":0,
			"errmsg":"ok",
			"external_contact":{
				"external_userid":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg",
				"name":"HEYZ",
				"type":1,
				"avatar":"http://wx.qlogo.cn/mmhead/PiajxSqBRaELfic8lqKJb9xJV4kIA48whfby6aOewwGjYAS8lpHRia6FQ/0",
				"gender":1
			},
			"follow_user":[
				{
					"userid":"HeYongZhen",
					"remark":"何永真",
					"description":"大R",
					"createtime":1686298805,
					"tags":[
						{
							"group_name":"个人标签",
							"tag_name":"高付费",
							"type":2
						},
						{
							"group_name":"客户等级",
							"tag_name":"核心",
							"type":1,
							"tag_id":"etFrvHCQAAdmO0fNxS6dIcyndju876EQ"
						}
					],
					"remark_mobiles":[
						"13802345801",
						"13802345802",
						"13802345803"
					],
					"remark_corp_name":"乾游",
					"add_way":0
				}
			]
		}*/

		// 外部联系人性别 0-未知 1-男性 2-女性
		if ($externalcontactDetail["external_contact"]["gender"] == 1) {
			$gender = "男性";
		} elseif ($externalcontactDetail["external_contact"]["gender"] == 2) {
			$gender = "女性";
		} else {
			$gender = "未知";
		}

		// 外部联系人的类型，1表示该外部联系人是微信用户，2表示该外部联系人是企业微信用户
		if ($externalcontactDetail["external_contact"]["type"] == 1) {
			$type = "微信用户";

			$remark_corp_name = $externalcontactDetail["follow_user"][0]["remark_corp_name"] ? $externalcontactDetail["follow_user"][0]["remark_corp_name"] : ""; // 微信客户备注的企业名称（仅微信客户有该字段）
		} elseif ($externalcontactDetail["external_contact"]["type"] == 2) {
			$type = "企业微信用户";

			$remark_corp_name = ""; // 微信客户备注的企业名称（仅微信客户有该字段）
		}

		// 备注手机号，类型为array，可能存在多个手机号
		if ( !empty($externalcontactDetail["follow_user"][0]["remark_mobiles"]) ) {
			$remark_mobiles_string = implode(";", $externalcontactDetail["follow_user"][0]["remark_mobiles"]);
			$remark_mobiles = rtrim($remark_mobiles_string, ";");
		} else {
			$remark_mobiles = "";
		}

		// 标签
		$tags = $externalcontactDetail["follow_user"][0]["tags"];
		$tag_name1 = "";
		$tag_name2 = "";
		if ( !empty($tags) ) {
			foreach ($tags as $key => $value) {
				if ($value['type'] == 1) {
					// 企业标签
					$tag_name1 .= $value['tag_name'].";";
				} elseif($value['type'] == 2) {
					// 个人标签：企业成员对外部联系人的自定义标签
					$tag_name2 .= $value['tag_name'].";";
				}
			}
			$tag_name1 = rtrim($tag_name1, ";");
			$tag_name2 = rtrim($tag_name2, ";");
		}

		// 获取客户来源
		$add_way = $this->getAddWay($externalcontactDetail["follow_user"][0]["add_way"]);

		$customerArray = array(
			// 客户名字
			"name" => $externalcontactDetail["external_contact"]["name"],
			// 客户userid，外部联系人userid
			"external_userid" => $externalcontactDetail["external_contact"]["external_userid"],
			// 客户头像
			"avatar" => $externalcontactDetail["external_contact"]["avatar"],
			// 性别 
			"gender" => $gender,
			// 所属人
			"follow_userid" => $externalcontactDetail["follow_user"][0]["userid"],
			// 客户类型   
			"type" => $type,
			// 客户状态：正常，被员工删除，被客户删除，双向删除，重新发送请求添加成功，默认正常
			"state" => $state,
			// 客户来源   
			"add_way" => $add_way,
			// 企业标签
			"tag_name1" => $tag_name1,
			// 个人标签
			"tag_name2" => $tag_name2,
			// 备注名称
			"remark" => $externalcontactDetail["follow_user"][0]["remark"] ? $externalcontactDetail["follow_user"][0]["remark"] : "",
			// 备注手机号
			"remark_mobiles" => $remark_mobiles,
			// 备注企业名称
			"remark_corp_name" => $remark_corp_name,
			// 描述
			"description" => $externalcontactDetail["follow_user"][0]["description"] ? $externalcontactDetail["follow_user"][0]["description"] : "",
			// 公司简称
			"corp_name" => $externalcontactDetail["external_contact"]["corp_name"] ? $externalcontactDetail["external_contact"]["corp_name"] : "",
			// 公司全称
			"corp_full_name" => $externalcontactDetail["external_contact"]["corp_full_name"] ? $externalcontactDetail["external_contact"]["corp_full_name"] : "",
			// 创建时间
			// "createtime" => date("Y-m-d H:i:s", $externalcontactDetail["follow_user"][0]["createtime"]),
			"createtime" => $externalcontactDetail["follow_user"][0]["createtime"], // 保存时间戳到数据库
			// 修改时间，默认为创建时间
			// "updatetime" => date("Y-m-d H:i:s", $externalcontactDetail["follow_user"][0]["createtime"])
			// "updatetime" => $externalcontactDetail["follow_user"][0]["createtime"]
		);

		// 保存数据到客户数据表
		$this->_customer_database_model->setFollowCustomers($customerArray);
	}

	/**
	 * 外部联系人免验证添加成员事件（此时成员尚未确认添加对方为好友）
	 */
	public function addHalfExternalContact($xMsg)
	{
		// 外部联系人主动添加了配置了客户联系功能且开启了免验证的成员时（此时成员尚未确认添加对方为好友），回调该事件：XML标签配置
		$xmlTag = array(
			'ToUserName',
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID',
			'State', // 添加此用户的「联系我」方式配置的state参数，或在获客链接中指定的customer_channel参数，可用于识别添加此用户的渠道
			'WelcomeCode' // 欢迎语code，可用于发送欢迎语
		);

		$arrayData = $this->xmlToArray($xmlTag, $xMsg);

		/* {
			"ToUserName":"wwe6ce267036e47037",
			"FromUserName":"sys",
			"CreateTime":"1686059631",
			"MsgType":"event",
			"Event":"change_external_contact",
			"ChangeType":"add_half_external_contact",
			"UserID":"HeYongZhen",
			"ExternalUserID":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg",
			"WelcomeCode":"eNWvgC7JHI2T5Ym_URh5TbpjjRqvKpfIOuKSFFJUvFg"
		}*/

		// 获取当前企业成员微信在客户数据表的客户数据
		$followSingleCustomer = $this->_customer_database_model->getFollowSingleCustomer($arrayData["UserID"]);

		// 当前企业成员没有客户数据在客户数据表
		if ( empty($followSingleCustomer) ) {
			// 添加当前企业成员微信下的所有客户到客户数据表

			// 同步企业成员微信下的所有客户
			$this->syncCustomer($arrayData["UserID"]);
		}

		// 添加新客户
		$this->addNewCustomer($arrayData['ExternalUserID'], "待企业成员同意");

		// 记录xml数据和xml转为array数据
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_addHalfExternalContact_".date('Ymd').".txt");
	}

	/**
	 * 删除跟进成员事件
	 */
	public function delFollowUser($xMsg)
	{
		// 【提示】员工的企业微信被客户微信删除后，客户微信是依然存在于员工企业微信的好友关系中的，所以被客户删除后依然可以调用客户详情接口获取客户详情信息的。也就是只要客户存在于员工的企业微信好友关系中的，都是调用接口获取客户详情信息的。
		// 企业成员微信删除了客户微信后，客户微信再删除企业成员微信的时候不再有回调信息。因为企业成员先删除客户微信的，客户已经不存在于企业成员微信好友列表中了。

		// 被外部联系人删除时，回调该事件：XML标签配置
		$xmlTag = array(
			'ToUserName',
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID'
		);

		$arrayData = $this->xmlToArray($xmlTag, $xMsg);

		/*{
			"ToUserName":"wwe6ce267036e47037",
			"FromUserName":"sys",
			"CreateTime":"1686056559",
			"MsgType":"event",
			"Event":"change_external_contact",
			"ChangeType":"del_follow_user",
			"UserID":"HeYongZhen",
			"ExternalUserID":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
		}*/

		// 获取当前客户详情
		$customerData = $this->_customer_database_model->getSingleCustomerData($arrayData["UserID"], $arrayData["ExternalUserID"]);
		
		$logTips = "";

		if (empty($customerData)) {
			// 获取不到当前客户信息，但存在被客户删除的情况，证明当前企业成员微信下是有客户微信的，则先同步当前企业成员微信下的所有客户信息，再读取一次客户数据表获取当前客户详情，修改状态
			
			// 同步企业成员微信下的所有客户
			$this->syncCustomer($arrayData["UserID"]);

			// 再读取一次客户数据表获取当前客户详情
			$customerData = $this->_customer_database_model->getSingleCustomerData($arrayData["UserID"], $arrayData["ExternalUserID"]);

			// 仅用于日志标记
			$logTips = "先同步客户再修改状态";
		}

		// 被客户删除次数加1
		$customer_delete_number = $customerData['customer_delete_number'] + 1;

		// 修改客户状态
		$updateData = array(
			'follow_userid' => $arrayData["UserID"], 
			'external_userid' => $arrayData["ExternalUserID"], 
			"state" => "被客户删除",
			"customer_deletetime" => time(),
			"customer_delete_number" => $customer_delete_number
		);
		$this->_customer_database_model->updateCustomerState($updateData);

		// 记录xml数据和xml转为array数据
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n". "logTips: ". $logTips. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_delFollowUser_".date('Ymd').".txt");
	}

	/**
	 * 删除企业客户事件
	 */
	public function delExternalContact($xMsg)
	{
		// 【提示】当企业成员微信删除了客户微信后，使用客户useid请求企业微信客户详情接口返回errcode为84061，服务人员删除了客户，则不再存在好友关系，无法调用接口。也就是存在好友关系的时候，才可以获取到客户详情信息。

		// 企业微信成员删除外部联系人时，回调该事件：XML标签配置
		$xmlTag = array(
			'ToUserName',
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID',
			'Source' // 删除客户的操作来源，DELETE_BY_TRANSFER表示此客户是因在职继承自动被转接成员删除
		);

		$arrayData = $this->xmlToArray($xmlTag, $xMsg);

		/*{
			"ToUserName":"wwe6ce267036e47037",
			"FromUserName":"sys",
			"CreateTime":"1686057391",
			"MsgType":"event",
			"Event":"change_external_contact",
			"ChangeType":"del_external_contact",
			"UserID":"HeYongZhen",
			"ExternalUserID":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
		}*/

		// 获取客户详情
		$customerData = $this->_customer_database_model->getSingleCustomerData($arrayData["UserID"], $arrayData["ExternalUserID"]);

		if ($customerData) {

			// 判断是否在职继承成功后，企业成员微信自动删除客户微信。通过最近在职转接成功时间跟当前时间戳的差值小于3秒则判断为在职继承成功后删除原跟进企业成员的回调，则不更新客户状态，大于3秒则更新。
			$currentTime = time();
			$timeDifference = abs($currentTime - $customerData['transfer_success_time']); // 差值的绝对值

			if ( $timeDifference > 3 ) {
				// 大于3秒则更新，则是正常被客户删除回调事件

				// 客户存在于客户数据表中，则修改客户状态

				// 判断客户当前状态
				if ($customerData["state"] == "被客户删除") {
					$state = "双向删除";
				} else {
					$state = "被员工删除";
				}

				// 修改客户状态
				$updateData = array(
					'follow_userid' => $arrayData["UserID"], 
					'external_userid' => $arrayData["ExternalUserID"], 
					"state" => $state,
					"follow_deletetime" => time()
				);
				$this->_customer_database_model->updateCustomerState($updateData);

				// 记录xml数据和xml转为array数据
				error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_delExternalContact_success_".date('Ymd').".txt");
			
			} else {
				// 小于3秒则是在职继承成功后删除原跟进企业成员的回调，不用更新客户状态

				// 记录xml数据和xml转为array数据
				error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_delExternalContact_not_".date('Ymd').".txt");
			}
			
		} else {
			
			/*
			【说明】
			企业成员删除客户微信，证明企业成员微信下是有至少一个客户的，一般是有多个客户。
			当客户数据表中不存在当前企业成员微信下的客户信息，需要同步企业成员微信下的所有客户到客户数据表。
			企业成员微信删除了客户微信后，则跟客户微信不再存在好友关系，使用客户useid请求企业微信客户详情接口返回errcode为84061，无法调用接口同步到当前被企业成员删除的客户信息了。
			*/

			// 获取当前企业成员微信下的所有客户
			$externalUseridList = $this->getExternalUserid($arrayData["UserID"]);

			// 	array(3) {
			// 		[0]=>
			// 		string(32) "woFrvHCQAA_CA2_E5Z1TJqw5G3UEuuJw"
			// 		[1]=>
			// 		string(32) "wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
			// 		[2]=>
			// 		string(32) "wmFrvHCQAAs_grwhgKbYa5LGmEmkBICQ"
			// 	}

			$logTips = "";

			if ($externalUseridList) {
				// 企业成员微信下还有其他客户

				foreach ($externalUseridList as $key => $value) {
					// 批量添加客户
					$this->addNewCustomer($value);
				}
				
				// 仅用于日志记录
				$logTips = "添加当前企业成员微信下的所有客户";

			} else {
				// 仅用于日志记录
				$logTips = "当前企业成员微信下唯一的客户已被删除，没有其他客户了";
			}

			// 记录xml数据和xml转为array数据
			error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n". "logTips: ". $logTips. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_delExternalContact_error_".date('Ymd').".txt");
		}

	}

	/**
	 * 编辑企业客户事件
	 */
	public function editExternalContact($xMsg)
	{
		/*
		【注意】
		目前在crm后台修改客户信息后，企业微信也会发送当前回调事件，这样会出现2次修改客户数据表信息。
		针对这个问题，crm后台修改客户信息后不再修改客户数据表信息，等接收到企业微信的当前回调事件后，再修改客户数据表信息。
		*/

		// 企业微信成员修改外部联系人的备注、手机号或标签时，回调该事件：XML标签配置
		$xmlTag = array(
			'ToUserName',
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID'
		);

		$arrayData = $this->xmlToArray($xmlTag, $xMsg);

		// 【注意】编辑企业客户事件是没有回调修改内容的，只是回调通知是哪个企业客户信息被修改了，需要通过客户userid请求客户详情接口获取客户最新消息
		/*{
			"ToUserName":"wwe6ce267036e47037", // 企业ID
			"FromUserName":"sys",
			"CreateTime":"1686059821",
			"MsgType":"event",
			"Event":"change_external_contact",
			"ChangeType":"edit_external_contact",
			"UserID":"HeYongZhen",
			"ExternalUserID":"wmFrvHCQAAqlKhnwjb12f7m_BQfnOwOw"
		}*/

		// 获取客户详情
		$customerData = $this->_customer_database_model->getSingleCustomerData($arrayData["UserID"], $arrayData["ExternalUserID"]);

		if (empty($customerData)) {
			// 客户数据表中不存在当前客户信息，但有编辑回调事件说明企业成员微信下是有客户的，则同步客户的跟进企业成员微信下的所有客户信息，同步完成后客户信息已是最新，不用再执行修改客户数据表逻辑

			// 同步企业成员微信下的所有客户
			$this->syncCustomer($arrayData["UserID"]);

			// 记录xml数据和xml转为array数据
			error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_editExternalContact2_".date('Ymd').".txt");
			
		} else {
			// 客户数据表中存在客户信息，则获取客户最新信息更新到客户数据表

			// 获取当前修改信息的外部联系人详情信息，然后更新当前外部联系人在数据库中的信息
			$externalcontactDetail = $this->externalcontact($arrayData['ExternalUserID']);

			// 标签
			$tags = $externalcontactDetail["follow_user"][0]["tags"];
			$tag_name1 = "";
			$tag_name2 = "";
			if ( !empty($tags) ) {
				foreach ($tags as $key => $value) {
					if ($value['type'] == 1) {
						// 企业标签
						$tag_name1 .= $value['tag_name'].";";
					} elseif($value['type'] == 2) {
						// 个人标签：企业成员对外部联系人的自定义标签
						$tag_name2 .= $value['tag_name'].";";
					}
				}
				$tag_name1 = rtrim($tag_name1, ";");
				$tag_name2 = rtrim($tag_name2, ";");
			}

			// 备注手机号，类型为array，可能存在多个手机号
			if ( !empty($externalcontactDetail["follow_user"][0]["remark_mobiles"]) ) {
				$remark_mobiles_string = implode(";", $externalcontactDetail["follow_user"][0]["remark_mobiles"]);
				$remark_mobiles = rtrim($remark_mobiles_string, ";");
			} else {
				$remark_mobiles = "";
			}

			// 更新当前外部联系人在数据库中的信息
			$updateData = array(
				'follow_userid' => $externalcontactDetail["follow_user"][0]["userid"], 
				'external_userid' => $externalcontactDetail["external_contact"]["external_userid"], 
				'tag_name1' => $tag_name1, // 企业标签
				'tag_name2' => $tag_name2, // 个人标签
				'remark' => $externalcontactDetail["follow_user"][0]["remark"], 
				'description' => $externalcontactDetail["follow_user"][0]["description"], 
				'remark_corp_name' => $externalcontactDetail["follow_user"][0]["remark_corp_name"], 
				'remark_mobiles' => $remark_mobiles,
				'updatetime' => time()
			);
			// error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "updateData: ". json_encode($updateData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_updateData_".date('Ymd').".txt");
			$this->_customer_database_model->updateCustomerData($updateData);

			// 记录xml数据和xml转为array数据
			error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "xMsg: ". $xMsg. "\n". "arrayData: ". json_encode($arrayData). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_editExternalContact1_".date('Ymd').".txt");
		
		}
	
	}

	/**
	 * 同步企业成员微信下的所有客户
	 */
	public function syncCustomer($follow_userid)
	{
		// 获取当前企业成员微信下的所有客户
		$externalUseridList = $this->getExternalUserid($follow_userid);

		// 	array(3) {
		// 		[0]=>
		// 		string(32) "woFrvHCQAA_CA2_E5Z1TJqw5G3UEuuJw"
		// 		[1]=>
		// 		string(32) "wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
		// 		[2]=>
		// 		string(32) "wmFrvHCQAAs_grwhgKbYa5LGmEmkBICQ"
		// 	}

		if ($externalUseridList) {
			foreach ($externalUseridList as $key => $value) {
				// 批量添加客户
				$this->addNewCustomer($value);
			}
		}
	}

	/**
	 * 获取客户详情
	 * 
	 * @param mixed access_token
	 * @param mixed external_userid
	 * @param mixed cursor 上次请求返回的next_cursor，也就是上次的页数。当客户在企业内的跟进人超过500人时需要使用cursor参数进行分页获取。
	 */
	public function externalcontact($external_userid, $cursor = "")
	{
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/get?access_token=".$this->_access_token."&external_userid=$external_userid&cursor=$cursor";

		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);

		// echo "<pre>";
		// var_dump($url);
		// var_dump($res);
		// exit;

		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "resJson：". $resJson. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_externalcontact_".date('Ymd').".txt");

		/*{
			"errcode":0,
			"errmsg":"ok",
			"external_contact":{
				"external_userid":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg",
				"name":"HEYZ",
				"type":1,
				"avatar":"http://wx.qlogo.cn/mmhead/PiajxSqBRaELfic8lqKJb9xJV4kIA48whfby6aOewwGjYAS8lpHRia6FQ/0",
				"gender":1
			},
			"follow_user":[
				{
					"userid":"HeYongZhen",
					"remark":"何永真",
					"description":"大R",
					"createtime":1686298805,
					"tags":[
						{
							"group_name":"个人标签",
							"tag_name":"高付费",
							"type":2
						},
						{
							"group_name":"客户等级",
							"tag_name":"核心",
							"type":1,
							"tag_id":"etFrvHCQAAdmO0fNxS6dIcyndju876EQ"
						}
					],
					"remark_mobiles":[
						"13802345801",
						"13802345802",
						"13802345803"
					],
					"remark_corp_name":"乾游",
					"add_way":0
				}
			]
		}*/

		if ($res['errcode'] == 0) {
			return $res;
		}

	}

	/**
	 * 获取指定企业成员微信添加的客户userid列表
	 * 
	 * @param mixed $access_token 
	 * @param mixed $userid 企业成员的userid
	 */
	public function getExternalUserid($userid)
	{
		// echo "<pre>";
		// var_dump($access_token);
		// var_dump($userid);
		// exit;
        
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/list?access_token=".$this->_access_token."&userid=$userid";
		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);
		// echo "<pre>";
		// var_dump($url);
		// var_dump($res);
		// exit;
		if ($res['errcode'] == 0) {

			return $res["external_userid"];

		}

		// external_userid : 外部联系人的userid列表, 也就是当前企业成员微信号添加的客户userid列表

		// array(3) {
		// 	["errcode"]=>
		// 	int(0)
		// 	["errmsg"]=>
		// 	string(2) "ok"
		// 	["external_userid"]=>
		// 	array(3) {
		// 		[0]=>
		// 		string(32) "woFrvHCQAA_CA2_E5Z1TJqw5G3UEuuJw"
		// 		[1]=>
		// 		string(32) "wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
		// 		[2]=>
		// 		string(32) "wmFrvHCQAAs_grwhgKbYa5LGmEmkBICQ"
		// 	}
		// }
	}


	  /**
	 * 获取客户来源
	 * 
	 * @param mixed $code 客户来源的指向代码
	 */
	public function getAddWay($code)
	{
		$add_way = "";
		switch ($code) {
			case 0:
				$add_way = "未知来源"; // 企业微信删除客户和企业微信被客户删除后重新添加后，会归为未知来源
				break;	

			case 1:
				$add_way = "扫描二维码";
				break;	

			case 2:
				$add_way = "搜索手机号";
				break;	

			case 3:
				$add_way = "名片分享";
				break;		

			case 4:
				$add_way = "群聊";
				break;	

			case 5:
				$add_way = "手机通讯录";
				break;	

			case 6:
				$add_way = "微信联系人";
				break;	

			case 8:
				$add_way = "安装第三方应用时自动添加的客服人员";
				break;	

			case 9:
				$add_way = "搜索邮箱";
				break;	

			case 10:
				$add_way = "视频号添加";
				break;	

			case 11:
				$add_way = "通过日程参与人添加";
				break;	

			case 12:
				$add_way = "通过会议参与人添加";
				break;		
				
			case 13:
				$add_way = "添加微信好友对应的企业微信";
				break;	
			
			case 14:
				$add_way = "通过智慧硬件专属客服添加";
				break;	

			case 15:
				$add_way = "通过上门服务客服添加";
				break;
				
			case 16:
				$add_way = "通过获客链接添加";
				break;	

			case 201:
				$add_way = "内部成员共享";
				break;	

			case 202:
				$add_way = "管理员/负责人分配";
				break;	

			default:
				$add_way = "未知来源";
				break;
		}

		return $add_way;
	}

	 /**
	 * 获取access_token
	 * 
	 * 权限说明：每个应用有独立的secret，所以每个应用的access_token应该分开来获取
	 */
	public function getToken()
	{
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->_corpid."&corpsecret=".$this->_corpsecret;

		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($res);
        // exit;

		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "resJson：". $resJson. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_getToken_".date('Ymd').".txt");

		if ($res['errcode'] == 0) {
			return $res['access_token'];
		}

		// {
		// 	"errcode": 0,
		// 	"errmsg": "ok",
		// 	"access_token": "1VRfQ6h79jDic8PldlioQcnwEMtOz9qBYGeJ_j69saQ9pVd_tIPbvLaftkcTL7bSzr8Hn86Dkf8IH493ytlb5fwCYiI-Xmsg8SM8QNDXIjkPMKw_SNBqJX_cj6oD1pmvf4jDYQLIly5QtRwp_1LKb5cmjYVNJau5Xme_k1i2yLa9HcekliY2ygt9TPxpSkUjMvC31dYM7Zg-4Mo9nrtm8A",
		// 	"expires_in": 7200
		// }

	}

	/**
	 * xml数据转为指定格式的array
	 * 
	 * @param mixed $xmlTag 预设数组格式
	 * @param mixed $sMsg xml数据
	 */
	public function xmlToArray($xmlTag, $sMsg)
	{
		$array = array();
		foreach($xmlTag as $x) {
			preg_match_all("/<".$x.">.*<\/".$x.">/", $sMsg, $temp);
			$array[] = $temp[0];
		}

		//去除XML标签并组装数据
		$data = array();
		foreach($array as $key => $value) {
			foreach($value as $k => $v) {
				$a = explode($xmlTag[$key].'>', $v);
				$v = substr($a[1], 0, strlen($a[1])-2);
				$data[$k][$xmlTag[$key]] = $v;
			}
		}

		return $data[0];
	}

	/**
	 * 企业微信验证回调URL
	 * 
	 * Token可由企业任意填写，用于生成签名：AP5YtJi6pfYTGfxjqoKgXBkNOPtO8Un
	 * EncodingAESKey用于消息体的加密，是AES密钥的Base64编码：ruiPT9nfbAD6m2Mnbi1JQ1m7yoSm8FWfqrYD1KwQDGo
	 */
	public function verifyURL($params) 
	{
		// $params = '{
		// 	"m":"index",
		// 	"a":"callbackCustomerEvent",
		// 	"msg_signature":"1ecb3749012938f5169ecc35028b3bd544ae1224",
		// 	"timestamp":"1685969100",
		// 	"nonce":"1685321472",
		// 	"echostr":"yay+Bh\/BT2IJKyySGsCi3FC+zTTDsCo\/Q5dpx84Xu1Rr2Ne0mZw3kxyRZs05YzcN4r48UzfLzQamIwSbNuzcvg=="
		// }';

		// 测试数据
		// $params = '{"m":"index","a":"callbackCustomerEvent","msg_signature":"2657a68f36ac38378814182afb1fa0ef1dccdd07","timestamp":"1686041309","nonce":"1685116407","echostr":"yvnoighlzxkwOwVCf6FwiZ8+MsuaY0ruqbh3gJ5NEomItz0HstJblQntataIkEwx0DV9sIiRsy6eVyYfuGPywA=="}';
		// $params = json_decode($params, true);

		// echo "<pre>";
		// var_dump($params);
		// exit;

		// 记录每次请求情况
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "params：". json_encode($params) . "\n\n", 3, C('DEDE_DATA_PATH')."/logs/verifyURL_params_".date('Ymd').".txt");
		
		unset($params['m']);
		unset($params['a']);

		$sVerifyMsgSig = $params['msg_signature']; // 企业微信加密签名，msg_signature结合了企业填写的token、请求中的timestamp、nonce参数、加密的消息体
		$sVerifyTimeStamp = $params['timestamp'];
		$sVerifyNonce = $params['nonce'];
		$sVerifyEchoStr = $params['echostr']; // 加密的字符串。需要解密得到消息内容明文，解密后有random、msg_len、msg、receiveid四个字段，其中msg即为消息内容明文

		// 需要返回的明文
		$sEchoStr = "";

		$errCode = $this->_wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
		if ($errCode == 0) {
			echo $sEchoStr;
			exit;
			// 验证URL成功，将sEchoStr返回
			// HttpUtils.SetResponce($sEchoStr);
		} else {
			print("ERR: " . $errCode . "\n\n");
		}

		
		// $array = array("ret" => $key, "msg" => $this->_types[$key]);
		// echo json_encode($array);exit;
	}

}
