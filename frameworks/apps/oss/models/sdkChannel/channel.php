<?php
error_reporting(0);

/**
 * 游戏渠道管理
 */

class channel {

	private $_ms_channel;
	private $_ms_game;
	private $_ms_role;

	public function __construct() {
		$this->_ms_channel = new Model('ms_channel');
		$this->_ms_game = new model('ms_game');
		$this->_ms_role = new model('role');
	}

	/**
	 * 获取一个渠道的详细信息
	 *
	 * @param int $id
	 * @return array
	 */
	public function getInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_ms_channel->get("`id`='{$id}'");
	}

	/**
	 * 获取游戏列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getList($offset=null, $length=2) {
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_ms_channel->select('*', null, null, '`sort` ASC', $limit);
	}

	/**
	 * 获取游戏列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getListByGname($game, $offset = null, $length = null, $sumString = '', $specialString = '', $adsChannel = '', $upGradeMark, $channelId, $remarks, $gameStr = '', $gid) {
		$where = "1";
		if($game && $game != false){
			$where .= " AND `gameAlias` = '". $game . "'";
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		if($sumString){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($adsChannel){
			if ($adsChannel == 'gdt') {
				$where .= " AND `adsChannel` = '' AND ext2 != ''";
			}else{
				$where .= " AND `adsChannel` = '". $adsChannel . "'";
			}
		}
		if ($upGradeMark == 1) {
			$where .= " AND `upGradeMark` = 1";
		}elseif ($upGradeMark == 2) {
			$where .= " AND `upGradeMark` = 0";
		}
		if ($channelId) {
			$where .= " AND `channelId` = ".$channelId;
		}
		if ($remarks) {
			$where .= " AND `remarks` LIKE '%".$remarks."%'";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 ||$gid == 15 || $gid == 17) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		return $this->_ms_channel->select('*', $where, null, '`sort` DESC', $limit);
	}


	/**
	 * 获取渠道总数
	 *
	 * @return int
	 */
	public function getChannelTotal($game, $sumString = '', $specialString = '', $adsChannel = '', $upGradeMark, $channelId, $remarks, $gameStr = '', $gid) {
		$where ="1";
		if($game && $game != false){
			$where .= " AND `gameAlias` = '". $game . "'";
		}
		if($sumString){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($adsChannel){
			if ($adsChannel == 'gdt') {
				$where .= " AND `adsChannel` = '' AND ext2 != ''";
			}else{
				$where .= " AND `adsChannel` = '". $adsChannel . "'";
			}
		}
		if ($upGradeMark == 1) {
			$where .= " AND `upGradeMark` = 1 ";
		}elseif ($upGradeMark == 2) {
			$where .= " AND `upGradeMark` = 0 ";
		}
		if ($channelId) {
			$where .= " AND `channelId` = ".$channelId;
		}
		if ($remarks) {
			$where .= " AND `remarks` LIKE '%".$remarks."%'";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 ||$gid == 15 || $gid == 17) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		$result = $this->_ms_channel->select("COUNT(*) as total", $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}


	/**
	 * 增加游戏
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function add(array $data) {
		return $this->_ms_channel->set($data);
	}

	/**
	 * 编辑游戏
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function edit($id, array $data) {
		$alias = mysql_real_escape_string($alias);
		return $this->_ms_channel->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除游戏
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function delete($id) {
		$id = mysql_real_escape_string($id);
		$channel = $this->getInfo($id);

		if(!empty($channel)) {
			$this->_ms_channel->delete("`id`='{$id}'");
			return true;
		}

		return false;
	}

	/**
	 * 判断uid属于哪个用户组
	 */
	public function returnUidGroup($uid){
		return $this->_ms_role->select("gid, id, uid, game, channelId, payCharging, limitTime, ads", "uid = '$uid'", null, null, null);
	}

	/**
	 * 获取某游戏下渠道列表
	 */
	public function getGameChannels($game) {
		$where = 1;
		if($game){
			$where .= " and gameAlias = '$game' AND channelId IS NOT NULL AND apkNum != ''";
		}

		return $this->_ms_channel->select('DISTINCT channelId, channelName', $where, null, '`id` DESC', '');
	}
}