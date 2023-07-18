<?php
/**
+----------------------------------------------------------
 * 封装企业部门相关计划任务对象
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
        require_once C('APP_LIST_PATH').'crontab/models/syncDepartmentEvent/database.php';
        // load('@crontab.model.syncDepartmentEvent.database');
		$this->_database_model = new database();
		// $this->_database_model = getInstance('model.syncDepartmentEvent.database');

        // 实例化企业微信相关接口对象
        // require_once C('APP_LIST_PATH').'oss/models/api/workWeixin.php';
		load('@oss.model.api.workWeixin');
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret);
    }

    /**
	 * 同步部门
	 * 
	 *【说明】
	 * 执行命令：/usr/bin/php /usr/share/nginx/html/wechatApp/www/crontab/index.php syncDepartmentEvent
	 * 执行时间：每分钟定时执行一次 * * * * *
	 * 
	 */
	public function syncDepartments()
	{
		// 获取需要执行的指定计划任务详情
		$crontabDetail = $this->_common_database_model->getCrontab('syncDepartments');

		// var_dump($crontabDetail);
		// exit;

		// 判断同步部门计划任务的状态是否1，1表示需要执行任务，2表示不需要执行或者执行完成
		if ( empty($crontabDetail[0]) ) {
			echo '当前没有可执行的任务';exit;
		}

		// 获取部门ID列表
		$departmentIdArray = $this->_workWeixin->getDepartmentId();

		// var_dump($departmentIdArray);
		// exit;

        /*array(11) {
            [0]=>
            array(3) {
              ["id"]=>
              int(1)
              ["parentid"]=>
              int(0)
              ["order"]=>
              int(100000000)
            }
            [1]=>
            array(3) {
              ["id"]=>
              int(2)
              ["parentid"]=>
              int(1)
              ["order"]=>
              int(100000000)
            }
        }*/

        $departmentDetail = array();

        foreach ($departmentIdArray as $key => $value) {

			// 获取单个部门详情
		    $departmentDetail = $this->_workWeixin->getDepartmentDetail($value['id']);

            // var_dump($departmentDetail);
            // var_dump($departmentDetail['id']);
			// exit;

            // 查询部门信息
            $departmentData = $this->_database_model->getSingleDepartmentData($departmentDetail['id']);

			// var_dump($departmentData);
			// exit;

            if ($departmentData) {
				// 更新部门信息
				$this->_database_model->updateDepartment($departmentDetail);
                
            } else {
				
				// 部门数据表中不存在当前部门，则保存数据到部门数据表
				$departmentArray = array(
                    'departmentid' => $departmentDetail['id'],
                    'parentid' => $departmentDetail['parentid'],
                    'name' => $departmentDetail['name'],
                    'order' => $departmentDetail['order']
                );
				// var_dump($departmentArray);
				// exit;
                $this->_database_model->setDepartments($departmentArray);
			}
        }

		// 修改本次执行的计划任务状态为2，表示执行完成
		$this->_common_database_model->updateCrontabState($crontabDetail[0]['uid'], $crontabDetail[0]['action'], $crontabDetail[0]['state']);

        // echo "<pre>";
        // var_dump($departmentDetail);
        // exit;

		// 记录每天执行次数
        error_log("\n".date("[Y-m-d H:i:s]").":\n". 'times: '. 'success finished'. "\n\n", 3, C('DEDE_DATA_PATH')."logs/".$crontabDetail[0]['action']."_".date("ymd").".txt");

		echo "成功执行完成";exit;

	}
}