<?php

/**
 * VIP用户统计记录
 */

class benefits {

	private $_benefits_model;
	private $_order_model;

	public function __construct() {
		$this->_benefits_model = new Model('ms_benefits');
		$this->_order_model = new Model('ms_order_tmp');
	}

	/**
	 * 获取返利列表
	 */
	public function getBenefitsList($offset, $length, $upperName, $specialName, $game,  $start, $end, $gid, $gameStr){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = " LIMIT " . intval($offset) . ',' . intval($length);
		}
		$where = 1;

		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($start){
			$where .= " AND b.`start` >= '$start'";
		}
		if($end){
			$where .= " AND b.`end` <= '$end'";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 ||$gid == 15 || $gid == 17) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}

		$sql = "SELECT g.`upperName`, g.`specialName`, g.`name`, b.`gameAlias`, b.`id`, b.`title`, b.`channelId`, b.`start`, b.`end`, b.`benefits` FROM ms_benefits b LEFT JOIN ms_game g ON g.`alias` = b.`gameAlias` WHERE " . $where . " ORDER BY b.`id` DESC " . $limit;
		$result = MODEL::getBySql($sql);
		return $result;
	}

	/**
	 * 获取返利列表总数
	 */
	public function getBenefitsTotal($upperName, $specialName, $game,  $start, $end, $gid, $gameStr){
		$where = 1;

		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($start){
			$where .= " AND b.`start` >= '$start'";
		}
		if($end){
			$where .= " AND b.`end` <= '$end'";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 ||$gid == 15 || $gid == 17) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		
		$sql = "SELECT COUNT(1) AS total FROM ms_benefits b LEFT JOIN ms_game g ON g.`alias` = b.`gameAlias` WHERE " . $where ;
		
		$result = MODEL::getBySql($sql);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 添加返利信息
	 */
	public function add($data){
		
		return $this->_benefits_model->set($data);
	}

	/**
	 * 删除返利信息息
	 */
	public function del($id){
		$id = mysql_real_escape_string($id);
		return $this->_benefits_model->delete("`id`='{$id}'");
	}


	/**
	 * 获取返利用户列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getUserList($offset, $length, $year, $upperName, $specialName, $game, $start, $end, $userName, $roleId, $serverId, $status, $type) {
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = " LIMIT " . intval($offset) . ',' . intval($length);
		}
		$where = 1;

		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($start){
			$where .= " AND b.`time` >= '$start'";
		}
		if($end){
			$where .= " AND b.`time` <= '$end'";
		}
		if($userName){
			$where .= " AND b.`userName` = '$userName'";
		}
		if($roleId){
			$where .= " AND b.`roleId` = '$roleId'";
		}
		if($serverId){
			$where .= " AND b.`serverId` = '$serverId'";
		}
		if($status){
			$where .= " AND b.`status` = '$status'";
		}
		if($type){
			$where .= " AND b.`grantType` = '$type'";
		}

		$sql = "SELECT g.`upperName`, g.`specialName`, g.`name`, b.`gameAlias`, b.`id`, b.`benefitId`, b.`recharge`, b.`grantData`, b.`userName`, b.`roleName`, b.`roleId`, b.`serverId`, b.`status`, b.`grantType`, b.`time`, b.`prop` FROM ms_benefits_recharge_" . $year . " b LEFT JOIN ms_game g ON g.`alias` = b.`gameAlias` WHERE " . $where . " ORDER BY b.`id` DESC " . $limit;

		$result = MODEL::getBySql($sql);
		return $result;
	}

	/**
	 * 获取返利用户列表总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getUserTotal($year, $upperName, $specialName, $game, $start, $end, $userName, $roleId, $serverId, $status, $type) {
		$where = 1;

		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($start){
			$where .= " AND b.`time` >= '$start'";
		}
		if($end){
			$where .= " AND b.`time` <= '$end'";
		}
		if($userName){
			$where .= " AND b.`userName` = '$userName'";
		}
		if($roleId){
			$where .= " AND b.`roleId` = '$roleId'";
		}
		if($serverId){
			$where .= " AND b.`serverId` = '$serverId'";
		}
		if($status){
			$where .= " AND b.`status` = '$status'";
		}
		if($type){
			$where .= " AND b.`grantType` = '$type'";
		}
		
		$sql = "SELECT COUNT(1) AS total FROM ms_benefits_recharge_" . $year . " b LEFT JOIN ms_game g ON g.`alias` = b.`gameAlias` WHERE " . $where ;
		
		$result = MODEL::getBySql($sql);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}
}