<?php

/**
 * 异步补发元宝
 * 
 */
class gameIngots {

	/**
	 * 储值结果异步通知
	 */
	public function ordedrAsynchronous() {

		//通信协议
		define('PVC', '1');

		//获取SDK配置文件
		$this->config = require(APP_LIST_PATH . "main/config.inc.php");

		if ($this->config['key']) {
			foreach ($this->config['key'] as $key => $value){
				if ($value['callback_url']) {
					$this->sdk_games_api[$key] = $value['callback_url'];
				}
			}
		}

		$sdk_games = array_keys($this->sdk_games_api);

		//只执行最近2个小时的订单
		$striffs = time() - 3600*2;

		//ms_order表
		$sql = "SELECT o.`orderId`, o.`gameAlias` as game, o.`money`, o.`time`, o.`userName`, o.`channelId`, o.`roleId`, o.`server`, o.`gameMessage`, o.`gold`, o.`orderDescr`, o.`apkNum`, o.`roleName`, o.`gameAlias`, o.`gameName`, o.`channelName` FROM `ms_order` o WHERE o.`orderStatus`=1 AND o.`sendStatus`=0 AND o.`time` >  $striffs";
		//$sql = "SELECT o.`orderId`, o.`gameAlias` as game, o.`money`, o.`time`, o.`userName`, o.`channelId`, o.`roleId`, o.`server`, o.`gameMessage`, o.`gold`, o.`orderDescr` FROM `ms_order` o WHERE o.`orderStatus`=1 AND o.sendStatus=0 AND ( o.`time` >  $striffs OR o.`orderId` IN (2018032800362667224, 2018032721231393999, 2018032619575001633) )";
		$rs = model::getBySql($sql);

		foreach ($rs as $key => $value) {
			$success = $this->requestGame($value);
			//更改订单状态
			if($success) {
				$update_sql = "UPDATE ms_order SET sendStatus=1 WHERE `orderId`='{$value['orderId']}'";
				//echo $update_sql;
				model::getBySql($update_sql);
			}
		}

		//重新回调渠道平台充值失败订单
		$csql = "SELECT o.`orderId`, o.`gameAlias`, o.`money`, o.`userName`, o.`channelId`, o.`gameMessage` FROM `ms_order` o WHERE o.`orderStatus`=1 AND o.`sendStatus`=2 AND o.`time` >  $striffs";
		
		$result = model::getBySql($csql);

		foreach ($result as $key => $value) {
			$sdkChannel = 'mGameSDK' . $value['channelId'];
			load('@main.model.api.' . $sdkChannel);
			$channel = new $sdkChannel();

			$success = $channel->requestPlfOrder($value);
			//更改订单状态
			if($success) {
				$update_sql = "UPDATE ms_order SET sendStatus=1 WHERE `orderId`='{$value['orderId']}'";
				model::getBySql($update_sql);
			}
		}

		//重新请求渠道，确定订单状态
		$tSql = "SELECT o.`orderId`, o.`channelId` AS channel, o.`apkNum` AS apknum, o.`gameAlias` AS game, o.`money`, t.`purchaseData` FROM ms_order o LEFT JOIN ms_order_tmp t ON o.`orderId` = t.`orderId` WHERE o.`time` > $striffs AND o.`orderStatus` = 0 AND t.`purchaseData` !=''";
		$tResult = model::getBySql($tSql);
		foreach ($tResult as $key => $value) {
			$sdkChannel = 'mGameSDK' . $value['channel'];
			load('@main.model.api.' . $sdkChannel);
			$channel = new $sdkChannel($value);

			$success = $channel->setOrderStatus($value['money'], true);
			$time = time();
			//更改订单状态
			if($success === true) {
				$update_sql = "UPDATE ms_order SET `orderStatus`=1, `time`={$time} WHERE `orderId`='{$value['orderId']}'";
				model::getBySql($update_sql);
			}
		}
	}

	/**
	 * 记录失败订单
	 * 
	 * @param string $oid
	 * @param string $error
	 */
	function recordFailOrder($oid, $error) {
		//过滤掉特殊字符，防sql注入
		$error = mysql_real_escape_string($error);
		$time = time();
		$insert_sql = "REPLACE INTO ms_order_failure VALUES('{$oid}', '{$error}', $time)";

		model::getBySql($insert_sql);
	}

	/**
	 * 获取失败的订单列表
	 */
	function getFailOrderList() {
		//半个小时后可再请求失败的订单，所以它们不会被返回（当作未请求失败）
		$time = (time() - 1800);
		$sql = "SELECT `oid` FROM `ms_order_failure` WHERE 1/* `time`>$time*/;";

		$rs = model::getBySql($sql);

		$orders = array();
		foreach ($rs as $key => $value) {
			$orders[] = $value['oid'];
		}

		return $orders;
	}

	/**
	 * 请求游戏发放元宝
	 * 
	 * @param array $data 订单信息
	 * @return boolean
	 */
	function requestGame($data) {
		$sql = "SELECT count(1) as num FROM `ms_order_failure` WHERE `oid`={$data['orderId']}";
		$rs = model::getBySql($sql);
		if (intval($rs[0]['num'] >= 10)) {
			return false;
		}

		//网络问题可能导致请求失败，失败的时候需要再次请求
		//但请求次数需要做限制，这里初始化一个值记录请求次数
		$request_count = 1;
		$success = false;

		$callbackStr = ($data['gameMessage']) ? trim($data['gameMessage']) : '';
		
		$user_model = getInstance('@main.model.libs.mGameSDKUser');
		//记录用户订单到临时表
		$user_model->saveOrderTmp($data);
		$channel_mode = new model('ms_channel');
		$channel = $channel_mode->get('gameAlias=%s AND channelId=%s AND apkNum=%s', array($data['gameAlias'], $data['channelId'], $data['apkNum']));
		$userName = $data['userName'];
		if ($channel['interflow'] == 1) {
			$member_mode = new model('ms_member');
			$member = $member_mode->get('`userName`=%s', array($data['userName']));
			//$data['userName'] = $member['platformUserId'];
			if($member){
				$data['userName'] = $member['platformUserId'];
				if ($data['apkNum'] == 'qk_outside') {
					$qk = explode('qk_', $data['userName']);
					$userName = $qk[1];
				}elseif ($data['apkNum'] == 'yj_outside') {
					$yj = explode('yj_', $data['userName']);
					$userName = $yj[1];
				}
			}
		}

		//请求参数按字母排序
		$query_data = array(
		'orderId' => $data['orderId'],
		'money' => $data['money'],
		'timestamp' => $data['time'],
		'userId' => $userName,
		'platformId' => $data['channelId'],
		'roleId' => $user_model->getOnlyRoleId($data['game'], $data['server'], $data['roleId'], false, $data['userName']),//回调给cp用cp的角色id
		'serverId' => $data['server'],
		'callbackStr' => $callbackStr,
		'gold' => $data['gold'],
		//'desc' => $data['orderDescr'],
		'pvc' => PVC,
		);

		ksort($query_data);
		echo implodeWithKey('', $query_data) . $this->config['key'][ $data['game'] ]['game_server'] . "\n";
		//将请求参数和通信密匙加密，形如： amount=1game=2....userid=n . appkey
		$query_data['sign'] = md5(implodeWithKey('', $query_data) . trim($this->config['key'][ $data['game'] ]['game_server']));
		echo $this->sdk_games_api[ $data['game'] ] . "\n";
		print_r($query_data);
		$api = $this->sdk_games_api[ $data['game'] ];

		load('@mgameapi.model.libs.helperTool');
		$helper = new helperTool();

		//开始请求，必需使用POST
		//每个订单最多请求2次
		/*while( ($result_str = httpRequest($api, $query_data)) || $request_count <= 2 ) {
			if($request_count > 2) {
				break;
			}
			$request_count++;
			$result = json_decode(trim($result_str, "\xEF\xBB\xBF"), true);
			$recode = $result['code'] . '';

			$helper->writeLogs(
			C('DEDE_DATA_PATH'),
			'requestGame_' . date('YmdH'),
			$result_str
			);

			if(intval($recode) == 3 || intval($recode) == 3006) {
				$success = true;
				break;
			}
		}*/
		//请求回调时只请求1次
		$result_str = httpRequest($api, $query_data);
		$result = json_decode(trim($result_str), true);
		$recode = $result['code'] . '';
		if(intval($recode) == 3 || intval($recode) == 3006) {
			$success = true;
			$benefit = $this->config['key'][$data['gameAlias']]['benefit'];
			if (!empty($benefit)) {
				//发放即时返利
				$mGameSDKBenefits = getInstance('@main.model.libs.mGameSDKBenefits');
				$mGameSDKBenefits->immediate($data, $benefit);
			}
		}
		if(!$success) {
			$error_types = array(
			'3' => '成功',
			'3001' => '其它错误',
			'3002' => '其它参数不合法',
			'3003' => '签名不匹配',
			'3004' => '无法找到用户',
			'3005' => '无法找到订单',
			'3006' => '订单重复提交',
			'3007' => '订单金额不匹配',
			);
			$error = $error_types[$recode] ? $result['code'] . '：' . $result['message'] : $result_str;

			//记录错误
			if ($data['orderId']) {
				$this->recordFailOrder($data['orderId'], $error);
			}

			$helper->writeLogs(
			C('DEDE_DATA_PATH'),
			'requestGame_error',
			json_encode($query_data) . $api . "\n\n" . $result_str
			);
		}else {
			$helper->writeLogs(
			C('DEDE_DATA_PATH'),
			'requestGame_success',
			json_encode($query_data) . $api . "\n\n" . $result_str
			);
		}

		return $success;
	}
}