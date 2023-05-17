<?php

/**
 * VIP用户统计记录
 */

class linkData {

	private $_link_order_model;

	public function __construct($years) {
		$this->_link_order_model = new Model('ms_link_order_' . $years);
	}

	/**
	 * 获取关联用户充值列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getLinkOrderList($offset = null, $row = null, $sumString, $specialString, $game, $channel, $start, $end, $userName, $apkNum, $serverId, $orderId, $payType, $linkUserName, $linkChannelId) {
		$where = 1;
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		$where .= $game ? " AND gameAlias = '" . $game . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		$where .= $start ? " AND time >=  '" . $start . "' " : '';
		$where .= $end ? " AND time <= '" . $end . "' " : '';
		$where .= $userName ? " AND userName = '" . $userName . "' " : '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
		$where .= $serverId ? " AND server = '" . $serverId . "' " : '';
		$where .= $orderId ? " AND orderId = '" . $orderId . "' " : '';
		$where .= $payType ? " AND payType = '" . $payType . "' " : '';
		$where .= $linkUserName ? " AND linkUserName = '" . $linkUserName . "' " : '';
		$where .= $linkChannelId ? " AND linkChannelId = '" . $linkChannelId . "' " : '';
		/*if ($game) {
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
		if ($serverId) {
			$where .= " AND server = '%" . $serverId . "%' ";
		}
		if($payType){
			$where .= " AND payType = '" . $payType . "' ";
		}*/
		$limit = '';
		if ($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
//var_dump($where);exit;
		return $this->_link_order_model->select('*', $where, null, 'time DESC', $limit);
	}

	/**
	 * 获取回流用户充值列表数据总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getLinkOrderTotal($sumString, $specialString, $game, $channel, $start, $end, $userName, $apkNum, $serverId, $orderId, $payType, $linkUserName, $linkChannelId) {
		$where = 1;
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		$where .= $game ? " AND gameAlias = '" . $game . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		$where .= $start ? " AND time >=  '" . $start . "' " : '';
		$where .= $end ? " AND time <= '" . $end . "' " : '';
		$where .= $userName ? " AND userName = '" . $userName . "' " : '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
		$where .= $serverId ? " AND server = '" . $serverId . "' " : '';
		$where .= $orderId ? " AND orderId = '" . $orderId . "' " : '';
		$where .= $payType ? " AND payType = '" . $payType . "' " : '';
		$where .= $linkUserName ? " AND linkUserName = '" . $linkUserName . "' " : '';
		$where .= $linkChannelId ? " AND linkChannelId = '" . $linkChannelId . "' " : '';
		$result = $this->_link_order_model->select('COUNT(1) AS total', $where);
		return $result;
	}
}