<?php
class ac {
	public function get_payment($data, $type='daily'){
		$start_date = array_key_exists('start_date', $data) ? $data['start_date'] : '';
		$end_date = array_key_exists('end_date', $data) ? $data['end_date'] : '';
		$game = array_key_exists('game', $data) ? $data['game'] : '';
		$channel = array_key_exists('channel', $data) ? $data['channel'] : '';
		$by = array_key_exists('by', $data) ? $data['by'] : '';
		
		$where = $limit = '';

		if ($t1 = strtotime($start_date)) {
			$where .= " AND o.otime > '" . $t1 . "' ";
		}
		if ($t2 = strtotime($end_date . "23:59:59")){
			$where .= " AND o.otime < '" . $t2 . "' ";
		}
		if (($gameid = trim($game))){
			if($gameid == 1){
				$where .= " AND o.game = '' AND o.type = '平台订单' ";
			}else{
				$where .= " AND o.game = '$gameid' ";
			}
		}
		

		//对游戏部分
		if ('game' == $by) {
			$where .= " AND o.game != '' ";
		}else{
			$where .= " AND o.type != '转点订单' ";
		}

		if ($t1 && $t2) {
			if ($t1 > $t2) {
				return array('code'=>'0', 'msg'=>'没有找到任何数据。');
			}else{
				if (floor(($t2 - $t1)/86400 ) > 90) {
					return array('code'=>'0', 'msg'=>'时间间隔不能超过三个月。');
				}
			}

			if ($t1 > time()) {
				return array('code'=>'0', 'msg'=>'没有找到任何数据。');
			}
		}else {
			if ($type == 'daily') {
				$limit = ' LIMIT 10 ';
			}
		}

		if ($type == 'year') {
			$cols = ' from_unixtime( o.time, "%Y" ) AS date, ';
		}else if ($type == 'month') {
			$cols = ' from_unixtime( o.time, "%Y-%m" ) AS date, ';
		}else {
			$cols = ' from_unixtime( o.time, "%Y-%m-%d" ) AS date, ';
		}

//		$sql = 'SELECT o.oid, ' . $cols . ' sum( o.money ) AS money, count( o.oid ) AS times, count( DISTINCT o.username ) as  passengers, sum( o.money )/count( o.oid ) AS avg, sum( o.money )/count( DISTINCT o.username ) AS arpu FROM `ms_all_orders` o  WHERE 1 ' . $where . 'AND o.status = 1 GROUP BY date ORDER BY o.time DESC ' . $limit;
		//echo $sql;exit;
		
		$sql = ' SELECT o.oid, from_unixtime( o.otime, "%Y-%m-%d" ) AS date, sum( o.omoney) AS money, count( o.oid ) AS times, count( DISTINCT o.ousername) AS passengers, sum( o.omoney) / count( o.oid ) AS avg, sum( o.omoney) / count( DISTINCT o.ousername) AS arpu
FROM `ms_agent_orders` o
WHERE 1
AND o.otime< "1411487999"
AND o.ostatus=1
GROUP BY date
ORDER BY o.otime DESC
' . $limit;
		echo $sql;exit;
		
		$data = model::getBySql($sql);

		return $data;
	}

	public function list_search_ajax($getData, $payment){
		@header("Content-type: application/json");
		if (!empty($getData['start_date']) || !empty($getData['end_time']) || !empty($getData['by']) || intval($getData['game'])) {
			if (!isset($payment['code'])) {
				if (empty($payment)) {
					$data = array('code'=>'0', 'msg'=>'没有找到任何数据');
				}else{
					$string = '';
					foreach ($payment as $key => $value){
						$string .= '
							<tr align="center" class="ajaxrow">
								<td width="16%">' . $value['date'] . '</td>				
								<td width="16%">' . $value['passengers'] . '</td>
								<td width="16%">' . $value['times'] . '</td>	
								<td width="16%">' . $value['money'] . '</td>	
								<td width="16%">' . $value['avg'] . '</td>		
								<td width="20%">' . $value['arpu'] . '</td>
							</tr>					
						';
					}
					$data = array('code'=>'1', 'msg'=>$string);
				}
			}else {
				$data = $payment;
			}
		}else {
			$data = array('code'=>'0', 'msg'=>'没有找到任何数据');
		}

		echo json_encode($data);
		exit;
	}

	public function search_user_info($data){
		if (empty($data['name'])) {
			return array('code'=>'0', 'msg'=>'请输入一个账号。');
		}

		$name = trim($data['name']);
		//取得会员信息及最近订单信息
		$sql = 'SELECT m.mid, m.userid, m.hash_username, IF( o.channel = "", "ttaw", o.channel ) AS site, m.money as surplus, from_unixtime( m.jointime, "%Y-%m-%d" ) AS join_time, from_unixtime( m.logintime, "%Y-%m-%d" ) AS login_time, SUM( o.money ) AS money, max( o.oid ) AS last_order_id, from_unixtime( max( o.time ) , "%Y-%m-%d" ) AS last_order_time, count( o.oid ) as totalpay FROM `blk_member` m LEFT JOIN ms_all_orders o ON m.hash_username = o.hash_username WHERE (m.userid = "' . $name . '" OR m.hash_username = "' . $name . '") AND o.status =1 GROUP BY o.username ORDER BY o.oid ASC LIMIT 1';
		$userinfo = model::getBySql($sql);

		//如果没有订单信息，取到会员信息
		if (empty($userinfo)) {
			$sql = 'SELECT m.mid, m.userid, m.hash_username, IF( m.site = "", "ttaw", m.site ) AS site, from_unixtime( m.jointime, "%Y-%m-%d" ) AS join_time, from_unixtime( m.logintime, "%Y-%m-%d" ) AS login_time FROM `blk_member` m WHERE m.userid = "' . $name . '" OR m.hash_username = "' . $name . '" LIMIT 1';
			$userinfo = model::getBySql($sql);
		}

		if (empty($userinfo)) {
			return array('code'=>'0', 'msg'=>'没有找到该账号任何数据。');
		}
		//取得订单数据
		$sql = 'SELECT o.*, from_unixtime( o.time, "%Y-%m-%d" ) AS order_date, o.`game`, o.type AS paytype, o.game as gamename, o.server as server FROM `ms_all_orders` o WHERE o.hash_username = "' . $userinfo[0]['hash_username'] . '"  ORDER BY o.`oid` DESC';
		$payment = model::getBySql($sql);
		//echo "reter";exit;
		//echo $sql;exit;
		if (empty($payment)) {
			$userinfo[0]['msg']  = '<tr align="center" class="ajaxrow"><td colspan="6">没有储值数据</td></tr>';
		}else{
			$string = '';
			$error_str = '';
			foreach ($payment as $key => $value){
				$agent_payment = model::getBySql('SELECT * FROM `ms_agent_orders` WHERE `oid` = \'' . $value['oid'] .'\'');
				$agent_oid = empty($agent_payment) ? '无' : $agent_payment[0]['agent_oid'];
				$agent_oid_omit = preg_replace('/^(.{15}).*/', '$1...', $agent_oid);
				if ($value['gamename']){
					$gamename = $value['gamename'];
				}else{
					$gamename = '飞币';
				}

				if($value['status'] == 1){	//成功
					$string .= '
						<tr align="center" class="ajaxrow">
							<td>' . $value['order_date'] . '</td>				
							<td>' . $value['oid'] . '</td>
							<td title="' . $agent_oid .'">' . $agent_oid_omit . '</td>
							<td>' . $value['paytype'] . '</td>	
							<td>' . $gamename . '(' . $value['server'] . ')</td>		
							<td>' . $value['money'] . '</td>
                            ' . ($value['realmoney'] == '' ? '<td><a class="changeAgent" href="/index.php?m=ac&a=changeAgentOrderInfo&hidden_agent_oid=1&oid=' . $value['oid'] . '">更正订单</a></td>' : '<td>无</td>') . '
						</tr>					
					';
				}else{	//失败
					$error_str .= '
						<tr align="center" class="ajaxrow">
							<td>' . $value['order_date'] . '</td>				
							<td>' . $value['oid'] . '</td>
							<td title="' . $agent_oid .'">' . $agent_oid_omit . '</td>
							<td>' . $value['paytype'] . '</td>	
							<td>' . $gamename . '(' . $value['server'] . ')</td>	
							<td>' . $value['money'] . '</td>
							' . ($value['channel'] == 'google' ? '<td><a class="changeAgent" href="/index.php?m=ac&a=changeAgentOrderInfo&oid=' . $value['oid'] . '">更正订单</a></td>' : '<td>无</td>') . '
						</tr>					
					';				
				}
			}
			$userinfo[0]['msg']  = $string;
			$userinfo[0]['error_str']  = $error_str;
		}
		$userinfo[0]['code'] = 1;
		return $userinfo[0];
	}

	public function search_order_info($data, $from_shops=false){
		if (empty($data['order'])) {
			return array('code'=>'0', 'msg'=>'请输入一个订单号。');
		}

		$order = trim(mysql_real_escape_string($data['order']));
		$sql = 'SELECT o.oid, o.username, o.hash_username, o.price, o.priceCount, o.gold, o.serverid, o.gameid, o.game, o.paytype, o.paycode, o.state, o.currency, o.role, o.roleid, o.areaid, o.gash_version, o.gash_item FROM `blk_shops_orders` o WHERE o.oid = "' . $order . '" LIMIT 1 ';
		$shops_data = model::getBySql($sql);

		$order_data = array();
		$agent_data = array();
		if($from_shops) {
			$order_data = $shops_data;
		} else {
			$real_order = null;
			//如果找不到数据，尝试使用联运商单号查询
			if(empty($shops_data)) {
				$agent_data = model::getBySql('SELECT * FROM `ms_agent_orders` WHERE oid="' . $order . '" OR agent_oid = "' . $order . '"');
				if(!empty($agent_data)) {
					$real_order = $agent_data[0]['oid'];
				}
			} else {
				$real_order = $shops_data[0]['oid'];
			}

			if (!is_null($real_order)) {
				$sql = 'SELECT o.oid, o.username, o.hash_username, o.money, o.gold, o.status, o.type, o.channel, o.ip, from_unixtime( o.time, "%Y-%m-%d" ) AS date, o.game, o.server, o.realmoney FROM `ms_all_orders` o WHERE o.oid = "' . $real_order . '" LIMIT 1 ';
				$order_data = model::getBySql($sql);
			}
		}
		if (empty($order_data)) {
			return array('code'=>'0', 'msg'=>'没有找到任何数据');
		}

		$order_data[0]['agent_oid'] = empty($agent_data) ? '無' : $agent_data[0]['agent_oid'];
		$order_data[0]['role'] = empty($shops_data) ? $agent_data[0]['ocharname'] : $shops_data[0]['role'];
		$order_data[0]['roleid'] = empty($shops_data) ? $agent_data[0]['roleid'] : $shops_data[0]['roleid'];
		$order_data[0]['code'] = 1;
		return $order_data[0];
	}

	public function get_coins_holders($limit=0){
		$limitSql = '';
		if ($limit > 0) {
			$limitSql = ' LIMIT  ' . $limit;
		}

		//先搜索平台订单
		$sql = "SELECT m.mid, m.userid, IF(m.site!='', IF(m.site='ff', 'ttaw', m.site), 'ttaw') as usite, m.uname, IF(m.sex != '', m.sex, '保密') as usex, m.money, m.email, FROM_UNIXTIME(m.jointime, '%Y-%m-%d') as joinin, m.joinip, IF(m.openidverify=0, '未綁定', '已綁定') as binding from blk_member m WHERE m.money > 0 ORDER BY m.money DESC $limitSql";
		$data = model::getBySql($sql);

		return $data;
	}

	public function get_daily_coins($data, $limit=0){
		$where = $string = '';
		$limitSql = ' LIMIT 31 ';
		if ($limit > 0) {
			$limitSql = ' LIMIT  ' . $limit;
		}
		$isajax = array_key_exists('isajax', $data) ? $data['isajax'] : 0;
		if ($isajax == 1) {
			if ($t1 = strtotime($data['start_date'])) {
				$where .= " AND day > '" . $t1 . "' ";
			}
			if ($t2 = strtotime($data['end_date'] . "23:59:59")){
				$where .= " AND day < '" . $t2 . "' ";
			}
		}

		$sql = "SELECT id, FROM_UNIXTIME(day, '%Y-%m-%d') as day, coins FROM ms_daily_coins WHERE 1 $where ORDER BY id DESC $limitSql";
		$listdata = model::getBySql($sql);
		if ($isajax == 1 && !empty($listdata)) {
			foreach ($listdata as $key => $value){
				$string .= '
					<tr align="center">
						<td width="50%">' . $value['day'] . '</td>				
						<td width="50%">' . $value['coins'] . '</td>
					</tr>
				';
			}
			$listdata['msg'] = $string;
		}

		return $listdata;
	}

	/**
	 * 
	 * 处理订单对账日
	 * @param array $data
	 * @param string $odid
	 */
	public function editOrderdate(array $data, $odid){
		if( empty($data['type']) ||
		empty($data['project']) ||
		empty($data['orderdate'])){
			return array(false, 408, '参数不足或者有误');
		}else{
			$data = array(
			'type' => $data['type'],
			'project' => $data['project'],
			'orderdate' => $data['orderdate'],
			'subtime' => time(),
			);
			$mod = new model('ms_orderdate_log');
			$where = empty($odid) ? null : '`id`=\'' . $odid . '\'';
			if($mod->set($data, $where)){
				return array(true, 1, '提交对账时间成功');
			}
			return array(false, 500, '数据错误');
		}
	}

	/**
	 * 
	 * 通过id获取订单对账日详细
	 * @param int $odid
	 */
	public function getOrderdate($odid){
		$mod = new model('ms_orderdate_log');
		$where = '`id`=' . $odid;
		return $mod->get($where);
	}

	/**
	 * 
	 * 通过type，project获取订单对账日详细
	 * @param string $type
	 * @param string $project
	 */
	public function getOrderdateByType($type, $project){
		$mod = new model('ms_orderdate_log');
		$where = '`type`=\'' . model::quote($type) . '\' AND `project`=\'' .  model::quote($project) . '\'';
		return $mod->get($where);
	}

	/**
	 * 
	 * 获取所有订单对账日
	 * @param string $type
	 * @param string $project
	 */
	public function getOrderdateList($type, $project){
		$where = '1=1 ';
		if($type)$where .= 'AND `type`=\'' .model::quote($type) . '\'';
		if($project)$where .= ' AND `project`=\'' . model::quote($project) . '\'';
		$mod = new model('ms_orderdate_log');
		return $mod->select('*', $where);
	}

	/**
	 * 
	 * 统计金流每月金额
	 * @param string $year
	 * @param string $month
	 * @param string $project
	 */
	public function getGoldstreamsTotal($year, $month, $project){
		$project = empty($project) ? 'mycard' : $project;
		if($project == 'mycard'){
			$sql = 'select 1 as orderdate,\'mycard\' as goldtype,IF(paycode = \'MyCard廠商直接儲值(In Game)\', \'ingame\', IF(paycode=\'MyCard轉點儲值\', \'point\', \'billing\')) AS pcode,
sum(priceCount) as total,from_unixtime(stime,\'%Y%m\') as ym from blk_shops_orders where paytype=9 and `state` = 1 AND from_unixtime(stime,\'%Y\')=\'' . $year . '\'';
			if($month)$where = ' and from_unixtime(stime,\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			$orderdate = $this->getOrderdateByType('goldstream', $project);
			if($orderdate['orderdate'] > 1){
				$time = $orderdate['orderdate']*24*60*60;
				$sql = 'select '.$orderdate['orderdate'].' as orderdate,\'mycard\' as goldtype,IF(paycode = \'MyCard廠商直接儲值(In Game)\', \'ingame\', IF(paycode=\'MyCard轉點儲值\', \'point\', \'billing\')) AS pcode,
sum(priceCount) as total,from_unixtime(stime-'.$time.',\'%Y%m\') as ym from blk_shops_orders where paytype=9 and `state` = 1 AND from_unixtime(stime-'.$time.',\'%Y\')=\'' . $year . '\'';
				if($month)$where=' and from_unixtime(stime-'.$time.',\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			}
			$sql .= $where.' group by ym, pcode order by ym desc';
		}
		return model::getBySql($sql);
	}

	/**
	 * 
	 * 统计游戏每月金额
	 * @param string $year
	 * @param string $month
	 * @param string $game
	 * @param array $games
	 */
	public function getGameTotal($year, $month, $game,array $games){
		if(!empty($game)){
			$sql = 'select 1 as orderdate,sum(money) as total,game,server,from_unixtime(paytime,\'%Y%m\') as ym from ms_gamepay_log where game=\''.$game.'\' and from_unixtime(paytime,\'%Y\')=\'' . $year . '\'';
			if($month)$sql.=' and from_unixtime(paytime,\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			$orderdate = $this->getOrderdateByType('game', $game);
			if($orderdate['orderdate'] > 1){
				$time = $orderdate['orderdate']*24*60*60;
				$sql = 'select '.$orderdate['orderdate'].' as orderdate,sum(money) as total,game,server,from_unixtime(paytime-'.$time.',\'%Y%m\') as ym from ms_gamepay_log where game=\''.$game.'\' and from_unixtime(paytime-'.$time.',\'%Y\')=\'' . $year . '\'';
				if($month)$sql.=' and from_unixtime(paytime-'.$time.',\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			}
			$sql.=' group by ym,game,server order by ym desc';
		}else{
			$sqlArr = array();
			foreach ($games as $key => $val){
				$sql = 'select 1 as orderdate,sum(money) as total,game,server,from_unixtime(paytime,\'%Y%m\') as ym from ms_gamepay_log where game=\''.$key.'\' and from_unixtime(paytime,\'%Y\')=\'' . $year . '\'';
				if($month)$sql.=' and from_unixtime(paytime,\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
				$orderdate = $this->getOrderdateByType('game', $key);
				if($orderdate['orderdate'] > 1){
					$time = $orderdate['orderdate']*24*60*60;
					$sql = 'select '.$orderdate['orderdate'].' as orderdate,sum(money) as total,game,server,from_unixtime(paytime-'.$time.',\'%Y%m\') as ym from ms_gamepay_log where game=\''.$key.'\' and from_unixtime(paytime-'.$time.',\'%Y\')=\'' . $year . '\'';
					if($month)$sql.=' and from_unixtime(paytime-'.$time.',\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
				}
				$sql.=' group by ym,game,server';
				$sqlArr[$key] = $sql;
				unset($sql);
			}
			$sql = implode($sqlArr, ' UNION ');
			$sql .=' order by ym desc,game asc,server asc';
		}
		return model::getBySql($sql);
	}

	public function getAgentList(){
		$mod = new model('ms_gamepay_log');
		//		$where = '`channel` != \'forgame\'';
		return $mod->select('DISTINCT(channel)');
	}

	public function getAgentTotal($year, $month, $agent, $agents){
		if(!empty($agent)){
			$sql = 'select 1 as orderdate,sum(money) as total,channel as agentname,game,FROM_UNIXTIME(paytime,\'%Y%m\') as ym from ms_gamepay_log where from_unixtime(paytime,\'%Y\')=\'' . $year . '\'';
			if($month)$sql.=' and from_unixtime(paytime,\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			$orderdate = $this->getOrderdateByType('agent', $agent);
			if($orderdate['orderdate'] > 1){
				$time = $orderdate['orderdate']*24*60*60;
				$sql = 'select '.$orderdate['orderdate'].' as orderdate,sum(money) as total,channel as agentname,game,FROM_UNIXTIME(paytime-'.$time.',\'%Y%m\') as ym from ms_gamepay_log where from_unixtime(paytime-'.$time.',\'%Y\')=\'' . $year . '\'';
				if($month)$sql.=' and from_unixtime(paytime-'.$time.',\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
			}
			$sql.=' and channel = \'' . $agent . '\'';
			$sql .=  ' group by channel,game,ym order by paytime desc';
		}else{
			foreach ($agents as $key => $val){
				$sql = 'select 1 as orderdate,sum(money) as total,channel as agentname,game,from_unixtime(paytime,\'%Y%m\') as ym from ms_gamepay_log where from_unixtime(paytime,\'%Y\')=\'' . $year . '\'';
				if($month)$sql.=' and from_unixtime(paytime,\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
				$orderdate = $this->getOrderdateByType('agent', $val['channel']);
				if($orderdate['orderdate'] > 1){
					$time = $orderdate['orderdate']*24*60*60;
					$sql = 'select '.$orderdate['orderdate'].' as orderdate,sum(money) as total,channel as agentname,game,from_unixtime(paytime-'.$time.',\'%Y%m\') as ym from ms_gamepay_log where channel=\''.$val['channel'].'\' and from_unixtime(paytime-'.$time.',\'%Y\')=\'' . $year . '\'';
					if($month)$sql.=' and from_unixtime(paytime-'.$time.',\'%m\') = \'' . sprintf("%02d", $month) . '\' ';
				}
				$sql .=  ' group by channel,game,ym order by paytime desc';
			}
		}
		return model::getBySql($sql);
	}
	/**
	 * 
	 * 确认订单对账月
	 * @param array $data
	 * @param string $type
	 */
	public function confirmOrder(array $data, $type){
		if( !empty($data['specific'])  &&
		!empty($data['project'])   &&
		!empty($data['ordertime']) &&
		!empty($data['timestamp'])
		){
			$corder = array(
			'type' => $type,
			'project' => $data['project'],
			'specific' => $data['specific'],
			'ordertime' => $data['ordertime'],
			'status' => 1,
			'createtime' => $data['timestamp'],
			);
			$mod = new model('ms_confirmorder_log');
			return $mod->set($corder);
		}
		return false;
	}

	/**
	 * 
	 * 获取已确认的订单对账月
	 * @param string $type
	 * @param string $project
	 * @param string $ordertime
	 */
	public function getConfirmOrders($type, $project, $ordertime){
		$where = ' status = 1';
		if($type) $where .= ' AND type = \''.$type.'\' ';
		if($ordertime) $where .= ' AND ordertime = \''.$ordertime.'\' ';
		if($project) $where .= ' AND project = \''.$project.'\' ';
		$mod = new model('ms_confirmorder_log');
		return $mod->select('*',$where);
	}

	/**
	 * 
	 * 获取金流订单
	 * @param string $project
	 * @param string $specific
	 * @param string $ordertime
	 */
	private function _getGameOrders($project, $specific, $ordertime){
		$sql = 'select oid,money as gold,paytime as otime from ms_gamepay_log where game=\'' . $project . '\' and server=\'' . $specific . '\' and from_unixtime(paytime,\'%Y%m\')=\'' . $ordertime . '\'';
		return model::getBySql($sql);
	}

	/**
	 * 
	 * 获取游戏订单
	 * @param string $project
	 * @param string $specific
	 * @param string $ordertime
	 */
	private function _getGoldstreamOrders($project, $specific, $ordertime){
		if($project == 'mycard'){
			$sql = 'select oid,priceCount as gold,stime as otime,IF(paycode = \'MyCard廠商直接儲值(In Game)\', \'ingame\', IF(paycode=\'MyCard轉點儲值\', \'point\', \'billing\')) AS pcode
 from blk_shops_orders where paytype=9 and `state` = 1  and  from_unixtime(stime,\'%Y%m\')=\'' . $ordertime . '\' having pcode=\'' . $specific . '\'';
			return model::getBySql($sql);
		}
	}

	/**
	 * 
	 * 获取我方储值订单
	 * @param string $type
	 * @param string $project
	 * @param string $specific
	 * @param string $ordertime
	 */
	public function getSourceList($type, $project, $specific, $ordertime){
		$source_list = array();
		switch ($type){
			case 'goldstream':
				$orders = $this->_getGoldstreamOrders($project, $specific, $ordertime);
				break;
			case 'game':
				$orders = $this->_getGameOrders($project, $specific, $ordertime);
				break;
		}
		if($orders && count($orders)){
			foreach ($orders as $key => $val){
				$source_list[$val['oid']]['oid'] = $val['oid'];
				$source_list[$val['oid']]['gold'] = $val['gold'];
				$source_list[$val['oid']]['time'] = date('Y-m-d H:i:s',$val['otime']);
			}
		}
		return $source_list;
	}

	/**
	 * 
	 * 读取csv文件获取对方订单
	 * @param string $fileallpath
	 */
	public function getTargetList($fileallpath){
		$target_list = array();
		$handle = fopen($fileallpath, "r");
		if($handle){
			while($data=fgetcsv($handle, 10000, ",")){
				$target_list[$data[0]]['oid'] = $data[0];
				$target_list[$data[0]]['gold'] = $data[1];
				$target_list[$data[0]]['time'] = $data[2];
			}
		}
		fclose($handle);
		return $target_list;
	}

	/**
	 * 改变订单金额
	 * 非平台订单和交易成功的订单不能更改
	 * 
	 * @param string $oid
	 * @param int $money
	 * @param int $currency
	 * @return array( code, msg )
	 */
	public function changeOrderMoney($oid, $money, $currency) {
		$oid = preg_replace('/[^\d]/', '', $oid);
		$money = intval($money);
		$order_model = new model('ms_all_orders');
		$shops_order_model = new model('blk_shops_orders');

		$order = $shops_order_model->get("oid={$oid}");
		if(empty($order)) {
			return array(
			'code' => 0, 'msg' => '不允許更改非平臺訂單！'
			);
		}
		if($order['state'] == 1) {
			return array(
			'code' => 0, 'msg' => '訂單已交易成功，不能改變金額！'
			);
		}

		//计算游戏币
		load('@agent.model.gameServer');
		$game = gameServer::getGameInfo($order['game']);
		if(empty($game)) {
			$gold = 0;
		} else {
			$gold = $money * $game['scale'];
		}

		$order_model->set( array(
		'money' => $money,
		'gold' => $gold,
		'realmoney' => $money,
		'currency' => $currency
		), "oid={$oid}" );


		$shops_order_model->set( array(
		'dprice' => $money,
		'price' => $money,
		'priceCount' => $money,
		'gold' => $gold,
		'realmoney' => $money,
		'currency' => $currency
		), "oid={$oid}");

		return array(
		'code' => 1, 'msg' => '更改成功！'
		);
	}

	/**
     * 更正联运商订单信息
     * 
     * @param string $oid
     * @param array $data
     */
	public function changeAgentOrderInfo($oid, $data) {
		$_oid = mysql_real_escape_string($oid);
		$agent_order_model = new model('ms_agent_orders');
		$order = $agent_order_model->get("oid={$_oid}");
		//允许程序更改哪些字段
		$allow_data = array('agent_oid', 'agent_pay_currency', 'agent_pay_money');
		$_data = array();
		foreach($data as $key => $value) {
			if($key == 'agent_pay_money' && $order['agent_pay_money'] != 0 ) {
				continue;
			}
			if((empty($order[$key]) || $order[$key] == 0) &&
			!empty($value) && in_array($key, $allow_data)) {
				$_data[$key] = mysql_real_escape_string($value);
			}
		}
		$_data['ostatus'] = 1;
		$_data['agent_pay_status'] = 1;
		$_data['agent_pay_time'] = $order['time'];
		$result = $agent_order_model->set($_data, "`oid`='{$_oid}'");

		if($result) {
			//更新汇总表数据
			$order_model = new model('ms_all_orders');
			$map_data = array();
			if(isset($_data['agent_pay_currency'])) {
				$map_data['currency'] = $_data['agent_pay_currency'];
			}
			if(isset($_data['agent_pay_money'])) {
				$map_data['realmoney'] = $_data['agent_pay_money'];
			}
			$map_data['status'] = 1;
			$order_model->set($map_data, "oid={$_oid}" );
			return array(
			'code' => 1, 'msg' => '更改成功！'
			);
		} else {
			return array(
			'code' => 0, 'msg' => '更改失败，未知错误！'
			);
		}
	}


	/**
     * 查询广告汇总信息 power by zhang
     * 
     * @param string $channel_id  渠道id
     * @param string $game; 游戏
	 * @param int material 素材id
     */
	public function get_adv_total($searchData, $offset, $row, $t1, $t2) {
        set_time_limit(300);
		$game = $material = '';
		if ($searchData['material']){
			$game = " AND adv_id = '" . $searchData['material'] . "' ";
			$material = " AND adv_id = '" . $searchData['material'] . "' ";
		}

		if($t1 && $t2){
			$t = " AND day between '" . $t1 . "' and '" . $t2 . "'";
		}else if($t1 && !$t2){
			$t = " AND day between '" . $t1 . "' and '" . date('Y-m-d'). "'";
		}
		
		$sql = 'SELECT sum(hits) as hits, sum(registration) as registration, sum(installations) as installations, sum(roles) as createRole, ' . 
				' sum(logins) as tlogin, sum(open_logins) as ologin, sum(items) as ds, sum(total) as totalmoney, sum(payers) as bcf, ' . 
				' day, game from `ms_adv_daily` WHERE 1 ' . $game . $t . ' group by day ORDER BY day DESC limit ' . $offset . ',' . $row;
		//echo $sql;
		$data = model::getBySql($sql);
		if (is_array($data)) {
			foreach ($data as $key => $value){
				//统计7725账号
				$sql = "SELECT COUNT(id) as r1 FROM `ms_adv_reg` WHERE FROM_UNIXTIME( `times`, '%Y-%m-%d') = '" . $value['day'] . "' AND game = '" . $value['game'] . "' AND ifopenid = 0 ". $material;
				$row7725 = model::getBySql($sql);
				//7725 注册量
				$data[$key]['reg'] = intval($row7725[0]['r1']);
				//openid 注册量
				$data[$key]['oreg'] = $data[$key]['registration'] - $data[$key]['reg'];
				$data[$key]['login'] = $data[$key]['tlogin'] - $data[$key]['ologin'];
			}
		}

		return $data;
	}

	/**
     * 查询广告汇总信息 power by zhang
     * 
     * @param string $channel_id  渠道id
     * @param string $game; 游戏
     * @param int material 素材id
     */
	public function count_adv_total($searchData, $t1, $t2){
		$material = '';

		if ($searchData['material']){
			$material = " AND adv_id = " . $searchData['material'];
		}
		if($t1 && $t2)
		{
			$t = " AND date between '" . $t1 . "' and '" . $t2 . "'";
		}
		else if($t1 && !$t2)
		{
			$t = " AND date between '" . $t1 . "' and '" . date('Y-m-d') . "'";
		}

		$sql = 'SELECT count(id) as t from (select * from `ms_adv_daily` WHERE 1 ' . $media . $game . $material . $t . ' group by day) as a';
		//echo $sql;
		$data = model::getBySql($sql);
		return $data;
	}
}