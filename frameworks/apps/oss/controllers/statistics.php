<?php

require_once APP_CONTROLLER_PATH . '/master.php';

class statisticsController extends masterControl {

	/**
     * 会员报表
     */
	public function member() {
		@session_start();

		if ($this->_is_channel) {
			if (!in_array('member', $this->_channel_role)) {
				ShowMsg('对不起，您无权进入此页面', '-1');
				exit;
			}
		}
		
		$operation_list = array('index', 'edit', 'save', 'bindingRelieve');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $operation);
		$uid_list = array('luojiang', 'baohuan', 'heyongzhen', 'yangzhenwei', 'chenjh', 'luojunri', 'yfdata','guofengchi', 'jianjianxiang','guofengchi');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';

		$statistics_model = getInstance('model.statistics');
		$member_model = new model('ms_member');
		$member_extend_model = new model('ms_member_info');

		// 获取当前账号登录过的所有游戏
		if($_POST['userNameToGame']){
			$usernameToGameSql = "SELECT g.`upperName`, g.`specialName` , r.`gameName` FROM `ms_role_seted` as r LEFT JOIN `ms_game` as g ON r.`gameAlias` = g.`alias` WHERE `userName` = '{$_POST['userNameToGame']}'";
			$usernameToGameRes = model::getBySql($usernameToGameSql);
			$usernameToGameArray = array();
			foreach ($usernameToGameRes as $key => $value) {
				$usernameToGameArray[] = $value['upperName']. '—'. $value['specialName']. '—'. $value['gameName'];
			}
			echo json_encode($usernameToGameArray);exit;
		}
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);
		
		switch($operation) {
			case 'index':
				require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
				$this->assign('channels', $channels);

				if (!empty($_REQUEST['game'])) {
					$game = $_REQUEST['game'];
					$this->assign('game', $game);
				}

				$this->assign('game_config', loadC('config.inc.php', 'main'));

				$gameStr = '';
				if ($gid == 8) {
					$game = $gidarr[0]['game'];
				}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 17 || $gid == 15){
					if ($gidarr[0]['game'] != 'all') {
						$explode = explode('|', $gidarr[0]['game']);
						foreach ($explode as $k => $v) {
							$gameStr .= "'" . $v . "',";
						}
						$gameStr = substr($gameStr,0,-1);
						$this->assign('gameStr', $gameStr);
					}
				}else {
					$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
				}
				$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
				$uid = trim($_REQUEST['userid']) ? trim($_REQUEST['userid']) : "";
				$start_time = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : "";
				$end_time = $_REQUEST['end_date'] ? $_REQUEST['end_date'] : "";
				$keywords = trim($_REQUEST['keywords']) ? trim($_REQUEST['keywords']) : "";
				if($_REQUEST['apkNum']){
					$apkNum = trim($_REQUEST['apkNum']);
				}elseif ($_REQUEST['yjApkNum']) {
					$apkNum = trim($_REQUEST['yjApkNum']);
				}

				if (!empty($_REQUEST['userid'])) {
					$userid = RemoveXSS($_REQUEST['userid']);
					$this->assign('userid', $userid);
				}

				$platformUserId = trim($_REQUEST['platformUserId']) ? trim($_REQUEST['platformUserId']) : "";
				$this->assign('platformUserId', $platformUserId);

				$info = trim($_REQUEST['info']) ? trim($_REQUEST['info']) : "";

				$this->assign('info', $info);
				$this->assign('keywords', $keywords);
				$this->assign('game_array', $game);
				$this->assign('channel_array', $channel);
				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);
				$this->assign('apkNum', $apkNum);

				$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
				$this->assign('list_page', $page);
				$row = 25;
				$offset = ($page - 1) * $row;
				$this->assign('list_length', $row);

				//获取上级游戏名
				if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ) {
					$UpperList = $statistics_model->getUpperListGs($gameStr);
				}else{
					$UpperList = $statistics_model->getUpperList();
				}
				$this->assign('UpperList', $UpperList);
				$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
				$this->assign('upperName', $upperName);
				$this->assign('gid', $gid);

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

				$order_list = $statistics_model->getMemberList($game, $channel, $uid, $start_time, $end_time, $offset, $row, $keywords, $apkNum, $sumString, $specialString, $info, $gid, $gameStr, $platformUserId);
				$total_row = $statistics_model->getMemberListTotal($game, $channel, $uid, $start_time, $end_time, $keywords, $apkNum, $sumString, $specialString, $info, $gid, $gameStr, $platformUserId);
				$this->assign('list_total', $total_row);

				//获取包号列表
				//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
				/*$committe_apknum = array();
				foreach ($committe_list as $key => $value) {
					$committe_apknum[$key] = $value['apkNum'];
				}*/
				$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
				$this->assign('committe_apknum', $committe_apknum);

				//游戏
				$game_model = getInstance('model.sdkGame.game');
				$game_list = $game_model->getList();
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

				foreach ($order_list as $k => $v) {
					$order_list[$k]['more'] = base64_encode($v['userName'].'|'.$v['callnumber'].'|'.$v['email']);
				}
				$this->assign('total', $total);
				$this->assign('page', $page);
				$this->assign('offset', $row);
				$this->assign('order_list', is_array($order_list) ? $order_list : array());
				break;

			case 'edit':
				//游戏
				
				$game_model = getInstance('model.sdkGame.game');
				$game_list = $game_model->getList();
				$gameListsArray = array();
				foreach ($game_list as $key => $value) {
					$gameListsArray[$key]['alias'] = $value['alias'];
					$gameListsArray[$key]['name'] = $value['upperName']. '—'. $value['specialName']. '—'. $value['name'];
				}
				// 按项目首字母排序
				$gameListsArray = data_letter_sort($gameListsArray, 'name');
				// 合并数组
				$gameLists = array();
				foreach ($gameListsArray as $key => $value) {
					foreach ($value as $k => $v) {
						$gameLists[] = $v;
					}
				}
				$this->assign('gameLists', $gameLists);

				$userName = $_REQUEST['userName'];
				$platformUserId = $_REQUEST['platformUserId'];
				$channelId = $_REQUEST['channelId'];
				$this->assign('userName', $userName);
				$this->assign('platformUserId', $platformUserId);
				$this->assign('channelId', $channelId);
				if ($checkRoot || $gid == 21) {
					$userExtend = $member_extend_model->get("`userName`='" . $userName . "'");
					$is_new = 0;
					if (!empty($userExtend)) {
						// 关联账号
						if($userExtend['assUserName']){
							$assUserName = $userExtend['assUserName'];
							$assUserNameArray = explode(',', $assUserName);
							$this->assign('assUserName', $assUserNameArray);
						}
						
						// 关联游戏
						$assGame = $userExtend['assGame'];
						if( strpos($assGame, ',') ){
							$assGameExplode = explode(',', $assGame);
							$assGameStr = '';
							foreach ($assGameExplode as $key => $value) {
								$assGameStr .= $value. "','";
							}
							$assGameStr = rtrim($assGameStr, "','");
							$sql = "SELECT `upperName`, `specialName`, `name` FROM `ms_game` WHERE `alias` in ('{$assGameStr}')";
						}else {
							$sql = "SELECT `upperName`, `specialName`, `name` FROM ms_game WHERE `alias` = '{$assGame}'";
						}
						$assGameRes = model::getBySql($sql);
						$assGameNameArray = array();
						foreach ($assGameRes as $key => $value) {
							$assGameNameArray[] = $value['upperName']. '——'. $value['specialName']. '——'. $value['name'];
							$assGameString .= $value['upperName']. '——'. $value['specialName']. '——'. $value['name']. ',';
						}
						$assGameString = rtrim($assGameString, ','); // 去掉右边的逗号
						$this->assign('assGameString', $assGameString);
						$this->assign('assGameName', $assGameNameArray);

						$assGame = $userExtend['assGame'];
						$assGameArray = explode(',', $assGame);
						$this->assign('assGame', $assGameArray);
						$type = $userExtend['type'];
						$this->assign('type', $type);
					}else{
						$is_new = 1;
					}
					$this->assign('is_new', $is_new);
				}else{
					ShowMsg('无相关权限', '/index.php?m=statistics&a=member');
				}
				break;
			case 'save':
				$userName = trim($_REQUEST['userName']);
				$password = trim($_REQUEST['password']);
				$assUserName = trim($_REQUEST['assUserName']);
				$assGame = trim($_REQUEST['assGame']);
				$platformUserId = trim($_REQUEST['platformUserId']);
				$channelId = trim($_REQUEST['channelId']);
				$is_new = trim($_REQUEST['is_new']);
				$type = trim($_REQUEST['type']);
				if (!empty($password)) {
					$success = $statistics_model->pwEdit($userName, md5($password));
					if (!$success) {
						ShowMsg('操作失败', '/index.php?m=statistics&a=member&userName=' . $userName . '&operation=edit');
					}
				}
				if ($checkRoot || $gid == 21) {
					if ($is_new == 0) {
						$member_extend_model->set(array(
						'assUserName' => $assUserName,
						'assGame' => $assGame,
						'type' => $type,
						'loginTime' => time(),
						'uid' => $this->_uid
						), "`userName`='" . $userName . "'");
					}elseif ($is_new == 1) {
						$data = array(
							'userName' => $userName, 
							'platformUserId' => $platformUserId, 
							'channelId' => $channelId, 
							'assUserName' => $assUserName, 
							'assGame' => $assGame,
							'type' => $type,
							'loginTime' => time(),
							'uid' => $this->_uid
							);
						$member_extend_model->set($data);
					}

				}
				// 上报账号关联后的转端标识至达咖玩
				if ($channelId == '500028') {
					if($assUserName){
						$isRelations = 1;
					}else{
						$isRelations = 0;
					}
					/*$platformUserIdSql = "select platformUserId from ms_member where userName = '{$_REQUEST['userName']}'";
					$platformUserIdSqlRes = model::getBySql($platformUserIdSql);
					$memberSub = $platformUserIdSqlRes[0]['platformUserId'];*/
					$memberSub = $platformUserId;
					$this->dkwReportRelation($memberSub, $isRelations);

				}

				// 上报账号关联后的转端标识至耀玩
				if ($channelId == '500071') {
					if($assUserName){
						$isRelations = 1;
					}else{
						$isRelations = 0;
					}
					
					$memberSub = $platformUserId;
					$this->yaowanReportRelation($memberSub, $isRelations);
				}

				// 记录转端操作日志
				error_log("\n". date("[Y-m-d H:i:s]"). "\n". "action: ". trim($_REQUEST['a']). "\n".  "uid: ". $this->_uid. "\n". "username: ". $userName. "\n". "assgame: ". $assGame. "\n". "assusername: ". $assUserName.  "\n\n", 3, C('DEDE_DATA_PATH')."/logs/turnLogs.txt");


				ShowMsg('操作成功', '/index.php?m=statistics&a=member');
				break;
			case 'bindingRelieve':
				if ($checkRoot != 1) {
					ShowMsg('无相关权限', '/index.php?m=statistics&a=member');
				}
				$user = $member_model->get('`userName`=%s', array($_GET['userName']));
				if (!$user) {
					ShowMsg('用户信息有误 ', '/index.php?m=statistics&a=member');
				}

				//解除手机号绑定
				$member_model->set(array(
				'callnumber' => ''
				), "`userName`='" . $_GET['userName'] . "'");
				ShowMsg('操作成功', '/index.php?m=statistics&a=member');
				break;

		}
	}

	/**
	 * 上报账号关联后的转端标识至达咖玩
	 * @param string $memberSub 渠道小号
	 * @param int $isRelations 转端标识
	 */
	public function dkwReportRelation($memberSub, $isRelations)
	{
		$url = 'http://sdk.api.gzzy128.com/api/index.php?m=index&a=relationsMember';

		$body = array(
			'memberSub' => $memberSub,
			'isRelations' => $isRelations,
		);

		$config = require(APP_LIST_PATH . "main/config.inc.php");
		$common_key = $config['common_key'];
		$sign_str = $body['memberSub'].$body['isRelations'].$common_key;
		$body['sign'] = md5($sign_str);

		$res = httpRequest($url, $body);

		// 记录操作日志
		error_log("\n". date("[Y-m-d H:i:s]"). "\n". "url: ". $url. "\n".  "body: ". json_encode($body). "\n". "sign_str: ". $sign_str. "\n". "res: ". $res. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/dkwReportRelation_". date("ymd"). ".txt");
	}

	/**
	 * 上报账号关联后的转端标识至耀玩
	 * @param string $memberSub 渠道小号
	 * @param int $isRelations 转端标识
	 */
	public function yaowanReportRelation($memberSub, $isRelations)
	{
		$url = 'http://sdk.api.online128.com/api/index.php?m=index&a=relationsMember';

		$body = array(
			'memberSub' => $memberSub,
			'isRelations' => $isRelations,
		);

		$config = require(APP_LIST_PATH . "main/config.inc.php");
		$common_key = $config['common_key'];
		$sign_str = $body['memberSub'].$body['isRelations'].$common_key;
		$body['sign'] = md5($sign_str);

		$res = httpRequest($url, $body);

		// 记录操作日志
		error_log("\n". date("[Y-m-d H:i:s]"). "\n". "url: ". $url. "\n".  "body: ". json_encode($body). "\n". "sign_str: ". $sign_str. "\n". "res: ". $res. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/yaowanReportRelation_". date("ymd"). ".txt");
	}

	/**
     * 订单
     */
	public function order() {
		@session_start();
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		
		$order_model = new Model('ms_order');
		//取出所有channel
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		if (!empty($_REQUEST['game'])) {
			$rq_game = $_REQUEST['game'];
			$this->assign('game', $rq_game);
		}

		$this->assign('game_config', loadC('config.inc.php', 'main'));
		$where = '';
		if (!isset($_REQUEST['ostatus'])) {
			$status = 1;
		} else {
			$status = intval($_REQUEST['ostatus']);
		}

		$this->assign('ostatus', $status);

		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$this->assign('userName', $userName);

		$roleId = trim($_REQUEST['roleId']) ? trim($_REQUEST['roleId']) : "";
		$this->assign('roleId', $roleId);
		$openAd = trim($_REQUEST['openAd']) ? trim($_REQUEST['openAd']) : "";
		$this->assign('openAd', $openAd);

		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		
		if ($gid == 8) {
			$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : $gidarr[0]['channelId'];//渠道
		}else{
			$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		}
		$paymentId = trim($_REQUEST['paymentId']) ? trim($_REQUEST['paymentId']) : "";//支付方式
		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}

		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('paymentId', $paymentId);

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
			if ($gid == 8) {
				if ($gidarr[0]['game'] == $value['alias']) {
					$games[$value['alias']] = $value['name'];
				}
			}else {
				$games[$value['alias']] = $value['name'];
			}
		}
		$this->assign('games', $games);

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

		$statistics_model = getInstance('model.statistics');

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
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

		$order_list = $statistics_model->getOrderList($game, $channel, $start_time, $end_time, $status, $userName, $offset, $row, $apkNum, $paymentId, $sumString, $specialString, $roleId, $openAd, $serverId, $gid, $gameStr, $orderId);
		
		$total_row = $statistics_model->getOrderListTotal($game, $channel, $start_time, $end_time, $status, $userName, $apkNum, $paymentId, $sumString, $specialString, $roleId, $openAd, $serverId, $gid, $gameStr, $orderId);
		$this->assign('list_total', $total_row['0']['total']);
		$order_model = new Model('ms_order');

		$num_omoney = 0;
		foreach ($order_list as $key => $val) {
			//$num_omoney += $val['money'];
			//$num_pay_gold += $val['gold'];
			foreach ($game_list as $k => $v) {
				if ($val['gameAlias'] == $v['alias']) {
					$order_list[$key]['upperName'] = $v['upperName'];
				}
			}
			$descArray = explode(',', $val['orderDescr']);
			if ($descArray[1] == 'USD') {
				$order_list[$key]['currency'] = '美元';
			}elseif ($descArray[1] == 'VND') {
				$order_list[$key]['currency'] = '越南盾';
			}else{
				$order_list[$key]['currency'] = '人民币';
			}
			if (!empty($descArray[2]) && $order_list[$key]['currency'] != '人民币') {
				$order_list[$key]['money'] = $descArray[2];
			}
			$vip = 'SELECT userName,phoneNum,returnImg FROM `ms_vipguest`  where userName = "'.$val['userName'].'"';
			$vip_data = Model::getBySql($vip);
			if ($vip_data[0]['phoneNum'] || $vip_data[0]['returnImg']) {
				$order_list[$key]['vip_sign'] = true;
			}else{
				$order_list[$key]['vip_sign'] = false;
			}

		}
		$array = array(
		//'total_omoney' => is_array($total_row) ? $total_row[0]['paymoney'] : 0,
		//'agent_pay_gold' => is_array($total_row) ? $total_row[0]['agent_pay_gold'] : 0,
		//'num_omoney' => $num_omoney,
		//'num_pay_gold' => $num_pay_gold,
		'list_page' => $page,
		'list_length' => $row,
		'gid' => $gid,
		'order_list' => is_array($order_list) ? $order_list : array()
		);
		$this->assign($array);

		
			if($_POST['operation'] === 'report') {
				$reports = array();
				
				foreach ($order_list as $keyr => $valuer) {
					if ($valuer['currency'] != '人民币') {
						$valuer['currency'] = "(".$valuer['currency'].")";
					}else{
						$valuer['currency'] = "";
					}
					$reports[$keyr]['ousername'] = $valuer['userName'];
					$reports[$keyr]['oid'] = $valuer['orderId'];
					$reports[$keyr]['game'] = $valuer['gameName'];
					$reports[$keyr]['server'] = $valuer['server'];
					$reports[$keyr]['ocharname'] = $valuer['roleName'];
					$reports[$keyr]['roleId'] = $valuer['roleId'];
					$reports[$keyr]['otime'] = date('Y-m-d H:i', $valuer['time']);
					$reports[$keyr]['omoney'] = $valuer['money'].$valuer['currency'];
					$reports[$keyr]['agent_pay_gold'] = $valuer['gold'];
					$reports[$keyr]['channelname'] = ($valuer['channelName']) ? $valuer['channelName'] : ' ';
					$reports[$keyr]['apkNum'] = ($valuer['apkNum']) ? $valuer['apkNum'] : ' ';
					if ($valuer['paymentId'] == 9) {
						$reports[$keyr]['paymentId'] = '微信';
					}elseif ($valuer['paymentId'] == 7) {
						$reports[$keyr]['paymentId'] = '支付宝';
					}else{
						$reports[$keyr]['paymentId'] = '';
					}
					if ($gid != 2) {
						$reports[$keyr]['adid'] = $valuer['adid'];
						$adstring = '广告id';
					}else{
						$adstring = '';
					}
					//$reports[$keyr]['paymentId'] = ($valuer['paymentId'] == 9) ? '微信' : ($valuer['paymentId'] == 7) ? '支付宝' : ' ';
				}
				$sdate = date('Ymd', $start_time);
				$edate = date('Ymd', $end_time);
				excel_export("《爱游就游-中央数据后台》充值列表_{$sdate}_{$edate}", array(
				'账号', '订单号', '游戏', '服务器', '角色', '角色ID', '充值时间', '金额', '元宝', 'CPS渠道', '所属包体', '通道', $adstring
				), $reports);
				exit;
			}
		

		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
		/*if ($gid != 8) {
			if (empty($channel)) {
				$recharge_total = $statistics_model->platformRechargeTotal($game, $start_time, $end_time, $sumString, $specialString);
				$this->assign('recharge_total', $recharge_total[0]['total']);
			}
		}*/
	}


	/**
     * 留存用戶
     */
	public function retention() {
		@session_start();

		//判断是gid
		$channel_model = getInstance('model.sdkChannel.channel');

		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);

		$channel = $_REQUEST['channel'];
		$this->assign('channel', $channel);

		//渠道列表
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
			//临时处理
			$channelArray = explode('|', $gidarr[0]['channelId']);
			$channelCount = count($channelArray);
			if($channelCount = 1 && empty($channel)){
				$channel = $gidarr[0]['channelId'];
			}
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}
		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}
		//查看权限
		$allow = 0;
		if ($gid == 1 || $gid == 15 || $gid == 17 || $this->_uid == 'buluoyy') {
			$allow = 1;
		}
		$this->assign('allow', $allow);
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

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

		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
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

		$rType = trim($_REQUEST['rType']) ? trim($_REQUEST['rType']) : "1";
		$this->assign('rType', $rType);

		if ($_POST) {
			if (empty($upperName) && $gid != 8) {
				ShowMsg('请选择游戏后查询',-1);exit;
			}
			$role_model = new Model('ms_role_seted');
			set_time_limit(180);

			$operation_list = array('list', 'report');
			$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'list';
			$this->assign('operation', $operation);

			$start_time = strtotime($_POST['start_date']);
			$end_time = strtotime($_POST['end_date']) > TIME ? TIME : strtotime($_POST['end_date']);
			$game = $_POST['game'];

			$date_list = $this->_getDateList($start_time, $end_time);
			krsort($date_list);

			//显示列表要处理分页
			if ($operation == 'list') {
				$this->assign('start_date', $_POST['start_date']);
				$this->assign('end_date', $_POST['end_date']);
				$this->assign('game', $game);
				$limit = 100;

				$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
				$offset = ($page - 1) * $limit;

				$this->assign('page', $page);
				$this->assign('limit', $limit);
				$this->assign('total', count($date_list));
				$date_list = array_splice($date_list, $offset, $limit);
			}
			$data = array();

			if($_REQUEST['apkNum']){
				$apkNum = trim($_REQUEST['apkNum']);
			}elseif ($_REQUEST['yjApkNum']) {
				$apkNum = trim($_REQUEST['yjApkNum']);
			}

			$where = ' 1 ';
			if ($gid == 8) {
				$where .= " AND s.`gameAlias`='" . $gidarr[0]['game'] . "' ";
				$other .= " AND l.`gameAlias`='" . $gidarr[0]['game'] . "' ";
			}else {
				$where .= ($game) ? " AND s.`gameAlias`='$game' " : "";
				$other .= ($game) ? " AND l.`gameAlias`='$game' " : "";
			}

			$where .= ($channel) ? " AND s.`channelId`='$channel' " : "";
			$where .= ($apkNum) ? " AND s.`apkNum`='$apkNum' " : "";
			$this->assign('apkNum', $apkNum);
			$this->assign('channel', $channel);
			if($sumString){
				$string = " AND s.`gameAlias` IN (" . $sumString . ") ";
				$str = " AND l.`gameAlias` IN (" . $sumString . ") ";
				if($specialString){
					$string = " AND s.`gameAlias` IN (" . $specialString . ") ";
					$str = " AND l.`gameAlias` IN (" . $specialString . ") ";
				}
				$where .= $string;
				$other .= $str;
			}

			//每列汇总
			$total_data = array();

			//要计算次日留存、三日留存、四日留存、五日留存、六日留存、七日留存、双周留存、月留存、双月留存、三月留存。
			$daylist = array(1, 2, 3, 4, 5, 6, 13, 29, 59, 89);
			foreach ($date_list as $date) {
				$_start = strtotime($date);
				$_end = strtotime($date . ' 23:59:59');
				$table = 'ms_role_seted';
				if ($rType == 1) {
					$regrows = $role_model->getBySql("SELECT count(1) as regtotal FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1");
				}else{
					$regrows = $role_model->getBySql("SELECT COUNT(DISTINCT o.`roleId`) AS regtotal FROM ms_order o LEFT JOIN `ms_role_seted` s ON o.`roleId` = s.`roleId` AND o.`gameAlias` = s.`gameAlias` AND o.`channelId` = s.`channelId` AND o.`userName` = s.`userName` WHERE $where AND o.`orderStatus` = '1' AND s.`type` = 'server' AND s.`isFirst` = 1 AND s.`time` BETWEEN $_start AND $_end AND FROM_UNIXTIME( o.`time` , '%Y-%m-%d' ) = '$date'");
				}

				$regtotal = $regrows ? intval($regrows[0]['regtotal']) : 0;

				if ($regtotal) {
					//每列汇总-1
					$total_data[0] += $regtotal;
					if ($rType == 1) {
						$sql = "SELECT count(DISTINCT l.roleId) as usertotal, FROM_UNIXTIME(l.time, '%Y-%m-%d') as date FROM ("
						. " SELECT roleId, channelId, gameAlias, apkNum, serverId "
						. " FROM ms_role_seted"
						. " WHERE $_start <= `time` AND `time` <= $_end AND `type` = 'server' AND `isFirst` = 1"
						. " ) s LEFT JOIN $table l ON s.roleId = l.roleId AND s.serverId = l.serverId" 
						. " WHERE $where $other AND l.`type` = 'server' AND $_start <= l.`time` AND l.`time` <= ($_end + (86400 * 30))"
						. " GROUP BY FROM_UNIXTIME(l.time, '%Y-%m-%d') ORDER BY l.time DESC";
					}else{
						$sql = "SELECT count(DISTINCT l.roleId) as usertotal, FROM_UNIXTIME(l.time, '%Y-%m-%d') as date FROM ("
						. " SELECT s.roleId, s.channelId, s.gameAlias, s.apkNum, s.serverId "
						. " FROM ms_order o LEFT JOIN `ms_role_seted` s ON o.`roleId` = s.`roleId` AND o.`gameAlias` = s.`gameAlias` AND o.`channelId` = s.`channelId` AND o.`userName` = s.`userName` "
						. " WHERE $where AND o.`orderStatus` = '1' AND s.`type` = 'server' AND s.`isFirst` = 1 AND s.`time` BETWEEN $_start AND $_end AND FROM_UNIXTIME( o.`time` , '%Y-%m-%d' ) = '$date') s LEFT JOIN $table l ON s.roleId = l.roleId AND s.serverId = l.serverId" 
						. " WHERE $where $other AND l.`type` = 'server' AND $_start <= l.`time` AND l.`time` <= ($_end + (86400 * 30))"
						. " GROUP BY FROM_UNIXTIME(l.time, '%Y-%m-%d') ORDER BY l.time DESC";
					}
					

					$loginrows = $role_model->getBySql($sql);
					$_data = array(
					'date' => $date, 'total' => $regtotal
					);
					foreach ($daylist as $day) {
						$retention = $this->_getRetention($regtotal, $loginrows, $_start, $day);
						$_data['admix_' . $day] = $retention[0] . '/( <span style="color: red">' . $retention[1] . '</span>% )';

						//每列汇总-N
						$total_data[$day] += $retention[1];
					}
				} else {
					$_data = array(
					'date' => $date, 'total' => 0
					);
					foreach ($daylist as $day) {
						$_data['admix_' . $day]  = '0/( 0% )';
					}
				}

				//当日期要过那个时间，才显示，比如今日是看不到次日留存率的
				//这种情况设定为空

				foreach ($daylist as $day) {
					if ((TIME - $_start < 86400 * $day)) {
						$_data['admix_' . $day]  = '';
					}
				}

				$data[] = $_data;
			}

			if ($operation === 'report') {
				$sdate = date('Ymd', $start_time);
				$edate = date('Ymd', $end_time);
				excel_export("留存用户统计表_{$sdate}_{$edate}", array(
				'日期', '注册数', '次日留存', '三日留存', '四日留存', '五日留存', '六日留存', '七日留存', '双周留存', '月留存', '双月留存', '三月留存'
				), $data);
				exit;
			} else {
				foreach ($daylist as $key => $value) {
					foreach ($data as $key1 => $value1) {
						if ($value1['admix_'.$value]) {
							$count[$value] += 1;
						}
					}
				}
				foreach ($total_data as $k => $v) {
					if ($k != 0) {
						$total_data[$k] = round($v / $count[$k], 2).'%';
					}
				}
				$this->assign('data', $data);
				$this->assign('total_data', $total_data);
				$this->assign('count_data', count($data));			
			}
		} else {
			$date = date('Y-m-d', TIME);
			$this->assign('start_date', $date);
			$this->assign('end_date', $date);
		}
	}

	/**
     * 获取某个范围的留存率
     *
     *
     * @param type $regtotal
     * @param type $data
     * @param type $start
     * @param type $day
     * @return type
     */
	private function _getRetention($regtotal, $data, $start, $day) {
		foreach ($data as $value) {
			if ($value['date'] == date('Y-m-d', $start + $day * 86400)) {
				return array($value['usertotal'], number_format($value['usertotal'] / $regtotal, 4) * 100);
			}
		}
		return array(0, 0);
	}

	private function _getDateList($start_time, $end_time) {
		$format = 'Y-m-d';
		$day_time = 86400;

		$intime = strtotime(date($format, $start_time));
		$endtime = strtotime(date($format, $end_time)) + $day_time;

		//计算时间差
		$diff_time = $endtime - $intime;
		//当前时间，它会遍历而增加
		$current_time = $intime;
		//这个数组保存查询的时间范围的所有日期
		$date_list = array(date($format, $intime));
		while (($diff_time -= $day_time) && $diff_time > 0) {
			//每遍历一次增加一天
			$current_time += $day_time;
			$date_list[] = date($format, $current_time);
		}
		return $date_list;
	}

	//地区统计
	public function area(){
		//判断是gid
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if (!empty($_REQUEST['channel'])) {
			$_SESSION['qchannel'] = $_REQUEST['channel'];
		} else {
			$_SESSION['qchannel'] = "0";
		}
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
		
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

		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$uid = trim($_REQUEST['userid']) ? trim($_REQUEST['userid']) : "";
		$start_time = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : "";
		$end_time = $_REQUEST['end_date'] ? $_REQUEST['end_date'] : "";
		$keywords = trim($_REQUEST['keywords']) ? trim($_REQUEST['keywords']) : "";
		$area = trim($_REQUEST['area']) ? trim($_REQUEST['area']) : "";
		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}

		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		$this->assign('list_page', $page);
		$row = 25;
		$offset = ($page - 1) * $row;
		$this->assign('list_length', $row);

		$statistics_model = getInstance('model.statistics');

		//获取上级游戏名
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$game_model = getInstance('model.sdkGame.game');
			$summary = $game_model->getGameName($upperName);
			$sum = array();
			foreach ($summary as $key => $value) {
				$sum[] = "'" . $value['alias'] . "'";
			}
			$sumString = implode(',', $sum);
			//取得专服游戏数据
			if ($specialName) {
				$specialSummary = $game_model->getGameName($upperName, $specialName);
				$specialSum = array();
				foreach ($specialSummary as $key => $value) {
					$specialSum[] = "'" . $value['alias'] . "'";
				}
				$specialString = implode(',', $specialSum);
			}
		}

		$area_list = $statistics_model->getAreaList($game, $channel, $start_time, $end_time, $offset, $row, $keywords, $area, $apkNum, $sumString, $specialString);
		$total_row = $statistics_model->getAreaListTotal($game, $channel, $start_time, $end_time, $keywords, $area, $apkNum, $sumString, $specialString);

		//渠道列表
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		$this->assign('area', $area);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('keywords', $keywords);
		$this->assign('list_total', $total_row);
		$this->assign('area_list', $area_list);
		$this->assign('apkNum', $apkNum);
	}

	/**
	 * 数据来源于数据表ms_integrated_daily
	 * 
	 * 说明：只有同步订单数据的游戏，因为没有活跃玩家信息，所以不做统计，付费金额是少于充值金额走势
	 * 
	 */
	public function consumption(){

		// 年份筛选条件
		$yearArray = array();
		$currentYear = date('Y', time());
		for ($i = 2020; $i <= $currentYear; $i++) { 
			$yearArray[] = $i;
		}
		$this->assign('yearArray', $yearArray);
	
		// 年份
		if ($_REQUEST['years']) {
			$_REQUEST['start_date'] = $_REQUEST['years'].'-01-01';
			$_REQUEST['end_date'] = $_REQUEST['years'].'-12-31';
			$this->assign('years', $_REQUEST['years']);
		}
	
		if ( empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date']) ) {
			ShowMsg('请选择开始日期', -1);
		}
	
		//判断是gid
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
	
		//游戏列表
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			if ($gid == 8) {
				if ($gidarr[0]['game'] == $value['alias']) {
					$games[$value['alias']] = $value['name'];
					$gidUpperName = $value['upperName'];
					$gidSpecialName = $value['specialName'];
				}
			}else {
				$games[$value['alias']] = $value['name'];
			}
		}
		$this->assign('games', $games);
	
		//渠道列表
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
	
		//区分不同角色组权限
		$agentChannel = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}
		$this->assign('allGame',$gidarr[0]['game']);

		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : $gidarr[0]['channelId'];
	
		$this->assign('channel', $channel);
	
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start_date 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end_date 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;
	
		$payCharging = 1;
		if ($gid == 13) {//扣量处理
			$payCharging = !empty($gidarr[0]['payCharging']) ? $gidarr[0]['payCharging'] : 1;
			if ($start_date < date('Y-m-d', $gidarr[0]['limitTime'])) {
				$start_date = date('Y-m-d', $gidarr[0]['limitTime']);
			}
		}
	
		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}
	
		$gsSource = $_REQUEST['gsSource'] ? $_REQUEST['gsSource'] : "";
		$sourceType = $_REQUEST['sourceType'] ? $_REQUEST['sourceType'] : "";

		if (!empty($sourceType)) {
			$apkNum = '';
			if ($sourceType == '1' && $gsSource == '投放') {
				$gsSource = '';
			}
			if ($sourceType == '2' && $gsSource != '投放') {
				$gsSource = '';
			}
			if (!empty($gsSource) && $gsSource != '投放') {
				$channel = '';
			}
		}else{
			$gsSource = '';
		}
		
		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		$this->assign('list_page', $page);
		//导出不限制条数
		$_REQUEST['report'] == 'report' ? $row = 10000 : $row = 25;
	
		$offset = ($page - 1) * $row;
		$this->assign('list_length', $row);
	
		$statistics_model = getInstance('model.statistics');
	
		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('gid', $gid);
	
		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
	
		// 根据所有项目名称获取所有专服名称
		$uperString = '';
		foreach ($UpperList as $key => $value) {
			$uperString .= "'". $value['upperName']. "',";
		}
		$uperString = rtrim($uperString, ',');
		$upperToSecondArray =  $statistics_model->upperToSecond($uperString);
		$specialNameArray = array();
		foreach ($upperToSecondArray as $key => $value) {
			$specialNameArray[$value['upperName']][] = $value['specialName'];
		}
		$this->assign('specialNameArray', $specialNameArray);
	
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		if ($gid == 8) {
			$upperName = $gidUpperName;
			$specialName = $gidSpecialName;
		}
		$this->assign('upperName', $upperName);
		$this->assign('specialName', $specialName);
	
		$sort = trim($_REQUEST['sort']) ? trim($_REQUEST['sort']) : "";
		$this->assign('sort', $sort);
		$displayMode = trim($_REQUEST['displayMode']) ? trim($_REQUEST['displayMode']) : "";
		$this->assign('displayMode', $displayMode);
		
		$source = $_REQUEST['source'] ? $_REQUEST['source'] : "";
		$this->assign('source', $source);
	
		$status = $_REQUEST['status'] ? $_REQUEST['status'] : "1";
		$this->assign('status', $status);
	
		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$game_model = getInstance('model.sdkGame.game');
			$sumList = $game_model->getGameName($upperName, '', $gameStr);
			$sum = array();
			foreach ($sumList as $key => $value) {
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
		if (empty($upperName) && $sort == 'date') {
			ShowMsg('选择游戏才能日期排序',-1);
		}
		if (empty($upperName)) {
			// 没有选择项目 默认查询全部项目
			$refine = 1;
			if ($source == 1 && $displayMode == 'details') {
				$refine = 7;
			}
		}elseif ($upperName && empty($specialName)) {
			// 选择项目 没有选择专服
			$refine = 2;
		}elseif ($specialName && empty($game)) {
			// 选择项目和专服 没有选择指定游戏
			$refine = 3;
		}elseif ($game && empty($channel)) {
			// 选择了指定游戏 没有选择指定渠道
			$refine = 4;
		}elseif ($channel && empty($apkNum)) {
			// 选择指定渠道
			$refine = 5;
		}elseif ($apkNum) {
			// 只选择包号
			$refine = 6;
		}
	
		$this->assign('refine', $refine);
	
		//权限
		$header_model = getInstance('model.statistics');
		$data_jution = $header_model->getHeader();
		$header_data = $header_model->getJurisdiction($gidarr[0]['uid']);

		// 游戏名称
		$this->assign('game', $game);
		$this->assign('specialName', $specialName);
		$this->assign('upperName', $upperName);
		if ($source == 3 && empty($channel) && empty($apkNum)) {
			// 市场数据: 包括目前市场部门在投放的渠道数据
	
			// 筛选专服 
			if ($_REQUEST['resertSpecialName']) {
				$str = strpos($_REQUEST['resertSpecialName'], ',');
				if ($str) {
					$string = str_replace(',', "','", $_REQUEST['resertSpecialName']);
					$resertSpecialName = "'". $string. "'";
				}else {
					$resertSpecialName = "'". $_REQUEST['resertSpecialName']. "'";
				}
	
				$this->assign('resertSpecialName', $_REQUEST['resertSpecialName']);
			}
	
			// 选择指定项目 去掉limit限制
			if ($_REQUEST['years'] && ($upperName || $specialName || $game)) {
				$row = 99999999;
			}
	
			// 360、oppo、UC、金立、百度、魅族、小米、应用宝、vivo、华为
			$marketChannel = "'000023', '000020', '000255', '001145', '110000', '000014', '000066', '160136', '000368', '500001'";
			
			//取得项目支出总数
			$marketcGamePay = $statistics_model->getGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $marketChannel, $apkNum, 'consumption', '', $resertSpecialName);
			//取得综合数据列表
			$marketcActiveLists = $statistics_model->yhGetConsumption($game, $marketChannel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据总记录数
			$marketcActive_total = $statistics_model->getConsumptionTotal($game, $marketChannel, $start_date, $end_date, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据汇总
			$marketcSummary = $statistics_model->getConsumptionSummary($game, $marketChannel, $start_date, $end_date, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
	
			// 综合数据列表数据格式化
			$marketcActive_list = array();
			$marketcActive_list = $this->getActiveList($displayMode, $_REQUEST['upperName'], $_REQUEST['upperName'], $_REQUEST['game'], $_REQUEST['years'], $marketcActiveLists);
	
			// 计算taptap渠道数据
			$qyChannel = '160068';
			$qyApkNum = 'taptap';
			//取得项目支出总数
			$qyGamePay = $statistics_model->qyGetGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $qyChannel, $qyApkNum, $resertSpecialName);
			//取得综合数据列表
			$qyActiveListData = $statistics_model->qyGetConsumption($game, $qyChannel, $start_date, $end_date, $offset, $row, $qyApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据总数
			$qyActive_total = $statistics_model->qyGetConsumptionTotal($game, $qyChannel, $start_date, $end_date, $qyApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据汇总
			$qyAummary = $statistics_model->qyGetConsumptionSummary($game, $qyChannel, $start_date, $end_date, $qyApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			
			// 清除null数组
			foreach ($qyAummary as $key => $value) {
				foreach ($value as $k => $v) {
					if ($v === null) {
						unset($qyAummary[$key]);
						break;
					}
				}
			}
	
			// 综合数据列表数据格式化
			$qyActive_list = array();
			$qyActive_list = $this->getActiveList($displayMode, $_REQUEST['upperName'], $_REQUEST['upperName'], $_REQUEST['game'], $_REQUEST['years'], $qyActiveListData);
	
			$gamePaySd = array();
			if (!empty($qyGamePay)) {
				foreach ($marketcGamePay as $k => $v) {
					if ($v['id'] == $qyGamePay[$k]['id']) {
						if ($v['upperName']) {
							$gamePaySd[$k]['upperName'] = $v['upperName'];
						}
						if ($v['specialName']) {
							$gamePaySd[$k]['specialName'] = $v['specialName'];
						}
						if ($v['gameName']) {
							$gamePaySd[$k]['gameName'] = $v['gameName'];
						}
						if ($v['gameAlias']) {
							$gamePaySd[$k]['gameAlias'] = $v['gameAlias'];
						}
						if ($v['apkNum']) {
							$gamePaySd[$k]['apkNum'] = $v['apkNum'];
						}
						if ($v['module']) {
							$gamePaySd[$k]['module'] = $v['module'];
						}
						if ($v['type']) {
							$gamePaySd[$k]['type'] = $v['type'];
						}
						if ($v['date']) {
							$gamePaySd[$k]['date'] = $v['date'];
						}
						if ($v['remark']) {
							$gamePaySd[$k]['remark'] = $v['remark'];
						}
						if ($v['pattern']) {
							$gamePaySd[$k]['pattern'] = $v['pattern'];
						}
						$gamePaySd[$k]['pay'] = $v['pay'] + $qyGamePay[$k]['pay'];
					}
				}
			}else {
				$gamePaySd = $marketcGamePay;
			}
	
			// 硬核渠道数据和taptap数据合并
			$active_listSd = array();
			$active_listSd = $this->conditionGetActiveList($displayMode, $marketcActive_list, $qyActive_list, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game']);
	
			// 数据汇总 直接相加即可
			$summarySd = array();
			if ($qyAummary) {
					foreach ($marketcSummary as $k => $v) {
						foreach ($qyAummary as $key => $value) {
							$summarySd[$k]['newUser'] = $v['newUser'] + $value['newUser'];
							$summarySd[$k]['oldUser'] = $v['oldUser'] + $value['oldUser'];
							$summarySd[$k]['activeUser'] = $v['activeUser'] + $value['activeUser'];
							$summarySd[$k]['amount'] = $v['amount'] + $value['amount'];
							$summarySd[$k]['payUser'] = $v['payUser'] + $value['payUser'];
							$summarySd[$k]['newPayUser'] = $v['newPayUser'] + $value['newPayUser'];
							$summarySd[$k]['newAmount'] = $v['newAmount'] + $value['newAmount'];
							$summarySd[$k]['oldPayUser'] = $v['oldPayUser'] + $value['oldPayUser'];
							$summarySd[$k]['oldAmount'] = $v['oldAmount'] + $value['oldAmount'];
						}
				} 
			}else {
				$summarySd = $marketcSummary;
			}
	
			// 统计QuickSDK下的酷派 百度 联想的数据
			$quickChannel = '500005';
			$quickApkNum = "'联想', '百度', '酷派'";
			//取得项目支出总数
			$quickGamePay = $statistics_model->quickGetGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $quickChannel, $quickApkNum, $resertSpecialName);
			//取得综合数据列表
			$quickActiveListData = $statistics_model->quickGetConsumption($game, $quickChannel, $start_date, $end_date, $offset, $row, $quickApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据总记录数
			$quickActive_total = $statistics_model->quickGetConsumptionTotal($game, $quickChannel, $start_date, $end_date, $quickApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
			//取得综合数据汇总
			$quickAummary = $statistics_model->quickGetConsumptionSummary($game, $quickChannel, $start_date, $end_date, $quickApkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
	
			$gamePay = array();
			if (!empty($quickGamePay)) {
				foreach ($gamePaySd as $k => $v) {
					if ($v['id'] == $quickGamePay[$k]['id']) {
						if ($v['upperName']) {
							$gamePay[$k]['upperName'] = $v['upperName'];
						}
						if ($v['specialName']) {
							$gamePay[$k]['specialName'] = $v['specialName'];
						}
						if ($v['gameName']) {
							$gamePay[$k]['gameName'] = $v['gameName'];
						}
						if ($v['gameAlias']) {
							$gamePay[$k]['gameAlias'] = $v['gameAlias'];
						}
						if ($v['apkNum']) {
							$gamePay[$k]['apkNum'] = $v['apkNum'];
						}
						if ($v['module']) {
							$gamePay[$k]['module'] = $v['module'];
						}
						if ($v['type']) {
							$gamePay[$k]['type'] = $v['type'];
						}
						if ($v['date']) {
							$gamePay[$k]['date'] = date('Ym', strtotime($v['date']));
						}
						if ($v['remark']) {
							$gamePay[$k]['remark'] = $v['remark'];
						}
						if ($v['pattern']) {
							$gamePay[$k]['pattern'] = $v['pattern'];
						}
						$gamePay[$k]['pay'] = $v['pay'] + $quickGamePay[$k]['pay'];
					}
				}
			}else {
				$gamePay = $gamePaySd;
				if ($_REQUEST['years']) {
					foreach ($gamePay as $key => $value) {
						if ($value['date']) {
							$gamePay[$key]['date'] = date('Ym', strtotime($value['date']));
						}
					}
				}
			}
	
			// 市场数据指定项目需要按月份展示数据
			if ($_REQUEST['years'] && $_REQUEST['upperName']) {
				$payData = array();
				foreach ($gamePay as $key => $value) {
					if ($value['date']) {
						$payData[$value['date']]['id'] = $value['id'];
						$payData[$value['date']]['upperName'] = $value['upperName'];
						$payData[$value['date']]['specialName'] = $value['specialName'];
						$payData[$value['date']]['gameName'] = $value['gameName'];
						$payData[$value['date']]['gameAlias'] = $value['gameAlias'];
						$payData[$value['date']]['channelId'] = $value['channelId'];
						$payData[$value['date']]['apkNum'] = $value['apkNum'];
						$payData[$value['date']]['module'] = $value['module'];
						$payData[$value['date']]['type'] = $value['type'];
						$payData[$value['date']]['date'] = $value['date'];
						$payData[$value['date']]['remark'] = $value['remark'];
						$payData[$value['date']]['pattern'] = $value['pattern'];
						$payData[$value['date']]['pay'] += $value['pay'];
					}
				}
				if ($payData) {
					// 数据列表
					$gamePay = $payData;
				}
			}
	
			// 综合数据列表数据格式化
			$quickActive_list = array();
			$quickActive_list = $this->getActiveList($displayMode, $_REQUEST['upperName'], $_REQUEST['upperName'], $_REQUEST['game'], $_REQUEST['years'], $quickActiveListData);
	
			// 硬核渠道数据 taptap数据和quickSDK数据三者合并
			$active_list = array();
			$active_list = $this->conditionGetActiveList($displayMode, $quickActive_list, $active_listSd, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game']);
	
			$summary = array();
			if ($quickAummary){
				foreach ($summarySd as $k => $v) {
					foreach ($quickAummary as $key => $value) {
						$summary[$k]['newUser'] = $v['newUser'] + $value['newUser'];
						$summary[$k]['oldUser'] = $v['oldUser'] + $value['oldUser'];
						$summary[$k]['activeUser'] = $v['activeUser'] + $value['activeUser'];
						$summary[$k]['amount'] = $v['amount'] + $value['amount'];
						$summary[$k]['payUser'] = $v['payUser'] + $value['payUser'];
						$summary[$k]['newPayUser'] = $v['newPayUser'] + $value['newPayUser'];
						$summary[$k]['newAmount'] = $v['newAmount'] + $value['newAmount'];
						$summary[$k]['oldPayUser'] = $v['oldPayUser'] + $value['oldPayUser'];
						$summary[$k]['oldAmount'] = $v['oldAmount'] + $value['oldAmount'];
					}
				}
			}else {
				$summary = $summarySd;
			}
	
			// 总记录
			if ($_REQUEST['years'] && ($upperName || $specialName || $game)) {
				// 按月展示 总记录数最多为12
				$active_total = count($active_list);
			}else {
				$active_total = $marketcActive_total;
			}
	
		}else {
		
			// 选择年份
			if ($_REQUEST['years']) {
				$row = 99999999;
			}
	
			//取得项目支出总数
			$gamePay = $statistics_model->getGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $channel, $apkNum, 'consumption');
			//取得综合数据列表
			$active_list = $statistics_model->getConsumption($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $sort, $displayMode, $upperName, $specialName, $source, $status);
			//取得综合数据总记录数
			$active_total = $statistics_model->getConsumptionTotal($game, $channel, $start_date, $end_date, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status);
			//取得综合数据汇总
			$summary = $statistics_model->getConsumptionSummary($game, $channel, $start_date, $end_date, $apkNum, $agentChannel, $sumString, $specialString, $gid, $gameStr, $gsSource, $refine, $displayMode, $upperName, $specialName, $source, $status);
	
		}
	
		if ($gid == 1 || $this->_uid == 'wangyinping' || $gid == 17 || $gid == 23 || $gid == 24) {
			if ($source == 3 && empty($channel)) {
				// 市场数据
				
				// 360、oppo、UC、金立、百度、魅族、小米、应用宝、vivo、华为
				$marketChannel = "'000023', '000020', '000255', '001145', '110000', '000014', '000066', '160136', '000368', '500001'";
				// 取得各游戏的投放支出
				$marketAdPayData = $statistics_model->getAdPay($game, $marketChannel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
				// 取得总投放支出
				$marketSumAdPay = $statistics_model->getSumAdPay($game, $marketChannel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
	
				// 各游戏的投放支出数据格式化
				$marketAdPay = array();
				$marketAdPay = $this->formatAdPayData($displayMode, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game'], $_REQUEST['years'], $marketAdPayData);
				
				// 计算taptap渠道数据
				$qyChannel = '160068';
				$qyApkNum = 'taptap';
	
				// 取得各游戏的投放支出
				$qyAdPayDate = $statistics_model->qyGetAdPay($game, $qyChannel, $start_date, $end_date, $qyApkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
				// 取得总投放支出
				$qySumAdPay = $statistics_model->qyGetSumAdPay($game, $qyChannel, $start_date, $end_date, $qyApkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
	
				// 各游戏的投放支出数据格式化
				$qyAdPay = array();
				$qyAdPay = $this->formatAdPayData($displayMode, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game'], $_REQUEST['years'], $qyAdPayDate);
	
				// 硬核渠道数据和taptap数据合并
				$adPaySd = array();
				$adPaySd = $this->conditionGetAdPayData($displayMode, $qyAdPay, $marketAdPay, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game']);
	
				// 硬核渠道和tabtab的数据汇总综合数据合并
				$sumAdPaySd = array();
				if ($marketSumAdPay && empty($qySumAdPay)) {
					$sumAdPaySd = $marketSumAdPay;
				}elseif ($qySumAdPay && empty($marketSumAdPay)) {
					$sumAdPaySd = $qySumAdPay;
				}elseif ($marketSumAdPay && $qySumAdPay) {
					foreach ($marketSumAdPay as $key => $value) {
						$sumAdPaySd[$key]['adPay'] = $value['adPay'] + $qySumAdPay[$key]['adPay'];
						$sumAdPaySd[$key]['exPay'] = $value['exPay'] + $qySumAdPay[$key]['exPay'];
						$sumAdPaySd[$key]['newlyProfit'] = $value['newlyProfit'] + $qySumAdPay[$key]['newlyProfit'];
					}
				}
				
				// 统计QuickSDK下的酷派 百度 联想的数据
				$quickChannel = '500005';
				$quickApkNum = "'联想', '百度', '酷派'";
				// 取得各游戏的投放支出
				$quickAdPayData = $statistics_model->quickGetAdPay($game, $quickChannel, $start_date, $end_date, $quickApkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
				// 取得总投放支出
				$quickSumAdPay = $statistics_model->quickGetSumAdPay($game, $quickChannel, $start_date, $end_date, $quickApkNum, $refine, $displayMode, $upperName, $specialName, $source, $status, $resertSpecialName);
	
				// 当数组列值为null 则注销数组
				foreach ($quickSumAdPay as $key => $value) {
					if ($value['adPay'] === null && $value['exPay'] === null && $value['newlyProfit'] === null) {
						unset($quickSumAdPay[$key]);
					}
				}
				
				// 各游戏的投放支出数据格式化
				$quickAdPay = array();
				$quickAdPay = $this->formatAdPayData($displayMode, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game'], $_REQUEST['years'], $quickAdPayData);
	
				// 硬核渠道数据和taptap数据合并
				$adPay = array();
				$adPay = $this->conditionGetAdPayData($displayMode, $quickAdPay, $adPaySd, $_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game']);
	
				// 数组倒序
				// $adPay = array_reverse($adPay);
	
				// 硬核渠道 tabtab QuickSDK的数据汇总综合数据合并
				$sumAdPay = array();
				if ($sumAdPaySd && empty($quickSumAdPay)) {
					$sumAdPay = $sumAdPaySd;
				}elseif ($quickSumAdPay && empty($sumAdPaySd)) {
					$sumAdPay = $quickSumAdPay;
				}elseif ($sumAdPaySd && $quickSumAdPay) {
					foreach ($sumAdPaySd as $key => $value) {
						$sumAdPay[$key]['adPay'] = $value['adPay'] + $quickSumAdPay[$key]['adPay'];
						$sumAdPay[$key]['exPay'] = $value['exPay'] + $quickSumAdPay[$key]['exPay'];
						$sumAdPay[$key]['newlyProfit'] = $value['newlyProfit'] + $quickSumAdPay[$key]['newlyProfit'];
					}
				}
			}else {
				// 取得各游戏的投放支出
				$adPay = $statistics_model->getAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status);
				// 取得总投放支出
				$sumAdPay = $statistics_model->getSumAdPay($game, $channel, $start_date, $end_date, $apkNum, $refine, $displayMode, $upperName, $specialName, $source, $status);
			}
	
		}

	
		foreach ($active_list as $key => $value) {
			$active_list[$key]['amount'] = $active_list[$key]['amount'] * $payCharging;
			$active_list[$key]['newAmount'] = $active_list[$key]['newAmount'] * $payCharging;
			$active_list[$key]['newUser'] = $active_list[$key]['newUser'] > $active_list[$key]['activeUser'] ? $active_list[$key]['activeUser'] : $active_list[$key]['newUser'];
			$active_list[$key]['newPayUser'] = $active_list[$key]['newPayUser'] > $active_list[$key]['payUser'] ? $active_list[$key]['payUser'] : $active_list[$key]['newPayUser'];
			$active_list[$key]['newAmount'] = $active_list[$key]['newAmount'] > $active_list[$key]['amount'] ? $active_list[$key]['amount'] : $active_list[$key]['newAmount'];
	
			if ($source != 3) {
				$active_list[$key]['date'] = substr($active_list[$key]['date'], 2, 10);
			}
			
			$active_list[$key]['arpu'] = round($active_list[$key]['amount'] / $active_list[$key]['payUser'], 2);
			$active_list[$key]['arppu'] = round($active_list[$key]['amount'] / $active_list[$key]['activeUser'], 2);
			$active_list[$key]['activeUserRate'] = round($active_list[$key]['payUser'] / $active_list[$key]['activeUser'], 4)*100 .'%';
			$active_list[$key]['newPayArpu'] = round($active_list[$key]['newAmount'] / $active_list[$key]['newPayUser'], 2);
			$active_list[$key]['newUserRate'] = round($active_list[$key]['newPayUser'] / $active_list[$key]['newUser'], 4)*100 .'%';
			$active_list[$key]['oldPayUser'] = $active_list[$key]['payUser'] - $active_list[$key]['newPayUser'];
			$active_list[$key]['oldAmount'] = number_format($active_list[$key]['amount'] - $active_list[$key]['newAmount'], 2);
			$active_list[$key]['oldUserRate'] = round($active_list[$key]['oldPayUser'] / $active_list[$key]['oldUser'], 4)*100 .'%';
			$active_list[$key]['multiple'] = round($active_list[$key]['amount'] / $active_list[$key]['newUser'], 1);
			$active_list[$key]['adPay'] = 0;
	
			if ($refine == 1) {
	
				// 没有选择项目 默认查询全部项目
				if ($source != 1) {
					// 项目支出
					foreach ($gamePay as $ke => $va) {
						if ($va['upperName'] == $value['upperName'] ) {
							$active_list[$key]['adPay'] = $va['pay'];
						}
					}
				}
				foreach ($adPay as $k => $v) {
					if ($v['upperName'] == $value['upperName']) {
						$active_list[$key]['adPay'] = $v['adPay'] + $v['exPay'] + $active_list[$key]['adPay'];
						$active_list[$key]['reRoi'] = $v['newlyProfit'] / $active_list[$key]['adPay'];
					}
				}
			}elseif ($refine == 2) {
				// 选择项目 没有选择专服
				if ($source != 1) {
					$pay = '';
					foreach ($gamePay as $ke1 => $va1) {
						if ($displayMode == 'sum') {
							if ($va1['upperName'] == $value['upperName'] && $va1['date'] == $value['date'] && $va1['module'] != 1) {
								$pay += $va1['pay'];
							}
						}else{
							if ($va1['specialName'] == $value['specialName']) {
								$pay += $va1['pay'];
							}
						}
					}
					$active_list[$key]['adPay'] = $pay;
				}
				foreach ($adPay as $k1 => $v1) {
					$v1['adPay'] = $v1['adPay'] ? $v1['adPay'] : 0;
					$v1['exPay'] = $v1['exPay'] ? $v1['exPay'] : 0;
					$pay = $pay ? $pay : 0;
					if ($displayMode == 'sum') {
						if ($v1['upperName'] == $value['upperName'] && $v1['date'] == $value['date']) {
							$active_list[$key]['adPay'] = $v1['adPay'] + $v1['exPay'] + $pay; // 综合支出 = 投放支出 + 额外支出 + 项目支出
							$active_list[$key]['reRoi'] = $v1['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}else{
						if ($v1['specialName'] == $value['specialName']) {
							$active_list[$key]['adPay'] = $v1['adPay'] + $v1['exPay'] + $pay; // 综合支出 = 投放支出 + 额外支出 + 项目支出
							$active_list[$key]['reRoi'] = $v1['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}
				}
	
			}elseif ($refine == 3) {
				// 选择项目和专服 没有选择指定游戏
				if ($source != 1 && $source != 3) {
					$pay = '';
					foreach ($gamePay as $ke1 => $va1) {
						if ($displayMode == 'sum') {
							if ($va1['specialName'] == $value['specialName'] && $va1['date'] == $value['date']) {
								$pay += $va1['pay'];
							}
						}else{
							if ($va1['gameAlias'] == $value['alias']) {
								$pay += $va1['pay'];
							}
						}
					}
					$active_list[$key]['adPay'] = $pay;
				}
				foreach ($adPay as $k2 => $v2) {
					if ($displayMode == 'sum') {
						if ($v2['specialName'] == $value['specialName'] && $v2['date'] == $value['date']) {
							$active_list[$key]['adPay'] = $v2['adPay'] + $v2['exPay'] + $active_list[$key]['adPay'];
							$active_list[$key]['reRoi'] = $v2['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}else{
						if ($v2['alias'] == $value['alias']) {
							$active_list[$key]['adPay'] = $v2['adPay'] + $v2['exPay'] + $active_list[$key]['adPay'];
							$active_list[$key]['reRoi'] = $v2['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}
				}
	
			}elseif ($refine == 4) {
				// 选择了指定游戏 没有选择指定渠道
				if ($source != 1) {
					$pay = '';
					foreach ($gamePay as $ke1 => $va1) {
						if ($displayMode == 'sum') {
							if ($va1['gameAlias'] == $value['alias'] && $va1['date'] == $value['date']) {
								$pay += $va1['pay'];
							}
						}else{
							if ($va1['gameAlias'] == $value['alias'] && $va1['channelId'] == $value['channelId']) {
								$pay += $va1['pay'];
							}
						}
					}
					$active_list[$key]['adPay'] = $pay;
				}
				foreach ($adPay as $k3 => $v3) {
					$v3['adPay'] = $v3['adPay'] ? $v3['adPay'] : 0;
					$v3['exPay'] = $v3['exPay'] ? $v3['exPay'] : 0;
					$pay = $pay ? $pay : 0;
					if ($displayMode == 'sum') {
						if ($v3['date'] == $value['date']) {
							$active_list[$key]['adPay'] = $v3['adPay'] + $v3['exPay'] + $pay; // 综合支出 = 投放支出 + 额外支出 + 项目支出
							$active_list[$key]['reRoi'] = $v3['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}else{
						if ($v3['channelId'] == $value['channelId']) {
							$active_list[$key]['adPay'] = $v3['adPay'] + $v3['exPay'] + $pay; // 综合支出 = 投放支出 + 额外支出 + 项目支出
							$active_list[$key]['reRoi'] = $v3['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}
				}
			}elseif ($refine == 5) {
				// 选择指定渠道
				if ($source != 1) {
					$pay = '';
					foreach ($gamePay as $ke1 => $va1) {
						if ($displayMode == 'sum') {
							if ($va1['channelId'] == $value['channelId'] && $va1['date'] == $value['date']) {
								$pay += $va1['pay'];
							}
						}else{
							if ($va1['apkNum'] == $value['apkNum']) {
								$pay += $va1['pay'];
							}
						}
					}
					$active_list[$key]['adPay'] = $pay;
				}
				foreach ($adPay as $k4 => $v4) {
					if ($displayMode == 'sum') {
						if ($v4['channelId'] == $value['channelId'] && $v4['date'] == $value['date']) {
							$active_list[$key]['adPay'] = $v4['adPay'] + $v4['exPay'] + $active_list[$key]['adPay'];
							$active_list[$key]['reRoi'] = $v4['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}else{
						if ($v4['apkNum'] == $value['apkNum']) {
							$active_list[$key]['adPay'] = $v4['adPay'] + $v4['exPay'] + $active_list[$key]['adPay'];
							$active_list[$key]['reRoi'] = $v4['newlyProfit'] / $active_list[$key]['adPay'];
						}
					}
				}
			}elseif ($refine == 6) {
				// 只选择包号
				foreach ($adPay as $k5 => $v5) {
					if ($v5['apkNum'] == $value['apkNum'] && $v5['date'] == $value['date']) {
						$active_list[$key]['adPay'] = $v5['adPay'] + $v5['exPay'];
						$active_list[$key]['reRoi'] = $v5['newlyProfit'] / $active_list[$key]['adPay'];
					}
				}
			}
	
			$active_list[$key]['roi'] = round($active_list[$key]['newAmount'] / $active_list[$key]['adPay'], 2);
		}

	
		// 选择年份
		if ($source != 3 && $_REQUEST['years']) {
	
			// 综合支出
			foreach ($adPay as $key => $value) {
				if ($value['date']) {
					$adPay[$key]['date'] = date('Ym', strtotime($value['date']));
				}
			}
	
			$new_adPay = array();
			foreach ($adPay as $key => $value) {
				if ($value['date']) {
					$new_adPay[$value['date']]['upperName'] = $value['upperName'];
					$new_adPay[$value['date']]['adPay'] += $value['adPay'];
					$new_adPay[$value['date']]['exPay'] += $value['exPay'];
					$new_adPay[$value['date']]['newlyProfit'] += $value['newlyProfit'];
					$new_adPay[$value['date']]['date'] = $value['date'];
				}
			}
	
			foreach ($active_list as $key => $value) {
				if ($value['date']) {
					$active_list[$key]['date'] = date('Ym', strtotime($value['date']));
					// 清除数字中的逗号 否则计算的时候逗号字符串后的数值会被忽略掉 被当作字符串 会影响计算结果
					$str = strpos($value['oldAmount'], ',');
					if ($str) {
						$active_list[$key]['oldAmount'] = str_replace(',', '', $value['oldAmount']);
					}
				}
			}
	
			$new_active_list = array();
			foreach ($active_list as $key => $value) {
				if ($value['date']) {
	
					if ($value['upperName']) {
						$new_active_list[$value['date']]['upperName'] = $value['upperName'];
					}
	
					if ($value['specialName']) {
						$new_active_list[$value['date']]['specialName'] = $value['specialName'];
					}
	
					if ($value['name']) {
						$new_active_list[$value['date']]['name'] = $value['name'];
					}
	
					$new_active_list[$value['date']]['date'] = $value['date'];
					$new_active_list[$value['date']]['newUser'] += $value['newUser'];
					$new_active_list[$value['date']]['oldUser'] += $value['oldUser'];
					$new_active_list[$value['date']]['activeUser'] += $value['activeUser'];
					$new_active_list[$value['date']]['amount'] += $value['amount'];
					$new_active_list[$value['date']]['payUser'] += $value['payUser'];
					$new_active_list[$value['date']]['newPayUser'] += $value['newPayUser'];
					$new_active_list[$value['date']]['newAmount'] += $value['newAmount'];
					$new_active_list[$value['date']]['oldPayUser'] += $value['oldPayUser'];
					$new_active_list[$value['date']]['oldAmount'] += $value['oldAmount'];
					$new_active_list[$value['date']]['arpu'] = round($new_active_list[$value['date']]['amount']/$new_active_list[$value['date']]['payUser'], 2);
					$new_active_list[$value['date']]['arppu'] = round($new_active_list[$value['date']]['amount']/$new_active_list[$value['date']]['activeUser'], 2);
					$new_active_list[$value['date']]['activeUserRate'] = round($new_active_list[$value['date']]['payUser']/$new_active_list[$value['date']]['activeUser'], 4)*100 .'%';
					$new_active_list[$value['date']]['newPayArpu'] = round($new_active_list[$value['date']]['newAmount']/$new_active_list[$value['date']]['newPayUser'], 2);
					$new_active_list[$value['date']]['newUserRate'] = round($new_active_list[$value['date']]['newPayUser']/$new_active_list[$value['date']]['newUser'], 4)*100 .'%';;
					$new_active_list[$value['date']]['oldUserRate'] = round($new_active_list[$value['date']]['oldPayUser']/$new_active_list[$value['date']]['oldUser'], 4)*100 .'%';
					$new_active_list[$value['date']]['multiple'] = round($new_active_list[$value['date']]['amount']/$new_active_list[$value['date']]['newUser'], 1);;
					$new_active_list[$value['date']]['adPay'] += $value['adPay'];
					$new_active_list[$value['date']]['roi'] = round($new_active_list[$value['date']]['newAmount']/$new_active_list[$value['date']]['adPay'], 2);
				}
			}
	
			// 实收ROI = 新增用户付费利润/综合支出
			foreach ($new_active_list as $key => $value) {
				foreach ($new_adPay as $k => $v) {
					$new_active_list[$key]['reRoi'] = round($new_adPay[$key]['newlyProfit']/$new_active_list[$key]['adPay'], 2);
				}
			}
		
		}
		
		if ($new_active_list) {
			$active_list = $new_active_list;
		}
	
		// 计算项目支出
		if ($source != 1) {
			foreach ($gamePay as $ke => $va) {
				if ($refine == 1) {
					$sumGamePay += $va['pay'];
				}elseif ($refine == 2) {
					if ($displayMode == 'sum') {
						$sumGamePay += $va['pay'];
					}else{
						if ($va['module'] == 3) {
							$sumGamePay += $va['pay'];
						}
					}
				}elseif ($refine == 3) {
					if ($va['module'] == 3 && $va['specialName'] == $specialName) {
						$sumGamePay += $va['pay'];
					}
				}elseif ($refine == 4) {
					if ($va['gameAlias'] == $game) {
						$sumGamePay += $va['pay'];
					}
				}elseif ($refine == 5) {
					if ($va['channelId'] == $channel) {
						$sumGamePay += $va['pay'];
					}
				}elseif ($refine == 6) {
					if ($va['apkNum'] == $apkNum) {
						$sumGamePay += $va['pay'];
					}
				}
			}
		}
	
		foreach ($summary as $key1 => $value1) {
			$summary[$key1]['amount'] = $summary[$key1]['amount'] * $payCharging;
			$summary[$key1]['newAmount'] = $summary[$key1]['newAmount'] * $payCharging;
			$summary[$key1]['arpu'] = round($summary[$key1]['amount'] / $summary[$key1]['payUser'], 2);
			$summary[$key1]['arppu'] = round($summary[$key1]['amount'] / $summary[$key1]['activeUser'], 2);
			$summary[$key1]['activeUserRate'] = round($summary[$key1]['payUser'] / $summary[$key1]['activeUser'], 4)*100 .'%';
			$summary[$key1]['newPayArpu'] = round($summary[$key1]['newAmount'] / $summary[$key1]['newPayUser'], 2);
			$summary[$key1]['newUserRate'] = round($summary[$key1]['newPayUser'] / $summary[$key1]['newUser'], 4)*100 .'%';
			$summary[$key1]['oldPayUser'] = $summary[$key1]['payUser'] - $summary[$key1]['newPayUser'];
			$summary[$key1]['oldAmount'] = number_format($summary[$key1]['amount'] - $summary[$key1]['newAmount'], 2);
			$summary[$key1]['oldUserRate'] = round($summary[$key1]['oldPayUser'] / $summary[$key1]['oldUser'], 4)*100 .'%';
			$summary[$key1]['multiple'] = round($summary[$key1]['amount'] / $summary[$key1]['newUser'], 1);
			if ($status != 2) {
				$summary[$key1]['adPay'] = $sumAdPay[0]['adPay'] + $sumAdPay[0]['exPay'] + $sumGamePay; // 综合支出 = 投放支出 + 额外支出 + 项目支出
			}
			$summary[$key1]['roi'] = round($summary[$key1]['newAmount'] / $summary[$key1]['adPay'], 2);
			$summary[$key1]['reRoi'] = round($sumAdPay[0]['newlyProfit'] / $summary[$key1]['adPay'], 2);
	
		}
	
		// 总记录数
		if ($_REQUEST['years'] && ($upperName || $specialName || $game)) {
			// 按月展示 总记录数最多为12
			$active_total = count($active_list);
		}
	
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
	
		// 针对市场数据处理分页
		// 因为市场数据是分三次去统计不同渠道数据，导致分页失效, 解决方法：去掉sql语句limit条件 ，根据数组键值去分页，后期数据量多的话不能使用这个方法
		if ($source == 3 && empty($channel) && empty($apkNum)) {
			$active_list = array_values($active_list);
			$activeListCount = count($active_list);
	
			$starTip = ($page - 1) * $row;
			$endTip = $starTip + $row;
			// 限制endTip最大值
			if ($endTip > $activeListCount) {
				$endTip = $activeListCount;
			}
	
			$new_active_list = array();
			for ($i = $offset; $i < $endTip; $i++) {
				$new_active_list[] = $active_list[$i];
			}
	
			$active_list = $new_active_list;
		}

		//GS负责游戏
		$gsChannel  = require(APP_LIST_PATH . "oss/config.gsChannel.php");
		foreach ($active_list as $kr => $vr) {
			if (!empty($gsChannel[$vr['upperName']])) {
				$active_list[$kr]['gsChannelName'] = $gsChannel[$vr['upperName']];
			}
		}

		//限制权限
		$ability_group = array(1,23,24);
		
		//导出
		$this->assign('ability_group',$ability_group);
		if ($_REQUEST['report'] == 'report') {
			
			if (!in_array($gid,$ability_group)) {
				ShowMsg('对不起，您无权限执行该功能', '-1');
				exit;
			}

			$reports = array();
			foreach ($active_list as $keyr => $valuer) {

				($refine != 1 && $displayMode == 'sum') || $refine == 6 ? $reports[$keyr]['date'] = $valuer['date'] : '';
				

				if( $displayMode == 'details' && $refine == 1){
					$reports[$keyr]['upperName'] = $valuer['upperName'];
					$reports[$keyr]['specialName'] = $valuer['specialName'];
				}else{
					if ($valuer['upperName']) {
						$reports[$keyr]['upperName'] = $valuer['upperName'];
					}else if ($valuer['name']) {
						$reports[$keyr]['upperName'] = $valuer['name'];
					}else if ($valuer['specialName']) {
						$reports[$keyr]['upperName'] = $valuer['specialName'];
					}
				}
				($refine >= 4 && $displayMode != 'sum') || ($refine >= 5 && $displayMode == 'sum') ? $reports[$keyr]['channelName'] = $valuer['channelName'] : '';
				($refine >= 5 && $displayMode != 'sum') || ($refine == 6) ? $reports[$keyr]['apkNum'] = $valuer['apkNum'] : '';
				
				$reports[$keyr]['newUser'] = $valuer['newUser'];
				$reports[$keyr]['oldUser'] = $valuer['oldUser'];
				$reports[$keyr]['activeUser'] = $valuer['activeUser'];
				$reports[$keyr]['amount'] = $valuer['amount'];
				$reports[$keyr]['multiple'] = $valuer['multiple'];
				$reports[$keyr]['payUser'] = $valuer['payUser'];
				$reports[$keyr]['arpu'] = $valuer['arpu'];
				$reports[$keyr]['arppu'] = $valuer['arppu'];
				$reports[$keyr]['activeUserRate'] = $valuer['activeUserRate'];
				$reports[$keyr]['newPayUser'] = $valuer['newPayUser'];
				$reports[$keyr]['newAmount'] = $valuer['newAmount'];
				$reports[$keyr]['newPayArpu'] = $valuer['newPayArpu'];
				$reports[$keyr]['newUserRate'] = $valuer['newUserRate'];
				$reports[$keyr]['oldPayUser'] = $valuer['oldPayUser'];
				$reports[$keyr]['oldAmount'] = $valuer['oldAmount'];
				$reports[$keyr]['oldUserRate'] = $valuer['oldUserRate'];
				$reports[$keyr]['adPay'] = $valuer['adPay'];
				$reports[$keyr]['roi'] = $valuer['roi'];
				$reports[$keyr]['averageMount'] = round($valuer['adPay'] / $valuer['newUser'],2);
				$reports[$keyr]['reRoi'] = $valuer['reRoi'] ? round($valuer['reRoi'],2) : 0;
				$reports[$keyr]['chargeA'] = round($valuer['adPay'] / $valuer['newPayUser'],2);
				$reports[$keyr]['Collection'] = round($valuer['amount'] / $valuer['adPay'],2);
				//实际回款率
				if($valuer['amount'] && $valuer['adPay']){
					if ($channel == '000368' || $channel == '000020') {
						$reports[$keyr]['actualAdPay'] = round(($valuer['amount'] / $valuer['adPay']) * 0.62 ,2);
					}else if ($channel == '500001') {
						$reports[$keyr]['actualAdPay'] = round(($valuer['amount'] / $valuer['adPay']) * 0.5 ,2);
					}else if ($channel == '000066') {
						$reports[$keyr]['actualAdPay'] = round(($valuer['amount'] / $valuer['adPay']) * 0.74 ,2);
					}else if ($channel == '500011') {
						$reports[$keyr]['actualAdPay'] = round(($valuer['amount'] / $valuer['adPay']) * 0.7 ,2);
					}
				}else{
					$reports[$keyr]['actualAdPay'] = 0;
				}
			}

			$sdate = $start_date ? date("Ymd",strtotime($start_date)) : date("Y-m-d",time());
			$edate = $end_date ? date("Ymd",strtotime($end_date)): date("Y-m-d",time());

			$reports_title = array('游戏');

			if( $displayMode == 'details' && $refine == 1){
				array_push($reports_title,'专服名称');
			}
			
			($refine != 1 && $displayMode == 'sum') || $refine == 6 ? array_unshift($reports_title,'日期') : '';

			if (($refine >= 4 && $displayMode != 'sum') || ($refine >= 5 && $displayMode == 'sum')) {
				array_push($reports_title,('渠道'));
			}
			if (($refine >= 5 && $displayMode != 'sum') || ($refine == 6)) {
				array_push($reports_title,('包号'));
			}
			array_push($reports_title,'新用户', '老用户', '活跃用户', '付费金额', '倍数', '付费人数', 'ARPPU','ARPU', '活跃付费率', '新用户付费人数', '新用户付费金额', '新用户ARPPU', '新用户付费率', '老用户付费人数', '老用户付费金额','老用户付费率','综合支出','ROI','平均单价','实收ROI','付费A值','回款率');

			$channel ? array_push($reports_title,'实际回款率') : '';

			excel_export("《爱游就游-中央数据后台》玩家综合数据_{$sdate}_{$edate}", $reports_title, $reports);
			exit;
		}

		$this->assign('committe_apknum', $committe_apknum);
		$this->assign('start_date', $start_date);
		$this->assign('end_date', $end_date);
		$this->assign('channel', $channel);
		$this->assign('game', $game);
		$this->assign('active', $active_list);
		$this->assign('list_total', $active_total);
		$this->assign('apkNum', $apkNum);
		$this->assign('summary', $summary);
		$this->assign('gsSource', $gsSource);
		$this->assign('sourceType', $sourceType);
		$this->assign('header_id',explode(',', $header_data[0]['header_id']));
		$this->assign('data_jution',$data_jution);
	}

	// 充值金額走勢
	public function platformRecharge() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}

		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		if (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22 ) && ($gidarr[0]['game'] != 'all')) {
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

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

	// 充值人數走勢
	public function platformRechargePeopleNumber() {
		$this->checkLogin();

		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) && ($gidarr[0]['game'] != 'all') ) {
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getChannelRechargePeopleNumber" 		:
					$this->getChannelRechargePeopleNumber();
					break;
			}
		}
		$this->setInitTime();
	}

	// 註冊人數走勢
	public function platformRegist() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

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

	// 獲取充值金額數據
	public function getPlatformRecharge() {
		$this->checkLogin();
		//$this->getRecharge('platform');
		$this->getChannel('platform', '充值金额', 'recharge');
	}

	// 獲取充值人數數據
	public function getChannelRechargePeopleNumber() {
		$this->checkLogin();
		$this->getChannel('platform', '充值人数', 'people_number');
	}

	// 獲取註冊人數數據
	public function getplatformRegist() {
		$this->checkLogin();
		//$this->getRegist('platform');
		$this->getChannel('platform', '人数', 'regist');
	}

	

	// 獲取註冊人數、充值金额數據
	public function getChannel($type, $title, $category) {

		// 检查登录
		$this->checkLogin();

		// header头定义前后端交互数据格式为json
		header('Content-Type: application/json');

		// 默认时间为本月
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));

		// 起始时间
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;

		switch ($type) {
			//　平台 拼接where语句
			case 'platform':

				$channelSql = '';

				$game = trim($_REQUEST['game']);
				if($game){
					$channelSql .= " AND gameAlias = '". $game ."'";
				}

				//角色组
				$channel_model = getInstance('model.sdkChannel.channel');
				$gidarr = $channel_model->returnUidGroup($this->_uid);
				$gid = intval($gidarr[0]['gid']);
				
				$payCharging = 1;
				if ($gid == 13) {//扣量处理
					$payCharging = !empty($gidarr[0]['payCharging']) ? $gidarr[0]['payCharging'] : 1;
					if ($start< date('Y-m-d', $gidarr[0]['limitTime'])) {
						$start = date('Y-m-d', $gidarr[0]['limitTime']);
					}
				}
				if ($gid == 8) {
					$channelSql .= " AND gameAlias = '". $gidarr[0]['game'] ."'";
				}elseif ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 || $gid == 22 || $gid == 15) {
					if ($gidarr[0]['game'] != 'all') {
						$explode = explode('|', $gidarr[0]['game']);
						$gameStr = '';
						foreach ($explode as $k => $v) {
							$gameStr .= "'" . $v . "',";
						}
						$gameStr = substr($gameStr,0,-1);
						$channelSql .=" AND gameAlias IN (" . $gameStr . ") ";
					}
				}
				$channel = trim($_REQUEST['channel']);
				if($channel){
					$channelSql .= " AND channelId = '". $channel ."'";
				}else{
					if (!empty($gidarr[0]['channelId'])) {
						$channelSql .= " AND channelId IN (" . $gidarr[0]['channelId'] . ")";
					}
				}
				$apkNum = trim($_REQUEST['apkNum']);
				if($apkNum){
					$channelSql .= " AND apkNum = '". $apkNum ."'";
				}
				$yjApkNum = trim($_REQUEST['yjApkNum']);
				if($yjApkNum){
					$channelSql .= " AND apkNum = '". $yjApkNum ."'";
				}
				//取得一级游戏数据
				$upperName = trim($_REQUEST['upperName']);
				$specialName = trim($_REQUEST['specialName']);
				if ($upperName && empty($_REQUEST['game'])) {
					$game_model = getInstance('model.sdkGame.game');
					$summary = $game_model->getGameName($upperName, '', $gameStr);
					$sum = array();
					foreach ($summary as $key => $value) {
						$sum[] = "'" . $value['alias'] . "'";
					}
					$sumString = implode(',', $sum);
					$string =" AND gameAlias IN (" . $sumString . ") ";
					//取得专服游戏数据
					if ($specialName) {
						$specialSummary = $game_model->getGameName($upperName, $specialName, $gameStr);
						$specialSum = array();
						foreach ($specialSummary as $key => $value) {
							$specialSum[] = "'" . $value['alias'] . "'";
						}
						$specialString = implode(',', $specialSum);
						$string =" AND gameAlias IN (" . $specialString . ") ";
					}
					$channelSql .= $string;
				}

				$groupSql   = '';

				break;
		}

		// 判断起始时间是否为一天
		$date  	= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) < 1	? 	'%Y-%m-%d-%H' 	: '%Y-%m-%d';
		$pdate	= intval((strtotime($end.' '.'23:59:59') - strtotime($start)) * 1/ (3600 * 24)) < 1	? 	'Y-m-d-H' 		: 'Y-m-d';

		// 判断起始时间是否为一年
		if (substr($start, 5) == '01-01' && substr($end, 5) == '12-31' && (substr($start, 0, 4) == substr($end, 0 ,4)) && $channelSql) {
			$date   = '%Y-%m';
		}

		// 查询时间为一天
		$hourSign  = '';
		if ($start == $end) {
			$gDate = '%Y-%m-%d %H'; // 查询时间为小时
			$hourSign  = ':00'; // 拼接小时
		}
		else {
			$gDate = '%Y-%m-%d';
		}

		switch ($category) {
			// 充值金額走势
			case 'recharge' :
				$sql 	 = "SELECT `channelId` AS `channel`, ROUND(SUM(`money`) * ".$payCharging.",2) AS `total`,
                                            FROM_UNIXTIME(`time`, '%c') AS `month`,
                                            `roleId`, `time` AS `date`,
                                            FROM_UNIXTIME(`time`, '$gDate') AS `day`
                                            FROM `ms_order`
                                            WHERE `orderStatus` = 1
                                            AND `time` BETWEEN ".strtotime($start)." AND ".strtotime($end.' '.'23:59:59').'
                                            '.$channelSql
				." GROUP BY ".$groupSql." FROM_UNIXTIME(`time`,'$date')
                                            ORDER BY `time`";

				break;
			// 註冊人數走势
			case 'regist' :
				$sql 	= "SELECT `channelId` AS `channel`, `userName`, `jointime` AS `date`, FROM_UNIXTIME(`jointime`, '%Y') AS `year`,FROM_UNIXTIME(`jointime`, '%c') AS `month`, FROM_UNIXTIME(`jointime`, '$gDate') AS `day`, COUNT(1) AS `total`
							FROM `ms_member`
							WHERE `jointime` BETWEEN ". strtotime($start) . ' AND '. strtotime($end.' '.'23:59:59') . $channelSql
				." GROUP BY ". $groupSql ." FROM_UNIXTIME(`jointime`,'$date')
				  	 	 	ORDER BY `jointime`";
							
				break;
			// 充值人数走势
			case 'people_number':
				$sql	= " SELECT count(1) AS total, s.time AS date, FROM_UNIXTIME(s.time, '$gDate') AS day
                                        FROM ( SELECT time, FROM_UNIXTIME(time, '$date') as ftime
                                                    FROM `ms_order`
                                                    WHERE `orderStatus` =1 AND `time` >=". strtotime($start) .'
                                                    AND `time`<='. strtotime($end.' '.'23:59:59').' '.$channelSql
				." GROUP BY ".$groupSql." ftime, roleId) s
                                                    GROUP BY ftime;
						";

				break;
			// 新增充值走势
			case 'newUserRecharge':
				$sql	= " SELECT SUM(newAmount) AS total, UNIX_TIMESTAMP(date) AS date, date AS day FROM ms_integrated_daily WHERE date >= '".$start."' AND date <= '".$end."'".$channelSql ."GROUP BY date";

				break;
			default:
				break;
		}

		$users  = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			$dateType	= 'datetime';
			$dateFormat = '%d';
			$dateLength = 3600 * 1000 * 1 * 24 * 1; // 用毫秒为计时单位的一天时间，1秒 = 1000毫秒
			$dateTip	= '%Y-%m-%d';
			$diff = intval( (strtotime($end.' '.'23:59:59') - strtotime($start) ) * 1 / (3600 * 24) );

			switch ($type) {
				case 'platform':
					if ($category == 'recharge' || $category == 'newUserRecharge') {
						// 如果是充值金額走势，或是新增充值走势
						$yName = '元';  // 设置鼠标浮动提示数据格式
						$tName = '金额'; // 设置纵坐标名称
					}
					else {
						// 如果是註冊人數走势，或是充值人数走势
						$yName = '人';  // 设置鼠标浮动提示数据格式
						$tName = '人数'; // 设置纵坐标名称
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
					$page = new page(count($tData), 32);
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

	// 初始化本月開始結束時間
	public function setInitTime() {
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$this->assign('bef_time', $bef_time);
		$this->assign('ent_time', $ent_time);
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

	// 獲取註冊人數、充值金額比例數據
	public function getProportion($type, $div) {
		$this->checkLogin();
		header('Content-Type: application/json');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());

		$where = " 1 ";
		$game = $_REQUEST['game'];
		$channel = $_REQUEST['channel'];
		$apkNum = $_REQUEST['apkNum'];
		$yjApkNum = $_REQUEST['yjApkNum'];
		$province = $_REQUEST['province'];
		$groupArea = 'province';
		$upperName = $_REQUEST['upperName'];
		$specialName = $_REQUEST['specialName'];

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$where .= " AND gameAlias = '". $gidarr[0]['game'] ."'";
		}else{
			if($game){
				if ($type == 'gameRechargeData') {
					$where .= " AND o.gameAlias= '" . $game ."'";
				}else{
					$where .= " AND gameAlias= '" . $game ."'";
				}
			}
		}
		if($channel){
			$where .= " AND channelId= '" . $channel ."'";
		}
		if($apkNum){
			$where .= " AND apkNum= '" . $apkNum ."'";
		}
		if($yjApkNum){
			$where .= " AND apkNum= '" . $yjApkNum ."'";
		}
		if($province){
			$where .= " AND province= '" . $province ."'";
			$groupArea = 'city';
		}
		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {
			$game_model = getInstance('model.sdkGame.game');
			$summary = $game_model->getGameName($upperName);
			$sum = array();
			foreach ($summary as $key => $value) {
				$sum[] = "'" . $value['alias'] . "'";
			}
			$sumString = implode(',', $sum);
			//$string =" AND gameAlias IN (" . $sumString . ") ";
			if ($type == 'gameRechargeData') {
				$string =" AND o.gameAlias IN (" . $sumString . ") ";
			}else{
				$string =" AND gameAlias IN (" . $sumString . ") ";
			}
			//取得专服游戏数据
			if ($specialName) {
				$specialSummary = $game_model->getGameName($upperName, $specialName);
				$specialSum = array();
				foreach ($specialSummary as $key => $value) {
					$specialSum[] = "'" . $value['alias'] . "'";
				}
				$specialString = implode(',', $specialSum);
				//$string =" AND gameAlias IN (" . $specialString . ") ";
				if ($type == 'gameRechargeData') {
					$string =" AND o.gameAlias IN (" . $specialString . ") ";
				}else{
					$string =" AND gameAlias IN (" . $specialString . ") ";
				}
			}
			$where .= $string;
		}

		switch ($type) {
			case 'areaRegist' :
				$sql = "SELECT `province`, `city`, COUNT(`userName`) AS `total`
						FROM `ms_member`
						WHERE ". $where ." AND `jointime` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')
				." GROUP BY ". $groupArea ."
					     ORDER BY COUNT(`userName`)";
				break;
			case 'channelRegist' :
				$sql = "SELECT `channelId`, `channelName`, COUNT(`userName`) AS `total`
						FROM `ms_member`
						WHERE ". $where ." AND `jointime` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')
				." GROUP BY channelId
					     ORDER BY total";
				break;
			case 'phoneRegist' :
				require_once APP_LIST_PATH . 'oss/phone.config.php';
				$phonesKey = array_keys($phones);
				foreach ($phonesKey as $key => $value) {
					$phonesKey[$key] = "'" . $value . "'";
				}
				$phoneString = implode(',',$phonesKey);

				$sql = "SELECT `phoneModel`, COUNT(`imei`) AS `total`
						FROM `ms_init`
						WHERE ". $where ." AND `time` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')
				." GROUP BY phoneModel HAVING phoneModel  IN(".$phoneString.")  ORDER BY COUNT(`imei`)";
				break;
			case 'gameRechargeData' :
				if (!empty($upperName) && !empty($specialName) && !empty($game)) {
					$groupData = 'o.channelId';
				}elseif (empty($upperName) && empty($specialName) && empty($game)) {
					$groupData = 'g.upperName';
				}elseif (!empty($upperName) && empty($specialName) && empty($game)) {
					$groupData = 'g.specialName';
				}elseif (!empty($upperName) && !empty($specialName) && empty($game)) {
					$groupData = 'o.gameAlias';
				}
				/*$sql = "SELECT SUM(`money`) AS total, `gameAlias`, gameName, channelId, channelName FROM ms_order WHERE " . $where . " AND `orderStatus` = 1 AND `time` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')
				." GROUP BY `" . $groupData . "` ORDER BY total";*/
				$sql = "SELECT SUM(o.`money`) AS total, o.`gameAlias`, o.`gameName`, o.`channelId`, o.`channelName`, g.`upperName`, g.`specialName` FROM ms_order AS o LEFT JOIN ms_game AS g ON o.`gameAlias` = g.`alias` WHERE " . $where . " AND o.`orderStatus` = 1 AND o.`time`  BETWEEN ". strtotime($start) ." AND ". strtotime($end.'23:59:59')." GROUP BY " . $groupData . " ORDER BY total";
				break;
			default:break;
		}
		$users = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			$sum = 0;
			if ($type == 'areaRegist') {
				$num = count($users);
				for ($i=0; $i<$num; $i++){
					if($province){
						$users[$i]['typeName'] = $users[$i]['city'];
					}else{
						$users[$i]['typeName'] = $users[$i]['province'];
					}
					if (empty($users[$i]['typeName'])) {
						$users[$i]['typeName'] = '未知';
					}
				}
			}elseif ($type == 'channelRegist') {
				$num = count($users);
				for ($i=0; $i<$num; $i++){
					$users[$i]['typeName'] = $users[$i]['channelName'];
					if (empty($users[$i]['typeName'])) {
						$users[$i]['typeName'] = '未知';
					}
				}
			}elseif ($type == 'phoneRegist') {
				$sql = "SELECT `phoneModel`, COUNT(`imei`) AS `total`
						FROM `ms_init`
						WHERE ". $where ." AND `time` BETWEEN ". strtotime($start) .' AND '. strtotime($end.' '.'23:59:59')." AND phoneModel NOT IN(".$phoneString.")";
				$other = model::getBySql($sql);
				$users = array_merge($users,$other);
				foreach($users as $val){
					$key_arrays[]=$val['total'];
				}
				array_multisort($key_arrays,SORT_ASC,SORT_NUMERIC,$users);
				require_once APP_LIST_PATH . 'oss/phone.config.php';
				$num = count($users);
				for ($i=0; $i<$num; $i++){
					$users[$i]['typeName'] = $phones[$users[$i]['phoneModel']];
					if (empty($users[$i]['typeName'])) {
						$users[$i]['typeName'] = '未知';
					}
				}
			}elseif ($type == 'gameRechargeData') {
				$num = count($users);
				for ($i=0; $i<$num; $i++){
					if (!empty($upperName) && !empty($specialName) && !empty($game)) {
						if ($users[$i]['channelName'] != '') {
							$users[$i]['typeName'] = $users[$i]['channelName'];
						}else {
							$users[$i]['typeName'] = $users[$i]['channelId'];
						}
					}elseif (empty($upperName) && empty($specialName) && empty($game)) {
						$users[$i]['typeName'] = $users[$i]['upperName'];
						if (empty($users[$i]['typeName'])) {
							$users[$i]['typeName'] = '未知';
						}
					}elseif (!empty($upperName) && empty($specialName) && empty($game)) {
						$users[$i]['typeName'] = $users[$i]['specialName'];
						if (empty($users[$i]['typeName'])) {
							$users[$i]['typeName'] = '未知';
						}
					}else {
						if ($users[$i]['gameName'] != '') {
							$users[$i]['typeName'] = $users[$i]['gameName'];
						}else {
							$users[$i]['typeName'] = $users[$i]['gameAlias'];
						}
					}
				}
			}
			foreach($users as $key=> $val){
				if(isset($data[$val['typeName']])) {
					$data[$val['typeName']][1] += intval($val['total']);
				}
				$data[$val['typeName']] = array($val['typeName'], intval($val['total']));
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

	/**
	 * 註冊人數比例（地区）
	 */
	public function areaRegistPie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$member_model = new Model('ms_member');
		//取出所有游戏
		$area_list = $member_model->getBySql("SELECT province FROM ms_member WHERE province !='' GROUP BY province");
		foreach ($area_list as $key => $value) {
			$area_row[$key] = $value['province'];
		}
		$this->assign('area_row', $area_row);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getAreaRegistPie" 	:
					$this->getAreaRegistPie();
					break;
			}
		}
		$this->setInitTime();
	}

	/**
	 * 註冊人數比例（渠道）
	 */
	public function channelRegistPie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8 || $gid == 15) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		//获取上级游戏名
		$statistics_model = getInstance('model.statistics');
		if (($gid == 17 || $gid == 15) && $gidarr[0]['game'] != 'all') {
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);

		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getChannelRegistPie" 	:
					$this->getChannelRegistPie();
					break;
			}
		}
		$this->setInitTime();
	}


	// 獲取註冊人數比例數據（地区）
	public function getAreaRegistPie() {
		$this->checkLogin();
		$this->getProportion('areaRegist', 'areaPie');
	}

	// 獲取註冊人數比例數據（渠道）
	public function getChannelRegistPie() {
		$this->checkLogin();
		$this->getProportion('channelRegist', 'channelPie');
	}

	/**
	 * 註冊人數比例（手机品牌）
	 */
	public function phoneRegistPie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		$statistics_model = getInstance('model.statistics');
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getPhoneRegistPie" 	:
					$this->getPhoneRegistPie();
					break;
			}
		}
		$this->setInitTime();
	}

	// 獲取註冊人數比例數據（手机品牌）
	public function getPhoneRegistPie() {
		$this->checkLogin();
		$this->getProportion('phoneRegist', 'phonePie');
	}

	/**
     * ltv生命周期总价值
     */
	public function lifeTimeValue() {
		@session_start();

		//判断是gid
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);

		$channel = $_REQUEST['channel'];
		$this->assign('channel', $channel);

		$statistics_model = getInstance('model.statistics');
		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}
		if ($gid == 8 || $gid == 13) {
			$agentChannel = $gidarr[0]['channelId'];
		}
		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

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

		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
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

		if ($_POST) {
			$role_model = new Model('ms_role_seted');
			set_time_limit(180);

			$operation_list = array('list', 'report');
			$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'list';
			$this->assign('operation', $operation);
			$payCharging = 1;
			if ($gid == 13) {//扣量处理
				$payCharging = !empty($gidarr[0]['payCharging']) ? $gidarr[0]['payCharging'] : 1;
				if ($_POST['start_date'] < date('Y-m-d', $gidarr[0]['limitTime'])) {
					$_POST['start_date'] = date('Y-m-d', $gidarr[0]['limitTime']);
				}
			}
			$start_time = strtotime($_POST['start_date']);
			$end_time = strtotime($_POST['end_date']) > TIME ? TIME : strtotime($_POST['end_date']);
			$game = $_POST['game'];
			$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
			if($_REQUEST['apkNum']){
				$apkNum = trim($_REQUEST['apkNum']);
			}elseif ($_REQUEST['yjApkNum']) {
				$apkNum = trim($_REQUEST['yjApkNum']);
			}

			$date_list = $this->_getDateList($start_time, $end_time);
			$dayCount = count($this->_getDateList($start_time, TIME));

			krsort($date_list);

			//显示列表要处理分页
			if ($operation == 'list') {
				require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
				$this->assign('channels', $channels);
				$this->assign('channel', $channel);
				$this->assign('apkNum', $apkNum);
				$this->assign('start_date', $_POST['start_date']);
				$this->assign('end_date', $_POST['end_date']);
				$this->assign('game', $game);
				$limit = 100;

				$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
				$offset = ($page - 1) * $limit;

				$this->assign('page', $page);
				$this->assign('limit', $limit);
				$this->assign('total', count($date_list));
				$date_list = array_splice($date_list, $offset, $limit);
			}
			$data = array();
			$data = array();

			$where = ' 1 ';
			if ($gid == 8) {
				$where .= " AND s.`gameAlias`='" . $gidarr[0]['game'] . "' ";
			}elseif (($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22 ) && $gidarr[0]['game'] != 'all' && !$game) {
				$where .= " AND s.`gameAlias` IN (" . $gameStr . ") ";
			}else {
				$where .= ($game) ? " AND s.`gameAlias`='$game' " : "";
			}

			if($sumString){
				$string = " AND s.`gameAlias` IN (" . $sumString . ") ";
				if($specialString){
					$string = " AND s.`gameAlias` IN (" . $specialString . ") ";
				}
				$where .= $string;
			}
			if ($channel) {
				$where .= " AND s.`channelId` = '" . $channel . "' ";
			}
			if ($apkNum) {
				$where .= " AND s.`apkNum` = '" . $apkNum . "' ";
			}

			//每列汇总
			$total_data = array();
			$dataReport = array();

			$order_model = new Model('ms_order');
			//要计算次日LTV、三日LTV、四日LTV、五日LTV、六日LTV、七日LTV、双周LTV、月LTV、双月LTV、三月LTV。
			$dayList = array(0, 1, 2, 3, 4, 5, 6, 13, 29, 59, 89);
			foreach ($date_list as $date) {
				$_start = strtotime($date);
				$_end = strtotime($date . ' 23:59:59');
				$table = 'ms_role_seted';

				$regList = $role_model->getBySql("SELECT count(1) AS total FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1");

				/*$reg = '';
				foreach ($regList as $key => $value) {
					$reg[] = "'" . $value['roleId'] . "'";
				}
				$regString = implode(',', $reg);*/

				//$orderSum = $order_model->getBySql("SELECT SUM(money) AS total, FROM_UNIXTIME(s.`time`, '%Y-%m-%d') AS day FROM ms_order s WHERE $where AND $_start <= s.`time` AND s.`roleId` IN($regString) AND s.`orderStatus` = 1 GROUP BY FROM_UNIXTIME(s.`time`, '%Y-%m-%d')");

				$orderSum = $order_model->getBySql("SELECT SUM(s.`money`) AS total, FROM_UNIXTIME(s.`time`, '%Y-%m-%d') AS day FROM ms_order s RIGHT JOIN (SELECT `roleId`, `userName`, `gameAlias`, `channelId` FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1) r ON s.`roleId` = r.`roleId` AND s.`userName` = r.`userName` AND s.`gameAlias` = r.`gameAlias` AND s.`channelId` = r.`channelId` WHERE $where AND $_start <= s.`time` AND s.`orderStatus` = 1 GROUP BY FROM_UNIXTIME(s.`time`, '%Y-%m-%d')");
				/*var_dump("SELECT count(1) AS total FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1");
var_dump("SELECT SUM(s.`money`) AS total, FROM_UNIXTIME(s.`time`, '%Y-%m-%d') AS day FROM ms_order s RIGHT JOIN (SELECT `roleId`, `userName`, `gameAlias`, `channelId` FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1) r ON s.`roleId` = r.`roleId` AND s.`userName` = r.`userName` AND s.`gameAlias` = r.`gameAlias` AND s.`channelId` = r.`channelId` WHERE $where AND $_start <= s.`time` AND s.`orderStatus` = 1 GROUP BY FROM_UNIXTIME(s.`time`, '%Y-%m-%d')");exit;*/
				/*$regRows = $role_model->getBySql("SELECT count(1) as regTotal FROM ms_role_seted s WHERE $where AND $_start <= s.`time` AND s.`time` <= $_end AND s.`type` = 'server' AND s.`isFirst` = 1");
				$regTotal = $regRows ? intval($regRows[0]['regTotal']) : 0;*/
				//$regTotal = count($regList);
				$regTotal = $regList ? intval($regList[0]['total']) : 0;
				$dataList = array(
				'date' => $date, 'total' => $regTotal
				);
				$dataListReport = $dataList;
				//每列汇总-1
				//$total_data[0] += $regTotal;
				$sumRegTotal += $regTotal;

				$money = $indeep = $notindeep = 0;
				foreach ($orderSum as $kr => $vr) {
					$orderSum[$kr]['total'] = $vr['total'] * $payCharging;
				}
				foreach ($orderSum as $k => $v) {
					$money += $v['total'];
					$orderSum[$k]['money'] = $money;
					$orderSum[$k]['ltv'] = Number_format($money/$regTotal, 2);
				}
				$endLtv = end($orderSum);

				foreach ($dayList as $key => $value) {
					if($this->deepInArray(date('Y-m-d', $_start + $value * 86400), $orderSum)){
						foreach ($orderSum as $key1 => $value1) {
							if ($value1['day'] == date('Y-m-d', $_start + $value * 86400)) {
								$dataList['admix_' . $value] = $value1['money'] . '/<span style="color: red">' . $value1['ltv'] . '</span>';
								$dataList['money_' . $value] = $value1['money'];
								$dataListReport['ltv_' . $value] = $value1['ltv'];

								//每列汇总-N
								$total_data[$value] += $value1['money'];
							}
						}
					}elseif($value == 1 && empty($dataList['admix_1']) && !empty($regTotal) && !empty($orderSum['0']['total'])){
						$dataList['admix_1'] = intval($orderSum['0']['total']) . '/<span style="color: red">' . Number_format($orderSum['0']['total']/$regTotal, 2) . '</span>';
						$dataList['money_1'] = intval($orderSum['0']['total']);
						$dataListReport['ltv_1'] = Number_format($orderSum['0']['total']/$regTotal, 2);
						$total_data[$value] += intval($orderSum['0']['total']);
					}else{
						//$strss = explode('/<', $dataList['admix_' . $dayList[$key - 1]]);
						/*$dataList['admix_' . $value] = $dataList['admix_' . $dayList[$key - 1]];// . ' ' . $strss[0];
						$dataList['money_' . $value] = $dataList['money_' . $dayList[$key - 1]];
						$dataListReport['ltv_' . $value] = $dataListReport['ltv_' . $dayList[$key - 1]];*/
						if (!empty($endLtv['money'])) {
							$dataList['admix_' . $value] = $endLtv['money'] . '/<span style="color: red">' . $endLtv['ltv'] . '</span>';;// . ' ' . $strss[0];
							$dataList['money_' . $value] = $endLtv['money'];
							$dataListReport['ltv_' . $value] = $endLtv['ltv'];
						}
						if ($dayCount > $value) {
							$total_data[$value] += $dataList['money_' . $dayList[$key]];
						}else{
							$total_data[$value] += 0;
						}
					}
				}

				//当日期要过那个时间，才显示，比如今日是看不到次日的数据
				//这种情况设定为空
				foreach ($dayList as $day) {
					if ((TIME - $_start < 86400 * $day)) {
						$dataList['admix_' . $day]  = '';
					}
				}
				$data[] = $dataList;
				$dataReport[] = $dataListReport;
			}

			if ($operation === 'report') {
				foreach ($total_data as $key => $value) {
					$total_data[$key] = round($value/$sumRegTotal, 2);
				}
				array_unshift($total_data,"数据汇总",$sumRegTotal);
				$dataReport[] = $total_data; 
				$sdate = date('Ymd', $start_time);
				$edate = date('Ymd', $end_time);
				excel_export("用户LTV统计表_{$sdate}_{$edate}", array(
				'日期', '新增用户数', '一日LTV', '二日LTV', '三日LTV', '四日LTV', '五日LTV', '六日LTV', '七日LTV', '双周LTV', '月LTV','双月LTV','三月LTV'
				), $dataReport);
				exit;
			} else {
				$this->assign('data', $data);
				$this->assign('total_data', $total_data);	
				$this->assign('count_data', count($data));	
				$this->assign('sumRegTotal', $sumRegTotal);
			}
		} else {
			$date = date('Y-m-d', TIME);
			$this->assign('start_date', $date);
			$this->assign('end_date', $date);
		}
	}

	public function deepInArray($value, $array) {
		foreach($array as $item) {
			if(!is_array($item)) {
				if ($item == $value) {
					return true;
				} else {
					continue;
				}
			}

			if(in_array($value, $item)) {
				return true;
			} else if($this->deepInArray($value, $item)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 游戏充值比例
	 */
	public function gameRechargeDataPie() {
		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}
		$this->assign('games', $game_row);

		$statistics_model = getInstance('model.statistics');

		//获取上级游戏名
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getGameRechargeDataPie" 	:
					$this->getGameRechargeDataPie();
					break;
			}
		}
		$this->setInitTime();
	}

	// 获取游戏充值比例数据
	public function getGameRechargeDataPie() {
		$this->checkLogin();
		$this->getProportion('gameRechargeData', 'gameDataPie');
	}

	/**
	 * 充值排行榜
	 * 
	 * 此功能仅限乾游内部使用，禁止外放
	 */
	public function paidList(){
		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');

		//渠道列表
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$start_date = $_REQUEST['start_date'] ? strtotime($_REQUEST['start_date']): "";
		$end_date = $_REQUEST['end_date'] ? strtotime($_REQUEST['end_date'] . '23:59:59') : "";
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		$this->assign('game_config', loadC('config.inc.php', 'main'));

		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14){
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		//游戏列表
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}

		//获取上级游戏名
		if ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14) {
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
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
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
		$check = 0;
		if ($sumString || $specialString ||$game) {
			//取得列表数据
			$summary = $statistics_model->paidList($sumString, $specialString, $game, $channel, $apkNum, $start_date, $end_date, $gid, $gameStr);
			$check = 1;
		}
		
		//模板赋值
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('summary', $summary);
		$this->assign('check', $check);
	}

	/**
	 * 根据渠道获取包名
	 */
	public function getApkNum() {
		$apkNum = '';
		if (empty($_POST['pvc'])) {
			if (!empty($_POST['channelId'])) {
				if($_POST['channelId'] != '111111' && $_POST['channelId'] != '500005' && $_POST['channelId'] != '500009' && $_POST['channelId'] != '500015'){
					$committe_list = array(
						'0' => array('apkNum' => '主包'),
						'1' => array('apkNum' => '分包1'),
						'2' => array('apkNum' => '分包2'),
						'3' => array('apkNum' => '分包3'),
						'4' => array('apkNum' => '分包4'),
						'5' => array('apkNum' => '分包5'),
						'6' => array('apkNum' => '分包6'),
						'7' => array('apkNum' => '分包7'),
						'8' => array('apkNum' => '分包8'),
						'9' => array('apkNum' => '分包9'),
						'10' => array('apkNum' => '分包10'),
						'11' => array('apkNum' => '分包11'),
						'12' => array('apkNum' => '分包12'),
						'13' => array('apkNum' => '分包13'),
						'14' => array('apkNum' => '分包14'),
						'15' => array('apkNum' => '分包15'),
						'16' => array('apkNum' => '分包16'),
						'17' => array('apkNum' => '分包17'),
						'18' => array('apkNum' => '分包18'),
						'19' => array('apkNum' => '分包19'),
						'20' => array('apkNum' => '分包20'),
						'21' => array('apkNum' => '分包21'),
						'22' => array('apkNum' => '分包22'),
						'23' => array('apkNum' => '分包23'),
						'24' => array('apkNum' => '分包24'),
						'25' => array('apkNum' => '分包25'),
						'26' => array('apkNum' => '分包26'),
						'27' => array('apkNum' => '分包27'),
						'28' => array('apkNum' => '分包28'),
						'29' => array('apkNum' => '分包29'),
						'30' => array('apkNum' => '分包30'),
						'31' => array('apkNum' => 'wx_Android'),
						'32' => array('apkNum' => 'wx_IOS'),
						);
						if($_POST['channelId'] == '160068' || $_POST['channelId'] == '500010'){
							$committe_list['100001'] = array('apkNum' => 'IOS');
							$committe_list['100002'] = array('apkNum' => '耀非');
							$committe_list['100003'] = array('apkNum' => '炎游');
							$committe_list['100004'] = array('apkNum' => '南宇');
							$committe_list['100005'] = array('apkNum' => 'taptap');
							$committe_list['100006'] = array('apkNum' => '心玩');
							$committe_list['100007'] = array('apkNum' => '外放');
						}
				}else{
					$statistics_model = getInstance('model.statistics');
					$committe_list = $statistics_model->getCommittee(0, 1000, $_POST['channelId'], $_POST['game']);
				}
				if ($committe_list) {
					$apkNum .= '<option value="">请选择</option>';
					foreach ($committe_list as $key => $value){
						$apkNumList = '';
						if ($value['apkNum']) {
							$apkNumList = (trim($_POST['apkNum']) == $value['apkNum']) ? 'selected="selected"' : '';
							$apkNum .= '<option value="' . $value['apkNum'] . '"' . $apkNumList . '>' . $value['apkNum'] . '</option>';
						}
					}
				}
			}

			if (empty($apkNum)) {
				$apkNum = '<option value="">无相关数据</option>';
			}
		}else{
			$apkNum .= '<option value="">请选择</option>';
			$apkNumArray = array('主包', '耀非', '炎游', '南宇', '游戏fan', 'TT语音', 'taptap', '果盘', '小七手游', '乐嗨嗨', '九妖游戏', '233手游', '天宇游', '3011游戏', '当乐', '联想', '坚果游玩', '奇点');
			foreach ($apkNumArray as $k => $v) {
				$selected = (trim($_POST['apkNum']) == $v) ? 'selected="selected"' : '';
				$apkNum .= '<option value="'.$v.'"'.$selected.'>'.$v.'</option>';
			}
		}
		echo $apkNum;exit;
	}

	/**
     * 角色列表
     */
	public function roleInfo() {
		@session_start();

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);

		//取出所有channel
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$this->assign('userName', $userName);

		$roleName = trim($_REQUEST['roleName']) ? trim($_REQUEST['roleName']) : "";
		$this->assign('roleName', $roleName);
		$roleId = trim($_REQUEST['roleId']) ? trim($_REQUEST['roleId']) : "";
		$this->assign('roleId', $roleId);
		//时间范围
		$start_date = trim($_REQUEST['start_date']) ? trim($_REQUEST['start_date']) : "";
		$end_date = trim($_REQUEST['end_date']) ? trim($_REQUEST['end_date']) : "";
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";//渠道
		$apkNum = trim($_REQUEST['apkNum']) ? trim($_REQUEST['apkNum']) : "";//包号
		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('game', $game);

		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
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
		//考虑服务器性能损耗，一次导出最多导出1000条
		if($_POST['operation'] === 'report') {
			$page = 1;
			$row = 1000;
			$offset = 0;
		}else {
			//查询数据
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$row = 25;
			$offset = ($page - 1) * $row;
		}

		$statistics_model = getInstance('model.statistics');

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
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

		$role_list = $statistics_model->getRoleInfo($offset, $row, $game, $channel, $userName, $roleName, $apkNum, $sumString, $specialString, $gameStr, $roleId, $start_date, $end_date);
		if($_POST['operation'] != 'report'){
			$total_row = $statistics_model->getRoleInfoTotal($game, $channel, $userName, $roleName, $apkNum, $sumString, $specialString, $gameStr, $roleId, $start_date, $end_date);
		}else{
			$total_row = 0;
		}
		$this->assign('list_total', $total_row);
		if ($start_date || $end_date) {
			$create = 0;
		}else{
			$create = 1;
		}
		foreach ($role_list as $key => $val) {
			$roleTime = $statistics_model->getRoleMTime($val['gameAlias'], $val['roleId'], $val['userName'], $create);
			if ($create == 1) {
				$role_list[$key]['roleMTime'] = $roleTime;
			}else{
				$role_list[$key]['roleMTime'] = $val['time'];
				$role_list[$key]['time'] = $roleTime;
			}
			$role_list[$key]['rolePayTime'] = $statistics_model->getRolePayTime($val['gameAlias'], $val['roleId'], $val['userName']);
			foreach ($game_list as $k => $v) {
				if ($val['gameAlias'] == $v['alias']) {
					$role_list[$key]['upperName'] = $v['upperName'];
				}
			}
		}
		if($_POST['operation'] === 'report') {
			$reports = array();
			foreach ($role_list as $keyr => $valuer) {
				$reports[$keyr]['userName'] = $valuer['userName'];
				$reports[$keyr]['upperName'] = $valuer['upperName'];
				$reports[$keyr]['gameName'] = $valuer['gameName'];
				$reports[$keyr]['channelName'] = $valuer['channelName'];
				$reports[$keyr]['serverId'] = $valuer['serverId'];
				$reports[$keyr]['roleName'] = $valuer['roleName'];
				$reports[$keyr]['roleId'] = $valuer['roleId'];
				$reports[$keyr]['apkNum'] = $valuer['apkNum'];
				$reports[$keyr]['time'] = date('Y-m-d', $valuer['time']);
				$reports[$keyr]['roleMTime'] = date('Y-m-d', $valuer['roleMTime']);
				$reports[$keyr]['rolePayTime'] = $valuer['rolePayTime'] ? date('Y-m-d', $valuer['rolePayTime']) : '-';
			}

			excel_export("《爱游就游-中央数据后台》角色列表", array(
			'账号', '上级游戏名', '游戏', '渠道', '区服', '角色名称', '角色ID', '包号', '创角时间', '最近登录时间', '最后点击充值'
			), $reports);
			exit;
		}
		$array = array(
		'list_page' => $page,
		'list_length' => $row,
		'gid' => $gid,
		'role_list' => is_array($role_list) ? $role_list : array()
		);
		$this->assign($array);

		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
	}

	/**
	 * 获取用户全部角色信息
	 */
	public function getUserRole() {
		$sql = "SELECT gameName, serverId, roleName, roleId FROM ms_role_seted WHERE userName = '" . $_POST['userName'] . "' AND isFirst = 1";
		$result = Model::getBySql($sql);
		$ecc = "";
		foreach ($result as $key => $value) {
			$ecc .= "游戏：" . $value['gameName'] . "；区服：" . $value['serverId'] . "；角色名：" . $value['roleName'] . "；角色ID：" . $value['roleId'] . "；||";
		}
		echo $ecc;exit;
	}

	/**
	 * 处理补单
	 * 
	 * 补单机制：不管之前订单支付是否成功，都直接更新支付状态为1，然后根据订单信息请求CP发货接口
	 */
	public function orderReplace() {
		$uid_list = array('luojiang', 'chenjh', 'heyongzhen');
		if (!in_array($this->_uid, $uid_list)) {
			ShowMsg('您没有相关权限 ', '/index.php?m=statistics&a=order');
		}
		$order_model = new model('ms_order');
		$order = $order_model->get("`orderId`='{$_GET['orderId']}'");
		if (!$order) {
			ShowMsg('订单信息有误 ', '/index.php?m=statistics&a=order');
		}
		if ($order['paymentId'] == 104) {
			$sendStatus = 2;
		}else{
			$sendStatus = $order['sendStatus'];
		}

		// 更新订单支付状态为1
		$order_model->set(array(
		'orderStatus' => 1,
		'replace' => $this->_uid,
		'sendStatus' => $sendStatus
		), '`orderId`=%s', array($order['orderId']));

		//先清除失败记录以便重新发起回调
		$model = new model('ms_order_failure');
        $model->delete("oid = '{$order['orderId']}'");

		//通知游服发货
		if ($order['paymentId'] == 104) {
			$sdkChannel = 'mGameSDK' . $value['channelId'];
			load('@main.model.api.' . $sdkChannel);
			$channel = new $sdkChannel();
			$success = $channel->requestPlfOrder($order);
		}else{
			$this->_user_model = getInstance('@main.model.libs.mGameSDKUser');
			$success = $this->_user_model->requestGame($order);	
		}

		//更改订单状态
		if($success) {
			$order_model->set(array(
			'sendStatus' => 1
			), '`orderId`=%s', array($order['orderId']));
		}
		ShowMsg('操作成功', '/index.php?m=statistics&a=order');
	}

	/**
	 * ajax获取乾游总充值
	 */
	public function platformRechargeTotal() {
		$upperName = $_REQUEST['upperName'];
		$specialName = $_REQUEST['specialName'];
		$game = $_REQUEST['game'];
		$end_time = strtotime($_REQUEST['end_date'] . '23:59:59');
		$start_time = strtotime($_REQUEST['start_date']);
		//取得一级游戏数据
		if ($upperName && empty($game)) {
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
		$statistics_model = getInstance('model.statistics');
		$recharge_total = $statistics_model->platformRechargeTotal($game, $start_time, $end_time, $sumString, $specialString);

		if (!empty($recharge_total[0]['total'])) {
			$total = $recharge_total[0]['total'];
		}else{
			$total = 0.00;
		}
		echo $total;exit;
	}

	/**
	 * GS内服数据统计
	 */
	public function gsDataSummary() {
		$statistics_model = getInstance('model.statistics');

		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		$gameStr = '';
		if($gid == 11 || $gid == 17){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		//获取上级游戏名
		if (($gid == 11 || ($gid == 17 && !empty($gameStr)) && ( $gidarr[0]['game'] != 'all'))) {
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
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

		$upperName = $_REQUEST['upperName'];
		$specialName = $_REQUEST['specialName'];
		$game = $_REQUEST['game'];
		$end_time = strtotime($_REQUEST['end_date'] . '23:59:59');
		$start_time = strtotime($_REQUEST['start_date']);
		$server = $_REQUEST['server'];
		$this->assign('game', $game);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('server', $server);
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);
		//取得一级游戏数据
		if ($upperName && empty($game)) {
			$game_model = getInstance('model.sdkGame.game');

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
			}
		}

		if ($_REQUEST['upperName']) {
			$operation_list = array('list');
			$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'list';
			$this->assign('operation', $operation);
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$this->assign('list_page', $page);
			$row = 25;
			$offset = ($page - 1) * $row;
			$this->assign('list_length', $row);
			$serverData = $game_model->getServerList('', '', $upperName, $specialName);
			$serverList = array();

			$gsData = $statistics_model->getGsData($offset, $row, $sumString, $specialString, $game, $server, $start_time, $end_time);
			$gsDataTotal = $statistics_model->getGsDataTotal($sumString, $specialString, $game, $server, $start_time, $end_time);

			foreach ($gsData as $k1 => $v1) {
				$gsData[$k1]['serverStr']  = $v1['oldServer'];
				foreach ($game_list as $k2 => $v2) {
					if ($v2['alias'] == $v1['gameAlias']) {
						$gsData[$k1]['upperName'] = $v2['upperName'];
						$gsData[$k1]['specialName'] = $v2['specialName'];
					}
				}
				foreach ($serverData as $k3 => $v3) {
					if ($v1['oldServer'] == $v3['cpServerNum']) {
						$gsData[$k1]['serverStr'] = $v3['gameServerNum'] .'('.$v3['cpServerNum'].')';
					}
				}
			}

			$total = 0;
			$num = 0;
			foreach ($gsDataTotal[0] as $key => $value) {
				$total += $value['payTotal'];
				foreach ($serverData as $key2 => $value2) {
					if ($value['oldServer'] == $value2['cpServerNum']) {
						$gsDataMon[$value2['gameServerNum']] += $value['payTotal'];
						if ($value2['reference'] == 1) {
							$gsDataMon['allPay'] += $value['payTotal'];
						}
					}
				}
			}
			foreach ($serverData as $key3 => $value3) {
				foreach ($gsDataMon as $key4 => $value4) {
					if ($key4 == $value3['gameServerNum']) {
						if ($value3['reference'] == 1) {
							$num++;
							$gsDataMon[$key4] = '<font style="color: red">' . $value4 . '</font>';
						}else{
							$gsDataMon[$key4] = '<font style="color: green">' . $value4 . '</font>';
						}
					}
				}
			}
			$arpu = round($gsDataMon['allPay'] /$num, 2);
			unset($gsDataMon['allPay']);
			$this->assign('gsDataMon', $gsDataMon);
			$this->assign('arpu', $arpu);
			$this->assign('game', $game);
			$this->assign('server', $server);
			$this->assign('gsData', $gsData);
			$this->assign('allTotal', $total);
			$this->assign('list_total', $gsDataTotal[1]);
			$this->assign('serverData', $serverData);
		}
	}

	/**
	 * 储值排行（90天）
	 * 
	 * 此功能仅限乾游内部使用，禁止外放
	 */
	public function paidRanking(){
		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');

		//渠道列表
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$start_date = $_REQUEST['start_date'] ? strtotime($_REQUEST['start_date']): "";
		$end_date = $_REQUEST['end_date'] ? strtotime($_REQUEST['end_date'] . '23:59:59') : "";
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";

		//上限500条
		if ($_REQUEST['ranking'] > 500) {
			$ranking = 100;
		}else{
			$ranking = $_REQUEST['ranking'] ? $_REQUEST['ranking'] : "";
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		$this->assign('game_config', loadC('config.inc.php', 'main'));

		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
		}else {
			$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		}

		//游戏列表
		$game_list = $game_model->getList();
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		if($_REQUEST['apkNum']){
			$apkNum = trim($_REQUEST['apkNum']);
		}elseif ($_REQUEST['yjApkNum']) {
			$apkNum = trim($_REQUEST['yjApkNum']);
		}

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
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

		//取得一级游戏数据
		if ($upperName && empty($_REQUEST['game'])) {

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
			//取得专服游戏数据
			if ($specialName) {

				if($gid == 17){
					$specialSummary = $game_model->getGameName($upperName, $specialName, $gameStr);
				}else {
					$specialSummary = $game_model->getGameName($upperName, $specialName);
				}

				$specialSum = array();
				foreach ($specialSummary as $key => $value) {
					$specialSum[] = "'" . $value['alias'] . "'";
				}
				$specialString = implode(',', $specialSum);
			}
		}
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		$check = 0;
		if ($sumString || $specialString || $game || $channel) {
			//取得列表数据
			$summary = $statistics_model->paidList2($sumString, $specialString, $game, $channel, $apkNum, $start_date, $end_date, $gid, $gameStr, $ranking);
			$check = 1;
		}
		
		//模板赋值
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('summary', $summary);
		$this->assign('check', $check);
		$this->assign('ranking', $ranking);
	}

		/**
	 * 获取游戏利润信息
	 */
	public function profit() {
		$statistics_model = getInstance('model.statistics');
		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";
		$apkNum = $_REQUEST['apkNum'] ? $_REQUEST['apkNum'] : "";
		$type = $_REQUEST['type'] ? $_REQUEST['type'] : "";
		$source = $_REQUEST['source'] ? $_REQUEST['source'] : "";
		$status = $_REQUEST['status'] ? $_REQUEST['status'] : "1";
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		// 年份筛选条件
		$yearArray = array();
		$currentYear = date('Y', time());
		for ($i = 2020; $i <= $currentYear; $i++) { 
			$yearArray[] = $i;
		}
		$this->assign('yearArray', $yearArray);

		// 快捷方式选择时间
		if ($_REQUEST['years']) {
			$_REQUEST['start_date'] = $_REQUEST['years'].'-01-01';
			$_REQUEST['end_date'] = $_REQUEST['years'].'-12-31';
			$this->assign('years', $_REQUEST['years']);
		}
		$start_date = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : "";
		$end_date = $_REQUEST['end_date'] ? $_REQUEST['end_date'] : "";

		// 获取当前角色组关联游戏
		$gameStr = '';
		if ($gid == 17 || $gid == 15){
			if ($gidarr[0]['game'] != 'all') {
				$explode = explode('|', $gidarr[0]['game']);
				foreach ($explode as $k => $v) {
					$gameStr .= "'" . $v . "',";
				}
				$gameStr = substr($gameStr,0,-1);
				$this->assign('gameStr', $gameStr);
			}
		}

		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		
		
		//用户权限区分
		$checkRoot = '';
		$uid_list1 = array('luojiang', 'root', 'guojian', 'yangzhenwei', 'chenjh', 'caiwu', 'heyongzhen','guofengchi');
		$uid_list2 = array();
		$uid_list3 = array('wangyinping');
		if (in_array($this->_uid, $uid_list1) || $gid == 17 || $gid == 23 || $gid == 24) {
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

		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		$this->assign('list_page', $page);
		if ( $_REQUEST['years'] ) {
			$row = 99999;
		} else {
			$row = 25;
		}
		$offset = ($page - 1) * $row;
		$this->assign('list_length', $row);

		//获取上级游戏名
		if (($gid == 17 || $gid == 15 )&& !empty($gameStr)  && ( $gidarr[0]['game'] != 'all')) {
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
				if ($gid == 17 || $gid == 15) {
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
				if ($gid == 17 || $gid == 15) {
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

		//权限
		$header_model = getInstance('model.statistics');
		$data_jution = $header_model->getHeader();
		$header_data = $header_model->getJurisdiction($gidarr[0]['uid']);
        $this->assign('header_id',explode(',', $header_data[0]['header_id']));

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

		if ($game) {
			$gameStr = '';
		}
		$this->assign('refine', $refine);
		$profitList = $statistics_model->getProfit($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr, $_REQUEST['operation']);
		
		$total = $statistics_model->getProfitTotal($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr);
		$gamePay = $statistics_model->getGamePay($start_date, $end_date, $refine, $upperName, $specialName, $game, $channel, $apkNum, 'profit', $gameStr);
		$summary = $statistics_model->getProfitSummary($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr);

		if ($refine == 1) {
			foreach ($profitList as $key => $value) {
				$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
				$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
				foreach ($gamePay as $key1 => $value1) {
					if ($value['date'] == $value1['date']) {
						if ($value1['module'] == 2) {
							$profitList[$key]['gamePay'] = $value1['pay'];
						}elseif ($value1['module'] == 3) {
							$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];
						}elseif ($value1['module'] == 1) {
							$profitList[$key]['coPay'] = $value1['pay'];
						}
					}
				}
				$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'] - $profitList[$key]['gamePay'] - $profitList[$key]['coPay'], 2);
				$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
				/*$sumPay += $profitList[$key]['gamePay'];
				$sumExPay += $profitList[$key]['exPay'];*/
			}
			if ($source != 1) {
				foreach ($gamePay as $keys => $values) {
					if ($values['module'] == 2) {
						$sumPay += $values['pay'];
					}elseif ($values['module'] == 3) {
						$sumExPay += $values['pay'];
					}elseif ($values['module'] ==1) {
						$sumCoPay += $values['pay'];
					}
				}
			}
			foreach ($summary as $k => $v) {
				$summary[$k]['exPay'] = $v['exPay'] + $sumExPay;
				$summary[$k]['coPay'] = $sumCoPay;
				$summary[$k]['final'] = round($v['profit'] - $v['exPay'] - $v['actualPay'] + $v['income'] - $sumPay - $sumExPay - $sumCoPay, 2);
				$summary[$k]['pay'] = $sumPay;
				$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';
			}
		}elseif ($refine == 2) {
			$sumPay = '';
			$gamePayinit =  $gamePay;
			foreach ($profitList as $key => $value) {
				$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
				$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
				foreach ($gamePay as $key1 => $value1) {
					if ($value['date'] == $value1['date']) {
						if ($value1['module'] == 2) {
							//$profitList[$key]['gamePay'] = $value1['pay'];
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
				$summary[$k]['exPay'] = $v['exPay'] + $sumExPay; // 额外支出
				$summary[$k]['final'] = round($v['profit'] - $v['exPay'] - $v['actualPay'] + $v['income'] - $sumPay - $sumExPay, 2);
				$summary[$k]['pay'] = $sumPay; // 投放支出
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
				$summary[$k]['final'] = round($v['profit'] - $summary[$k]['exPay'] - $v['actualPay'] + $v['income'], 2);
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

				// $exPay += $value['exPay']; // 额外支出
				// $adPay += $value['adPay']; // 投放支出
				// $actualPay += $value['actualPay']; // 实际投放支出

			}

			// $summary[0]['exPay'] = $exPay;
			// $summary[0]['adPay'] = $adPay;
			// $summary[0]['actualPay'] = $actualPay;

			foreach ($summary as $k => $v) {
				// 净利润 = 分成后流水 - 额外支出 - 实际投放支出 + GS收入
				$summary[$k]['final'] = round($v['profit'] - $v['exPay'] - $v['actualPay'] + $v['income'], 2);
				// 利润率 = 净利润 / 游戏流水
				$summary[$k]['profitMargin'] = round($summary[$k]['final'] / $v['amount'], 4)*100 .'%';
			}

		}elseif ($refine == 5) {
			$sumPay = '';
			/*var_dump($gamePay);
			var_dump($profitList);exit;*/
			foreach ($profitList as $key => $value) {
				$profitList[$key]['cpRate'] = round($value['cpAmount'] / $value['amount'], 4)*100 .'%';;
				$profitList[$key]['channelRate'] = round($value['channelAmount'] / $value['amount'], 4)*100 .'%';
				foreach ($gamePay as $key1 => $value1) {
					if ($value['upperName'] == $value1['upperName']) {
						if ($value1['module'] == 2) {
							$profitList[$key]['pay'] += $value1['pay'];
						}elseif ($value1['module'] == 3) {
							$profitList[$key]['exPay'] = $value['exPay'] + $value1['pay'];
						}
						
						//$profitList[$key]['remark'] = $value1['remark'];
						//$gamePay[$key1]['pay'] = '';
					}
				}
				$profitList[$key]['final'] = round($value['profit'] - $profitList[$key]['exPay'] - $value['actualPay'] + $value['income'] - $profitList[$key]['pay'], 2);
				$profitList[$key]['profitMargin'] = round($profitList[$key]['final'] / $value['amount'], 4)*100 .'%';
				//$sumPay += $profitList[$key]['pay'];
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
				$summary[$k]['final'] = round($v['profit'] - $summary[$k]['exPay'] - $v['actualPay'] + $v['income'] - $sumPay, 2);
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

		// 按月展示数据
		if ( $_REQUEST['years'] && ( $_REQUEST['upperName'] || $_REQUEST['specialName'] || $_REQUEST['game'] ) ) {
			// 日期处理为月份
			foreach ($profitList as $key => $value) {
				$profitList[$key]['date'] = date('Y-m', strtotime($value['date']));
			}
			
			// 按月区分数据
			$dateProfitList = array();
			foreach ($profitList as $key => $value) {
				$dateProfitList[$value['date']][] = $value;
			}

			$monthProfitList = array();
			foreach ($dateProfitList as $key => $value) {
				foreach ($value as $k => $v) {
					if ( $v['channelId'] ) {
						$monthProfitList[$key]['channelId'] = $v['channelId']; 
					}

					if ( $v['gameAlias'] ) {
						$monthProfitList[$key]['gameAlias'] = $v['gameAlias']; 
					}

					if ( $v['gameName'] ) {
						$monthProfitList[$key]['gameName'] = $v['gameName']; 
					}

					if ( $v['specialName'] ) {
						$monthProfitList[$key]['specialName'] = $_REQUEST['upperName']; // 项目名称
					}

					if ( $v['name'] ) {
						$monthProfitList[$key]['name'] = $_REQUEST['specialName']; // 专服名称
					}

					$monthProfitList[$key]['amount'] += $v['amount']; // 游戏总流水
					$monthProfitList[$key]['cpAmount'] += $v['cpAmount']; // cp分成流水
					$monthProfitList[$key]['channelAmount'] += $v['channelAmount']; // 渠道分成流水
					$monthProfitList[$key]['profit'] += $v['profit']; // 分成后流水
					$monthProfitList[$key]['adPay'] += $v['adPay']; // 投放支出
					$monthProfitList[$key]['exPay'] += $v['exPay']; // 额外支出
					$monthProfitList[$key]['disAmount'] += $v['disAmount']; // 真实充值流水
					$monthProfitList[$key]['date'] = $v['date']; // 日期
					$monthProfitList[$key]['actualPay'] += $v['actualPay']; // 实际支出
					$monthProfitList[$key]['income'] += $v['income']; // GS收入
					$monthProfitList[$key]['cpRate'] = $v['cpRate']; // 同个项目的cp分成比例是一样的
					$monthProfitList[$key]['channelRate'] = round( ( ( $monthProfitList[$key]['channelAmount'] / $monthProfitList[$key]['amount'] ) * 100 ) , 2 ). '%'; // 渠道分成比例 = 渠道分成流水 / 游戏总流水
					$monthProfitList[$key]['pay'] += $v['pay']; // 项目支出
					$monthProfitList[$key]['final'] += $v['final']; // 净利润
					$monthProfitList[$key]['profitMargin'] = round( ( ( $monthProfitList[$key]['final'] / $monthProfitList[$key]['amount'] ) * 100 ) , 2 ). '%'; // 利润率 = 净利润 / 游戏总流水
				}
			}

			$profitList = $monthProfitList;
			// 总记录条数
			$total = count($profitList);
		}

		// 导出数据
		if( ($gid == 1 || $gid == 20 || $gid == 24) && $_REQUEST['operation'] == 'report'){

			$reports = array();
			foreach($profitList as $key => $value){
				$reports[$key]['date'] = date('Ym', strtotime($value['date']. '00:00:00'));
				$reports[$key]['upperName'] = $value['upperName'];
				$reports[$key]['specialName'] = $value['specialName'];
				$reports[$key]['name'] = $value['name'];
				$reports[$key]['amount'] = $value['amount'];
				$reports[$key]['disAmount'] = $value['disAmount'];
				$reports[$key]['cpAmount'] = $value['cpAmount'];
				$reports[$key]['channelAmount'] = $value['channelAmount'];
				$reports[$key]['profit'] = $value['profit'];
				$reports[$key]['exPay'] = $value['exPay'];
				$reports[$key]['income'] = $value['income'];
				$reports[$key]['pay'] = $value['pay'];
				$reports[$key]['channelName'] = $value['channelName'];
				$reports[$key]['apkNum'] = $value['apkNum'];
				$reports[$key]['adPay'] = $value['adPay'];
				$reports[$key]['actualPay'] = $value['actualPay'];
				$reports[$key]['final'] = $value['final'] + $value['pay'];
				$reports[$key]['profitMargin'] = round(($value['final'] + $value['pay'] ) / $value['amount'], 4)*100 .'%';
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
			
			excel_export("《爱游就游-中央数据后台》流水分成列表_{$date}", array(
			'账期', '项目','专服', '游戏','游戏流水','真实充值','cp分成','渠道支出','分成后流水','额外支出','gs收入','项目支出', '渠道', '包号', '投放支出', '实际投放支出', '净利润', '利润率'
			), $reportsArray);
			exit;
		}
		$this->assign('list_total', $total);
		$this->assign('page', $page);
		$this->assign('list_length', $row);
		$this->assign('profitList', $profitList);
		$this->assign('summary', $summary);
	}

	/**
	 * 修改额外支出信息
	 */
	public function exPayChange() {
		$mode = new model('ms_profit_daily');
		$data = $mode->get('`id`=%s', array($_POST['id']));
		if (!$data) {
			$array['status'] = "1001";
			echo json_encode($array);exit;//没有对应的数据
		}
		if (isset($_POST['exPay'])) {
			$success = $mode->set(array('exPay' => $_POST['exPay']), '`id`=%s', array($_POST['id']));
		}elseif (isset($_POST['actualPay'])) {
			$success = $mode->set(array('actualPay' => $_POST['actualPay']), '`id`=%s', array($_POST['id']));
		}else{
			$success = $mode->set(array('adPay' => $_POST['adPay']), '`id`=%s', array($_POST['id']));
		}
		if ($success) {
			$array['status'] = "15";
			$array['final'] = $data['profit'] - $_POST['exPay'];
			echo json_encode($array);exit;//成功
		}else{
			$array['status'] = "1002";
			echo json_encode($array);exit;//失败
		}
	}

	/**
	 * 批量处理用户改密
	 */
	public function batches() {
		$operation_list = array('index', 'edit');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $_REQUEST['operation']);
		$uid_list = array('baohuan','chenjh','luojunri','yfdata','heyongzhen','yangzhenwei', 'jianjianxiang','guofengchi');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';
		$this->assign('checkRoot', $checkRoot);
		$user = false;
		if (!empty($_REQUEST['useNameStr'])) {
			$user = true;

		}
		$statistics_model = getInstance('model.statistics');
		switch ($operation) {
			case 'index':
				if ($user) {
					$strPattern = "/[a-z]{2}[0-9]{6}/";
				    $arrMatches = [];
				    preg_match_all($strPattern, $_REQUEST['useNameStr'], $arrMatches);
					if (count($arrMatches[0]) > 100) {
						ShowMsg('单次处理条数不能超过100条', '-1');
						exit;
					}
					$useNameStr = "'".implode("','", $arrMatches[0])."'";
					//var_dump($useNameStr);exit;

					$memberList = $statistics_model->getBatchesMemberList($useNameStr);
					$this->assign('memberList', $memberList);
				}
				break;
			
			case 'edit':
				if (empty($_REQUEST['password'])) {
					ShowMsg('密码不能为空', '-1');exit;
				}
				if (empty($_REQUEST['batchesUserName'])) {
					ShowMsg('请勾选需要修改的账号', '-1');exit;
				}
				$password = md5($_REQUEST['password']);
				$batchesUserName = "'".str_replace(",","','",$_REQUEST['batchesUserName'])."'";
				$memberList = $statistics_model->batchesChangePWD($batchesUserName, $password);
				break;
		}
		$this->assign('user', $user);
	}


	/**
     * 订单
     */
	public function compenOrder() {
		@session_start();

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);

		$order_model = new Model('ms_order');
		//取出所有channel
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		if (!empty($_REQUEST['game'])) {
			$rq_game = $_REQUEST['game'];
			$this->assign('game', $rq_game);
		}

		$this->assign('game_config', loadC('config.inc.php', 'main'));
		$where = '';
		if (!isset($_REQUEST['ostatus'])) {
			$status = 2;
		} else {
			$status = intval($_REQUEST['ostatus']);
		}
		$this->assign('ostatus', $status);
		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$this->assign('userName', $userName);
		$roleId = trim($_REQUEST['roleId']) ? trim($_REQUEST['roleId']) : "";
		$this->assign('roleId', $roleId);
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$channel = trim($_REQUEST['channel']) ? trim($_REQUEST['channel']) : "";//渠道
		$apkNum = trim($_REQUEST['apkNum']) ? trim($_REQUEST['apkNum']) : "";
		$this->assign('channel', $channel);
		$this->assign('apkNum', $apkNum);
		$this->assign('paymentId', $paymentId);

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

		$statistics_model = getInstance('model.statistics');

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

		$order_list = $statistics_model->getCompenOrderList($game, $channel, $start_time, $end_time, $status, $userName, $offset, $row, $apkNum, $sumString, $specialString, $roleId, $serverId, $orderId);
		$total_row = $statistics_model->getCompenOrderListTotal($game, $channel, $start_time, $end_time, $status, $userName, $apkNum, $sumString, $specialString, $roleId, $serverId,$orderId);
		$this->assign('list_total', $total_row['0']['total']);
		$order_model = new Model('ms_order');

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
			if ($status == 3) {
				$name = '已处理';
			}else{
				$name = '未处理';
			}
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
			excel_export("《中央数据后台》".$name."订单列表_{$sdate}_{$edate}", array(
			'账号', '订单号', '游戏', '服务器', '角色', '角色ID', '充值时间', '金额', '元宝', '渠道', '所属包体'
			), $reports);
			exit;
		}

		//获取包号列表
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
	}

	/**
	 * 修改订单为QA状态
	 */
	public function compenOrderReplace() {
		$orderStatus = 3;//补单折扣结算订单
		if ($_GET['type'] == 1) {
			$url = '/index.php?m=statistics&a=order';
		}elseif ($_GET['type'] == 2) {
			$url = '/index.php?m=statistics&a=compenOrder';
		}elseif ($_GET['type'] == 3) {
			$url = '/index.php?m=statistics&a=order';
			$orderStatus = 4;//QA订单
		}
		$uid_list = array('luojiang', 'heyongzhen', 'chenjh');
		$uidRoot = in_array($this->_uid, $uid_list) ? 1 : 0;
		$order_model = new model('ms_order');
		$order = $order_model->get("`orderId`='{$_GET['orderId']}'");
		if (!$order) {
			ShowMsg('没有该订单信息', $url);
			exit;
		}elseif ($order['orderStatus'] == 1 && $uidRoot != 1 && $orderStatus != 4) {
			ShowMsg('该订单不能修改状态', $url);
			exit;
		}
		$order_model->set(array(
		'orderStatus' => $orderStatus,
		'replace' => $this->_uid
		), '`orderId`=%s', array($order['orderId']));

		ShowMsg('操作成功', $url);
		exit;
	}

	/**
	 * 修改防刷订单金额并回调发货（只用于测试服）
	 * 
	 * 说明：用于测试防刷单结果的订单的金额，统一修改为6元
	 */
	public function changeOrderMoney()
	{
		$order_model = new model('ms_order');
		$order = $order_model->get("`orderId`='{$_GET['orderId']}'");

		if ($order['money'] <= 6) {
			ShowMsg('订单金额必须大于6元', '/index.php?m=statistics&a=order&ostatus=0');exit;
		}

		// 修改订单金额和支付状态
		$order['money'] = 6;
		$order['orderStatus'] = 1;
		$order_model->set(array(
		'money' => $order['money'],
		'orderStatus' => $order['orderStatus'],
		), '`orderId`=%s', array($_GET['orderId']));
		
		//通知游服发货
		$this->_user_model = getInstance('@main.model.libs.mGameSDKUser');
		$res = $this->_user_model->requestGame($order);

		// 防刷订单返回结果可以是成功到账实际充值金额，也可以是不到账，即提示支付成功，通知游戏失败
		if ($res) {
			// 修改订单发货状态
			$order_model->set(array(
			'sendStatus' => 1,
			), '`orderId`=%s', array($_GET['orderId']));
			
			ShowMsg('操作成功', '/index.php?m=statistics&a=order&ostatus=1');exit;
		} else {
			ShowMsg('支付成功，通知游戏失败', '/index.php?m=statistics&a=order&ostatus=1');exit;
		}
	}

	/**
	 * 数据导入
	 * 导入表格格式统一规则
	 * consumption ： 游戏别名，渠道ID，游戏包号，日期（xxxx-xx-xx），充值金额
	 * profit ：游戏别名，渠道ID，游戏包号，日期（xxxx-xx-xx），充值金额，CP分成，渠道分成，分成后利润，数据类型：(1、联运；2、广告 ； 3、cps )
	 */
	public function orderImport() {
		$url = '/index.php?m=statistics&a='.$_GET['target'];
		if($_GET['target'] == 'consumption'){
			$result = $this->getUploadFile('consumption', 1000, 'E', $url);
			$setModel = new model('ms_integrated_daily');
		}elseif ($_GET['target'] == 'profit') {
			$result = $this->getUploadFile('profit', 1000, 'I', $url);
			$setModel = new model('ms_profit_daily');
		}else{
			ShowMsg("当前模块不支持导入文件", '/index.php?m=home&a=index');
			exit;
		}

		$channel_model = new model('ms_channel');
		$channelList = $channel_model->getBySql("SELECT gameAlias, gameName, channelId, channelName, apkNum FROM ms_channel");
		$isset = 0;
		$unset = '';
		foreach ($result as $key => $value) {
			foreach ($channelList as $k => $v) {
				if ($value['0'] == $v['gameAlias'] && $value['1'] == $v['channelId'] && $value['2'] == $v['apkNum']) {
					$isset = 1;
					$result[$key]['gameName'] = $v['gameName'];
					$result[$key]['channelName'] = $v['channelName'];
				}
			}
			if ($isset == 0) {
				$unset .= '<br />{ '.$value['0'].':'.$value['1'].':'.$value['2'].' }';
			}
			$isset = 0;
		}

		if ($unset) {
			ShowMsg("存在不匹配数据".$unset, $url, 0, 10000);
			exit;
		}else{
			foreach ($result as $key1 => $value1) {
				$data_list = array(
				'gameAlias'		 => $value1['0'],
				'channelId'	     => $value1['1'],
				'apkNum'		 => $value1['2'],
				'date'	         => $value1['3'],
				'amount'	     => $value1['4'],
				'channelName'	 => $value1['channelName'],
				'gameName'		 => $value1['gameName'],
				'source'		 => 1,
				);
				if ($_GET['target'] == 'profit') {
					$data_list['cpAmount'] = $value1['5'];
					$data_list['channelAmount'] = $value1['6'];
					$data_list['profit'] = $value1['7'];
					$data_list['type'] = $value1['8'];
				}
				$setModel->set($data_list);
			}
		} 
		ShowMsg("导入成功", $url);
		exit;
	}

	public function getUploadFile($target, $line=1000, $format, $url) {
		load('uploadfile');
		$path = C('DEDE_DATA_PATH') . "orderExcel/" . date('Ymd') . "/";
		@mkdir($path);
		$filetypes = array('xlsx', 'xls');
		$files = $_FILES['file'];
		$upload = new uploadfile($files, $path, 999999999999, $filetypes);
		$file_name = $target.time();
		$success = !!$upload->upload($file_name);
		$suffix = pathinfo($files['name'][0]);

		$dir = $path . $file_name .'.'.$suffix['extension'];
		if(!$success) {
			ShowMsg("上传失败", $url);
		}
        load('plugins.PHPExcel.Classes.PHPExcel.IOFactory');
        $phpExcel = PHPExcel_IOFactory::load($dir);
		// 设置为默认表
		$phpExcel->setActiveSheetIndex(0);
		// 获取表格数量
		//$sheetCount = $phpExcel->getSheetCount();
		// 获取行数
		$row = $phpExcel->getActiveSheet()->getHighestRow();
		// 获取列数
		$column = $phpExcel->getActiveSheet()->getHighestColumn();
		$highestRow = $phpExcel->getActiveSheet()->getHighestRow();
		$highestColumn = $phpExcel->getActiveSheet()->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
			$excelData[$row][] =(string)$phpExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		
		if ($row > $line) {
			ShowMsg("添加条数已超过上限", $url);exit;
		}
		if (empty($format)) {
			ShowMsg("未设置表格格式", $url);exit;
		}

		if (strtoupper($column) != strtoupper($format)) {
			ShowMsg("添加的表格格式不正确", $url);exit;
		}
		return $excelData;
	}

	/**
	 * 修改数据状态
	 */
	public function changeDataStatus() {
		if ($_GET['model'] == 'consumption') {
			$url = '/index.php?m=statistics&a=consumption';
			$sqlModel = new model('ms_integrated_daily');
		}elseif ($_GET['model'] == 'profit') {
			$url = '/index.php?m=statistics&a=profit';
			$sqlModel = new model('ms_profit_daily');
		}
		$uid_list = array('heyongzhen', 'chenjh');
		$uidRoot = in_array($this->_uid, $uid_list) ? 1 : 0;
		if (!$uidRoot) {
			ShowMsg('无相关操作权限', $url);
			exit;
		}
		$data = $sqlModel->get("`id`='{$_GET['id']}'");
		if (!$data) {
			ShowMsg('没有该数据信息', $url);
			exit;
		}
		$sqlModel->set(array(
		'status' => 2,
		), '`id`=%s', array($data['id']));

		ShowMsg('操作成功', $url);
		exit;
	}

	/**
	 * 新用户充值走势
	 */
	public function newUserRecharge() {

		$order_model = new Model('ms_game');
		//取出所有游戏
		$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias!='' AND name IS NOT NULL");

		if (!empty($_REQUEST['game'])) {
			$game = $_REQUEST['game'];
			$this->assign('game', $game);
		}

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 8) {
			$game_row = $order_model->getBySql("SELECT alias, name FROM ms_game  WHERE alias='" . $gidarr[0]['game'] . "' AND name IS NOT NULL");
		}

		$this->assign('games', $game_row);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$statistics_model = getInstance('model.statistics');
		//获取包号列表
		//$committe_list = $statistics_model->getCommittee(0, 1000, $channel);
		/*$committe_apknum = array();
		foreach ($committe_list as $key => $value) {
			$committe_apknum[$key] = $value['apkNum'];
		}*/
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);

		//获取上级游戏名
		if (( $gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 17 ) && ( $gidarr[0]['game'] != 'all') ) {
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$this->checkLogin();
		if (isset($_GET['ajax']) && !empty($_GET['ajax'])) {
			switch ($_GET['ajax']) {
				case "getNewUserRecharge" 		:
					$this->getNewUserRecharge();
					break;
			}
		}
		$this->setInitTime();
	}

	// 获取新用户充值数据
	public function getNewUserRecharge() {
		$this->checkLogin();
		$this->getChannel('platform', '充值金额', 'newUserRecharge');
	}

	/**
	 * 综合数据列表数据合并
	 *
	 * @param array $firstArray
	 * @param array $secondArray
	 * @param string $key
	 * @return array
	 */
	public function activeListMerge($firstArray, $secondArray, $key)
	{
		$firstArrayCount = count($firstArray);
		$secondArrayCount = count($secondArray);
		$oneArray = array();
		$twoArray = array();
		$newArray = array();
		if ($firstArrayCount >= $secondArrayCount) {
			$oneArray = $firstArray;
			$twoArray = $secondArray;
		}else {
			$oneArray = $secondArray;
			$twoArray = $firstArray;
		}
		foreach ($oneArray as $k => $v) {
			if ($v[$key] == $twoArray[$k][$key]) {
				if ($v['upperName']) {
					$newArray[$k]['upperName'] = $v['upperName'];
				}
				if ($v['specialName']) {
					$newArray[$k]['specialName'] = $v['specialName'];
				}
				if ($v['name']) {
					$newArray[$k]['name'] = $v['name'];
				}
				if ($v['alias']) {
					$newArray[$k]['alias'] = $v['alias'];
				}
				if ($v['date']) {
					$newArray[$k]['date'] = $v['date'];
				}
				$newArray[$k]['newUser'] = $v['newUser'] + $twoArray[$k]['newUser'];
				$newArray[$k]['oldUser'] = $v['oldUser'] + $twoArray[$k]['oldUser'];
				$newArray[$k]['activeUser'] = $v['activeUser'] + $twoArray[$k]['activeUser'];
				$newArray[$k]['amount'] = $v['amount'] + $twoArray[$k]['amount'];
				$newArray[$k]['payUser'] = $v['payUser'] + $twoArray[$k]['payUser'];
				$newArray[$k]['newPayUser'] = $v['newPayUser'] + $twoArray[$k]['newPayUser'];
				$newArray[$k]['newAmount'] = $v['newAmount'] + $twoArray[$k]['newAmount'];
				$newArray[$k]['oldPayUser'] = $v['oldPayUser'] + $twoArray[$k]['oldPayUser'];
				$newArray[$k]['oldAmount'] = $v['oldAmount'] + $twoArray[$k]['oldAmount'];
			}else {
				$newArray[$k] = $v;
			}
		}
		foreach ($twoArray as $k => $v) {
			if ($v[$key] != $oneArray[$k][$key]) {
				$newArray[$k] = $v;
			}
		}
		
		return $newArray;
	}

	/**
	 * 综合数据的列表数据进行格式化
	 *
	 * @param string $displayMode
	 * @param string $upperName
	 * @param string $specialName
	 * @param string $game
	 * @param string $years
	 * @param array $listData
	 * @return array
	 */
	public function getActiveList($displayMode, $upperName, $specialName, $game, $years, $listData)
	{
		$activeList = array();
		if ($displayMode == 'sum') {
			if ($upperName) {
				// 选择指定项目的市场数据来源的汇总模式下
				foreach ($listData as $key => $value) {
					if ($years) {
						// 按月份展示数据
						$value['date'] = date('Ym', strtotime($value['date']));
					}else {
						// 按日期展示数据
						$value['date'] = $value['date'];
					}

					if ($value['upperName']) {
						$activeList[$value['date']]['upperName'] = $value['upperName'];
					}
					if ($value['specialName']) {
						$activeList[$value['date']]['specialName'] = $value['specialName'];
					}
					if ($value['name']) {
						$activeList[$value['date']]['name'] = $value['name'];
					}
					if ($value['alias']) {
						$activeList[$value['date']]['alias'] = $value['alias'];
					}
					$activeList[$value['date']]['newUser'] += $value['newUser'];
					$activeList[$value['date']]['oldUser'] += $value['oldUser'];
					$activeList[$value['date']]['activeUser'] += $value['activeUser'];
					$activeList[$value['date']]['amount'] += $value['amount'];
					$activeList[$value['date']]['payUser'] += $value['payUser'];
					$activeList[$value['date']]['newPayUser'] += $value['newPayUser'];
					$activeList[$value['date']]['newAmount'] += $value['newAmount'];
					$activeList[$value['date']]['oldPayUser'] += $value['oldPayUser'];
					$activeList[$value['date']]['oldAmount'] += $value['oldAmount'];
					$activeList[$value['date']]['date'] = $value['date'];
				}
			}else {
				$activeList = array_column($listData, null, 'upperName'); 
			}
		}else {
			// 详情模式 （不需要考虑年份条件）
			if ($upperName && empty($specialName)) {
				$activeList = array_column($listData, null, 'specialName'); 

			}elseif($specialName && empty($game)) {
				$activeList = array_column($listData, null, 'alias'); 

			}elseif($game) {
				$activeList = $listData; 

			}else {
				$activeList = array_column($listData, null, 'upperName'); 
			}
		}
		return $activeList;
	}

	/**
	 * 根据不同条件获取综合数据列表
	 *
	 * @param string $displayMode
	 * @param array $firstArray
	 * @param array $secondArray
	 * @param string $upperName
	 * @param string $specialName
	 * @param string $game
	 * @return array
	 */
	public function conditionGetActiveList($displayMode, $firstArray, $secondArray, $upperName, $specialName, $game)
	{
		$activeList = array();
		if ($firstArray && empty($secondArray)) {
			$activeList = $firstArray;
		}elseif (empty($firstArray) && $secondArray) {
			$activeList = $secondArray;
		}elseif ($firstArray && $secondArray) {
			if ($displayMode == 'sum') {
				// 汇总模式
				if ($upperName) {
					$activeList = $this->activeListMerge($firstArray, $secondArray, 'date');
				}else {
					$activeList = $this->activeListMerge($firstArray, $secondArray, 'upperName');
				}
			}else {
				// 详情模式 （不需要考虑年份条件）
				if ($upperName && empty($specialName)) {
					$activeList = $this->activeListMerge($firstArray, $secondArray, 'specialName');
				}elseif($specialName && empty($game)) {
					$activeList = $this->activeListMerge($firstArray, $secondArray, 'alias');
				}elseif($game) {
					$activeList = array_merge($firstArray, $secondArray);
				}else {
					$activeList = $this->activeListMerge($firstArray, $secondArray, 'upperName');
				}
			}
		}
		return $activeList;
	}

	/**
	 * 格式化综合数据列表的投放支出数据
	 *
	 * @param [type] $displayMode
	 * @param [type] $upperName
	 * @param [type] $specialName
	 * @param [type] $game
	 * @param [type] $years
	 * @param [type] $listData
	 * @return void
	 */
	public function formatAdPayData($displayMode, $upperName, $specialName, $game, $years, $listData)
	{
		$adPay = array();
		if ($displayMode == 'sum') {
			if ($upperName) {
				// 选择指定项目的市场数据来源的汇总模式下
				foreach ($listData as $key => $value) {
					if ($years) {
						// 按月份展示数据
						$value['date'] = date('Ym', strtotime($value['date']));
					}else {
						// 按日期展示数据
						$value['date'] = $value['date'];
					}

					if ($value['upperName']) {
						$adPay[$value['date']]['upperName'] = $value['upperName'];
					}
					if ($value['specialName']) {
						$adPay[$value['date']]['specialName'] = $value['specialName'];
					}
					if ($value['alias']) {
						$adPay[$value['date']]['alias'] = $value['alias'];
					}
					$adPay[$value['date']]['adPay'] += $value['adPay']; 
					$adPay[$value['date']]['exPay'] += $value['exPay']; 
					$adPay[$value['date']]['newlyProfit'] += $value['newlyProfit']; 
					$adPay[$value['date']]['date'] = $value['date'];
				}
			}else {
				$adPay = array_column($listData, null, 'upperName'); 
			}
		}else {
			// 详情模式 （不需要考虑年份条件）
			if ($upperName && empty($specialName)) {
				$adPay = array_column($listData, null, 'specialName'); 

			}elseif($specialName && empty($game)) {
				$adPay = array_column($listData, null, 'alias'); 

			}elseif($game) {
				$adPay = $listData; 

			}else {
				$adPay = array_column($listData, null, 'upperName'); 
			}
		}
		return $adPay;
	}

	/**
	 * 综合数据列表合并综合数据
	 *
	 * @param array $firstArray
	 * @param array $secondArray
	 * @param string $key
	 * @return array
	 */
	public function adPayDataMerge($firstArray, $secondArray, $key)
	{
		$firstArrayCount = count($firstArray);
		$secondArrayCount = count($secondArray);
		$oneArray = array();
		$twoArray = array();
		$newArray = array();
		if ($firstArrayCount >= $secondArrayCount) {
			$oneArray = $firstArray;
			$twoArray = $secondArray;
		}else {
			$oneArray = $secondArray;
			$twoArray = $firstArray;
		}
		foreach ($oneArray as $k => $v) {
			if ($v[$key] == $twoArray[$k][$key]) {
				if ($v['upperName']) {
					$newArray[$k]['upperName'] = $v['upperName'];
				}
				if ($v['specialName']) {
					$newArray[$k]['specialName'] = $v['specialName'];
				}
				if ($v['alias']) {
					$newArray[$k]['alias'] = $v['alias'];
				}
				if ($v['date']) {
					$newArray[$k]['date'] = $v['date'];
				}
				$newArray[$k]['adPay'] = $v['adPay'] + $twoArray[$k]['adPay'];
				$newArray[$k]['exPay'] = $v['exPay'] + $twoArray[$k]['exPay'];
				$newArray[$k]['newlyProfit'] = $v['newlyProfit'] + $twoArray[$k]['newlyProfit'];
			}else {
				if ($v['upperName']) {
					$newArray[$k]['upperName'] = $v['upperName'];
				}
				if ($v['specialName']) {
					$newArray[$k]['specialName'] = $v['specialName'];
				}
				if ($v['alias']) {
					$newArray[$k]['alias'] = $v['alias'];
				}
				if ($v['date']) {
					$newArray[$k]['date'] = $v['date'];
				}
				$newArray[$k]['adPay'] = $v['adPay'];
				$newArray[$k]['exPay'] = $v['exPay'];
				$newArray[$k]['newlyProfit'] = $v['newlyProfit'];
			}
		}
		return $newArray;
	}

	/**
	 * 根据不同条件获取综合数据列表的综合数据
	 *
	 * @param string $displayMode
	 * @param array $firstArray
	 * @param array $secondArray
	 * @param string $upperName
	 * @param string $specialName
	 * @param string $game
	 * @return array
	 */
	public function conditionGetAdPayData($displayMode, $firstArray, $secondArray, $upperName, $specialName, $game)
	{
		$adPayData = array();
		if ($firstArray && empty($secondArray)) {
			$adPayData = $firstArray;
		}elseif (empty($firstArray) && $secondArray) {
			$adPayData = $secondArray;
		}elseif ($firstArray && $secondArray) {
			if ($displayMode == 'sum') {
				// 汇总模式
				if ($upperName) {
					$adPayData = $this->adPayDataMerge($firstArray, $secondArray, 'date');
				}else {
					$adPayData = $this->adPayDataMerge($firstArray, $secondArray, 'upperName');
				}
			}else {
				// 详情模式 （不需要考虑年份条件）
				if ($upperName && empty($specialName)) {
					$adPayData = $this->adPayDataMerge($firstArray, $secondArray, 'specialName');
				}elseif($specialName && empty($game)) {
					$adPayData = $this->adPayDataMerge($firstArray, $secondArray, 'alias');
				}elseif($game) {
					$adPayData = array_merge($firstArray, $secondArray);
				}else {
					$adPayData = $this->adPayDataMerge($firstArray, $secondArray, 'upperName');
				}
			}
		}
		return $adPayData;
	}
	
	/**
	 * 删除角色记录(只用于测试服)
	 */
	public function delRole()
	{
		$roleId = trim($_GET['roleId']);
		if ($roleId) {
			$delSql = "delete from ms_role_seted where roleId = '{$roleId}'";
			$res = model::getBySql($delSql);
			if ($res) {
				ShowMsg('操作成功', '/index.php?m=statistics&a=roleInfo');exit;
			} else {
				ShowMsg('操作失败', 'index.php?m=statistics&a=roleInfo');exit;
			}
		} else {
			ShowMsg('操作异常', 'index.php?m=statistics&a=roleInfo');exit;
		}
	}

	/**
	 * 导出记录
	 */
	public function fileList()
	{	
		$operation_list = array('index', 'download', 'edit', 'save', 'del');
		$request_oper = array_key_exists('operation', $_REQUEST) ? $_REQUEST['operation'] : '';
		$operation = in_array($request_oper, $operation_list) ? $request_oper : 'index';
		$this->assign('operation', $operation);

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$row = 20;
				$offset = ($page - 1) * $row;

				// 数据列表
				$statistics_model = getInstance('model.statistics');
				$fileList = $statistics_model->getfileManage($offset, $row);
				
				// 数据总数
				$total = $statistics_model->getFileCount();

				foreach ($fileList as $key => $value) {
					$fileList[$key]['createTime'] = date('Y-m-d H:i:s', $value['createTime']);
				}

				$this->assign('fileList', $fileList);
				$this->assign('list_length', $row);
				$this->assign('list_total', $total);
				$this->assign('list_page', $page);
				break;

			case 'download':
				// 下载

				$id = trim($_REQUEST['id']);

				// 获取文件名称
				$statistics_model = getInstance('model.statistics');
				$res = $statistics_model->getFileParams($id);
				$file = $res[0]['fileName']. '.'. $res[0]['type'];
				$day = date('Ymd', $res[0]['createTime']);

				// 静态资源域名
				$domain = C('STATIC_SOURCE_SITE');
				// 文件下载地址
				$downloadUrl = $domain. 'orderExcel/'. $day. '/'. $file;

				// 文件存在服务器路径
				$catalogue = C('DEDE_DATA_PATH'). 'orderExcel/'. $day. '/'. $file;

				// 如果文件存在, 则跳转到下载路径
				if ( file_exists($catalogue) ) {
					header('location:'. $downloadUrl);
				} else {
					ShowMsg('文件不存在', -1);
				}

				break;

			default:

			break;
		}
	}

}