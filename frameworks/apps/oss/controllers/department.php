<?php
/**
+----------------------------------------------------------
* 部门管理控制器
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/

require_once APP_CONTROLLER_PATH . '/master.php';

class departmentController extends masterControl {

	public $_REQUEST;
	public $_department_business_model;

	function __construct() {
        parent::__construct();

		$this->_REQUEST = $_REQUEST;

        // 引用部门业务逻辑模型
		$this->_department_business_model = getInstance('model.department.business');
    }

    /**
     * 部门列表
     */
    public function departmentList()
    {
        // 分页
		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$row = 15; // 偏移量，即每页记录条数
		$offset = ($page - 1) * $row; // 每页记录起点

        $departmentListData = $this->_department_business_model->departmentList($offset, $row);

        // echo "<pre>";
        // var_dump($departmentListData);
        // exit;

        $this->assign('page', $page);
        $this->assign('row', $row);
		$this->assign('rowcount', $departmentListData["departmentsCount"]); // 总记录数
		$this->assign('departmentList', $departmentListData["departmentData"]);

    }

    /**
     * 同步部门
     */
    public function syncDepartments()
    {
       
        $this->_department_business_model->syncDepartments($this->_uid);
        
    }


}
