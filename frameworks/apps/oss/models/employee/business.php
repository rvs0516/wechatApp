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
	public $_employee_database_model;
	public $_access_token;
	public $_workWeixin;

	function __construct() {

		$corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录
		$contactSecret = "FSibQ2uBHVzxxc_xEJJopsFSy3qx5Q_bCcW4YzOxnKM"; // 使用通讯录同步专有的secret

        // 实例化数据访问对象
		$this->_employee_database_model = getInstance('model.employee.database');

        // 实例化企业微信相关接口对象
        require_once APP_PATH.'models/api/workWeixin.php';
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret);

    }

    /**
     * 员工列表
     */
    public function employeeList($offset, $row)
    {
        // 获取所有员工信息
        $employeeData = $this->_employee_database_model->getAllEmployees($offset, $row);

        // 格式化数据
        foreach ($employeeData as $key => $value) {
            $employeeData[$key]['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : "";
            $employeeData[$key]['updatetime'] = $value['updatetime'] ? date('Y-m-d H:i:s', $value['updatetime']) : "";
            $employeeData[$key]['mobile'] = $value['mobile'] ? $value['mobile'] : "";

            // 激活状态: 1=已激活，2=已禁用，4=未激活，5=退出企业。已激活代表已激活企业微信或已关注微信插件（原企业号）。未激活代表既未激活企业微信又未关注微信插件（原企业号）。
            switch ($value['status']) {
                case 1:
                    $employeeData[$key]['status'] = '已激活';
                    break;
                case 2:
                    $employeeData[$key]['status'] = '已禁用';
                    break;
                case 4:
                    $employeeData[$key]['status'] = '未激活';
                    break;
                case 5:
                    $employeeData[$key]['status'] = '退出企业'; // 表示已离职
                    break;
                default:
                    $employeeData[$key]['status'] = '未知';
                    break;
            }
             
        }
        
        // 获取员工列表总记录数
        $employeesCount = $this->_employee_database_model->getAllEmployeesCount();

        // echo "<pre>";
        // var_dump($employeeData);
        // var_dump($employeesCount);
        // exit;

        $data = array(
            'employeeData' => $employeeData ? $employeeData : array(),
            'employeesCount' => $employeesCount ? $employeesCount : 0
        );

        return $data;
    }

    /**
     * 同步员工
     */
    public function syncEmployees($uid)
    {
    
        // 获取当前用户上次操作同步员工的时间，限制5分钟内操作一次，为了用户体验，操作时间间隔5分钟内的直接提示同步成功
        $getCrontab = $this->_employee_database_model->getUidCrontab($uid, 'syncEmployees');

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

        // 保存数据到客户数据表
        $crontabArray = array(
            'uid' => $uid,
            'name' => '同步员工',
            'action' => 'syncEmployees',
            'state' => 1,
            'createtime' => time(),
        );
		$res = $this->_employee_database_model->setCrontabs($crontabArray);
        if ($res) {
            ShowMsg('同步中，请稍等！', '/index.php?m=employee&a=employeeList');
        } else {
            ShowMsg('同步失败！', '/index.php?m=employee&a=employeeList');
        }

    }

    /**
     * 变更员工部门信息
     */
    public function updateEmployeeDepartment($requestData)
    {
        // echo "<pre>";
        // var_dump($requestData);
        // exit;

        // 员工账号
		$userid = trim($requestData["userid"]);

        // 从员工数据表获取当前员工详情信息
        $employeeData = $this->_employee_database_model->getSingleEmployeeData($userid);
        
		// echo "<pre>";
		// var_dump($employeeData);
		// exit;

        $data = array(
            'userid' => $employeeData["userid"],
            'name' => $employeeData["name"],
            'alias' => $employeeData["alias"],
            'department' => $employeeData["department"],
            'position' => $employeeData["position"]
        );

		return $data;

    }

    /**
     * 获取部门列表
     */
    public function getAllDepartments()
    {
        return $this->_employee_database_model->getAllDepartments();
    }

    /**
     * 保存变更员工部门信息，同时上报企业微信
     */
    public function saveEmployeeDepartment($requestData)
    {
       
        // echo "<pre>";
        // var_dump($requestData);
        // exit;

        // 更新成员
        $data = array(
            'userid' => $requestData['form']['userid'],
            'department' => $requestData['form']['departmentid'],
            'position' => $requestData['form']['position'],
        );
        $updateRes = $this->_workWeixin->updateEmployee($data);

        // 更新成功后
        if ($updateRes) {
            // 保存更新内容到部门数据表
            $this->_employee_database_model->updateEmployee($data);

            ShowMsg('修改成功！', '/index.php?m=employee&a=employeeList');
        }

    }

}