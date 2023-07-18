<?php
/**
 * 对外接口入口
 * 
 */
class indexController extends controller 
{

	public $_REQUEST;
	private $_customer_business_model;

	/**
     * 构造函数
     */
	public function __construct() {
		parent::__construct();

		$this->_REQUEST = $_REQUEST;

		// $this->_customer_business_model = getInstance('model.customer.business');
	}

	/**
	 * 接收客户变更事件
	 * 
	 * 说明：为了能够让自建应用和企业微信进行双向通信，企业可以在应用的管理后台开启接收消息模式。
	 * 当接收成功后，http头部返回200表示接收ok，其他错误码企业微信后台会一律当做失败并发起重试。
	 * 
	 * 访问URL形如： http://test.api.wxwork.2y9y.com/api/index.php?m=index&a=callbackCustomerEvent
	 * 重定向访问URL：http://test.api.wxwork.2y9y.com/api/callbackCustomerEvent
	 * 
	 */
	public function callbackCustomerEvent() 
	{
		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$token = "AP5YtJi6pfYTGfxjqoKgXBkNOPtO8Un";
		$encodingAESKey = "ruiPT9nfbAD6m2Mnbi1JQ1m7yoSm8FWfqrYD1KwQDGo";

		load('model.customer.business');
		$this->_customer_business_model = new business($token, $encodingAESKey);

		// 企业微信验证回调URL
		// $this->_customer_business_model->verifyURL($this->_REQUEST);exit;

		// 测试数据
		/*
		$sMsg = "<xml><ToUserName><![CDATA[wwe6ce267036e47037]]></ToUserName><FromUserName><![CDATA[sys]]></FromUserName><CreateTime>1686050377</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[change_external_contact]]></Event><ChangeType><![CDATA[add_external_contact]]></ChangeType><UserID><![CDATA[HeYongZhen]]></UserID><ExternalUserID><![CDATA[wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg]]></ExternalUserID><WelcomeCode><![CDATA[0P2jM1zY6Vea6MbcRgaZ3W-vIGl1cpuqhZVdK45k0Ss]]></WelcomeCode></xml>";
		$cMsg = str_replace("<![CDATA[", "" , $sMsg);
		$xMsg = str_replace("]]>", "" , $cMsg);
		//XML标签配置
		$xmlTag = array(
			'ToUserName',
			'FromUserName',
			'CreateTime',
			'MsgType',
			'Event',
			'ChangeType',
			'UserID',
			'ExternalUserID',
			'WelcomeCode'
		);
		$arrayData = $this->xmlToArray($xmlTag, $xMsg);
		echo "<pre>";
		var_dump($xMsg); 
		var_dump($arrayData);
		exit;
		*/
			
		// $this->_REQUEST = '{"m":"index","a":"callbackCustomerEvent","msg_signature":"35a1c291fa6983227593f1d2aa590a6709b61106","timestamp":"1686044085","nonce":"1685451045"}';
		// $this->_REQUEST = json_decode($this->_REQUEST, true);

		// echo "<pre>";
		// var_dump($this->_REQUEST);
		// exit;

		// 测试 - 添加企业客户事件
		// $xMsg = '<xml><ToUserName>wwe6ce267036e47037</ToUserName><FromUserName>sys</FromUserName><CreateTime>1686972265</CreateTime><MsgType>event</MsgType><Event>change_external_contact</Event><ChangeType>add_external_contact</ChangeType><UserID>HeYongZhen</UserID><ExternalUserID>wmFrvHCQAAhL9RURDJswxos8B8xf2YGg</ExternalUserID><WelcomeCode>x2oq2HtLBOQtzC-5nubAYWoehpkBD8xIG-67-ec0OjU</WelcomeCode></xml>';
		// $this->_customer_business_model->addExternalContact($xMsg);
		// exit;

		// 测试 - 外部联系人免验证添加成员事件（此时成员尚未确认添加对方为好友）
		// $xMsg = '<xml><ToUserName>wwe6ce267036e47037</ToUserName><FromUserName>sys</FromUserName><CreateTime>1686969693</CreateTime><MsgType>event</MsgType><Event>change_external_contact</Event><ChangeType>add_half_external_contact</ChangeType><UserID>HeYongZhen</UserID><ExternalUserID>wmFrvHCQAAhL9RURDJswxos8B8xf2YGg</ExternalUserID><WelcomeCode>qu0a1qa9Y77YzBpxtUxKBwGFptqUAKJNCmVuZz91ZPc</WelcomeCode></xml>';
		// $this->_customer_business_model->addHalfExternalContact($xMsg);
		// exit;


		// 接收传送的数据
		$xml = file_get_contents("php://input");

		// 记录每次请求情况
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "params：". json_encode($this->_REQUEST) . "\n". "xml：". $xml. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_params_".date('Ymd').".txt");
		
		unset($this->_REQUEST['m']);
		unset($this->_REQUEST['a']);

		$sReqMsgSig = $this->_REQUEST['msg_signature']; // 企业微信加密签名
		$sReqTimeStamp = $this->_REQUEST['timestamp'];
		$sReqNonce = $this->_REQUEST['nonce'];
		$sReqData = $xml; 

		$sMsg = "";  // 解析之后的明文
		$decryptRes = $this->_customer_business_model->decryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);

		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "errCode：". $decryptRes['errCode']. "\n"."sMsg：". $decryptRes['sMsg']. "\n\n" , 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_sMsg_".date('Ymd').".txt");

		if ($decryptRes['errCode'] == 0) {
			// 解密成功，sMsg即为xml格式的明文
			$sMsg = $decryptRes['sMsg'];

			// TODO: 对明文的处理
			/*<xml>
				<ToUserName>
					<![CDATA[wwe6ce267036e47037]]>
				</ToUserName>
				<FromUserName>
					<![CDATA[sys]]>
				</FromUserName>
				<CreateTime>
					1686050377
				</CreateTime>
				<MsgType>
					<![CDATA[event]]>
				</MsgType>
				<Event>
					<![CDATA[change_external_contact]]>
				</Event>
				<ChangeType>
					<![CDATA[add_external_contact]]>
				</ChangeType>
				<UserID>
					<![CDATA[HeYongZhen]]>
				</UserID>
				<ExternalUserID>
					<![CDATA[wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg]]>
				</ExternalUserID>
				<WelcomeCode>
					<![CDATA[0P2jM1zY6Vea6MbcRgaZ3W-vIGl1cpuqhZVdK45k0Ss]]>
				</WelcomeCode>
			</xml>
			*/

			// 去掉xml数据中的CDATA标签
			$cMsg = str_replace("<![CDATA[", "" , $sMsg);
			$xMsg = str_replace("]]>", "" , $cMsg);

			// 通过ChangeType改变类型参数的值判断当前回调事件
			if ( strpos($xMsg, "add_external_contact") !== false ) {

				// 添加企业客户事件
				$this->_customer_business_model->addExternalContact($xMsg);

			} elseif ( strpos($xMsg, "add_half_external_contact") !== false ) {
				
				// 外部联系人免验证添加成员事件
				$this->_customer_business_model->addHalfExternalContact($xMsg);

			} elseif ( strpos($xMsg, "del_follow_user") !== false ) {

				// 删除跟进成员事件
				$this->_customer_business_model->delFollowUser($xMsg);

			} elseif ( strpos($xMsg, "del_external_contact") !== false ) {

				// 删除企业客户事件
				$this->_customer_business_model->delExternalContact($xMsg);

			} elseif ( strpos($xMsg, "edit_external_contact") !== false ) {

				// 编辑企业客户事件
				$this->_customer_business_model->editExternalContact($xMsg);
			
			}

			// 向企业微信成功响应内容为得到的明文消息内容(不能加引号，不能带bom头，不能带换行符)
			echo $sMsg;
			exit;

		} else {

			// 记录错误日志
			error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "errCode：". $decryptRes['errCode']. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/callbackCustomerEvent_errCode_".date('Ymd').".txt");

			print("ERR: " . $decryptRes['errCode'] . "\n\n");
			//exit(-1);
		}
	}

	/**
	 * 接收通讯录变更回调事件
	 * 
	 * 【注意】
	 * 需配置备案主体与当前企业主体相同或有关联关系的域名。
	 * 代开发自建应用会默认回调通讯录变更事件。事件回调到代开发应用回调URL上。
	 * 
	 * 访问URL形如： http://test.api.wxwork.2y9y.com/api/index.php?m=index&a=callbackEmployeeEvent
	 * 
	 * 重定向访问URL：http://test.api.wxwork.2y9y.com/api/callbackEmployeeEvent
	 */
	public function callbackEmployeeEvent()
	{
		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$token = "Fo9qMlTosqsvv1VSjy4WU8f";
		$encodingAESKey = "Fz97dNaSM5HFGlBLBjdsVtmnbuDFjL6PzSF8KJF2gwY";

		load('model.customer.business');
		$this->_customer_business_model = new business($token, $encodingAESKey);

		// 企业微信验证回调URL
		$this->_customer_business_model->verifyURL($this->_REQUEST);exit;

	}

	/**
	 * 接收会话存档回调事件
	 * 
	 * 【说明】
	 * 企业收到或发送新消息时，将以事件的形式推送到指定的url。触发事件的最小时间间隔为15秒。
	 * 
	 * 访问URL形如： http://test.api.wxwork.2y9y.com/api/index.php?m=index&a=callbackConversation
	 * 重定向访问URL：http://test.api.wxwork.2y9y.com/api/callbackConversation
	 * 
	 */
	public function callbackConversation()
	{
		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$token = "wJxuUajwGameUfuVv57qCiG";
		$encodingAESKey = "vaXY088EkdAFaXgk1OuME7uNqtecasYfySKAxeNm1Yj";

		load('model.customer.business');
		$this->_customer_business_model = new business($token, $encodingAESKey);

		// 企业微信验证回调URL
		$this->_customer_business_model->verifyURL($this->_REQUEST);exit;
	}

	/**
	 * 网页授权登录回调
	 * 
	 * 访问URL形如： http://test.api.wxwork.2y9y.com/api/index.php?m=index&a=callbackWebLogin
	 * 重定向访问URL：http://test.api.wxwork.2y9y.com/api/callbackWebLogin
	 * 
	 */
	public function callbackWebLogin()
	{
		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$token = "wJxuUajwGameUfuVv57qCiG";
		$encodingAESKey = "vaXY088EkdAFaXgk1OuME7uNqtecasYfySKAxeNm1Yj";

		load('model.customer.business');
		$this->_customer_business_model = new business($token, $encodingAESKey);

		// 企业微信验证回调URL
		$this->_customer_business_model->verifyURL($this->_REQUEST);exit;
	}


}