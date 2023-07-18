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

	private $_crm_departments;

	public function __construct()
    {
        $this->_crm_departments = new model('crm_departments');
	}

    /**
	 * 获取指定部门信息
	 * 
	 * @param [string] $departmentid 部门ID
	 * 
	 */
	public function getSingleDepartmentData($departmentid)
	{
		$where = "`departmentid`='{$departmentid}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_departments->get($where);
	}

    
    /**
     * 更新指定部门信息
     */
    public function updateDepartment($data)
    {
        // 指定需要更新信息的部门
		$where = "departmentid='{$data['id']}'";
		
		// 需要更新信息的字段
		$updateArray = array(
			'name' => $data['name'],
			'parentid' => $data['parentid'],
			'order' => $data['order']
		);

		// set($data = array(), $where = null, $printf_args=array())
		$this->_crm_departments->set($updateArray, $where);
    }

    /**
     * 保存数据到部门数据表
     */
    public function setDepartments($data)
	{
		return $this->_crm_departments->set($data);
	}

}