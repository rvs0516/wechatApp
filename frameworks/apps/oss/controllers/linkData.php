<?php
//error_reporting(E_ALL);
require_once APP_CONTROLLER_PATH . '/master.php';

class linkDataController extends masterControl {

	public function linkOrder (){
		$years = trim($_REQUEST['years']) ? trim($_REQUEST['years']) : date("Y");
		$start = trim($_REQUEST['start']) ? strtotime(trim($_REQUEST['start'])) : "";
		$end = trim($_REQUEST['end']) ? strtotime(trim($_REQUEST['end']) . '23:59:59') : "";
		$orderId = trim($_REQUEST['orderId']) ? trim($_REQUEST['orderId']) : "";
		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$payType = trim($_REQUEST['payType']) ? trim($_REQUEST['payType']) : "";
		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$apkNum = trim($_REQUEST['apkNum']) ? trim($_REQUEST['apkNum']) : "";
		$serverId = trim($_REQUEST['serverId']) ? trim($_REQUEST['serverId']) : "";
		$linkChannelId = trim($_REQUEST['linkChannelId']) ? trim($_REQUEST['linkChannelId']) : "";
		$linkUserName = trim($_REQUEST['linkUserName']) ? trim($_REQUEST['linkUserName']) : "";
		$this->assign('years', $years);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
		$linkChannels = $channels;
		$this->assign('linkChannels', $linkChannels);
		$committeApknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committeApknum', $committeApknum);
		$statistics_model = getInstance('model.statistics');
		$UpperList = $statistics_model->getUpperList();

			
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$game_model = getInstance('model.sdkGame.game');
		$gameList = $game_model->getList();
		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$summary = $game_model->getGameName($upperName, '', $gameStr);
			$sum = array();
			foreach ($summary as $key => $value) {
				$sum[] = "'" . $value['alias'] . "'";
			}
			$sumString = implode(',', $sum);
			//取得专服游戏数据
			if ($specialName) {
				$specialSummary = $game_model->getGameName($upperName, $specialName, $gameStr);
				$specialSum = array();
				foreach ($specialSummary as $key => $value) {
					$specialSum[] = "'" . $value['alias'] . "'";
				}
				$specialString = implode(',', $specialSum);
			}
		}

		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		$this->assign('list_page', $page);
		$row = 25;
		$offset = ($page - 1) * $row;
		$this->assign('list_length', $row);

		load('@oss.model.linkData');
		$link_model = new linkData($years);
		//var_dump($channels);exit;
		$orderList = $link_model->getLinkOrderList($offset, $row, $sumString, $specialString, $game, $channel, $start, $end, $userName, $apkNum, $serverId, $orderId, $payType, $linkUserName, $linkChannelId);
		//var_dump($orderList);exit;
		$total = $link_model->getLinkOrderTotal($sumString, $specialString, $game, $channel, $start, $end, $userName, $apkNum, $serverId, $orderId, $payType, $linkUserName, $linkChannelId);
		//var_dump($total);exit;
		$games = array();
		foreach ($orderList as $key => $value) {
			foreach ($channels as $key2 => $value2) {
				if ($value['channelId'] == $key2) {
					$orderList[$key]['channelName'] = $value2;
				}
				if ($value['linkChannelId'] == $key2) {
					$orderList[$key]['linkChannelName'] = $value2;
				}
			}
			if ($value['payType'] == 'kb') {
				$orderList[$key]['payTypeName'] = '咖币';
			}elseif($value['payType'] == 'coin'){
				$orderList[$key]['payTypeName'] = 'dkw平台币';
			}
			foreach ($gameList as $k => $v) {
				if ($value['gameAlias'] == $v['alias']) {
					$orderList[$key]['gameName'] = $v['name'];
					$orderList[$key]['upperName'] = $v['upperName'];
				}
				$games[$v['alias']] = $v['name'];
			}
		}

//var_dump($games);exit;
		$this->assign('order_list', $orderList);
		$this->assign('list_total', $total[0]['total']);
		$this->assign('games', $games);

		$this->assign('start', date('Y-m-d', $start));
		$this->assign('end', date('Y-m-d', $end));
		$this->assign('orderId', $orderId);
		$this->assign('userName', $userName);
		$this->assign('payType', $payType);
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('serverId', $serverId);
		$this->assign('linkChannelId', $linkChannelId);
		$this->assign('linkUserName', $linkUserName);
		//var_dump($orderId);exit;
	}
}