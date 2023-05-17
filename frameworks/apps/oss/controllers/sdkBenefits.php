<?php
//error_reporting(E_ALL);

require_once APP_CONTROLLER_PATH . '/master.php';

class sdkBenefitsController extends masterControl {

	/**
	 * 游戏分类管理
	 */
	public function userList() {
		$operation_list = array('index', 'add', 'manual');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';

		$statistics_model = getInstance('model.statistics');
		$benefits_model = getInstance('model.sdkBenefits.benefits');
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

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

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$game_model = getInstance('model.sdkGame.game');
		$game = $game_model->getList();
		$this->assign('game', $game);
		$this->assign('operation', $operation);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$length = 20;
		$offset = ($page - 1) * $length;

		$gameAlias = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$start = trim($_REQUEST['start_date']) ? strtotime(trim($_REQUEST['start_date'])) : "";
		$end = trim($_REQUEST['end_date']) ? strtotime(trim($_REQUEST['end_date'])."23:59:59") : "";
		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$roleId = trim($_REQUEST['roleId']) ? trim($_REQUEST['roleId']) : "";
		$serverId = trim($_REQUEST['serverId']) ? trim($_REQUEST['serverId']) : "";
		$status = trim($_REQUEST['status']) ? trim($_REQUEST['status']) : "";
		$type = trim($_REQUEST['type']) ? trim($_REQUEST['type']) : "";
		$year = trim($_REQUEST['year']) ? trim($_REQUEST['year']) : date('Y');
		switch($operation) {
			case 'index':
			$benefits = $benefits_model->getBenefitsList();
			$list = $benefits_model->getUserList($offset, $length, $year, $upperName, $specialName, $gameAlias, $start, $end, $userName, $roleId, $serverId, $status, $type, $gid, $gameStr); 
			$total = $benefits_model->getUserTotal($year, $upperName, $specialName, $gameAlias, $start, $end, $userName, $roleId, $serverId, $status, $type, $gid, $gameStr); 
			foreach ($list as $key => $value) {
				foreach ($benefits as $k => $v) {
					if ($value['benefitId'] == $v['id']) {
						$list[$key]['title'] = $v['title'];
					}
				}
			}
			
			$this->assign('list', $list);
			$this->assign('total', $total);
			$this->assign('page', $page);
			$this->assign('length', $length);
			$this->assign('gameAlias', $gameAlias);
			$this->assign('year', $year);
			$this->assign('upperName', $upperName);
			$this->assign('roleId', $roleId);
			$this->assign('serverId', $serverId);
			$this->assign('status', $status);
			$this->assign('type', $type);
			$this->assign('start_date', $_REQUEST['start_date']);
			$this->assign('end_date', $_REQUEST['end_date']);
			$this->assign('userName', $userName);
			break;

			case 'add':
				break;

			case 'manual'://手动发放返利
				/*if (empty($_REQUEST['start'])) {
					ShowMsg('开始时间不能为空', -1);exit;
				}
				$data = array(
					'title' => $_REQUEST['title'], 
					'gameAlias' => $_REQUEST['game'], 
					'channelId' => implode(',', $_REQUEST['channels']), 
					'start' => strtotime($_REQUEST['start']), 
					'end' => !empty($_REQUEST['end']) ? strtotime($_REQUEST['end'] . '23:59:59') : '', 
					'benefits' => $_REQUEST['benefits'], 
					);*/
			//var_dump($_REQUEST);exit;
			if (empty($_REQUEST['userName']) || empty($_REQUEST['game']) || empty($_REQUEST['roleId']) || empty($_REQUEST['roleName']) || empty($_REQUEST['serverId']) || empty($_REQUEST['prop'])) {
				ShowMsg('参数不能为空', -1);exit;
			}
			$memModel = new Model('ms_member');
			$member = $memModel->get('userName=%s', array($_REQUEST['userName']));
			if (!$member) {
				ShowMsg('账号不存在', -1);exit;
			}
			$benefitOid = "S".get_order_sn();
			$data = array(
				'benefitOid' => $benefitOid,
				'userName' => $_REQUEST['userName'], 
				'gameAlias' => $_REQUEST['game'], 
				'roleId' => $_REQUEST['roleId'], 
				'roleName' => $_REQUEST['roleName'], 
				'serverId' => $_REQUEST['serverId'], 
				//'channelId' => $_REQUEST['channelId'], 
				//'day' => date('Y-m-d'), 
				'prop' => $_REQUEST['prop'], 
				'grantData' => $_REQUEST['grantData'], 
				'grantType' => 2, 
				'operator' => $this->_uid,  
				'time' => time(),  
				);
			$db = 'ms_benefits_recharge_'.date('Y');
			$recharge = new Model($db);
			$saveId = $recharge->set($data); 
			if (!empty($saveId)) {
				$data['id'] = $saveId;
			}
			//var_dump($save);exit;
			load('@oss.model.sdkBenefits.api.' . $_REQUEST['api']);
			$benefitsClass = new $_REQUEST['api']();
			$data['gold'] = $_REQUEST['grantData'];
			$result = $benefitsClass->grantBenefits($data);
			if($result == true){
				$recharge->set(array(
					'status' => 1,
					), '`benefitOid`=%s', array($benefitOid));
			}
			ShowMsg('操作成功', '?m=sdkBenefits&a=userList');exit;
			break;
		}
	}

	/**
	 * 返利管理
	 */
	public function benefits() {
		$operation_list = array('index', 'add', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';

		$statistics_model = getInstance('model.statistics');
		$benefits_model = getInstance('model.sdkBenefits.benefits');
		
		$upperName = trim($_REQUEST['upperName']) ? trim($_REQUEST['upperName']) : "";
		$this->assign('upperName', $upperName);
		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);

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
		}
		//获取上级游戏名
		if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 15 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
			$UpperList = $statistics_model->getUpperListGs($gameStr);
		}else{
			$UpperList = $statistics_model->getUpperList();
		}
		$this->assign('UpperList', $UpperList);

		//获取专服游戏名
		$specialList = $statistics_model->getSpecialList($upperName);
		$this->assign('specialList', $specialList);
		$specialName = trim($_REQUEST['specialName']) ? trim($_REQUEST['specialName']) : "";
		$this->assign('specialName', $specialName);

		$game_model = getInstance('model.sdkGame.game');
		$game = $game_model->getList();
		$this->assign('game', $game);
		$this->assign('operation', $operation);
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;

				$gameAlias = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
				$start_date = trim($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : "";
				$end_date = trim($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date'] . '23:59:59') : "";

				$list = $benefits_model->getBenefitsList($offset, $length, $upperName, $specialName, $gameAlias,  $start_date, $end_date, $gid, $gameStr); 
				$total = $benefits_model->getBenefitsTotal($upperName, $specialName, $gameAlias,  $start_date, $end_date, $gid, $gameStr); 
				foreach ($list as $key => $value) {
					$channelList = '';
					$str = '';
					if (empty($value['channelId'])) {
						$list[$key]['channelName'] = '全渠道';
					}else{
						$channelList = explode(',', $value['channelId']);
						foreach ($channelList as $k => $v) {
							$list[$key]['channelName'] .= $channels[$v].",";
						}
					}
					$list[$key]['startDate'] = date('Y-m-d', $value['start']);
					$list[$key]['endDate'] = date('Y-m-d', $value['end']);//!empty($value['end']) ? date('Y-m-d H:i:s', $value['end']) : '长期';
					if ($value['benefits']) {
						$json = json_decode($value['benefits'], true);
						//var_dump($json);
						foreach ($json as $k => $v) {
							if ($v[2] == 'gold') {
								$bType = ' 游戏币 : ';
							}elseif ($v[2] == 'percent') {
								$bType = ' 返比 : ';
							}elseif ($v[2] == 'prop') {
								$bType = ' 道具ID : ';
							}
							$str .= '累充 ' . $v[0] . ' - ' . $v[1] . $bType . $v[3].";".PHP_EOL;
						}
						$list[$key]['benefitsName'] = $str;
					}
				}

				$this->assign('list', $list);
				$this->assign('total', $total);
				$this->assign('page', $page);
				$this->assign('length', $length);
				$this->assign('gameAlias', $gameAlias);
				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);

				break;

			case 'add':
				break;

			case 'save':
			
				if (empty($_REQUEST['start']) || empty($_REQUEST['end'])) {
					ShowMsg('时间范围不能为空', -1);exit;
				}
				if (empty($_REQUEST['game'])) {
					ShowMsg('游戏不能为空', -1);exit;
				}
				$result = $this->getUploadFile($_REQUEST['game'].'-'.$_REQUEST['api'].'-', 100, 'D');
				if (count($result[1]) != 4) {
					ShowMsg('表格格式不对', -1);exit;
				}
				$data = array(
					'title' => trim($_REQUEST['title']), 
					'gameAlias' => trim($_REQUEST['game']), 
					'channelId' => implode(',', $_REQUEST['channels']), 
					'start' => strtotime($_REQUEST['start']), 
					'end' => !empty($_REQUEST['end']) ? strtotime($_REQUEST['end'] . '23:59:59') : '', 
					'benefits' => json_encode($result), 
					'api' => trim($_REQUEST['api']), 
					'rate' => trim($_REQUEST['rate']),
					);
				
				$benefits_model->add($data); 
				ShowMsg('操作成功', '?m=sdkBenefits&a=benefits');exit;
				break;

			case 'del':
				$id = $_GET['id'];
				$benefits_model->del($id);
				ShowMsg('操作成功', '?m=sdkBenefits&a=benefits');exit;
				break;
		}
	}

	public function getUploadFile($target, $line=1000, $format) {
		load('uploadfile');
		$path = C('DEDE_DATA_PATH') . "benefitExcel/" . date('Ymd') . "/";
		@mkdir($path);
		$filetypes = array('xlsx', 'xls');
		$files = $_FILES['file'];
		//var_dump($files);exit;
		$upload = new uploadfile($files, $path, 999999999999, $filetypes);
		$file_name = $target.time();
		$success = !!$upload->upload($file_name);
		
		$suffix = pathinfo($files['name'][0]);

		$dir = $path . $file_name .'.'.$suffix['extension'];
		if(!$success) {
			ShowMsg("上传失败", -1);
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
		//var_dump($column);var_dump($format);exit;
		if ($row > $line) {
			ShowMsg("添加条数已超过上限", -1);exit;
		}
		if (empty($format)) {
			ShowMsg("未设置表格格式", -1);exit;
		}

		if (strtoupper($column) != strtoupper($format)) {
			ShowMsg("添加的表格格式不正确", -1);exit;
		}
		return $excelData;
	}
}