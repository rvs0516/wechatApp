<?php
/**
+----------------------------------------------------------
 * 封装企业员工相关计划任务对象
+----------------------------------------------------------
 * @author heyonzghen
 * @version 2023.7.5
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
		$this->_database_model = getInstance('model.syncEmployeeEvent.database');

        // 实例化企业微信相关接口对象
        // require_once C('APP_LIST_PATH').'oss/models/api/workWeixin.php';
		load('@oss.model.api.workWeixin');
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret);
    }

     /**
     * 同步员工
	 * 
	 * 【说明】
	 * 执行命令：/usr/bin/php /usr/share/nginx/html/wechatApp/www/crontab/index.php syncEmployeeEvent
	 * 执行时间：每分钟定时执行一次 * * * * *
	 * 
     */
    public function syncEmployees()
    {
		// 获取需要执行的指定计划任务详情
		$crontabDetail = $this->_common_database_model->getCrontab('syncEmployees');

		// var_dump($crontabDetail);
		// exit;

		// 判断同步员工计划任务的状态是否1，1表示需要执行任务，2表示不需要执行或者执行完成
		if ( empty($crontabDetail[0]) ) {
			echo '当前没有可执行的任务';exit;
		}

        // 获取企业成员的userid与对应的部门ID列表
		$employeeUserid = $this->_workWeixin->getEmployeeUserid();

		// var_dump($employeeUserid);
		// exit;

        // array(63) {
        //     [0]=>
        //     array(2) {
        //       ["userid"]=>
        //       string(7) "prolove"
        //       ["department"]=>
        //       int(1)
        //     }
        //     [1]=>
        //     array(2) {
        //       ["userid"]=>
        //       string(4) "Xing"
        //       ["department"]=>
        //       int(1)
        //     }
        // }

        $employeeListArray = array();
        
        foreach ($employeeUserid as $key => $value) {
            // 获取可见范围内的成员详情信息
            $employeeListArray[] = $this->_workWeixin->getEmployeeList($value['userid']);
        }

		// var_dump($employeeListArray);
		// exit;

        // 保存员工信息到员工数据表中
        foreach ($employeeListArray as $key => $value) {

			// 查询指定员工信息
			$employeeData = $this->_database_model->getSingleEmployeeData($value['userid']);

			// var_dump($employeeData);
			// exit;

			// 判断员工信息是否已存在于员工信息数据表中，存在则表示更新，不存在则表示新增
			if ($employeeData) {
				// 更新

				$this->_database_model->updateEmployee($value);
				
			} else {
				// 新增
	
				$employeeArray = array(
					'userid' => $value['userid'], // 成员UserID。对应管理端的帐号，企业内必须唯一。不区分大小写，长度为1~64个字节；第三方应用返回的值为open_userid
					'name' => $value['name'],
					'alias' => $value['alias'], // 别名，昵称
					'gender' => $value['gender'] ? $value['gender'] : 0,	// 性别。0表示未定义，1表示男性，2表示女性。代开发自建应用需要管理员授权且成员oauth2授权获取
					'avatar' => $value['avatar'] ? $value['avatar'] : '', // 头像url。 代开发自建应用需要管理员授权且成员oauth2授权获取
					'qr_code' => $value['qr_code'] ? $value['qr_code'] : '', // 员工个人二维码，扫描可添加为外部联系人(注意返回的是一个url，可在浏览器上打开该url以展示二维码)；代开发自建应用需要管理员授权且成员oauth2授权获取
					'mobile' => $value['mobile'] ? $value['mobile'] : 0, // 手机号码。代开发自建应用需要管理员授权才返回。	企业内必须唯一。若成员已激活企业微信，则需成员自行修改（此情况下该参数被忽略，但不会报错）
					'department' => $value['department'][0], // 成员所属部门id列表
					'position' => $value['position'], // 职务信息；代开发自建应用需要管理员授权才返回
					'status' => $value['status'], // 员工状态。激活状态: 1=已激活，2=已禁用，4=未激活，5=退出企业。已激活代表已激活企业微信或已关注微信插件（原企业号）。未激活代表既未激活企业微信又未关注微信插件（原企业号）。
					'createtime' => time(), // 创建时间
				);
	
				// var_dump($employeeArray);
				// exit;

				// 保存数据到客户数据表
				$this->_database_model->setEmployees($employeeArray);

			}
            
        }

		// 修改本次执行的计划任务状态为2，表示执行完成
		$this->_common_database_model->updateCrontabState($crontabDetail[0]['uid'], $crontabDetail[0]['action'], $crontabDetail[0]['state']);

        // echo "<pre>";
        // var_dump($employeeListArray);
        // exit;

		// 记录每天执行次数
		error_log("\n".date("[Y-m-d H:i:s]").":\n". 'times: '. 'success finished'. "\n\n", 3, C('DEDE_DATA_PATH')."logs/".$crontabDetail[0]['action']."_".date("ymd").".txt");

		echo "成功执行完成";exit;
    }

}