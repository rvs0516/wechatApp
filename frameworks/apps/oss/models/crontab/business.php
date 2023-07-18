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
	public $_crontab_database_model;

	function __construct() {

        // 实例化数据访问对象
		$this->_crontab_database_model = getInstance('model.crontab.database');

    }

    /**
     * 任务列表
     */
    public function crontabList($offset, $row)
    {
        // 获取所有员工信息
        $crontabData = $this->_crontab_database_model->getAllCrontabs($offset, $row);

        // 格式化数据
        foreach ($crontabData as $key => $value) {
            $crontabData[$key]['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : "";
            $crontabData[$key]['finishtime'] = $value['finishtime'] ? date('Y-m-d H:i:s', $value['finishtime']) : "";
            // 执行状态：1=需要执行，2=不需要执行或者执行完成
            $crontabData[$key]['state'] = $crontabData[$key]['state'] == 1 ? '待执行' : '完成';
        }
        
        // 获取员工列表总记录数
        $crontabsCount = $this->_crontab_database_model->getAllCrontabsCount();

        // echo "<pre>";
        // var_dump($crontabData);
        // var_dump($crontabsCount);
        // exit;

        $data = array(
            'crontabData' => $crontabData ? $crontabData : array(),
            'crontabsCount' => $crontabsCount ? $crontabsCount : 0
        );

        return $data;
    }


}