<?php
error_reporting(0);

/**
 * 游戏管理
 */

class archives {

	private $_archives_model;

	public function __construct() {
		$this->_archives_model = new Model('ms_archives');
	}

	/**
	 * 获取一个游戏的详细信息
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_archives_model->get("`id`='{$id}'");
	}

	/**
	 * 获取游戏列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getList($offset=null, $length=null, $type){
		$where = 1;
		if($type){
			$where .= " AND `type` = '".$type."'";
		}

		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_archives_model->select('*', $where, null, '', $limit);
	}

	/**
	 * 获取游戏总数
	 * 
	 * @return int
	 */
	public function getTotal($type) {
		$where = 1;
		if($type){
			$where .= " AND `type` = '".$type."'";
		}

		$result = $this->_archives_model->select('COUNT(*) as total',$where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 增加游戏
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function add(array $data) {
		return $this->_archives_model->set($data);
	}

	/**
	 * 编辑游戏
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function edit($id, array $data) {
		$id = mysql_real_escape_string($id);
		return $this->_archives_model->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除游戏
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function delete($id) {
		$id = mysql_real_escape_string($id);
		$game = $this->getInfo($id);
		
		$success = $this->_archives_model->delete("`id`='{$id}'");
			
	}
}