<?php
/**
+----------------------------------------------------------
* 任务管理控制器
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
require_once APP_CONTROLLER_PATH . '/master.php';

class crontabController extends masterControl 
{
    public $_REQUEST;
	public $_crontab_business_model;

	function __construct() {
        parent::__construct();

		$this->_REQUEST = $_REQUEST;

        // 引用员工业务逻辑模型
		$this->_crontab_business_model = getInstance('model.crontab.business');
    }

    /**
     * 任务列表
     */
    public function crontabList()
    {
        // 分页
		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$row = 15; // 偏移量，即每页记录条数
		$offset = ($page - 1) * $row; // 每页记录起点

        $crontabListData = $this->_crontab_business_model->crontabList($offset, $row);

        // echo "<pre>";
        // var_dump($crontabListData);
        // exit;

        $this->assign('page', $page);
        $this->assign('row', $row);
		$this->assign('rowcount', $crontabListData["crontabsCount"]); // 总记录数
		$this->assign('crontabList', $crontabListData["crontabData"]);
    }
}