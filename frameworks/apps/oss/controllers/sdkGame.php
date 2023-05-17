<?php
//error_reporting(E_ALL);

require_once APP_CONTROLLER_PATH . '/master.php';

class sdkGameController extends masterControl {

	/**
	 * 游戏分类管理
	 */
	public function category() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		//注入操作标识，用它来告诉程序显示哪些视图
		$this->assign('operation', $operation);

		//初始化分类模型
		$category_model = getInstance('model.sdkGame.category');
		switch($operation) {
			case 'index':
				$this->assign('category_list', $category_model->getList());
				break;

			case 'edit':
				$category = $category_model->getInfo( $_GET['id'] );
				if(empty($category)) {
					ShowMsg('分类不存在', -1);
				}
				$this->assign('category', $category);
				break;

			case 'add':
				//do nothing
				break;

			case 'save':
				//检查是新增还是编辑
				$category = $category_model->getInfo( $_POST['id'] );
				$is_new = empty($category);
				//初始化结果值
				$success = false;
				if($is_new) {
					$success = $category_model->add(array(
					'id' => $_POST['id'],
					'name' => $_POST['name'],
					'sort' => $_POST['sort']
					));
				} else {
					$success = $category_model->edit(
					$_POST['id'],
					array(
					'name' => $_POST['name'],
					'sort' => $_POST['sort']
					)
					);
				}
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=category');
				break;

			case 'del':
				//包含游戏的分类不能删除
				//初始化游戏模型
				$game_model = getInstance('model.sdkGame.game');
				$game_total = $game_model->getTotalByCategory( $_GET['id'] );
				if($game_total > 0) {
					ShowMsg('操作失败，该分类下的游戏不為空', -1);
				}
				$category_model->delete( $_GET['id'] );
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=category');
				break;
		}
	}

	/**
	 * 游戏管理
	 */
	public function game() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del', 'view');
		$request_oper = array_key_exists('operation', $_REQUEST) ? $_REQUEST['operation'] : '';
		$operation = in_array($request_oper, $operation_list) ? $request_oper : 'index';
		//注入操作标识，用它来告诉程序显示哪些视图
		$this->assign('operation', $operation);
		$uid_list = array('luojiang', 'baohuan', 'heyongzhen', 'yangzhenwei', 'chenjh', 'guojian', 'liuyuwen','guofengchi');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';
		$editor = $checkRoot;
		$this->assign('editor', $editor);
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 15) {
			$checkRoot = 1;
		}
		$this->assign('checkRoot', $checkRoot);
		$this->assign('gid', $gid);
		//游戏
		$game_model = getInstance('model.sdkGame.game');

		/*$game_list = $game_model->getList(0, 1000);
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);*/
		$statistics_model = getInstance('model.statistics');

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

		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}

		$this->assign('UpperList', $UpperList);
		/*$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);*/
		//获取专服游戏名
		/*$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);*/
		/*$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);*/
		//取得一级游戏数据
		/*if ($upperName && empty($_REQUEST['game'])) {
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
		}*/

		//初始化游戏模型
		$game_model = getInstance('model.sdkGame.game');
		//初始化分类模型
		$category_model = getInstance('model.sdkGame.category');

		$category_list = $category_model->getList();

		//能同步ios数据的渠道
		//因已接入渠道较多，故不引用anyChannels.php
		$syn = array(
		'500009' => '昆游', 
		'500011' => 'IOS', 
		'500015' => '狸猫',
		'160068' => '乾游',
		'500031' => '神起',
		'500005' => 'Quick',
		'500026' => '悠谷',
		'500043' => '蓝色火焰',
		'160198' => '小7手游',
		'500028' => '达咖玩',
		'000066' => '小米',
		'000368' => 'VIVO',
		'000020' => 'OPPO',
		'500001' => '华为（253）',
		'500010' => '鲸天互娱',
		'500067' => '永乐',
		);
		$this->assign('syn', $syn);
		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;

				$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
				$status = trim($_REQUEST['status']) ? trim($_REQUEST['status']) : "";
				$keywords = trim($_REQUEST['keywords']) ? trim($_REQUEST['keywords']) : "";

				$payMethod = intval($_REQUEST['payMethod']) ? intval($_REQUEST['payMethod']) : "";
				$this->assign('payMethod', $payMethod);

				if ($payMethod == 1) {
					$weixinType = trim($_REQUEST['wxAppType']) ? trim($_REQUEST['wxAppType']) : "";
					$alipayType = trim($_REQUEST['aliAppType']) ? trim($_REQUEST['aliAppType']) : "";
				}elseif ($payMethod == 2) {
					$weixinType = trim($_REQUEST['weixinType']) ? trim($_REQUEST['weixinType']) : "";
					$alipayType = trim($_REQUEST['alipayType']) ? trim($_REQUEST['alipayType']) : "";
				}

				$game_list = $game_model->formatData( $game_model->getList($offset, $length, $game, $status, $keywords, $weixinType, $alipayType, $payMethod, $gameStr), 'detail' );
				$total_row = $game_model->getTotal($offset, $length, $game, $status, $keywords, $weixinType, $alipayType, $payMethod, $gameStr);

				foreach($game_list as $key => $value){
					$game_list[$key]['size'] = round( filesize( C('DEDE_DATA_PATH').$value['app_file']) / (1024 * 1024) ) . "M";
					$game_list[$key]['tokenMd5'] = md5($value['token']);
				}
				$this->assign('game_list', $game_list);
				$this->assign('list_length', $length);
				$this->assign('list_total', $total_row);
				$this->assign('list_page', $page);
				$this->assign('game', $game);
				$this->assign('status', $status);
				$this->assign('keywords', $keywords);
				$this->assign('weixinType', $_REQUEST['weixinType']);
				$this->assign('alipayType', $_REQUEST['alipayType']);
				$this->assign('wxAppType', $_REQUEST['wxAppType']);
				$this->assign('aliAppType', $_REQUEST['aliAppType']);
				break;

			case 'add':
				$this->assign('category_list', $category_list);
				break;

			case 'edit':
				$game = $game_model->getInfo($_GET['id']);
				$game_list = $game_model->getList();
				$games = array();
				foreach ($game_list as $key => $value) {
					$games[$value['alias']] = $value['name'];
					if ($value['alias'] == $game['relateGame']) {
						$game['relateUpper'] = $value['upperName'];
						$game['relateSpecial'] = $value['specialName'];
					}
				}
				$this->assign('games', $games);

				$channelSyn = explode('|', $game['channelSyn']);
				//可以用游戏别名来进行编辑
				if(empty($game) && isset($_GET['game'])) {
					$game = $game_model->getInfoByAlias($_GET['game']);
				}
				if(empty($game)) {
					ShowMsg('游戏不存在', -1);
				}
				$game = $game_model->formatData( $game, 'detail' );

				//获取专服游戏名
				$specialList = $statistics_model->getSpecialList($game['relateUpper']);
				$this->assign('static', C(STATIC_SOURCE_SITE));
				$this->assign('specialList', $specialList);
				$this->assign('game', $game);
				$this->assign('push_games', explode(',', $game['push_games']));
				$this->assign('category_list', $category_list);
				$this->assign('channelSyn', $channelSyn);
				break;

			case 'save':
				
				/*if ($_POST['link']) {
				$temapp = explode('-', $_POST['link']);
				if (count($temapp) > 1) {
				ShowMsg('游戏包格式错误，请勿使用“-”符', -1);exit;
				}
				}

				//保存之前做一些必要的检查
				$category = $category_model->getInfo($_POST['category']);
				if(empty($category)) {
				ShowMsg('分类不存在', -1);
				}*/

				if(empty($_POST['name'])) {
					ShowMsg('游戏名不能为空', -1);
				}

				//检查是新增还是编辑
				$game = $game_model->getInfo($_POST['id']);
				$is_new = empty($game);

				if($is_new) {
					if(empty($_POST['alias'])) {
						ShowMsg('游戏別名不能为空', -1);
					}
					$game = $game_model->getInfoByAlias($_POST['alias']);
					if(!empty($game)) {
						ShowMsg('游戏別名不能重复','-1');
					}
				}
				//H5服务商与主体
				$weixinType = trim($_REQUEST['weixinType']) ? trim($_REQUEST['weixinType']) : "";
				$alipayType = trim($_REQUEST['alipayType']) ? trim($_REQUEST['alipayType']) : "";
				$channelSyn = implode('|', $_REQUEST['channelSyn']);

				//APP、APP+、小程序服务商与主体
				$wxAppType = trim($_REQUEST['wxAppType']) ? trim($_REQUEST['wxAppType']) : "";
				$aliAppType = trim($_REQUEST['aliAppType']) ? trim($_REQUEST['aliAppType']) : "";

				//CP分成（默认0.2）
				$proportion = trim($_REQUEST['proportion']) ? trim($_REQUEST['proportion']) : "";


				$data = array(
				'alias' => $is_new ? trim($_POST['alias']) : $game['alias'],
				'stars' => $_POST['stars'],
				'name' => trim($_POST['name']),
				'detail' => $_POST['detail'],
				'category' => $_POST['category'],
				'sort' => $_POST['sort'],
				'type' => implode(',', $_POST['type']),
				'status' => intval($_POST['status']),
				'ispay' => intval($_POST['ispay']),
				'developer' => $_POST['developer'],
				'date' => strtotime($_POST['date']),
				'scale' => intval($_POST['scale']),
				'developer' => $_POST['developer'],
				'monetaryUnit' => $_POST['monetaryUnit'],
				'token' => $_POST['token'],
				'serverKey' => $_POST['serverKey'],
				'callbackUrl' => $_POST['callbackUrl'],
				'isration' => intval($_POST['isration']),
				'visibleFloat' => intval($_POST['visibleFloat']),
				'logger' => intval($_POST['logger']),
				'packageName' => trim($_POST['packageName']),
				'upperName' => trim($_POST['upperName']),
				'specialName' => trim($_POST['specialName']),
				'weixinType' => $weixinType,
				'alipayType' => $alipayType,
				'payMethod' => intval($_POST['payMethod']),
				'wxAppType' =>  $wxAppType,
				'aliAppType' => $aliAppType,
				'channelSyn' => $channelSyn,
				'upGradeMark' => intval($_POST['upGradeMark']),
				'h5Url' => trim($_POST['h5Url']),
				'relateGame' => trim($_POST['relateGame']),
				'cpName' => trim($_POST['cpName'])
				);
				if ($checkRoot == 1) {
					$data['proportion'] = $proportion;
					$data['cpAllowance'] = trim($_POST['cpAllowance']);
				}
				//处理附件
				load('uploadfile');
				$upload_config = array(
				array(
				'name' => '底图',
				'fields' => 'basePic',
				'filetypes' => array('png', 'gif', 'jpg', 'jpeg'),
				'path' => 'qyAnySDK/img/'
				)
				);

				foreach($upload_config as $config) {
					$files = $_FILES[ $config['fields'] ];
					if(!empty($files['name'][0])) {
						$path = C('DEDE_DATA_PATH') . $config['path'];
						if (!file_exists($path)) {
							@mkdir($path);
						}
						$upload = new uploadfile($files, $path, 999999999999, $config['filetypes']);

						$file_name = $data['alias'] . '_' . time();
						$success = !!$upload->upload( $file_name );
						if(!$success) {
							ShowMsg("上傳{$config['name']}失败", -1);
						}
						$data[ $config['fields'] ] = $config['path'] . $file_name . '.' .
						pathinfo($files['name'][0], PATHINFO_EXTENSION);

						if($config['fields'] === 'app_file') {
							$data['app_size'] = round( $files['size'][0] / 1024 );
						}
					}
				}

				if($is_new) {
					$game_model->add($data);
				} else {
					$game_model->edit($_POST['id'], $data);
				}
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=game');
				break;

			case 'del':
				$game_model->delete( $_GET['id'] );
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=game');
				break;

			case 'view':
				$game = $game_model->getInfo($_GET['id']);
				$gameList = $game_model->getList();
				foreach ($gameList as $k => $v) {
					if ($game['relateGame'] == $v['alias']) {
						$game['relateUpper'] = $v['upperName'];
						$game['relateSpecial'] = $v['specialName'];
						$game['relateName'] = $v['name'];
					}
				}
				$this->assign('game', $game);
				break;
		}
	}


	/**
	 * 广告管理
	 */
	public function adv() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		//注入操作标识，用它来告诉程序显示哪些视图
		$this->assign('operation', $operation);

		//初始化分类模型
		$adv_model = getInstance('model.gamebox.adv');


		//游戏
		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList(0, 100);
		$games = array();
		foreach ($game_list as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		switch($operation) {
			case 'index':
				$adv_list = $adv_model->formatData( $adv_model->getList() );
				$this->assign('adv_list', $adv_list);
				break;

			case 'edit':
				$adv = $adv_model->getInfo( $_GET['id'] );
				if(empty($adv)) {
					ShowMsg('广告不存在', -1);
				}
				$adv = $adv_model->formatData($adv);
				$this->assign('adv', $adv);
				break;

			case 'add':
				//do nothing
				break;

			case 'save':
				//检查是新增还是编辑
				$is_new = ($_POST['is_new'] == 1);
				$adv = $adv_model->getInfo( $_POST['id'] );
				if($is_new && !empty($adv)) {
					ShowMsg('广告标示已存在', -1);
				}

				$data = array(
				'game' => $_POST['game'],
				'type' => $_POST['type'],
				'link' => $_POST['link'],
				//'itunes_id' => $_POST['itunes_id'],
				);
				//处理附件
				load('uploadfile');
				if(!empty($_FILES['image']['name'][0])) {
					$filetypes = array('png', 'gif', 'jpg', 'jpeg');
					$dir = '/sdkfile/adv/';
					$path = C('DEDE_DATA_PATH') . $dir;
					if (!file_exists($path)) {
						@mkdir($path);
					}
					$upload = new uploadfile($_FILES['image'], $path, 999999999999, $filetypes);

					$file_name = $_POST['id'] . '_' . time();
					$success = !!$upload->upload( $file_name );
					if(!$success) {
						ShowMsg("上传图片失败", -1);
					}
					$data['image'] = $dir . $file_name . '.' .
					pathinfo($_FILES['image']['name'][0], PATHINFO_EXTENSION);
				}

				if($is_new) {
					$data['id'] = $_POST['id'];
					$adv_model->add($data);
				} else {
					$adv_model->edit($_POST['id'], $data);
				}
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=adv');
				break;

			case 'del':
				$adv_model->delete( $_GET['id'] );
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=adv');
				break;
		}
	}

	/**
	 * 根据专服游戏名获取后台游戏名
	 */
	public function getGameName() {
		$gameName = '';

		if (!empty($_POST['specialName'])) {
			$game_model = getInstance('model.sdkGame.game');
			//$games = $game_model->getGameName($_POST['upperName'], $_POST['specialName']);
			if ($_POST['gid'] == 2 || $_POST['gid'] == 11 || $_POST['gid'] == 13 || $_POST['gid'] == 14 || $_POST['gid'] == 19 || $_POST['gid'] == 17 || $_POST['gid'] == 15 || $_POST['gid'] == 22) {
				$games = $game_model->getGameName($_POST['upperName'], $_POST['specialName'], $_POST['gameStr']);
			}else{
				$games = $game_model->getGameName($_POST['upperName'], $_POST['specialName']);
			}

			if ($games) {
				$gameName .= '<option value="">请选择</option>';
				foreach ($games as $key => $value){
					$gameList = '';
					if ($value['name']) {
						$gameList = (trim($_POST['game']) == $value['alias']) ? 'selected="selected"' : '';
						$gameName .= '<option value="' . $value['alias'] . '"' . $gameList . '>' .$value['name'] . '</option>';
					}
				}
			}
		}

		if (empty($gameName)) {
			$gameName = '<option value="">无渠道数据</option>';
		}

		echo $gameName;exit;
	}

	/**
	 * 根据接入的游戏名获取专服游戏名
	 */
	public function getSpecialName() {
		$gameName = '';

		if (!empty($_POST['upperName'])) {
			$game_model = getInstance('model.sdkGame.game');
			if ($_POST['gid'] == 2 || $_POST['gid'] == 11 || $_POST['gid'] == 13 || $_POST['gid'] == 14 || $_POST['gid'] == 19 || $_POST['gid'] == 17 || $_POST['gid'] == 15 || $_POST['gid'] == 22) {
				$games = $game_model->getSpecialName($_POST['upperName'], $_POST['gameStr']);
				
			}else{
				$games = $game_model->getSpecialName($_POST['upperName']);
			}
			if ($games) {
				$gameName .= '<option value="">请选择</option>';
				foreach ($games as $key => $value){
					$gameList = '';
					if ($value['specialName']) {
						$gameList = (trim($_POST['specialName']) == $value['specialName']) ? 'selected="selected"' : '';
						$gameName .= '<option value="' . $value['specialName'] . '"' . $gameList . '>' . $value['specialName'] . '</option>';
					}
				}
			}
		}

		if (empty($gameName)) {
			$gameName = '<option value="">无渠道数据</option>';
		}
		

		echo $gameName;exit;
	}

	/**
	 * 游戏指定ip登录屏蔽（封禁IP或设备）
	 */
	public function baned() {
		//初始化游戏模型
		$game_model = getInstance('model.sdkGame.game');
		$operation = trim($_REQUEST['operation']) ? trim($_REQUEST['operation']) : "index";

		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;

				$banedKey = trim($_REQUEST['banedKey']) ? trim($_REQUEST['banedKey']) : "";
				//时间范围
				if (!empty($_REQUEST['start_date'])) {
					$start = strtotime($_REQUEST['start_date']);
				}
				if (!empty($_REQUEST['end_date'])) {
					$end = strtotime($_REQUEST['end_date'] . '23:59:59');
				}

				$banedList = $game_model->getBanedList($offset, $length, $banedKey, $start, $end);
				$banedTotal = $game_model->getBanedTotal($offset, $length, $banedKey, $start, $end);

				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);
				$this->assign('banedList', $banedList);
				$this->assign('banedKey', $banedKey);
				$this->assign('list_length', $length);
				$this->assign('list_total', $banedTotal);
				$this->assign('list_page', $page);
				break;
			case 'insert':
				$data['baned'] = trim($_REQUEST['banedKey']);
				$data['time'] = time();
				$data['uid'] = $this->_uid;

				$whiteList = array('02:00:00:00:00:00', '00000000-0000-0000-0000-000000000000');
				if (in_array($data['baned'], $whiteList)) {
					ShowMsg('该设备号或IP不允许屏蔽', '/index.php?m=sdkGame&a=baned');
				}

				$add = $game_model->banedAdd($data);
				if ($add) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=baned');
				}else{
					ShowMsg('重复提交', '/index.php?m=sdkGame&a=baned');
				}
				break;
			case 'del':
				$game_model->banedDelete($_GET['id']);
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=baned');
				break;
		}
	}

	/**
	 * 游戏区服
	 */
	public function gameServer() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$request_oper = array_key_exists('operation', $_REQUEST) ? $_REQUEST['operation'] : '';
		$operation = in_array($request_oper, $operation_list) ? $request_oper : 'index';

		$this->assign('operation', $operation);

		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		
		//获取上级游戏名
		if (($gid == 17 || $gid == 15) && $gidarr[0]['game'] != 'all') {
			$explode = explode('|', $gidarr[0]['game']);
			foreach ($explode as $k => $v) {
				$gameStr .= "'" . $v . "',";
			}
			$gameStr = substr($gameStr,0,-1);
			$this->assign('gameStr', $gameStr);
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else {
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

		//取得上级游戏数据
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

		if (($gid == 17 || $gid == 15) && $UpperList && empty($upperName)) {
			$upperName = '';
			foreach ($UpperList as $key => $value) {
				$upperName .= "'". $value['upperName']. "',";
			}
			$upperName = substr($upperName, 0, -1);
		}

		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 25;
				$offset = ($page - 1) * $length;

				$reference = $_REQUEST['reference'] ? $_REQUEST['reference'] : "";

				$game_list = $game_model->getServerList($offset, $length, $upperName, $specialName, $reference);
				$total = $game_model->getServerTotal($upperName, $specialName, $reference);
				$this->assign('game_list', $game_list);
				$this->assign('list_length', $length);
				$this->assign('list_total', $total);
				$this->assign('list_page', $page);
				$this->assign('reference', $reference);	
				break;

			case 'add':
				break;

			case 'edit':
				$gameList = $game_model->getServerInfo($_GET['id']);
				$this->assign('gameList', $gameList);
				break;

			case 'save':
				if(empty($_POST['upperName']) || 
					empty($_POST['specialName']) ||
					empty($_POST['gameServerName']) ||
					empty($_POST['gameServerNum']) ||
					empty($_POST['cpServerNum'])
					) {
					ShowMsg('参数不能为空', -1);
				}

				$data = array(
				'upperName' => $_POST['upperName'],
				'specialName' => $_POST['specialName'],
				'gameServerName' => $_POST['gameServerName'],
				'gameServerNum' => $_POST['gameServerNum'],
				'cpServerNum' => $_POST['cpServerNum'],
				'reference' => $_POST['reference']
				);

				if($_POST['is_new']) {
					$game_model->serverAdd($data);
				} else {
					$game_model->serverEdit($_POST['id'], $data);
				}

				ShowMsg('操作成功', '/index.php?m=sdkGame&a=gameServer');
				break;
			case 'del':
				$game_model->serverDelete( $_GET['id'] );
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=gameServer');
				break;
		}
	}


	/**
	 * 获取游戏区服号
	 */
	public function getGameServer() {
		$option = '';

		if (!empty($_POST['specialName'])) {
			$game_model = getInstance('model.sdkGame.game');
			
			$gameServer = $game_model->getServerList('','',$_POST['upperName'], $_POST['specialName']);

			if ($gameServer) {
				$option .= '<option value="">请选择</option>';
				$gameList = '';
				foreach ($gameServer as $key => $value){
					if ($value['cpServerNum']) {
						$gameList = (trim($_POST['server']) == $value['cpServerNum']) ? 'selected="selected"' : '';
						$option .= '<option value="' . $value['cpServerNum'] . '"' . $gameList . '>' . $value['gameServerNum'] . '(' .$value['cpServerNum'] . ')</option>';
					}
				}
			}
		}

		if (empty($option)) {
			$option = '<option value="">无相关数据</option>';
		}

		echo $option;exit;
	}


	/**
	 * 游戏项目支出管理
	 */
	public function gamePay() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$request_oper = array_key_exists('operation', $_REQUEST) ? $_REQUEST['operation'] : '';
		$operation = in_array($request_oper, $operation_list) ? $request_oper : 'index';

		$this->assign('operation', $operation);

		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');

		
		$uid_list = array('luojiang', 'root', 'yangzhenwei', 'chenjh', 'guojian', 'wangyinping','heyongzhen','guofengchi');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';
		//优化组权限
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		if ($gid == 17 || $gid == 15 || $gid == 24) {
			$checkRoot = 1;
		}
		$this->assign('checkRoot', $checkRoot);

		//获取上级游戏名
		// if ( ($gid == 17 || $gid == 15) && $gidarr[0]['game'] != 'all') {
		// 	$explode = explode('|', $gidarr[0]['game']);
		// 	foreach ($explode as $k => $v) {
		// 		$gameStr .= "'" . $v . "',";
		// 	}
		// 	$gameStr = substr($gameStr,0,-1);
		// 	$this->assign('gameStr', $gameStr);
		// 	$UpperList = $statistics_model->getUpperListGs($gameStr);
		// }else {
		// 	$UpperList = $statistics_model->getUpperList();
		// }
		
		$UpperList = $statistics_model->getUpperList();
		
		$this->assign('UpperList', $UpperList);
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);

		$this->assign('gid', $gid);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
		$committe_apknum  = require(APP_LIST_PATH . "oss/config.apkNum.php");
		$this->assign('committe_apknum', $committe_apknum);
		$gameList = $game_model->getList();
		$games = array();
		foreach ($gameList as $key => $value) {
			$games[$value['alias']] = $value['name'];
		}
		$this->assign('games', $games);

		switch($operation) {
			case 'index':
				$marray = array(
					'1' => '公司模块', 
					'2' => '项目模块',
					'3' => '游戏模块',
					);
				$tarray = array(
					'公司模块' => array(
						'1' => '资质费用', 
						'2' => '推广费用', 
						'3' => '企业签', 
						'4' => '服务器', 
						), 
					'项目模块' => array(
						'1' => '项目支出', 
						), 
					'游戏模块' => array(
						'1' => '自充支出', 
						'2' => '推广红包', 
						'3' => '返利补单', 
						'4' => '渠道推广', 
						), 
					);
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 25;
				$offset = ($page - 1) * $length;
				$specialList = $statistics_model->getSpecialList($upperName);
				$this->assign('specialList', $specialList);

				$start_date = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : "";
				$end_date = $_REQUEST['end_date'] ? $_REQUEST['end_date'] : "";
				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);
				$specialName = $_REQUEST['specialName'] ? $_REQUEST['specialName'] : "";
				$this->assign('specialName', $_REQUEST['specialName']);

				$game = $_REQUEST['game'] ? $_REQUEST['game'] : "";
				$this->assign('gameAlias', $_REQUEST['game']);
				$channel = $_REQUEST['channel'] ? $_REQUEST['channel'] : "";
				$this->assign('channel', $_REQUEST['channel']);
				$apkNum = $_REQUEST['apkNum'] ? $_REQUEST['apkNum'] : "";
				$this->assign('apkNum', $_REQUEST['apkNum']);

				$module = $_REQUEST['module'] ? $_REQUEST['module'] : "";
				$this->assign('module', $_REQUEST['module']);
				$type = $_REQUEST['type'] ? $_REQUEST['type'] : "";
				$this->assign('type', $_REQUEST['type']);

				$game_list = $game_model->getGamePayList($offset, $length, $start_date, $end_date, $upperName, $specialName, $game, $channel, $apkNum, $module, $type);
				$total = $game_model->getGamePayListTotal($start_date, $end_date, $upperName, $specialName, $game, $channel, $apkNum, $module, $type, 'count');
				$sum = $game_model->getGamePayListTotal($start_date, $end_date, $upperName, $specialName, $game, $channel, $apkNum, $module, $type, 'sum');

				//只可编辑一个月内的数据
				$stopDate = date("Y-m-d",strtotime("-1 month"));
				foreach ($game_list as $key => $value) {
					if ($value['date'] >= $stopDate) {
						$game_list[$key]['changeRoot'] = 1;
					}else{
						$game_list[$key]['changeRoot'] = '';
					}
					if ($this->_uid == 'chenjh' || $this->_uid == 'heyongzhen') {
						$game_list[$key]['changeRoot'] = 1;
					}
					$game_list[$key]['moduleName'] = $marray[$value['module']];
					$game_list[$key]['typeName'] = $tarray[$marray[$value['module']]][$value['type']];
					$game_list[$key]['channelName'] = $channels[$value['channelId']];
				}
				$this->assign('game_list', $game_list);
				$this->assign('list_length', $length);
				$this->assign('list_total', $total);
				$this->assign('list_page', $page);
				$this->assign('sum', $sum);
				break;

			case 'add':
				break;

			case 'edit':
				$gameList = $game_model->getgamePayInfo($_GET['id']);
				$specialList = $statistics_model->getSpecialList($gameList['upperName']);

				$this->assign('specialList', $specialList);

				$this->assign('gameList', $gameList);
				$this->assign('type', $gameList['type']);

				break;

			case 'save':
				if(empty($_POST['module']) || empty($_POST['type']) || empty($_POST['date']) || empty($_POST['pay'])) {
					ShowMsg('必要参数不能为空', -1);exit;
				}
				if ($_POST['date'] > date('Y-m-d',time()) ) {
					ShowMsg('添加日期不能超过当前日期', -1);exit;
				}
				$profitTmp = '';
				$profitModel = new model('ms_profit_daily');
				if ($_POST['module'] == 3) {
					if(empty($_POST['game']) || empty($_POST['channel']) || empty($_POST['apkNum']) || empty($_POST['pattern'])) {
						ShowMsg('必要参数不能为空', -1);exit;
					}
					//(`gameAlias`,`channelId`,`apkNum`,`date`,`type`)
					$profitTmp = $profitModel->get("`gameAlias`='{$_POST['game']}' AND `channelId`='{$_POST['channel']}' AND `apkNum`='{$_POST['apkNum']}' AND `date`='{$_POST['date']}' AND `type`='{$_POST['pattern']}'");
					//var_dump($tmp);var_dump("`gameAlias`='{$_POST['game']}' AND `channelId`='{$_POST['channel']}' AND `apkNum`='{$_POST['apkNum']}' AND `date`='{$_POST['date']}' AND `type`='{$_POST['pattern']}'");exit;
					if ($_POST['type'] != 4) {
						if (!$profitTmp) {
							ShowMsg('无当天充值数据,不可以添加', -1);exit;
						}
					}
				}
				$game = $game_model->getInfoByAlias($_POST['game']);

				$data = array(
				'upperName' => $_POST['upperName'],
				'specialName' => $_POST['specialName'],
				'gameAlias' => $_POST['game'],
				'gameName' => $game['name'],
				'channelId' => $_POST['channel'],
				'apkNum' => $_POST['apkNum'],
				'module' => $_POST['module'],
				'type' => $_POST['type'],
				'date' => $_POST['date'],
				'pay' => $_POST['pay'],
				'remark' => $_POST['remark'],
				'pattern' => $_POST['pattern']
				);

				$gameList = $game_model->getgamePayInfo($_POST['id']);

				if(!$gameList) {
					$repeat = 0;
					foreach ($gamePayList as $key => $value) {
						if ($value['date'] == $_POST['date']) {
							$repeat = 1;
						}
					}
					if ($repeat == 1) {
						ShowMsg('已有该数据，如需修改请到对应数据修改', '/index.php?m=sdkGame&a=gamePay');exit;
					}
					$game_model->gamePayAdd($data);
				} else {
					$game_model->gamePayEdit($_POST['id'], $data);
				}
				if ($_POST['module'] == 3 && $_POST['type'] == 4 && !$profitTmp) {
					require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
					$array['channelId'] = $_POST['channel'];
					$array['channelName'] = $channels[$_POST['channel']];
					$array['gameAlias'] = $_POST['game'];
					$array['gameName'] = $game['name'];
					$array['apkNum'] = $_POST['apkNum'];
					$array['date'] = $_POST['date'];
					$array['type'] = $_POST['pattern'];
					//$array['exPay'] = $_POST['pay'];
					$profitModel->set($array);
				}
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=gamePay');exit;
				break;
			case 'del':
				$game_model->gamePayDelete( $_GET['id'] );
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=gamePay');exit;
				break;
		}
	}

	/**
	 * 玩家账号特殊处理(封禁账号)
	 * 
	 * 注意：目前账号封禁和渠道配置中的注册登录限制功能没有做关联，如果渠道配置中限制登录注册，那么添加白名单是无效的
	 */
	public function specialList() {
		//初始化游戏模型
		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');
		$operation = trim($_REQUEST['operation']) ? trim($_REQUEST['operation']) : "index";
		$this->assign('operation', $operation);

		
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		$this->assign('gid', $gid);

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

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
		//获取上级游戏名
		if ( ( $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else {
			$UpperList = $statistics_model->getUpperList();
		}
		
		$this->assign('UpperList', $UpperList);

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
			}
		}
		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;

				$rgame = $_REQUEST['rgame'] ? $_REQUEST['rgame'] : '';
				$this->assign('rgame', $rgame);

				$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
				$type = trim($_REQUEST['type']) ? trim($_REQUEST['type']) : "";

				$sList = $game_model->getSpecialList($offset, $length, $userName, $rgame, $sumString, $specialString, $type, $gameStr);
				$sTotal = $game_model->getSpecialTotal($userName, $rgame, $sumString, $specialString, $type, $gameStr);
				$gameList = $game_model->getList();
				foreach ($sList as $key => $value) {
					foreach ($gameList as $k => $v) {
						if ($value['gameAlias'] == $v['alias']) {
							$sList[$key]['upperName'] = $v['upperName'];
							$sList[$key]['gameName'] = $v['name'];
						}
						if ($value['type'] == 'whiteList' && $value['ext'] == $v['alias']) {
							$sList[$key]['relateGame'] = $v['upperName'].' / '.$v['name'];
						}
					}

				}
				$games = array();
				foreach ($gameList as $key => $value) {
					$games[$value['alias']] = $value['name'];
				}

				$this->assign('games', $games);
				$this->assign('sList', $sList);
				$this->assign('type', $type);
				$this->assign('userName', $userName);
				$this->assign('list_length', $length);
				$this->assign('list_total', $sTotal);
				$this->assign('list_page', $page);
				break;
			case 'add':
				//do nothing
				break;
			case 'save':
				if (empty($_REQUEST['userName']) && empty($_REQUEST['userNameStr'])) {
					ShowMsg('账号不能为空', '-1');
				}
				if (!empty($_REQUEST['userName']) && !empty($_REQUEST['userNameStr'])) {
					ShowMsg('批量处理和单用户处理不能同时进行', '-1');
				}
				/*if(!preg_match("/^[a-zA-Z]{2}[0-9]{6}$/", $_REQUEST['userName']) && !empty($_REQUEST['userName'])){
		            ShowMsg('账号格式有误，请重新核实', '-1');
		        }*/
				if ($_REQUEST['all'] != '1' && empty($_REQUEST['game'])) {
					ShowMsg('请选择游戏', '-1');
				}elseif($_REQUEST['all'] == '1' && !empty($_REQUEST['game'])){
					$_REQUEST['game'] = '';
				}
				if ($_REQUEST['all'] == '1' && $_REQUEST['type'] == 'whiteList') {
					ShowMsg('白名单不支持全游戏处理', '-1');
				}
				if (empty($_REQUEST['whiteGame']) && $_REQUEST['type'] == 'whiteList' && empty($_REQUEST['whiteType'])) {
					ShowMsg('关联游戏不能为空', '-1');
				}
				$data['userName'] = trim($_REQUEST['userName']);
				$data['time'] = time();
				$data['uid'] = $this->_uid;
				$data['gameAlias'] = $_REQUEST['game'];
				if ($_REQUEST['type'] == 'ban') {
					if ($_REQUEST['all'] == '1') {
						$data['type'] = 'allBan';
					}else{
						$data['type'] = 'singleBan';
					}
				}elseif ($_REQUEST['type'] == 'whiteList') {
					$data['type'] = 'whiteList';
					// 当白名单类型为允许模拟器登录，则保存白名单类型到ext字段，增加扩展字段使用率
					if($_REQUEST['whiteType'] == 'simulator'){
						$data['ext'] = trim($_REQUEST['whiteType']);
					}else{
						$data['ext'] = trim($_REQUEST['whiteGame']);
					}
				}
				$add = $game_model->specialBatchAdd($_REQUEST['userNameStr'], $data);
				/*if ($_REQUEST['batch'] == 1) {
					$add = $game_model->specialBatchAdd($_REQUEST['userNameStr'], $data);
				}else{
					$add = $game_model->specialAdd($data);
				}*/
				if ($add) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=specialList');
				}else{
					ShowMsg('操作失败', '/index.php?m=sdkGame&a=specialList');
				}
				break;
			case 'del':
				$game_model->specialDelete($_GET['id']);
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=specialList');
				break;
		}
	}


	/**
	 * 游戏渠道隔离
	 */
	public function isolate() {
		//初始化游戏模型
		$game_model = getInstance('model.sdkGame.game');
		$statistics_model = getInstance('model.statistics');
		$operation = trim($_REQUEST['operation']) ? trim($_REQUEST['operation']) : "index";
		$this->assign('operation', $operation);

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

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$this->assign('game', $game);
		$status = trim($_REQUEST['status']) ? trim($_REQUEST['status']) : "";
		$this->assign('status', $status);
		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;

				$rgame = $_REQUEST['rgame'] ? $_REQUEST['rgame'] : '';
				$this->assign('rgame', $rgame);

				$isList = $game_model->getIsolateList(null, null, $upperName, $specialName, $game, $status);
				$isTotal = $game_model->getIsolateTotal($upperName, $specialName, $game, $status);

				foreach ($isList as $key => $value) {
					if ($value['barringChannel']) {
						$cList = explode(',', $value['barringChannel']);
						$cName = array();
						foreach ($cList as $k => $v) {
							$cName[] = $channels[$v];
						}
						$isList[$key]['barringChannelName'] = implode(',', $cName);
					}
				}

				$this->assign('isList', $isList);
				$this->assign('list_length', $length);
				$this->assign('list_total', $isTotal);
				$this->assign('list_page', $page);
				break;
			case 'add':
				//do nothing
				break;
			case 'edit':
				$isolate = $game_model->getIsolateInfo($_GET['id']);
				if ($isolate['status'] == 1) {
					$data['status'] = 0;
				}else{
					$data['status'] = 1;
				}
				$edit = $game_model->isolateAdd($data, $_GET['id']);
				if ($edit) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=isolate');
				}else{
					ShowMsg('操作失败', '/index.php?m=sdkGame&a=isolate');
				}
				break;
			case 'save':
				if (empty($_REQUEST['upperName'])) {
					ShowMsg('项目游戏名不能为空', '-1');exit;
				}
				$isset = $game_model->getIsolateIsset($_REQUEST['upperName'], $_REQUEST['specialName'], $_REQUEST['game']);
				
				if ($isset) {
					ShowMsg('该添加项已存在，请勿重复提交', '-1');exit;
				}
				if (!empty($_REQUEST['game'])) {
					$gameData = $game_model->getList(null, null, $_REQUEST['game']);
				}
				
				$data['upperName'] = trim($_REQUEST['upperName']);
				$data['specialName'] = trim($_REQUEST['specialName']);
				$data['gameAlias'] = trim($_REQUEST['game']);
				$data['gameName'] = $gameData[0]['name'];
				$data['barringChannel'] = implode(',', $this->_REQUEST['channels']);;
				$data['status'] = $_REQUEST['status'];
				$type = 1;
				if (!empty($data['specialName'])) {
					$type = 2;
				}
				if (!empty($data['gameAlias'])) {
					$type = 3;
				}
				$data['type'] = $type;
				$add = $game_model->isolateAdd($data);
				
				if ($add) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=isolate');
				}else{
					ShowMsg('操作失败', '/index.php?m=sdkGame&a=isolate');
				}
				break;
			case 'del':
				$game_model->isolateDelete($_GET['id']);
				ShowMsg('操作成功', '/index.php?m=sdkGame&a=isolate');
				break;
		}
	}

	/**
	 * 母包测试账号
	 */
	public function testUsers()
	{
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
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
				$length = 25;
				$offset = ($page - 1) * $length;

				$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
				$this->assign('userName', $userName);
				$where = 1;
				if ($userName) {
					$where .= " AND userName = '{$userName}'";
				}

				$sql = 'select * from ms_testUsers where '. $where. ' limit '. $offset. ','. $length;
				$testUserList = model::getBySql($sql);
				$total = count($testUserList);

				$this->assign('testUserList', $testUserList);
				$this->assign('list_length', $length);
				$this->assign('list_total', $total);
				$this->assign('list_page', $page);
				break;

			case 'add':
				break;

			case 'edit':
				
				$testUsersModel = new model('ms_testUsers');
				$userData = $testUsersModel->get("`id`='{$_GET['id']}'");
				$this->assign('userData', $userData);
				break;

			case 'save':
				if (empty($_POST['userName']) || empty($_POST['amount'])) {
					ShowMsg('参数不能为空', -1);
				}

				if ( trim($_POST['amount']) > 5000) {
					ShowMsg('配额不能大于5000元', -1);
				}

				$data = array(
					'userName' => trim($_POST['userName']),
					'amount' => trim($_POST['amount']),
					'ip' => trim($_POST['ip']) ? trim($_POST['ip']) : '',
					'createTime' => time(),
				);
				
				$testUsersModel = new model('ms_testUsers');
				if ($_POST['id']) {
					$res = $testUsersModel->set($data, "id='{$_POST['id']}'");
				} else {
					$res = $testUsersModel->set($data);
				}
				if ($res) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=testUsers');
				} else {
					ShowMsg('操作失败', -1);
				}
				break;

			case 'del':
				$id = $_GET['id'];
				$sql = "delete from ms_testUsers where id = '{$id}'";
				$res = model::getBySql($sql);
				if ($res) {
					ShowMsg('操作成功', '/index.php?m=sdkGame&a=testUsers');
				} else {
					ShowMsg('操作失败', -1);
				}
				break;
		}
	}
}