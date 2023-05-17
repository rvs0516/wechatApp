<?php
//error_reporting(E_ALL);
require_once APP_CONTROLLER_PATH . '/master.php';

class vipGuestController extends masterControl {

	/**
	 * vip列表
	 */
	public function vipList()
	{
		
		$vipGuest_model =  getInstance('model.vipGuest');
		$this->checkLogin();
		$statistics_model = getInstance('model.statistics');

		//角色组
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid',$gid);

		// 回访
		if (isset($this->_REQUEST ['RetVisit'])) {
			$qq = !empty($this->_REQUEST ['form']['qq']) ? $this->_REQUEST ['form']['qq'] : '';
			$wx = !empty($this->_REQUEST ['form']['wx']) ? $this->_REQUEST ['form']['wx'] : '';
			$phone = !empty($this->_REQUEST ['form']['phone']) ? $this->_REQUEST ['form']['phone'] : '';
			$wid = !empty($this->_REQUEST ['form']['wid']) ? $this->_REQUEST ['form']['wid'] : '';
			$uid = !empty($this->_REQUEST ['form']['uid']) ? $this->_REQUEST ['form']['uid'] : '';
			$userName = $vipGuest_model->getRetVisitUser($uid);
			if (empty($uid)) {
				$this->showMsg('用户不存在,请核对重试', - 1);
				exit();
			}
			//回访周期
			$revisitTime = $vipGuest_model->getRetVisitTime($uid,2);

			//回访前查看审核状态,未审核状态不能进行回访
			$revisitStatus = $vipGuest_model->getRetVisitStatus($uid);
			if ($revisitStatus['status'] == 0) {
				$this->showMsg('管理未审核，请等待审核通过后重试', - 1);
				exit();
			}

			//vip状态审核不通过
			if($revisitStatus['status'] == -1 && $revisitStatus['examineRemark'] == 1) {
				$this->showMsg('该账号未通过审核，不允许进行回访操作', - 1);
				exit();
			}
			
			//回访周期没过
			$revisitTime = strtotime("+7 day",$revisitTime['revisitTime']);
			if ($revisitTime >= time() ) {
				$this->showMsg('回访周期过短,建议7天一次', - 1);
				exit();
			}

			//ck1缺少用户信息参数
			if (empty($qq) || empty($wx) || empty($phone) || empty($wid)) {
				$this->showMsg('缺少回访人员信息,请核对重试', - 1);
				exit();
			}

			/**
			 * 图片类型
			 */
			$img_type = array(
				0 => 'image/jpeg',
				1 => 'image/png' ,
				2 => 'image/pjpeg' ,
				3 => 'image/gif'
			);

			//图片格式判断
			if ($_FILES["file"]["size"] < 1048576 )
			{
				if (in_array($_FILES["file"]["type"],$img_type)) {
					if ($_FILES["file"]["error"] > 0)
					{
						$this->showMsg('上传出现问题,请重试', - 1);
						exit();
					}else{

						$file_name = date("YmdHis",time()).'_'.$userName.'.png';
						if (file_exists(C('DEDE_DATA_PATH').'vipGuest/'.$file_name))
						{
							$this->showMsg('图片出现重复,请重试', - 1);
						}
						else
						{
							move_uploaded_file($_FILES["file"]["tmp_name"],C('DEDE_DATA_PATH').'vipGuest/'.$file_name);
						}
					}
				}else{
					$this->showMsg('图片格式不对,请核对重试', - 1);
					exit();
				}
			}else{
				$this->showMsg('图片大小超过1M,请核对重试', - 1);
				exit();
			}

			//删除原来图片
			$returnImg = C('DEDE_DATA_PATH').'vipGuest/';
			$returnImg .= $vipGuest_model->getRetVisitImg($uid);
			unlink($returnImg);

			$update['wid'] = $wid;
			$update['qq'] = $qq;
			$update['weixin'] = $wx;
			$update['phoneNum'] = $phone;
			$update['returnImg'] = $file_name;
			$update['revisitTime'] = time();
			$update['status'] = 0;
			
			$revisitStatus = $vipGuest_model->getRetVisit($uid, $update);
			$this->showMsg('操作成功', -1);
			exit();
		}

		//批量审核
		if($this->_REQUEST ['format'] == 'examine'){
			if (!$this->_REQUEST['id']) {
				$res = array(
					'code' => 0,
					'msg' => 'id为空'
				);
			}else{
				$list = explode(',', $this->_REQUEST['id']);

				foreach ($list as $key => $value) {
					$data['status'] = 1;
					$data['examineTime'] = time();
					$vipGuest_model->getExamine($data,$value);
				}
				$res = array(
					'code' => 1,
					'msg' => '成功'
				);
			}
			echo json_encode($res);
			exit;
		}

		//审核
		if (isset($this->_REQUEST ['Examine'])) {
			
			
			$uid = !empty($this->_REQUEST ['form']['uid']) ? $this->_REQUEST['form']['uid'] : '';
			$status = !empty($this->_REQUEST ['form']['status']) ? $this->_REQUEST['form']['status'] : '';
			$examineRemark = !empty($this->_REQUEST ['form']['examineRemark']) ? $this->_REQUEST['form']['examineRemark'] : '';
			if (empty($uid)) {
				$this->showMsg('用户不存在,请核对重试', -1);
				exit();
			}
			//审核状态
			$userStatus = $vipGuest_model->getRetVisitUserStatus($uid);
			if ($userStatus != 0 ) {
				$this->showMsg('无需二次审核', -1);
				exit();
			}

			$data['status'] = $status;
			$data['examineRemark'] = $examineRemark;
			$data['examineTime'] = time();
			$vipGuest_model->getExamine($data,$uid);
			$this->showMsg('操作成功', -1);
			exit();
		}

		$gameStr = '';
		$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		$this->assign('game', $game);

		//区分不同角色组权限
		$agentChannel = '';

		// if($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 17 || $gid == 22){
		// 	if ($gidarr[0]['game'] != 'all') {
		// 		$explode = explode('|', $gidarr[0]['game']);
		// 		foreach ($explode as $k => $v) {
		// 			$gameStr .= "'" . $v . "',";
		// 		}
		// 		$gameStr = substr($gameStr,0,-1);
		// 		$this->assign('gameStr', $gameStr);
		// 	}
		// 	$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		// }else {
		// 	$game = trim($_REQUEST['game']) ? trim($_REQUEST['game']) : "";
		// }

		// //获取上级游戏名
		// if ( ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 15 || $gid == 22 || ($gid == 17 && !empty($gameStr)) ) && ( $gidarr[0]['game'] != 'all') ){
		// 	$UpperList = $statistics_model->getUpperListGs($gameStr);
		// }else{
		// 	$UpperList = $statistics_model->getUpperList();
		// }
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

		//全部专员账号
		$majorpersonList = $res = model::getBySql('select uid from role where gid = 22');
		$this->assign('majorpersonList', $majorpersonList);

		//排序
		$sort = !empty($this->_REQUEST ['sort']) ? $this->_REQUEST ['sort'] : '';
		$this->assign('sort',$sort);

		// 审核状态
		$examine = trim($_REQUEST['examine']) ? trim($_REQUEST['examine']) : "";
		$this->assign('examine',$examine);

		//搜索账号
		$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
		$this->assign('userName',$userName);

		//专员账号
		$majorperson = trim($_REQUEST['majorperson']) ? trim($_REQUEST['majorperson']) : "";
		$this->assign('majorperson',$majorperson);
		
		//最后登录时间
		$start_date = trim($_REQUEST['start_date']) ;
		$end_date = trim($_REQUEST['end_date']);
		$this->assign('start_date',$start_date);
		$this->assign('end_date',$end_date);

		if ($start_date || $end_date) {
			$start_date = strtotime($_REQUEST['start_date'].' 00:00:00');
			$end_date = strtotime($_REQUEST['end_date'].' 23:59:59') ;
		}
		
		//考虑服务器性能损耗，一次导出最多导出20000条
		if($_POST['operation'] === 'report') {
			$page = 1;
			$row = 20000;
			$offset = 0;
		}else {
			//查询数据
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$row = 15;
			$offset = ($page - 1) * $row;
		}

		$this->assign('list_page', $page);
		$this->assign('list_length', $row);

		//vip来源
		$source = $_REQUEST['source'] ? $_REQUEST['source'] : "";
		$this->assign('source',$source);

		//vip用户列表数据
		$vipGuestList = $vipGuest_model->getVipGuestList($sort, $examine, $start_date, $end_date, $game, $offset, $row, $userName, $source, $sumString, $specialString, $gameStr, $gid, $majorperson);

		//vip用户列表页数
		$vipGuestListTotal = $vipGuest_model->getVipGuestListTotal($examine, $start_date, $end_date, $game, $userName, $source, $sumString, $specialString, $gameStr, $gid, $majorperson);

		
		foreach ($vipGuestList as $key => $value) {
			
			// 停充天数
			$vipGuestList[$key]['end_recharge_day'] = $vipGuestList[$key]['lastPayTime'] ? (int)((time() - $vipGuestList[$key]['lastPayTime']) / 86400) : -1 ;

			//停登天数
			$vipGuestList[$key]['end_login_day'] = $vipGuestList[$key]['loginTime'] ? (int)((time() - $vipGuestList[$key]['loginTime']) / 86400) : -1;

			//回访周期
			$revisitTime = $vipGuest_model->getRetVisitTime($vipGuestList[$key]['userName'],1);
			$vipGuestList[$key]['revisitStatus'] = strtotime("+7 day",$revisitTime['revisitTime']);
			if ( ( $vipGuestList[$key]['status'] == -1 && $vipGuestList[$key]['revisitTime'] != '' ) || ($vipGuestList[$key]['status'] == 1 && $vipGuestList[$key]['revisitStatus'] < time())) {
				$vipGuestList[$key]['revisitStatus'] = 1;
			}else{
				$vipGuestList[$key]['revisitStatus'] = 0;
			}

			//专员只能看自己操作的vip联系方式和聊天截图信息
			if (($gid == 22 && $value['uid'] != $this->_uid)) {
				$vipGuestList[$key]['returnImg'] = '';
				$vipGuestList[$key]['phoneNum'] = '';
				$vipGuestList[$key]['weixin'] = '';
				$vipGuestList[$key]['qq'] = '';
				$vipGuestList[$key]['birthday'] = '';
			}
		}

		if ($_POST['operation'] === 'report') {
			foreach ($vipGuestList as $key => $value) {

				$data[$key]['userName'] = $value['userName'];
				$data[$key]['channelName'] = $value['channelName'];
				$data[$key]['sumMoney'] = $value['sumMoney'];
				$data[$key]['firstCharge'] = $value['firstCharge'];
				$data[$key]['lastGameName'] = $value['lastGameName'];
				$data[$key]['joinTime'] = $value['joinTime'] ? date('Y-m-d H:i',$value['joinTime']) : '';
				$data[$key]['end_recharge_day'] = $value['end_recharge_day'] != -1 ? $value['end_recharge_day'].'天' : '';
				$data[$key]['lastPayTime'] = $value['lastPayTime'] ? date('Y-m-d H:i',$value['lastPayTime']) : '';
				$data[$key]['end_login_day'] = $value['end_login_day'] != -1 ? $value['end_login_day'].'天' : '';
				$data[$key]['loginTime'] = $value['loginTime'] ? date('Y-m-d H:i',$value['loginTime']) : '';
				$data[$key]['relationStatus'] = $value['relationStatus'] ? '是' : '否';
				$data[$key]['relationLoginTime'] = $value['relationLoginTime'] ? date('Y-m-d H:i',$value['relationLoginTime']) : '';
				$data[$key]['relationUserName'] = $value['relationUserName'];
				if($value['status'] == 1){
					$data[$key]['status'] = '审核通过';
				}elseif ($value['status'] == -1) {
					$status_msg = $value['examineRemark'] == 1 ? "不是vip用户" : "回访信息不合格";
					$data[$key]['status'] = '审核未通过(' .$status_msg. ')';
				}else{
					$data[$key]['status'] = '待审核';
				}
				$data[$key]['qq'] = $value['qq'];
				$data[$key]['phoneNum'] = $value['phoneNum'];
				$data[$key]['weixin'] = $value['weixin'];

				$data[$key]['revisitTime'] = $value['revisitTime'] ? date('Y-m-d H:i',$value['revisitTime']) : '';
			}
			excel_export("VIP用户列表_".date("Y-m-d H:i:s"), array(
			'账号', '渠道','累充','首充','最后登录游戏','注册时间','停充天数','最后付费时间','停登天数','最后登录时间','是否曾关联','关联账号最后登录时间','最后登录关联账号','审核状态','QQ','联系电话','WX','上次回访时间'), $data);
			exit;
		}

		$this->assign('vipGuestList',$vipGuestList);
		$this->assign('list_total',$vipGuestListTotal);
		$this->assign('route',C('STATIC_SOURCE_SITE').'vipGuest/');
	}

	/**
     * vip添加
     */
    public function vipGuest(){
		$operation_list = array('index', 'add', 'edit', 'save', 'del', 'allow', 'revisit');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $operation);

		$guest_model = getInstance('model.vipGuest');
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
				$this->assign('list', $list);

				if ($gid == 21 || $gid == 22) {
					// $list = $guest_model->getVipInfo($_REQUEST['userName']);
					// if ($list) {
					// 	ShowMsg('该用户信息已记录', '/index.php?m=statistics&a=member');
					// }
					$this->assign('userName', $_REQUEST['userName']);
					$this->assign('gameName', $_REQUEST['gameName']);
					$this->assign('gameAlias', $_REQUEST['gameAlias']);
					$this->assign('loginTime', $_REQUEST['loginTime']);
					$this->assign('channelName', $_REQUEST['channelName']);
					$this->assign('channelId', $_REQUEST['channelId']);
					$this->assign('joinTime', $_REQUEST['joinTime']);
				}else{
					ShowMsg('暂无该权限', -1);
				}
				
			break;

			case 'edit':
				$list = $guest_model->getVipInfo($_REQUEST['userName']);
				$this->assign('list', $list);
				$this->assign('userName', $_REQUEST['userName']);
				$this->assign('gameName', $_REQUEST['gameName']);
				$this->assign('gameAlias', $_REQUEST['gameAlias']);
			break;

			case 'save':
				if (!$_POST['userName']) {
					$this->showMsg('用户不存在,请核对重试', - 1);
					exit();
				}
				/**
				 * 图片类型
				*/
				$img_type = array(
					0 => 'image/jpeg',
					1 => 'image/png' ,
					2 => 'image/pjpeg' ,
					3 => 'image/gif'
				);

				//图片格式判断
				if ($_FILES["file"]["size"] < 1048576 )
				{
					if (in_array($_FILES["file"]["type"],$img_type)) {
						if ($_FILES["file"]["error"] > 0)
						{
							$this->showMsg('上传出现问题,请重试', - 1);
							exit();
						}else{

							$file_name = date("YmdHis",time()).'_'.$_POST['userName'].'.png';
							if (file_exists(C('DEDE_DATA_PATH').'vipGuest/'.$file_name))
							{
								$this->showMsg('图片出现重复,请重试', - 1);
								exit();
							}
							else
							{
								move_uploaded_file($_FILES["file"]["tmp_name"],C('DEDE_DATA_PATH').'vipGuest/'.$file_name);
							}
						}
					}else{
						$this->showMsg('图片格式不对,请核对重试', - 1);
						exit();
					}
				}else{
					$this->showMsg('图片大小超过1M,请核对重试', - 1);
					exit();
				}
				$game = $guest_model->getRetVisitUserGame($_POST['userName']);

				$data = array(
				'userName' => trim($_POST['userName']),
				'gameAlias' => trim($_POST['gameAlias']),
				'gameName' => trim($_POST['gameName']),
				'phoneNum' => $_POST['phoneNum'],
				'weixin' => $_POST['weixin'],
				'qq' => $_POST['qq'],
				'birthday' => strtotime($_POST['birthday']),
				'remark' => $_POST['remark'],
				'returnImg' => $file_name,
				'lastGameAlias' => $game['gameAlias'],
				'lastGameName' => $game['gameName']
				);

				$data['uid'] = $this->_uid;
				$data['loginTime'] = $_POST['loginTime'];
				$data['channelName'] = $_POST['channelName'];
				$data['channelId'] = $_POST['channelId'];
				$data['joinTime'] = $_POST['joinTime'];
				$data['vipJoinTime'] = time();

				$list = $guest_model->getVipInfo($_REQUEST['userName']);
				if($list){
					if ($list['status'] == 2) {

						$data['status'] = 0;
						$guest_model->edit($list['id'], $data);
						ShowMsg('操作成功', '/index.php?m=statistics&a=member');

					}else{

						$data['status'] = $list['status'];
						$guest_model->edit($list['id'], $data);
						ShowMsg('操作成功', '/index.php?m=statistics&a=member');

					}
				}else{
					$data['status'] = 0;
					$data['vipSource'] = 1;
					$status = $guest_model->add($data);
					ShowMsg('操作成功', '/index.php?m=statistics&a=member');
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
	* 用户跟进
	*/
    public function maintain(){
	    $operation_list = array('index', 'add', 'save', 'del', 'maintain');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $operation);

		$guest_model = getInstance('model.vipGuest');

		$maintain = new Model('ms_member_maintain');
		switch($operation) {
			case 'index':
				$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
				$this->assign('list_page', $page);
				$length = 25;
				if ($_REQUEST['op'] == 'report') {
					$length = 5000;
				}
				$offset = ($page - 1) * $length;
				$this->assign('list_length', $length);

				$userName = trim($_REQUEST['userName']) ? trim($_REQUEST['userName']) : "";
				$this->assign('userName', $userName);

				$start_date = $_REQUEST['start_date'] ? strtotime($_REQUEST['start_date']) : "";
				$end_date = $_REQUEST['end_date'] ? strtotime($_REQUEST['end_date'] . '23:59:59') : "";

				$status = trim($_REQUEST['status']) ? trim($_REQUEST['status']) : "";
				$this->assign('status', $status);

				$editUid = trim($_REQUEST['editUid']) ? trim($_REQUEST['editUid']) : "";
				$this->assign('editUid', $editUid);

				$sort = trim($_REQUEST['sort']) ? trim($_REQUEST['sort']) : "";
				$this->assign('sort', $sort);

				$list = $guest_model->getMaintainList($offset, $length, $userName, $start_date, $end_date, $status, $editUid, $sort);
				
				if ($_REQUEST['op'] != 'report') {
					$total = $guest_model->getMaintainTotal($userName, $start_date, $end_date, $status, $editUid, $sort);
				}else{
					$reports = array();
				
					foreach ($list as $keyr => $valuer) {
						$reports[$keyr]['userName'] = $valuer['userName'];
						$reports[$keyr]['contactAddress'] = $valuer['contactAddress'];
						$reports[$keyr]['frequency'] = $valuer['frequency'];
						$reports[$keyr]['status'] = $valuer['status'] == 1 ? '已跟进' : '未跟进';
						$reports[$keyr]['time'] = date('Y-m-d H:i', $valuer['time']);
						$reports[$keyr]['uid'] = $valuer['uid'];
					}

					excel_export("《中央数据后台》用户跟进列表", array(
					'账号', '通知邮件', '通知次数', '状态', '操作时间', '操作uid'
					), $reports);
					exit;
				}

				$this->assign('list', $list);
				$this->assign('list_total', $total[0]['total']);
				$this->assign('start_date', $_REQUEST['start_date']);
				$this->assign('end_date', $_REQUEST['end_date']);
			break;

			case 'add':
				
			break;

			case 'save':

				$strPattern = "/[a-z]{2,4}[0-9]{6}/";
			    $arrMatches = [];
			    preg_match_all($strPattern, $_REQUEST['userNameStr'], $arrMatches);

				if (count($arrMatches[0]) > 50) {
					ShowMsg('单次处理条数不能超过50条', '-1');
					exit;
				}
				//默认邮箱
				if ($_REQUEST['contactType'][0] == 'email' && empty($_REQUEST['contactAddress'])) {
					$contactAddress = 'jianxiang.jian@jieyougame.com';
				}else{
					$contactAddress = $_REQUEST['contactAddress'];
				}
				$contactAddress .= '|jiang.luo@jieyougame.xyz';
				foreach ($arrMatches[0] as $key => $value) {
					$data = array(
					'userName' => $value,
					'handleType' => $_REQUEST['handleType'][0],
					'contactType' => $_REQUEST['contactType'][0],
					'contactAddress' => $contactAddress,
					'time' => time(),
					'uid' => $this->_uid
					);
					$res = $guest_model->set($data);
				}
				ShowMsg('操作成功', '/index.php?m=vipGuest&a=maintain');exit;

			break;

			case 'del':
				$maintain->delete("`id`='{$_REQUEST['id']}'");
				ShowMsg('操作成功', '/index.php?m=vipGuest&a=maintain');exit;
			break;

			case 'maintain':
				$data = array(
					'status' => 1,
					'time' => time(),
					'uid' => $this->_uid
					);
				$maintain->set($data, "`id`='{$_REQUEST['id']}'");
				ShowMsg('操作成功', '/index.php?m=vipGuest&a=maintain');exit;
			break;
		}
	}

}