<?php
require_once APP_CONTROLLER_PATH . '/master.php';
class reportController extends masterControl {

	// 平台充值金額
	public function platform() {
		$this->checkLogin();
		$year 	= !empty($_GET['year'])  && $_GET['year']	!= 'NULL' ? $_GET['year'] 	: '';
		$month 	= !empty($_GET['month']) && $_GET['month'] 	!= 'NULL' ? $_GET['month'] 	: '';
		$date   = '';
		$this->assign('lastday', '12');
		if ($year) {
			$date = $year;
			$this->assign('years', $year);
		}
		if (!empty($month)) {
			if (!empty($month) && $month < 10) {
				$date .= "-0".$month;
			}
			else if ($month > 9) {
				$date .= "-".$month;
			}
			$this->assign('lastday', getLastday($date));
			$this->assign('day', 1);
			$this->assign('month', $month);
		}
		$this->getPlatform('%Y', $date, '%Y-%m-%d', '1');
	}

	private function getPlatform($wdate, $date, $gdate, $quarter = '0') {
		$total		= 0;
		$time = ($gdate == '%Y-%m-%d') ? '%e' : ($gdate == '%Y-%m' ? '%c' : '');
		$whereSql = '';
		//$whereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(o.`stime`,'$wdate') = '$date' " : '';
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		$qSql  ='SELECT  COUNT(DISTINCT o.`username`) AS `usernum`, SUM(o.`money`) AS `total`, ROUND(SUM(o.`money`)/COUNT(DISTINCT o.`username`),2) AS `ARPU`, FROM_UNIXTIME(o.`time`,"'.$wdate.'") AS `date`, '.$lq.'FROM_UNIXTIME(o.`time`, "'.$time.'")'.$rq.' AS `time`
				 FROM `ms_all_orders` o LEFT JOIN `blk_member` m ON o.`username` = m.`userid` 
				 WHERE o.`status` = 1 '.$whereSql.'
				 GROUP BY '.$lq.'FROM_UNIXTIME(o.`time`, "'.$gdate.'")'.$rq.',FROM_UNIXTIME(o.`time`,"%Y")'.' '
		.'ORDER BY FROM_UNIXTIME(o.`time`, "%Y") DESC, FROM_UNIXTIME(o.`time`, "%m-%d")';
		$qData	= model::getBySql($qSql);
		if (is_array($qData) && count($qData) > 0 ) {
			foreach ($qData as $key => $value) {
				$newDubp[$value['date']][$value['time']] 	= $value['total'];
				$newDubp[$value['date']]['total'] 			= $newDubp[$value['date']]['total']+$value['total'];
			}
			unset($qData);
			$this->assign('pubp', $newDubp);
		}
		// 按年
		if (strlen($date) == 4 && $date ) {
			$ymData 	 = self::getPlatform2('%Y', $date, '%Y-%m');
			$quarterData = self::getPlatform2('%Y', $date, '%Y-%m-%d', '1');
			$this->assign('ymData', $ymData);
			$this->assign('quarter', $quarterData);
			$this->assign('qym', '1');
			// 统计某时间之前的注册人数
			$timeSql = " AND FROM_UNIXTIME(m.`jointime`, '%Y') < '$date' ";
			$userSql = "SELECT COUNT(*) AS `preMember` FROM `blk_member` m WHERE 1 ".$timeSql;
			$user    = model::getBySql($userSql);
			$this->assign('preMember',$user[0]['preMember']);
		}
		// 按月
		else if (strlen($date) > 4 && $date) {
			$ymData 	 = self::getPlatform2('%Y-%m', $date, '%Y-%m-%d');
			$quarterData = self::getPlatform2('%Y', substr($date, 0, 4), '%Y-%m-%d', '1');
			$timeSql = " AND FROM_UNIXTIME(m.`jointime`, '%Y-%m') < '$date' ";
			$this->assign('ymData', $ymData);
			$this->assign('quarter', $quarterData);
			$this->assign('qym', '1');
			// 统计某时间之前的注册人数
			$userSql = "SELECT COUNT(*) AS `preMember` FROM `blk_member` m WHERE 1 ".$timeSql;
			$user    = model::getBySql($userSql);
			$this->assign('preMember',$user[0]['preMember']);
		}
		else {
			$this->assign('qym', '0');
		}
		$timeSql = " AND FROM_UNIXTIME(m.`jointime`, '%Y') < '$date' ";
		$userSql = "SELECT COUNT(*) AS `qMember` FROM `blk_member` m WHERE 1 ".$timeSql;
		$user    = model::getBySql($userSql);
		$this->assign('qMember',$user[0]['qMember']);
	}

	private static function getPlatform2($wdate, $date, $gdate, $quarter = '0') {
		$time = ($gdate == '%Y-%m-%d') ? '%e' 	: ($gdate == '%Y-%m' ? '%c' : '');
		$count= ($gdate == '%Y-%m-%d') ? 1 		: ($gdate == '%Y-%m' ? 2 : '');
		$whereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(o.`time`,'$wdate') = '$date' " : '';
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		// 新增用户数，累计注册人数
		$mSql = "SELECT COUNT(`mid`) AS `member`, ".$lq."FROM_UNIXTIME(m.`jointime`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(m.`jointime`, '".$time."')".$rq." AS `time`, FROM_UNIXTIME(m.`jointime`, '%Y') AS `year`
				 FROM `blk_member` m
				 WHERE 1 AND FROM_UNIXTIME(m.`jointime`, '".$wdate."') = '".$date."'
				 GROUP BY `date`";
		$mData = model::getBySql($mSql);
		$total = 0;
		foreach ($mData as $key => $value) {
			$data[$value['year']][$value['time']]['member'] = intval($value['member']);
			$data[$value['year']][$value['time']]['allMember'] = $total + intval($value['member']);
		}

		// 充值金额	充值用户	充值次数	ARPU
		$oSql = "SELECT SUM(o.`money`) AS `total`, FROM_UNIXTIME(o.`time`, '%Y') AS `year`, COUNT(DISTINCT o.`username`) AS `ruser`, COUNT(o.`username`) AS `rnum`, ROUND(SUM(o.`money`)/COUNT(DISTINCT o.`username`),2) AS `ARPU`, ".$lq."FROM_UNIXTIME(o.`time`,'".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(o.`time`, '".$time."')".$rq." AS `time`
				 FROM `ms_all_orders` o
				 WHERE o.`status` = 1 AND FROM_UNIXTIME(o.`time`, '".$wdate."') = '".$date."'
				 GROUP BY `date`";
		$oData = model::getBySql($oSql);
		foreach ($oData as $key => $value) {
			$data[$value['year']][$value['time']]['total'] = intval($value['total']);
			$data[$value['year']][$value['time']]['ruser'] = intval($value['ruser']);
			$data[$value['year']][$value['time']]['rnum']  = intval($value['rnum']);
			$data[$value['year']][$value['time']]['ARPU']  = $value['ARPU'];
		}

		// 活跃用户
		/*$prSql= "SELECT FROM_UNIXTIME(ma.`time`, '%Y') AS `year`, COUNT(*) AS `auser`, ".$lq."FROM_UNIXTIME(ma.`time`, '".$gdate."')".$rq." AS `date`, FROM_UNIXTIME(ma.`time`, '%c') AS `time`
				 FROM (
				 		SELECT * FROM `blk_member_action` bmc 
				 		WHERE 1 AND bmc.`action` = 'login' AND FROM_UNIXTIME(bmc.`time`, '".$wdate."') = '".$date."' 
				 		GROUP BY ".$lq."FROM_UNIXTIME(bmc.`time`, '".$gdate."')".$rq.", bmc.`userid` 
				 		HAVING COUNT(*) >= ".$count."
				 	  ) ma 
				 WHERE 1
				 GROUP BY `date`";
		$pData = model::getBySql($prSql);
		foreach ($pData as $key => $value) {
			$data[$value['year']][$value['time']]['auser'] = intval($value['auser']);
		}*/

		if (is_array($data) && count($data) > 0 ) {
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					$data[$key][$k] = array(
					'total'		=> array_key_exists('total' , 	 $v) 		? intval($v['total']) 			: 0,
					'ruser' 	=> array_key_exists('ruser' , 	 $v) 		? intval($v['ruser']) 			: 0,
					'rnum'  	=> array_key_exists('rnum'  , 	 $v) 		? intval($v['rnum'])  			: 0,
					'ARPU'  	=> array_key_exists('ARPU'  , 	 $v) 		? sprintf("%.2f", $v['ARPU']) 	: 0,
					'auser' 	=> array_key_exists('auser' , 	 $v) 		? intval($v['auser']) 			: 0,
					'member'	=> array_key_exists('member', 	 $v) 		? intval($v['member'])			: 0,
					'allMember' => array_key_exists('allMember', $v) 		? intval($v['allMember']) 		: 0,
					);
				}
			}
		}
		return $data;
	}

	// 渠道充值數據
	public function channel() {
		$this->checkLogin();
		load('model.channel');
		$year 	= !empty($_GET['year'])    && $_GET['year']		!= 'NULL' ? $_GET['year'] 	: '';
		$month 	= !empty($_GET['month'])   && $_GET['month'] 	!= 'NULL' ? $_GET['month'] 	: '';
		$channel= !empty($_GET['channel']) && $_GET['channel'] 	!= 'NULL' ? $_GET['channel'] 	: '';
		$date   = '';
		$this->assign('lastday', '12');
		$yearData = $this->getChannel('%Y', '', '%Y');
		$this->assign('yearData', $yearData);
		$this->assign('channelName', channel::$names);
		$notSql = channel::get_notLike_sql(' AND m.userid NOT LIKE "', '%" ');
		$channelSql= !empty($channel)  ? ($channel == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channel'") : '';
		// 按年月
		if ($year && $month) {
			$date = $year;
			if (!empty($month) && $month < 10) {
				$date .= "-0".$month;
			}
			else if ($month > 9) {
				$date .= "-".$month;
			}
			$this->assign('lastday', getLastday($date));
			$this->assign('day', 1);
			$this->assign('month', $month);
			// 根据渠道查询
			if (!empty($channel)) {
				$dayData 	 = $this->getChannel2('%Y-%m', $date, '%Y-%m-%d', '', $channel);
				$quarterData = $this->getChannel2('%Y', $year, '%Y-%m-%d', '1', $channel);
				$this->assign('channelSign', '1');
				$sql = "SELECT COUNT(*) AS `preUser` FROM `blk_member` m WHERE 1 $channelSql AND FROM_UNIXTIME(m.`jointime`, '%Y-%m') < '$date'";
				$user = model::getBySql($sql);
				$this->assign('preUser', $user[0]['preUser']);
			}
			else {
				$dayData  = $this->getChannel('%Y-%m', $date, '%Y-%m-%d');
				$quarterData = $this->getChannel('%Y', $year, '%Y-%m-%d', '1');
				$this->assign('qym', '1');
			}
			$this->assign('years', $year);
			$this->assign('quarterData', $quarterData);
			$this->assign('monthData', $dayData);
		}
		else if (!empty($year)) {
			// 根据渠道查询
			if (!empty($channel)) {
				$quarterData = $this->getChannel2('%Y', $year, '%Y-%m-%d', '1', $channel);
				$monthData	 = $this->getChannel2('%Y', $year, '%Y-%m', '', $channel);
				$this->assign('channelSign', '1');
				$sql = "SELECT COUNT(*) AS `preUser` FROM `blk_member` m WHERE 1 $channelSql AND FROM_UNIXTIME(m.`jointime`, '%Y') < '$year'";
				$user = model::getBySql($sql);
				$this->assign('preUser', $user[0]['preUser']);
			}
			else {
				$quarterData = $this->getChannel('%Y', $year, '%Y-%m-%d', '1');
				$monthData	 = $this->getChannel('%Y', $year, '%Y-%m');
				$this->assign('qym', '1');
			}
			$this->assign('years', $year);
			$this->assign('quarterData', $quarterData);
			$this->assign('monthData', $monthData);
		}
		$sql = "SELECT COUNT(*) AS `preUser` FROM `blk_member` m WHERE 1 $channelSql AND FROM_UNIXTIME(m.`jointime`, '%Y') < '$year'";
		$user = model::getBySql($sql);
		$this->assign('qUser', $user[0]['preUser']);
	}

	// 獲取渠道充值數據
	private function getChannel($wdate, $date, $gdate, $quarter = '0') {
		load('model.channel');
		$time = ($gdate == '%Y-%m-%d') ? '%e' 	: ($gdate == '%Y-%m' ? '%c' : '');
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		$year 		= !empty($_GET['year'])  && $_GET['year'] != 'NULL' ? $_GET['year'] 									  : date('Y');
		$whereSql 	= !empty($wdate) 		 && !empty($date) 			? " AND FROM_UNIXTIME(o.`time`,'$wdate') = '$date' " : '';
		$this->assign('years', $date);
		$channelSql = "	SELECT  SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) AS `channel`, SUM(o.`money`) AS `total`, ".$lq."FROM_UNIXTIME(o.`time`,'".$gdate."')".$rq." AS `date`, FROM_UNIXTIME(o.`time`,'%Y') AS `year`, ".$lq."FROM_UNIXTIME(o.`time`, '".$time."')".$rq." AS `time`
						FROM `ms_all_orders` o LEFT JOIN `blk_member` m ON o.`username` = m.`userid` 
						WHERE o.`status` = 1 ".$whereSql."
						GROUP BY  IF(SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1)!='ff',SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1),''), `date`";
		$channelData = model::getBySql($channelSql);
		if (is_array($channelData) && count($channelData) > 0 ) {
			foreach ($channelData as $key=> $value) {
				$channelData[$key]['channelName'] = channel::get_user_type($value['channel']);
			}
			foreach ($channelData as $key => $value) {
				if ($gdate == '%Y') {
					$newChannel[$value['year']][$value['channelName']]['total'] = $value['total'];
				}
				else {
					$newChannel[$value['year']][$value['channelName']][$value['time']] = $value['total'];
				}
				if (empty($value['channel']) || $value['channel'] == 'ff') {
					$newChannel[$value['year']][$value['channelName']]['channel'] = 'ff';
				}
				else {
					$newChannel[$value['year']][$value['channelName']]['channel'] = $value['channel'];
				}
			}
			unset($channelData);
			$this->assign('channel', $newChannel);
		}
		return $newChannel;
	}

	private function getChannel2($wdate, $date, $gdate, $quarter = '0', $channel = null) {
		load('model.channel');
		$time = ($gdate == '%Y-%m-%d') ? '%e' 	: ($gdate == '%Y-%m' ? '%c' : '');
		$count= ($gdate == '%Y-%m-%d') ? 1 		: ($gdate == '%Y-%m' ? 2 	: '');
		$whereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(o.`time`,'$wdate') = '$date' " : '';
		$notSql = channel::get_notLike_sql(' AND m.userid NOT LIKE "', '%" ');
		$channelSql= !empty($channel)  ? ($channel == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channel'") : '';
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		// 充值金额	充值用户	充值次数	ARPU
		$moSql ="SELECT o.`username`, SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) AS `channel`, FROM_UNIXTIME(o.`time`, '%Y') AS `year`, SUM(o.`money`) AS `total`, COUNT(DISTINCT o.`username`) AS `ruser`, COUNT(o.`username`) AS `rnum`, ROUND(SUM(o.`money`)/COUNT(DISTINCT o.`username`),2) AS `ARPU`, ".$lq."FROM_UNIXTIME(o.`time`,'".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(o.`time`, '".$time."')".$rq." AS `time`, COUNT(m.`userid`) AS `member`
				 FROM `ms_all_orders` o LEFT JOIN `blk_member` m ON o.`username` = m.`userid` 
				 WHERE o.`status` = 1 AND FROM_UNIXTIME(o.`time`, '".$wdate."') = '".$date."' ".$channelSql."
				 GROUP BY `date`";
		$moData = model::getBySql($moSql);
		foreach ($moData as $key=>$value) {
			if ($value['channel'] == '') {
				$value['channel'] = 'ff';
			}
			$data[$value['year']][$value['time']]['channel'] = $value['channel'];
			$data[$value['year']][$value['time']]['total'] 	 = intval($value['total']);
			$data[$value['year']][$value['time']]['ruser'] 	 = intval($value['ruser']);
			$data[$value['year']][$value['time']]['rnum']  	 = intval($value['rnum']);
			$data[$value['year']][$value['time']]['ARPU']  	 = $value['ARPU'];
		}
		// 活跃用户
		/*$maSql= "SELECT FROM_UNIXTIME(ma.`time`, '%Y') AS `year`, COUNT(*) AS `auser`, ".$lq."FROM_UNIXTIME(ma.`time`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(ma.`time`, '".$time."')".$rq." AS `time`
				 FROM (SELECT bmc.`time`, m.`userid` FROM `blk_member_action` bmc JOIN `blk_member` m ON bmc.`userid` = m.`mid` WHERE 1 AND bmc.`action` = 'login' AND FROM_UNIXTIME(bmc.`time`, '".$wdate."') = '".$date."' AND 1 ".$channelSql." GROUP BY ".$lq."FROM_UNIXTIME(bmc.`time`, '".$gdate."')".$rq.", bmc.`userid` HAVING COUNT(*) >= ".$count.") ma 
				 WHERE 1
				 GROUP BY `date`";
		$maData = model::getBySql($maSql);
		foreach ($maData as $key=>$value) {
			$data[$value['year']][$value['time']]['auser'] = intval($value['auser']);
		}*/
		// 新增用户数	累计注册用户数
		$mSql = "SELECT COUNT(`mid`) AS `member`, ".$lq."FROM_UNIXTIME(m.`jointime`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(m.`jointime`, '".$time."')".$rq." AS `time`, FROM_UNIXTIME(m.`jointime`, '%Y') AS `year`
				 FROM `blk_member` m
				 WHERE 1 AND FROM_UNIXTIME(m.`jointime`, '".$wdate."') = '".$date."' $channelSql
				 GROUP BY `date`";
		$mData = model::getBySql($mSql);
		foreach ($mData as $key=>$value) {
			if ($value['channel'] == '') {
				$value['channel'] = 'ff';
			}
			$data[$value['year']][$value['time']]['channel'] = $value['channel'];
			$data[$value['year']][$value['time']]['member']  = intval($value['member']);
		}
		if (is_array($data) && count($data) > 0 ) {
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					$data[$key][$k] = array(
					'channel'=> array_key_exists('channel', $v) ? intval($v['channel']) 		: 0,
					'total'	 => array_key_exists('total'  , $v) ? intval($v['total']) 			: 0,
					'ruser'  => array_key_exists('ruser'  , $v) ? intval($v['ruser']) 			: 0,
					'rnum'   => array_key_exists('rnum'   , $v) ? intval($v['rnum'])  			: 0,
					'ARPU'   => array_key_exists('ARPU'   , $v) ? sprintf("%.2f", $v['ARPU']) 	: 0,
					'auser'  => array_key_exists('auser'  , $v) ? intval($v['auser']) 			: 0,
					'member' => array_key_exists('member' , $v) ? intval($v['member'])			: 0,
					);
				}
			}
		}
		return $data;
	}

	// 遊戲充值數據
	public function game() {
		$this->checkLogin();
		$year 	= !empty($_GET['year'])    && $_GET['year']		!= 'NULL' ? $_GET['year'] 	: '';
		$month 	= !empty($_GET['month'])   && $_GET['month'] 	!= 'NULL' ? $_GET['month'] 	: '';
		$gameid	= !empty($_GET['gameid'])  && $_GET['gameid'] 	!= 'NULL' ? $_GET['gameid'] : '';
		$alias	= !empty($_GET['alias'])   && $_GET['alias'] 	!= 'NULL' ? $_GET['alias']  : '';
		$date   = '';
		$this->assign('lastday', '12');
		$gameData = $this->getGame();
		$this->assign('gameData', $gameData);
		// 按游戏查询
		if ($gameid) {
			$game = $this->getGame('', '', '%Y', '',$gameid, $alias);
			$this->assign('yearData', $game);
			$this->assign('gameYear', 1);
		}
		// 按游戏  年月
		if ($gameid && $year && $month) {
			$date = $year;
			if (!empty($month) && $month < 10) {
				$date .= "-0".$month;
			}
			else if ($month > 9) {
				$date .= "-".$month;
			}
			$this->assign('lastday', getLastday($date));
			$this->assign('day', 1);
			$this->assign('month', $month);
			$dayData 	 = $this->getGame('%Y-%m', $date, '%Y-%m-%d', '', $gameid, $alias);
			$quarterData = $this->getGame('%Y', $year, '%Y-%m-%d', '1', $gameid, $alias);
			$this->assign('gameMonth', '1');
			$this->assign('years', $year);
			$this->assign('quarterData', $quarterData);
			$this->assign('monthData', $dayData);
		}
		// 按游戏 日
		else if ($gameid && !empty($year)) {
			$quarterData = $this->getGame('%Y', $year, '%Y-%m-%d', '1', $gameid, $alias);
			$monthData	 = $this->getGame('%Y', $year, '%Y-%m', '', $gameid, $alias);
			$this->assign('gameMonth', '1');
			$this->assign('years', $year);
			$this->assign('quarterData', $quarterData);
			$this->assign('monthData', $monthData);
		}
	}

	private function getGame($wdate, $date, $gdate, $quarter = '0', $game = null, $alias = null) {
		$this->checkLogin();
		$time = ($gdate == '%Y-%m-%d') ? '%e' : ($gdate == '%Y-%m' ? '%c' : '');
		$count= ($gdate == '%Y-%m-%d') ? 1 	  : ($gdate == '%Y-%m' ? 2 : 2);
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
			$count  = 2;
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		$moWhereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(o.`time`,'$wdate') = '$date' "  : '';
		$maWhereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(bpr.`time`, '$wdate') = '$date'" : '';
		if (!empty($game)) {
			$moWhereSql.= " AND o.`gameid` = '$game' ";
			$maWhereSql.= " AND bpr.`alias`= '$alias'";
			$gameName = model::getBySql("SELECT `ename` FROM `blk_games_inter` WHERE `alias` = '$alias' LIMIT 1");
			$this->assign('gameName', $gameName[0]['ename']);
		}
		if ($gdate) {
			$moGroupSql = ', '.$lq.'FROM_UNIXTIME(o.`time`, "'.$gdate.'")'.$rq;
			$maGroupSql = $lq."FROM_UNIXTIME(bpr.`time`, '".$gdate."')".$rq.",";
			$maGroup    = " ,`date`";
			$maoGroup   = "  AND a.`date` = b.`date`";
		}
		// 活跃玩家數
		$maSql= "SELECT g.`ename` AS `game`, g.`evalue` AS `gameid`, FROM_UNIXTIME(pr.`time`, '%Y') AS `year`, pr.`alias`, COUNT(*) AS `auser`, ".$lq."FROM_UNIXTIME(pr.`time`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(pr.`time`, '".$time."')".$rq." AS `time`
				 FROM (SELECT * FROM `blk_play_record` bpr WHERE 1 ".$maWhereSql." GROUP BY bpr.`alias`,".$maGroupSql." bpr.`uid` HAVING COUNT(*) >= ".$count.") pr JOIN `blk_games_inter` g ON pr.`alias` = g.`alias`
				 WHERE 1 
				 GROUP BY pr.`alias` ".$maGroup;	
		// 	充值金额	充值用户	充值次数	ARPU
		$moSql = 'SELECT  g.`alias`, o.`gameid`, g.`ename` AS `game`, SUM(o.`money`) AS `total`, COUNT(DISTINCT o.`username`) AS `ruser`, COUNT(o.`username`) AS `rnum`, ROUND(SUM(o.`money`)/COUNT(DISTINCT o.`username`), 2) AS `ARPU`, FROM_UNIXTIME(o.`time`, "%Y") AS `year`, '.$lq.'FROM_UNIXTIME(o.`time`, "'.$gdate.'")'.$rq.' AS `date`,'.$lq.'FROM_UNIXTIME(o.`time`, "'.$time.'")'.$rq.' AS `time`
				 FROM `ms_all_orders` o JOIN `blk_games_inter` g ON o.`gameid` = g.`evalue` 
				 WHERE o.`status` = 1 AND o.`gameid` <> 1'. ' '.$moWhereSql 
		.' GROUP BY o.`gameid` '.$moGroupSql.' '
		.'ORDER BY FROM_UNIXTIME(o.`time`, "%Y") DESC, FROM_UNIXTIME(o.`time`, "%m-%d")';
		// 按季度 和 月
		if ($gdate == '%Y-%m-%d' || $gdate == '%Y-%m') {
			$maData = model::getBySql($maSql);
			foreach ($maData as $key=>$value) {
				$data[$value['year']][$value['time']]['auser']  = intval($value['auser']);
				$data[$value['year']][$value['time']]['gameid'] = intval($value['gameid']);
				$data[$value['year']][$value['time']]['alias'] 	= $value['alias'];
			}

			$moData = model::getBySql($moSql);
			foreach ($moData as $key=>$value) {
				$data[$value['year']][$value['time']]['total'] 	= intval($value['total']);
				$data[$value['year']][$value['time']]['ruser'] 	= intval($value['ruser']);
				$data[$value['year']][$value['time']]['rnum']  	= intval($value['rnum']);
				$data[$value['year']][$value['time']]['ARPU']  	= sprintf("%.2f", $value['ARPU']);
				$data[$value['year']][$value['time']]['gameid'] = intval($value['gameid']);
				$data[$value['year']][$value['time']]['alias'] 	= $value['alias'];
			}

			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					$data[$key][$k] = array(
					'total' => array_key_exists('total' , $v) ? intval($v['total']) 		: 0,
					'ruser' => array_key_exists('ruser' , $v) ? intval($v['ruser']) 		: 0,
					'rnum'  => array_key_exists('rnum'  , $v) ? intval($v['rnum'])  		: 0,
					'ARPU'  => array_key_exists('ARPU'  , $v) ? sprintf("%.2f", $v['ARPU']) : 0,
					'auser' => array_key_exists('auser' , $v) ? intval($v['auser']) 		: 0,
					'gameid'=> array_key_exists('gameid', $v) ? intval($v['gameid'])		: 0,
					'alias' => array_key_exists('alias' , $v) ? $v['alias']		  			: 0,
					);
				}
			}
		}
		// 按年
		else if ($gdate == '%Y') {
			$maData = model::getBySql($maSql);
			foreach ($maData as $key=>$value) {
				$data[$value['year']]['auser']  = intval($value['auser']);
				$data[$value['year']]['gameid'] = intval($value['gameid']);
				$data[$value['year']]['alias']  =  $value['alias'];
				$data[$value['year']]['game']  	=  $value['game'];
			}
			$moData = model::getBySql($moSql);
			foreach ($moData as $key=>$value) {
				$data[$value['year']]['total'] 	= intval($value['total']);
				$data[$value['year']]['ruser'] 	= intval($value['ruser']);
				$data[$value['year']]['rnum']  	= intval($value['rnum']);
				$data[$value['year']]['ARPU']  	= sprintf("%.2f", $value['ARPU']);
				$data[$value['year']]['gameid'] = intval($value['gameid']);
				$data[$value['year']]['alias']  =  $value['alias'];
				$data[$value['year']]['game']  	=  $value['game'];
			}
			foreach ($data as $key => $value) {
				$data[$key] = array(
				'total'		=> array_key_exists('total', $value) 	? intval($value['total']) 			: 0,
				'ruser' 	=> array_key_exists('ruser', $value)	? intval($value['ruser']) 			: 0,
				'rnum'  	=> array_key_exists('rnum', $value) 	? intval($value['rnum'])  			: 0,
				'ARPU'  	=> array_key_exists('ARPU', $value) 	? sprintf("%.2f", $value['ARPU']) 	: 0,
				'gameid' 	=> array_key_exists('gameid', $value)	? intval($value['gameid']) 			: 0,
				'alias' 	=> array_key_exists('alias', $value) 	? $value['alias'] 					: 0,
				'auser' 	=> array_key_exists('auser', $value) 	? intval($value['auser']) 			: 0,
				'game'  	=> array_key_exists('game', $value) 	? $value['game'] 					: 0,
				);
			}
		}
		// 所有游戏
		else {
			$maData = model::getBySql($maSql);
			foreach ($maData as $key=>$value) {
				$data[$value['game']]['auser'] 	= intval($value['auser']);
				$data[$value['game']]['gameid'] = intval($value['gameid']);
				$data[$value['game']]['alias']  =  $value['alias'];
				$data[$value['game']]['game']   =  $value['game'];
			}
			$moData = model::getBySql($moSql);
			foreach ($moData as $key=>$value) {
				$data[$value['game']]['total'] 	= intval($value['total']);
				$data[$value['game']]['ruser'] 	= intval($value['ruser']);
				$data[$value['game']]['rnum']  	= intval($value['rnum']);
				$data[$value['game']]['ARPU']  	= sprintf("%.2f", $value['ARPU']);
				$data[$value['game']]['gameid'] = intval($value['gameid']);
				$data[$value['game']]['alias']  =  $value['alias'];
				$data[$value['game']]['game']   =  $value['game'];
			}
			foreach ($data as $key => $value) {
				$data[$key] = array(
				'total'		=> array_key_exists('total', $value) 	? intval($value['total']) 			: 0,
				'ruser' 	=> array_key_exists('ruser', $value)	? intval($value['ruser']) 			: 0,
				'rnum'  	=> array_key_exists('rnum', $value) 	? intval($value['rnum'])  			: 0,
				'ARPU'  	=> array_key_exists('ARPU', $value) 	? sprintf("%.2f", $value['ARPU']) 	: 0,
				'gameid' 	=> array_key_exists('gameid', $value)	? intval($value['gameid']) 			: 0,
				'alias' 	=> array_key_exists('alias', $value) 	? $value['alias'] 					: 0,
				'auser' 	=> array_key_exists('auser', $value) 	? intval($value['auser']) 			: 0,
				'game'  	=> array_key_exists('game', $value) 	? $value['game'] 					: 0,
				);
			}
		}
		return $data;
	}

	// 聯運商數據
	public function agent() {
		$this->checkLogin();
		$agent 	= !empty($_GET['agent'])   && $_GET['agent']	!= 'NULL' ? $_GET['agent'] 	: '';
		$year 	= !empty($_GET['year'])    && $_GET['year']		!= 'NULL' ? $_GET['year'] 	: '';
		$month 	= !empty($_GET['month'])   && $_GET['month'] 	!= 'NULL' ? $_GET['month'] 	: '';
		$alias	= !empty($_GET['alias'])   && $_GET['alias'] 	!= 'NULL' ? $_GET['alias']  : '';
		$agentData = $this->getAgent('', '', '');
		$this->assign('agentData', $agentData);
		$date   = '';
		$this->assign('lastday', '12');
		//　按联运商
		if ($agent) {
			$agentYear = $this->getAgent('', '', '%Y', '', $agent);
			$agentGame = $this->getAgent('', '', '', '', $agent, '1');
			$this->assign('agentYear', $agentYear);
			$this->assign('agentGame', $agentGame);
			$this->assign('agentYearSign', '1');
			$this->assign('agentGameSign', '1');
		}
		if (empty($alias)) {
			if ($agent && $year && $month) {
				$date = $year;
				if (!empty($month) && $month < 10) {
					$date .= "-0".$month;
				}
				else if ($month > 9) {
					$date .= "-".$month;
				}
				$this->assign('lastday', getLastday($date));
				$this->assign('day', 1);
				$agentQuarter 	= $this->getAgent('%Y', $year, '%Y-%m-%d', '1', $agent);
				$agentDay   	= $this->getAgent('%Y-%m', $date, '%Y-%m', '', $agent);
				$this->assign('agentQuarter', $agentQuarter);
				$this->assign('agentMonth', $agentDay);
				$this->assign('agentQuarterSign', '1');
				$this->assign('agentMonthSign', '1');
				$playSql   = "SELECT COUNT(*) AS `playusers`
							  FROM `ms_agent_play_log` fgpl
							  WHERE 1 AND FROM_UNIXTIME(fgpl.`playtime`, '%Y-%m') < '$date'";
				$users = model::getBySql($playSql);
				$this->assign('playusers', $users[0]['playusers']);
			}
			else if ($agent && $year) {
				$agentQuarter = $this->getAgent('%Y', $year, '%Y-%m-%d', '1', $agent);
				$agentMonth   = $this->getAgent('%Y', $year, '%Y-%m', '', $agent);
				$this->assign('agentQuarter', $agentQuarter);
				$this->assign('agentMonth', $agentMonth);
				$this->assign('agentQuarterSign', '1');
				$this->assign('agentMonthSign', '1');
				$playSql   = "SELECT COUNT(*) AS `playusers`
							  FROM `ms_agent_play_log` fgpl
							  WHERE 1 AND FROM_UNIXTIME(fgpl.`playtime`, '%Y-%m') < '$date'";
				$users = model::getBySql($playSql);
				$this->assign('playusers', $users[0]['playusers']);
			}
		}
		// 按游戏
		else if ($alias) {
			$gameYear = $this->getAgent('', '', '', '', $agent, '1', $alias);
			$this->assign('gameYear', $gameYear);
			$this->assign('gameYearSign', '1');
			if ($agent && $year && $month) {
				$date = $year;
				if (!empty($month) && $month < 10) {
					$date .= "-0".$month;
				}
				else if ($month > 9) {
					$date .= "-".$month;
				}
				$this->assign('lastday', getLastday($date));
				$this->assign('day', 1);
				$gameQuarter = $this->getAgent('%Y', $year, '%Y-%m-%d', '1', $agent, '', $alias);
				$gameDay   	 = $this->getAgent('%Y-%m', $date, '%Y-%m', '', $agent, '', $alias);
				$this->assign('agentQuarter', $gameQuarter);
				$this->assign('agentMonth', $gameDay);
				$this->assign('agentQuarterSign', '1');
				$this->assign('agentMonthSign', '1');
				$this->assign('alias', $alias);
				$playSql   = "SELECT COUNT(*) AS `playusers`
							  FROM `ms_agent_play_log` fgpl
							  WHERE 1 AND FROM_UNIXTIME(fgpl.`playtime`, '%Y-%m') < '$date'";
				$users = model::getBySql($playSql);
				$this->assign('playusers', $users[0]['playusers']);
			}
			else if ($agent && $year) {
				$gameQuarter = $this->getAgent('%Y', $year, '%Y-%m-%d', '1', $agent, '', $alias);
				$gameMonth   = $this->getAgent('%Y', $year, '%Y-%m', '', $agent, '', $alias);
				$this->assign('alias', $alias);
				$this->assign('agentQuarter', $gameQuarter);
				$this->assign('agentMonth', $gameMonth);
				$this->assign('agentQuarterSign', '1');
				$this->assign('agentMonthSign', '1');
				$playSql   = "SELECT COUNT(*) AS `playusers`
							  FROM `ms_agent_play_log` fgpl
							  WHERE 1 AND FROM_UNIXTIME(fgpl.`playtime`, '%Y-%m') < '$date'";
				$users = model::getBySql($playSql);
				$this->assign('playusers', $users[0]['playusers']);
			}
		}
	}

	/**
	 * 获取联运商数据
	 * 
	 * @param string $wdate     where条件
	 * @param string $date		where条件对应年（月）
	 * @param string $gdate		group by 条件
	 * @param string $quarter   季度查询标志
	 * @param string $agent		联运商名称
	 * @param string $gGame     按游戏查询标志
	 * @param string $game		游戏gameid	
	 * @param string $alias		游戏别名
	 */
	private function getAgent($wdate, $date, $gdate, $quarter = null, $agent = null, $gGame = null, $game = null, $alias = null) {
		$this->checkLogin();
		$time = ($gdate == '%Y-%m-%d') ? '%e' 	: ($gdate == '%Y-%m' ? '%c' : '');
		$count= ($gdate == '%Y-%m-%d') ? 1 		: ($gdate == '%Y-%m' ? 2 	: 2);
		$oWhereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(fgo.`otime`, '$wdate') 		= '$date' " : '';
		$lWhereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(fall.`logintime`, '$wdate') 	= '$date' " : '';
		$pWhereSql = !empty($wdate) && !empty($date) ? " AND FROM_UNIXTIME(fgpl.`playtime`, '$wdate') 	= '$date' " : '';
		if(!empty($quarter)) {
			$lq 	= 'QUARTER(';
			$rq		= ')';
			$time 	= '%Y-%m-%d';
			$count  = 2;
		}
		else {
			$lq 	= '';
			$rq	 	= '';
		}
		if ($gdate) {
			$oGroupSql 		= ", `date` ";
			$fallGroupSql 	= ", $lq FROM_UNIXTIME(fall.`logintime`, '$gdate') $rq ";
			$pGroupSql 		= ", `date` ";
			$lGroupSql 		= ",`date`";
		}
		if ($gGame) {
			$oGroupSql 		.= ",fgo.`game`";
			$fallGroupSql 	.= ",fall.`game`";
			$lGroupSql 		.= ",al.`game`";
			$pGroupSql 		.= ",fgpl.`game`";
		}
		if ($agent) {
			$oWhereSql 		.= " AND fgo.`agent_name` 	= '$agent' ";
			$lWhereSql 		.= " AND fall.`agent` 		= '$agent' ";
			$pWhereSql 		.= " AND fgpl.`agent` 		= '$agent' ";
		}
		if ($game) {
			$oWhereSql 		.= " AND fgo.`game` 		= '$game' ";
			$lWhereSql 		.= " AND fall.`game` 		= '$game' ";
			$pWhereSql 		.= " AND fgpl.`game` 		= '$game' ";
			$gameName = model::getBySql("SELECT `ename` FROM `blk_games_inter` WHERE `alias` = '$game' LIMIT 1");
			$this->assign('gameName', $gameName[0]['ename']);
		}
		//　充值数据
		$ordersSql = "SELECT g.`ename` AS `gameName`, fgo.`game`, fgo.`agent_name` AS `agent_name`, SUM(fgo.`agent_pay_money`) AS `totalMoney`, COUNT(DISTINCT fgo.`ousername`) AS `payuser`, COUNT(fgo.`ousername`) AS `paytimes`, ROUND(SUM(fgo.`agent_pay_money`)/COUNT(DISTINCT fgo.`ousername`),2) AS `ARPU`,
					  	FROM_UNIXTIME(fgo.`otime`, '%Y') AS `year`,".$lq."FROM_UNIXTIME(fgo.`otime`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(fgo.`otime`, '".$time."')".$rq." AS `time`
					  FROM `ms_agent_user` fau  JOIN `blk_games_inter` g ON fau.`game` = g.`alias` JOIN `ms_agent_orders` fgo ON fau.`username` = fgo.`ousername` AND fau.`game` = fgo.`game` AND fau.`agent` = fgo.`agent_name`
					  WHERE 1 AND fgo.`ostatus` = 1 AND g.`parent_id` = 0 ".$oWhereSql."
					  GROUP BY fgo.`agent_name` ".$oGroupSql;
		// 活跃用户
		$loginSql  = "SELECT g.`ename` AS `gameName`, al.`agent` AS `agent_name`, al.`game`, COUNT(*) AS `loginuser`, ".$lq."FROM_UNIXTIME(al.`logintime`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(al.`logintime`, '".$time."')".$rq." AS `time`, FROM_UNIXTIME(al.`logintime`, '%Y') AS `year`
					  FROM (SELECT * FROM `ms_agent_login_log` fall WHERE 1 ".$lWhereSql." GROUP BY fall.`agent`, fall.`username` ".$fallGroupSql." HAVING COUNT(*) >= $count) al JOIN `blk_games_inter` g ON al.`game` = g.`alias`
					  WHERE 1
					  GROUP BY al.`agent` ".$lGroupSql;
		//　登入玩家
		$playSql   = "SELECT g.`ename` AS `gameName`,FROM_UNIXTIME(fgpl.`playtime`, '%Y') AS `year`, fgpl.`agent` AS `agent_name`,fgpl.`game`, COUNT(DISTINCT fgpl.`username`) AS `playuser`, ".$lq."FROM_UNIXTIME(fgpl.`playtime`, '".$gdate."')".$rq." AS `date`, ".$lq."FROM_UNIXTIME(fgpl.`playtime`, '".$time."')".$rq." AS `time`
					  FROM `ms_agent_play_log` fgpl JOIN `blk_games_inter` g ON fgpl.`game` = g.`alias`
					  WHERE 1 ".$pWhereSql."
					  GROUP BY fgpl.`agent` ".$pGroupSql;
		// 按季度  和 月
		if ($gdate == '%Y-%m-%d' || $gdate == '%Y-%m') {
			$orderData = model::getBySql($ordersSql);
			foreach ($orderData as $value) {
				$data[$value['year']][$value['time']]['agent_name'] = intval($value['agent_name']);
				$data[$value['year']][$value['time']]['gameName'] 	= $value['gameName'];
				$data[$value['year']][$value['time']]['game'] 		= $value['game'];
				$data[$value['year']][$value['time']]['totalMoney'] = intval($value['totalMoney']);
				$data[$value['year']][$value['time']]['payuser'] 	= intval($value['payuser']);
				$data[$value['year']][$value['time']]['paytimes'] 	= intval($value['paytimes']);
				$data[$value['year']][$value['time']]['ARPU'] 		= sprintf("%.2f", $value['ARPU']);
			}
			$loginData = model::getBySql($loginSql);
			foreach ($loginData as $value) {
				$data[$value['year']][$value['time']]['agent_name'] = intval($value['agent_name']);
				$data[$value['year']][$value['time']]['gameName'] 	= $value['gameName'];
				$data[$value['year']][$value['time']]['game'] 		= $value['game'];
				$data[$value['year']][$value['time']]['loginuser']  = intval($value['loginuser']);
			}
			$playData = model::getBySql($playSql);
			foreach ($playData as $value) {
				$data[$value['year']][$value['time']]['agent_name'] = intval($value['agent_name']);
				$data[$value['year']][$value['time']]['gameName'] 	= $value['gameName'];
				$data[$value['year']][$value['time']]['game'] 		= $value['game'];
				$data[$value['year']][$value['time']]['playuser']	= intval($value['playuser']);
			}
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					$data[$key][$k] = array(
					'agent_name' 	=> array_key_exists('agent_name', $v) ? $v['agent_name'] : 0,
					'totalMoney' 	=> array_key_exists('totalMoney', $v) ? intval($v['totalMoney']) : 0,
					'payuser' 		=> array_key_exists('payuser'   , $v) ? intval($v['payuser']) : 0,
					'paytimes'  	=> array_key_exists('paytimes' 	, $v) ? intval($v['paytimes'])  : 0,
					'ARPU'  		=> array_key_exists('ARPU		', 	$v) ? sprintf("%.2f", $v['ARPU']) : 0,
					'loginuser' 	=> array_key_exists('loginuser' , $v) ? intval($v['loginuser']) : 0,
					'playuser' 		=> array_key_exists('playuser' , $v) ? intval($v['playuser']) : 0,
					'gameName' 		=> array_key_exists('gameName' , $v) ? $v['gameName']: 0,
					'game' 			=> array_key_exists('game', $v) ? $v['game']: 0,
					);
				}
			}
		}
		// 按年
		else if ($gdate == '%Y') {
			$orderData = model::getBySql($ordersSql);
			foreach ($orderData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName'] 		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['totalMoney'] 	= intval($value['totalMoney']);
				$data[$value['year']]['payuser'] 		= intval($value['payuser']);
				$data[$value['year']]['paytimes'] 		= intval($value['paytimes']);
				$data[$value['year']]['ARPU'] 			= sprintf("%.2f", $value['ARPU']);

			}
			$loginData = model::getBySql($loginSql);
			foreach ($loginData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName'] 		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['loginuser'] 		= intval($value['loginuser']);
			}

			$playData = model::getBySql($playSql);
			foreach ($playData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName'] 		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['playuser'] 		= intval($value['playuser']);
			}
			foreach ($data as $key => $value) {
				$data[$key]['agent_name'] 	= array_key_exists('agent_name', $value) 	? $value['agent_name'] 				: 0;
				$data[$key]['totalMoney'] 	= array_key_exists('totalMoney', $value) 	? intval($value['totalMoney'])		: 0;
				$data[$key]['payuser'] 		= array_key_exists('payuser', $value) 		? intval($value['payuser'])			: 0;
				$data[$key]['paytimes'] 	= array_key_exists('paytimes', $value) 		? intval($value['paytimes'])		: 0;
				$data[$key]['ARPU'] 		= array_key_exists('ARPU', $value) 			? sprintf("%.2f", $value['ARPU']) 	: 0;
				$data[$key]['loginuser'] 	= array_key_exists('loginuser', $value) 	? intval($value['loginuser'])		: 0;
				$data[$key]['year'] 		= array_key_exists('year', $value) 			? intval($value['year'])			: 0;
				$data[$key]['playuser'] 	= array_key_exists('playuser', $value) 		? intval($value['playuser'])		: 0;
				$data[$key]['gameName'] 	= array_key_exists('gameName', $value) 		? $value['gameName']				: 0;
				$data[$key]['game'] 		= array_key_exists('game', $value)  		? $value['game']					: 0;
			}
		}
		//　点击游戏   按年
		else if ($game && $gGame) {
			$orderData = model::getBySql($ordersSql);
			foreach ($orderData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName']		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['totalMoney'] 	= intval($value['totalMoney']);
				$data[$value['year']]['payuser'] 		= intval($value['payuser']);
				$data[$value['year']]['paytimes'] 		= intval($value['paytimes']);
				$data[$value['year']]['ARPU'] 			= sprintf("%.2f", $value['ARPU']);

			}
			$loginData = model::getBySql($loginSql);
			foreach ($loginData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName']		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['loginuser'] 		= intval($value['loginuser']);
			}

			$playData = model::getBySql($playSql);
			foreach ($playData as $key=>$value) {
				$data[$value['year']]['year'] 			= intval($value['year']);
				$data[$value['year']]['agent_name'] 	= $value['agent_name'];
				$data[$value['year']]['gameName']		= $value['gameName'];
				$data[$value['year']]['game'] 			= $value['game'];
				$data[$value['year']]['playuser'] 		= intval($value['playuser']);
			}

			foreach ($data as $key => $value) {
				$data[$key]['agent_name'] 	= array_key_exists('agent_name', $value) 	? $value['agent_name'] 				: 0;
				$data[$key]['totalMoney'] 	= array_key_exists('totalMoney', $value) 	? intval($value['totalMoney'])		: 0;
				$data[$key]['payuser'] 		= array_key_exists('payuser', $value) 		? intval($value['payuser'])			: 0;
				$data[$key]['paytimes'] 	= array_key_exists('paytimes', $value) 		? intval($value['paytimes'])		: 0;
				$data[$key]['ARPU'] 		= array_key_exists('ARPU', $value) 			? sprintf("%.2f", $value['ARPU']) 	: 0;
				$data[$key]['loginuser'] 	= array_key_exists('loginuser', $value) 	? intval($value['loginuser'])		: 0;
				$data[$key]['year'] 		= array_key_exists('year', $value) 			? intval($value['year'])			: 0;
				$data[$key]['playuser'] 	= array_key_exists('playuser', $value) 		? intval($value['playuser'])		: 0;
				$data[$key]['gameName'] 	= array_key_exists('gameName', $value) 		? $value['gameName']				: 0;
				$data[$key]['game'] 		= array_key_exists('game', $value)  		? $value['game']					: 0;
			}
		}
		//　所有游戏
		else if ($gGame) {
			$orderData = model::getBySql($ordersSql);
			foreach ($orderData as $key=>$value) {
				$data[$value['gameName']]['year'] 			= intval($value['year']);
				$data[$value['gameName']]['agent_name'] 	= $value['agent_name'];
				$data[$value['gameName']]['gameName'] 		= $value['gameName'];
				$data[$value['gameName']]['game'] 			= $value['game'];
				$data[$value['gameName']]['totalMoney'] 	= intval($value['totalMoney']);
				$data[$value['gameName']]['payuser'] 		= intval($value['payuser']);
				$data[$value['gameName']]['paytimes'] 		= intval($value['paytimes']);
				$data[$value['gameName']]['ARPU'] 			= sprintf("%.2f", $value['ARPU']);

			}
			$loginData = model::getBySql($loginSql);
			foreach ($loginData as $key=>$value) {
				$data[$value['gameName']]['year'] 			= intval($value['year']);
				$data[$value['gameName']]['agent_name'] 	= $value['agent_name'];
				$data[$value['gameName']]['gameName'] 		= $value['gameName'];
				$data[$value['gameName']]['game'] 			= $value['game'];
				$data[$value['gameName']]['loginuser'] 		= intval($value['loginuser']);
			}
			$playData = model::getBySql($playSql);
			foreach ($playData as $key=>$value) {
				$data[$value['gameName']]['year'] 			= intval($value['year']);
				$data[$value['gameName']]['agent_name'] 	= $value['agent_name'];
				$data[$value['gameName']]['gameName'] 		= $value['gameName'];
				$data[$value['gameName']]['game'] 			= $value['game'];
				$data[$value['gameName']]['playuser'] 		= intval($value['playuser']);
			}
			foreach ($data as $key => $value) {
				$data[$key]['agent_name'] 	= array_key_exists('agent_name', $value) 	? $value['agent_name'] 				: 0;
				$data[$key]['totalMoney'] 	= array_key_exists('totalMoney', $value) 	? intval($value['totalMoney'])		: 0;
				$data[$key]['payuser'] 		= array_key_exists('payuser', $value) 		? intval($value['payuser'])			: 0;
				$data[$key]['paytimes'] 	= array_key_exists('paytimes', $value) 		? intval($value['paytimes'])		: 0;
				$data[$key]['ARPU'] 		= array_key_exists('ARPU', $value) 			? sprintf("%.2f", $value['ARPU']) 	: 0;
				$data[$key]['loginuser'] 	= array_key_exists('loginuser', $value) 	? intval($value['loginuser'])		: 0;
				$data[$key]['year'] 		= array_key_exists('year', $value) 			? intval($value['year'])			: 0;
				$data[$key]['playuser'] 	= array_key_exists('playuser', $value) 		? intval($value['playuser'])		: 0;
				$data[$key]['gameName'] 	= array_key_exists('gameName', $value) 		? $value['gameName']				: 0;
				$data[$key]['game'] 		= array_key_exists('game', $value)  		? $value['game']					: 0;
			}
		}
		// 所有联运商
		else {
			$orderData = model::getBySql($ordersSql);
			foreach ($orderData as $key=>$value) {
				$data[$value['agent_name']]['year'] 		= intval($value['year']);
				$data[$value['agent_name']]['agent_name'] 	= $value['agent_name'];
				$data[$value['agent_name']]['gameName'] 	= $value['gameName'];
				$data[$value['agent_name']]['game'] 		= $value['game'];
				$data[$value['agent_name']]['totalMoney'] 	= intval($value['totalMoney']);
				$data[$value['agent_name']]['payuser'] 		= intval($value['payuser']);
				$data[$value['agent_name']]['paytimes'] 	= intval($value['paytimes']);
				$data[$value['agent_name']]['ARPU'] 		= sprintf("%.2f", $value['ARPU']);

			}
			$loginData = model::getBySql($loginSql);
			foreach ($loginData as $key=>$value) {
				$data[$value['agent_name']]['year'] 		= intval($value['year']);
				$data[$value['agent_name']]['agent_name'] 	= $value['agent_name'];
				$data[$value['agent_name']]['gameName'] 	= $value['gameName'];
				$data[$value['agent_name']]['game'] 		= $value['game'];
				$data[$value['agent_name']]['loginuser'] 	= intval($value['loginuser']);
			}
			$playData = model::getBySql($playSql);
			foreach ($playData as $key=>$value) {
				$data[$value['agent_name']]['year'] 		= intval($value['year']);
				$data[$value['agent_name']]['agent_name'] 	= $value['agent_name'];
				$data[$value['agent_name']]['gameName'] 	= $value['gameName'];
				$data[$value['agent_name']]['game'] 		= $value['game'];
				$data[$value['agent_name']]['playuser'] 	= intval($value['playuser']);
			}
			foreach ($data as $key => $value) {
				$data[$key]['agent_name'] 	= array_key_exists('agent_name', $value) 	? $value['agent_name'] 				: 0;
				$data[$key]['totalMoney'] 	= array_key_exists('totalMoney', $value) 	? intval($value['totalMoney'])		: 0;
				$data[$key]['payuser'] 		= array_key_exists('payuser', $value) 		? intval($value['payuser'])			: 0;
				$data[$key]['paytimes'] 	= array_key_exists('paytimes', $value) 		? intval($value['paytimes'])		: 0;
				$data[$key]['ARPU'] 		= array_key_exists('ARPU', $value) 			? sprintf("%.2f", $value['ARPU']) 	: 0;
				$data[$key]['loginuser'] 	= array_key_exists('loginuser', $value) 	? intval($value['loginuser'])		: 0;
				$data[$key]['year'] 		= array_key_exists('year', $value) 			? intval($value['year'])			: 0;
				$data[$key]['playuser'] 	= array_key_exists('playuser', $value) 		? intval($value['playuser'])		: 0;
				$data[$key]['gameName'] 	= array_key_exists('gameName', $value) 		? $value['gameName']				: 0;
				$data[$key]['game'] 		= array_key_exists('game', $value)  		? $value['game']					: 0;
			}
		}
		return $data;
	}

	//遊戲接口數據
	public function mGameApiLog(){
		//檢測是否登錄
		$this->checkLogin();
		session_start();		
		$dbOj = $this->dbOj;
		$do = isset($_GET['do'])?$_GET['do']:'';			
		if($do == 'search'){						
			load('model.mGameApiLog');			
			$data_url = C('DEDEDATA').'logs';																	
			if(!empty($_REQUEST['starttime']) && !empty($_REQUEST['endtime'])) {				
				$files = mGameApiLog::listdir($data_url, $_REQUEST['game'], $_REQUEST['starttime'], $_REQUEST['endtime']);										
				mGameApiLog::getAllLog($files);				
				$this->assign('btime', $_REQUEST['starttime']);
				$this->assign('etime', $_REQUEST['endtime']);		
				$this->assign('fgame', $_REQUEST['game']);				
				$this->assign('files', $files);	
			}else if(!empty($_REQUEST['game'])) {
				$files = mGameApiLog::listdir2($data_url, $_REQUEST['game']);									
				mGameApiLog::getAllLog($files);					
				$this->assign('fgame', $_REQUEST['game']);				
				$this->assign('files', $files);	
			   }					
		 }else {
		 	$sdays = time()+(7 * 24 * 60 * 60);
		 	$week = date('Y-m-d', $sdays);
			$today = date('Y-m-d', time());			
			$this->assign('today', $today);
			$this->assign('weeklater', $week);
		 }
		//游戏名列表
		require C('DEDEDATA') .'/enums/games_setting.php';
		$this->assign('games', $games);
	}
	
	//游戏日志数据显示页面
	public function lGameApiLog() {
		//檢測是否登錄
		$this->checkLogin();				
		session_start();		
		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;					
        $row = 10;              
        $this->assign('offset', $row);	                
        $offset = ($page - 1) * $row;             
		$log_model = new model("ms_logfile");		
		//该列表数据可分三种搜索情况,第一种搜索情况，制定游戏名即可搜索该条件下的日志		
		if(!empty($_REQUEST['game'])) {	
			// $row和$offset 必须重新赋值, 用于本条件语句内部的条件语句,即第二种搜索情况的内部,否则 查询语句会搜索不到
				 $row2 = 10;               		      	                
		         $offset2 = ($page - 1) * $row;
		        	        						
				$where = "game like '%".$_REQUEST['game']."%'";											
				$total_row = $log_model->getBySql("SELECT COUNT(*) AS total FROM ms_logfile WHERE $where");						
		        $total = $total_row[0]['total'];	                	
				$log_row = $log_model->getBySql("SELECT * FROM ms_logfile WHERE $where ORDER BY id DESC LIMIT $offset, $row"); 			 				
				$log_list = array();				
				foreach($log_row as $row) {					
					$log_list[] = $row;				
				}				
				$this->assign('fgame', $_REQUEST['game']);	
				//第二种搜索情况，指定游戏名和日期，即可搜索到该条件下的日志						                     							
				if(!empty($_REQUEST['starttime']) && !empty($_REQUEST['endtime'])) {					
					$where = "addtime >= '".strtotime($_REQUEST['starttime'])."' AND addtime <= '".strtotime($_REQUEST['endtime'])."' AND"." game like '%".$_REQUEST['game']."%'";											
					$total_row = $log_model->getBySql("SELECT COUNT(*) AS total FROM ms_logfile WHERE $where");						
			        $total = $total_row[0]['total'];	                	
					$log_row = $log_model->getBySql("SELECT * FROM ms_logfile WHERE $where ORDER BY id DESC LIMIT $offset2, $row2"); 			 				
					$log_list = array();				
					foreach($log_row as $row) {					
						$log_list[] = $row;	
					}
					if($log_row == false) {
						$this->assign('warn', '请注意,该搜索条件下没有相关数据！');
					}			
					$this->assign('fgame', $_REQUEST['game']);
					$this->assign('tbtime', $_REQUEST['starttime']);
					$this->assign('tetime', $_REQUEST['endtime']);					
				}
		//第三种搜索情况，指定时间段即可搜索到该条件下的日志				
		}else if(!empty($_REQUEST['starttime']) && !empty($_REQUEST['endtime'])) {
				$where = "addtime >= '".strtotime($_REQUEST['starttime'])."' AND addtime <= '".strtotime($_REQUEST['endtime'])."'";											
				$total_row = $log_model->getBySql("SELECT COUNT(*) AS total FROM ms_logfile WHERE $where");						
		        $total = $total_row[0]['total'];	                	
				$log_row = $log_model->getBySql("SELECT * FROM ms_logfile WHERE $where ORDER BY id DESC LIMIT $offset, $row"); 			 				
				$log_list = array();				
				foreach($log_row as $row) {					
					$log_list[] = $row;				
				}
				$this->assign('tbtime', $_REQUEST['starttime']);
				$this->assign('tetime', $_REQUEST['endtime']);
				if($log_row == false) {
					$this->assign('warn', '请注意,该搜索条件下没有相关数据！');
				}
			//在没有指定任何搜索条件下，如:刚进入日志数据阅览界面时的情况
		  }else {				
				$total_row = $log_model->getBySql("SELECT COUNT(*) AS total FROM ms_logfile");		
		        $total = $total_row[0]['total'];	        	
				$log_row = $log_model->getBySql("SELECT * FROM ms_logfile ORDER BY id DESC LIMIT $offset, $row");  			
				$log_list = array();															
				foreach($log_row as $row) {									
					$log_list[] = $row;				
				}			
			}				
		//游戏名列表
		require C('DEDEDATA') .'/enums/games_setting.php';		
		$this->assign('pagesize', $allpage);		
		$this->assign('games', $games);		
		$this->assign('total', $total);		
        $this->assign('page', $page);      
		$this->assign('log_list', is_array($log_list) ? $log_list : array());
	}
	
		
}
