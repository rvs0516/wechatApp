<?php

require_once APP_CONTROLLER_PATH . '/master.php';

class sdkChannelController extends masterControl {

	/**
	 * 游戏管理
	 */
	public function listChannel() {
		if ($_REQUEST['operation'] == 'upGradeMark') {
			if (empty($_REQUEST['batchesChannelId'])) {
				ShowMsg('请勾选需要修改的账号', 'index.php?m=sdkChannel&a=listChannel');
				exit;
			}
			$password = md5($_REQUEST['password']);
			$batchesChannelId = "'".str_replace(",","','",$_REQUEST['batchesChannelId'])."'";
			$channel_model = new model('ms_channel');
			$success = $channel_model->getBySql("UPDATE ms_channel SET upGradeMark = ".$_REQUEST['status']." WHERE id IN(".$batchesChannelId.")");
			if ($success) {
				ShowMsg('修改成功', 'index.php?m=sdkChannel&a=listChannel');
				exit;
			}else{
				ShowMsg('修改失败', 'index.php?m=sdkChannel&a=listChannel');
				exit;
			}
		}else{
			$uid_list = array('luojiang', 'baohuan', 'yangzhenwei', 'chenjh', 'guojian', 'heyongzhen', 'luzhijun', 'liuyuwen');
			$editor = in_array($this->_uid, $uid_list) ? 1 : '';
			$this->assign('editor', $editor);
			$channel_model = getInstance('model.sdkChannel.channel');
			$game_model = getInstance('model.sdkGame.game');
			$game = $game_model->getList();

			$gameAlias = trim($_REQUEST['game']);
			$adsChannel = trim($_REQUEST['adsChannel']);

			$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
			$page = $page === 0 ? 1 : $page;
			$length = 20;
			$offset = ($page - 1) * $length;

			$statistics_model = getInstance('model.statistics');
			$gidarr = $channel_model->returnUidGroup($this->_uid);
			$gid = intval($gidarr[0]['gid']);

			$gameStr = '';
			if ($gid == 8) {
				$game = $gidarr[0]['game'];
			}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17){
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
			if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
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
			$upGradeMark = trim($_REQUEST['upGradeMark']) ? trim($_REQUEST['upGradeMark']) : "";
			$this->assign('upGradeMark', $upGradeMark);
			require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
			$this->assign('channels', $channels);
			$channelId = trim($_REQUEST['channelId']) ? trim($_REQUEST['channelId']) : "";
			$this->assign('channelId', $channelId);
			$remarks = trim($_REQUEST['remarks']) ? trim($_REQUEST['remarks']) : "";
			$this->assign('remarks', $remarks);

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

			$game_list = $channel_model->getListByGname($gameAlias, $offset, $length, $sumString, $specialString, $adsChannel, $upGradeMark, $channelId, $remarks, $gameStr, $gid);
			$list_total = $channel_model->getChannelTotal($gameAlias, $sumString, $specialString, $adsChannel, $upGradeMark, $channelId, $remarks, $gameStr, $gid);
			foreach ($game_list as $key => $value) {
				foreach ($game as $key1 => $value1) {
					if ($value['gameAlias'] == $value1['alias']) {
						$game_list[$key]['upperName'] = $value1['upperName'];
						$game_list[$key]['specialName'] = $value1['specialName'];
					}
				}
			}

			require_once APP_LIST_PATH . 'main/adsChannels.php';
			foreach ($adsChannels as $k => $v) {
				foreach ($game_list as $k1 => $v1) {
					if (($k == $v1['adsChannel'])) {
						$game_list[$k1]['adsName'] = $v;
					}
					if (!empty($v1['ext2']) && empty($v1['adsChannel']) && ($v1['channelId'] == '160068' || $v1['channelId'] == '160136')) {
						$game_list[$k1]['adsName'] = '广点通';
					}
				}
			}
			$array = array(
			'game' => $game,
			'gameAlias' => $gameAlias,
			'game_list' => $game_list,
			'list_total' => $list_total,
			'game_name' => $game_name,
			'channel_name' => $channel_name,
			'page' => $page,
			'list_length' => $length,
			'offset' => $offset,
			'callbackUrl' => C('NOTIFY_SITE'),
			'adsChannels' => $adsChannels,
			'adsChannel' => $adsChannel
			);
			$this->assign($array);
		}
	}

	/**
	 * 新增
	 */
	public function addChannel() {
		$channel_model = getInstance('model.sdkChannel.channel');
		$statistics_model = getInstance('model.statistics');
		$uid_list = array('luojiang', 'baohuan', 'yangzhenwei', 'chenjh', 'guojian', 'heyongzhen', 'luzhijun', 'liuyuwen');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';

		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 15 || $gid == 24) {
			$checkRoot = 1;
		}
		$this->assign('checkRoot', $checkRoot);
		$gameStr = '';
		if ($gid == 8) {
			$game = $gidarr[0]['game'];
		}elseif($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17){
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
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
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

		$game_model = getInstance('model.sdkGame.game');
		$game = $game_model->getList();
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';   // 支持渠道
		require_once APP_LIST_PATH . 'main/adsChannels.php';

		$archivesModel = getInstance('model.sdkArchives.archives');
		$privacyArchives = $archivesModel->getList(0, 100, 1);
		$agreementArchives = $archivesModel->getList(0, 100, 2);

		// 模拟器限制选项默认选中
		$simulator = '1';
		$this->assign('simulator', $simulator);

		$this->assign(array(
		'game' => $game,
		'channels' => $channels,
		'operation' => 'add',
		'adsChannels' => $adsChannels,
		'privacyArchives' => $privacyArchives,
		'agreementArchives' => $agreementArchives,
		));
		$this->display('sdkChannel/editChannel.html');
	}

	/**
	 * 编辑
	 */
	public function editChannel() {
		
		// 当前方法使用到的model
		$channel_model = getInstance('model.sdkChannel.channel');
		$statistics_model = getInstance('model.statistics');

		// 获取当前用户是否有权限访问当前模块
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid != 1 && $gid != 15 && $_GET['do'] != 'view' && $gid != 24) {
			ShowMsg('当前账号无该操作权限', '/index.php?m=sdkChannel&a=listChannel');exit;
		}
		$this->assign('gid', $gid);

		// 获取所有游戏信息
		$game_model = getInstance('model.sdkGame.game');
		$game = $game_model->getList();

		// 获取当前需要编辑的渠道信息
		$channel = $channel_model->getInfo($_GET['id']);
		if(empty($channel)) {
			ShowMsg('渠道不存在，正返回列表', -1);exit;
		}

		// 限制访问数据的权限
		$uid_list = array('luojiang', 'baohuan', 'yangzhenwei', 'chenjh', 'guojian', 'heyongzhen', 'luzhijun', 'liuyuwen');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';
		$this->assign('checkRoot', $checkRoot);
		if ($gid == 15 || $gid == 24) {
			$checkRoot = 1;
		}
		$this->assign('checkRoot', $checkRoot);
		
		//获取上级游戏名
		$UpperList = $statistics_model->getUpperList();
		$this->assign('UpperList', $UpperList);

		//渠道默认分成、通道费
		$proportion = require(APP_LIST_PATH . "main/channel.proportion.php");
		$this->assign('proportion', $proportion);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';

		// 根据游戏别名获取对应的项目和专服名称
		foreach ($game as $key => $value) {
			if ($value['alias'] == $channel['gameAlias']) {
				$channel['upperName'] = $value['upperName'];
				$channel['specialName'] = $value['specialName'];
			}
		}

		// 根据项目获取专服列表
		$specialList = $statistics_model->getSpecialList($channel['upperName']);
		$this->assign('specialList', $specialList);

		//还原的原始公钥（字符串）
		$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
		$end_public_key = "-----END PUBLIC KEY-----\r\n";

		$gamePubKey = file_get_contents(APP_LIST_PATH . "main/models/api/includes/" . $channel['channelId'] . "GameSeverSDK/publicKey/" . $channel['gameAlias'] . "/gamePublicKey.pem");
		$gamePubKey = str_replace($begin_public_key, '', $gamePubKey);
		$gamePubKey = str_replace($end_public_key, '', $gamePubKey);
		$gamePubKey = str_replace(PHP_EOL, '', $gamePubKey);

		$payPubKey = file_get_contents(APP_LIST_PATH . "main/models/api/includes/" . $channel['channelId'] . "GameSeverSDK/publicKey/" . $channel['gameAlias'] . "/payPublicKey.pem");

		$payPubKey = str_replace($begin_public_key, '', $payPubKey);
		$payPubKey = str_replace($end_public_key, '', $payPubKey);
		$payPubKey = str_replace(PHP_EOL, '', $payPubKey);

		$gamePrivateKey = file_get_contents(APP_LIST_PATH . "main/models/api/includes/" . $channel['channelId'] . "GameSeverSDK/privateKey/" . $channel['gameAlias'] . "/gamePrivateKey.pem");
		$gamePrivateKey = str_replace("-----BEGIN PRIVATE KEY-----\r\n", '', $gamePrivateKey);
		$gamePrivateKey = str_replace("-----END PRIVATE KEY-----\r\n", '', $gamePrivateKey);
		$gamePrivateKey = str_replace(PHP_EOL, '', $gamePrivateKey);

		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
		require_once APP_LIST_PATH . 'main/adsChannels.php';
		foreach ($adsChannels as $k => $v) {
			foreach ($channel as $k1 => $v1) {
				if ($v1 == $k && ($channel['channelId'] == '160068' || $channel['channelId'] == '160136')) {
					$channel['adsName'] = $v;
				}
			}
		}

		$banAction = explode(',' , $channel['banAction']);
		if (in_array('regs', $banAction)) {
			$channel['banRegs'] = 1;
		}
		if (in_array('login', $banAction)) {
			$channel['banLogin'] = 1;
		}
		if (in_array('pay', $banAction)) {
			$channel['banPay'] = 1;
		}
		$change = 0;
		if ($channel['channelId'] == '160068') {
			$hour = date('G');
			if ($hour != 23) {
				$change = 1;
			}
		}
		$loadInfo = json_decode($channel['loadInfo'], true);
		$clearData = json_decode($channel['clearData'], true);
		$archivesModel = getInstance('model.sdkArchives.archives');
		$privacyArchives = $archivesModel->getList(0, 100, 1);
		$agreementArchives = $archivesModel->getList(0, 100, 2);
		$archivesArray = explode(',', $channel['archivesControl']);
		$initTips = $archivesArray[0];
		$privacy = $archivesArray[1];
		$agreement = $archivesArray[2];
		$arrays = array(
		'game' => $game,
		'channel' => $channel,
		'channels' => $channels,
		'operation' => 'edit',
		'gamePubKey' => $gamePubKey,
		'payPubKey' => $payPubKey,
		'gamePrivateKey' => $gamePrivateKey,
		'callbackUrl' => C('NOTIFY_SITE') . 'callback/' . $channel['channelId'],
		'adsChannels' => $adsChannels,
		'banArea' => $channel['banArea'],
		'change' => $change,
		'loadInfoDate' => $loadInfo['loadInfoDate'],
		'loadInfoType' => $loadInfo['loadInfoType'],
		'clearStart' => $clearData['clearStart'],
		'clearEnd' => $clearData['clearEnd'],
		'packageName' => $channel['packageName'],
		'privacyArchives' => $privacyArchives,
		'agreementArchives' => $agreementArchives,
		'initTips' => $initTips,
		'privacy' => $privacy,
		'agreement' => $agreement,
		'mainPart' => $channel['mainPart'],
		'simulator' => $channel['simulator'],
		);
		$this->assign($arrays);

		if ($_GET['do'] == 'view') {
			$this->display('sdkChannel/viewChannel.html');
			exit;
		}
	}

	/**
	 * 保存渠道
	 */
	public function saveChannel() {

		$game_model = getInstance('model.sdkGame.game');
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		//根据游戏别名取得名称
		$game = $game_model->getInfoByAlias($_POST['game']);
		if (!empty($_POST['clearStart']) && empty($_POST['clearEnd'])) {
			ShowMsg('缺少清除数据的结束时间', -1);exit;
		}elseif (empty($_POST['clearStart']) && !empty($_POST['clearEnd'])) {
			ShowMsg('缺少清除数据的开始时间', -1);exit;
		}

		//检查是新增还是编辑
		$channel = $channel_model->getInfo( intval($_POST['id']) );
		$is_new = empty($channel);

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		if ($gid == 1 || ($gid == 15 && $is_new) || $gid == 24) {

			//生成UC渠道配置信息
			if($_POST['channel'] == '000255'){
				$fileData = array(
				'sdkserver.baseUrl'        => "http://sdk.9game.cn",
				'sdkserver.baseUrl.port'   => "80",
				'sdkgamedata.baseUrl'      => "http://collect.sdkyy.9game.cn",
				'sdkgamedata.baseUrl.port' => "8080",
				'sdkrealname.baseUrl'      => "http://sdknc.9game.cn",
				'sdkrealname.baseUrl.port' => "80",
				'sdkserver.game.gameId'    => trim($_POST['appid']),
				'sdkserver.game.apikey'    => trim($_POST['appkey']),
				'sdkserver.debug'          => "false",
				'sdkserver.debug.filepath' => "/var/tmp/",
				'connectTimeOut'           => "5"
				);

				$output = "<?php";
				$output .= "\n";
				$output .= "return ";
				$output .= var_export($fileData, true);
				$output .= ";";
				$filePath = APP_LIST_PATH . "main/models/api/includes/000255GameSeverSDK/config/" . $_POST['game'] . "config.inc.php";
				file_put_contents($filePath, $output);
			}

			//生成相应的支付公钥、游戏公钥和游戏私钥（仅用于本地和测试服调试）
			if(isset($_POST['gamePubKey']) || isset($_POST['payPubKey']) || isset($_POST['gamePrivateKey'])){
				$pubKeyPath = APP_LIST_PATH . "main/models/api/includes/" . $_POST['channel'] . "GameSeverSDK/publicKey/" . $_POST['game'];

				// 【注意】在PHP版本5.5以下类似empty()、is_array()、is_dir()的函数不能直接判断某功能的返回值，只能写成变量的形式或者升级PHP5.5及以上版本。

				if(!is_dir($pubKeyPath)){
					mkdir($pubKeyPath);
				}
				if (!empty(trim($_POST['gamePubKey']))) {
					mkPubKey($pubKeyPath . "/gamePublicKey.pem", $_POST['gamePubKey']);
				}

				if (!empty(trim($_POST['payPubKey']))) {
					mkPubKey($pubKeyPath . "/payPublicKey.pem", $_POST['payPubKey']);
				}

				$priKeyPath = APP_LIST_PATH . "main/models/api/includes/" . $_POST['channel'] . "GameSeverSDK/privateKey/" . $_POST['game'];
				if(!is_dir($priKeyPath)){
					mkdir($priKeyPath);
				}
				if (!empty(trim($_POST['gamePrivateKey']))) {
					mkPriKey($priKeyPath . "/gamePrivateKey.pem", $_POST['gamePrivateKey']);
				}
			}

			//写入渠道信息表
			$channelData = array();
			$channelData['gameAlias'] = $_POST['game'];
			$channelData['remarks'] = $_POST['remarks'];
			$channelData['gameName'] = $game['name'];
			$channelData['channelId'] = $_POST['channel'];
			$channelData['channelName'] = $channels[$_POST['channel']];
			$channelData['appid'] = trim($_POST['appid']);
			$channelData['appkey'] = trim($_POST['appkey']);
			$channelData['qyLg'] = intval($_POST['qyLg']);
			$channelData['qyPy'] = intval($_POST['qyPy']);
			$channelData['qypyAmount'] = (intval($_POST['qyPy'])) ? intval($_POST['qypy_amount']) : 0;
			$channelData['ext1'] = trim($_POST['ext1']);
			$channelData['ext2'] = trim($_POST['ext2']);
			$channelData['adsParam'] = trim($_POST['adsParam']);
			$channelData['pkgName'] = trim($_POST['pkgName']);
			$channelData['mainPart'] = trim($_POST['mainPart']);
			if ($_POST['apkNum']) {
				$channelData['apkNum'] = $_POST['apkNum'];
			}
			$channelData['sort'] = $_POST['sort'];
			//强更
			$channelData['versionCode'] = intval($_POST['versionCode']);
			$channelData['updateUri'] = trim($_POST['updateUri']);

			$channelData['adsChannel'] = trim($_POST['adsChannel']);
			//限制地区
			$channelData['banArea'] = trim($_POST['banArea']);
			//渠道分成
			if ($_POST['change'] == 1) {
				$channelData['exProportion'] = $channel['exProportion'];
			}else{
				$channelData['exProportion'] = trim($_POST['exProportion']);
			}

			//渠道通道费
			$channelData['channelAllowance'] = trim($_POST['channelAllowance']);
			//结算方式
			$channelData['settlement'] = trim($_POST['settlement']);
			$channelData['upGradeMark'] = trim($_POST['upGradeMark']);
			$channelData['isTest'] = trim($_POST['isTest']);
			$channelData['simulator'] = trim($_POST['simulator']);
			$channelData['checkBlog'] = trim($_POST['checkBlog']);

			$clearData = array(
				'clearStart' => trim($_POST['clearStart']), 
				'clearEnd' => trim($_POST['clearEnd']), 
				);
			$channelData['clearData'] = json_encode($clearData);	
			if (empty($_POST['clearStart']) && empty($_POST['clearStart'])) {
				$channelData['clearData'] = '';
			}
			$loadInfo = array(
				'loadInfoDate' => trim($_POST['loadInfoDate']), 
				'loadInfoType' => trim($_POST['loadInfoType']), 
				);
			$channelData['loadInfo'] = json_encode($loadInfo);
			$channelData['packageName'] = trim($_POST['packageName']);
			$channelData['archivesControl'] = $_POST['initTips'].','.$_POST['privacy'].','.$_POST['agreement'];

			//限制属性
			$banArray = array();
			if ($_POST['banRegs'] == 1) {
				$banArray[] = 'regs';
			}
			if ($_POST['banLogin'] == 1) {
				$banArray[] = 'login';
			}
			if ($_POST['banPay'] == 1) {
				$banArray[] = 'pay';
			}
			$channelData['banAction'] = implode(',', $banArray);
			/*$channelData['banLg'] = intval($_POST['banLg']);
			$channelData['banPy'] = intval($_POST['banPy']);*/
			if ($channelData['exProportion'] > 1 || $channelData['exProportion'] < 0) {
				ShowMsg('操作失败，渠道分成不能大于1或少于0', '/index.php?m=sdkChannel&a=listChannel');exit;
			}

			if($is_new) {
				// 新增保存

				//互通功能在配置参数时选择后不可修改
				$channelData['interflow'] = trim($_POST['interflow']);
				$channel_model->add($channelData);
			}else {
				// 编辑保存
				
				if ($channelData['channelId'] == '160068' && $_POST['change'] != 1) {
					$channelList = $arrayName = array('南宇', '耀非', '炎游', '畅梦');
					if (in_array($channelData['apkNum'], $channelList)) {
						$url = C('MIS_URL') . 'api/index.php?m=index&a=getDiscount';
						$data = array(
							'gschannel' => $channelData['apkNum'], 
							'game'		=> $channelData['gameAlias'],
							'discount'  => $channelData['exProportion']  
							);
						$result_str = httpRequest($url, $data);
						if ($result_str != 'success') {
							ShowMsg('操作失败，请核实mis后台对应渠道', '/index.php?m=sdkChannel&a=listChannel');exit;
						}
					}
					$this->qyDiscountConf($channelData);
				}
				$channel_model->edit($_POST['id'], $channelData);
			}
		}else{
			$clearData = array(
				'clearStart' => trim($_POST['clearStart']), 
				'clearEnd' => trim($_POST['clearEnd']), 
				);
			$channelData['clearData'] = json_encode($clearData);	
			if (empty($_POST['clearStart']) && empty($_POST['clearStart'])) {
				$channelData['clearData'] = '';
			}
			$loadInfo = array(
				'loadInfoDate' => trim($_POST['loadInfoDate']), 
				'loadInfoType' => trim($_POST['loadInfoType']), 
				);
			$channelData['loadInfo'] = json_encode($loadInfo);
			$channelData['packageName'] = trim($_POST['packageName']);
			$channel_model->edit($_POST['id'], $channelData);
		}

		ShowMsg('操作成功', '/index.php?m=sdkChannel&a=listChannel');exit;
	}

	/**
	 * 编辑
	 */
	public function delChannel() {
		$channel_model = getInstance('model.sdkChannel.channel');

		$success = $channel_model->delete($this->_REQUEST['id']);
		if($success) {
			ShowMsg('删除成功，正返回列表', '/index.php?m=sdkChannel&a=listChannel');exit;
		}

		ShowMsg('删除失败，正在返回列表', -1);exit;
	}

	/**
	 * 获取某游戏下所有渠道
	 */
	public function getGameChannels() {
		$server = '';
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		if (empty($_POST['pvc'])) {
			if (!empty($_POST['game'])) {
				$channel_model = getInstance('model.sdkChannel.channel');

				if($_POST['uid']){
					$gidarr = $channel_model->returnUidGroup($_POST['uid']);
					if (!empty($gidarr[0]['channelId'])) {
						$agentChannel = explode(',', $gidarr[0]['channelId']);
						foreach ($channels as $key => $value) {
							foreach ($agentChannel as $key1 => $value1) {
								if ($value1 == $key) {
									$ser['channelId'] = $value1;
									$ser['channelName'] = $channels[$key];
									$servers[] = $ser;
								}
							}
						}
					}else{
						$servers = $channel_model->getGameChannels($_POST['game']);
					}
				}else{
					$servers = $channel_model->getGameChannels($_POST['game']);
				}

				if ($servers) {
					$server .= '<option value="">请选择</option>';
					foreach ($servers as $key => $value){
						$c_channel = '';
						if ($value['channelName']) {
							$c_channel = (trim($_POST['channelId']) == $value['channelId']) ? 'selected="selected"' : '';
							$server .= '<option value="' . $value['channelId'] . '"' . $c_channel . '>' . $value['channelName'] . '</option>';
						}
					}
				}
			}

			if (empty($server)) {
				$server = '<option value="">无渠道数据</option>';
			}
		}else{
			$server .= '<option value="">请选择</option>';
			foreach ($channels as $k => $v) {
				$selected = (trim($_POST['channelId']) == $k) ? 'selected="selected"' : '';
				$server .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
			}
		}

		echo $server;exit;
	}

	/*
	* 保存乾游渠道折扣修改记录，减少计算利润模块的误差
	 */
	public function qyDiscountConf($channelData) {
		$config = require(APP_LIST_PATH . "oss/config.qyDiscount.php");
		$channel_model = new model('ms_channel');
		$channel = $channel_model->get("`gameAlias`='" . $channelData['gameAlias'] . "' AND `channelId`= '160068' AND `apkNum`='" . $channelData['apkNum'] . "'");
		//避免网络延迟、操作等原因导致记录为第二天因此减去600秒
		$day = date('Y-m-d', time() - 600);
		if ($channel['exProportion'] != $channelData['exProportion']) {
			$channel['exProportion'] = $channel['exProportion'] ? $channel['exProportion'] : '1';
			foreach ($config as $key => $value) {
				if ($key != $day) {
					unset($config[$key]);
				}
			}
			$config[$day][$channelData['gameAlias']][$channelData['apkNum']] = $channel['exProportion'];

			$output = "<?php";
			$output .= "\n";
			$output .= "return ";
			$output .= var_export($config, true);
			$output .= ";";
			file_put_contents(APP_LIST_PATH . "oss/config.qyDiscount.php", $output);
		}

		return true;
	}
}