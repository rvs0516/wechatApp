<?php
/**
+----------------------------------------------------------
* 数据访问模型
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
class database {

	private $_role;
	private $_crm_customers;
	private $_crm_crontabs;

	public function __construct()
    {
		$this->_role = new model('role');
		$this->_crm_customers = new model('crm_customers');
		$this->_crm_crontabs = new model('crm_crontabs');
	}

    /**
	 * 判断uid属于哪个用户组
	 */
	public function getUidGroup($uid)
    {
		return $this->_role->get("`uid`='{$uid}'");
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
	 * 根据企业成员的userid查询客户数据表
	 * 
	 */
	public function getFollowCustomers($follow_userid, $offset, $row)
	{
		// select($fields = '*', $where = null, $groupby = null, $orderby = null, $limit = null)
		$where = "`follow_userid`='{$follow_userid}'";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		return $this->_crm_customers->select('*', $where, $groupby, $orderby, $limit);
	}

	/**
	 * 保存数据到客户数据表
	 */
	public function setFollowCustomers($data)
	{
		return $this->_crm_customers->set($data);
	}

	/**
	 * 获取客户数据表中所有客户信息
	 */
	public function getAllCustomers($offset, $row)
	{
		$where = "";
		$groupby = "";
		$orderby = "`id` DESC";
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		return $this->_crm_customers->select('*', $where, $groupby, $orderby, $limit);
	}

	/**
	 * 获取客户列表总记录数
	 */
	public function getAllCustomersCount()
	{
		$count = $this->_crm_customers->select('count(1) as count');
		return $count[0]['count'];
	}

	/**
	 * 获取客户列表总记录数
	 */
	public function getFollowCustomersCount($follow_userid)
	{
		$where = "`follow_userid`='{$follow_userid}'";
		$count = $this->_crm_customers->select('count(1) as count', $where);
		return $count[0]['count'];
	}

	/**
	 * 获取指定客户信息
	 * 
	 * @param string $follow_userid 客户所属企业用户ID
	 * @param string $external_userid 客户userid
	 * 
	 */
	public function getSingleCustomerData($follow_userid, $external_userid)
	{
		$where = "`follow_userid`='{$follow_userid}' and `external_userid`='{$external_userid}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_customers->get($where);
	}

	/**
	 * 更新客户备注信息（目前没有使用）
	 */
	public function updateCustomerData($updateData)
	{
		// 指定需要更新信息的客户
		$where = "follow_userid='{$updateData["userid"]}' and external_userid='{$updateData["external_userid"]}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'remark' => $updateData["remark"], 
			'description' => $updateData["description"], 
			'remark_corp_name' => $updateData["remark_company"], 
			'remark_mobiles' => $updateData["remark_mobiles"],
			'updatetime' => time(),
		);

		// echo "<pre>";
		// var_dump($where);
		// var_dump($updateArray);
		// exit;

		// error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "updateArray: ". json_encode($updateArray). "\n\n", 3, C('DEDE_DATA_PATH')."/logs/oss_updateCustomerData_".date('Ymd').".txt");

		
		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_customers->set($updateArray, $where);
	}

	/**
     * 修改转接客户信息
     */
    public function updateCustomerState($follow_userid, $new_follow_userid, $external_userid, $state, $updatetime, $old_transfertime)
    {
		// 指定需要更新信息的客户
		$where = "follow_userid='{$follow_userid}' and external_userid='{$external_userid}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'state' => $state,
			'new_follow_userid' => $new_follow_userid,
			'transfertime' => $updatetime,
			'old_transfertime' => $old_transfertime
		);

		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_customers->set($updateArray, $where);
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
     * 保存数据到计划任务数据表
     */
    public function setCrontabs($data)
	{
		return $this->_crm_crontabs->set($data);
	}

}