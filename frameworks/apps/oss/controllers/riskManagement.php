<?php
/**
+----------------------------------------------------------
* 风险管理控制器
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/

require_once APP_CONTROLLER_PATH . '/master.php';

class riskManagementController extends masterControl {

	public $_REQUEST;
	public $_riskManagement_business_model;

	function __construct() {
        parent::__construct();

		$this->_REQUEST = $_REQUEST;

        // 引用业务逻辑模型
		$this->_riskManagement_business_model = getInstance('model.riskManagement.business');
    }

    /**
     * 会话记录列表
     */
    public function conversationRecord()
    {
        // 分页
        $page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
        $page = $page === 0 ? 1 : $page;
        $row = 15; // 偏移量，即每页记录条数
        $offset = ($page - 1) * $row; // 每页记录起点
        $this->assign('page', $page);
        $this->assign('row', $row);

        // 获取部门列表
        $departmentList = $this->_riskManagement_business_model->getDepartmentList();
        $this->assign('departmentList', $departmentList);

        // echo "<pre>";
        // var_dump($departmentList);
        // exit;

        // 部门ID
        $departmentId = trim($this->_REQUEST['department']) ? trim($this->_REQUEST['department']) : '';
        // 员工ID
        $userId = trim($this->_REQUEST['employee']) ? trim($this->_REQUEST['employee']) : '';
        $this->assign('departmentId', $departmentId);
        $this->assign('userId', $userId);
        
        if ($userId) {

            // echo "<pre>";
            // var_dump($this->_REQUEST);
            // exit;

            // 查找指定员工的客户列表
            $customersListData = $this->_riskManagement_business_model->getCustomersList($userId, $offset, $row);

            // echo "<pre>";
            // var_dump($customersListData);
            // exit;

            $this->assign('rowcount', $customersListData["customersCount"]); // 总记录数
		    $this->assign('customersList', $customersListData["customersList"]);

        }

        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

    }

    /**
     * 获取指定部门的员工
     */
    public function getDepartmentEmployee()
    {
        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

        // 部门ID
        $departmentId = trim($this->_REQUEST['departmentId']);
        // 员工ID
        $userId = trim($this->_REQUEST['userId']);

        // 获取指定部门的员工
        $departmentEmployeeList = $this->_riskManagement_business_model->getDepartmentEmployee($departmentId);

        // 拼接员工下拉列表option选项信息
        if ($departmentEmployeeList) {
            $option = '';
            $selected = '';

            $option .= '<option value="">请选择</option>';
            foreach ($departmentEmployeeList as $key => $value){
                $selected = ($userId == $value['userid']) ? 'selected="selected"' : '';
                $option .= '<option value="' . $value['userid'] . '"' . $selected . '>' . $value['name'] . '</option>';
            }
        }

        if (empty($option)) {
			$option = '<option value="">无相关数据</option>';
		}

		echo $option;exit;

        // echo "<pre>";
        // var_dump($departmentEmployeeList);
        // exit;

    }

    /**
     * 查看会话
     */
    public function checkConversation()
    {
        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

        // 员工ID
        $followUserid = trim($this->_REQUEST['followUserid']);
        // 客户ID
        $externalUserid = trim($this->_REQUEST['externalUserid']);

        if ($followUserid && $externalUserid) {
            
            // 获取会话内容
            $chatMsgHtml = $this->_riskManagement_business_model->checkConversation($followUserid, $externalUserid);

            // echo "<pre>";
            // var_dump($chatMsgHtml);
            // exit;
            
            echo $chatMsgHtml;exit;
        }
    }



}
