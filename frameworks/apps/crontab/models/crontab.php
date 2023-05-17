<?php

class crontab {
	/**
	 * 统计数据活跃用户数详情(每15分钟执行一次)
	 * 首次更新时间必须是整点数，例：12:00
	 * @param int $date
	 */
	public function msIntermodal($date=null) {
		if ($date) {
			$day = date('Y-m-d', $date);
		}else {
			$date = TIME - 10;
			$day = date('Y-m-d', $date);
		}
		$_start = strtotime($day);
		$_end = strtotime($day . ' 23:59:59');
		//活跃用户
		$activeUser = 'SELECT `channelId`, `channelName`, `gameAlias`, `gameName`, `apkNum`, COUNT(DISTINCT `roleId`, `userName`) AS activeUser FROM `ms_role_seted` WHERE `time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND `type` = "server" GROUP BY `gameAlias`, `channelId`, `apkNum` ORDER BY `gameAlias` DESC';
		$activeUser = model::getBySql($activeUser);

		if($activeUser){
			$integrated_model = new model('ms_integrated_daily');

			//新增用户
			$newUser = 'SELECT `channelId`, `channelName`, `gameAlias`, `gameName`, `apkNum`, COUNT(DISTINCT `roleId`, `userName`) AS total_newUser FROM `ms_role_seted` WHERE `time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND `type` = "server" AND `isFirst` = 1 GROUP BY `gameAlias`, `channelId`, `apkNum` ORDER BY `gameAlias` DESC';
			$newUser = model::getBySql($newUser);

			//充值金额
			$amount = 'SELECT `channelId`, `channelName`, `gameAlias`, `gameName`, `apkNum`, SUM(`money`) AS total_amount FROM ms_order WHERE `time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND orderStatus = "1" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
			$amount = model::getBySql($amount);

			//付费人数
			$payUser = 'SELECT `channelId`, `channelName`, `gameAlias`, `gameName`, `apkNum`, COUNT(DISTINCT `roleId`, `userName`) AS total_payUser FROM ms_order WHERE `time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND orderStatus = "1" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
			$payUser = model::getBySql($payUser);

			//新用户付费人数
			$newPayUser = 'SELECT o.`channelId`, o.`channelName`, o.`gameAlias`, o.`gameName`, o.`apkNum`, COUNT(DISTINCT o.`roleId`) AS total_newPayUser FROM ms_order o LEFT JOIN `ms_role_seted` r ON o.`roleId` = r.`roleId` AND o.`gameAlias` = r.`gameAlias` AND o.`channelId` = r.`channelId` AND o.`userName` = r.`userName` WHERE o.`orderStatus` = "1" AND r.`type` = "server" AND r.`isFirst` = 1 AND r.`time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND FROM_UNIXTIME( o.`time` , "%Y-%m-%d" ) = "' . $day . '" GROUP BY o.`channelId`, o.`gameAlias`, o.`apkNum` ORDER BY o.`gameAlias` DESC';
			$newPayUser = model::getBySql($newPayUser);

			//新用户付费金额
			$newAmount = 'SELECT o.`channelId`, o.`channelName`, o.`gameAlias`, o.`gameName`, o.`apkNum`, SUM(o.`money`) AS total_newAmount FROM ms_order o LEFT JOIN `ms_role_seted` r ON o.`roleId` = r.`roleId` AND o.`gameAlias` = r.`gameAlias` AND o.`channelId` = r.`channelId` AND o.`userName` = r.`userName` WHERE o.`orderStatus` = "1" AND r.`type` = "server" AND r.`isFirst` = 1 AND r.`time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND FROM_UNIXTIME( o.`time`, "%Y-%m-%d" ) = "' . $day . '" GROUP BY o.`channelId`, o.`gameAlias`, o.`apkNum` ORDER BY o.`gameAlias` DESC';
			$newAmount = model::getBySql($newAmount);

			//首充金额
			$firstRecharge = 'SELECT `channelId`, `channelName`, `gameAlias`, `gameName`, `apkNum`, SUM(`money`) AS total_firstRecharge FROM ms_order WHERE `time` BETWEEN "' . $_start . '" AND "' . $_end . '" AND orderStatus = "1" AND isFirst = "1" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
			$firstRecharge = model::getBySql($firstRecharge);

			foreach ($activeUser as $key1 => $value1) {
				$activeUser[$key1]['newUser'] = 0;
				$activeUser[$key1]['oldUser'] = $value1['activeUser'];
				foreach ($newUser as $key2 => $value2) {
					if (($value1['channelId'] == $value2['channelId']) &&
					($value1['gameAlias'] == $value2['gameAlias']) &&
					($value1['apkNum'] == $value2['apkNum']))
					{
						$activeUser[$key1]['newUser'] = $value2['total_newUser'];
						$activeUser[$key1]['oldUser'] = $value1['activeUser'] - $value2['total_newUser'];
					}
				}

				foreach($amount as $key3 => $value3){
					if(($value1['channelId'] == $value3['channelId']) &&
					($value1['gameAlias'] == $value3['gameAlias']) &&
					($value1['apkNum'] == $value3['apkNum']))
					{
						$activeUser[$key1]['amount'] = $value3['total_amount'];
					}
				}

				foreach($payUser as $key4 => $value4){
					if(($value1['channelId'] == $value4['channelId']) &&
					($value1['gameAlias'] == $value4['gameAlias']) &&
					($value1['apkNum'] == $value4['apkNum']))
					{
						$activeUser[$key1]['payUser'] = $value4['total_payUser'];
					}
				}

				foreach($newPayUser as $key5 => $value5){
					if(($value1['channelId'] == $value5['channelId']) &&
					($value1['gameAlias'] == $value5['gameAlias']) &&
					($value1['apkNum'] == $value5['apkNum']))
					{
						$activeUser[$key1]['newPayUser'] = $value5['total_newPayUser'];
					}
				}

				foreach($newAmount as $key6 => $value6){
					if(($value1['channelId'] == $value6['channelId']) &&
					($value1['gameAlias'] == $value6['gameAlias']) &&
					($value1['apkNum'] == $value6['apkNum']))
					{
						$activeUser[$key1]['newAmount'] = $value6['total_newAmount'];
					}
				}

				foreach($firstRecharge as $key7 => $value7){
					if(($value1['channelId'] == $value7['channelId']) &&
					($value1['gameAlias'] == $value7['gameAlias']) &&
					($value1['apkNum'] == $value7['apkNum']))
					{
						$activeUser[$key1]['firstRecharge'] = $value7['total_firstRecharge'];
					}
				}

				$activeUser[$key1]['oldPayUser'] = $activeUser[$key1]['payUser'] - $activeUser[$key1]['newPayUser'];
				$activeUser[$key1]['oldAmount'] = $activeUser[$key1]['amount'] - $activeUser[$key1]['newAmount'];

				$data_list = array(
				'channelId'	     => $activeUser[$key1]['channelId'],//渠道ID
				'channelName'	 => $activeUser[$key1]['channelName'],//渠道名称
				'gameAlias'		 => $activeUser[$key1]['gameAlias'],//游戏别名
				'gameName'		 => $activeUser[$key1]['gameName'],//游戏名称
				'apkNum'		 => $activeUser[$key1]['apkNum'],//游戏包号
				'date'	         => $day,//日期
				'newUser'		 => $activeUser[$key1]['newUser'],//新增用户
				'oldUser'	     => $activeUser[$key1]['oldUser'] > 0 ? $activeUser[$key1]['oldUser'] : 0,//老用户
				'activeUser'	 => $activeUser[$key1]['activeUser'],//活跃用户
				'amount'	     => $activeUser[$key1]['amount'],//充值金额
				'payUser'		 => $activeUser[$key1]['payUser'],//付费人数
				'newPayUser'	 => $activeUser[$key1]['newPayUser'],//新用户付费人数
				'newAmount'		 => $activeUser[$key1]['newAmount'],//新用户充值金额
				'oldPayUser'	 => $activeUser[$key1]['oldPayUser'],//老用户付费人数
				'oldAmount'		 => $activeUser[$key1]['oldAmount'],//老用户充值金额
				'firstRecharge'	 => $activeUser[$key1]['firstRecharge']//老用户充值金额
				);

				$checkDate = $integrated_model->get("`date`='" . $day . "' AND `channelId`= '" . $data_list['channelId'] . "' AND `gameAlias`= '" . $data_list['gameAlias'] . "' AND `apkNum`= '" . $data_list['apkNum'] . "'");

				//判断更新的时间
				if($checkDate) {
					$sql = "UPDATE `ms_integrated_daily` SET `newUser` = '" . $data_list['newUser'] . "',"
					. "`oldUser` = '" . $data_list['oldUser'] . "',"
					. "`activeUser` = '" . $data_list['activeUser'] . "',"
					. "`amount` = '" . $data_list['amount'] . "',"
					. "`payUser` = '" . $data_list['payUser'] . "',"
					. "`newPayUser` = '" . $data_list['newPayUser'] . "',"
					. "`newAmount` = '" . $data_list['newAmount'] . "',"
					. "`oldPayUser` = '" . $data_list['oldPayUser'] . "',"
					. "`oldAmount` = '" . $data_list['oldAmount'] . "',"
					. "`firstRecharge` = '" . $data_list['firstRecharge'] . "'"
					. " WHERE `date`='" . $day . "' AND `channelId`= '" . $data_list['channelId'] . "' AND `gameAlias`= '" . $data_list['gameAlias'] . "' AND `apkNum`= '" . $data_list['apkNum'] . "'";
					Model::getBySql($sql);
				}else {
					$integrated_model->set($data_list);
				}
			}
		}
	}

	/**
	 * 异步补发元宝
	 * 
	 */
	public function gameIngots() {
		load('model.gameIngots');
		$gameIngots = new gameIngots();

		$gameIngots->ordedrAsynchronous();
	}

	/**
	 * 生成游戏对接文件
	 * 每五分钟执行一次
	 */
	public function gameParamConf() {
		$this->config = require(APP_LIST_PATH . "main/config.inc.php");

		$game_model = getInstance('@oss.model.sdkGame.game');
		$game_list = $game_model->getList(0, 10000);
		$isolate_list = $game_model->getIsolateList();
		//返利
		$benefits_model = getInstance('@oss.model.sdkBenefits.benefits');
		$benefits_list = $benefits_model->getBenefitsList(); 

		foreach ($game_list as $key => $value) {
			if ($value['weixinType']) {
				$weixinType = $value['weixinType'];
			}else{
				$weixinType = 'zhangling|fandian';
			}
			if ($value['alipayType']) {
				$alipayType = $value['alipayType'];
			}else{
				$alipayType = 'alipay|fandian';
			}
			$this->config['key'][$value['alias']]['sdk'] = $value['token'];
			$this->config['key'][$value['alias']]['game_server'] = $value['serverKey'];
			$this->config['key'][$value['alias']]['scale'] = $value['scale'];
			$this->config['key'][$value['alias']]['monetary_unit'] = $value['monetaryUnit'];
			$this->config['key'][$value['alias']]['callback_url'] = $value['callbackUrl'];
			$this->config['key'][$value['alias']]['isration'] = $value['isration'];
			$this->config['key'][$value['alias']]['visible_float'] = $value['visibleFloat'];
			$this->config['key'][$value['alias']]['weixinType'] = $weixinType;
			$this->config['key'][$value['alias']]['alipayType'] = $alipayType;
			$this->config['key'][$value['alias']]['channelSyn'] = $value['channelSyn'];
			$this->config['key'][$value['alias']]['relateGame'] = $value['relateGame'];
			$this->config['key'][$value['alias']]['h5Url'] = $value['h5Url'];
			$benefitId = 0;//初始化返利id
			foreach ($benefits_list as $k => $v) {
				if ($value['alias'] == $v['gameAlias'] && $v['start'] < time() && $v['end'] > time()) {
					$benefitId = $v['id'];//返利开启
				}
			}
			$this->config['key'][$value['alias']]['benefit'] = $benefitId;
			$isolate = $isolate1 = $isolate2 = $isolate3 = 0;

			//判断是否渠道隔离
			foreach ($isolate_list as $k1 => $v1) {
				if ($v1['status'] == 1) {
					if ($v1['type'] == 1 && $v1['upperName'] == $value['upperName']) {
						$isolate1 = true;
					}
					if ($v1['type'] == 2 && $v1['upperName'] == $value['upperName'] && $v1['specialName'] == $value['specialName']) {
						$isolate2 = true;
					}
					if ($v1['type'] == 3 && $v1['gameAlias'] == $value['alias']) {
						$isolate3 = true;
					}
				}
			}
			if ($isolate1 == true) {
				$isolate = 1;
			}else{
				if ($isolate2 == true) {
					$isolate = 2;
				}else{
					if ($isolate3 == true) {
						$isolate = 3;
					}else{
						$isolate = 0;
					}
				}
			}
			$this->config['key'][$value['alias']]['isolate'] = $isolate;
			if ($value['logger']) {
				$this->config['log'][$value['alias']]['type'] = 'all';
			}else {
				unset($this->config['log'][$value['alias']]);
			}
		}

		$output = "<?php";
		$output .= "\n";
		$output .= "return ";
		$output .= var_export($this->config, true);
		$output .= ";";
		file_put_contents(APP_LIST_PATH . "main/config.inc.php", $output);

		return true;
	}

	/**
	 * 更新IP得到真实地区
	 */
	public function IpUpdate() {
		//新浪接口废了，故屏蔽		[2018-8-3]by Prolove
		return true;
		
		$statistics_model = getInstance('@oss.model.statistics');
		$order_list = $statistics_model->getNorAreaMemberList(2000);

		foreach($order_list as $k => $v){
			if((!empty($v['joinIp'])) && (empty($v['province']))){
				$ipInfos = GetIpLookup($v['joinIp']);
				$province = $ipInfos['province'];
				$city = $ipInfos['city'];
				if ($province) {
					$sql = "UPDATE `ms_member` SET `province` = '".$province."',`city` = '".$city."' WHERE `joinIp`= '". $v['joinIp'] ."'";
					Model::getBySql($sql);
				}
			}
		}
		return true;
	}

	/**
	 * 针对网络等复杂原因造成 setUserInfo 接口无效写入
	 * 的情况，从而自动补齐数据避免统计数据方面的误差而做
	 * 每隔五分钟执行一次
	 */
	public function fixedRoleSeted() {
		$day = date("Y-m-d");
		$sql = "SELECT DISTINCT `roleId`, `gameAlias` FROM `ms_role_seted` WHERE `type` = 'server' AND `day` = '{$day}'";
		$role = Model::getBySql($sql);

		$sql = "SELECT DISTINCT o.`roleId` , o.`userName` , o.`channelId` , o.`channelName` , o.`roleName` , o.`roleId` , o.`orderStatus` , o.`sendStatus` , o.`gameAlias` , o.`gameName` , o.`apkNum` , o.`server` FROM `ms_order` o WHERE o.`orderStatus` =1 AND o.`roleId` != '' AND o.`orderId` != '' AND FROM_UNIXTIME( o.`time`, \"%Y-%m-%d\" ) = '" . $day . "' ORDER BY o.`roleId` ASC";
		$order = Model::getBySql($sql);

		foreach ($role as $key1 => $value1) {
			foreach ($order as $key2 => $value2) {
				if ($value1['roleId'] == $value2['roleId'] && $value1['gameAlias'] == $value2['gameAlias']) {
					$array[] = array(
					'roleId' => $value1['roleId'],
					'gameAlias' => $value1['gameAlias']
					);
				}
			}
		}

		//找出两组数据的差值
		$cmp = function($av, $bv){
			$r = strcmp($av['roleId'], $bv['roleId']);
			return $r === 0 ? strcmp($av['gameAlias'], $bv['gameAlias']) : $r;
		};

		$forasp = array_values(array_udiff($array, $order, $cmp));
		$aggregate = array_udiff($order, $array, $cmp);
		foreach($aggregate as &$dv){
			$forasp[]=$dv;
		}
		unset($aggregate);

		if ($forasp) {
			$role_model = new model('ms_role_seted');
			foreach ($forasp as $k => $v) {
				$role_seted = $role_model->get('`roleId`=' . $v['roleId'] . ' AND `gameAlias`="' . $v['gameAlias'] . '" AND `type`="server"');
				if ($role_seted) {
					$isFirst = 0;
				}else{
					$isFirst = 1;
				}
				$array = array(
				'channelId' => $v['channelId'],
				'channelName' => $v['channelName'],
				'gameAlias' => $v['gameAlias'],
				'gameName' => $v['gameName'],
				'apkNum' => $v['apkNum'],
				'userName' => $v['userName'],
				'type' => 'server',
				'time' => time(),
				'roleId' => $v['roleId'],
				'roleName' => $v['roleName'],
				'serverId' => $v['server'],
				'isFirst' => $isFirst,
				'day' => $day,
				'hour' => date("Y-m-d H")
				);

				$role_model->set($array);
			}
		}
	}

	/**
	 * MIS后台数据转移
	 */
	public function dataMigration($game, $channel, $limit=100) {	
		$query = array(
			'game' => $game,
			'channel' => $channel,
			'limit' => $limit,
			);

		$where = 1;
		if (!empty($game)) {
			$where .=  " AND `gameAlias` = '" . $game . "'";
		}

		//source是数据来源，区分两个后台的数据
		$sql = "SELECT `time` FROM `ms_order` WHERE " . $where . " AND `source` = 'mis' ORDER BY time DESC LIMIT 1";
		$misTime = Model::getBySql($sql);
		if ($misTime) {
			$query['time'] = $misTime[0]['time'];
		}

		//mis后台接口
		$url = C('MIS_URL') . 'api/index.php?m=index&a=dataMigration';

		$misData = httpRequest($url, $query);
		$misData = json_decode($misData, true);

		$order_model = new model('ms_order');
		foreach ($misData as $key => $value) {
			if ($value['agent_name'] == 'weixin') {
				$paymentId = 9;
			} elseif ($value['agent_name'] == 'alipay') {
				$paymentId = 7;
			} elseif ($value['agent_name'] == 'apple') {
				$paymentId = 11;
			} elseif ($value['agent_name'] == 'upmp') {
				$paymentId = 2;
			}else{
				$paymentId = 12;
			}
			//MIS后台的数据只区分乾游和IOS
			if (strtolower($value['channel']) == 'ios') {
				$channelId = 'ios';
				$channelName = 'IOS';
			}else{
				$channelId = '160068';
				$channelName = '乾游';
			}
			$array = array(
			'channelId' => $channelId,
			'channelName' => $channelName,
			'gameAlias' => $value['game'],
			'gameName' => '',
			'apkNum' => '主包',
			'orderId' => $value['oid'],
			'money' => $value['omoney'],
			'time' => $value['otime'],
			'userName' => $value['ousername'],
			'server' => $value['server'],
			'roleId' => $value['roleid'],
			'roleName' => $value['ocharname'],
			'orderStatus' => 1,
			'sendStatus' => 1,
			'paymentId' => $paymentId,
			'paymentName' => $value['agent_name'],
			'gold' => $value['agent_pay_gold'],
			'orderDescr' => '',
			'ip' => $value['ip'],
			'gameMessage' => '',
			'source' => 'mis'
			);
			$order_model->set($array);
		}
	}
	
	/**
	 * 数据表减压 (每2小时执行一次)
	 * 本方法原本为ms_gdt_feedback数据表减压使用
	 * 目前进行了拓展，为减少相关影响，故不修改本方法名 时间：2019-02-16
	 */
	public function feedbackReduce($date=null) {
		if (empty($date)) {
			$date = time();
		}
		//只保留当前时间前3天数据
		$reduceTime = $date - 3600*24*3;
		$feedback_model = new model('ms_gdt_feedback');
		$feedback_model->DELETE('feed_time < ' . $reduceTime);

		//只保留当前时间前30天数据
		$reduceOTime = $date - 3600*24*30;
		$order_model = new model('ms_order');
		$order_model->DELETE('time < ' . $reduceOTime . ' AND orderStatus = 0');

		//只保留当前时间前90天数据
		$reduceTmpTime = $date - 3600*24*90;
		$tmp_model = new model('ms_order_tmp');
		$tmp_model->DELETE('time < ' . $reduceTmpTime );

		//30天没有登录记录，即为二次流失用户
		$infoTime = $date - 3600*24*30;
		$info_model = new model('ms_member_info');
		$infoUpdate['type'] = 2;
		$info_model->set($infoUpdate, 'loginTime < %s AND type = 1', array($infoTime));

		return true;
	}
	
	/**
	 * 指定游戏定时切换为乾游支付
	 * 每整点执行一次
	 * 关：9点-12点，14点-18点(取消 2019.07.09)
	 * 开：12点-14点，18点-次日9点(取消 2019.07.09)
	 * 关：10点-12点，14点-17点
  	 * 开：12点-14点，17点-次日10点
	 */
	public function qyPaySwitch($date=null) {
		$gameList = array(
			//游戏别名，渠道号，包号
			array('wangzhexz_jswl17', '160136', '主包'),  	//修仙世界（绝世武林）应用宝
			array('wangzhexz_jswl17', '160136', '分包1'), 
			array('wangzhexz_jswl17', '160136', '分包2'), 
			array('wangzhexz_jswl26', '160136', '主包'),  	//幻化之锋（绝世武林）应用宝
			array('wangzhexz_jswl26', '160136', '分包1'), 
			//array('wangzhexz_jswl27', '160136', '主包'),  	//酒神阴阳冕（绝世武林）应用宝
			//array('wangzhexz_jswl27', '160136', '分包1'), 
			//array('wangzhexz_jswl37', '160136', '主包'),  	//雷霆英雄（绝世武林）应用宝
			//array('wangzhexz_jswl18', '160136', '主包'),  	//武动精灵（绝世武林）应用宝
			//array('daojianws11', '160136', '主包'),       	//天神传（武动九天）应用宝
			//array('daojianws11', '160136', '分包1'),
			//array('daojianws22', '160136', '主包'),       	//战仙传（武动九天）应用宝
			array('daojianws23', '160136', '主包'),       	//剑客下山（武动九天）应用宝	
			array('daojianws23', '160136', '分包2'),	  	//剑客下山（武动九天）应用包
			//array('daojianws24', '160136', '主包'), 	  	//一剑成仙（武动九天）应用宝	
			//array('daojianws24', '160136', '分包1'), 
			array('daxia2', '160136', '主包')	  	  		//逆剑苍穹（大侠2）应用宝
		);

		$checkDayStr = date('Y-m-d ',time());

	    $timeBegin1 = strtotime($checkDayStr."10:00:00");
	    $timeEnd1 = strtotime($checkDayStr."12:00:00");

	    $timeBegin2 = strtotime($checkDayStr."14:00:00");
	    $timeEnd2 = strtotime($checkDayStr."17:00:00");
	   
	    $curr_time = time();
	    $channel_model = new model('ms_channel');
	    if(($curr_time >= $timeBegin1 && $curr_time < $timeEnd1) || ($curr_time >= $timeBegin2 && $curr_time < $timeEnd2)){
	    //10点-12点 ,14点-17点
	       foreach ($gameList as $k => $v) {
	       		$channel_model->set(array('qyPy' => 0),
				'`gameAlias`=%s AND `channelId`=%s AND `apkNum`=%s',
				array($v[0], $v[1], $v[2])
				);
	       }
	    }else {
	    	foreach ($gameList as $key => $value) {
	       		$channel_model->set(array('qyPy' => 1),
				'`gameAlias`=%s AND `channelId`=%s AND `apkNum`=%s',
				array($value[0], $value[1], $value[2])
				);
	       }
	    }
	}

	/**
	 * ms_role_seted数据表减压 (凌晨3-6点，每半小时执行一次)
	 * 请优先在数据库分次执行下面SQL语句，减少程序运行时的压力(id和条数需要改变)
	 * DELETE FROM ms_role_seted WHERE 1 AND id <= 28862431 AND type = "server" AND isFirst = 0 limit 1000;
	 */
	public function roleReduce($date=null) {
		$hour = date('G');//当前时间段
		$array = array('2', '3', '4', '5', '6', '7');//时间范围是凌晨3-6点
		$check = in_array($hour, $array) ? true : false;

		if ($check) {
			if (empty($date)) {
				$date = time();
			}
			//只保留当前时间前120天数据
			$reduceRTime = $date - 3600*24*120;
			$reduceRDay = date("Y-m-d", $reduceRTime);
			$role_model = new model('ms_role_seted');
			//$role_model->DELETE('day = "' . $reduceRDay . '" AND type = "server" AND isFirst = 0 LIMIT 10000');
			$roleInfo = $role_model->get('day = "' . $reduceRDay . '" AND type = "server" AND isFirst = 0 ORDER BY id DESC');
			$role_model->DELETE('id <= "' . $roleInfo['id'] . '" AND type = "server" AND isFirst = 0 LIMIT 10000');
			return true;
		}else{
			return false;
		}

	}

	/**
	 * 统计每天游戏的利润(每小时执行一次)
	 * 每天凌晨01:00-04:00为有效执行时间，其他时间不进行数据处理
	 */
	public function msProfit($day=null, $hour=null) {
		//$day = '2020-02-11'; 
		//$hour = 2;
		$hour = $hour ? $hour : date('G');//当前时间段
		$array = array('1','2', '3', '4');//时间范围是凌晨1-4点
		$check = in_array($hour, $array) ? true : false;
		if ($check) {
			if (!$day) {
				//统计前一天数据
				$date = TIME - 86400;
				$day = date('Y-m-d', $date);
			}
			$start = strtotime($day);
			$end = strtotime($day . ' 23:59:59');
			$gameSql = 'SELECT alias, name, proportion,cpAllowance FROM ms_game';
			$gameData = model::getBySql($gameSql);

			$channelSql = 'SELECT gameAlias, channelId, apkNum, exProportion, channelAllowance, settlement FROM ms_channel';
			$channelData = model::getBySql($channelSql);

			//渠道默认分成
			$proportion = require(APP_LIST_PATH . "main/channel.proportion.php");

			foreach ($gameData as $key => $value) {
				foreach ($channelData as $key1 => $value1) {
					if ($value['alias'] == $value1['gameAlias']) {
						//$value['proportion'] = $value['proportion'] ? $value['proportion'] : "0.2";//默认cp分成为2:8，cp
						$channelData[$key1]['proportion'] = $value['proportion'];
						$channelData[$key1]['cpAllowance'] = $value['cpAllowance'];
					}
				}
			}
			//引用修改前的折扣，减少因修改折扣产生的误差
			$qyDiscountConf = require(APP_LIST_PATH . "oss/config.qyDiscount.php");
			foreach ($channelData as $keyes => $valuees) {
				if ($valuees['channelId'] == '160068') {
					$qyDiscount = $qyDiscountConf[$day][$valuees['gameAlias']][$valuees['apkNum']];
					if (!empty($qyDiscount)) {
						$channelData[$keyes]['exProportion'] = $qyDiscount;
					}
					//部分cp按折扣后流水结算的
					if ($valuees['settlement'] == 2) {
						$channelData[$keyes]['exProportion']  = $channelData[$keyes]['exProportion'] ? $channelData[$keyes]['exProportion'] : 1;
						$channelData[$keyes]['proportion'] = $valuees['proportion'] * $channelData[$keyes]['exProportion'];
					}
				}
			}

			//需特殊处理的畅梦游戏
			$cmgArray = array('wangzhexz_jswl41', 'moshenzj31', 'muyingzs_tgfml41');

			//因历史遗留，这部分游戏（cps）需要特殊处理
			$yyArray = array('sanguoxxz_midsdk', 'guozhanll', 'zhuzaicq_new', 'muyingzs', 'liehuoqj_fsby', 'kaitianby_bh3', 'muchangcq_fsby', 'daojianws29', 'muyingzs9', 'batu_fsby');

			foreach ($channelData as $kcm => $vcm) {
				if (in_array($vcm['gameAlias'], $cmgArray) && ($vcm['apkNum'] == '耀非' || $vcm['apkNum'] == '主包')) {
					$channelData[$kcm]['cmg'] = 1;
				}
				if ($vcm['apkNum'] == '主包') {
					if (in_array($vcm['gameAlias'], $yyArray)) {
						$channelData[$kcm]['cmg'] = 2;
					}/*elseif ($vcm['gameAlias'] == 'tulonglh_myzs') {
						$channelData[$kcm]['cmg'] = 3;
					}*/
				}
			}

			$model = new model('ms_profit_daily');
			if ($hour == '2') {
				//联运（不包括乾游的安卓和ios）
				$orderSql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `apkNum`, sum(money) AS total_amount FROM ms_order WHERE time BETWEEN '.$start.' AND '.$end.' AND orderStatus = "1" AND channelId != "160068" AND channelId != "500011" AND adid = "" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$orderData = model::getBySql($orderSql);
				//鲸天互娱包计算折扣
				$jingSql = 'SELECT `gameAlias`, `gameName`, `apkNum`, sum(disMoney) AS total_dis FROM ms_order_tmp WHERE time BETWEEN '.$start.' AND '.$end.' AND channelId = "500010" GROUP BY `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$jingData = model::getBySql($jingSql);
				foreach ($orderData as $kr => $vr) {
					$orderData[$kr]['disMoney'] = $vr['total_amount'];
					foreach ($jingData as $kr1 => $vr1) {
						if ($vr['channelId'] == '500010' && $vr['gameAlias'] == $vr1['gameAlias'] && $vr['apkNum'] == $vr1['apkNum']) {
							$orderData[$kr]['disMoney'] = $vr1['total_dis'];
						}
					}
				}
				foreach ($orderData as $k => $v) {
					foreach ($channelData as $k1 => $v1) {
						if ($v['gameAlias'] == $v1['gameAlias'] && $v['channelId'] == $v1['channelId'] && $v['apkNum'] == $v1['apkNum']) {
							$cpAllowance = $v1['cpAllowance'] ? $v1['cpAllowance'] : 0;
							//cp的分成
							$cpAmount = $v['total_amount'] * (1 - $cpAllowance) * $v1['proportion'];
							//渠道的分成
							//心玩。鲸天互娱包特别处理
							if ($v['channelId'] == '500008') {
								if (empty($v1['exProportion'])) {
									$v1['exProportion'] = 1;
								}
								$exArr = explode(',', $v1['channelAllowance']);
								if (empty($exArr[1])) {
										$exPro = 0.22;
								}else{
									$exPro = $exArr[1];
								}
								if (empty($exArr[0])) {
									$exAllowance = 0.012;
								}else{
									$exAllowance = $exArr[0];
								}
								//算法（1-折扣）+（折扣*（1-通道费比例）*分成）+（折扣*通道费比例）
								//（1-折扣）为我方承担的折扣损失
								//折扣*（1-通道费比例）*分成 为扣除通道费后给渠道的分成
								//（折扣*通道费比例） 为我方实际承担的通道费
								$rate = 1 - $v1['exProportion'] + $v1['exProportion'] * (1 - $exAllowance) * $exPro + $v1['exProportion'] * $exAllowance;
								$channelAmount = $v['total_amount'] * $rate;
							}elseif ($v['channelId'] == '500010') {
								$exArr = explode(',', $v1['channelAllowance']);
								if (empty($exArr[1])) {
									$exPro = 0;
								}else{
									$exPro = $exArr[1];
								}
								$channelAmount = $v['total_amount'] - $v['disMoney'] + $v['disMoney'] * $exPro;
							}else{
								$exProportion = empty($v1['exProportion']) ? $proportion[$v['channelId']]['proportion'] : $v1['exProportion'];
								if (empty($v1['channelAllowance'])) {
									$channelAllowance = $proportion[$v['channelId']]['channelAllowance'];
								}elseif ($v1['channelAllowance'] < 0) {
									$channelAllowance = 0;
								}else{
									$channelAllowance = $v1['channelAllowance'];
								}
								//$channelAllowance = empty($v1['channelAllowance']) ? $proportion[$v['channelId']]['channelAllowance'] : $v1['channelAllowance'];
								$channelAmount = $v['total_amount'] * (1 - $channelAllowance) * $exProportion + $v['total_amount'] * $channelAllowance;
							}
							$orderData[$k]['cpAmount'] = $cpAmount;
							$orderData[$k]['channelAmount'] = $channelAmount;
							//分成后的利润
							$orderData[$k]['profit'] = $v['total_amount'] - $cpAmount - $channelAmount;
							//上古bt特殊处理
							if ($v['gameAlias'] == 'shangguxx_bt2' || $v['gameAlias'] == 'shangguxx_bt2_ios') {
								$orderData[$k]['profit'] = ($v['total_amount'] - $cpAmount - $channelAmount) * 0.5;
								$orderData[$k]['channelAmount'] = $channelAmount + ($v['total_amount'] - $cpAmount - $channelAmount) * 0.5;
							}
						}
					}
				}
				//因操作不当或其他问题，导致无法增加或漏增加相关参数的，使用默认比例处理
				foreach ($orderData as $k2 => $v2) {
					if (empty($v2['profit']) && $v2['channelId'] != '500028' && $v2['channelId'] != '500071') {
						foreach ($channelData as $k3 => $v3) {
							if ($v2['gameAlias'] == $v3['gameAlias'] && $v2['channelId'] == $v3['channelId']) {
								$cpAllowance = $v3['cpAllowance'] ? $v3['cpAllowance'] : 0;
								//cp的分成
								$cpAmount = $v2['total_amount'] * (1 - $cpAllowance) * $v3['proportion'];

								$channelAmount = $v2['total_amount'] * $proportion[$v2['channelId']]['proportion'];
								$orderData[$k2]['cpAmount'] =  $cpAmount;
								$orderData[$k2]['channelAmount'] = $channelAmount;
								$orderData[$k2]['profit'] = $v2['total_amount'] - $cpAmount - $channelAmount;
							}
						}
					}

					$dataList = array(
						'channelId'	     => $orderData[$k2]['channelId'],//渠道ID
						'channelName'	 => $orderData[$k2]['channelName'],//渠道名称
						'gameAlias'		 => $orderData[$k2]['gameAlias'],//游戏别名
						'gameName'		 => $orderData[$k2]['gameName'],//游戏名称
						'apkNum'		 => $orderData[$k2]['apkNum'],//游戏包号
						'date'	         => $day,//日期
						'amount'		 => $orderData[$k2]['total_amount'],//流水
						'cpAmount'		 => $orderData[$k2]['cpAmount'],//CP分成
						'channelAmount'	 => $orderData[$k2]['channelAmount'],//渠道分成
						'profit'		 => $orderData[$k2]['profit'],//利润
						'type'		 	 => '1',//数据类型 1：联运 2.广告 3、cps
						'disAmount'		 => $orderData[$k2]['disMoney'],
						);
					$model->set($dataList);
				}
			}elseif ($hour == '3') {
				//广告
				$adOrderSql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `apkNum`, sum(money) AS total_amount FROM ms_order WHERE time BETWEEN '.$start.' AND '.$end.' AND orderStatus = "1" AND adid != "" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$adOrderData = model::getBySql($adOrderSql);

				foreach ($adOrderData as $ks => $vs) {
					foreach ($channelData as $ks1 => $vs1) {
						if ($vs['gameAlias'] == $vs1['gameAlias'] && $vs['channelId'] == $vs1['channelId'] && $vs['apkNum'] == $vs1['apkNum']) {
							$cpAllowance = $vs1['cpAllowance'] ? $vs1['cpAllowance'] : 0;
							//cp的分成
							$cpAmount = $vs['total_amount'] * (1 - $cpAllowance) * $vs1['proportion'];

							$exProportion = empty($vs1['exProportion']) ? $proportion[$vs['channelId']]['proportion2'] : $vs1['exProportion'];
							if (empty($vs1['channelAllowance'])) {
								$channelAllowance = $proportion[$vs['channelId']]['channelAllowance'];
							}elseif ($vs1['channelAllowance'] < 0) {
								$channelAllowance = 0;
							}else{
								$channelAllowance = $vs1['channelAllowance'];
							}
							//$channelAllowance = empty($vs1['channelAllowance']) ? $proportion[$vs['channelId']]['channelAllowance'] : $vs1['channelAllowance'];
							$channelAmount = $vs['total_amount'] * (1 - $channelAllowance) * $exProportion + $vs['total_amount'] * $channelAllowance;
							$adOrderData[$ks]['cpAmount'] = $cpAmount;
							$adOrderData[$ks]['channelAmount'] = $channelAmount;
							//分成后的利润
							$adOrderData[$ks]['profit'] = $vs['total_amount'] - $cpAmount - $channelAmount;
						}
					}
					$dataList = array(
						'channelId'	     => $adOrderData[$ks]['channelId'],//渠道ID
						'channelName'	 => $adOrderData[$ks]['channelName'],//渠道名称
						'gameAlias'		 => $adOrderData[$ks]['gameAlias'],//游戏别名
						'gameName'		 => $adOrderData[$ks]['gameName'],//游戏名称
						'apkNum'		 => $adOrderData[$ks]['apkNum'],//游戏包号
						'date'	         => $day,//日期
						'amount'		 => $adOrderData[$ks]['total_amount'],//流水
						'cpAmount'		 => $adOrderData[$ks]['cpAmount'],//CP分成
						'channelAmount'	 => $adOrderData[$ks]['channelAmount'],//渠道分成
						'profit'		 => $adOrderData[$ks]['profit'],//利润
						'type'		 	 => '2',//数据类型 1：联运 2.广告 3、cps
						'disAmount'		 => $adOrderData[$ks]['total_amount'],
						);
					$model->set($dataList);
				}
			}elseif ($hour == '1') {
				//乾游（不包括ios）
				/*$qyOrderSql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `apkNum`, sum(money) AS total_amount FROM ms_order WHERE time BETWEEN '.$start.' AND '.$end.' AND orderStatus = "1" AND channelId = "160068" AND adid = "" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$qyOrderData = model::getBySql($qyOrderSql);

				foreach ($qyOrderData as $keys => $values) {
					foreach ($channelData as $keys1 => $values1) {
						if ($values['gameAlias'] == $values1['gameAlias'] && $values['channelId'] == $values1['channelId'] && $values['apkNum'] == $values1['apkNum']) {
							$cpAllowance = $values1['cpAllowance'] ? $values1['cpAllowance'] : 0;
							//cp的分成
							$cpAmount = $values['total_amount'] * (1 - $cpAllowance) * $values1['proportion'];
							//默认没有渠道分成，对应GS渠道分成分别计算
							$qyOrderData[$keys]['type'] = 3;
							if ($values['apkNum'] == '南宇' || $values['apkNum'] == '炎游' || $values['apkNum'] == '心玩') {
								if (empty($values1['exProportion'])) {
									$values1['exProportion'] =1;
								}
								$exArr = explode(',', $values1['channelAllowance']);
								if (empty($exArr[1])) {
									$exPro = 0.2;
								}else{
									$exPro = $exArr[1];
								}
								$rate = 1 - $values1['exProportion'] + $exPro * $values1['exProportion'] ;
								$channelAmount = $values['total_amount'] * $rate;
							}elseif ($values['apkNum'] == '耀非' || $values['apkNum'] == '主包') {
								if (empty($values1['exProportion'])) {
									$values1['exProportion'] = 1;
								}*/
								/*if (empty($values1['channelAllowance'])) {
									$values1['channelAllowance'] = 0.05;
								}*/
							/*	if ($values1['cmg'] == 1) {//因历史遗留问题，畅梦包特殊处理
									$discountAmount = $values['total_amount'] * $values1['exProportion'];
									$memAmount = $values['total_amount'] - $discountAmount;
									$channelAmount = $discountAmount - $values['total_amount'] * 0.25 - $discountAmount * 0.15 + $memAmount;
								}elseif ($values1['cmg'] == 2) {*/
									/*if (empty($values1['channelAllowance'])) {
										$values1['channelAllowance'] = 0.05;
									}
									$gsAmount = $values['total_amount'] * (1 - $values1['channelAllowance']) * $values1['exProportion'] * 0.25;
									$channelAmount = $gsAmount + $values['total_amount'] * (1 - $values1['exProportion']);*/
									/*if (empty($values1['exProportion'])) {
										$values1['exProportion'] =1;
									}
									$rate = 1 - $values1['exProportion'] + 0.2 * $values1['exProportion'] ;
								$channelAmount = $values['total_amount'] * $rate;
								}elseif ($values1['cmg'] == 3) {
									$gsAmount = $values['total_amount'] * $values1['exProportion'] * 0.4;
									$channelAmount = $gsAmount + $values['total_amount'] * (1 - $values1['exProportion']);
								}else{
									$rate = 1 - $values1['exProportion'];
									$channelAmount = $values['total_amount'] * $rate;
								}
							}elseif ($values['apkNum'] == '金克丝') {
								if (empty($values1['exProportion'])) {
									$cpAmount = $values['total_amount'];
									$channelAmount = 0 ;
								}else{
									$channelAmount = $values['total_amount'] * $values1['exProportion'];
								}
							}elseif ($values['apkNum'] == '畅梦') {
								if (empty($values1['exProportion'])) {
									$values1['exProportion'] = 0.8;
								}
								$discountAmount = $values['total_amount'] * $values1['exProportion'];
								$memAmount = $values['total_amount'] - $discountAmount;
								$channelAmount = $discountAmount - $values['total_amount'] * 0.25 - $discountAmount * 0.15 + $memAmount;
							}else{
								$channelAmount = 0;
								$qyOrderData[$keys]['type'] = 2;
							}
							$qyOrderData[$keys]['cpAmount'] = $cpAmount;
							$qyOrderData[$keys]['channelAmount'] = $channelAmount;
							$qyOrderData[$keys]['profit'] = $values['total_amount'] - $channelAmount - $cpAmount;
						}
					}
				}
				//因操作不当或其他问题，导致无法增加或漏增加相关参数的，使用默认比例处理
				foreach ($qyOrderData as $keys2 => $values2) {
					if (empty($values2['profit'])) {
						foreach ($gameData as $keys3 => $values3) {
							$qyOrderData[$keys2]['type'] = 3;
							if ($values2['gameAlias'] == $values3['alias']) {
								$values3['proportion'] = $values3['proportion'] ? $values3['proportion'] : "0.2";
								$cpAllowance = $values3['cpAllowance'] ? $values3['cpAllowance'] : 0;
								$cpAmount = $values2['total_amount'] * (1 - $cpAllowance) * $values3['proportion'];
								if ($values2['gameAlias'] != 'jianzongqy_yuelan') {
									$channelAmount = $values2['total_amount'] * 0.2;//因为历史遗留无法准确匹配相关数据，按8折计算
									if (strtolower($values2['apkNum']) == 'ios') {
										if($values2['gameAlias'] == 'zhuzaisc_ios'){
											$channelAmount = $values2['total_amount'] * 0.25;
										}else{
											$channelAmount = 0;
										}
									}
								}else{
									$channelAmount = $values2['total_amount'] * 0.78;//越南
									$cpAmount = $values2['total_amount'] * 0;//耀非工作室
								}
							}
							$qyOrderData[$keys2]['cpAmount'] = $cpAmount;
							$qyOrderData[$keys2]['channelAmount'] = $channelAmount;
							$qyOrderData[$keys2]['profit'] = $values2['total_amount'] - $channelAmount - $cpAmount;
						}
					}

					$dataList = array(
						'channelId'	     => $qyOrderData[$keys2]['channelId'],//渠道ID
						'channelName'	 => $qyOrderData[$keys2]['channelName'],//渠道名称
						'gameAlias'		 => $qyOrderData[$keys2]['gameAlias'],//游戏别名
						'gameName'		 => $qyOrderData[$keys2]['gameName'],//游戏名称
						'apkNum'		 => $qyOrderData[$keys2]['apkNum'],//游戏包号
						'date'	         => $day,//日期
						'amount'		 => $qyOrderData[$keys2]['total_amount'],//流水
						'cpAmount'		 => $qyOrderData[$keys2]['cpAmount'],//CP分成
						'channelAmount'	 => $qyOrderData[$keys2]['channelAmount'],//渠道分成
						'profit'		 => $qyOrderData[$keys2]['profit'],//利润
						'type'		 	 => $qyOrderData[$keys2]['type'],//数据类型 1：联运 2.广告 3、cps
						'disAmount'		 => $qyOrderData[$keys2]['total_amount'],
						);
					$model->set($dataList);
				}*/
				$qyDisSql = 'SELECT `gameAlias`, `gameName`, `apkNum`, sum(disMoney) AS total_dis FROM ms_order_tmp WHERE time BETWEEN '.$start.' AND '.$end.' AND channelId = "160068" GROUP BY `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$qyDisData = model::getBySql($qyDisSql);

				$qyOrderSql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `apkNum`, sum(money) AS total_amount FROM ms_order WHERE time BETWEEN '.$start.' AND '.$end.' AND orderStatus = "1" AND channelId = "160068" AND adid = "" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$qyOrderData = model::getBySql($qyOrderSql);

				foreach ($qyOrderData as $kq => $vq) {
					$qyOrderData[$kq]['disMoney'] = $vq['total_amount'];
					foreach ($qyDisData as $kq1 => $vq1) {
						if ($vq['channelId'] = '160068' && $vq['gameAlias'] == $vq1['gameAlias'] && $vq['apkNum'] == $vq1['apkNum']) {
							$qyOrderData[$kq]['disMoney'] = $vq1['total_dis'];
						}
					}
				}
				foreach ($qyOrderData as $k => $v) {
					foreach ($channelData as $k1 => $v1) {
						if ($v['gameAlias'] == $v1['gameAlias'] && $v['channelId'] == $v1['channelId'] && $v['apkNum'] == $v1['apkNum']) {
							$qyOrderData[$k]['type'] = 3;
							$cpAllowance = $v1['cpAllowance'] ? $v1['cpAllowance'] : 0;
							//cp的分成
							$cpAmount = $v['total_amount'] * (1 - $cpAllowance) * $v1['proportion'];

							$osGs = array('南宇', '心玩'); //外部GS
							$qyGs = array('耀非', '主包'); //我方GS

							$exArr = explode(',', $v1['channelAllowance']);
							if (empty($exArr[1])) {
								$exPro = 0;
							}else{
								$exPro = $exArr[1];
							}
							if (empty($exArr[0])) {
								$exAllowance = 0.012;
							}else{
								$exAllowance = $exArr[0];
							}

							if (in_array($v['apkNum'], $osGs)) {
								if ($exPro == 0) {
									$exPro = 0.2; //默认分成0.2
								}
								//渠道分成算法为：折扣损失金额 + 折扣后流水分成
								$channelAmount = $v['total_amount'] - $v['disMoney'] + $v['disMoney'] * $exPro;

							}elseif (in_array($v['apkNum'], $qyGs)) {
								if ($v1['cmg'] == 1) {//因历史遗留问题，畅梦包特殊处理
									//我方先分总金额的25%，再分折扣金额的15%（两部分相加为我方与cp的总分成）
									//渠道分成算法为 ：总金额 - 我方与cp的总分成
									$channelAmount = $v['total_amount'] - $v['total_amount'] * 0.25 - $v['disMoney'] * 0.15;

								}elseif ($v1['cmg'] == 2) {
									//渠道分成算法为 ：GS分成 + 折扣损失金额
									//$gsAmount = $v['disMoney'] * 0.2;//默认分成0.2
									//$channelAmount = $gsAmount + $v['total_amount'] - $v['disMoney'];
									//渠道分成算法为 ：（真实支付-cp分成）* 0.25
									$channelAmount = $v['total_amount'] - $v['disMoney'] + ($v['disMoney'] - $cpAmount) * 0.25;

								}else{
									$gsAmount = $v['disMoney'] * $exPro;
									$channelAmount = $gsAmount + $v['total_amount'] - $v['disMoney'];
								}
							}elseif ($v['apkNum'] == '炎游') {
								if ($exPro == 0) {
									$exPro = 0.25; //默认分成0.2
								}
								//渠道分成算法为 ：（真实支付-cp分成）* 0.25
								//$channelAmount = $v['total_amount'] - $v['total_amount'] * 0.25 - $v['disMoney'] * 0.15;
								$channelAmount = $v['total_amount'] - $v['disMoney'] + ($v['disMoney'] - $cpAmount) * $exPro;

							}elseif ($v['apkNum'] == '畅梦') {
								//我方先分总金额的25%，再分折扣金额的15%（两部分相加为我方与cp的总分成）
								//渠道分成算法为 ：总金额 - 我方与cp的总分成
								$channelAmount = $v['total_amount'] - $v['total_amount'] * 0.25 - $v['disMoney'] * 0.15;
							}elseif ($v['apkNum'] == '外放') {
								//渠道分成算法为 ：总金额 * 分成比例
 								$channelAmount = $v['total_amount'] * $exPro;
							}elseif(strpos($v['apkNum'], '分包') !== false){
								$channelAmount = 0;
								$qyOrderData[$k]['type'] = 2;
							}else{
								$gsAmount = $v['disMoney'] * $exPro;
								$channelAmount = $gsAmount + $v['total_amount'] - $v['disMoney'];
							}
							$qyOrderData[$k]['cpAmount'] = $cpAmount;
							$qyOrderData[$k]['channelAmount'] = $channelAmount;
							$qyOrderData[$k]['profit'] = $v['total_amount'] - $channelAmount - $cpAmount;
						}
					}
				}
				//因操作不当或其他问题，导致无法增加或漏增加相关参数的，使用默认比例处理
				foreach ($qyOrderData as $keys2 => $values2) {
					if (empty($values2['profit'])) {
						foreach ($gameData as $keys3 => $values3) {
							$qyOrderData[$keys2]['type'] = 3;
							if ($values2['gameAlias'] == $values3['alias']) {
								$values3['proportion'] = $values3['proportion'] ? $values3['proportion'] : "0";
								$cpAllowance = $values3['cpAllowance'] ? $values3['cpAllowance'] : 0;
								$cpAmount = $values2['total_amount'] * (1 - $cpAllowance) * $values3['proportion'];
								if ($values2['gameAlias'] != 'jianzongqy_yuelan') {
									$channelAmount = 0;
									if (strtolower($values2['apkNum']) == 'ios') {
										if($values2['gameAlias'] == 'zhuzaisc_ios'){
											$channelAmount = $values2['total_amount'] * 0.25;
										}else{
											$channelAmount = 0;
										}
									}
								}else{
									$channelAmount = $values2['total_amount'] * 0.78;//越南
									$cpAmount = $values2['total_amount'] * 0;//耀非工作室
								}
							}
							$qyOrderData[$keys2]['cpAmount'] = $cpAmount;
							$qyOrderData[$keys2]['channelAmount'] = $channelAmount;
							$qyOrderData[$keys2]['profit'] = $values2['total_amount'] - $channelAmount - $cpAmount;
						}
					}

					$dataList = array(
						'channelId'	     => $qyOrderData[$keys2]['channelId'],//渠道ID
						'channelName'	 => $qyOrderData[$keys2]['channelName'],//渠道名称
						'gameAlias'		 => $qyOrderData[$keys2]['gameAlias'],//游戏别名
						'gameName'		 => $qyOrderData[$keys2]['gameName'],//游戏名称
						'apkNum'		 => $qyOrderData[$keys2]['apkNum'],//游戏包号
						'date'	         => $day,//日期
						'amount'		 => $qyOrderData[$keys2]['total_amount'],//流水
						'cpAmount'		 => $qyOrderData[$keys2]['cpAmount'],//CP分成
						'channelAmount'	 => $qyOrderData[$keys2]['channelAmount'],//渠道分成
						'profit'		 => $qyOrderData[$keys2]['profit'],//利润
						'type'		 	 => $qyOrderData[$keys2]['type'],//数据类型 1：联运 2.广告 3、cps
						'disAmount'		 => $qyOrderData[$keys2]['disMoney'],
						);
					$model->set($dataList);
				}
			}elseif ($hour == '4') {
				//乾游ios
				$iosOrderSql = 'SELECT `gameAlias`, `gameName`, `channelId`, `channelName`, `apkNum`, sum(money) AS total_amount FROM ms_order WHERE time BETWEEN '.$start.' AND '.$end.' AND orderStatus = "1" AND channelId = "500011" GROUP BY `channelId`, `gameAlias`, `apkNum` ORDER BY `gameAlias` DESC';
				$iosOrderData = model::getBySql($iosOrderSql);

				/*foreach ($gameData as $keys4 => $values4) {
					foreach ($iosOrderData as $keys5 => $values5) {
						if ($values5['gameAlias'] == $values4['alias']) {
							$values4['proportion'] = $values4['proportion'] ? $values4['proportion'] : "0.2";
							$cpAllowance = $values4['cpAllowance'] ? $values4['cpAllowance'] : 0;
							$cpAmount = $values5['total_amount'] * (1 - $cpAllowance) * $values4['proportion'];
							$channelAmount = 0;
							//因有可能存在支付切换，所以需要分别计算不同支付的利润
							if ($values5['paymentId'] == '11') {
								$channelAmount = $values5['total_amount'] * 0.3;//ios固定分成
							}
							$gameData[$keys4]['amount'] += $values5['total_amount'];
							$gameData[$keys4]['profit'] += $values5['total_amount'] - $channelAmount - $cpAmount;
							$gameData[$keys4]['channelAmount'] += $channelAmount;
							$gameData[$keys4]['cpAmount'] += $cpAmount;
						}
					}
					if (!empty($gameData[$keys4]['amount'])) {
						$dataList = array(
							'channelId'	     => '500011',//渠道ID
							'channelName'	 => 'IOS',//渠道名称
							'gameAlias'		 => $gameData[$keys4]['alias'],//游戏别名
							'gameName'		 => $gameData[$keys4]['name'],//游戏名称
							'apkNum'		 => '主包',//游戏包号
							'date'	         => $day,//日期
							'amount'		 => $gameData[$keys4]['amount'],//流水
							'cpAmount'		 => $gameData[$keys4]['cpAmount'],//CP分成
							'channelAmount'	 => $gameData[$keys4]['channelAmount'],//渠道分成
							'profit'		 => $gameData[$keys4]['profit'],//利润
							'type'		 	 => '3',//数据类型 1：联运 2.广告 3、cps
							);
						$model->set($dataList);
					}
				}*/
				foreach ($iosOrderData as $keys4 => $values4) {
					foreach ($channelData as $keys5 => $values5) {
						if ($values4['gameAlias'] == $values5['gameAlias'] && $values4['channelId'] == $values5['channelId'] && $values4['apkNum'] == $values5['apkNum']) {
							
							$cpAllowance = $values5['cpAllowance'] ? $values5['cpAllowance'] : 0;
							//cp的分成
							$cpAmount = $values4['total_amount'] * (1 - $cpAllowance) * $values5['proportion'];
							
							$exArr = explode(',', $values5['channelAllowance']);
							if (empty($exArr[1])) {
								$exPro = 0;
							}else{
								$exPro = $exArr[1];
							}
							$disMoney = $values4['disMoney'] ? $values4['disMoney'] : $values4['total_amount'];
							//$gsAmount = $disMoney * $exPro;
							$channelAmount = $disMoney * $exPro + $values4['total_amount'] - $disMoney;

							$iosOrderData[$keys4]['cpAmount'] = $cpAmount;
							$iosOrderData[$keys4]['channelAmount'] = $channelAmount;
							//分成后的利润
							$iosOrderData[$keys4]['profit'] = $values4['total_amount'] - $cpAmount - $channelAmount;
							$iosOrderData[$keys4]['disMoney'] = $disMoney;
						}
					}
					$dataList = array(
						'channelId'	     => $iosOrderData[$keys4]['channelId'],//渠道ID
						'channelName'	 => $iosOrderData[$keys4]['channelName'],//渠道名称
						'gameAlias'		 => $iosOrderData[$keys4]['gameAlias'],//游戏别名
						'gameName'		 => $iosOrderData[$keys4]['gameName'],//游戏名称
						'apkNum'		 => $iosOrderData[$keys4]['apkNum'],//游戏包号
						'date'	         => $day,//日期
						'amount'		 => $iosOrderData[$keys4]['total_amount'],//流水
						'cpAmount'		 => $iosOrderData[$keys4]['cpAmount'],//CP分成
						'channelAmount'	 => $iosOrderData[$keys4]['channelAmount'],//渠道分成
						'profit'		 => $iosOrderData[$keys4]['profit'],//利润
						'type'		 	 => '3',//数据类型 1：联运 2.广告 3、cps
						'disAmount'		 => $iosOrderData[$keys4]['disMoney'],
						);
					$model->set($dataList);
				}
			}
		}else{
			return false;
		}
	}

	/**
	 * 用于发送短信提醒相关客服及时补单发货
	 * 每5分钟执行一次
	 */
	public function remindToSms() {
		$order_model = new model('ms_order');
		$countSql = "SELECT COUNT(1) as total FROM ms_order WHERE orderStatus = 2";
		$count = $order_model->getBySql($countSql);
		$telphoneList = array('13631467268'); 

		if (!empty($count[0]['total'])) {
			foreach ($telphoneList as $key => $value) {
				noerSendSms($value, $count[0]['total']);
			}
		}

	}

	/**
	 * 用于补充利润统计的数据
	 * 每天凌晨5点统计（需要在msProfit每天有效运行后执行）
	 * 不在msProfit、msIntermodal中统计是为了减少单一计划任务消耗的资源
	 */
	public function profitAdded($date = null) {

		$profitModel = new model('ms_profit_daily');
		if ($date) {
			$yesterday = $date;
		}else{
			$yesterday = date("Y-m-d",strtotime("-1 day"));
		}

		$start = strtotime($yesterday);
		$end = strtotime($yesterday) + 86399;

		$profitSql = "SELECT * FROM ms_profit_daily WHERE `date` = '".$yesterday."'";
		$profit = model::getBySql($profitSql);

		//联运
		$jointSql = 'SELECT o.`channelId`, o.`channelName`, o.`gameAlias`, o.`gameName`, o.`apkNum`, SUM(o.`money`) AS newAmount FROM ms_order o LEFT JOIN `ms_role_seted` r ON o.`roleId` = r.`roleId` AND o.`gameAlias` = r.`gameAlias` AND o.`channelId` = r.`channelId` AND o.`userName` = r.`userName` WHERE o.`orderStatus` = "1" AND r.`type` = "server" AND r.`isFirst` = 1 AND r.`time` BETWEEN "' . $start . '" AND "' . $end . '" AND FROM_UNIXTIME( o.`time`, "%Y-%m-%d" ) = "' . $yesterday . '" AND o.`adid` = "" GROUP BY o.`channelId`, o.`gameAlias`, o.`apkNum`';
		$jonint = model::getBySql($jointSql);

		//广告
		$adsSql = 'SELECT o.`channelId`, o.`channelName`, o.`gameAlias`, o.`gameName`, o.`apkNum`, SUM(o.`money`) AS newAmount FROM ms_order o LEFT JOIN `ms_role_seted` r ON o.`roleId` = r.`roleId` AND o.`gameAlias` = r.`gameAlias` AND o.`channelId` = r.`channelId` AND o.`userName` = r.`userName` WHERE o.`orderStatus` = "1" AND r.`type` = "server" AND r.`isFirst` = 1 AND r.`time` BETWEEN "' . $start . '" AND "' . $end . '" AND FROM_UNIXTIME( o.`time`, "%Y-%m-%d" ) = "' . $yesterday . '" AND o.`adid` != "" GROUP BY o.`channelId`, o.`gameAlias`, o.`apkNum`';
		$ads = model::getBySql($adsSql);

		foreach ($profit as $key => $value) {
			foreach ($jonint as $k1 => $v1) {
				if ($value['type'] != 2 && $value['gameAlias'] == $v1['gameAlias'] && $value['channelId'] == $v1['channelId'] && $value['apkNum'] == $v1['apkNum']) {
					$newlyProfit = round(round($value['profit'] / $value['amount'], 4) * $v1['newAmount'], 2);
					$profitModel->set(array('newlyProfit' => $newlyProfit,'newAmount' => $v1['newAmount']), 'gameAlias=%s AND channelId=%s AND apkNum=%s AND date=%s AND type=%s', array($v1['gameAlias'], $v1['channelId'], $v1['apkNum'], $yesterday, $value['type']));
				}
			}
			foreach ($ads as $k2 => $v2) {
				if ($value['type'] == 2 && $value['gameAlias'] == $v2['gameAlias'] && $value['channelId'] == $v2['channelId'] && $value['apkNum'] == $v2['apkNum']) {
					$newlyProfit = round(round($value['profit'] / $value['amount'], 4) * $v2['newAmount'], 2);
					$profitModel->set(array('newlyProfit' => $newlyProfit,'newAmount' => $v2['newAmount']), 'gameAlias=%s AND channelId=%s AND apkNum=%s AND date=%s AND type=2', array($v2['gameAlias'], $v2['channelId'], $v2['apkNum'], $yesterday));
				}
			}
		}
	}

	/**
	 * 用于清除测试数据
	 * 每天凌晨4点执行
	 */
	public function clearTestData() {
		$sql = "SELECT * FROM ms_channel WHERE clearData != '';";
		$channel = model::getBySql($sql);
		if (!$channel) {
			return false;
		}

		foreach ($channel as $key => $value) {
			$game = $value['gameAlias'];
			$channelId = $value['channelId'];
			$apkNum = $value['apkNum'];
			$clearData = json_decode($value['clearData'], true);
			$start = strtotime($clearData['clearStart']);
			$end = strtotime($clearData['clearEnd']) + 86399;

			//清除操作必须存在起始时间和结束时间
			if (!empty($clearData['clearStart']) && !empty($clearData['clearEnd'])) {
				$orderSql = "UPDATE ms_order SET orderStatus = 4 WHERE gameAlias = '{$game}' AND orderStatus = 1 AND channelId = '{$channelId}' AND apkNum = '{$apkNum}' AND time >= {$start} AND time <= {$end} LIMIT 200";
				model::getBySql($orderSql);

				$consumSql = "UPDATE ms_integrated_daily SET status = 2 WHERE channelId = '{$channelId}' AND gameAlias = '{$game}' AND apkNum = '{$apkNum}' AND date >= '{$clearData['clearStart']}' AND date <= '{$clearData['clearEnd']}'  LIMIT 100";
				model::getBySql($consumSql);

				$profitSql = "UPDATE ms_profit_daily SET status = 2 WHERE channelId = '{$channelId}' AND gameAlias = '{$game}' AND apkNum = '{$apkNum}' AND date >= '{$clearData['clearStart']}' AND date <= '{$clearData['clearEnd']}'  LIMIT 100";
				model::getBySql($profitSql);
				
				$channelSql = "UPDATE ms_channel SET clearData = '' WHERE id = '{$value['id']}'";
				model::getBySql($channelSql);
			}
		}
	}


	/*
	 * 用于查询sdk服务器ip是否可用
	 * 每天执行一次
	 */
	public function ipCheck() {

		$config = require(APP_LIST_PATH . "main/config.sdkip.php");

		$ip = '';
		$available = 0;
		foreach ($config as $key => $value) {
			if (httpcode($value['ip']) == 200) {
				$config[$key]['status'] = 1;
				$available++;
			}else{
				$config[$key]['status'] = 0;
			}
		}
		//可用ip少于等于3条时短信提示
		if ($available <= 3) {
			//noerSendSms('18320714614', $available, '9000001407275074');
		}
		$output = "<?php";
		$output .= "\n";
		$output .= "return ";
		$output .= var_export($config, true);
		$output .= ";";
		file_put_contents(APP_LIST_PATH . "main/config.sdkip.php", $output);
	}

	/**
	 * 重推千古宠界的硬核数据到九尊
	 * 
	 * 时间：2021-7-30到2021-11-30
	 */
	public function jzOrderRepost($channelId, $startTime, $endTime)
	{	
		$game = 'xiaoyaolr_dtry2';

		// 验证参数
		$paramName = '';
		if (empty($game)) {
			$paramName = 'game';
		}elseif (empty($channelId)) {
			$paramName = 'channelId';
		}elseif (empty($startTime)) {
			$paramName = 'startTime';
		}elseif (empty($endTime)) {
			$paramName = 'endTime';
		}
		if ($paramName) {
			$output = array("code" =>9002, "data" =>'', "message"=>'缺少验证参数'.$paramName);
			echo json_encode($output);exit;
		}

		if ($game == 'xiaoyaolr_dtry2') {
			$sql = "SELECT `username`, `orderId`, `server`, `roleId`, `roleName`, `money`, `time` FROM `ms_order` WHERE `gameAlias` = '{$game}' AND `channelId` = '{$channelId}' AND `orderStatus` = 1 AND `sendStatus` = 1 AND `time` >= '{$startTime}' AND `time` <= '{$endTime}'";
			$sqlRes = model::getBySql($sql);

			if ($channelId == '000066') {
				$pf = 'LXM072OG';
			}elseif ($channelId == '500001') {
				$pf = 'HWV5';
			}elseif ($channelId == '000368') {
				$pf = 'LVIVO';
			}elseif ($channelId == '000020') {
				$pf = 'LOPPO';
			}elseif ($channelId == '160136') {
				$pf = 'LYYB';
			}

			$user_model = getInstance('@main.model.libs.mGameSDKUser');
			foreach ($sqlRes as $key => $value) {
				// 创建支付订单
				$createOrderRes = $user_model->jzCreateOrder($pf, $value);
				if (empty($createOrderRes)) {
					$output = array("code"  => 201, "data"  => array('orderId' => $value['orderId']), "message" => '创建支付订单推送失败');
					echo json_encode($output);
				}
				// 更新支付订单状态
				$callBackRes = $user_model->jzCallBack($pf, $value['orderId'], $value['money']);
				if (empty($callBackRes)) {
					$output = array("code"  => 201, "data"  => array('orderId' => $value['orderId']), "message" => '更新支付订单状态推送失败');
					echo json_encode($output);
				}
			}

			$output = array("code"  => 200, "data" => '', "message" => '完成推送');
			echo json_encode($output);exit;

		}else {
			$output = array("code" =>2015, "data" =>'', "message"=>'游戏名非法');
			echo json_encode($output);exit;
		}

	}


	/*
	 * 早上6点执行
	 * 用于获取达咖玩GS转端数据利润
	 */
	public function getDkwAssociatedProfit() {
		$config = require(APP_LIST_PATH . "main/config.inc.php");
		$data['key'] = $config['common_key'];
		$data['time'] = time();
		$data['date'] = date('Y-m-d', $data['time'] - 86400);//需要获取数据的日期
		$data['sign'] = md5($data['time']. $data['date'] . $data['key']);
		$data['type'] = 'profit';//不参与验签

		//$url = "http://sdk.api.gzzy128.co/api/index.php?a=getMidData";
		$url = "http://open.api.gzzy128.com/index.php?a=getMidData";
		$res = httpRequest($url, $data);
		$res = json_decode($res, true);
		if ($res['code'] == 1) {
			$resultData = json_decode($res['data'], true);

			$sql = "SELECT p.id, p.gameAlias, c.appId FROM ms_channel c RIGHT JOIN ms_profit_daily p ON c.gameAlias = p.gameAlias AND c.channelId = p.channelId AND c.apkNum = p.apkNum WHERE p.channelId = '500028' AND p.date = '" . $data['date'] . "' AND c.appId !=''";
			$sqlRes = model::getBySql($sql);

			$profitModel = new model('ms_profit_daily');
			foreach ($sqlRes as $key => $value) {
				foreach ($resultData as $k => $v) {
					//使用appId作为判断条件更准确，因为存在少部分项目别名不一致
					if ($v['game'] == $value['appId']) {
						$profitModel->set(array('income' => $v['profit']),
						'`id`=%s',
						array($value['id'])
						);
					}
				}
			}
		}

	}

	/*
	 * 早上6点执行
	 * 用于获取耀玩GS转端数据利润
	 */
	public function getYaowanAssociatedProfit() {

		$config = require(APP_LIST_PATH . "main/config.inc.php");
		$data['key'] = $config['common_key'];
		$data['time'] = time();
		$data['date'] = date('Y-m-d', $data['time'] - 86400);//需要获取数据的日期
		$data['sign'] = md5($data['time']. $data['date'] . $data['key']);
		$data['type'] = 'profit';//不参与验签

		$url = "http://open.api.online128.com/index.php?a=getMidData";
		$res = httpRequest($url, $data);
		$res = json_decode($res, true);
		if ($res['code'] == 1) {
			$resultData = json_decode($res['data'], true);

			$sql = "SELECT p.id, p.gameAlias, c.appId FROM ms_channel c RIGHT JOIN ms_profit_daily p ON c.gameAlias = p.gameAlias AND c.channelId = p.channelId AND c.apkNum = p.apkNum WHERE p.channelId = '500071' AND p.date = '" . $data['date'] . "' AND c.appId !=''";
			$sqlRes = model::getBySql($sql);

			$profitModel = new model('ms_profit_daily');
			foreach ($sqlRes as $key => $value) {
				foreach ($resultData as $k => $v) {
					//使用appId作为判断条件更准确，因为存在少部分项目别名不一致
					if ($v['game'] == $value['appId']) {
						$profitModel->set(array('income' => $v['profit']),
						'`id`=%s',
						array($value['id'])
						);
					}
				}
			}
		}

	}


	/**
	 * 数据库瘦身
	 * 每5分钟执行一次
	 * 方法限制只在03:00-06:59 有效执行
	 */
	public function diskSpaceOptimize() {
		load('model.optimize');
		$optimize = new optimize();

		$optimize->importTableData();
	}

	/**
	 * 执行导出文件程序
	 * 
	 */
	public function outputFiles() 
	{	
		$sql = "select * from ms_fileManage where status = '1'";
		$res = model::getBySql($sql);

		load('model.outputFiles');
		$outputFiles = new outputFiles();

		foreach ($res as $key => $value) {
			switch($value['function']) {
				case 'order':

					// 导出订单
					$outputFiles->outputOrder($value);

					break;
				default:
				
					break;
			}
		}
	}

	/**
     * 
	 * 删除创建时间大于3天的目录及其目录下的所有目录和文件(每天凌晨4点执行)
	 * 
	 */
	public function deleteDir() {

        // 获取创建时间大于3天的文件导出记录
        $dayStr = date("Y-m-d", strtotime("-3 day"));
		$day = str_replace('-', '', $dayStr);
        $timestamp = strtotime($dayStr. " 23:59:59");

		// 删除创建时间大于3天的目录和目录下所有文件
        $path = C('DEDE_DATA_PATH'). 'orderExcel/'. $day. '/';
		if (is_dir($path)) {
			// 扫描指定目录和目录下的所有文件：获取包含指定目录和目录下的所有文件的数组，示例：array('images', 'horse.gif', 'dog.gif', 'cat.gif', '..', '.')
			$dirs = scandir($path);
			// 遍历目录和目录下所有文件
			foreach ($dirs as $dir) {
				// 遍历结果中的符号.表示是当前目录，符号..表示是上级目录，所有要去掉这两个目录
				if ($dir != '.' && $dir != '..') {
					$sonDir = $path.'/'.$dir;
					// 判断是否目录，如果是目录，则递归循环继续执行当前函数deleteDir，直到遍历结果是文件
					if (is_dir($sonDir)) {
						$this->deleteDir($sonDir);
						// 删除空目录：如果是空目录，则直接删除
						@rmdir($sonDir);
					} else {
						// 删除文件
						@unlink($sonDir);
					}
				}
			}
			// 删除空目录：如果是空目录，则直接删除
			@rmdir($path);
		}

		// 修改文件状态为已删除
        $fileManageModel = new model('ms_fileManage');
		$fileManageModel->set(
            array(
                'status' => '3'
            ), 
            "createTime <= '{$timestamp}'"
        );

		return true;
	}

	/**
	 * 重推原来转端玩家的转端标识至达咖玩
	 */
	public function dkwOldReportRelation()
	{	
		$sql = "select platformUserId from ms_member_info where assUserName != '' and channelId = '500028'";
		$sqlRes = model::getBySql($sql);
		
		foreach($sqlRes as $key => $value){
			$url = 'http://sdk.api.gzzy128.com//api/index.php?m=index&a=relationsMember';

			$body = array(
				'memberSub' => $value['platformUserId'],
				'isRelations' => 1,
			);
	
			$config = require(APP_LIST_PATH . "main/config.inc.php");
			$common_key = $config['common_key'];
			$sign_str = $body['memberSub'].$body['isRelations'].$common_key;
			$body['sign'] = md5($sign_str);

			$res = httpRequest($url, $body);
	
			// 记录操作日志
			error_log("\n". date("[Y-m-d H:i:s]"). "\n". "url: ". $url. "\n".  "body: ". json_encode($body). "\n". "sign_str: ". $sign_str. "\n". "res: ". $res. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/dkwReportRelation_". date("ymd"). ".txt");
		}
	}

	/**
	 * 发送邮件
	 * 请求频率：5分钟/次
	 */
	public function sendMail(){	
		//查询过去10分钟的登录
		$start = time() - 60 * 10;
		$sql = "SELECT userName, contactAddress, gameAlias, roleId, roleName, serverId FROM ms_member_maintain WHERE `data` >= $start AND handleType = 'login' AND contactType = 'email' AND `status` = 0 GROUP BY userName LIMIT 200";

		$res = model::getBySql($sql);
		if ($res) {
			$gSql = "SELECT upperName, specialName, name, alias FROM ms_game";
			$gameList = model::getBySql($gSql);

			$gameNameList = array();
			foreach ($gameList as $kr => $vr) {
				$gameNameList[$vr['alias']] = $vr['upperName']. '—'. $vr['specialName']. '—'. $vr['name'];
			}

			$data = array();
			foreach ($res as $key => $value) {
				$data[$value['contactAddress']] .= "账号：" . $value['userName'] . ', 最近登录游戏: ' . $gameNameList[$value['gameAlias']] . ', 角色名称: ' . $value['roleName'] . ', 区服: ' . $value['serverId'] . '<br>';
				//$data[$value['contactAddress']][] = $value['userName'];
			}
			
			foreach($data as $k => $v){
				$emailList = explode('|', $k);
				if (count($emailList) == 1) {
					$sendto_email = $k;
					$subject = '用户登录提醒';
					$body = '中央后台检测到以下账号有登录操作<br>' . $v;
					//记录发送次数
					smtp_mail($sendto_email, $subject, $body);
					$fSql = "UPDATE ms_member_maintain SET frequency = frequency + 1 WHERE `data` >= $start AND handleType = 'login' AND contactType = 'email' AND `status` = 0 AND contactAddress = '$k'";
					model::getBySql($fSql);
				}else {
					foreach ($emailList as $k1 => $v1) {
						$sendto_email = $v1;
						$subject = '用户登录提醒';
						$body = '中央后台检测到以下账号有登录操作<br>' . $v;
						smtp_mail($sendto_email, $subject, $body);
					}
					//记录发送次数
					$fSql = "UPDATE ms_member_maintain SET frequency = frequency + 1 WHERE `data` >= $start AND handleType = 'login' AND contactType = 'email' AND `status` = 0 AND contactAddress = '$k'";
					model::getBySql($fSql);
				}

			}
		}
		//通知超过200次的停止通知
		$stopSql = "UPDATE ms_member_maintain SET status = 3 WHERE frequency >= 200 AND status = 0";
		model::getBySql($stopSql);
	}

	/*
	* 批量导入vip付费用户
	*/
	// public function importVip(){

	// 	$vipGuestModel = new model('ms_vipguest');

	// 	//初始化设置导入条数
	// 	$import_str_num = 1; //从第几条开始
	// 	$import_end_num = 2; //到第几条结束且包括该条, 例: import_end_num = 2  1,2
		
	// 	$result = $this->getUploadFile('profit', 3000, 'C');
		
	// 	//删除标题内容
	// 	unset($result[1]);

	// 	foreach ($result as $key => $value) {
	// 		if ($key < $import_str_num + 1) {
	// 			continute;
	// 		}else if($key > $import_end_num + 1){
	// 			continute;
	// 		}else{
	// 			$userList = model::getBySql("SELECT mm.channelId, mm.channelName, mm.joinTime, mm.loginTime, mm.gameAlias, mm.gameName, mmi.assUserName, mmi.loginTime mmiLoginTime FROM `ms_member` as mm left JOIN `ms_member_info` as mmi on mm.userName = mmi.userName where mm.userName = '{$value[0]}' limit 1");
	// 			if ($userList){
	// 				$result[$key]['channelId'] = $userList[0]['channelId'];
	// 				$result[$key]['channelName'] = $userList[0]['channelName'];
	// 				$result[$key]['joinTime'] = $userList[0]['joinTime'];
	// 				$result[$key]['loginTime'] = $userList[0]['loginTime'];
	// 				$result[$key]['gameAlias'] = $userList[0]['gameAlias'];
	// 				$result[$key]['gameName'] = $userList[0]['gameName'];
	// 				$result[$key]['assUserName'] = $userList[0]['assUserName'];
	// 				$result[$key]['mmiLoginTime'] = $userList[0]['mmiLoginTime'];
	// 				$result[$key]['relationStatus'] = $userList[0]['assUserName'] ? 1 : 0;

	// 				$data_list = array(
	// 					'userName'		=> $value[0],
	// 					'channelName'	=> $result[$key]['channelName'],
	// 					'channelId'		=> $result[$key]['channelId'],
	// 					'weixin'	    => $value[1],
	// 					'vipJoinTime'	=> time(),
	// 					'uid'			=> $value[2],
	// 					'status'		=> 1,
	// 					'joinTime'		=> $result[$key]['joinTime'],
	// 					'loginTime'		=> $result[$key]['loginTime'],
	// 					'lastGameAlias' => $result[$key]['gameAlias'],
	// 					'lastGameName'	=> $result[$key]['gameName'],
	// 					'relationUserName'	=> $result[$key]['assUserName'],
	// 					'relationLoginTime'	=> $result[$key]['mmiLoginTime'],
	// 					'relationStatus'    => $result[$key]['assUserName'] ? 1 : 0
	// 				);
	// 				$status = $vipGuestModel->set($data_list);
	// 			}
	// 		}
	// 	}

	// }
	/*
	* 读取文件内容
	*/
	// public function getUploadFile($target, $line=1000, $format, $url='-1') {

	// 	//内置导入文件路径
	// 	$dir =  C('DEDE_DATA_PATH').'orderExcel/20221025/profit1666671239.xlsx';

 //        load('plugins.PHPExcel.Classes.PHPExcel.IOFactory');

 //        $phpExcel = PHPExcel_IOFactory::load($dir);
	// 	// 设置为默认表
	// 	$phpExcel->setActiveSheetIndex(0);
	// 	// 获取表格数量
	// 	$sheetCount = $phpExcel->getSheetCount();
	// 	// 获取行数
	// 	$row = $phpExcel->getActiveSheet()->getHighestRow();
	// 	// 获取列数
	// 	$column = $phpExcel->getActiveSheet()->getHighestColumn();
	// 	$highestRow = $phpExcel->getActiveSheet()->getHighestRow();
	// 	$highestColumn = $phpExcel->getActiveSheet()->getHighestColumn();
	// 	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	// 	$excelData = array();
	// 	for ($row = 1; $row <= $highestRow; $row++) {
	// 		for ($col = 0; $col < $highestColumnIndex; $col++) {
	// 		$excelData[$row][] =(string)$phpExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue();
	// 		}
	// 	}
		
	// 	if ($row > $line) {
	// 		ShowMsg("添加条数已超过上限", $url);exit;
	// 	}
	// 	if (empty($format)) {
	// 		ShowMsg("未设置表格格式", $url);exit;
	// 	}

	// 	if (strtoupper($column) != strtoupper($format)) {
	// 		ShowMsg("添加的表格格式不正确", $url);exit;
	// 	}
	// 	return $excelData;
	// }
}