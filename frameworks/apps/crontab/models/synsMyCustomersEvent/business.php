<?php
/**
+----------------------------------------------------------
 * 封装企业员工相关计划任务对象
+----------------------------------------------------------
 * @author heyonzghen
 * @version 2023.7.6
+---------------------------------------------------------- 
 */
class business
{
    
    public $_common_database_model;
    public $_database_model;
	public $_access_token;
	public $_workWeixin;

    public function __construct() 
    {
        $corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录
		$contactSecret = "FSibQ2uBHVzxxc_xEJJopsFSy3qx5Q_bCcW4YzOxnKM"; // 使用通讯录同步专有的secret

		// 实例化公共数据访问对象
		$this->_common_database_model = getInstance('model.commonDB');

        // 实例化数据访问对象
		$this->_database_model = getInstance('model.synsMyCustomersEvent.database');

        // 实例化企业微信相关接口对象
        // require_once C('APP_LIST_PATH').'oss/models/api/workWeixin.php';
		load('@oss.model.api.workWeixin');
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret);
    }

    /**
     * 同步我的客户
	 * 
	 *【说明】
	 * 执行命令：/usr/bin/php /usr/share/nginx/html/wechatApp/www/crontab/index.php synsMyCustomersEvent
	 * 执行时间：每分钟定时执行一次 * * * * *
	 * 
     */
    public function synsMyCustomers()
    {
		// 获取需要执行的指定计划任务详情
		$crontabDetail = $this->_common_database_model->getCrontab('synsMyCustomers');

		// var_dump($crontabDetail);
		// exit;

		// 判断同步员工计划任务的状态是否1，1表示需要执行任务，2表示不需要执行或者执行完成
		if ( empty($crontabDetail[0]) ) {
			echo '当前没有可执行的任务';exit;
		}

        $uid = $crontabDetail[0]['uid'];

        // 根据计划任务操作者查找后台角色账号对应的企业成员userid
        $roleInfo = $this->_database_model->getRoleInfo($uid);
		$userid = $roleInfo['agent_uid'];

		// var_dump($userid);
		// exit;

        // 获取单个企业成员微信下的所有客户信息，结果为二维数组，并且保存到客户数据表中
        $this->getExternalcontact($userid);

		// 修改本次执行的计划任务状态为2，表示执行完成
		$this->_common_database_model->updateCrontabState($crontabDetail[0]['uid'], $crontabDetail[0]['action'], $crontabDetail[0]['state']);

        // echo "<pre>";
        // var_dump($employeeListArray);
        // exit;

		// 记录每天执行次数
		error_log("\n".date("[Y-m-d H:i:s]").":\n". 'times: '. 'success finished'. "\n\n", 3, C('DEDE_DATA_PATH')."logs/".$crontabDetail[0]['action']."_".date("ymd").".txt");

		echo "成功执行完成";exit;

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

                    // var_dump($customerArray);
                    // exit;

			        // error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "customerArray: ". json_encode($customerArray). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/oss_setFollowCustomers_".date('Ymd').".txt");
                

					// 保存数据到客户数据表
					$this->_database_model->setFollowCustomers($customerArray);
	
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
		// var_dump($userid);
		// exit;

		// 获取外部联系人的userid列表
		$externalUseridList = $this->_workWeixin->getExternalUserid($userid);

		// var_dump($userid);
		// var_dump($externalUseridList);
		// exit;

		// 获取客户详情
		if ($externalUseridList) {

			$externalcontactDetail = array();
			foreach ($externalUseridList as $key => $value) {
				$externalcontactDetail[] = $this->_workWeixin->externalcontact($value, "");
			}

            // var_dump($externalcontactDetail);
            // exit;

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

}