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
    private $_crm_employees;

	public function __construct()
    {
		$this->_crm_employees = new model('crm_employees');
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
     * 更新指定员工信息
     */
    public function updateEmployee($data)
    {
        // 指定需要更新信息的客户
		$where = "userid='{$data['userid']}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'name' => $data['name'],
			'alias' => $data['alias'],
			'status' => $data['status'],
			'avatar' => $data['avatar'], // 头像URL
			'qr_code' => $data['qr_code'], // 员工个人二维码
			'mobile' => $data['mobile'],
			'department' => $data['department'][0],
			'position' => $data['position'],
			'updatetime' => time()
		);

		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_employees->set($updateArray, $where);
    }

    /**
	 * 保存数据到员工数据表
	 */
	public function setEmployees($data)
	{
		return $this->_crm_employees->set($data);
	}


}