<?php
/**
 * 游戏分类管理
 */

class category {
	
	private $_category_model;
	
	public function __construct() {
		$this->_category_model = new Model('ms_category');
	}
	
	/**
	 * 获取列表
	 * 
	 * @return array
	 */
	public function getList() {
		return $this->_category_model->select('*', null, null, '`sort` ASC');
	}
	
	/**
	 * 获取一个分类的信息
	 * 
	 * @param string id
	 * @return array
	 */
	public function getInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_category_model->get("`id`='{$id}'");
	}
	
	/**
	 * 增加一个分类
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function add(array $data) {
		return $this->_category_model->set($data);
	}
	
	/**
	 * 编辑一个分类
	 * 
	 * @param string $id 分类标识
	 * @param array $data
	 * @return boolean
	 */
	public function edit($id, array $data) {
		$id = mysql_real_escape_string($id);
		return $this->_category_model->set($data, "`id`='{$id}'");
	}
	
	/**
	 * 删除一个分类
	 * 
	 * @param string $id 分类标识
	 * @return boolean
	 */
	public function delete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_category_model->delete("`id`='{$id}'");
	}
}
?>
