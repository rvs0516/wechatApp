<?php
//error_reporting(E_ALL);
require_once APP_CONTROLLER_PATH . '/master.php';

class honouredGuestController extends masterControl {

	public function honouredGuest (){
		$operation_list = array('index', 'add', 'edit', 'save', 'del', 'allow', 'revisit');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $operation);

		$guest_model = getInstance('model.honouredGuest');
		$statistics_model = getInstance('model.statistics');
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		switch($operation) {
			case 'index':
				$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
				$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
				$start = $_REQUEST['start_date'] ? strtotime($_REQUEST['start_date']) : "";
				$end = $_REQUEST['end_date'] ? strtotime($_REQUEST['end_date'] . '23:59:59') : time();
				$status = trim($_REQUEST['status']) ? trim($_REQUEST['status']) : "";

				$this->assign('userName', $userName);
				$this->assign('game', $game);
				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);
				$this->assign('status', $status);
				if ($gid == 9) {
					$replace = $this->_uid;
					$status = 1;
				}
				if($_POST['type'] == 'report') {
					$length = 3000;
					$offset = 0;
				}else {
					//查询数据
					$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
					$length = 25;
					$offset = ($page - 1) * $length;
				}

				$this->assign('list_page', $page);
				$this->assign('list_length', $length);

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
				$dataList = $guest_model->getVipList($offset, $length, $sumString, $specialString, $game, $userName, $start, $end, $replace, $status);
				$dataTotal = $guest_model->getVipTotal($sumString, $specialString, $game, $userName, $start, $end, $replace, $status);
				$game_list = $game_model->getList();
				foreach ($dataList as $key => $val) {
					$dataList[$key]['payTime'] = $statistics_model->getRolePayTime('', '', $val['userName']);
					$same = $this->getSameWeek($val['revisit'], time());
					if ($same) {
						$dataList[$key]['same'] = 1;
					}
					foreach ($game_list as $k => $v) {
						if ($val['gameAlias'] == $v['alias']) {
							$dataList[$key]['upperName'] = $v['upperName'];
						}
					}
				}
				$games = array();
				foreach ($game_list as $key => $value) {
					if ($gid == 8) {
						if ($gidarr[0]['game'] == $value['alias']) {
							$games[$value['alias']] = $value['name'];
						}
					}else {
						$games[$value['alias']] = $value['name'];
					}
				}
				$this->assign('games', $games);
				$this->assign('dataList', $dataList);
				$this->assign('list_total', $dataTotal);

				//导出数据
				if ($_POST['type'] == 'report') {
					$reports = array();
					foreach ($dataList as $keyr => $valuer) {
						$same = $valuer['same'] == 1 ? "已回访" : "未回访";
						$status = $valuer['status'] == 1 ? "已审核" : "未审核";
						$birthday = !empty($valuer['birthday']) ? date('Y-m-d', $valuer['birthday']) : "";
						$payTime = !empty($valuer['payTime']) ? date('Y-m-d', $valuer['payTime']) : "";

						$reports[$keyr]['userName'] = $valuer['userName'];
						$reports[$keyr]['upperName'] = $valuer['upperName'];
						$reports[$keyr]['gameName'] = $valuer['gameName'];
						$reports[$keyr]['weixin'] = $valuer['weixin'];
						$reports[$keyr]['qq'] = $valuer['qq'];
						$reports[$keyr]['birthday'] = $birthday;
						$reports[$keyr]['loginTime'] = date('Y-m-d', $valuer['loginTime']);
						$reports[$keyr]['payTime'] = $payTime;
						$reports[$keyr]['remark'] = $valuer['remark'];
						$reports[$keyr]['replace'] = $valuer['replace'];
						$reports[$keyr]['same'] = $same;
						$reports[$keyr]['status'] = $status;
					}
					$sdate = date('Ymd', $start);
					$edate = date('Ymd', $end);
					excel_export("《爱游就游-中央数据后台》VIP列表_{$sdate}_{$edate}", array('账号', '上级游戏名', '来自游戏', '微信', 'QQ', '生日', '最近登录时间', '最后充值时间', '备注', '归属', '回访状态', '审核状态'), $reports);
					exit;
				}
			break;

			case 'add':
				$list = $guest_model->getVipInfo($_REQUEST['userName']);
				if ($list) {
					ShowMsg('该用户信息已记录', '/index.php?m=statistics&a=member');
				}
				$this->assign('userName', $_REQUEST['userName']);
				$this->assign('gameName', $_REQUEST['gameName']);
				$this->assign('gameAlias', $_REQUEST['gameAlias']);
				$this->assign('loginTime', $_REQUEST['loginTime']);
			break;

			case 'edit':
				$list = $guest_model->getVipInfo($_REQUEST['userName']);
				$this->assign('list', $list);
				$this->assign('userName', $_REQUEST['userName']);
				$this->assign('gameName', $_REQUEST['gameName']);
				$this->assign('gameAlias', $_REQUEST['gameAlias']);
			break;

			case 'save':
				$data = array(
				'userName' => trim($_POST['userName']),
				'gameAlias' => trim($_POST['gameAlias']),
				'gameName' => trim($_POST['gameName']),
				'phoneNum' => $_POST['phoneNum'],
				'weixin' => $_POST['weixin'],
				'qq' => $_POST['qq'],
				'birthday' => strtotime($_POST['birthday']),
				'remark' => $_POST['remark']
				);

				if($_POST['isNew'] == 1) {
					$data['status'] = 0;
					$data['replace'] = $this->_uid;
					$data['loginTime'] = $_POST['loginTime'];
					$data['revisit'] = time();
					$guest_model->add($data);
					ShowMsg('操作成功', '/index.php?m=statistics&a=member');
				} else {
					$guest_model->edit($_POST['id'], $data);
					ShowMsg('操作成功', '/index.php?m=honouredGuest&a=honouredGuest');
				}
			break;

			case 'del':
				$guest_model->del($_GET['id']);
				ShowMsg('操作成功', '/index.php?m=honouredGuest&a=honouredGuest');
			break;

			case 'allow':
				$data['status'] = 1;
				$guest_model->edit($_GET['id'], $data);
				ShowMsg('操作成功', '/index.php?m=honouredGuest&a=honouredGuest');
			break;

			case 'revisit':
				$data['revisit'] = time();
				$guest_model->edit($_GET['id'], $data);
				ShowMsg('操作成功', '/index.php?m=honouredGuest&a=honouredGuest');
			break;
		}
	}

	/**
	* 判断两日期是不是同一周
	* 星期是按周日到周六
	*/
    public function getSameWeek($pretime,$aftertime){
	    $flag = false;//默认不是同一周
	    $afweek = date('w',$aftertime);//当前是星期几
	    $minday= $aftertime - $afweek * 3600*24;
		$mintime = strtotime(date('y-m-d', $minday));//一周开始时间
	    $maxtime = $mintime + 7*3600*24;//一周结束时间
	    if ( $pretime >= $mintime && $pretime <= $maxtime){//同一周
	      $flag = true;
	    }
	    return $flag;
	}

	/**
     * 回流用户订单
     */
	public function order() {
		@session_start();
		$order_model = new Model('ms_order');
		$config = loadC('config.inc.php', 'main');
		//取出所有channel
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$this->assign('userName', $userName);

		$roleId = trim($_REQUEST['roleId']) ? trim($_REQUEST['roleId']) : "";
		$this->assign('roleId', $roleId);

		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$type = trim($_REQUEST['type']) ? trim($_REQUEST['type']) : 1;
		$this->assign('type', $type);
		$this->assign('game', $game);
		
		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";//渠道
		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}

		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);

		//时间范围
		if (!empty($_REQUEST['start_date'])) {
			$start_time = strtotime($_REQUEST['start_date']);
		}
		if (!empty($_REQUEST['end_date'])) {
			$end_time = strtotime($_REQUEST['end_date'] . '23:59:59');
		}
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);

		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		//考虑服务器性能损耗，一次导出最多导出三千条
		if($_POST['operation'] === 'report') {
			$page = 1;
			$row = 3000;
			$offset = 0;
		}else {
			//查询数据
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$row = 25;
			$offset = ($page - 1) * $row;
		}

		$statistics_model = getInstance('model.statistics');
		$guest_model = getInstance('model.honouredGuest');

		//获取上级游戏名
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$game_model = getInstance('model.sdkGame.game');
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

		$serverId = trim($_REQUEST['serverId']) ? trim($_REQUEST['serverId']) : "";
		$this->assign('serverId', $serverId);

		$orderId = trim($_REQUEST['orderId']) ? trim($_REQUEST['orderId']) : "";
		$this->assign('orderId', $orderId);

		$order_list = $guest_model->getOrderList($offset, $row, $sumString, $specialString, $game, $channel, $start_time, $end_time, $userName, $apkNum, $roleId, $serverId, $orderId, $type);
		$total_row = $guest_model->getOrderListTotal($sumString, $specialString, $game, $channel, $start_time, $end_time, $userName, $apkNum, $roleId, $serverId, $orderId, $type);
		$this->assign('list_total', $total_row['0']['total']);
		foreach ($order_list as $k => $v) {
			$scale = $config['key'][$v['gameAlias']]['scale'] ? $config['key'][$v['gameAlias']]['scale'] : 10;
			$order_list[$k]['gold'] = $v['money'] * $scale;
		}

		$num_omoney = 0;
		foreach ($order_list as $key => $val) {
			foreach ($game_list as $k => $v) {
				if ($val['gameAlias'] == $v['alias']) {
					$order_list[$key]['upperName'] = $v['upperName'];
				}
			}
		}
		$array = array(
		'list_page' => $page,
		'list_length' => $row,
		'gid' => $gid,
		'order_list' => is_array($order_list) ? $order_list : array()
		);
		$this->assign($array);

		if($_POST['operation'] === 'report') {
			$reports = array();
			foreach ($order_list as $keyr => $valuer) {
				$reports[$keyr]['ousername'] = $valuer['userName'];
				$reports[$keyr]['oid'] = $valuer['orderId'];
				$reports[$keyr]['game'] = $valuer['gameName'];
				$reports[$keyr]['server'] = $valuer['server'];
				$reports[$keyr]['ocharname'] = $valuer['roleName'];
				$reports[$keyr]['roleId'] = $valuer['roleId'];
				$reports[$keyr]['otime'] = date('Y-m-d H:i', $valuer['time']);
				$reports[$keyr]['omoney'] = $valuer['money'];
				$reports[$keyr]['agent_pay_gold'] = $valuer['gold'];
				$reports[$keyr]['channelname'] = ($valuer['channelName']) ? $valuer['channelName'] : ' ';
				$reports[$keyr]['apkNum'] = ($valuer['apkNum']) ? $valuer['apkNum'] : ' ';
			}
			$sdate = date('Ymd', $start_time);
			$edate = date('Ymd', $end_time);
			excel_export("《爱游就游-中央数据后台》回流充值列表_{$sdate}_{$edate}", array(
			'账号', '订单号', '游戏', '服务器', '角色', '角色ID', '充值时间', '金额', '元宝', '渠道', '所属包体'
			), $reports);
			exit;
		}

		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
	}

}