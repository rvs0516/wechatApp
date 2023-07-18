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
	private $_crm_departments;
	private $_crm_crontabs;

	public function __construct()
    {
		$this->_role = new model('role');
		$this->_crm_departments = new model('crm_departments');
		$this->_crm_crontabs = new model('crm_crontabs');
	}

    /**
     * 获取所有部门数据
     */
	public function getAllDepartments($offset, $row)
	{
		$where = "";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		return $this->_crm_departments->select('*', $where, $groupby, $orderby, $limit);
	}

    /**
	 * 获取部门列表总记录数
	 */
	public function getAllDepartmentsCount()
	{
		$count = $this->_crm_departments->select('count(1) as count');
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

}