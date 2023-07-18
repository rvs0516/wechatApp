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
	private $_crm_crontabs;

	public function __construct()
    {
		$this->_role = new model('role');
		$this->_crm_crontabs = new model('crm_crontabs');
	}

    /**
     * 获取所有任务
     */
    public function getAllCrontabs($offset, $row)
	{
		$where = "";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		return $this->_crm_crontabs->select('*', $where, $groupby, $orderby, $limit);
	}

    /**
	 * 获取任务列表总记录数
	 */
	public function getAllCrontabsCount()
	{
		$count = $this->_crm_crontabs->select('count(1) as count');
		return $count[0]['count'];
	}

    
}