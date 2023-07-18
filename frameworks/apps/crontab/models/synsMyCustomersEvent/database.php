<?php
/**
+----------------------------------------------------------
* 数据访问模型
+----------------------------------------------------------
* @author heyongzhen
* @version 2023.7.10
+----------------------------------------------------------
*/
class database 
{
    private $_role;
    private $_crm_customers;

	public function __construct()
    {
        $this->_role = new model('role');
		$this->_crm_customers = new model('crm_customers');
	}

    /**
	 * 获取scrm平台指定角色信息
	 */
	public function getRoleInfo($uid)
    {
		return $this->_role->get("`uid`='{$uid}'");
	}

    /**
	 * 保存数据到客户数据表
	 */
	public function setFollowCustomers($data)
	{
		return $this->_crm_customers->set($data);
	}
}