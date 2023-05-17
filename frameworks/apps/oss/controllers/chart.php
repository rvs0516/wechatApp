<?php
require_once APP_CONTROLLER_PATH . '/master.php';
class chartController extends masterControl {
	// 設置線形圖樣式
	public function setLine($div, $title, $subtitle, $dateformat = 'datetime', $label = '%d', $tickInterval = 86400000, $yaxis = '人数', $tooltipd = '%Y-%m-%d', $tooltip = '', $plot = '0') {
		load('model.highcharts');
		$chart = highcharts::getInstance();
		$chart->setChart( array('renderTo'=>"'{$div}'", 'defaultSeriesType'=>"'line'", 'zoomType'=>"'x'") );
		$chart->setTitle( 'text', "'{$title}'" );
		$chart->setSubTitle( 'text', "'{$subtitle}'" );
		$chart->setXaxis( array('type'=>$dateformat, 'labels'=>$label, 'tickInterval'=>$tickInterval) );
		$chart->setYaxis( array('min'=>0, 'title'=>array('text'=>"'{$yaxis}'"), 'allowDecimals'=>0) );
		$chart->setTooltip( array('formatter'=> array( $tooltipd, $tooltip ), 'crosshairs'=> '1', 'shared'=> '1') );
		$chart->setPlotoptions('line', $plot );
		$chart->setAjax('ajax', 1);
		return $chart;
	}

	// 设置柱形圖样式
	public function setColumn($div, $title, $categories, $yaxis, $tooltip, $plot = '1') {
		load('model.highcharts');
		$chart = highcharts::getInstance();
		$chart->setChart( array('renderTo'=>"'{$div}'", 'defaultSeriesType'=>"'column'") );
		$chart->setTitle('text', "'{$title}'");
		//$chart->setSubTitle('text', "'{$subtitle}'");
		$chart->setXaxis( array('categories'=>$categories));
		$chart->setYaxis( array('labels'=> '%', 'title'=>'', 'opposite'=>1), array('labels'=>$yaxis[1], 'title'=>'') );
		$chart->setTooltip( array($yaxis[0], $yaxis[1]), array('增幅','%'));
		$chart->setPlotoptions('column', $plot);
		$chart->setAjax('ajax', 1);
		return $chart;
	}

	// 設置比例樣式
	public function setPie($title, $div = 'pie', $subtitle = '') {
		load('model.highcharts');
		$chart = highcharts::getInstance();
		$chart->setChart( array('renderTo'=>"'{$div}'", 'defaultSeriesType'=>"'pie'") );
		$chart->setTitle('text', "''");
		$chart->setSubTitle('text', "''");
		$chart->setPieLegend(1);
		$chart->setPlotoptions('pie', '%');
		$chart->setTooltip( array('formatter'=>'%') );
		$chart->setAjax('ajax', 1);
		return $chart;
	}

	// 初始化本月開始結束時間
	public function setInitTime() {
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$this->assign('bef_time', $bef_time);
		$this->assign('ent_time', $ent_time);
	}

	/**
	 * 渠道註冊人數走勢
	 */
	public function channelRegist() {
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getChannelRegist" 		:
					$this->getChannelRegist();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 渠道註冊人數比例
	 */
	public function channelRegistPie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		$this->assign('games', $game_row);
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getRegistPie" 	:
					$this->getRegistPie();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 游戏充值比例
	 */
	public function channelGamePie() {
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getGamePie" 	:
					$this->getGamePie();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 渠道充值金額走勢
	 */
	public function channelRecharge() {
		$this->checkLogin();

		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		$this->assign('games', $game_row);
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case 'getChannelRecharge' 		:
					$this->getChannelRecharge();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 渠道充值金額比例
	 */
	public function channelRechargePie() {
		$this->checkLogin();

		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		$this->assign('games', $game_row);

		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case 'getRechargePie':
					$this->getRechargePie();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 遊戲充值金額比例
	 */
	public function gameRechargePie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		$this->assign('games', $game_row);
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case 'getGameRechargePie' 		:
					$this->getGameRechargePie();
					break;
			}
		}
		$this->setInitTime();
	}

	// 渠道ARPU
	public function channelArpu() {
		@session_start();
		if (!empty($_REQUEST['channel'])) {
			$_SESSION['qchannel'] = $_REQUEST['channel'];
		} else {
			$_SESSION['qchannel'] = "0";
		}
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		$this->assign('games', $game_row);

		$this->checkLogin();
		$total = $times = $person = 0;
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date'])) && ($_REQUEST['start_date'] != 'NULL')) 	? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 		? $_REQUEST['end_date'] 	: $ent_time;
		$this->assign('start_date', $start);
		$this->assign('end_date', 	$end);
		$game = $_REQUEST['game'];
		$channel = $_REQUEST['channel'];
		if($game){
			$where = " AND `game` = '". $game ."'";
		}
		if($channel){
			$where .= " AND `channel` = '". $channel ."'";
		}
		$sql = "SELECT `channel` AS `channel`, SUM(`omoney`) AS `totalPrice`, `roleid`, COUNT(`oid`) AS `times`,
					   COUNT( DISTINCT `ousername`) AS `persons`, ROUND(SUM(`omoney`)/COUNT( DISTINCT `ousername`),2) AS `ARPU`
				FROM `ms_agent_orders`
				WHERE `ostatus` = 1 ". $where ." AND `otime` BETWEEN '".strtotime($start)."' AND '".strtotime($end.' '.'23:59:59') ."'
                                    GROUP BY  `channel`";
		$users = model::getBySql($sql);
		$channel_model = getInstance('model.sdkChannel.channel');
		foreach ($users as $key => $value) {
			$channel_name = $channel_model->returnAgentname($value['channel']);
			$users[$key]['channle_name'] = $channel_name[0]['channel'];
		}

		if (is_array($users) && count($users)) {
			$num = count($users);
			for ($i=0; $i<$num; $i++){
				$users[$i]['channelName'] = $users[$i]['channel'];//channel::get_user_type();
			}
			foreach ($users as $key=> $val) {
				$data[] = array(
				'qd'			=> $val['channle_name'] ."(". $val['channelName'] .")",
				'totalPrice'	=> intval($val['totalPrice']),
				'times'			=> $val['times'],
				'persons'		=> $val['persons'],
				'ARPU'			=> $val['ARPU']
				);
				$total 	= $total + intval($val['totalPrice']);
				$times 	= $times + $val['times'];
				$person	= $person+ $val['persons'];
			}
		}

		// 渠道的充值总额、充值次数、充值人数、ARPU值
		$channelData 	= $data;
		$this->assign('channelData', $channelData);
		// 各个渠道的充值总额、充值次数、充值人数、ARPU值
		$this->assign('total', $total);
		$this->assign('times', $times);
		$this->assign('person', $person);
	}

	// 平台註冊人數走勢
	public function platformRegist() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		$this->assign('games', $game_row);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getPlatformRegist" 		:
					$this->getPlatformRegist();
					break;
			}
		}
		$this->setInitTime();
	}

	// 平台充值金額走勢
	public function platformRecharge() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		$this->assign('games', $game_row);
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getPlatformRecharge" 		:
					$this->getPlatformRecharge();
					break;
			}
		}
		$this->setInitTime();
	}

	//新增某日导入所有玩家后续充值走势图
	public function platformDayRecharge() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		$this->assign('games', $game_row);
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getPlatformDayRecharge":
					$this->getPlatformDayRecharge();
					break;
			}
		}
		$this->setInitTime();
	}

	public function platformRechargePeopleNumber() {
		$this->checkLogin();

		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		$this->assign('games', $game_row);

		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getChannelRechargePeopleNumber" 		:
					$this->getChannelRechargePeopleNumber();
					break;
			}
		}
		$this->setInitTime();
	}


	// 遊戲充值金額走勢
	public function gameRecharge() {
		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getGameRecharge" 		:
					$this->getGameRecharge();
					break;
			}
		}
		$this->setInitTime();
	}

	// 獲取註冊人數、充值金额數據
	public function getChannel($type, $title, $category) {
		$this->checkLogin();
		header('Content-Type: application/json');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;
		switch ($type) {
			//　渠道
			case 'channel' :
				load('model.channel');
				//!empty($_REQUEST['qd']) && $_REQUEST['qd'] != 'NULL' ? $channelName = $_REQUEST['qd'] : '';
				//$notSql = channel::get_notLike_sql(' AND m.`userid` NOT LIKE "', '%" ');
				//$channelSql	= !empty($channelName) ? ($channelName == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channelName'") : '';
				$game = trim($_REQUEST['game']);
				if($game){
					$channelSql .= " AND game = '". $game ."'";
				}
				$groupSql   = "";
				break;
				//　平台
			case 'platform':
				$game = trim($_REQUEST['game']);
				if($game){
					$channelSql .= " AND game = '". $game ."'";
				}
				$groupSql   = '';
				break;
				//新增某日导入所有玩家后续充值走势图
			case 'platformDay':
				$game = trim($_REQUEST['game']);
				$channel = trim($_REQUEST['channel']);
				if($game){
					$channelSql .= " AND m.`game` = '". $game ."'";
				}
				if($channel){
					$channelSql .= " AND m.`channel` = '". $channel ."'";
				}
				$groupSql   = '';
				break;
		}
		$date  	= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) <= 1	? 	'%Y-%m-%d-%H' 	: '%Y-%m-%d';
		$pdate	= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) <= 1	? 	'Y-m-d-H' 		: 'Y-m-d';
		if (substr($start, 5) == '01-01' && substr($end, 5) == '12-31' && (substr($start, 0, 4) == substr($end, 0 ,4)) && $channelSql) {
			$date   = '%Y-%m';
		}
		$hourSign  = '';
		if ($start == $end) {
			$gDate = '%Y-%m-%d %H';
			$hourSign  = ':00';
		}
		else {
			$gDate = '%Y-%m-%d';
		}
		switch ($category) {
			// 充值金額
			case 'recharge' :
				$sql 	 = "SELECT `channel` AS `channel`, SUM(`omoney`) AS `total`,
                                            FROM_UNIXTIME(`otime`, '%c') AS `month`,
                                            `roleid`, `otime` AS `date`,
                                            FROM_UNIXTIME(`otime`, '$gDate') AS `day`
                                            FROM `ms_agent_orders`
                                            WHERE `ostatus` = 1
                                            AND `otime` BETWEEN ".strtotime($start)." AND ".strtotime($end.' '.'23:59:59').'
                                            '.$channelSql
				." GROUP BY ".$groupSql." FROM_UNIXTIME(`otime`,'$date')
                                            ORDER BY `otime`";
				break;
				//新增某日导入所有玩家后续充值走势图
			case 'rechargeDay' :
				$sql 	 = "SELECT SUM(o.`omoney`) AS total,
                                            FROM_UNIXTIME(o.`otime`, '%c') AS `month`,
                                             o.`otime` AS `date`,
                                            FROM_UNIXTIME(o.`otime`, '$gDate') AS `day`
                                            FROM `ms_agent_orders` o
                                            LEFT JOIN `ms_members` m ON m.`userid` = o.`ousername`
                                            WHERE o.`ostatus` =1
                                            AND m.`jointime` >=".strtotime($start)." AND m.`jointime` <= ".strtotime($end.' '.'23:59:59').'
                                            '. $channelSql ."  GROUP BY FROM_UNIXTIME(o.`otime`,'$date')";
				break;
				// 註冊人數
			case 'regist' :
				$game	= (!empty($_REQUEST['game'])) ? " AND game = '" . trim($_REQUEST['game']) . "'" : "";
				$sql 	= "SELECT `channel` AS `channel`, `userid`, `jointime` AS `date`, FROM_UNIXTIME(`jointime`, '%Y') AS `year`,FROM_UNIXTIME(`jointime`, '%c') AS `month`, FROM_UNIXTIME(`jointime`, '$gDate') AS `day`, COUNT(*) AS `total`
							FROM `ms_members`
							WHERE `jointime` BETWEEN ". strtotime($start) . ' AND '. strtotime($end.' '.'23:59:59') . $game .' '.$channelSql
				." GROUP BY ".$groupSql." FROM_UNIXTIME(`jointime`,'$date')
				  	 	 	ORDER BY `jointime`";
				break;
				//充值人数
			case 'people_number':
				$sql	= " SELECT count(*) AS total, s.otime AS date, FROM_UNIXTIME(s.otime, '$gDate') AS day
                                        FROM ( SELECT *, FROM_UNIXTIME(otime, '$date') as ftime
                                                    FROM `ms_agent_orders`
                                                    WHERE `ostatus` =1 AND `otime` >=". strtotime($start) .'
                                                    AND `otime`<='. strtotime($end.' '.'23:59:59').' '.$channelSql
				." GROUP BY ".$groupSql." ftime, ousername) s
                                                    GROUP BY ftime;
						";
				break;
			default:break;
		}
		$users  = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			$dateType	= 'datetime';
			$dateFormat = '%d';
			$dateLength = 3600 * 1000 * 1 * 24 * 1;
			$dateTip	= '%Y-%m-%d';
			$diff = intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1 / (3600 * 24));

			switch ($type) {
				case 'channel' :
					$num = count($users);
					for ($i=0; $i<$num; $i++){
						$users[$i]['channelName'] = $users[$i]['channel'];//channel::get_user_type($users[$i]['channel']);
					}
					if ((substr($start, 0, 4) == substr($end, 0 ,4)) && substr($start, 5) == '01-01' && substr($end, 5) == '12-31' && $channelSql) {
						if ($category == 'recharge') {
							$yName = array('金额', '元');
						}
						else {
							$yName = array('注册人数', '人');
						}
						$yearMonth  = "'一月' ,'二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'";
						$chart		= $this->setColumn($type, channel::$names[$channelName], '['.$yearMonth.']', $yName, '');
						foreach ($users as $key=> $val) {
							$data[$val['channelName']][$val['month']] = intval($val['total']);
						}
						foreach ($data as $key=> $val) {
							for ($i = 1; $i <= 12; $i++) {
								if (array_key_exists($i, $val)) {
									$newData[] = $val[$i];
								}
								else {
									$newData[] = 0;
								}
							}
						}
						/*
						* @TODO
						* 一月份增長率應於上一年12月份做比較，這裡取0
						* $range[0] = 0;
						*/
						$range[0] = 0;
						for ($i = 2; $i <= 12; $i++) {
							if (array_key_exists($i, $data[channel::$names[$channelName]])) {
								if ($data[channel::$names[$channelName]][$i-1]) {
									if (($data[channel::$names[$channelName]][$i]-$data[channel::$names[$channelName]][$i-1]) < 0 ) {
										$val = -($data[channel::$names[$channelName]][$i]-$data[channel::$names[$channelName]][$i-1]);
									}
									else {
										$val = $data[channel::$names[$channelName]][$i]-$data[channel::$names[$channelName]][$i-1];
									}
									$range[$i-1] = (float)sprintf("%.2f", ($val)*100/($data[channel::$names[$channelName]][$i-1]));
								}
								else {
									$range[$i-1] = 0;
								}
							}
							else {
								$range[$i-1] = 0;
							}
						}
						unset($data);
						$data[$yName[0]] = array (
						'column' => $newData,
						);

						$data['增幅'] = array(
						'spline' => $range
						);
						$json['temp'] = 2;	// 圖表多維標誌
					}
					else {
						// 图表
						if ($category == 'recharge') {
							$yName = '元';
						}
						else {
							$yName = '人';
						}
						// 顯示圖表
						if (empty($_GET['p'])) {
							$chart 	= $this->setLine($type, '', '', $dateType, $dateFormat, $dateLength, $title, $dateTip, $yName);
							foreach ($users as $key=> $val) {
								$data[$val['channelName']][] = array(strtotime(date($pdate, $val['date'])) * 1000, intval($val['total']));
							}
							foreach (channel::$names as $value) {
								if (count($data[$value]) > 0) {
									$newData[$value] = $data[$value];
								}
							}
							unset($data);
							$data = $newData;
							!empty($_REQUEST['game']) && $_REQUEST['game'] != 'NULL' ? $chart->setPlotoptions('line', '1') : '';
							$chart->setTimeLength($diff, $chart);
						}

						// 数字表格
						load('model.page');
						foreach ($users as $k=> $v) {
							$tData[$v['day'].$hourSign][$v['channelName']] = $v['total'];
							$tData[$v['day'].$hourSign]['total'] = $tData[$v['day'].$hourSign]['total'] + $v['total'];
							$tData['统计'][$v['channelName']] = $tData['统计'][$v['channelName']] + $v['total'];
							$tData['统计']['total'] = $tData['统计']['total'] + $v['total'];
						}
						$tLastData = $tData['统计'];
						unset($tData['统计']);
						ksort($tData);
						$tData['统计'] = $tLastData;
						$page = new page(count($tData), 30);
						$pageData = array_slice($tData, $page->firstRow, $page->listRows);
						$pageStr = $page->show();
						foreach (channel::$names as $val) {
							$table .= "<th>$val</th>";
						}
						$table .= "<th>小计</th>";
						$th = "<thead><tr><th>日期/渠道</th>".$table."</tr></thead>";
						foreach ($pageData as $key=>$value) {
							$tr .= "<tr><td>$key</td>";
							foreach (channel::$names as $val) {
								if (array_key_exists($val, $pageData[$key])) {
									$tr .= "<td>$value[$val]</td>";
								}
								else {
									$tr .= "<td>0</td>";
								}
							}
							$tr .= "<td>$value[total]</td>";
							$tr .= "</tr>";
						}
						$str = $th.'<tbody>'.$tr.'</tbody>';
						$json['temp']	= 1;
					}
					break;
				case 'platform':
					if ($category == 'recharge') {
						$yName = '元';
						$tName = '金额';
					}
					else {
						$yName = '人';
						$tName = '人数';
					}
					// 顯示圖表
					if (empty($_GET['p'])) {
						$chart 	= $this->setLine($type, '', '', $dateType, $dateFormat, $dateLength, $title, $dateTip, $yName);
						foreach ($users as $key=> $val) {
							$data[$tName][] = array(strtotime(date($pdate,$val['date']))*1000, intval($val['total']));
						}
						$chart->setPlotoptions('line', '1');
						$chart->setTimeLength($diff, $chart);

					}
					// 数字表格
					load('model.page');
					foreach ($users as $k=> $v) {
						$tData[$v['day'].$hourSign] = $v['total'];
						$tData['统计'] = $tData['统计'] + $v['total'];
					}
					$tLastData = $tData['统计'];
					unset($tData['统计']);
					ksort($tData);
					$tData['统计'] = $tLastData;
					$page = new page(count($tData), 30);
					$pageData = array_slice($tData, $page->firstRow, $page->listRows);
					$pageStr = $page->show();
					$table .= "<th style='width:50%'>$tName</th>";
					$th = "<thead><tr><th style='width:50%'>日期</th>".$table."</tr></thead>";
					foreach ($pageData as $key=>$value) {
						$tr .= "<tr><td>$key</td><td>$value</td></tr>";
					}
					$str = $th.'<tbody>'.$tr.'</tbody>';
					$json['temp']	= 1;
					break;
				case 'platformDay':
					if ($category == 'rechargeDay') {
						$yName = '元';
						$tName = '金额';
					}
					else {
						$yName = '人';
						$tName = '人数';
					}
					// 顯示圖表
					if (empty($_GET['p'])) {
						$chart 	= $this->setLine($type, '', '', $dateType, $dateFormat, $dateLength, $title, $dateTip, $yName);
						foreach ($users as $key=> $val) {
							$data[$tName][] = array($val['date']*1000, intval($val['total']));
						}
						$chart->setPlotoptions('line', '1');
						$chart->setTimeLength($diff, $chart);

					}
					// 数字表格
					load('model.page');
					foreach ($users as $k=> $v) {
						$tData[$v['day'].$hourSign] = $v['total'];
						$tData['统计'] = $tData['统计'] + $v['total'];
					}
					$tLastData = $tData['统计'];
					unset($tData['统计']);
					ksort($tData);
					$tData['统计'] = $tLastData;
					$page = new page(count($tData), 30);
					$pageData = array_slice($tData, $page->firstRow, $page->listRows);
					$pageStr = $page->show();
					$table .= "<th style='width:50%'>$tName</th>";
					$th = "<thead><tr><th style='width:50%'>日期</th>".$table."</tr></thead>";
					foreach ($pageData as $key=>$value) {
						$tr .= "<tr><td>$key</td><td>$value</td></tr>";
					}
					$str = $th.'<tbody>'.$tr.'</tbody>';
					$json['temp']	= 1;
					break;
				default:break;
			}
			$json['table']  = $str;
			$json['pageStr']= $pageStr;
			if (empty($_GET['p'])) {
				$json['str'] 	= $chart->toString();
				$json['pageSign'] = 0;
				$json['data']   = $data;
			}
			else {
				$json['pageSign'] = 1;
			}
		}
		else {
			$json['temp'] = 0;
		}
		echo json_encode($json);
		exit(0);
	}

	// 獲取註冊人數數據
	public function getRegist($type) {
		$title		= '人数';
		$category   = 'regist';
		$this->getChannel($type, $title, $category);
	}
	// 獲取渠道註冊人數數據
	public function getChannelRegist() {
		$this->checkLogin();
		$this->getRegist('channel');
	}

	// 獲取平台註冊人數數據
	public function getplatformRegist() {
		$this->checkLogin();
		$this->getRegist('platform');
	}

	// 獲取充值金額數據
	public function getRecharge($type) {
		$title		= '充值金额';
		$category   = 'recharge';
		$this->getChannel($type, $title, $category);
	}

	//获取新增某日导如所有玩家后续充值走势图
	public function getRechargeDay($type) {
		$title		= '充值金额';
		$category   = 'rechargeDay';
		$this->getChannel($type, $title, $category);
	}

	// 獲取渠道充值金額數據
	public function getChannelRecharge() {
		$this->checkLogin();
		$this->getRecharge('channel');
	}

	// 獲取平臺充值人數數據
	public function getChannelRechargePeopleNumber() {
		$this->checkLogin();
		$this->getChannel('platform', '充值人数', 'people_number');
	}

	// 獲取平台充值金額數據
	public function getPlatformRecharge() {
		$this->checkLogin();
		$this->getRecharge('platform');
	}

	//获取新增某日导如入有玩家后续充值走势图
	public function getPlatformDayRecharge() {
		$this->checkLogin();
		$this->getRechargeDay('platformDay');
	}

	// 獲取遊戲充值金額數據
	public function getGameRecharge() {
		$this->checkLogin();
		header('Content-Type: application/json');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d');
		$gameStr = (isset($_REQUEST['alias']) 		&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';
		if ($gameStr) {
			$channel= explode(',', $gameStr);
			foreach ($channel as $key=>$val) {
				$gameName.= "'$val'".',';
			}
			$gameName = substr($gameName, 0, -1);
		}
		else {
			$gameName = '';
		}
		!empty($_REQUEST['game']) && $_REQUEST['game'] != 'NULL' ? $gameName = $_REQUEST['game'] : '';
		$gameSql	= !empty($gameName) ? " AND o.`gameid` = $gameName " : '';
		$date  		= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) <= 1	? 	'%Y-%m-%d %H:00' 	: '%Y-%m-%d';
		if (substr($start, 5) == '01-01' && substr($end, 5) == '12-31' && $gameName && (substr($start, 0, 4) == substr($end, 0 ,4))) {
			$date   = '%Y-%m';
		}
		$pdate		= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) <= 1	? 	'Y-m-d H:00' 		: 'Y-m-d';
		$hourSign  = '';
		if ($start == $end) {
			$gDate = '%Y-%m-%d %H';
			$hourSign  = ':00';
		}
		else {
			$gDate = '%Y-%m-%d';
		}
		$sql = 'SELECT  o.`gameid`, g.`ename` AS `game`, o.`time` AS `date`, SUM(o.`money`) AS `totalPrice`, o.`money`, FROM_UNIXTIME(o.`time`, "%c") AS `month`, FROM_UNIXTIME(o.`time`, "'.$gDate.'") AS `day`
				FROM `ms_all_orders` o JOIN `blk_games_inter` g ON o.`gameid` = g.`evalue`
				WHERE o.`status` = 1 AND o.`gameid` <> 1 AND g.`parent_id` = 0 AND o.`time` BETWEEN '.strtotime($start).' AND '.strtotime($end.' '.'23:59:59').' '.$gameSql
		.' GROUP BY o.`gameid`, FROM_UNIXTIME(o.`time`, "'.$date.'")';
		$payment = model::getBySql($sql);
		if (is_array($payment) && count($payment)) {
			$dateType	= 'datetime';
			$dateFormat = '%d';
			$dateLength = 3600*1000*1*24;
			$dateTip	= '%Y-%m-%d';
			$diff = intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24));

			if ((substr($start, 0, 4) == substr($end, 0 ,4)) && substr($start, 5) == '01-01' && substr($end, 5) == '12-31' && $gameName) {
				$yearMonth  = "'一月' ,'二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'";
				foreach ($payment as $key=> $val) {
					$wGame = $val['game'];
					$data[$val['game']][$val['month']] = intval($val['totalPrice']);
				}
				$chart		= $this->setColumn('game', $wGame, '['.$yearMonth.']', array('金额', '元'), '');
				foreach ($data as $key=> $val) {
					for ($i = 1; $i <= 12; $i++) {
						if (array_key_exists($i, $val)) {
							$newData[] = $val[$i];
						}
						else {
							$newData[] = 0;
						}
					}
				}
				/*
				* @TODO
				* 一月份增長率應於上一年12月份做比較，這裡取0
				*/
				$range[0] = 0;
				for ($i = 2; $i <= 12; $i++) {
					if (array_key_exists($i, $data[$wGame])) {
						if ($data[$wGame][$i-1]) {
							if ($data[$wGame][$i]-$data[$wGame][$i-1] < 0 ) {
								$val = -($data[$wGame][$i]-$data[$wGame][$i-1]);
							}
							else {
								$val = $data[$wGame][$i]-$data[$wGame][$i-1];
							}
							$range[$i-1] = (float)sprintf("%.2f", ($val)*100/($data[$wGame][$i-1]));
						}
						else {
							$range[$i-1] = 0;
						}
					}
					else {
						$range[$i-1] = 0;
					}
				}
				unset($data);
				$data['金额'] = array (
				'column' => $newData,
				);

				$data['增幅'] = array(
				'spline' => $range
				);
				$json['temp'] = 2;	// 圖表多維標誌
			}
			else {
				// 圖表
				if (empty($_GET['p'])) {
					$chart 		= $this->setLine('game', '', '', $dateType, $dateFormat, $dateLength, '充值金额', $dateTip, '台幣');
					$chart->setPlotoptions('line', '1');
					foreach ($payment as $key=> $val) {
						$data[$val['game']][] = array(strtotime(date($pdate,$val['date']))*1000, intval($val['totalPrice']));
					}
					if ($diff == 1 || (count($data[$_REQUEST['userid']]) == 1)) {
						$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%H', 'tickInterval'=>3600 * 1000 * 1) );
						$chart->setTooltip( array('formatter'=> array( '%Y-%m-%d,%H:00', "" )) );
						$chart->setChart('defaultSeriesType',"'line'");
						$chart->setPlotoptions('line', '1');
						//$chart->setTimeLength($diff, $chart);
					}
					else {
						$chart->setTimeLength($diff, $chart);
					}
				}

				// 数字表格
				load('model.page');
				foreach ($payment as $k=> $v) {
					$tData[$v['day'].$hourSign][$v['game']] = $v['totalPrice'];
					$tData[$v['day'].$hourSign]['total'] = $tData[$v['day'].$hourSign]['total'] + $v['totalPrice'];
					$tData['统计'][$v['game']] = $tData['统计'][$v['game']] + $v['totalPrice'];
					$tData['统计']['total'] = $tData['统计']['total'] + $v['totalPrice'];
				}
				$tLastData = $tData['统计'];
				unset($tData['统计']);
				ksort($tData);
				$tData['统计'] = $tLastData;
				$page = new page(count($tData), 30);
				$pageData = array_slice($tData, $page->firstRow, $page->listRows);
				$pageStr = $page->show();
				$allGame = model::getBySql("SELECT `ename` FROM `blk_games_inter` WHERE `parent_id` = 0");
				foreach ($allGame as $value) {
					$table .= "<th>$value[ename]</th>";
				}
				$table .= "<th>小计</th>";
				$th = "<thead><tr><th>日期/游戏</th>".$table."</tr></thead>";
				foreach ($pageData as $key=>$value) {
					$tr .= "<tr><td>$key</td>";
					foreach ($allGame as $val) {
						if (array_key_exists($val['ename'], $pageData[$key])) {
							$tr .= "<td>".$value[$val['ename']]."</td>";
						}
						else {
							$tr .= "<td>0</td>";
						}
					}

					$tr .= "<td>$value[total]</td>";
					$tr .= "</tr>";
				}
				$str = $th.'<tbody>'.$tr.'</tbody>';
				$json['temp'] = 1;
				// !empty($_REQUEST['game']) && $_REQUEST['game'] != 'NULL' ? 	$chart->setPlotoptions('line', '1') : '';
			}
			$json['table']  = $str;
			$json['pageStr']= $pageStr;
			if (empty($_GET['p'])) {
				$json['str'] 	= $chart->toString();
				$json['pageSign'] = 0;
				$json['data']   = $data;
			}
			else {
				$json['pageSign'] = 1;
			}
		}
		else {
			$json['temp'] = 0;
		}
		echo json_encode($json);
		exit(0);
	}

	// 獲取註冊人數、充值金額比例數據
	public function getProportion($type, $div) {
		$this->checkLogin();
		header('Content-Type: application/json');
		$channel_model = getInstance('model.sdkChannel.channel');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());
		$where = " 1 ";
		$game = $_REQUEST['game'];
		$channel = $_REQUEST['channel'];
		if($game){
			$where .= " AND game= '" . $game ."'";
		}
		switch ($type) {
			case 'regist' :
				$sql = "SELECT `channel`, COUNT(`mid`) AS `total`
						FROM `ms_members`
						WHERE ". $where ." AND `jointime` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')
				." GROUP BY channel
					     ORDER BY COUNT(`mid`)";
				break;
			case 'recharge' :
				$sql = "SELECT `channel` AS `channel`, SUM(`omoney`) AS `total`
						FROM `ms_agent_orders`
						WHERE ". $where ." AND `ostatus` = 1 AND `otime` > ".strtotime($start)." AND `otime` < ".strtotime($end.' '.'23:59:59')
				." GROUP BY `channel`
					      ORDER BY SUM(`omoney`)";
				break;
			case 'gameRecharge' :
				$game = $_REQUEST['game'];
				$where = "";
				if($game){
					$where = " AND `game` = '". $game ."'";
				}
				$sql = "SELECT `game`, `channel` AS `typeName`, SUM(`omoney`) AS `total`
						FROM `ms_agent_orders`
						WHERE `ostatus` = 1 ". $where ." AND `otime` > ".strtotime($start)." AND `otime` < ".strtotime($end.' '.'23:59:59')
				." GROUP BY `game`
					      ORDER BY SUM(`omoney`)";
				break;
			default:break;
		}
		$users = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			$sum = 0;
			if ($type != 'gameRecharge') {
				$num = count($users);
				for ($i=0; $i<$num; $i++){
					$typeName = $channel_model->returnAgentname($users[$i]['channel']);
					foreach($typeName as $key => $value){
						$users[$i]['channelName'] =  $value['channel'];
					}
					$users[$i]['typeName'] = $users[$i]['channelName'] ."(". $users[$i]['channel'] .")" ;
				}

			}
			foreach($users as $key=> $val){
				if($val['game']){
					$typeName = $channel_model->returnGameName($val['game']);
					foreach($typeName as $key => $value){
						$users[$key]['gameName'] =  $value['name'];
					}
				}
				if(isset($data[$val['typeName']])) {
					$data[$val['typeName']][1] += intval($val['total']);
				}
				$data[$val['typeName']] = array( $value['name'] . " " . $val['typeName'], intval($val['total']));
				$sum = $sum + intval($val['total']);
			}

			foreach($data as $key=>$val){
				$pp[] 	= array($val[0], (intval($val[1])/$sum)*100);
			}
			$temp 	= 1;
			$chart 	= $this->setPie('', $div);
			$json['str'] = $chart->toString();
			$json['data'] = array($pp);
		}
		else {
			$temp = 0;
		}
		$json['temp'] = $temp;
		echo json_encode($json);
		exit(0);
	}
	// 獲取渠道註冊人數比例數據
	public function getRegistPie() {
		$this->checkLogin();
		$this->getProportion('regist', 'channelpie');
	}

	// 獲取渠道充值金額比例數據
	public function getRechargePie() {
		$this->checkLogin();
		$this->getProportion('recharge', 'rechargepie');
	}

	// 獲取遊戲充值金額比例數據
	public function getGameRechargePie() {
		$this->checkLogin();
		$this->getProportion('gameRecharge', 'gamerechargepie');
	}

	/**
     * 显示玩家游戏生命周期走势
     */
	public function playerOnlineReporting() {
		$online_model = new Model('ms_player_online_reporting');
		if(!$_GET['ajax']) {
			//取出所有游戏
			$game_row = $online_model->getBySql("SELECT DISTINCT game"
			. " FROM ms_player_online_reporting WHERE game!='' AND game IS NOT NULL");
			$game = array();
			foreach($game_row as $row) {
				$game[] = $row['game'];
			}
			$this->assign('game', $game);
		} else {
			//计算时间范围
			$start_date = mysql_real_escape_string($_REQUEST['start_date']);
			$end_date = mysql_real_escape_string($_REQUEST['end_date']);
			$subsql = '';
			if(!empty($start_date)) {
				$subsql .= " AND '{$start_date}' <= FROM_UNIXTIME(start_time, '%Y-%m-%d')";
			}
			if(!empty($end_date)) {
				$subsql .= " AND FROM_UNIXTIME(start_time, '%Y-%m-%d') <= '{$end_date}'";
			}
			//游戏
			if(!empty($_REQUEST['game'])) {
				$game = mysql_real_escape_string($_REQUEST['game']);
				$subsql .= " AND FIND_IN_SET(game, '$game')";
			}

			//设置sql语句
			$format_start_time = "FROM_UNIXTIME(start_time, '%Y-%m-%d')";
			$sql = "SELECT COUNT(DISTINCT userid) as user_total"
			. ",SUM(online_time) as time_total"
			. ",{$format_start_time} as date"
			. ",game"
			. " FROM `ms_player_online_reporting`"
			. " WHERE 1 $subsql"
			. " GROUP BY date, game";
			$rows = $online_model->getBySql($sql . ' ORDER BY date ASC');

			//使用一个变量保存坐标数组
			$data = array();
			foreach($rows as $row) {
				//每一条数据线表示一个游戏
				//所以使用游戏名作为标识符，要检查并初始化它
				if(!isset($data[ $row['game'] ])) {
					$data[ $row['game'] ] = array();
				}
				$data_game = &$data[ $row['game'] ];
				$data_game[] = array(
				//x坐标
				strtotime($row['date']) * 1000,
				//y坐标
				ceil($row['time_total'] / $row['user_total'] / 60)
				);
			}

			//以类似的方式得到表格数据
			$rows = $online_model->getBySql($sql . ' ORDER BY date DESC');
			$table_data = array();
			foreach($rows as $row) {
				if(!isset($table_data[ $row['date'] ])) {
					$table_data[ $row['date'] ] = array();
				}
				$data_date = &$table_data[ $row['date'] ];
				$data_date[] = array(
				'game' => $row['game'],
				'time_total' => ceil($row['time_total'] / 3600),
				'user_total' => $row['user_total']
				);
			}
			$table = '<thead><tr><td>日期</td><td>游戏</td><td>总在线时长</td><td>总在线人数</td></tr></thead>';
			foreach($table_data as $date => $rows) {
				$count = count($rows);
				$first_row = array_shift($rows);
				$table .= "<tr>"
				. "<td rowspan='{$count}'>{$date}</td>"
				. "<td>{$first_row['game']}</td>"
				. "<td>{$first_row['time_total']}小时</td>"
				. "<td>{$first_row['user_total']}人</td>"
				. "</tr>";
				if($count > 1) {
					foreach($rows as $row) {
						$table .= "<tr>"
						. "<td>{$row['game']}</td>"
						. "<td>{$row['time_total']}小时</td>"
						. "<td>{$row['user_total']}人</td>"
						. "</tr>";
					}
				}
			}

			//输出图表数据
			$dateType	= 'datetime';
			$dateFormat = '%d';
			$dateLength = 3600 * 1000 * 1 * 24 * 1;
			$dateTip	= '%Y-%m-%d';
			$chart 	= $this->setLine('channel', '', '', $dateType, $dateFormat, $dateLength, '平均在线市场', $dateTip, '分钟');
			echo json_encode(array(
			'data' => $data,
			'pageSign' => 0,
			'str' => $chart->toString(),
			'temp' => count($data) > 0 ? 1 : 0,
			'table' => $table
			));
			exit;
		}
	}
}