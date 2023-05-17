<?php

/**
 * VIP用户统计记录
 */

class honouredGuest {

	private $_guest_model;
	private $_order_model;

	public function __construct() {
		$this->_guest_model = new Model('ms_honoured_guest');
		$this->_order_model = new Model('ms_order_tmp');
	}

	/**
	 * 获取VIP用户列表
	 */
	public function getVipList($offset, $length, $sumString, $specialString, $game, $userName, $start, $end, $replace, $status){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
				$expand =  '`oldServer`, ';
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '$game'";
		}
		if($userName){
			$where .= " AND `userName` = '$userName'";
		}
		if($start){
			$where .= " AND `loginTime` >= '$start'";
		}
		if($end){
			$where .= " AND `loginTime` <= '$end'";
		}
		if($replace){
			$where .= " AND `replace` = '$replace'";
		}
		if($status){
			if($status == 2){
				$where .= " AND `status` = 0";
			}else{
				$where .= " AND `status` = '$status'";
			}
		}
		return $this->_guest_model->select('*', $where, null, '`id` DESC', $limit);
	}

	/**
	 * 获取VIP用户总数
	 */
	public function getVipTotal($sumString, $specialString, $game, $userName, $start, $end, $replace, $status){
		$where = 1;
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
				$expand =  '`oldServer`, ';
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '$game'";
		}
		if($userName){
			$where .= " AND `userName` = '$userName'";
		}
		if($start){
			$where .= " AND `loginTime` >= '$start'";
		}
		if($end){
			$where .= " AND `loginTime` <= '$end'";
		}
		if($status){
			if($status == 2){
				$where .= " AND `status` = 0";
			}else{
				$where .= " AND `status` = '$status'";
			}
		}
		$result = $this->_guest_model->select('COUNT(1) AS total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取单个VIP用户信息
	 */
	public function getVipInfo($userName){
		return $this->_guest_model->get("`userName`='{$userName}'");
	}

	/**
	 * 添加VIP用户信息
	 */
	public function add($data){
		return $this->_guest_model->set($data);
	}

	/**
	 * 编辑VIP用户信息
	 */
	public function edit($id, array $data) {
		$id = mysql_real_escape_string($id);
		return $this->_guest_model->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除VIP用户信息
	 */
	public function del($id){
		$id = mysql_real_escape_string($id);
		return $this->_guest_model->delete("`id`='{$id}'");
	}


	/**
	 * 获取回流用户充值列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getOrderList($offset, $row, $sumString, $specialString, $game, $channel, $start, $end, $userName, $apkNum, $roleId, $serverId, $orderId, $type) {
		$where = 1;
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if ($game) {
			$where .= " AND gameAlias = '" . $game . "' ";
		}
		if ($channel) {
			$where .= " AND channelId = '" . $channel . "' ";
		}
		if ($start) {
			$where .= " AND time >= " . $start;
		}
		if ($end) {
			$where .= " AND time <= " . $end;
		}
		if($userName){
			$where .=" AND `userName` = '". $userName ."' ";
		}
		if ($orderId) {
			$where .= " AND orderId = '" . $orderId . "' ";
		}
		if ($apkNum) {
			$where .= " AND apkNum = '" . $apkNum . "' ";
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "' ";
		}
		if ($serverId) {
			$where .= " AND server LIKE '%" . $serverId . "%' ";
		}
		if($type){
			$where .= " AND type = '" . $type . "' ";
		}
		$limit = "$offset, $row";

		return $this->_order_model->select('*', $where, null, 'time DESC', $limit);
	}

	/**
	 * 获取回流用户充值列表数据总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getOrderListTotal($sumString, $specialString, $game, $channel, $start, $end, $userName, $offset, $row, $apkNum,$roleId, $serverId, $orderId, $type) {
		$where = ' type = 1 ';
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if ($game) {
			$where .= " AND gameAlias = '" . $game . "' ";
		}
		if ($channel) {
			$where .= " AND channelId = '" . $channel . "' ";
		}
		if ($start) {
			$where .= " AND time >= " . $start;
		}
		if ($end) {
			$where .= " AND time <= " . $end;
		}
		if($userName){
			$where .=" AND `userName` = '". $userName ."' ";
		}
		if ($orderId) {
			$where .= " AND orderId = '" . $orderId . "' ";
		}
		if ($apkNum) {
			$where .= " AND apkNum = '" . $apkNum . "' ";
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "' ";
		}
		if ($serverId) {
			$where .= " AND server LIKE '%" . $serverId . "%' ";
		}
		if($type){
			$where .= " AND type = '" . $type . "' ";
		}
		$result = $this->_order_model->select('COUNT(1) AS total', $where);
		return $result;
	}
}