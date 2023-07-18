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
	private $_crm_customers;

	public function __construct()
    {
		$this->_crm_customers = new model('crm_customers');
	}

    /**
	 * 更新客户信息
	 */
	public function updateCustomerData($updateData)
	{

        // {
        //     "follow_userid":"HeYongZhen",
        //     "external_userid":"wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg",
        //     "remark":"\u4f55\u6c38\u771f",
        //     "description":"\u5927R",
        //     "remark_corp_name":"\u4e7e\u6e3822",
        //     "remark_mobiles":"13802345801<br>13802345802<br>13802345803"
        // }

		// 指定需要更新信息的客户
		$where = "follow_userid='{$updateData["follow_userid"]}' and external_userid='{$updateData["external_userid"]}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'tag_name1' => $updateData["tag_name1"], 
			'tag_name2' => $updateData["tag_name2"], 
			'remark' => $updateData["remark"], 
			'description' => $updateData["description"], 
			'remark_corp_name' => $updateData["remark_corp_name"], 
			'remark_mobiles' => $updateData["remark_mobiles"],
			'updatetime' => $updateData["updatetime"],
		);

		// echo "<pre>";
		// var_dump($where);
		// var_dump($updateArray);
		// exit;
		
		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_customers->set($updateArray, $where);
	}

    /**
     * 修改客户状态
     */
    public function updateCustomerState($updateData)
    {
		// 指定需要更新信息的客户
		$where = "follow_userid='{$updateData["follow_userid"]}' and external_userid='{$updateData["external_userid"]}'";
		
		// 需要更新信息的字段
		$updateArray = array();

        // 客户状态
        if ($updateData["state"]) {
            $updateArray['state'] = $updateData["state"];
        }

        // 被客户删除时间
        if ($updateData["customer_deletetime"]) {
            $updateArray['customer_deletetime'] = $updateData["customer_deletetime"];
        }

        // 被客户删除次数
        if ($updateData["customer_delete_number"]) {
            $updateArray['customer_delete_number'] = $updateData["customer_delete_number"];
        }

        // 被员工删除时间
        if ($updateData["follow_deletetime"]) {
            $updateArray['follow_deletetime'] = $updateData["follow_deletetime"];
        }

        // 重新创建时间
        if ($updateData["restart_createtime"]) {
            $updateArray['restart_createtime'] = $updateData["restart_createtime"];
        }

        // 原所属人
        if ($updateData["old_follow_userid"]) {
            $updateArray['old_follow_userid'] = $updateData["old_follow_userid"];
        }

        // 在职继承回调更新专用
        if ($updateData["new_follow_userid"] == "del" && $updateData["remark_follow_userid"] && $updateData["transfer_success_time"]) {
            // 删除准所属人
            $updateArray['new_follow_userid'] = "";

            // 更换所属人
            $updateArray['follow_userid'] = $updateData["remark_follow_userid"];

            // 最近在职转接成功时间
            $updateArray['transfer_success_time'] = $updateData["transfer_success_time"];
        }

        // 用于添加待企业成员同意的客户，修改客户状态为客户主动添加
        if ($updateData["add_way"]) {
            $updateArray['add_way'] = $updateData["add_way"];
        }

        // 员工确认添加客户时间
        if ($updateData["agree_createtime"]) {
            $updateArray['agree_createtime'] = $updateData["agree_createtime"];
        }

        // echo "<pre>";
        // var_dump($updateArray);
        // exit;
        
		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_customers->set($updateArray, $where);
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
	 * 获取正在转接状态的客户信息
	 * 
	 * @param string $external_userid 客户userid
	 * @param string $state 客户状态
	 * 
	 */
	public function getDesignationStateCustomer($new_follow_userid, $external_userid, $state)
	{
		$where = "`new_follow_userid`='{$new_follow_userid}' and `external_userid`='{$external_userid}' and `state`='{$state}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_customers->get($where);
	}

    /**
	 * 保存数据到客户数据表
	 */
	public function setFollowCustomers($data)
	{
		return $this->_crm_customers->set($data);
	}

    /**
     * 获取指定企业成员微信下的一个客户信息
     * 
     * @param string $follow_userid 企业成员微信userid
     */
    public function getFollowSingleCustomer($follow_userid)
    {
        $where = "`follow_userid`='{$follow_userid}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_customers->get($where);
    }
}