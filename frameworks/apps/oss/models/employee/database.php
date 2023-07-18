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
	private $_crm_crontabs;
	private $_crm_departments;

	public function __construct()
    {
		$this->_role = new model('role');
		$this->_crm_employees = new model('crm_employees');
		$this->_crm_crontabs = new model('crm_crontabs');
		$this->_crm_departments = new model('crm_departments');
	}

    /**
	 * 判断uid属于哪个用户组
	 */
	public function getUidGroup($uid)
    {
		return $this->_role->get("`uid`='{$uid}'");
	}

    /**
     * 获取所有员工数据
     */
	public function getAllEmployees($offset, $row)
	{
		$where = "";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		return $this->_crm_employees->select('*', $where, $groupby, $orderby, $limit);
	}

    /**
	 * 获取员工列表总记录数
	 */
	public function getAllEmployeesCount()
	{
		$count = $this->_crm_employees->select('count(1) as count');
		return $count[0]['count'];
	}

    /**
     * 保存数据到计划任务数据表
     */
    public function setCrontabs($data)
	{
		return $this->_crm_crontabs->set($data);
	}

    /**
     * 获取指定用户操作指定任务最新一条记录的创建时间
     */
    public function getUidCrontab($uid, $crontabName)
    {
        $where = "`uid`='{$uid}' and `action`='{$crontabName}'";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '1'; 
		
		return $this->_crm_crontabs->select('createtime, state', $where, $groupby, $orderby, $limit);
    }

	/**
	 * 获取指定员工信息
	 * 
	 * @param [string] $userid 员工ID
	 * 
	 */
	public function getSingleEmployeeData($userid)
	{
		$where = "`userid`='{$userid}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_employees->get($where);
	}

	/**
     * 获取部门列表
     */
    public function getAllDepartments()
    {
        $where = "";
		$groupby = "";
		$orderby = "`order` DESC";
		$limit = '';
		
		return $this->_crm_departments->select('*', $where, $groupby, $orderby, $limit);
    } 

	/**
	 * 更新指定员工信息
	 */
	public function updateEmployee($updateData)
	{
		// 指定需要更新信息的员工
		$where = "userid='{$updateData['userid']}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'department' => $updateData["department"], 
			'position' => $updateData["position"]
		);

		// echo "<pre>";
		// var_dump($where);
		// var_dump($updateArray);
		// exit;
		
		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_employees->set($updateArray, $where);
	}

}