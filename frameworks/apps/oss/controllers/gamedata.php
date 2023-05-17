<?php
require_once APP_CONTROLLER_PATH . '/master.php';
class gamedataController extends masterControl {
	// 主頁面
	public function index() {
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getChannel"  : 
						$this->getChannel('channel');			
						break;
				case "getGameUser"   :
						$this->getGameUser();
				case "getStoreValue" : 
						$this->getStoreValue('totalMonty');			
						break;
			    case "getPayUser"	:
				   		$this->getPayUser();
				   		break;
			}
		}		
		$sql 	  = "SELECT `alias`, `ename` FROM `blk_games_inter` WHERE `parent_id` = '0'";
		$gameData = model::getBySql($sql);
		$this->assign("game", $gameData);
		$this->assign('date', date('Y-m-d', strtotime("now -7 day")));
	}
	/*
	 * $type = 'channel' 渠道創號數
	 * $type = 'game'    遊戲導入帳號數
	 */
	public function getChannel($type = 'channel') {
		$this->checkLogin();
		header('Content-Type: application/json');
		$date 		 = isset($_REQUEST['date']) 	  ? $_REQUEST['date'] : '';
		$condition   = isset($_REQUEST['condition'])  ? $_REQUEST['condition'] : '';
		$game   	 = isset($_REQUEST['game'])  	  ? $_REQUEST['game'] : '';
		if ($type == 'channel') {
			switch ($condition) {
				case 'week' : 
					if ($date) {
						$start 	  = $date;
						$end   	  = date('Y-m-d', strtotime("$start +6 day"));
						$sel   	  = "`date`";
						$whereSql = " AND `date` BETWEEN  '$start' AND '$end' ";
						$groupSql = "`date`";
					}
					break;
				case 'month':
					if ($date) {
						$start 	  = substr($date, 0, 7);
						$end   	  = date('Y-m', strtotime("$start +5 month"));
						$sel      = "SUBSTR(`date`, 1 , 7) AS `date`";
						$whereSql = " AND SUBSTR(`date`, 1 , 7) BETWEEN  '$start' AND '$end' ";
						$groupSql = "SUBSTR(`date`, 1 , 7)";
					}
					break;
			}
			$field = 'channel';
			$title = '渠道';
			$sql = "SELECT `channel`, $sel, SUM(`num`) AS `user` 
					FROM `ms_reg_data`
					WHERE 1 ".$whereSql."
					GROUP BY `channel`, $groupSql";						
		}
		else if ($type == 'game') {
			if ($game) {
				$field = 'channel';
				$title = '渠道';	
				$whereSql = " AND fgu.`game` = '$game' ";		
				$groupSql = "fgu.`channel`, `date`";
			}
			else {
				$field = 'ename';
				$title = '遊戲';				
				$groupSql = "fgu.`game`, `date`";
			}			
			switch ($condition) {
				// 未來一週
				case 'week' : 
					if ($date) {
						$start 	  = $date;
						$end   	  = date('Y-m-d', strtotime("$start +6 day"));
						$whereSql.= " AND fgu.`units` = 'day' AND fgu.`date` BETWEEN  '$start' AND '$end' ";
					}				
					break;
				//　未來6個月
				case 'month':
					if ($date) {
						$start 	  = substr($date, 0, 7);
						$end   	  = date('Y-m', strtotime("$start +5 month"));
						$whereSql.= " AND fgu.`units` = 'month' AND fgu.`date` BETWEEN  '$start' AND '$end' ";
					}
					break;
			}
			$sql = "SELECT g.`ename`, fgu.`channel`, SUM(fgu.`num`) AS `user` , fgu.`date`
					FROM `ms_gameuser_data` fgu JOIN `blk_games_inter` g ON fgu.`game` = CONVERT(g.`alias` USING utf8)
					WHERE g.`parent_id` = '0' AND 1 $whereSql
					GROUP BY $groupSql";			
		}
		$data = model::getBySql($sql);
		if (is_array($data) && count($data)) {
			$res  = array();
			$time = array();
			foreach ($data as $val) {
				$res[$val[$field]][$val['date']] = $val['user'];
				$total[$val['date']]			 = $total[$val['date']] + $val['user'];
			}
			// 表頭
			$header = "<thead><tr><th>$title/日期</th><th>$start</th>";
			array_push($time, $start);
			
			if ($condition == 'week') {
				for ($i = 1; $i <= 5; $i++) {
					$riqi   = date('Y-m-d', strtotime("$start +$i days"));
					array_push($time, $riqi);
					$header .= "<th>$riqi</th>";
				}				
			}
			else if ($condition == 'month') {
				for ($i = 1; $i <= 4; $i++) {
					$riqi   = date('Y-m', strtotime("$start +$i month"));
					array_push($time, $riqi);
					$header .= "<th>$riqi</th>";
				}					
			}
			array_push($time, $end);
			$header .= "<th>$end</th></tr></thead>";
			
			// 表主體數據
			foreach ($res as $key => $value) {
				$tr .= "<tr><td>$key</td>";
				foreach ($time as $v) {
					if (array_key_exists($v, $value)) {
						$tr .= "<td>$value[$v]</td>";
					}
					else {
						$tr .= "<td>0</td>";	
					}		
				}
				$tr .= "</tr>";
			}
			if ($type == 'channel') {
				$tr .= "<tr><td>匯總</td>";
				foreach ($time as $v) {
					if (array_key_exists($v, $total)) {
						$tr .= "<td>$total[$v]</td>";
					}
					else {
						$tr .= "<td>0</td>";	
					}		
				}
				$tr .= "</tr>";
			}
			$json['data'] = $header . '<tbody>' . $tr . '</tbody>';
			$json['temp'] = '1';
		}
		else {
			$json['temp'] = '0';
		}
		echo json_encode($json);
		exit;
	}
	
	// 儲值金額
	public function getStoreValue($index = 'totalMonty') {
		$this->checkLogin();
		header('Content-Type: application/json');
		$date 		= isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
		$game 		= isset($_REQUEST['game']) ? $_REQUEST['game'] : '';
		$condition  = isset($_REQUEST['condition'])  ? $_REQUEST['condition'] : '';
		if ($game){
			$whereSql = " AND `game` = '$game' ";
			$groupSql = " fgl.`channel`, `date`";
			$field	  = 'channel';
			$title    = '渠道';
		}
		else {
			$groupSql = " fgl.`game`, `date`";
			$field	  = 'ename';
			$title    = '遊戲';
		}
		switch ($condition) {
			case 'week' : 
				if ($date) {
					$start = $date;
					$end   = date('Y-m-d', strtotime("$start +7 day"));
					$end2  = date('Y-m-d', strtotime("$start +6 day"));
					$dateFormat = '%Y-%m-%d';
					$whereSql .= " AND fgl.`paytime` BETWEEN  '".strtotime($start)."' AND '".(strtotime($end)-1)."' ";
				}
				break;
			case 'month' : 
				if ($date) {
					$start = substr($date, 0 ,7);
					$end   = date('Y-m', strtotime("$start +6 month -1 days"));
					$end2   = date('Y-m', strtotime("$start +6 month -1 days"));
					$dateFormat = '%Y-%m';
					$whereSql .= " AND FROM_UNIXTIME(fgl.`paytime`, '%Y-%m') BETWEEN  '$start' AND '$end' ";
				}				
				break;
		}

		$sql = "SELECT fgl.`channel`, g.`ename`, FROM_UNIXTIME(fgl.`paytime`, '$dateFormat') AS `date`, COUNT(DISTINCT `username`) AS `user`, SUM(fgl.`money`) AS `totalMonty` 
				FROM `ms_gamepay_log` fgl JOIN `blk_games_inter` g ON fgl.`game` = CONVERT(g.`alias` USING utf8)
				WHERE g.`parent_id` = '0' AND fgl.send_status = 1 ".$whereSql."
				GROUP BY $groupSql";
		$data = model::getBySql($sql);
		if (is_array($data) && count($data)) {
			$res  = array();
			$time = array();		
			foreach ($data as $val) {
				$res[$val[$field]][$val['date']] = $val[$index];
				$total[$val['date']]			 = $total[$val['date']] + $val[$index];
			}
			$header = "<thead><tr><th>$title/日期</th><th>$start</th>";
			array_push($time, $start);
			if ($condition == 'week') {
				for ($i = 1; $i <= 5; $i++) {
					$riqi   = date('Y-m-d', strtotime("$start +$i days"));
					array_push($time, $riqi);
					$header .= "<th>$riqi</th>";
				}				
			}
			else if ($condition == 'month') {
				for ($i = 1; $i <= 4; $i++) {
					$riqi   = date('Y-m', strtotime("$start +$i month"));
					array_push($time, $riqi);
					$header .= "<th>$riqi</th>";
				}					
			}
			array_push($time, $end2);
			$header .= "<th>$end2</th></tr></thead>";
			foreach ($res as $key => $value) {
				$tr .= "<tr><td>$key</td>";
				foreach ($time as $v) {
					if (array_key_exists($v, $value)) {
						$tr .= "<td>$value[$v]</td>";
					}
					else {
						$tr .= "<td>0</td>";	
					}		
				}
				$tr .= "</tr>";
			}
			
			$tr .= "<tr><td>匯總</td>";
			foreach ($time as $v) {
				if (array_key_exists($v, $total)) {
					$tr .= "<td>$total[$v]</td>";
				}
				else {
					$tr .= "<td>0</td>";	
				}		
			}
			$tr .= "</tr>";			
			$json['data'] = $header . '<tbody>' . $tr . '</tbody>';
			$json['temp'] = '1';
		}
		else {
			$json['temp'] = '0';
		}
		echo json_encode($json);
		exit;
	}
	
	// 不重複儲值帳號
	public function getPayUser() {
		$this->checkLogin();
		$this->getStoreValue('user');
	}
	
	// 遊戲導入帳號
	public function getGameUser() {
		$this->checkLogin();
		$this->getChannel('game');
	}
}