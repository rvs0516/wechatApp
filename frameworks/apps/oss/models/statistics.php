<?php

/**
 * 统计数据
 */

class statistics {

	private $_statistics_model;
	private $_ms_intermodal_daily;
	private $_ms_members;
	private $_ms_agent_orders;
	private $_ms_intermodal_hour;
	private $_ms_game;
	private $ms_integrated_daily;
	private $_ms_role;
	private $_ms_profit;

	public function __construct() {
		$this->_statistics_model = new Model('ms_statistics');
		$this->_ms_members = new Model('ms_member');
		$this->_ms_agent_orders = new Model('ms_order');
		$this->_ms_game = new model('ms_game');
		$this->_integrated_model = new model('ms_integrated_daily');
		$this->_ms_role = new model('ms_role_seted');
		$this->_ms_profit = new model('ms_profit_daily');
	}

	/**
	 * 获取每日注册扣量和充值金额扣量(超级管理员)
	 */
	public function getAmountDeduction($offset = null, $length = null, $game = null, $channel = null, $channel_name = null, $res_cop_model, $start_date, $end_date){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		if($game){
			$where .= " AND game = '$game'";
		}
		if($channel){
			$where .= " AND channel = '$channel'";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}
		if($res_cop_model){
			if($res_cop_model == 'napa'){
				$where .= " AND cop_model = ''";
			}  else {
				$where .= " AND cop_model= '$res_cop_model'";
			}
		}
		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}

		return $this->_ms_intermodal_daily->select('*', $where, null, '`day` DESC', $limit);
	}

	/**
     *获取每日注册扣量和充值金额扣量(行销商)
     */
	public function getAmountDeductionAd($offset = null, $length = 15, $game = null, $channel = null, $channel_name = null, $res_cop_model, $start_date, $end_date){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		if($game){
			$where .= " AND game = '$game'";
		}
		if(empty($game)){
			return "";
		}  else {
			if($channel){
				$where .= " AND channel IN(". $channel .")";
			}
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}
		if($res_cop_model){
			if($res_cop_model == 'napa'){
				$where .= " AND cop_model = ''";
			}  else {
				$where .= " AND cop_model= '$res_cop_model'";
			}
		}
		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}
		return $this->_ms_intermodal_daily->select('*', $where, null, '`day` DESC', $limit);
	}

	/**
     *获取每日注册扣量和充值金额扣量(渠道商)
     */
	public function getAmountDeductionAgent($offset = null, $length = 15,  $channel = null, $channel_name = null, $res_cop_model, $start_date, $end_date){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		if($channel){
			$where .= " AND channel = '$channel'";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}
		if($res_cop_model){
			$where .= " AND cop_model= '$res_cop_model'";
		}
		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}
		return $this->_ms_intermodal_daily->select('*', $where, null, '`day` DESC', $limit);
	}

	/**
	 * 获取游戏列表(超级管理员组)
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getList($game, $channel, $start, $end) {
		$where = ' 1 ';
		$where .= ($game) ? " AND game = '" . $game . "' " : '';
		$where .= ($channel) ? " AND channel = '" . $channel . "' " : '';
		$where .= ($start) ? " AND addtime >= " . $start : '';
		$where .= ($end) ? " AND addtime <= " . $end : '';

		return $this->_statistics_model->select('*', $where, null, '`game` ASC');
	}

	/**
	 * 获取游戏列表(行销商的)
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getListAd($game, $channel, $start, $end) {

		$where = ' 1 ';
		$where .= ($game) ? " AND game = '" . $game . "' " : '';
		if(!empty($game)){
			$where .= ($channel) ? " AND channel IN ( ". $channel .")" : "";
		}else{
			return "";
		}
		$where .= ($start) ? " AND addtime >= " . $start : '';
		$where .= ($end) ? " AND addtime <= " . $end : '';

		return $this->_statistics_model->select('*', $where, null, '`game` ASC');
	}

	/**
	 * 获取游戏列表(渠道商)
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getListAgent($channel, $start, $end) {

		$where = ' 1 ';
		$where .= ($channel)  ? " AND channel IN ( ". $channel .")" : "";
		$where .= ($start) ? " AND addtime >= " . $start : '';
		$where .= ($end) ? " AND addtime <= " . $end : '';
		return $this->_statistics_model->select('*', $where, null, '`game` ASC');
	}

	/**
	 * 对游戏的数据进行格式化
	 * 方法在原有字段中增加一些新字段
	 *
	 * @param array data 可以是一维（一行）或者多维（多行）数组
	 * @return array format_data
	 */
	public function formatData($channel, $type='normal') {
		if(empty($channel)) {
			return array();
		}

		$channel_list_format = array();
		foreach($channel as $key => $data) {
			//激活
			if ($data['type'] == 'init') {
				$channel_list_format[$data['channel']][$data['game']]['init'][] = $data;
			}

			//注册
			if ($data['type'] == 'regs') {
				$channel_list_format[$data['channel']][$data['game']]['regs'][] = $data;
			}

			//创角
			if ($data['type'] == 'role') {
				$channel_list_format[$data['channel']][$data['game']]['role'][] = $data;
			}

			//在线时长
			if ($data['type'] == 'online') {
				$channel_list_format[$data['channel']][$data['game']]['online'][] = $data['online_time'];
			}

			//储值
			if ($data['type'] == 'pay') {
				if ($data['online_time']) {
					$channel_list_format[$data['channel']][$data['game']]['pay'][] = $data;
					$channel_list_format[$data['channel']][$data['game']]['total'][] = $data['online_time'];
				}
			}
		}

		return $channel_list_format;
	}

	/**
     * 获取总数(超级管理员)
     *
     * @return int
     */
	public function getIntermodalDailyTotal($game, $channel, $channel_name, $res_cop_model, $start_date, $end_date) {
		$where = 1;
		if($game){
			$where .= " AND game = '$game'";
		}
		if($channel){
			$where .= " AND channel = '$channel'";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}

		if($res_cop_model){
			if($res_cop_model == 'napa'){
				$where .= " AND cop_model = ''";
			}else {
				$where .= " AND cop_model= '$res_cop_model'";
			}
		}

		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}

		$result = $this->_ms_intermodal_daily->select("COUNT(1) as total, SUM(amount) AS total_amount, SUM(regs) AS total_regs, SUM( FLOOR(`amount_rate` * `amount`) ) AS total_amount_rate, SUM( FLOOR(`regs_rate` * `regs`))  AS total_regs_rate", $where);

		return $result;
	}
	/**
     * 获取总数(行销商)
     *
     * @return int
     */
	public function getIntermodalDailyTotalAd($game, $channel, $channel_name = null, $res_cop_model, $start_date, $end_date) {
		$where = 1;
		if($game){
			$where .= " AND game = '$game'";
		}
		if(!empty($game)){
			if($channel){
				$where .= " AND channel IN(". $channel .")";
			}
		}else{
			return "";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}

		if($res_cop_model){
			if($res_cop_model == 'napa'){
				$where .= " AND cop_model = ''";
			}  else {
				$where .= " AND cop_model= '$res_cop_model'";
			}
		}

		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}
		$result = $this->_ms_intermodal_daily->select("COUNT(1) as total, SUM(amount) AS total_amount, SUM(regs) AS total_regs, SUM( `amount_rate` * `amount` ) AS total_amount_rate, SUM( `regs_rate` * `regs` ) AS total_regs_rate", $where);
		return $result;
	}

	/**
     * 获取总数(渠道商)
     *
     * @return int
     */
	public function getIntermodalDailyTotalAgent($channel, $channel_name, $res_cop_model, $start_date, $end_date) {
		$where = 1;
		if($channel){
			$where .= " AND channel = '$channel'";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}
		if($res_cop_model){
			$where .= " AND cop_model= '$res_cop_model'";
		}
		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}
		$result = $this->_ms_intermodal_daily->select("COUNT(1) as total, SUM(amount) AS total_amount, SUM(regs) AS total_regs, SUM( `amount_rate` * `amount` ) AS total_amount_rate, SUM( `regs_rate` * `regs` ) AS total_regs_rate", $where);
		return $result;
	}

	/**
     * 导出excel报表
     *
     * @return int
     */
	public function excelAmountDeduction($game, $channel, $channel_name, $res_cop_model, $start_date, $end_date){
		$where = 1;
		if($game){
			$where .= " AND game = '$game'";
		}
		if($channel){
			$where .= " AND channel = $channel";
		}
		if($channel_name){
			$where .= " AND channel = '$channel_name'";
		}

		if($res_cop_model){
			if($res_cop_model == 'napa'){
				$where .= " AND cop_model = ''";
			}  else {
				$where .= " AND cop_model= '$res_cop_model'";
			}
		}
		if($start_date){
			$where .= " AND day >= '$start_date'";
		}
		if($end_date){
			$where .= " AND day <= '$end_date'";
		}
		return $this->_ms_intermodal_daily->select('*', $where, null, '`day` DESC');
	}

	/**
	 * 获取玩家列表(超级管理员组)
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getMemberList($game, $channel, $uid, $start, $end, $offset= null, $row=null, $keywords, $apkNum, $sumString, $specialString, $info, $gid, $gameStr, $platformUserId) {
		if($end){
			$end = strtotime($end)+85399;
		}
		$where = ' 1 ';
		//$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		$where .= ($channel) ? " AND channelId = '" . $channel . "' " : '';
		//$where .= ($uid) ? " AND userName = '" . $uid . "'" : "";
		// $where .= ($uid) ? " AND (userName = '" . $uid . "' OR platformUserId = '" . $uid . "')" : '';
		$where .= ($uid) ? " AND userName = '" . $uid. "'" : '';
		$where .= ($platformUserId) ? " AND platformUserId = '" . $platformUserId . "'" : '';
		$where .= ($start) ? " AND joinTime >= " . strtotime($start) : '';
		$where .= ($end) ? " AND joinTime <= " . $end : '';
		$where .= ($keywords) ? " AND (province LIKE '%" . $keywords . "%' OR city LIKE '%" . $keywords . "%') " : "";
		$where .= ($apkNum) ? " AND apkNum = '" . $apkNum . "' " : '';
		$where .= ($info) ? " AND (loginIp = '" . $info . "' OR loginPhoneId = '" . $info . "')" : '';
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 ) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}

		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}

		return $this->_ms_members->select('*', $where, null, 'id DESC', $limit);
	}

	/**
	 * 获取玩家列表(超级管理员组)数据总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getMemberListTotal($game, $channel, $uid, $start, $end, $keywords, $apkNum, $sumString, $specialString, $info, $gid, $gameStr, $platformUserId) {
		if($end){
			$end = strtotime($end)+85399;
		}
		$where = ' 1 ';
		//$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		$where .= ($channel) ? " AND channelId = '" . $channel . "' " : '';
		//$where .= ($uid) ? " AND userName = '" . $uid . "'" : "";
		// $where .= ($uid) ? " AND (userName = '" . $uid . "' OR platformUserId = '" . $uid . "')" : '';
		$where .= ($uid) ? " AND userName = '" . $uid. "'" : '';
		$where .= ($platformUserId) ? " AND platformUserId = '" . $platformUserId . "'" : '';
		$where .= ($start) ? " AND jointime >= " . strtotime($start) : '';
		$where .= ($end) ? " AND jointime <= " . $end : '';
		$where .= ($keywords) ? " AND (province LIKE '%" . $keywords . "%' OR city LIKE '%" . $keywords . "%') " : "";
		$where .= ($apkNum) ? " AND apkNum = '" . $apkNum . "' " : '';
		$where .= ($info) ? " AND (loginIp = '" . $info . "' OR loginPhoneId = '" . $info . "')" : '';
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 ) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		$result = $this->_ms_members->select('COUNT(1) AS total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取充值列表(超级管理员组)
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getOrderList($game, $channel, $start, $end, $status, $userName, $offset, $row, $apkNum, $paymentId, $sumString, $specialString, $roleId, $openAd, $serverId, $gid, $gameStr, $orderId) {
		$where = ' 1 ';
		//$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';

		if ($channel) {
			//代理组 关联渠道可进行查询多个
			if ($gid == 8) {
				explode(',',$channel)[1] ? $where .= " AND channelId in (" . $channel . ") " : $where .= " AND channelId = ".$channel;
			}else{
				$where .= " AND channelId = '" . $channel . "' ";
			}
		}

		$where .= ($start) ? " AND time >= " . $start : '';
		$where .= ($end) ? " AND time <= " . $end : '';

		if($status){
			$where .= " AND orderStatus =" . $status;
		}elseif( $status == 0){
			$where .= " AND orderStatus =" . $status;
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
		if ($paymentId == 7 || $paymentId == 9 || $paymentId == 11 || $paymentId == 12 || $paymentId == 104) {
			$where .= " AND paymentId = '" . $paymentId . "' ";
		}elseif ($paymentId == 10) {
			$where .= " AND (paymentId = 7 OR paymentId = 9)";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "' ";
		}
		if ($openAd == 1) {
			$where .= " AND adid != '' ";
		}
		if ($serverId) {
			//$where .= " AND server LIKE '%" . $serverId . "%' ";
			$where .= " AND server = '" . $serverId . "' ";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 ||$gid == 15 || $gid == 17 || $gid == 22) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}

		$limit = "$offset, $row";

		return $this->_ms_agent_orders->select('*', $where, null, 'time DESC', $limit);
	}

	/**
	 * 获取充值列表(超级管理员组)数据总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getOrderListTotal($game, $channel, $start, $end, $status, $userName, $apkNum, $paymentId, $sumString, $specialString, $roleId, $openAd, $serverId, $gid, $gameStr, $orderId) {
		$where = ' 1 ';
		//$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		$where .= ($start) ? " AND time >= " . $start : '';
		$where .= ($end) ? " AND time <= " . $end : '';

		if ($channel) {
			//代理组 关联渠道可进行查询多个
			if ($gid == 8) {
				explode(',',$channel)[1] ? $where .= " AND channelId in (" . $channel . ") " : $where .= " AND channelId = ".$channel;
			}else{
				$where .= " AND channelId = " . $channel;
			}
		}

		if($status){
			$where .= " AND orderStatus =" . $status;
		}elseif( $status == 0){
			$where .= " AND orderStatus =" . $status;
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
		if ($paymentId == 7 || $paymentId == 9 || $paymentId == 11 || $paymentId == 12 || $paymentId == 104) {
			$where .= " AND paymentId = '" . $paymentId . "' ";
		}elseif ($paymentId == 10) {
			$where .= " AND (paymentId = 7 OR paymentId = 9)";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "' ";
		}
		if ($openAd == 1) {
			$where .= " AND adid != '' ";
		}
		if ($serverId) {
			//$where .= " AND server LIKE '%" . $serverId . "%' ";
			$where .= " AND server = '" . $serverId . "' ";
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22 ) && $gameStr) {
			$gameList = explode(',', $gameStr);
			$group = array();
			$i = 0;
			foreach ($gameList as $key => $value) {
				//每30个游戏别名为一组，分多组查询降低消耗
				if ($i * 30 <= $key  && ($i + 1) * 30 >= $key) {
					$i = $i + 1;
				}
				$group[$i][] = $value;
			}
			//$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		if (empty($group)) {
			$result = $this->_ms_agent_orders->select('COUNT(1) AS total', $where);
		}else {
			foreach ($group as $k => $v) {
				$d = $where . " AND gameAlias IN (".implode(',', $v).") ";
				$total = $this->_ms_agent_orders->select('COUNT(1) AS total', $d);
				$result[0]['total'] += $total[0]['total'];
				
			}
		}
		//$result = $this->_ms_agent_orders->select('COUNT(1) AS total', $where);
		return $result;
	}

	/**
	 * 获取充值列表(行销商)数据总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getOrderAdTotal($game, $c_channel, $channel, $start_time, $end_time, $status, $userName) {
		$where = ' 1 ';
		$where .= ($game) ? " AND game = '" . $game . "' " : '';
		if(!empty($game)){
			$where .= ($c_channel) ? " AND channel IN ( ". $c_channel .")" : "";
		}else{
			return "";
		}
		$where .= ($channel) ? " AND FIND_IN_SET(agent_name, '$channel')" : "";
		$where .= ($start_time) ? " AND time >= " . $start_time : '';
		$where .= ($end_time) ? " AND time <= " . $end_time : '';
		if($status){
			$where .= " AND orderStatus =" . $status;
		}elseif( $status == 0){
			$where .= " AND orderStatus =" . $status;
		}
		if($userName){
			$where .=" AND (`userName` = '". $userName ."' OR `oid` ='". $userName ."')";
		}
		$result = $this->_ms_agent_orders->select('COUNT(1) AS total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	//当天平均每小时注册人数
	public function getAvgRegsters($game, $channel, $start_date, $end_date, $offset, $length){
		if(!$channel){
			return null;
		}
		$limit = " LIMIT $offset, $length";
		$where = "1 AND `regs` != 0";
		if($game){
			$where .= " AND `game` = '$game'";
		}
		if($channel){
			$where .= " AND `channel` = '$channel'";
		}
		if($start_date){
			$where .= " AND `day` >='$start_date'";
		}
		if($end_date){
			$where .= " AND `day` <='$end_date'";
		}
		$sql = "SELECT `hour`, `regs` FROM ms_intermodal_hour WHERE $where order by hour desc $limit";
		$data = MODEL::getBySql($sql);
		return $data;
	}

	//统计在线总条数(total)
	public function getAvgRegstersNum($game, $channel, $start_date, $end_date){
		$where = "1 AND `regs` != 0";
		if($game){
			$where .= " AND `game` = '$game'";
		}
		if($channel){
			$where .= " AND `channel` = '$channel'";
		}
		if($start_date){
			$where .= " AND `day` >='$start_date'";
		}
		if($end_date){
			$where .= " AND `day` <='$end_date'";
		}
		$sql = "SELECT COUNT(`id`) as num FROM ms_intermodal_hour WHERE $where";
		$data = MODEL::getBySql($sql);
		return $data;
	}

	public function getAvgOnlines($game, $channel, $start_date, $end_date, $offset, $length){
		if(!$channel){
			return null;
		}
		$limit = " LIMIT $offset, $length";
		$where = "1 AND `online_user` != 0";
		if($game){
			$where .= " AND `game` = '$game'";
		}
		if($channel){
			$where .= " AND `channel` = '$channel'";
		}
		if($start_date){
			$where .= " AND `day` >= '{$start_date}'";
		}
		if($end_date){
			$where .= " AND `day` <= '{$end_date}'";
		}

		$sql = "SELECT `hour`, `online_user`, `online` FROM ms_intermodal_hour WHERE $where order by hour desc $limit";
		$data = MODEL::getBySql($sql);
		return $data;
	}

	//在线总数供分页使用
	public function getAvgOnlinesNum($game, $channel, $start_date, $end_date){
		if(!$channel){
			return null;
		}
		$where = "1 AND `online_user` != 0";
		if($game){
			$where .= " AND `game` = '$game'";
		}
		if($channel){
			$where .= " AND `channel` = '$channel'";
		}
		if($start_date){
			$where .= " AND `day` >= '{$start_date}'";
		}
		if($end_date){
			$where .= " AND `day` <= '{$end_date}'";
		}

		$sql = "SELECT COUNT(1) AS num FROM ms_intermodal_hour WHERE $where order by hour desc";
		$data = MODEL::getBySql($sql);
		return $data ? $data[0]['num'] : '';
	}

	public function getMemberChannel() {
		//渠道
		$sql = "SELECT COUNT( * ) AS num, m.channel, c.channel AS name FROM  `ms_members` m LEFT JOIN ms_channel c ON m.channel = c.alias WHERE c.channel IS NOT NULL GROUP BY m.channel";
		$channels = Model::getBySql($sql);
		return $channels;
	}

	/**
	 * 取得未获取区域的用户列表
	 *
	 * @param intval $offset
	 * @param intval $row
	 * @return array
	 */
	public function getNorAreaMemberList($row=null) {
		if($row !== null) {
			$limit = intval($row);
		}
		$where = '`joinIp` != "" AND `province` = ""';
		return $this->_ms_members->select('*', $where, null, 'id DESC', $limit);
	}

	/**
	 * 取得获取区域的用户列表
	 *
	 * @param intval $offset
	 * @param intval $row
	 */
	public function getAreaList($game, $channel, $start, $end, $offset, $row, $keywords, $area, $apkNum, $sumString, $specialString) {
		if($end){
			$end = strtotime($end)+85399;
		}
		$where = ' 1 ';
		$where .= ($game) ? " AND `gameAlias` = '" . $game . "' " : '';
		$where .= ($channel) ? " AND `channelId` = '" . $channel . "' " : '';
		$where .= ($start) ? " AND `jointime` >= " . strtotime($start) : '';
		$where .= ($end) ? " AND `jointime` <= " . $end : '';
		$where .= ($keywords) ? " AND (`province` LIKE '%" . $keywords . "%' OR `city` LIKE '%" . $keywords . "%') " : "";
		$where .= ($apkNum) ? " AND `apkNum` = '" . $apkNum . "' " : '';
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($area == 1){
			$area = ', province';
		}elseif($area == 2){
			$area = ', city';
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		$sql = "SELECT COUNT(id) as total, `province`, `city`, `gameAlias`, `jointime`, `channelId`, `channelName`, `gameName`, `apkNum` FROM ms_member WHERE " . $where . " GROUP BY `gameAlias`, `channelId`, `apkNum` $area ORDER BY total DESC LIMIT " . $limit;
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 取得获取区域的用户总数
	 */
	public function getAreaListTotal($game, $channel, $start, $end, $keywords, $area, $apkNum, $sumString, $specialString) {
		if($end){
			$end = strtotime($end)+85399;
		}
		$where = ' 1 ';
		$where .= ($game) ? " AND `gameAlias` = '" . $game . "' " : '';
		$where .= ($channel) ? " AND `channelId` = '" . $channel . "' " : '';
		$where .= ($start) ? " AND `jointime` >= " . strtotime($start) : '';
		$where .= ($end) ? " AND `jointime` <= " . $end : '';
		$where .= ($keywords) ? " AND (`province` LIKE '%" . $keywords . "%' OR `city` LIKE '%" . $keywords . "%') " : "";
		$where .= ($apkNum) ? " AND `apkNum` = '" . $apkNum . "' " : '';
		$where .= ($sumString) ? " AND gameAlias IN (" . $sumString . ") " : '';
		if($area == 1){
			$area = ', province';
		}elseif($area == 2){
			$area = ', city';
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		$sql = "SELECT COUNT(id) as total, `province`, `city`, `gameAlias`, `jointime`, `channelId`, `channelName`, `gameName`, `apkNum` FROM ms_member WHERE " . $where . " GROUP BY `gameAlias`, `channelId`, `apkNum`" . $area;
		$res = Model::getBySql($sql);
		$result = count($res);
		return $result;
	}

	/**
	 * 取得角色管理列表
	 */
	public function getrole($userid, $gid=null) {
		$where = ' 1 ';
		$where .= ($userid) ? " AND `id` = '" . $userid . "' " : '';
		$where .= ($gid) ? " AND `gid` = '" . $gid . "' " : '';
		$sql = "SELECT `id`,`uid` FROM role WHERE ".$where." GROUP BY id";
		$data = Model::getBySql($sql);
		return $data;
	}

	/**
     * 取得综合数据列表
     *
     */
	public function getConsumption($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22 || $gid == 15) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}

		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		// 筛选专服
		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sort = $sort ? $sort : 'amount';
			$params = $displayMode == 'details' ? ' , g.specialName ' : '';
			$groupBy = $displayMode == 'details' ? '  GROUP BY g.upperName, g.specialName ' : ' GROUP BY g.upperName ';
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName ".$params." FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where.$groupBy." ORDER BY ".$sort." DESC LIMIT ".$limit;

		}elseif ($refine == 2) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.specialName';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC LIMIT ".$limit;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ORDER BY ".$sort."  DESC LIMIT ".$limit;
			}
			
		}elseif ($refine == 3) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.name';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC LIMIT ".$limit;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ORDER BY ".$sort." DESC LIMIT ".$limit;
			}

		}elseif ($refine == 4) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'i.channelId';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC LIMIT ".$limit;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId ORDER BY ".$sort." DESC LIMIT ".$limit;
			}
		
		}elseif ($refine == 5) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'amount';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC LIMIT ".$limit;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ORDER BY ".$sort." DESC LIMIT ".$limit;
			}
		}elseif ($refine == 6) {
			if (empty($sort) || $sort == 'date') {
				$sort = 'i.date';
			}
			$sql = "SELECT i.`id`, SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC LIMIT ".$limit;
		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where."ORDER BY i.date DESC LIMIT ".$limit;
		}
		$result = Model::getBySql($sql);
		return $result;
		/*$extra = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if(empty($game) && empty($channel)){
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($agentChannel){
				$where .= " AND `channelId` IN (" . $agentChannel . ")";
			}
			if($sumString && !$game){
				$string = " AND gameAlias IN (" . $sumString . ") ";
				if($specialString){
					$string = " AND gameAlias IN (" . $specialString . ") ";
				}
				$where .= $string;
			}
			if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}
			if ($gsSource == '1') {
				$where .= " AND apkNum IN ('耀非', '炎游', '南宇') ";
				$extra = ', `apkNum`';
			}elseif($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
				$where .= "  AND `channelId` != '111111'";//因历史遗留问题，故剔除易接包号为分包的数据
				$extra = ', `channelId`, `apkNum`';
			}elseif(!empty($gsSource)){
				$where .= " AND `apkNum` = '$gsSource'";
			}
			$sql = 'SELECT `gameAlias`, `gameName`, `date`, SUM(`newUser`) AS newUser, SUM(`oldUser`) AS oldUser, SUM(`activeUser`) AS activeUser, SUM(`amount`) AS amount, SUM(`payUser`) AS payUser, SUM(`newPayUser`) AS newPayUser, SUM(`newAmount`) AS newAmount, SUM(`oldPayUser`) AS oldPayUser, SUM(`oldAmount`) AS oldAmount, SUM(`firstRecharge`) AS firstRecharge, `channelId`, `channelName`, `apkNum` FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`' . $extra . ' ORDER BY `date` DESC, `id` ASC LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($game && empty($channel)) {
			$where .= " AND `gameAlias` = '$game'";
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($agentChannel){
				$where .= " AND `channelId` IN (" . $agentChannel . ")";
			}
			if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}elseif($game) {
				$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
			}
			if ($gsSource == '1') {
				$where .= " AND apkNum IN ('耀非', '炎游', '南宇') ";
				$extra = ', `apkNum`';
			}elseif($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
				$where .= "  AND `channelId` != '111111'";//因历史遗留问题，故剔除易接包号为分包的数据
				$extra = ', `channelId`, `apkNum`';
			}elseif(!empty($gsSource)){
				$where .= " AND `apkNum` = '$gsSource'";
			}
			$sql = 'SELECT `gameAlias`, `gameName`, `date`, SUM(`newUser`) AS newUser, SUM(`oldUser`) AS oldUser, SUM(`activeUser`) AS activeUser, SUM(`amount`) AS amount, SUM(`payUser`) AS payUser, SUM(`newPayUser`) AS newPayUser, SUM(`newAmount`) AS newAmount, SUM(`oldPayUser`) AS oldPayUser, SUM(`oldAmount`) AS oldAmount, SUM(`firstRecharge`) AS firstRecharge, `channelId`, `channelName`, `apkNum` FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`' . $extra . ' ORDER BY `date` DESC, `id` ASC LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}else {
			//$where .= " AND `gameAlias` = '$game'";
			$where .= " AND `channelId` = '$channel'";
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($apkNum){
				$where .= " AND `apkNum` = '$apkNum'";
			}
			if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}elseif($game) {
				$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
			}
			if($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
			}
			$sql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `date`, `apkNum`, SUM(`newUser`) AS newUser, SUM(`oldUser`) AS oldUser, SUM(`activeUser`) AS activeUser, SUM(`amount`) AS amount, SUM(`payUser`) AS payUser, SUM(`newPayUser`) AS newPayUser, SUM(`newAmount`) AS newAmount, SUM(`oldPayUser`) AS oldPayUser, SUM(`oldAmount`) AS oldAmount, SUM(`firstRecharge`) AS firstRecharge FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`, `apkNum` ORDER BY `date` DESC, `id` ASC LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}*/
	}

	/**
     * 取得硬核数据列表
     *
     */
	public function yhGetConsumption($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}

		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		// 筛选专服
		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sort = $sort ? $sort : 'amount';
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.upperName ORDER BY ".$sort." DESC";

		}elseif ($refine == 2) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.specialName';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ORDER BY ".$sort."  DESC";
			}
			
		}elseif ($refine == 3) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.name';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ORDER BY ".$sort." DESC";
			}

		}elseif ($refine == 4) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'i.channelId';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId ORDER BY ".$sort." DESC";
			}
		
		}elseif ($refine == 5) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'amount';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ORDER BY ".$sort." DESC";
			}
		}elseif ($refine == 6) {
			if (empty($sort) || $sort == 'date') {
				$sort = 'i.date';
			}
			$sql = "SELECT i.`id`, SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where."ORDER BY i.date DESC";
		}	

		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得taptap综合数据列表
     *
     */
	public function qyGetConsumption($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){

		$where = 1;
		
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}

		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sort = $sort ? $sort : 'amount';
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.upperName ORDER BY ".$sort." DESC";
		}elseif ($refine == 2) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.specialName';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ORDER BY ".$sort."  DESC";
			}
			
		}elseif ($refine == 3) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.name';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ORDER BY ".$sort." DESC";
			}

		}elseif ($refine == 4) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'i.channelId';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId ORDER BY ".$sort." DESC";
			}
		}elseif ($refine == 5) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'amount';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ORDER BY ".$sort." DESC";
			}
		}elseif ($refine == 6) {
			if (empty($sort) || $sort == 'date') {
				$sort = 'i.date';
			}
			$sql = "SELECT i.`id`, SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where."ORDER BY i.date DESC ";
		}		
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetConsumption($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){

		$where = 1;
		
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND i.`apkNum` in (". $apkNum. ")" : " AND i.`apkNum` = '" . $apkNum . "' ";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}

		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sort = $sort ? $sort : 'amount';
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.upperName ORDER BY ".$sort." DESC ";
			
		}elseif ($refine == 2) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.specialName';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ORDER BY ".$sort."  DESC ";
			}
			
		}elseif ($refine == 3) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'g.name';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC ";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ORDER BY ".$sort." DESC";
			}

		}elseif ($refine == 4) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'i.channelId';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, g.alias FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId ORDER BY ".$sort." DESC";
			}
		}elseif ($refine == 5) {
			if (empty($sort) || $sort == 'date') {
				if ($displayMode == 'sum') {
					$sort = 'i.date';
				}else{
					$sort = 'amount';
				}
			}
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum, g.alias, i.channelId FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ORDER BY ".$sort." DESC";
			}
		}elseif ($refine == 6) {
			if (empty($sort) || $sort == 'date') {
				$sort = 'i.date';
			}
			$sql = "SELECT i.`id`, SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ORDER BY ".$sort." DESC";
		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where."ORDER BY i.date DESC";
		}		
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得综合数据总数
     *
     */
	public function getConsumptionTotal($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName){

		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else {
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$params = $displayMode == 'details' ? ' , g.specialName ' : '';
			$groupBy = $displayMode == 'details' ? '  GROUP BY g.upperName, g.specialName ' : ' GROUP BY g.upperName ';
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName ".$params." FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where.$groupBy;
		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ";
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias";
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId";
			}

		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ";
			}

		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";

		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;

		}
		$result = Model::getBySql($sql);
		$result = count($result);
		return $result;
		/*$extra = '';
		if(empty($game) && empty($channel)){
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($agentChannel){
				$where .= " AND `channelId` IN (" . $agentChannel . ")";
			}
			if($sumString && !$game){
				$string = " AND gameAlias IN (" . $sumString . ") ";
				if($specialString){
					$string = " AND gameAlias IN (" . $specialString . ") ";
				}
				$where .= $string;
			}
			if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}
			if ($gsSource == '1') {
				$where .= " AND apkNum IN ('耀非', '炎游', '南宇') ";
				$extra = ', `apkNum`';
			}elseif($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
				$where .= "  AND `channelId` != '111111'";//因历史遗留问题，故剔除易接包号为分包的数据
				$extra = ', `apkNum`';
			}elseif(!empty($gsSource)){
				$where .= " AND `apkNum` = '$gsSource'";
			}
			$sql = 'SELECT COUNT(1) FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`' . $extra;
			$res = Model::getBySql($sql);
			$result = count($res);
			return $result;
		}elseif ($game && empty($channel)) {
			//$where .= " AND `gameAlias` = '$game'";
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($agentChannel){
				$where .= " AND `channelId` IN (" . $agentChannel . ")";
			}
			if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}elseif($game) {
				$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
			}
			if ($gsSource == '1') {
				$where .= " AND apkNum IN ('耀非', '炎游', '南宇') ";
				$extra = ', `apkNum`';
			}elseif($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
				$where .= "  AND `channelId` != '111111'";//因历史遗留问题，故剔除易接包号为分包的数据
				$extra = ', `apkNum`';
			}elseif(!empty($gsSource)){
				$where .= " AND `apkNum` = '$gsSource'";
			}
			$sql = 'SELECT COUNT(1) FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`' . $extra;
			$res = Model::getBySql($sql);
			$result = count($res);
			return $result;
		}else {
			//$where .= " AND `gameAlias` = '$game'";
			$where .= " AND `channelId` = '$channel'";
			if($start_date){
				$where .= " AND `date` >= '$start_date'";
			}
			if($end_date){
				$where .= " AND `date` <= '$end_date'";
			}
			if($apkNum){
				$where .= " AND `apkNum` = '$apkNum'";
			}
			if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
				$where .= " AND gameAlias IN (".$gameStr.") ";
			}elseif($game) {
				$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
			}
			if($gsSource == '投放'){
				$where .= " AND `apkNum` LIKE '%分包%'";
			}
			$sql = 'SELECT COUNT(1) FROM ms_integrated_daily WHERE ' . $where . ' GROUP BY `gameAlias`, `date`, `apkNum`';
			$res = Model::getBySql($sql);
			$result = count($res);
			return $result;
		}*/
	}

	/**
     * 取得taptap综合数据总数
     *
     */
	public function qyGetConsumptionTotal($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		
		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else {
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.upperName";
			
		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ";
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias";
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId";
			}

		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ";
			}

		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";

		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;

		}
		$result = Model::getBySql($sql);
		$result = count($result);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetConsumptionTotal($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		
		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND i.`apkNum` in (". $apkNum. ")" : " AND i.`apkNum` = '" . $apkNum . "' ";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else {
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.upperName";

		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.specialName ";
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias";
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.channelId";
			}

		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.apkNum ";
			}

		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where." GROUP BY i.date ";

		}elseif ($refine == 7) {
			$sql = "SELECT g.upperName, g.`name`, i.channelName, i.date, i.apkNum, i.amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;

		}
		$result = Model::getBySql($sql);
		$result = count($result);
		return $result;
	}

	/**
     * 取得综合数据汇总
     *
     */
	public function getConsumptionSummary($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		
		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			
		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;

		}elseif ($refine == 7) {
			$sql = "SELECT SUM(i.amount) AS amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;
		}	
		$result = Model::getBySql($sql);
		return $result;
		/*if($game){
			$where .= " AND `gameAlias` = '$game'";
		}*/
		/*if($channel){
			$where .= " AND `channelId` = '$channel'";
		}else{
			if($agentChannel){
				$where .= " AND `channelId` IN (" . $agentChannel . ")";
			}
		}
		if($start_date){
			$where .= " AND `date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `date` <= '$end_date'";
		}
		if($apkNum){
			$where .= " AND `apkNum` = '$apkNum'";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}
		if ($gsSource == '1') {
			$where .= " AND apkNum IN ('耀非', '炎游', '南宇') ";
		}elseif($gsSource == '投放'){
			$where .= " AND `apkNum` LIKE '%分包%'";
			$where .= "  AND `channelId` != '111111'";//因历史遗留问题，故剔除易接包号为分包的数据
		}elseif(!empty($gsSource)){
			$where .= " AND `apkNum` = '$gsSource'";
		}
		$sql = 'SELECT SUM(`newUser`) AS newUser, SUM(`oldUser`) AS oldUser, SUM(`activeUser`) AS activeUser, SUM(`amount`) AS amount, SUM(`payUser`) AS payUser, SUM(`newPayUser`) AS newPayUser, SUM(`newAmount`) AS newAmount, SUM(`oldPayUser`) AS oldPayUser, SUM(`oldAmount`) AS oldAmount, SUM(`firstRecharge`) AS firstRecharge FROM ms_integrated_daily WHERE ' . $where;
		$result = Model::getBySql($sql);
		return $result;*/
	}

	/**
     * 取得taptap综合数据汇总
     *
     */
	public function qyGetConsumptionSummary($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName){
		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$where .= " AND i.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			
		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;

		}elseif ($refine == 7) {
			$sql = "SELECT SUM(i.amount) AS amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;
		}	
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetConsumptionSummary($game, $channel, $start_date, $end_date, $apkNum, $agentChannel = '', $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		if($start_date){
			$where .= " AND i.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND i.`date` <= '$end_date'";
		}

		if ($channel) {
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND i.`channelId` in (". $channel. ")" : " AND i.`channelId` = '" . $channel . "' ";
		}

		if($apkNum){
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND i.`apkNum` in (". $apkNum. ")" : " AND i.`apkNum` = '" . $apkNum . "' ";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($sumString && !$game){
			$string = " AND g.`alias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND g.`alias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND g.`alias` IN (".$gameStr.") ";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND i.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND i.`source` = 0 ";
		}
		if($status){
			$where .= " AND i.`status` = '$status' ";
		}else{
			$where .= " AND i.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;

		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.upperName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
			
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.specialName, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}

		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}else{
				$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.channelName, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(i.newUser) AS newUser, SUM(i.oldUser) AS oldUser, SUM(i.activeUser) AS activeUser, SUM(i.amount) AS amount, SUM(i.payUser) AS payUser, SUM(i.newPayUser) AS newPayUser, SUM(i.newAmount) AS newAmount, SUM(i.oldPayUser) AS oldPayUser, SUM(i.oldAmount) AS oldAmount, g.name, i.date, i.channelName, g.alias, i.channelId, i.apkNum FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE ".$where;

		}elseif ($refine == 7) {
			$sql = "SELECT SUM(i.amount) AS amount FROM ms_integrated_daily i LEFT JOIN ms_game g ON i.gameAlias = g.alias WHERE " . $where;
		}	
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取长尾渠道列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getCommittee($offset=null, $row=100, $channel=null, $game=null){
		$where = 1;
		if($channel){
			$where .= " AND `channelId` = '$channel'";
		}
		if($game){
			$where .= " AND `gameAlias` = '$game'";
		}
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		$sql = 'SELECT DISTINCT `apkNum` FROM ms_member WHERE ' . $where .' LIMIT ' . $limit;
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取上级游戏列表(超级管理员组)
	 * @return array
	 */
	public function getUpperList() {
		$sql = 'SELECT DISTINCT `upperName` FROM ms_game WHERE `upperName` != ""';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取专服游戏列表(超级管理员组)
	 * @return array
	 */
	public function getSpecialList($upperName) {
		$sql = 'SELECT DISTINCT `specialName` FROM ms_game WHERE `upperName` = "' . $upperName . '" AND `specialName` !=""';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取乾游的总充值
	 * @return array
	 */
	public function platformRechargeTotal($game, $start_date, $end_date, $sumString, $specialString) {
		if($game){
			$where .= " AND `gameAlias` = '$game'";
		}
		if($start_date){
			$where .= " AND `time` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `time` <= '$end_date'";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		$sql = 'SELECT SUM(`money`) AS total FROM ms_order WHERE orderStatus = "1" AND channelId = "160068" ' . $where;
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 充值排行榜
     */
	public function paidList($sumString, $specialString, $game, $channel, $apkNum, $start_date, $end_date, $gid, $gameStr){
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}

		if($channel){
			$where .= " AND `channelId` = '$channel'";
		}
		if($apkNum){
			$where .= " AND `apkNum` = '$apkNum'";
		}
		if($start_date){
			$where .= " AND `time` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `time` <= '$end_date'";
		}

		$sql = 'SELECT SUM(money) as paid, `gameAlias`, `gameName`, `channelId`, `channelName`, `userName`, `apkNum`, `roleName`, `roleId`, `server` FROM ms_order WHERE orderStatus = 1 ' . $where . ' GROUP BY userName ORDER BY paid DESC LIMIT 100';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 修改玩家密码
	 */
	public function pwEdit($uid, $password) {
		$sql = 'UPDATE  ms_member SET `password` = "' . $password . '" WHERE `userName` = "' . $uid . '" LIMIT 1';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取上级游戏列表(GS角色组)
	 * @return array
	 */
	public function getUpperListGs($game) {
		$sql = 'SELECT DISTINCT `upperName` FROM ms_game WHERE `upperName` != "" AND `alias` IN ('.$game.')';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取专服游戏列表(超级管理员组)
	 * @return array
	 */
	public function getSpecialListGs($upperName) {
		$sql = 'SELECT DISTINCT `specialName` FROM ms_game WHERE `upperName` = "' . $upperName . '" AND `specialName` !=""';
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     *  获取角色列表
     */
	public function getRoleInfo($offset=null, $length=25, $game, $channel, $userName, $roleName, $apkNum, $sumString, $specialString, $gameStr, $roleId, $start_date, $end_date){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = "1";
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '" . $game . "'";
		}
		if($channel){
			$where .= " AND channelId = '" . $channel . "'";
		}
		if($userName){
			$where .= " AND  userName = '" . $userName . "'";
		}
		if($apkNum){
			$where .= " AND apkNum = '" . $apkNum . "'";
		}
		if($roleName){
			$where .= " AND roleName = '" . $roleName . "'";
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "'";
		}
		if (!$game && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}
		if (empty($start_date) && empty($end_date)) {
			$where .= ' AND isFirst= 1';
		}else{
			$where .= $start_date ? " AND day >= '" . $start_date . "'": '';
			$where .= $end_date ? " AND day <= '" . $end_date . "'": '';
		}
		return $this->_ms_role->select('*', $where, null, '`id` DESC', $limit);
	}

	/**
     *  获取角色列表总条数
     */
	public function getRoleInfoTotal($game, $channel, $userName, $roleName, $apkNum, $sumString, $specialString, $gameStr, $roleId, $start_date, $end_date){
		$where = "1";
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '" . $game . "'";
		}
		if($channel){
			$where .= " AND channelId = '" . $channel . "'";
		}
		if($userName){
			$where .= " AND userName = '" . $userName . "'";
		}
		if($apkNum){
			$where .= " AND apkNum = '" . $apkNum . "'";
		}
		if($roleName){
			$where .= " AND roleName = '" . $roleName . "'";
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "'";
		}
		if (!$game && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}
		if (empty($start_date) && empty($end_date)) {
			$where .= ' AND isFirst= 1';
		}else{
			$where .= $start_date ? " AND day >= '" . $start_date . "'": '';
			$where .= $end_date ? " AND day <= '" . $end_date . "'": '';
		}
		$result = $this->_ms_role->select('COUNT(1) AS total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取角色最新登录或创角日期
	 */
	public function getRoleMTime($game, $roleId, $userName, $create) {
		if ($create != 1) {
			$where = ' AND isFirst= 1';
		}else{
			$where = '';
		}
		$sql = 'SELECT `time` FROM ms_role_seted WHERE `gameAlias` = "' . $game . '" AND `roleId` ="' . $roleId . '"AND `userName` = "' . $userName .'" AND type = "server"'.$where.' ORDER BY time DESC LIMIT 1';
		$result = Model::getBySql($sql);
		return $result[0]['time'];
	}

	/**
	 * 获取玩家最后点击充值日期
	 */
	public function getRolePayTime($game, $roleId, $userName) {
		$where = '1';
		if($game){
			$where .= " AND gameAlias = '" . $game . "'";
		}
		if($roleId){
			$where .= " AND roleId = '" . $roleId . "'";
		}
		if($userName){
			$where .= " AND userName = '" . $userName . "'";
		}
		$result = $this->_ms_agent_orders->select('time', $where, null, '`time` DESC', '1');
		/*$sql = 'SELECT `time` FROM ms_order WHERE `gameAlias` = "' . $game . '" AND `roleId` ="' . $roleId . '"AND `userName` = "' . $userName . '" ORDER BY time DESC LIMIT 1';
		$result = Model::getBySql($sql);*/
		return $result[0]['time'];
	}

	/**
	 * 获取GS区服数据
	 */
	public function getGsData($offset = null, $length = null, $sumString, $specialString, $game, $server, $start_date, $end_date) {
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		$expand =  '';
		if($sumString && !$game){
			if($specialString){
				$str = strpos($specialString, ',');
				if ($str) {
					$string = " AND `gameAlias` IN (" . $specialString . ") ";
				}else {
					$string = " AND `gameAlias` = $specialString ";
				}
				$expand =  '`oldServer`, ';
			}else {
				$str = strpos($sumString, ',');
				if ($str) {
					$string = " AND `gameAlias` IN (" . $sumString . ") ";
				}else {
					$string = " AND `gameAlias` = $sumString ";
				}
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '$game'";
			$expand =  '`oldServer`, ';
		}
		if($server){
			$where .= " AND `oldServer` = '$server'";
		}else{
			$where .= " AND `oldServer` != ''";
		}
		if($start_date){
			$where .= " AND `time` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `time` <= '$end_date'";
		}

		$sql = "SELECT SUM(money) AS payTotal, FROM_UNIXTIME(`time`, '%Y-%m-%d') AS day, $expand `gameName`, `gameAlias` FROM ms_order WHERE $where AND `orderStatus` = 1 GROUP BY FROM_UNIXTIME(`time`, '%Y-%m-%d'), $expand `gameAlias` ORDER BY `time` DESC LIMIT $limit";
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取GS区服数据条数
	 */
	public function getGsDataTotal($sumString, $specialString, $game, $server, $start_date, $end_date) {
		$where = 1;
		$expand =  '';
		if($sumString && !$game){
			if($specialString){
				$str = strpos($specialString, ',');
				if ($str) {
					$string = " AND `gameAlias` IN (" . $specialString . ") ";
				}else {
					$string = " AND `gameAlias` = $specialString ";
				}
				$expand =  '`oldServer`, ';
			}else {
				$str = strpos($sumString, ',');
				if ($str) {
					$string = " AND `gameAlias` IN (" . $sumString . ") ";
				}else {
					$string = " AND `gameAlias` = $sumString ";
				}
			}
			$where .= $string;
		}elseif($game){
			$where .= " AND `gameAlias` = '$game'";
			$expand =  '`oldServer`, ';
		}
		if($server){
			$where .= " AND `oldServer` = '$server'";
		}else{
			$where .= " AND `oldServer` != ''";
		}
		if($start_date){
			$where .= " AND `time` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `time` <= '$end_date'";
		}

		$sql = "SELECT SUM(money) AS payTotal, FROM_UNIXTIME(`time`, '%Y-%m-%d') AS day, $expand `gameName`, `gameAlias` FROM ms_order WHERE $where AND `orderStatus` = 1 GROUP BY FROM_UNIXTIME(`time`, '%Y-%m-%d'), $expand `gameAlias`";
		$result[0] = Model::getBySql($sql);
		$result[1] = count($result[0]);
		return $result;
	}


	/**
     * 储值排行
     */
	public function paidList2($sumString, $specialString, $game, $channel, $apkNum, $start_date, $end_date, $gid, $gameStr, $ranking){
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if (!$game && ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22) && $gameStr) {
			$where .= " AND gameAlias IN (".$gameStr.") ";
		}elseif($game) {
			$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		}

		if($channel){
			$where .= " AND `channelId` = '$channel'";
		}
		if($apkNum){
			$where .= " AND `apkNum` = '$apkNum'";
		}
		if($start_date){
			$where .= " AND `time` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND `time` <= '$end_date'";
		}
		if(empty($ranking)){
			$ranking = 100;
		}

		$sql = 'SELECT SUM(money) as paid, `gameAlias`, `gameName`, `channelId`, `channelName`, `userName`, `apkNum`, `roleName`, `roleId`, `server` FROM ms_order_tmp WHERE 1 ' . $where . ' GROUP BY userName ORDER BY paid DESC LIMIT '. $ranking;
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得利润数据列表
     *
     */
	public function getProfit($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = '', $operation = ''){
		if ($refine == 2 || $refine == 3 || $refine == 5) {
			$where = '';
			$where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
			$where .= $type ? " AND p.`type` = '" . $type . "' " : '';	
			$where .= $channel ? " AND p.`channelId` = '" . $channel . "' " : '';
			if($source == 1){
				$where .= " AND p.`source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND p.`source` = 0 ";
			}
			if($status){
				$where .= " AND p.`status` = '$status' ";
			}else{
				$where .= " AND p.`status` = 1 ";
			}
		}else{
			$where = 1;
			$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
			$where .= $type ? " AND type = '" . $type . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			if($source == 1){
				$where .= " AND `source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND `source` = 0 ";
			}
			if($status){
				$where .= " AND `status` = '$status' ";
			}else{
				$where .= " AND `status` = 1 ";
			}
		}

		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND gameAlias in (" . $gameStr . ") ";
			}else {
				$where .= " AND gameAlias = $gameStr";
			}
		}

		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
		
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if ($refine == 1) {
			$sql = 'SELECT SUM(`amount`) AS amount, SUM(`cpAmount`) AS cpAmount, SUM(`channelAmount`) AS channelAmount, SUM(`profit`) AS profit, SUM(`adPay`) AS adPay, SUM(`exPay`) AS exPay, SUM(`disAmount`) AS disAmount, date, SUM(`actualPay`) AS actualPay, SUM(`income`) AS income FROM ms_profit_daily WHERE '.$where.' GROUP BY `date` ORDER BY date DESC LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 2) {
			$sql = 'SELECT g.`specialName`, SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`exPay`) AS exPay, SUM(p.`disAmount`) AS disAmount, p.`date`, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where.' GROUP BY g.`specialName`, p.`date` ORDER BY p.`date` DESC , g.`specialName` LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 3) {
			$sql = 'SELECT g.`name`, SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`exPay`) AS exPay, SUM(p.`disAmount`) AS disAmount, p.`date`,p.`gameAlias`, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND  g.`specialName` = "'.$specialName.'"'. $where.' GROUP BY g.`alias`, p.`date` ORDER BY p.`date` DESC, g.`name`LIMIT ' . $limit;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 4) {
			$where .= $game ? " AND gameAlias = '" . $game . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
			return $this->_ms_profit->select('*', $where, '', 'date DESC', $limit);
		}elseif ($refine == 5) {

			if($operation == 'report'){

				$limit = '0,99999';
				// 非乾游数据
				$sql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`apkNum`, p.`gameAlias`, 
				SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date`, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income 
				FROM  ms_profit_daily  as p 
				LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
				LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` 
				WHERE 1 '.$where.' AND p.`channelId` != "160068" 
				GROUP BY p.`gameAlias` , p.`channelName`, p.`apkNum` 
				ORDER BY p.`date` DESC 
				LIMIT ' . $limit;
				$result = Model::getBySql($sql);
	
				// 乾游数据
				$qySql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`gameAlias`, 
				SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date`, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income 
				FROM  ms_profit_daily  as p 
				LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
				LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` 
				WHERE 1 '.$where.' AND p.`channelId` = "160068" 
				GROUP BY p.`gameAlias` , p.`channelName` 
				ORDER BY p.`date` DESC 
				LIMIT ' . $limit;
				$qyResult = Model::getBySql($qySql);

				$result = array_merge($result, $qyResult);

			} else{

				$sql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE 1 '.$where.' GROUP BY g.`upperName` ORDER BY g.`upperName` LIMIT ' . $limit;
				$result = Model::getBySql($sql);

			}
			
			return $result;
		}
	}

	/**
     * 取得利润数据总条数
     *
     */
	public function getProfitTotal($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = ''){
		if ($refine == 2 || $refine == 3 || $refine == 5) {
			$where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
			$where .= $type ? " AND p.`type` = '" . $type . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			if($source == 1){
				$where .= " AND p.`source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND p.`source` = 0 ";
			}
			if($status){
				$where .= " AND p.`status` = '$status' ";
			}else{
				$where .= " AND p.`status` = 1 ";
			}
		}else{
			$where = 1;
			$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
			$where .= $type ? " AND type = '" . $type . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			if($source == 1){
				$where .= " AND `source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND `source` = 0 ";
			}
			if($status){
				$where .= " AND `status` = '$status' ";
			}else{
				$where .= " AND `status` = 1 ";
			}
		}

		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';

		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND gameAlias in (" . $gameStr . ") ";
			}else {
				$where .= " AND gameAlias = $gameStr";
			}
		}
		if ($refine == 1) {
			$sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily WHERE '.$where.' GROUP BY `date`';
			$result = Model::getBySql($sql);
			return count($result);
		}elseif ($refine == 2) {
			$sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where.' GROUP BY g.`specialName`, p.`date`';
			$result = Model::getBySql($sql);
			return count($result);
		}elseif ($refine == 3) {
			$sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND  g.`specialName` = "'.$specialName.'"'. $where.' GROUP BY g.`alias`, p.`date`';
			$result = Model::getBySql($sql);
			return count($result);
		}elseif ($refine == 4) {
			$where .= $game ? " AND gameAlias = '" . $game . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
			$result = $this->_ms_profit->select('COUNT(1) AS total', $where);
			return $result[0]['total'];
		}elseif ($refine == 5) {
			$sql = 'SELECT g.`upperName` FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE 1 '.$where.' GROUP BY g.`upperName`';
			$result = Model::getBySql($sql);
			return count($result);
		}
	}

	/**
     * 取得利润数据总数
     *
     */
	public function getProfitSummary($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = ''){

		if ($refine == 2 || $refine == 3 || $refine == 5) {
			$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
			$where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
			$where .= $type ? " AND p.`type` = '" . $type . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			if($source == 1){
				$where .= " AND p.`source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND p.`source` = 0 ";
			}
			if($status){
				$where .= " AND p.`status` = '$status' ";
			}else{
				$where .= " AND p.`status` = 1 ";
			}
		}else{
			$where = 1;
			$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
			$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
			$where .= $type ? " AND type = '" . $type . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
			if($source == 1){
				$where .= " AND `source` = 1 ";
			}elseif ($source == 2) {
				$where .= " AND `source` = 0 ";
			}
			if($status){
				$where .= " AND `status` = '$status' ";
			}else{
				$where .= " AND `status` = 1 ";
			}
		}

		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND gameAlias IN (" . $gameStr . ") ";
			}else {
				$where .= " AND gameAlias = " . $gameStr . " ";
			}
		}

		if ($refine == 2) {
			$sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 3) {
			$sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND g.`specialName` = "'.$specialName.'"'. $where;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 4) {
			$where .= $game ? " AND gameAlias = '" . $game . "' " : '';
			$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
			$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
			$sql = 'SELECT SUM(`amount`) AS amount, SUM(`cpAmount`) AS cpAmount, SUM(`channelAmount`) AS channelAmount, SUM(`profit`) AS profit, SUM(`adPay`) AS adPay, SUM(`disAmount`) AS disAmount, SUM(`exPay`) AS exPay, SUM(`actualPay`) AS actualPay, SUM(`income`) AS income FROM ms_profit_daily WHERE '. $where;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 1) {
			$sql = 'SELECT SUM(`amount`) AS amount, SUM(`cpAmount`) AS cpAmount, SUM(`channelAmount`) AS channelAmount, SUM(`profit`) AS profit, SUM(`adPay`) AS adPay, SUM(`disAmount`) AS disAmount, SUM(`exPay`) AS exPay, SUM(`actualPay`) AS actualPay, SUM(`income`) AS income FROM ms_profit_daily WHERE '.$where;
			$result = Model::getBySql($sql);
			return $result;
		}elseif ($refine == 5) {
			$sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE 1 '.$where;
			$result = Model::getBySql($sql);
			return $result;
		}
	}

	/**
     * 取得项目支出总数
     *
     */
	/*public function getGamePay($start_date, $end_date, $upper, $model){
		$where = '';
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		if ($upper) {
			$sql = 'SELECT pay, date, remark, upperName FROM ms_game_pay WHERE upperName = "'.$upper.'"'.$where;
		}else{
			if ($model == 'consumption') {
				$sql = 'SELECT SUM(pay) AS pay, upperName FROM ms_game_pay WHERE 1 '.$where.' GROUP BY upperName';
			}elseif ($model == 'profit') {
				$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
			}else{
				$sql = 'SELECT SUM(pay) AS pay, date FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date';
			}
		}
		$result = Model::getBySql($sql);
		return $result;
	}*/
	/**
     * 取得项目支出总数
     *
     */
	public function getGamePay($start_date, $end_date, $refine, $upper, $special, $alias, $channel, $apkNum, $model, $gameStr = '', $resertSpecialName = ''){

		$where = '';
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		if ($model == 'consumption') {

			// 筛选专服
			if ($resertSpecialName) {
				$where .= " AND specialName NOT IN ($resertSpecialName)";
			}

			if ($channel) {
				$marketChannel = strpos($channel, ',');
				$where .= $marketChannel ? " AND channelId in (". $channel. ")" : " AND channelId = '" . $channel . "' ";
			}

			if ($upper) {
				$where .= " AND upperName = '" . $upper . "' ";
				$where .= $special ? " AND specialName = '" . $special . "' " : '';
				$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';
				$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';

				$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
			}else{
				$sql = 'SELECT SUM(pay) as pay, upperName FROM ms_game_pay WHERE module != 1 '.$where.' GROUP BY upperName';
			}

		}elseif ($model == 'profit') {
			
			$group = '';
			if ($upper) {
				$where .= " AND upperName = '" . $upper . "' ";
				$where .= $special ? " AND specialName = '" . $special . "' " : '';
				$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
				$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
				$group .= ',specialName';
				if ($refine == 3) {
					$group .= ', specialName, gameAlias';
				}
				$sql = 'SELECT SUM(pay) as pay, date, module, remark '.$group.' FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date, module '.$group;
				if ($refine == 4) {
					$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
				}
				/*if ($module == 1) {
					$sql = 'SELECT pay, date FROM ms_game_pay WHERE module = 1 ' . $where;
				}elseif ($module == 2) {
					$sql = 'SELECT pay, date, remark, upperName FROM ms_game_pay WHERE upperName = "'.$upper.'" AND module = 2 AND type = 1 '.$where;
				}elseif ($module == 3) {
					$where .= $special ? " AND specialName = '" . $special . "' " : '';
					$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';
					$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
					$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
					$sql = 'SELECT pay, date, remark, upperName FROM ms_game_pay WHERE upperName = "'.$upper.'" AND module = 3 '.$where;
				}*/
			}else{
				$sql = 'SELECT SUM(pay) as pay, date, module FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date, module';
				if ($refine == 5) {
					$sql = 'SELECT SUM(pay) as pay, module, upperName FROM ms_game_pay WHERE 1 '.$where.' GROUP BY module, upperName';
				}
			}
			//$sql = 'SELECT SUM(pay) as pay, date, module, remark '.$group.' FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date, module '.$group;
			//$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
		}
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得taptap项目支出总数
     *
     */
	public function qyGetGamePay($start_date, $end_date, $refine, $upper, $special, $alias, $channel, $apkNum, $resertSpecialName = ''){
		$where = '';
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';

		if ($resertSpecialName) {
			$where .= " AND specialName NOT IN ($resertSpecialName)";
		}

		if ($upper) {
			// 选择指定项目
			$where .= " AND upperName = '" . $upper . "' ";
			$where .= $special ? " AND specialName = '" . $special . "' " : '';
			$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';

			$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
		}else{
			// 查询全部项目
			$sql = 'SELECT SUM(pay) as pay, upperName FROM ms_game_pay WHERE module != 1 '.$where.' GROUP BY upperName';
		}
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetGamePay($start_date, $end_date, $refine, $upper, $special, $alias, $channel, $apkNum, $resertSpecialName){
		$where = '';
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';

		if ($resertSpecialName) {
			$where .= " AND specialName NOT IN ($resertSpecialName)";
		}

		if ($apkNum) {
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND apkNum in (". $apkNum. ")" : " AND apkNum = '" . $apkNum . "' ";
		}

		if ($upper) {
			// 选择指定项目
			$where .= " AND upperName = '" . $upper . "' ";
			$where .= $special ? " AND specialName = '" . $special . "' " : '';
			$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';

			$sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
		}else{
			// 查询全部项目
			$sql = 'SELECT SUM(pay) as pay, upperName FROM ms_game_pay WHERE module != 1 '.$where.' GROUP BY upperName';
		}

		$result = Model::getBySql($sql);
		return $result;
	}

	/*
	 * 批量获取用户数据
	 */
	public function getBatchesMemberList($uid) {
		$sql = "SELECT * FROM ms_member WHERE userName IN(".$uid.")";

		$result = Model::getBySql($sql);
		return $result;
	}

	/*
	 * 批量修改用户密码
	 */
	public function batchesChangePWD($uid, $password) {
		if (!empty($uid) && !empty($password)) {
			$sql = "UPDATE ms_member SET `password` = '".$password."' WHERE userName IN(".$uid.")";
			$result = Model::getBySql($sql);
			return $result;
		}
	}


	/**
	 * 获取补单折扣列表
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getCompenOrderList($game, $channel, $start, $end, $status, $userName, $offset, $row, $apkNum, $sumString, $specialString, $roleId, $serverId, $orderId) {
		$where = ' 1 ';

		$where .= $channel ? " AND channelId = '" . $channel ."'": '';
		$where .= $start ? " AND time >= " . $start : '';
		$where .= $end ? " AND time <= " . $end : '';
		$where .= $status ? " AND orderStatus = '" . $status ."'": " AND orderStatus = '2'";
		$where .= $userName ? " AND userName = '" . $userName ."'": '';
		$where .= $orderId ? " AND orderId = '" . $orderId ."'": '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum ."'": '';
		$where .= $roleId ? " AND roleId = '" . $roleId ."'": '';
		$where .= $serverId ? " AND server LIKE '%" . $serverId . "%' " : '';

		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		
		$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}

		return $this->_ms_agent_orders->select('*', $where, null, 'time DESC', $limit);
	}

	/**
	 * 获取补单折扣列表总条数
	 *
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getCompenOrderListTotal($game, $channel, $start, $end, $status, $userName, $apkNum, $sumString, $specialString, $roleId, $serverId, $orderId) {
		$where = ' 1 ';

		$where .= $channel ? " AND channelId = '" . $channel ."'": '';
		$where .= $start ? " AND time >= " . $start : '';
		$where .= $end ? " AND time <= " . $end : '';
		$where .= $status ? " AND orderStatus = '" . $status ."'": '';
		$where .= $userName ? " AND userName = '" . $userName ."'": '';
		$where .= $orderId ? " AND orderId = '" . $orderId ."'": '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum ."'": '';
		$where .= $roleId ? " AND roleId = '" . $roleId ."'": '';
		$where .= $serverId ? " AND server LIKE '%" . $serverId . "%' " : '';

		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		
		$where .= ($game) ? " AND gameAlias = '" . $game . "' " : '';
		$result = $this->_ms_agent_orders->select('COUNT(1) AS total', $where);
		return $result;
	}

	/**
     * 取得各游戏的投放支出
     *
     */
	public function getAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND p.`channelId` in (". $channel. ")" : " AND p.`channelId` = '" . $channel . "' ";
		}
		if($apkNum){
			$where .= " AND p.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`upperName` ";

		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.`specialName` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`specialName` ";
			}
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.specialName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ";
			}
		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`channelId` ";
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`apkNum` ";
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, p.date, SUM(p.newlyProfit) as newlyProfit, g.alias, p.channelId, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
		}
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得taptap的投放支出
     *
     */
	public function qyGetAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$where .= " AND p.`channelId` = '$channel'";
		}
		if($apkNum){
			$where .= " AND p.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`upperName` ";

		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.`specialName` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`specialName` ";
			}
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.specialName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ";
			}
		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`channelId` ";
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`apkNum` ";
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, p.date, SUM(p.newlyProfit) as newlyProfit, g.alias, p.channelId, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
		}
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$where .= " AND p.`channelId` = '$channel'";
		}
		if($apkNum){
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND p.`apkNum` in (". $apkNum. ")" : " AND p.`apkNum` = '" . $apkNum . "' ";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		if ($refine == 1) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`upperName` ";
			
		}elseif ($refine == 2) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.upperName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.`specialName` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.`specialName` ";
			}
		}elseif ($refine == 3) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.specialName, p.`date` FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY g.alias ";
			}
		}elseif ($refine == 4) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, g.alias FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`channelId` ";
			}
		}elseif ($refine == 5) {
			if ($displayMode == 'sum') {
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.date, p.channelId FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
			}else{
				$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`apkNum` ";
			}
		}elseif ($refine == 6) {
			$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, p.date, SUM(p.newlyProfit) as newlyProfit, g.alias, p.channelId, p.apkNum FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where." GROUP BY p.`date` ";
		}
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得总投放支出
     *
     */
	public function getSumAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$marketChannel = strpos($channel, ',');
			$where .= $marketChannel ? " AND p.`channelId` in (". $channel. ")" : " AND p.`channelId` = '" . $channel . "' ";
		}
		if($apkNum){
			$where .= " AND p.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}		
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}

		$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where;
		
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 取得taptap总投放支出
     *
     */
	public function qyGetSumAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName = ''){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$where .= " AND p.`channelId` = '$channel'";
		}
		if($apkNum){
			$where .= " AND p.`apkNum` = '$apkNum'";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}		
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}
		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}
		$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where;
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
     * 统计QuickSDK下的酷派 百度 联想的数据
     *
     */
	public function quickGetSumAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName){
		$where = 1;
		$limit = '';
		if($offset !== null || $row !== null) {
			$limit = intval($offset) . ',' . intval($row);
		}
		if($start_date){
			$where .= " AND p.`date` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND p.`date` <= '$end_date'";
		}
		if($channel){
			$where .= " AND p.`channelId` = '$channel'";
		}
		if($apkNum){
			$marketApkNum = strpos($apkNum, ',');
			$where .= $marketApkNum ? " AND p.`apkNum` in (". $apkNum. ")" : " AND p.`apkNum` = '" . $apkNum . "' ";
		}
		if($upperName){
			$where .= " AND g.`upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND g.`specialName` = '$specialName'";
		}
		if($game){
			$where .= " AND g.`alias` = '$game'";
		}		
		if($source == 1){
			$where .= " AND p.`source` = 1 ";
		}elseif ($source == 2) {
			$where .= " AND p.`source` = 0 ";
		}
		if($status){
			$where .= " AND p.`status` = '$status' ";
		}else{
			$where .= " AND p.`status` = 1 ";
		}

		if ($resertSpecialName) {
			$where .= " AND g.`specialName` NOT IN ($resertSpecialName)";
		}
		$sql = "SELECT SUM(p.adPay) as adPay, SUM(p.exPay) as exPay, SUM(p.newlyProfit) as newlyProfit FROM ms_profit_daily p LEFT JOIN ms_game g ON p.gameAlias = g.alias WHERE ".$where;
		
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 根据项目名称获取专服名称
	 */
	public function upperToSecond($upperName)
	{
		$sql = "SELECT `upperName`, `specialName` FROM `ms_game` WHERE `upperName` IN ($upperName) GROUP BY `upperName`,`specialName`";
		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取导出记录数据列表
	 */
	public function getfileManage($offset, $row)
	{
		$sql = 'select * from ms_fileManage '. 'order by id desc'. ' limit '. $offset. ','. $row;
		$fileList = model::getBySql($sql);
		return $fileList;
	}

	/**
	 * 获取导出记录数据总数
	 */
	public function getFileCount()
	{
		$countSql = 'select count(1) as total from ms_fileManage';
		$counArray = model::getBySql($countSql);
		$total = $counArray[0]['total'];
		return $total;
	}

	/**
	 * 获取指定导出记录数据
	 */
	public function getFileParams($id)
	{
		$sql = "select fileName, type, createTime from ms_fileManage where id = '{$id}'";
		$res = model::getBySql($sql);
		return $res;
	}
	/**
	 * 获取用户综合数据权限数据
	 */
	public function getJurisdiction($uid)
	{
		$sql = "select `header_id` from role where uid = '{$uid}'";
		$res = model::getBySql($sql);
		return $res;
	}
	/**
	 * 获取玩家综合数据的权限标题
	 */
	public function getHeader(){
		$sql = "select `header_id`,`header_name` from ms_header ";
		$res = model::getBySql($sql);
		return $res;
	}
}