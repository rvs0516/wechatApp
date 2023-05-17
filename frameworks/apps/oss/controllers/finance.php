<?php
require_once APP_CONTROLLER_PATH . '/master.php';

class financeController extends masterControl {
	
	/**
	 * 利润详情
	 * 
	 */
	public function profit(){
		$statistics_model = getInstance('model.finance');
		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$start_date = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : "";
		$end_date = $_REQUEST['end_date'] ? $_REQUEST['end_date'] : "";
		$apkNum = $_REQUEST['apkNum'] ? $_REQUEST['apkNum'] : "";
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : "";
		$source = $_REQUEST['source'] ? $_REQUEST['source'] : "";
		$status = $_REQUEST['status'] ? $_REQUEST['status'] : "1";
		
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		// 获取当前角色组关联游戏
		$gameStr = '';
		if ($gid == 17){
			if ($_REQUEST['game']) {
				$game = trim($_REQUEST['game']);
			}else {
				if ($gidarr[0]['game'] != 'all') {
					$explode = explode('|', $gidarr[0]['game']);
					foreach ($explode as $k => $v) {
						$gameStr .= "'" . $v . "',";
					}
					$gameStr = substr($gameStr,0,-1);
					$this->assign('gameStr', $gameStr);
				}
			}
			
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		//用户权限区分
		$checkRoot = '';
		$uid_list1 = array('luojiang', 'root', 'guojian', 'yangzhenwei', 'chenjh', 'caiwu', 'heyongzhen');
		$uid_list2 = array();
		$uid_list3 = array('wangyinping');
		if (in_array($this->_uid, $uid_list1) || $gid == 17) {
			$checkRoot = 1;
		}elseif (in_array($this->_uid, $uid_list2)) {
			$checkRoot = 2;
		}elseif (in_array($this->_uid, $uid_list3)) {
			$checkRoot = 3;
		}elseif ($gid == 15) {
			$checkRoot = 4;
		}
		$this->assign('checkRoot', $checkRoot);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('apkNum', $apkNum);
		$this->assign('type', $type);
		$this->assign('source', $source);
		$this->assign('status', $status);

		//考虑服务器性能损耗，一次导出最多导出20000条
		if($_POST['operation'] === 'report') {
			$page = 1;
			$row = 20000;
			$offset = 0;
		}else {
			//查询数据
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$row = 25;
			$offset = ($page - 1) * $row;
		}
		$this->assign('list_page', $page);
		$this->assign('list_length', $row);

		//获取上级游戏名
		if ($gid == 17 && !empty($gameStr)) {
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else {
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$game_model = getInstance('model.sdkGame.game');
			
			//取得专服游戏数据
			if ($specialName) {

				if ($gid == 17) {
					$specialSummary = $game_model->getGameName($upperName, $specialName, $gameStr);
				}else {
					$specialSummary = $game_model->getGameName($upperName, $specialName);
				}
				
				$specialSum = array();
				foreach ($specialSummary as $key => $value) {
					$specialSum[] = "'" . $value['alias'] . "'";
				}
				$specialString = implode(',', $specialSum);
				$gameStr = $specialString;
			}else {
				if ($gid == 17) {
					$summary = $game_model->getGameName($upperName, '', $gameStr);
				}else {
					$summary = $game_model->getGameName($upperName);
				}
				
				$sum = array();
				foreach ($summary as $key => $value) {
					$sum[] = "'" . $value['alias'] . "'";
				}
				$sumString = implode(',', $sum);
				$gameStr = $sumString;
			}
		}

		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		//只可编辑一个月内的数据
		$stopDate = date("Y-m-d",strtotime("-1 month"));

		// 不同条件对应不同的$refine值  1: 没有选择任何条件 2：选择指定项目 3：选择指定专服  4：选择指定游戏  5：没有选择指定游戏，只选择指定时间
		if (empty($upperName)) {
			$refine = 1;
			if (!empty($start_date) || !empty($end_date) ) {
				$refine = 5;
				$model = 'profit';
			}
		}elseif ($upperName && empty($specialName)) {
			$refine = 2;
		}elseif ($specialName && empty($game)) {
			$refine = 3;
		}elseif ($game) {
			$refine = 4;
		}
		
		// 数据表
		$month_date = date('Ym', strtotime($start_date));
		$dataTable = 'ms_profit_daily_'. $month_date;

		if ($refine != 1) {
			$this->assign('refine', $refine);
			$profitList = $statistics_model->getProfit($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr, $dataTable);
			$total = $statistics_model->getProfitTotal($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr);
			$gamePay = $statistics_model->getGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $channel, $apkNum, 'profit', $gameStr);
			$summary = $statistics_model->getProfitSummary($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr);

			if ($refine == 2) {
				$sumPay = '';
				$gamePayinit =  $gamePay;
				foreach ($profitList as $key => $value) {
					$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
					$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
					foreach ($gamePay as $key1 => $value1) {
						
						if ($value['date'] == $value1['date']) {
							if ($value1['module'] == 2) {
								$profitList[$key]['pay'] = $value1['pay'];
								$profitList[$key]['remark'] = $value1['remark'];
								$gamePay[$key1]['pay'] = '';
							}elseif ($value1['module'] == 3) {
								if ($value['specialName'] == $value1['specialName']) {
									$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];

								}
							}
						}
					}
				$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'], 2);
				$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
					
				}
				
				if ($source != 1) {
					foreach ($gamePayinit as $keys => $values) {
						if ($values['module'] == 2) {
							$sumPay += $values['pay'];
						}elseif ($values['module'] == 3) {
							$sumExPay += $values['pay'];
						}
					}
				}
				foreach ($summary as $k => $v) {
					$summary[$k]['exPay'] = $v['exPay'] + $sumExPay;
					$summary[$k]['final'] = round($v['profit'] - $v['exPay'] - $v['actualPay'] + $v['income'] - $sumPay - $sumExPay, 2);
					$summary[$k]['pay'] = $sumPay;
					$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';

				}
			}elseif ($refine == 3) {
				foreach ($profitList as $key => $value) {
					$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
					$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
					foreach ($gamePay as $key1 => $value1) {
						if ($value['date'] == $value1['date'] && $value1['module'] == 3 && $value['gameAlias'] == $value1['gameAlias']) {
							$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];
						}
					}
					$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'], 2);
					$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
				}
				foreach ($gamePay as $keys => $values) {
					if ($values['module'] == 3) {
						$sumExPay += $values['pay'];
					}
				}
				foreach ($summary as $k => $v) {
					$summary[$k]['exPay'] = $v['exPay'] + $sumExPay;
					$summary[$k]['final'] = round($v['profit'] - $summary[$k]['exPay'] - $v['actualPay'] + $value['income'], 2);
					$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';
				}
			}elseif ($refine == 4) {
				//只可编辑一个月内的数据
				$stopDate = date("Y-m-d",strtotime("-4 month"));
				foreach ($profitList as $key => $value) {
					$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
					$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
					if ($value['date'] >= $stopDate) {
						$profitList[$key]['changeRoot'] = 1;
					}else{
						$profitList[$key]['changeRoot'] = '';
					}
					foreach ($gamePay as $key1 => $value1) {
						if ($value['date'] == $value1['date'] && $value1['module'] == 3 && $value['gameAlias'] == $value1['gameAlias'] && $value['channelId'] == $value1['channelId'] && $value['apkNum'] == $value1['apkNum'] && $value['type'] == $value1['pattern']) {
							$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];
						}
					}
					$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'], 2);
					$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
				}
				foreach ($gamePay as $keys => $values) {
					if ($values['module'] == 3) {
						$sumExPay += $values['pay'];
					}
				}
				foreach ($summary as $k => $v) {
					$summary[$k]['exPay'] = $v['exPay'] + $sumExPay;
					$summary[$k]['final'] = round($v['profit'] - $summary[$k]['exPay'] - $v['actualPay'] + $v['income'], 2);
					$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';
				}
			}elseif ($refine == 5) {
				$sumPay = '';
				foreach ($profitList as $key => $value) {

					// 渠道类别
					if (strpos($value['upperName'], 'T') != 'false') {
						$upperNameArray = explode('-', $value['upperName']);
						$btArray = explode('T', $upperNameArray[0]);
						if ($btArray[1]) {
							$profitList[$key]['channelType'] = 'bt收入';
						}else {
							if ($value['channelId'] == '160068') {
								$profitList[$key]['channelType'] = '2y9y收入';
							}elseif ($value['channelId'] == '500011') {
								$profitList[$key]['channelType'] = '海外收入';
							}elseif ($value['channelId'] == '500028') {
								$profitList[$key]['channelType'] = '大咖玩收入';
							}else {
								$profitList[$key]['channelType'] = '渠道收入';
							} 
						}
					}else {
						if ($value['channelId'] == '160068') {
							$profitList[$key]['channelType'] = '2y9y收入';
						}elseif ($value['channelId'] == '500011') {
							$profitList[$key]['channelType'] = '海外收入';
						}elseif ($value['channelId'] == '500028') {
							$profitList[$key]['channelType'] = '大咖玩收入';
						}else {
							$profitList[$key]['channelType'] = '渠道收入';
						} 
					}

					// 日期
					$profitList[$key]['date'] = date('Ym', strtotime($value['date']. '00:00:00'));

					// 渠道通道费
					if ($value['channelAllowance'] == '-1' || $value['channelAllowance'] == '0' || $value['channelAllowance'] == '') {
						$profitList[$key]['channelAllowance'] = '';
					}elseif (strpos($value['channelAllowance'], ',')) {
						$channelAllowanceArray = explode(',', $value['channelAllowance']);
						if ($channelAllowanceArray[0] == '0') {
							$profitList[$key]['channelAllowance'] = '';
						}else {
							$profitList[$key]['channelAllowance'] = ($value['channelAllowance'] * 100). '%';
						}
					}else {
						$profitList[$key]['channelAllowance'] = ($value['channelAllowance'] * 100). '%';
					}

					// cp通道费
					if ($value['cpAllowance']) {
						$profitList[$key]['cpAllowance'] = ($value['cpAllowance'] * 100). '%';
					}

					$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
					$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
					foreach ($gamePay as $key1 => $value1) {
						if ($value['upperName'] == $value1['upperName']) {
							if ($value1['module'] == 2) {
								$profitList[$key]['pay'] += $value1['pay'];
							}elseif ($value1['module'] == 3) {
								$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];
							}
						}
					}
					$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'] - $profitList[$key]['pay'], 2);
					$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
				}
				if ($source != 1) {
					foreach ($gamePay as $keys => $values) {
						if ($values['module'] == 2) {
							$sumPay += $values['pay'];
						}elseif ($values['module'] == 3) {
							$sumExPay += $values['pay'];
						}
					}
				}
				foreach ($summary as $k => $v) {
					$summary[$k]['pay'] = $sumPay;
					$summary[$k]['exPay'] = $v['exPay'] + $sumExPay;
					$summary[$k]['final'] = round($v['profit'] - $summary[$k]['exPay'] - $v['actualPay'] + $value['income'] - $sumPay, 2);
					$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';
				}
			}

			foreach ($profitList as $keyr => $valuer) {
				if ($checkRoot == 2) {
					$profitList[$keyr]['final'] = $valuer['amount'] - $valuer['cpAmount'] - $valuer['exPay'] - $valuer['actualPay'] + $valuer['income'] - $valuer['gamePay'];
				}elseif ($checkRoot == 3) {
					$profitList[$keyr]['final'] = $valuer['amount'] - $valuer['channelAmount'] - $valuer['exPay'] - $valuer['actualPay'] + $valuer['income'] - $valuer['gamePay'];
				}
			}

			foreach ($summary as $kr => $vr) {
				if ($checkRoot == 2) {
					$summary[$kr]['final'] = $vr['amount'] - $vr['cpAmount'] - $vr['exPay'] - $vr['actualPay'] + $vr['income'] - $vr['pay'];
				}elseif ($checkRoot == 3) {
					$summary[$kr]['final'] = $vr['amount'] - $vr['channelAmount'] - $vr['exPay'] - $vr['actualPay'] + $vr['income'] - $vr['pay'];
				}
			}

			// 导出
			if($_POST['operation'] === 'report') {
				$reports = array();
				foreach ($profitList as $key => $value) {
					$reports[$key]['date'] = $value['date'];
					$reports[$key]['mainPart'] = $value['mainPart'];
					$reports[$key]['cpName'] = $value['cpName'];
					$reports[$key]['upperName'] = $value['upperName'];
					$reports[$key]['specialName'] = $value['specialName'];
					$reports[$key]['name'] = $value['name'];
					$reports[$key]['channelName'] = $value['channelName'];
					$reports[$key]['apkNum'] = $value['apkNum'];
					$reports[$key]['channelType'] = $value['channelType'];
					$reports[$key]['amount'] = $value['amount'];
					$reports[$key]['disAmount'] = $value['disAmount'];
					$reports[$key]['cpRate'] = $value['cpRate'];
					$reports[$key]['cpAllowance'] = $value['cpAllowance'];
					$reports[$key]['cpAmount'] = $value['cpAmount'];
					$reports[$key]['channelRate'] = $value['channelRate'];
					$reports[$key]['channelAllowance'] = $value['channelAllowance'];
					$reports[$key]['channelAmount'] = $value['channelAmount'];
					$reports[$key]['profitMargin'] = $value['profitMargin'];
					$reports[$key]['profit'] = $value['profit'];
				}
				
				// 按项目名称分组
				$projectArray = array();
				foreach ($reports as $key => $value) {
					$projectArray[$value['upperName']][] = $value;
				}

				// 专服名称按字母顺序排序
				$specialNameArray = array();
				foreach ($projectArray as $key => $value) {
					$specialNameArray[$key][] = data_letter_sort($value, 'specialName');
				}

				// 合并数组
				$projectNameArray = array();
				foreach ($specialNameArray as $key => $value) {
					foreach ($value as $k => $v) {
						foreach ($v as $kk => $vv) {
							foreach ($vv as $kkk => $vvv) {
								$projectNameArray[] = $vvv;
							}
						}
					}
				}

				// 项目名称按字母顺序排序
				$projectNameArray = data_letter_sort($projectNameArray, 'upperName');
				
				// 合并数组
				$reportsArray = array();
				foreach ($projectNameArray as $key => $value) {
					foreach ($value as $k => $v) {
						$reportsArray[] = $v;
					}
				}

				$date = date('YmdHis', time());
				excel_export("《爱游就游-中央数据后台》流水分成列表_{$date}", array(
				'账单日期', '上线主体', 'cp名称', '项目', '专服', '游戏', '渠道', '包号', '渠道类别', '总流水', '真实付费流水', '渠道分成比例', '渠道通道费', '渠道分成', 'CP分成比例', 'CP通道费', 'CP分成', '毛利润', '分成后流水', '实际对账流水', '代金卷', '自充', '实际分成', '开票金额', '发票号码', '开票时间', '寄出时间', '快递单号', '已收款', '收款时间', '合同账期', '预计收款时间', '逾期时间', '预警', '已开票发票(未收款)', '未开发票(未收款)', '坏账', ' 回款率'
				), $reportsArray);
				exit;
			}
		}

		$this->assign('list_total', $total);
		$this->assign('page', $page);
		$this->assign('list_length', $row);
		$this->assign('profitList', $profitList);
		$this->assign('summary', $summary);
	}
}