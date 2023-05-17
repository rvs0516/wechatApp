<?php
require_once APP_CONTROLLER_PATH . '/master.php';

class batchController extends masterControl {
	
	/**
	 * 批量处理用户关联(角色转端)
	 * 
	 * 说明：实现同专服游戏的不同渠道账号的角色信息互转
	 * 
	 */
	public function batchRelation() {
		$operation_list = array('index', 'edit');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $_REQUEST['operation']);
		$uid_list = array('baohuan','chenjh','luojunri','yfdata','heyongzhen','yangzhenwei', 'jianjianxiang');
		$checkRoot = in_array($this->_uid, $uid_list) ? 1 : '';
		$this->assign('checkRoot', $checkRoot);
		$user = false;
		if (!empty($_REQUEST['useNameStr'])) {
			$user = true;

		}
		$batchModel = getInstance('model.batch');
		switch ($operation) {
			case 'index':
				// 查询
				if ($user) {
					// 匹配账号
					$strPattern = "/[a-z]{2,4}[0-9]{6}/";
				    $arrMatches = [];
				    preg_match_all($strPattern, $_REQUEST['useNameStr'], $arrMatches);

					if (count($arrMatches[0]) > 50) {
						ShowMsg('单次处理条数不能超过50条', '-1');
						exit;
					}

					// 支持同个玩家的多个游戏同时转端
					$memberListArray = array();
					foreach ($arrMatches[0] as $key => $value) {
						$useNameString = "'". $value. "'";
						$memberListArray[] = $batchModel->getBatchesMemberList($useNameString);
					}

					$memberList = array();
					foreach ($memberListArray as $key => $value) {
						$memberList[] = $value[0];
					}
					
					foreach ($memberList as $key => $value) {
						$isset = 0;
						$array = '';

						// 查看用户是否已经有转端记录了
						$data = $batchModel->getAssData($value['userName']);

						if ($data) {
							$isset = 1;
							$memberList[$key]['assUserName'] = $data['assUserName'];
						}

						// 查找玩家关联游戏
						$assGameSql = "SELECT assGame FROM ms_member_info WHERE userName = '{$value['userName']}'";
						$assGameRes = model::getBySql($assGameSql);
						$memberList[$key]['assGame'] = $assGameRes[0]['assGame'];

						$userData = array(
							'userName' => $value['userName'], 
							'platformUserId' => $value['platformUserId'], // 渠道平台用户唯一标识
							'channelId' => $value['channelId'], 
							'gameAlias' => $memberList[$key]['gameAlias'], 
							'assUserName' => $memberList[$key]['assUserName'] ? $memberList[$key]['assUserName'] : '', 
							'isset' => $isset, // isset用于判断账号是否已关联过其他游戏和账号
							);
						$memberList[$key]['data'] = base64_encode(json_encode($userData));//将数据base64加密，避免传递过程被转义
						$memberList[$key]['platformUserId'] =$value['platformUserId'];
					}

					$this->assign('memberList', $memberList);
				}
				break;
			
			case 'edit':
				// 关联
				if (empty($_REQUEST['batchesData'])) {
					ShowMsg('请勾选需要修改的账号', '-1');exit;
				}
				$array = explode(',', $_REQUEST['batchesData']);

				$count = count($array);
				if ($count > 50) {
					ShowMsg('暂不支持多账号关联', '-1');exit;
				}
				
				foreach ($array as $key => $value) {
					$array[$key] = json_decode(base64_decode($value), true);
				}

				// 整合关联的账号到同个数组
				$mergeArray = array();
				for ($i=0; $i < $count; $i = $i + 2) { 
					$mergeArray[$i][] = $array[$i];
					$mergeArray[$i][] = $array[$i + 1];
				}
				$relevanceUserArray = array_values($mergeArray);

				$mInfoModel = new model('ms_member_info');
				foreach ($relevanceUserArray as $key => $value) {
					
					// 原账号
					if ($value[0]['isset'] == 1) {
						// 之前已经有关联过其他游戏和账号
						// 添加新关联游戏和账号到原有数据上，需要用逗号隔开，账号和游戏要一一对应
						$value[0]['assUserName'] = $value[0]['assUserName'] ? $value[0]['assUserName']. ",". $value[1]['userName'] : $value[1]['userName'];

						$mInfoModel->set(
							array(
								'assUserName' => $value[0]['assUserName'],
							), 
							"userName='{$value[0]['userName']}'"
						);
						$this->dkwBatchReportRelation($value[0]);

						$this->yaowanBatchReportRelation($value[0]);

						// 记录转端操作日志
						error_log("\n". date("[Y-m-d H:i:s]"). "\n". "action: ". trim($_REQUEST['a']). "\n".  "uid: ". $this->_uid. "\n". "username: ". $value[0]['userName']. "\n". "assusername: ". $value[0]['assUserName'].  "\n\n", 3, C('DEDE_DATA_PATH')."/logs/turnLogs.txt");

					} else {
						$data['userName'] = $value[0]['userName'];
						$data['platformUserId'] = $value[0]['platformUserId'];
						$data['channelId'] = $value[0]['channelId'];
						$data['assUserName'] = $value[1]['userName'];
						// $data['assGame'] = $value[1]['gameAlias'];
						$data['loginTime'] = time(); 
						$data['type'] = 0; 
						$data['uid'] = $this->_uid;
						
						$mInfoModel->set($data);
						$this->dkwBatchReportRelation($data);

						$this->yaowanBatchReportRelation($data);

						// 记录转端操作日志
						error_log("\n". date("[Y-m-d H:i:s]"). "\n". "action: ". trim($_REQUEST['a']). "\n".  "uid: ". $this->_uid. "\n". "username: ". $value[0]['userName']. "\n". "assusername: ". $value[1]['userName'].  "\n\n", 3, C('DEDE_DATA_PATH')."/logs/turnLogs.txt");
					}
					
					// 新账号
					if ($value[1]['isset'] == 1) {
						// 之前已经有关联过其他游戏和账号
						// 添加新关联游戏和账号到原有数据上，需要用逗号隔开，账号和游戏要一一对应
						$value[1]['assUserName'] = $value[1]['assUserName'] ? $value[1]['assUserName']. ",". $value[0]['userName'] : $value[0]['userName'];

						$mInfoModel->set(
							array(
								'assUserName' => $value[1]['assUserName'],
							), 
							"userName='{$value[1]['userName']}'"
						);
						$this->dkwBatchReportRelation($value[1]);

						$this->yaowanBatchReportRelation($value[1]);

						// 记录转端操作日志
						error_log("\n". date("[Y-m-d H:i:s]"). "\n". "action: ". trim($_REQUEST['a']). "\n".  "uid: ". $this->_uid. "\n". "username: ". $value[1]['userName']. "\n". "assusername: ". $value[1]['assUserName'].  "\n\n", 3, C('DEDE_DATA_PATH')."/logs/turnLogs.txt");

					} else {
						$data['userName'] = $value[1]['userName'];
						$data['platformUserId'] = $value[1]['platformUserId'];
						$data['channelId'] = $value[1]['channelId'];
						$data['assUserName'] = $value[0]['userName'];
						$data['loginTime'] = time(); 
						$data['type'] = 0; 
						$data['uid'] = $this->_uid;

						$mInfoModel->set($data);
						$this->dkwBatchReportRelation($data);

						$this->yaowanBatchReportRelation($data);
						
						// 记录转端操作日志
						error_log("\n". date("[Y-m-d H:i:s]"). "\n". "action: ". trim($_REQUEST['a']). "\n".  "uid: ". $this->_uid. "\n". "username: ". $value[1]['userName']. "\n". "assusername: ". $value[0]['userName'].  "\n\n", 3, C('DEDE_DATA_PATH')."/logs/turnLogs.txt");

					}

				}
				break;
		}
		$this->assign('user', $user);
	}

	// 上报账号关联后的转端标识至达咖玩
	public function dkwBatchReportRelation($data) {
		if ($data['channelId'] == '500028') {
			if($data['assUserName']){
				$isRelations = 1;
			}else{
				$isRelations = 0;
			}
			$memberSub = $data['platformUserId'];
			//$this->dkwReportRelation($memberSub, $isRelations);

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
			error_log("\n". date("[Y-m-d H:i:s]"). "\n". "url: ". $url. "\n".  "body: ". json_encode($body). "\n". "sign_str: ". $sign_str. "\n". "res: ". $res. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/dkwBatchReportRelation_". date("ymd"). ".txt");
		}
	}

	// 上报账号关联后的转端标识至耀玩
	public function yaowanBatchReportRelation($data) {
		if ($data['channelId'] == '500071') {
			if($data['assUserName']){
				$isRelations = 1;
			}else{
				$isRelations = 0;
			}
			$memberSub = $data['platformUserId'];

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
			error_log("\n". date("[Y-m-d H:i:s]"). "\n". "url: ". $url. "\n".  "body: ". json_encode($body). "\n". "sign_str: ". $sign_str. "\n". "res: ". $res. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/yaowanBatchReportRelation_". date("ymd"). ".txt");
		}
	}

}