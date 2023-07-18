<?php
/**
+----------------------------------------------------------
* 员工管理控制器
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/

require_once APP_CONTROLLER_PATH . '/master.php';

class employeeController extends masterControl {

	public $_REQUEST;
	public $_employee_business_model;

	function __construct() {
        parent::__construct();

		$this->_REQUEST = $_REQUEST;

        // 引用员工业务逻辑模型
		$this->_employee_business_model = getInstance('model.employee.business');
    }

    /**
     * 员工列表
     */
    public function employeeList()
    {
        // 分页
		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$row = 15; // 偏移量，即每页记录条数
		$offset = ($page - 1) * $row; // 每页记录起点

        $employeeListData = $this->_employee_business_model->employeeList($offset, $row);

        // echo "<pre>";
        // var_dump($employeeListData);
        // exit;

        $this->assign('page', $page);
        $this->assign('row', $row);
		$this->assign('rowcount', $employeeListData["employeesCount"]); // 总记录数
		$this->assign('employeeList', $employeeListData["employeeData"]);

    }

    /**
     * 同步员工
     */
    public function syncEmployees()
    {
       
        $this->_employee_business_model->syncEmployees($this->_uid);
        
    }

    /**
     * 变更员工部门信息
     */
    public function updateEmployeeDepartment()
    {
        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

        // 从员工数据表获取当前员工详情信息
        $employeeData = $this->_employee_business_model->updateEmployeeDepartment($this->_REQUEST);

        // 获取部门列表
        $departmentList = $this->_employee_business_model->getAllDepartments();

        // echo "<pre>";
        // var_dump($employeeData);
        // exit;

		$this->assign('userid', $employeeData["userid"]);
		$this->assign('name', $employeeData["name"]);
		$this->assign('alias', $employeeData["alias"]);
		$this->assign('department', $employeeData["department"]);
		$this->assign('position', $employeeData["position"]);
		$this->assign('departmentList', $departmentList);
    }

    /**
     * 保存变更员工部门信息，同时上报企业微信
     */
    public function saveEmployeeDepartment()
    {
        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

        $this->_employee_business_model->saveEmployeeDepartment($this->_REQUEST);

    }


}
