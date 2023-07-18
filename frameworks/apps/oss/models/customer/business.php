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
	public $_customer_database_model;
	public $_access_token;
	public $_workWeixin;

	function __construct() {

		$corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录

        // 实例化数据访问对象
		$this->_customer_database_model = getInstance('model.customer.database');

        // 实例化企业微信相关接口对象
        require_once APP_PATH.'models/api/workWeixin.php';
        $this->_workWeixin = new workWeixin($corpId, $appSecret);

        // 获取access_token
		// $this->_access_token = $this->getToken();
    }

	/**
	 * 根据scrm平台角色名称获取对应的企业成员userid
	 */
	public function getAgentUid($uid)
	{
		return $this->_customer_database_model->getUidGroup($uid);
	}

	/**
	 * 我的客户列表
	 * 
	 * @param [string]  $userid 企业成员ID
     * @param [int] $offset 分页记录起点
     * @param [int] $row 分页记录总数
     * @return [array]
	 */
	public function myCustomerList($userid, $offset, $row)
	{
		// 根据企业成员的userid查询客户数据表
		$customerData = $this->_customer_database_model->getFollowCustomers($userid, $offset, $row);

		foreach ($customerData as $key => $value) {
            // 时间戳转为日期
            $customerData[$key]['createtime'] = $value['createtime'] ? date("Y-m-d H:i:s", $value['createtime']) : "";
            $customerData[$key]['restart_createtime'] = $value['restart_createtime'] ? date("Y-m-d H:i:s", $value['restart_createtime']) : "";
            $customerData[$key]['updatetime'] = $value['updatetime'] ? date("Y-m-d H:i:s", $value['updatetime']) : "";
            $customerData[$key]['follow_deletetime'] = $value['follow_deletetime'] ? date("Y-m-d H:i:s", $value['follow_deletetime']) : "";
            $customerData[$key]['customer_deletetime'] = $value['customer_deletetime'] ? date("Y-m-d H:i:s", $value['customer_deletetime']) : "";
            $customerData[$key]['transfertime'] = $value['transfertime'] ? date("Y-m-d H:i:s", $value['transfertime']) : "";
            $customerData[$key]['old_transfertime'] = $value['old_transfertime'] ? date("Y-m-d H:i:s", $value['old_transfertime']) : "";
            $customerData[$key]['transfer_success_time'] = $value['transfer_success_time'] ? date("Y-m-d H:i:s", $value['transfer_success_time']) : "";
            $customerData[$key]['agree_createtime'] = $value['agree_createtime'] ? date("Y-m-d H:i:s", $value['agree_createtime']) : "";
            // 备注手机号换行显示
            $customerData[$key]['remark_mobiles'] = str_replace(";", "<br>", $value['remark_mobiles']);
        }

		// 获取企业成员的客户列表总记录数
		$customersCount = $this->_customer_database_model->getFollowCustomersCount($userid);

		$data = array(
            'customerData' => $customerData ? $customerData : array(),
            'customersCount' => $customersCount ? $customersCount : 0
        );

        return $data;
	}

    /**
     * 客户列表[当前方法已弃用]
     * 
     * @param string||array  $userid 企业成员ID
     * @param int $offset 分页记录起点
     * @param int $row 分页记录总数
     * @return array
     * 
     */
    public function customerList($userid, $offset, $row)
    {
        if ( is_array($userid) ) {
			// 超管角色组：可以查看整个企业下的所有开通客户联系权限的企业成员微信下的客户信息

			foreach ($userid as $key => $value) {

				// 根据企业微信的userid查询客户数据表中并只获取一条记录，如果连一条记录都不存在，则调用渲染单个成员获取客户详情接口，获取到客户信息后保存到客户数据表
				$followCustomerSingle = $this->_customer_database_model->getFollowCustomerSingle($value);

				// echo "<pre>";
				// var_dump($value);
				// var_dump($followCustomerSingle);
				// exit;

				// 客户数据表不存在当前企业成员跟进的客户微信信息，则调用渲染单个成员获取客户详情接口，获取到客户信息后保存到客户数据表
				if (!$followCustomerSingle) {

					// echo "<pre>";
					// var_dump($value);
					// echo "--------------------------------";

					// 获取单个企业成员微信下的所有客户信息，结果为二维数组，并且保存到客户数据表中
					$this->getExternalcontact($value);
				}

			}

			// 获取客户数据表中所有客户信息
			$customerData = $this->_customer_database_model->getAllCustomers($offset, $row);

			// 获取客户列表总记录数
			$customersCount = $this->_customer_database_model->getAllCustomersCount();

			// echo "<pre>";
			// var_dump($customerData);
			// exit;

		} elseif ( !is_array($userid) && $userid ) {
			// 普通企业成员：只能查看自己企业微信下的客户信息

			// 根据企业成员的userid查询客户数据表
			$customerData = $this->_customer_database_model->getFollowCustomers($userid, $offset, $row);

			// echo "<pre>";
			// var_dump($customerData);
			// exit;
			

			// 判断客户数据表中是否存在当前企业成员下的客户信息，如果为空，表示客户数据表中不存在当前企业成员的客户信息，可能是企业成员下没有客户或者是第一次登录crm平台
			if ( empty($customerData) ) {

				// echo "<pre>";
				// var_dump($userid);
				// echo "--------------------------------";
                // exit;

				// 获取单个企业成员微信下的所有客户信息，结果为二维数组，并且保存到客户数据表中
				$this->getExternalcontact($userid);

				// 再次根据企业成员的userid查询客户数据表
				$customerData = $this->_customer_database_model->getFollowCustomers($userid, $offset, $row);
			}

			// 获取企业成员的客户列表总记录数
			$customersCount = $this->_customer_database_model->getFollowCustomersCount($userid);
			
			// echo "<pre>";
			// var_dump($customerData);
			// exit;

		} elseif ( empty($userid) ) {
      
            // 直接读取客户数据表

            // 获取客户数据表中所有客户信息
			$customerData = $this->_customer_database_model->getAllCustomers($offset, $row);

			// 获取客户列表总记录数
			$customersCount = $this->_customer_database_model->getAllCustomersCount();
        }

        foreach ($customerData as $key => $value) {
            // 时间戳转为日期
            $customerData[$key]['createtime'] = $value['createtime'] ? date("Y-m-d H:i:s", $value['createtime']) : "";
            $customerData[$key]['restart_createtime'] = $value['restart_createtime'] ? date("Y-m-d H:i:s", $value['restart_createtime']) : "";
            $customerData[$key]['updatetime'] = $value['updatetime'] ? date("Y-m-d H:i:s", $value['updatetime']) : "";
            $customerData[$key]['follow_deletetime'] = $value['follow_deletetime'] ? date("Y-m-d H:i:s", $value['follow_deletetime']) : "";
            $customerData[$key]['customer_deletetime'] = $value['customer_deletetime'] ? date("Y-m-d H:i:s", $value['customer_deletetime']) : "";
            $customerData[$key]['transfertime'] = $value['transfertime'] ? date("Y-m-d H:i:s", $value['transfertime']) : "";
            $customerData[$key]['old_transfertime'] = $value['old_transfertime'] ? date("Y-m-d H:i:s", $value['old_transfertime']) : "";
            $customerData[$key]['transfer_success_time'] = $value['transfer_success_time'] ? date("Y-m-d H:i:s", $value['transfer_success_time']) : "";
            $customerData[$key]['agree_createtime'] = $value['agree_createtime'] ? date("Y-m-d H:i:s", $value['agree_createtime']) : "";
            // 备注手机号换行显示
            $customerData[$key]['remark_mobiles'] = str_replace(";", "<br>", $value['remark_mobiles']);
        }

        // echo "<pre>";
        // var_dump($customerData);
        // exit;

        $data = array(
            'customerData' => $customerData ? $customerData : array(),
            'customersCount' => $customersCount ? $customersCount : 0
        );

        return $data;
    }

    /**
	 * 渲染单个成员获取客户详情
	 * 
	 * @param mixed $access_token 
	 * @param mixed $name 数组类型，企业成员的userid列表，一次最多支持100个
	 * 
	 */
	public function getExternalcontact($userid)
	{

		// echo "<pre>";
		// var_dump($this->_access_token);
		// var_dump($userid);
		// exit;

		if ($userid) {

			// 获取单个企业成员的客户详情
			$externalcontactDetail = $this->getExternalcontactDetail($userid);
			
			// echo "<pre>";
			// var_dump($externalcontactDetail);
			// exit;

			// 判断当前企业成员微信下是否存在客户微信
			if (!empty($externalcontactDetail)) {

				foreach ($externalcontactDetail as $key => $value) {

					// 外部联系人性别 0-未知 1-男性 2-女性
					if ($value["external_contact"]["gender"] == 1) {
						$gender = "男性";
					} elseif ($value["external_contact"]["gender"] == 2) {
						$gender = "女性";
					} else {
						$gender = "未知";
					}

					// 外部联系人的类型，1表示该外部联系人是微信用户，2表示该外部联系人是企业微信用户
					if ($value["external_contact"]["type"] == 1) {
						$type = "微信用户";
	
						$remark_corp_name = $value["follow_user"][0]["remark_corp_name"] ? $value["follow_user"][0]["remark_corp_name"] : ""; // 微信客户备注的企业名称（仅微信客户有该字段）
					} elseif ($value["external_contact"]["type"] == 2) {
						$type = "企业微信用户";
	
						$remark_corp_name = ""; // 微信客户备注的企业名称（仅微信客户有该字段）
					}
	
					// 备注手机号，类型为array，可能存在多个手机号
					if ( !empty($value["follow_user"][0]["remark_mobiles"]) ) {
						$remark_mobiles_string = implode(";", $value["follow_user"][0]["remark_mobiles"]);
						$remark_mobiles = rtrim($remark_mobiles_string, ";");
					} else {
						$remark_mobiles = "";
					}
	
					// 标签
					// if (!empty($value["follow_user"][0]["tags"])) {
					// 	if ($value["follow_user"][0]["tags"][0]["type"] == 1) {
					// 		// 企业标签
					// 		$tag_name1 = $value["follow_user"][0]["tags"][0]["tag_name"];
					// 	} elseif ($value["follow_user"][0]["tags"][0]["type"] == 2) {
					// 		// 个人标签：企业成员对外部联系人的自定义标签
					// 		$tag_name2 = $value["follow_user"][0]["tags"][0]["tag_name"];
					// 	}
	
					// 	if ($value["follow_user"][0]["tags"][1]["type"] == 1) {
					// 		// 企业标签
					// 		$tag_name1 = $value["follow_user"][0]["tags"][1]["tag_name"];
					// 	} elseif ($value["follow_user"][0]["tags"][1]["type"] == 2) {
					// 		// 个人标签：企业成员对外部联系人的自定义标签
					// 		$tag_name2 = $value["follow_user"][0]["tags"][1]["tag_name"];
					// 	}
					// } else {
					// 	$tag_name1 = "";
					// 	$tag_name2 = "";
					// }
                    
                    // 标签
                    $tags = $value["follow_user"][0]["tags"];
                    $tag_name1 = "";
                    $tag_name2 = "";
                    if ( !empty($tags) ) {
                        foreach ($tags as $k=> $v) {
                            if ($v['type'] == 1) {
                                // 企业标签
                                $tag_name1 .= $v['tag_name'].";";
                            } elseif($v['type'] == 2) {
                                // 个人标签：企业成员对外部联系人的自定义标签
                                $tag_name2 .= $v['tag_name'].";";
                            }
                        }
                        $tag_name1 = rtrim($tag_name1, ";");
                        $tag_name2 = rtrim($tag_name2, ";");
                    }
	
					// 获取客户来源
					$add_way = $this->getAddWay($value["follow_user"][0]["add_way"]);

					$customerArray = array(
						// 客户名字
						"name" => $value["external_contact"]["name"],
						// 客户userid，外部联系人userid
						"external_userid" => $value["external_contact"]["external_userid"],
						// 客户头像
						"avatar" => $value["external_contact"]["avatar"],
						// 性别 
						"gender" => $gender,
						// 所属人
						"follow_userid" => $value["follow_user"][0]["userid"],
						// 客户类型   
						"type" => $type,
						// 客户状态：正常，被员工删除，被客户删除，双向删除，重新发送请求添加成功，默认正常
						"state" => "正常",
						// 客户来源   
						"add_way" => $add_way,
						// 企业标签
						"tag_name1" => $tag_name1,
						// 个人标签
						"tag_name2" => $tag_name2,
						// 备注名称
						"remark" => $value["follow_user"][0]["remark"] ? $value["follow_user"][0]["remark"] : "",
						// 备注手机号
						"remark_mobiles" => $remark_mobiles,
						// 备注企业名称
						"remark_corp_name" => $remark_corp_name,
						// 描述
						"description" => $value["follow_user"][0]["description"] ? $value["follow_user"][0]["description"] : "",
						// 公司简称
						"corp_name" => $value["external_contact"]["corp_name"] ? $value["external_contact"]["corp_name"] : "",
						// 公司全称
						"corp_full_name" => $value["external_contact"]["corp_full_name"] ? $value["external_contact"]["corp_full_name"] : "",
						// 创建时间
						// "createtime" => date("Y-m-d H:i:s", $value["follow_user"][0]["createtime"]),
						"createtime" => $value["follow_user"][0]["createtime"], // 保存时间戳到数据库
						// 修改时间，默认为创建时间
						// "updatetime" => date("Y-m-d H:i:s", $value["follow_user"][0]["createtime"])
						// "updatetime" => $value["follow_user"][0]["createtime"]
					);
					$customerData[] = $customerArray;

                    // echo "<pre>";
                    // var_dump($customerArray);

			        // error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "customerArray: ". json_encode($customerArray). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/oss_setFollowCustomers_".date('Ymd').".txt");
                

					// 保存数据到客户数据表
					$this->_customer_database_model->setFollowCustomers($customerArray);
	
				}

                // exit;

			} else {
				// 当前企业成员微信下没有客户微信
				$customerData = array();
			}
            
			// 结果为二维数组
			return $customerData;
		}
		
	}

    /**
	 * 获取单个企业成员的客户详情
	 */
	public function getExternalcontactDetail($userid)
	{

		// echo "<pre>";
		// var_dump($this->_access_token);
		// var_dump($userid);
		// exit;

		// 获取外部联系人的userid列表
		$externalUseridList = $this->_workWeixin->getExternalUserid($userid);

		// 获取客户详情
		if ($externalUseridList) {

			$externalcontactDetail = array();
			foreach ($externalUseridList as $key => $value) {
				$externalcontactDetail[] = $this->_workWeixin->externalcontact($value, "");
			}
			return $externalcontactDetail;
			
		} else {
			// 当前企业成员微信下没有客户微信
			return array();
		}
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
	 * 获取配置了客户联系功能的成员列表
	 * 
	 * @param mixed $access_token 调用接口凭证
	 */
	public function getFollowuserList()
	{
		return $this->_workWeixin->getFollowuserList();
	}

    /**
	 * 修改客户备注信息
	 */
	public function updateCustomerRemark($requestData)
	{
		
		// echo "<pre>";
		// var_dump($requestData);
		// var_dump($this->_uid);
		// exit;

		// switch ($requestData['op']) {
		// 	case 'edit':
		// 		# code...
		// 		break;
			
		// 	default:
		// 		# code...
		// 		break;
		// }
		

		// 获取当前用户所属的角色组信息
		// $customerList_model = getInstance('model.customer.customerList');
		// $gidarr = $customerList_model->getUidGroup($this->_uid);
		// $gid = intval($gidarr['gid']);

		// 获取access_token
		// $access_token = $this->getToken($corpId, $appSecret);

		// 客户userid
		$external_userid = trim($requestData["external_userid"]);

		// 客户所属企业用户ID
		$follow_userid = trim($requestData["follow_userid"]);

		// 获取当前客户详情信息
		// $customerData = $this->_workWeixin->externalcontact($external_userid, "");

        // 从客户数据表获取当前客户详情信息
        $customerData = $this->_customer_database_model->getSingleCustomerData($follow_userid, $external_userid);
        
		// echo "<pre>";
		// var_dump($customerData);
		// exit;
		
		// $type = $customerData["external_contact"]["type"]; // 外部联系人的类型，1表示该外部联系人是微信用户，2表示该外部联系人是企业微信用户
		// $remark = $customerData["follow_user"][0]["remark"];
		// $remark_corp_name = $customerData["follow_user"][0]["remark_corp_name"];
		// $remark_mobiles = (count($customerData["follow_user"][0]["remark_mobiles"]) > 1) ? implode(";", $customerData["follow_user"][0]["remark_mobiles"]) : $customerData["follow_user"][0]["remark_mobiles"][0];
		// $description = $customerData["follow_user"][0]["description"];

        $data = array(
            'external_userid' => $customerData["external_userid"],
            'follow_userid' => $customerData["follow_userid"],
            'type' => $customerData["type"],
            'remark' => $customerData["remark"],
            'remark_corp_name' => $customerData["remark_corp_name"],
            'remark_mobiles' => $customerData["remark_mobiles"],
            'description' => $customerData["description"]
        );

		return $data;
	}

    /**
	 * 上报修改信息到企业微信
     * 
     * 【说明】
     * 目前在crm后台修改客户信息后，企业微信也会发送当前事件的回调，这样会出现2次修改客户数据表信息的情况。
     * 针对重复修改客户数据表信息的问题，crm后台修改客户信息后不再修改客户数据表信息，等接收到企业微信对当前事件的回调信息后，再修改客户数据表信息。
     * 所以crm后台修改客户信息后，只上报修改信息到企业微信。
     * 
	 */
	public function saveCustomerRemark($requestData)
	{
		// echo "<pre>";
		// var_dump($requestData);
		// exit;

		if (!empty($requestData["form"])) {

			$formData = $requestData["form"];

			$remark_mobiles_one = trim($formData["remark_mobiles"]); // 清除字符串左右空格
			$remark_mobiles_two = str_replace("；", ";", $remark_mobiles_one); // 中文分号替换为英文分号
			$remark_mobiles_str = trim($remark_mobiles_two, ";"); // 清除字符串左右英文分号
			if ( strpos($remark_mobiles_str, ";") !== false ) {
				// 存在多个手机号
				$remark_mobiles = explode(";", $remark_mobiles_str);
			} else {
				// 只有一个手机号
				$remark_mobiles = array($remark_mobiles_str);
			}

            // // 上报修改信息到企业微信
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/remark?access_token=".$this->_access_token;

			// // $body = '{
			// // 	"userid":"zhangsan",
			// // 	"external_userid":"woAJ2GCAAAd1asdasdjO4wKmE8Aabj9AAA",
			// // 	"remark":"备注信息",
			// // 	"description":"描述信息",
			// // 	"remark_company":"腾讯科技",
			// // 	"remark_mobiles":[
			// // 		"13800000001",
			// // 		"13800000002"
			// // 	],
			// // 	"remark_pic_mediaid":"MEDIAID"
			// // }';

            $bodyArray = array(
                "userid" => $formData["follow_userid"],
                "external_userid" => $formData["external_userid"],
                "remark" => $formData["remark"],
                "description" => $formData["description"],
                "remark_company" => $formData["remark_corp_name"],
                "remark_mobiles" => $remark_mobiles,
            );
			// $body = json_encode($bodyArray) ;

			// $resJson = httpRequest($url, $body, '', 'json');
			// $res = json_decode($resJson, true);

            // 修改客户备注信息
            $res = $this->_workWeixin->updateCustomerRemark($bodyArray);

			// echo "<pre>";
			// var_dump($res);
			// exit;

			// {
			// 	"errcode": 0,
			// 	"errmsg": "ok"
			// }

            /*
            
            */

			// 保存修改信息到客户数据表中
            // $bodyArray['remark_mobiles'] = $remark_mobiles_str;
            // $bodyArray['updatetime'] = time();
            // $this->_customer_database_model->updateCustomerData($bodyArray);

            return $res;
		}
	}

    /**
	 * 渲染批量获取客户详情（目前没有使用）
	 * 
	 * @param mixed $access_token 
	 * @param mixed $name 数组类型，企业成员的userid列表，一次最多支持100个
	 * 
	 */
	public function getBatchExternalcontact($userid)
	{
		if ($userid) {
			// 批量获取客户详情
			$externalcontactDetail = $this->_workWeixin->batchGetExternalcontact($userid);

			// echo "<pre>";
			// var_dump($externalcontactDetail);
			// exit;

			foreach ($externalcontactDetail as $key => $value) {

				// 外部联系人性别 0-未知 1-男性 2-女性
				if ($value["external_contact"]["gender"] == 1) {
					$gender = "男性";
				} elseif ($value["external_contact"]["gender"] == 2) {
					$gender = "女性";
				} else {
					$gender = "未知";
				}

				// 外部联系人的类型，1表示该外部联系人是微信用户，2表示该外部联系人是企业微信用户
				if ($value["external_contact"]["type"] == 1) {
					$type = "微信用户";

					$remark_corp_name = $value["follow_info"]["remark_corp_name"] ? $value["follow_info"]["remark_corp_name"] : ""; // 微信客户备注的企业名称（仅微信客户有该字段）
				} elseif ($value["external_contact"]["type"] == 2) {
					$type = "企业微信用户";

					$remark_corp_name = ""; // 微信客户备注的企业名称（仅微信客户有该字段）
				}

				// 备注手机号，类型为array，可能存在多个手机号
				if ( !empty($value["follow_info"]["remark_mobiles"]) ) {
					$remark_mobiles_string = implode("<br>", $value["follow_info"]["remark_mobiles"]);
					$remark_mobiles = rtrim($remark_mobiles_string, "<br>");
				} else {
					$remark_mobiles = "";
				}

				// echo "<pre>";
				// var_dump($remark_mobiles);
				
				// exit;

				// 标签：批量获取客户详情情况下，标签信息只会返回企业标签和规则组标签的tag_id，个人标签将不再返回，个人标签没有标签ID
				if (!empty($value["follow_info"]["tag_id"])) {

					// 类型为array
					$tag_id_array = $value["follow_info"]["tag_id"];

					// 根据tag_id企业标签ID查找对应的标签名称

					$tag_name1 = $tag_id_array[0];
					$tag_name2 = "";
				} else {
					$tag_name1 = "";
					$tag_name2 = "";
				}

				// 获取客户来源
				$add_way = $this->getAddWay($value["follow_info"]["add_way"]);

				$customerData[] = array(
					// 客户名字
					"name" => $value["external_contact"]["name"],
					// 客户userid，外部联系人userid
					"external_userid" => $value["external_contact"]["external_userid"],
					// 客户头像
					"avatar" => $value["external_contact"]["avatar"],
					// 性别 
					"gender" => $gender,
					// 所属人
					"follow_userid" => $value["follow_info"]["userid"],
					// 客户类型   
					"type" => $type,
					// 客户状态：正常，被员工删除，被客户删除，双向删除，重新发送请求添加成功，默认正常
					"state" => "正常",
					// 客户来源   
					"add_way" => $add_way,
					// 企业标签
					"tag_name1" => $tag_name1,
					// 个人标签
					"tag_name2" => $tag_name2,
					// 备注名称
					"remark" => $value["follow_info"]["remark"] ? $value["follow_info"]["remark"] : "",
					// 备注手机号
					"remark_mobiles" => $remark_mobiles,
					// 备注公司名称
					"remark_corp_name" => $remark_corp_name,
					// 描述
					"description" => $value["follow_info"]["description"] ? $value["follow_info"]["description"] : "",
					// 公司简称
					"corp_name" => $value["external_contact"]["corp_name"] ? $value["external_contact"]["corp_name"] : "",
					// 公司全称
					"corp_full_name" => $value["external_contact"]["corp_full_name"] ? $value["external_contact"]["corp_full_name"] : "",
					// 创建时间
					// "createtime" => date("Y-m-d H:i:s", $value["follow_info"]["createtime"]),
					"createtime" => $value["follow_info"]["createtime"], // 保存时间戳到数据库
					// 修改时间，默认为创建时间
					// "updatetime" => date("Y-m-d H:i:s", $value["follow_info"]["createtime"])
					// "updatetime" => $value["follow_info"]["createtime"]
				);
			}

			// echo "<pre>";
			// var_dump($customerData);
			// exit;

			return $customerData;
		}
	}

    /**
	 * 在职继承
	 */
	public function transferCustomer($requestData)
	{
		// echo "<pre>";
		// var_dump($requestData);
		// exit;

		// 客户userid
		$external_userid = trim($requestData["external_userid"]);

		// 客户所属企业用户ID
		$follow_userid = trim($requestData["follow_userid"]);

        // 从客户数据表获取当前客户详情信息
        $customerData = $this->_customer_database_model->getSingleCustomerData($follow_userid, $external_userid);
        
		// echo "<pre>";
		// var_dump($customerData);
		// exit;

        if ($customerData["state"] == "正在转接中") {
			ShowMsg('客户正在转接中，无需重复操作！', '/index.php?m=customer&a=allCustomerList');
		}

		if ($customerData["state"] == "被员工删除") {
			ShowMsg('客户已被员工删除，不能转接！', '/index.php?m=customer&a=allCustomerList');
		}

		if ($customerData["state"] == "被客户删除") {
			ShowMsg('已被客户删除，不能转接！', '/index.php?m=customer&a=allCustomerList');
		}

		if ($customerData["state"] == "双向删除") {
			ShowMsg('双向删除的客户，不能转接！', '/index.php?m=customer&a=allCustomerList');
		}

        $currentTime = time();
		$agreedTime = $customerData["old_transfertime"] + 7776000; // 前2次在职转接时间+90天时间戳
		if ($currentTime < $agreedTime) {
			ShowMsg('客户转接过于频繁（90个自然日内，在职成员的每位客户仅可被转接2次）！', '/index.php?m=customer&a=allCustomerList', '', 3000);
		}

        $data = array(
            'external_userid' => $customerData["external_userid"],
            'follow_userid' => $customerData["follow_userid"],
            'name' => $customerData["name"],
            'state' => $customerData["state"],
            'old_transfertime' => $customerData["old_transfertime"]
        );

		return $data;
	}

    /**
	 * 更新单个客户的跟进企业成员，上报企业微信
	 */
	public function saveTransferCustomer($requestData)
	{
        // echo "<pre>";
		// var_dump($requestData);
		// exit;

        $follow_userid = $requestData['follow_userid'];
        $new_follow_userid = $requestData['new_follow_userid'];

        // 针对可能出现重复客户情况，判断转接的客户是否已经在接替企业成员微信的好友列表的下，存在提示：客户已在接替企业成员微信的好友列表中，无需再转接！
		$transferCustomerData = $this->_customer_database_model->getSingleCustomerData($new_follow_userid, $requestData['external_userid']);
        if ($transferCustomerData) {
            $returnData = array(
                array(
                    'errcode' => 10000
                )
            );
            return $returnData;
        }

        // 获取客户详情
        $customerData = $this->_customer_database_model->getSingleCustomerData($follow_userid, $requestData['external_userid']);

        $currentTime = time();
		$agreedTime = $customerData["old_transfertime"] + 7776000; // 前2次在职转接时间+90天时间戳
		if ($currentTime < $agreedTime) {
            // 客户转接过于频繁（90个自然日内，在职成员的每位客户仅可被转接2次）
            $returnData = array(
                array(
                    'errcode' => 10001
                )
            );
            return $returnData;
        } else {
            // 当前时间已经超过记录的前2次在职转接时间90天，最近在职转接时间变为前2次在职转接时间
            $old_transfertime = $customerData["transfertime"];
        }

        $data = array(
            "handover_userid" => $follow_userid,
            "takeover_userid" => $new_follow_userid,
            "external_userid" => array($requestData["external_userid"]), // 类型为数组，客户的external_userid列表，每次最多分配100个客户
            // "transfer_success_msg" => "您好，您的服务已升级，后续将由我的其他同事接替我的工作，继续为您服务。"
        );

        // echo "<pre>";
		// var_dump($requestData);
		// var_dump($data);
		// exit;

        // 分配在职成员的客户
        $customerArray = $this->_workWeixin->transferCustomer($data);
        
        //     array(1) {
        //       [0]=>
        //       array(2) {
        //         ["errcode"]=>
        //         int(0)
        //         ["external_userid"]=>
        //         string(32) "wmFrvHCQAAWBOh3HVu3OCzcFL-G6KcQQ"
        //       }
        //     }

        $state = "正在转接中";

        foreach ($customerArray as $key => $value) {
            if ($value["errcode"] == 0) {
                // 修改客户状态为“正在转接中”，最近在职转接时间变为前2次在职转接时间
                $this->_customer_database_model->updateCustomerState($follow_userid, $new_follow_userid, $value["external_userid"], $state, $currentTime, $old_transfertime);
            }
        }

        return $customerArray;
    }

    /**
     * 离职继承
     */
    public function dimissionTransfer($requestData)
    {
        // 客户userid
		$external_userid = trim($requestData["external_userid"]);

		// 客户所属企业用户ID
		$follow_userid = trim($requestData["follow_userid"]);

        // 获取员工状态，仅限对离职状态员工的客户操作离职继承

    }

	/**
	 * 团队客户列表
	 * 
     * @param [int] $offset 分页记录起点
     * @param [int] $row 分页记录总数
     * @return [array]
	 * 
	 */
	public function allCustomerList($offset, $row)
	{
		// 获取客户数据表中所有客户信息
		$customerData = $this->_customer_database_model->getAllCustomers($offset, $row);

		foreach ($customerData as $key => $value) {
            // 时间戳转为日期
            $customerData[$key]['createtime'] = $value['createtime'] ? date("Y-m-d H:i:s", $value['createtime']) : "";
            $customerData[$key]['restart_createtime'] = $value['restart_createtime'] ? date("Y-m-d H:i:s", $value['restart_createtime']) : "";
            $customerData[$key]['updatetime'] = $value['updatetime'] ? date("Y-m-d H:i:s", $value['updatetime']) : "";
            $customerData[$key]['follow_deletetime'] = $value['follow_deletetime'] ? date("Y-m-d H:i:s", $value['follow_deletetime']) : "";
            $customerData[$key]['customer_deletetime'] = $value['customer_deletetime'] ? date("Y-m-d H:i:s", $value['customer_deletetime']) : "";
            $customerData[$key]['transfertime'] = $value['transfertime'] ? date("Y-m-d H:i:s", $value['transfertime']) : "";
            $customerData[$key]['old_transfertime'] = $value['old_transfertime'] ? date("Y-m-d H:i:s", $value['old_transfertime']) : "";
            $customerData[$key]['transfer_success_time'] = $value['transfer_success_time'] ? date("Y-m-d H:i:s", $value['transfer_success_time']) : "";
            $customerData[$key]['agree_createtime'] = $value['agree_createtime'] ? date("Y-m-d H:i:s", $value['agree_createtime']) : "";
            // 备注手机号换行显示
            $customerData[$key]['remark_mobiles'] = str_replace(";", "<br>", $value['remark_mobiles']);
        }

		// 获取客户列表总记录数
		$customersCount = $this->_customer_database_model->getAllCustomersCount();

		$data = array(
            'customerData' => $customerData ? $customerData : array(),
            'customersCount' => $customersCount ? $customersCount : 0
        );

        return $data;
	}

	/**
     * 同步团队客户
     */
    public function syncAllCustomers($uid)
    {
    
        // 获取当前用户上次操作同步的时间，限制5分钟内操作一次，为了用户体验，操作时间间隔5分钟内的直接提示同步成功
        $getCrontab = $this->_customer_database_model->getUidCrontab($uid, 'syncAllCustomers');

		// echo "<pre>";
		// var_dump($getCrontab);
		// exit;

        // array(1) {
        //     [0]=>
        //     array(2) {
        //       ["createtime"]=>
        //       string(10) "1687264362"
        //       ["state"]=>
        //       string(1) "1"
        //     }
        //   }

        if ($getCrontab) {
            $current = time();
            $timeDifference = $current - $getCrontab[0]['createtime'];

            if ($getCrontab[0]['state'] == 2 && $timeDifference <= 300) {
                ShowMsg('同步完成！', '/index.php?m=customer&a=allCustomerList');
            }

            if ($getCrontab[0]['state'] == 1) {
                ShowMsg('同步中，请稍等！', '/index.php?m=customer&a=allCustomerList');
            }
        }

        // 保存数据到数据表
        $crontabArray = array(
            'uid' => $uid,
            'name' => '同步团队客户',
            'action' => 'syncAllCustomers',
            'state' => 1,
            'createtime' => time(),
        );
		$res = $this->_customer_database_model->setCrontabs($crontabArray);
        if ($res) {
            ShowMsg('同步中，请稍等！', '/index.php?m=customer&a=allCustomerList');
        } else {
            ShowMsg('同步失败！', '/index.php?m=customer&a=allCustomerList');
        }

    }

	/**
	 * 同步“我的”客户，即同步当前登录SCRM平台的企业成员跟进的客户
	 */
    public function synsMyCustomers($uid)
    {
    
        // 获取当前用户上次操作同步的时间，限制5分钟内操作一次，为了用户体验，操作时间间隔5分钟内的直接提示同步成功
        $getCrontab = $this->_customer_database_model->getUidCrontab($uid, 'synsMyCustomers');

		// echo "<pre>";
		// var_dump($getCrontab);
		// exit;

        // array(1) {
        //     [0]=>
        //     array(2) {
        //       ["createtime"]=>
        //       string(10) "1687264362"
        //       ["state"]=>
        //       string(1) "1"
        //     }
        //   }

        if ($getCrontab) {
            $current = time();
            $timeDifference = $current - $getCrontab[0]['createtime'];

            if ($getCrontab[0]['state'] == 2 && $timeDifference <= 300) {
                ShowMsg('同步完成！', '/index.php?m=customer&a=customerList');
            }

            if ($getCrontab[0]['state'] == 1) {
                ShowMsg('同步中，请稍等！', '/index.php?m=customer&a=customerList');
            }
        }

        // 保存数据到数据表
        $crontabArray = array(
            'uid' => $uid,
            'name' => '同步我的客户',
            'action' => 'synsMyCustomers',
            'state' => 1,
            'createtime' => time(),
        );
		$res = $this->_customer_database_model->setCrontabs($crontabArray);
        if ($res) {
            ShowMsg('同步中，请稍等！', '/index.php?m=customer&a=customerList');
        } else {
            ShowMsg('同步失败！', '/index.php?m=customer&a=customerList');
        }

    }
    

}