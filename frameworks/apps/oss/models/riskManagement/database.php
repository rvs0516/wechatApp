<?php
/**
+----------------------------------------------------------
* 数据访问模型
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
class database 
{

    private $_role;
	private $_crm_employees;
	private $_crm_departments;
	private $_crm_customers;
	private $_crm_chat_msg;

	public function __construct()
    {
		$this->_role = new model('role');
		$this->_crm_employees = new model('crm_employees');
		$this->_crm_departments = new model('crm_departments');
        $this->_crm_customers = new model('crm_customers');
        $this->_crm_chat_msg = new model('crm_chat_msg');
	}

    /**
     * 获取部门列表
     */
    public function getDepartmentList()
    {
        $where = "";
		$groupby = "";
		$orderby = "`order` DESC";
		$limit = '';
		return $this->_crm_departments->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
     * 获取指定部门的员工列表
     */
    public function getDepartmentEmployeeList($departmentId)
    {
        $where = "`department`='{$departmentId}'";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';

        return $this->_crm_employees->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
     * 获取指定员工的客户列表
     * 
     * @param [int] $userId 员工ID
     */
    public function getCustomersList($userId, $offset, $row)
    {
        $where = "`follow_userid`='{$userId}'";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
        if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}

        return $this->_crm_customers->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
	 * 获取指定员工的客户列表总记录数
	 */
	public function getCustomersCount($userId)
	{
        $where = "`follow_userid`='{$userId}'";
		$count = $this->_crm_customers->select('count(1) as count', $where);
		return $count[0]['count'];
	}

    /**
     * 获取客户发送的聊天信息
     */
    public function getCustomerChatMsg($followUserid, $externalUserid)
    {
        $where = "`sender`='{$externalUserid}' and `receiver`='{$followUserid}'";
		$groupby = "";
		$orderby = "`msgtime` ASC";
		$limit = '';
        // if ($offset !== null || $row !== null) {
		// 	$limit = intval($offset) . ',' . intval($row);
		// }

        return $this->_crm_chat_msg->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
     * 获取员工发送的聊天信息
     */
    public function getEmployeeChatMsg($followUserid, $externalUserid)
    {
        $where = "`sender`='{$followUserid}' and `receiver`='{$externalUserid}'";
		$groupby = "";
		$orderby = "`msgtime` ASC";
		$limit = '';
        // if ($offset !== null || $row !== null) {
		// 	$limit = intval($offset) . ',' . intval($row);
		// }

        return $this->_crm_chat_msg->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
     * 获取指定客户信息
     */
    public function getCustomerInfo($external_userid)
    {
        return $this->_crm_customers->get("`external_userid`='{$external_userid}'");
    }

    /**
     * 获取指定员工信息
     */
    public function getEmployeeInfo($userid)
    {
        return $this->_crm_employees->get("`userid`='{$userid}'");
    }


}