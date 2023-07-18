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
	public $_department_database_model;
	public $_access_token;
	public $_workWeixin;

	function __construct() {

		$corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录
		$contactSecret = "FSibQ2uBHVzxxc_xEJJopsFSy3qx5Q_bCcW4YzOxnKM"; // 使用通讯录同步专有的secret

        // 实例化数据访问对象
		$this->_department_database_model = getInstance('model.department.database');

        // 实例化企业微信相关接口对象
        require_once APP_PATH.'models/api/workWeixin.php';
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret);

    }

    /**
     * 部门列表
     */
    public function departmentList($offset, $row)
    {
        // 获取所有部门信息
        $departmentData = $this->_department_database_model->getAllDepartments($offset, $row);
        
        // 获取部门列表总记录数
        $departmentsCount = $this->_department_database_model->getAllDepartmentsCount();

        // echo "<pre>";
        // var_dump($departmentData);
        // var_dump($departmentsCount);
        // exit;

        $data = array(
            'departmentData' => $departmentData ? $departmentData : array(),
            'departmentsCount' => $departmentsCount ? $departmentsCount : 0
        );

        return $data;

    }

    /**
     * 同步部门
     */
    public function syncDepartments($uid)
    {
    
        // 获取当前用户上次操作同步的时间，限制5分钟内操作一次，为了用户体验，操作时间间隔5分钟内的直接提示同步成功
        $getCrontab = $this->_department_database_model->getUidCrontab($uid, 'syncDepartments');

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
                ShowMsg('同步完成！', '/index.php?m=employee&a=employeeList');
            }

            if ($getCrontab[0]['state'] == 1) {
                ShowMsg('同步中，请稍等！', '/index.php?m=employee&a=employeeList');
            }
        }

        // 保存数据到数据表
        $crontabArray = array(
            'uid' => $uid,
            'name' => '同步部门',
            'action' => 'syncDepartments',
            'state' => 1,
            'createtime' => time(),
        );
		$res = $this->_department_database_model->setCrontabs($crontabArray);
        if ($res) {
            ShowMsg('同步中，请稍等！', '/index.php?m=employee&a=employeeList');
        } else {
            ShowMsg('同步失败！', '/index.php?m=employee&a=employeeList');
        }

    }

}