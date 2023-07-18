<?php
/**
+----------------------------------------------------------
* 公共数据访问模型
+----------------------------------------------------------
* @author heyongzhen
* @version 2023.6.20
+----------------------------------------------------------
*/
class commonDB {

	private $_crm_crontabs;

	public function __construct()
    {
        $this->_crm_crontabs = new model('crm_crontabs');
	}

    /**
     * 获取需要执行的指定计划任务详情
     */
    public function getCrontab($action)
    {
        $where = "`action`='{$action}' and `state` = 1";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '1'; 
		
		return $this->_crm_crontabs->select('*', $where, $groupby, $orderby, $limit);
    }

    /**
     * 修改计划任务执行状态
     */
    public function updateCrontabState($uid, $action, $state)
    {
        // 指定需要更新信息的客户
		$where = "uid='{$uid}' and action='{$action}' and `state`='{$state}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'state' => 2,
			'finishtime' => time()
		);

		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_crontabs->set($updateArray, $where);
    }

}