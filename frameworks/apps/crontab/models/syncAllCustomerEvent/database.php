<?php
/**
+----------------------------------------------------------
* 数据访问模型
+----------------------------------------------------------
* @author heyongzhen
* @version 2023.7.5
+----------------------------------------------------------
*/
class database 
{

	private $_crm_customers;

	public function __construct()
    {
		$this->_crm_customers = new model('crm_customers');
	}

    /**
	 * 根据企业成员的userid查询客户数据表并只获取一条记录
	 * 
	 */
	public function getFollowCustomerSingle($follow_userid)
	{
		$where = "`follow_userid`='{$follow_userid}'";
		return $this->_crm_customers->get($where);
	}

    /**
	 * 保存数据到客户数据表
	 */
	public function setFollowCustomers($data)
	{
		return $this->_crm_customers->set($data);
	}

}