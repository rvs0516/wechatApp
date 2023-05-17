<?php

/**
 * 后台权限
 *
 */

error_reporting(E_ERROR);

ini_set("display_errors", "Off");

require_once APP_CONTROLLER_PATH . '/master.php';

class privController extends masterControl {

	const OFFSET = 30;

	function __construct() {
		parent::__construct();
		$this->loadTable('role_group');
		$this->loadTable('role');
	}

	function editpwd() {
		$this->checkLogin();
		
		$username = $this->_uid;
		$this->assign('username', $username);
		if (isset($this->_REQUEST ['do'])) {
			//$insertdata = $this->_REQUEST;
			$insertdata['password'] = md5($this->_config['cookie_prefix'] . $this->_REQUEST['newpwd']);
			$lastid = $this->_db->update('@role',$insertdata, 'uid=\'' . $this->_uid.'\'');
			cookie::set('oss_admin_user', '', time() - 3600000, $this->_config['cookie_path'], $this->_config['cookie_domain']);
			$this->showMsg($this->_L['editpwd_logout'], 'index.php?m=priv&a=login');
			exit();
		}
	}

	function addmodule() {
		$this->checkLogin();

		if (isset($this->_REQUEST ['do'])) {
			$insertdata = $this->_REQUEST ['form'];
			$insertdata ['ctime'] = date('Y-m-d H:i:s');
			$insertdata ['author'] = $this->_uid;
			$lastid = $this->_db->insert('@role_action', $insertdata);
			/*
			$this->_db->query("UPDATE @role_group SET `priv` = CONCAT(`priv`, ',', $lastid) WHERE `id` = 1 ");
			*/
			$this->showMsg($this->_L['op_success'], - 1);
			exit();
		}
		$modulelist = $this->_db->fetchAll('SELECT * FROM @role_module');
		
		$this->assign('modulelist', $modulelist);
		// $this->show ( 'admin/addmodule.html' );
	}

	function editmodule() {
		$this->checkLogin();
		$id = intval($this->_REQUEST ['id']);
		if (isset($this->_REQUEST ['do'])) {
			$insertdata = $this->_REQUEST ['form'];
			if (empty($insertdata['display'])) {
				$insertdata['display'] = 0;
			}
			if (empty($insertdata['isadmin'])) {
				$insertdata['isadmin'] = 0;
			}
			$insertdata ['ctime'] = date('Y-m-d H:i:s');
			$insertdata ['author'] = $this->_uid;
			$lastid = $this->_db->update('@role_action', $insertdata, 'id=' . $id);
			$this->showMsg($this->_L['op_success'], - 1);
			exit();
		}

		$roleaction = $this->_db->fetchFirst('SELECT * FROM @role_action WHERE id = ' . $id);
		
		$modulelist = $this->_db->fetchAll('SELECT * FROM @role_module');
		foreach ($modulelist as $key => $value) {
			if ($roleaction['module'] == $value['module']) {
				$value['current'] = 1;
				$modulelist[$key] = $value;
				break;
			}
		}
		$this->assign('roleaction', $roleaction);
		$this->assign('modulelist', $modulelist);
	}

	function listmodule() {
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}

		$where = ' WHERE rm.module=ra.module ';
		$where .= $_REQUEST['moduleMake'] ? " AND rm.module = '" . $_REQUEST["moduleMake"] ."'" : '';
		$where .= $_REQUEST['module'] ? " AND rm.module = '" . $_REQUEST["module"] ."'" : '';
		$where .= $_REQUEST['action'] ? " AND ra.action = '" . $_REQUEST['action'] ."'" : '';
		$where .= $_REQUEST['author'] ? " AND ra.author = '" . $_REQUEST['author'] ."'" : '';

		$where .= $_REQUEST['start_date'] ? " AND ra.ctime >= '" . $_REQUEST['start_date'] ."'" : '';
		$where .= $_REQUEST['end_date'] ? " AND ra.ctime < '" . date('Y-m-d',strtotime($_REQUEST['end_date']."+ 1 day"))."'" : '';

		$fields = ' COUNT(1) rowcount';
		$formatsql = 'SELECT %s
		FROM @role_module rm , @role_action ra '.$where;
		$sql = sprintf($formatsql, $fields);
		$row = $this->_db->fetchFirst($sql);
		$rowcount = $row['rowcount'];
		$fields = ' ra.id,rm.name mname,ra.name aname,ra.sort,ra.ctime,ra.author,ra.action,ra.isadmin,rm.module';
		$sql = sprintf($formatsql, $fields);
		$sql .= ' ORDER BY ra.ctime DESC LIMIT ' . $pagestart . ',' . $offset;
		$list = $this->_db->fetchAll($sql);
		$this->assign('list', $list);
		$this->assign('modulelist', $this->_db->fetchAll('SELECT * FROM @role_module'));
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
		$this->assign('module', $_REQUEST['module']);
		$this->assign('action', $_REQUEST['action']);
		$this->assign('author', $_REQUEST['author']);
		$this->assign('moduleMake', $_REQUEST['moduleMake']);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		
		//  $this->show ( 'admin/listmodule.html' );
	}

	function delaction() {
		$this->checkLogin();
		$id = intval($this->_REQUEST['id']);
		//($this->_db-> query('DELETE FROM @role_action WHERE id='.$id)) ? ShowMsg('鍒櫎鎴愬姛', -1): ShowMsg('鍒櫎澶辨晽', -1);
		$this->_db->query('DELETE FROM @role_action WHERE id=' . $id);
		$sql = 'UPDATE @role set priv=\'\' WHERE priv=\'' . $id . '\'';
		$this->_db->query($sql);
		$sql = 'UPDATE @role_group set priv=\'\' WHERE priv=\'' . $id . '\'';
		$this->_db->query($sql);
		$sql = "UPDATE @role a INNER JOIN @role b  ON a.uid=b.uid AND a.priv REGEXP ',$id|$id,' SET a.priv=REPLACE(REPLACE(a.priv,'$id,',''),',$id','')";
		$this->_db->query($sql);
		$sql = "UPDATE @role_group a INNER JOIN @role_group b  ON a.id=b.id AND a.priv REGEXP ',$id|$id,' SET a.priv=REPLACE(REPLACE(a.priv,'$id,',''),',$id','')";
		$this->_db->query($sql);
		$this->showMsg($this->_L['del_success'], -1);
	}

	function addrole() {
		$this->checkLogin();
		if (isset($this->_REQUEST['do'])) {
			
			$channels = implode(',', $this->_REQUEST['channels']);
			
			$uid = !empty($this->_REQUEST ['form']['uid']) ? $this->_REQUEST ['form']['uid'] : '';
			if (empty($uid)) {
				$this->showMsg($this->_L['uid_error'], - 1);
				exit;
			}
			$privs = join(',', $this->_REQUEST ['form'] ['aid']);
			$gid = intval($this->_REQUEST ['form']['gid']);

			$headerData = implode(',', $this->_REQUEST['headerData']);
			
			
			//所属游戏
			if ($gid == 8) {
				$game = $this->_REQUEST ['form']['game'];
			}elseif ($gid == 2 || $gid == 11 || $gid == 13 || $gid == 14 || $gid == 19 || $gid == 17 || $gid == 15 || $gid == 22) {
				if($this->_REQUEST ['form']['allGame'] == 'all'){
					$game = 'all';
				}else{
					$game = $this->_REQUEST ['form']['gameStr'];
				}
			}
			$mobile = $this->_REQUEST ['form']['mobile'];
			$mail = $this->_REQUEST ['form']['mail'];
			$realname = $this->_REQUEST ['form']['realname'];
			$payCharging = $this->_REQUEST ['form']['payCharging'];
			$limitTime = strtotime($this->_REQUEST ['form']['limitTime']);
			$adsChannel = $this->_REQUEST ['form']['adsChannel'];
			$adsAccount = $this->_REQUEST ['form']['adsAccount'];
			
			
			if (empty($gid)) {
				ShowMsg('请选择用户组', -1);
			}
			if (empty($realname)) {
				ShowMsg('真实姓名不能为空', -1);
			}
			$update ['uid'] = $uid;
			$update ['priv'] = $privs;
			$update ['gid'] = $gid;
			$update ['realname'] = $realname;
			$update ['game'] = $game;
			$update ['channelId'] = $channels;
			$update	['header_id'] = $headerData;
			if (!empty($this->_REQUEST ['form']['password'])) {
				$password = md5($this->_config['cookie_prefix'] . $this->_REQUEST ['form']['password']);
				$update ['password'] = $password;
			}
			$update ['mobile'] = $mobile;
			$update ['mail'] = $mail;
			$update ['payCharging'] = $payCharging;
			$update ['limitTime'] = $limitTime;
			$update ['author'] = $this->_uid;

			$update ['ads'] = $adsChannel.",".$adsAccount;
			
			if ($this->_db->fetchFirst('SELECT * FROM @role WHERE uid=\'' . $uid . '\'')) {
				$this->_db->update('@role', $update, ' uid=\'' . $uid . '\'');
				$this->showMsg($this->_L['op_finish'], - 1);
				exit;
			} else {
				if ($this->_db->insert('@role', $update)) {
					$this->showMsg($this->_L['op_finish'], - 1);
				} else {
					$this->showMsg($this->_L['op_failed'], - 1);
				}
				exit;
			}
		}

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

		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);

		$uid = !empty($this->_REQUEST ['uid']) ? $this->_REQUEST ['uid'] : '';
		$modules = $this->_db->fetchAll('
		SELECT ra.id,rm.name mname,ra.name aname,rm.sort,ra.action,ra.param,ra.isadmin,rm.module
		FROM @role_module rm , @role_action ra 
		WHERE rm.module=ra.module 
		AND ra.isadmin=1 
		AND ra.display=1');
		$grouplist = $this->_role->fetchAllRoleGroup();
		
		$rolerow = $this->_role->fetchRole($uid);

		//关联所负责游戏的全部游戏或部分游戏筛选
		if ($rolerow['game'] == 'all') {
			$this->assign('allGame','all');
		}else{
			$this->assign('allGame','part');
		}

		// 用户综合数据权限
		$header_model = getInstance('model.statistics');
		$data = $header_model->getHeader();
		$header_data = $header_model->getJurisdiction($uid);
		$player_data = array();
		$priv_data = array();

		foreach ($data as $key => $value) {
			$list = explode('_', $value['header_id']);
			$data[$key]['group'] = $list;
			if ($data[$key]['group'][0] == 300) {
				$priv_data[$key] = $data[$key];
			}else{
				$player_data[$key] = $data[$key];
			}
		}

		$openChannel = explode(',', $rolerow['channelId']);
		$this->assign('openChannel', $openChannel);
		if (isset($this->_REQUEST ['uid'])) {
			if (!empty($rolerow)) {
				$this->assign('isfound', 1);
			} else {
				$this->assign('isfound', 2);
			}
		}
		/*if (!empty($rolerow['gid'])) {
			foreach ($grouplist as $key => $value) {
				if ($value['id'] == $rolerow['gid']) {
					$value['iscurrent'] = 1;
					$grouplist[$key] = $value;
					break;
				}
			}
		}*/
		if($this->_REQUEST['op'] == 'edit'){
			//角色组
			$channel_model = getInstance('model.sdkChannel.channel');
			
			$gidarr = $channel_model->returnUidGroup($this->_uid);
			
			$roleuid = $gidarr[0]['uid'];

			$secondly = $this->_db->fetchAll('SELECT * FROM role_group WHERE `id` =' . intval($rolerow['gid']));
			if ($roleuid == 'root' || $roleuid == 'luojiang' || $roleuid == 'chenjh' || $roleuid == 'yangzhenwei') {
				$this->assign('grouplist', $grouplist);
			}else{
				$this->assign('grouplist', $secondly);
			}
		}elseif ($this->_REQUEST['op'] == 'add') {
			$this->assign('grouplist', $grouplist);
		}
		

		$ads_model = getInstance('model.ads');
		$accountList = $ads_model->getAccountList();
		
		$ads = explode(',', $rolerow['ads']);
		$rolerow['adsChannel'] = $ads[0];
		$rolerow['adsAccount'] = $ads[1];
		$rolerow['game'] = explode('|', $rolerow['game']);

		//用空字符替换掉旧版关联游戏输入框的个别换行
		$condition = array(" ","  ","\t","\n","\r");
		$rolerow['game'] = str_replace($condition,"",$rolerow['game']);
		
		//取出游戏列表
		// $gamelist = $this->_db->fetchAll('SELECT alias, upperName, specialName, name FROM ms_game WHERE status=1');
		// foreach ($gamelist as $key => $value) {
		// 	$gamelist[$key]['name'] = $value['upperName']. '—'. $value['specialName']. '—'. $value['name'];
		// }
		// $this->assign('gamelist', $gamelist);
		$this->assign('gameLists', $gameLists);
		$this->assign('this_game', explode(',', $rolerow['games']));
		$this->assign('rolerow', $rolerow);
		$this->assign('rolerowGame', $rolerow['game'][0]);
		$this->assign('ruid', $uid);
		$this->assign('modulelist', $list);
		$this->assign('rolegid', $rolerow['gid']);
		$this->assign('gameStr', $rolerow['game']);
		$this->assign('accountList', $accountList);
		$this->assign('header_id',explode(',', $header_data[0]['header_id']));
		$this->assign('player_data',$player_data);
		$this->assign('priv_data',$priv_data);
	}

	function listgroup() {
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}
		$fields = ' COUNT(1) rowcount';
		$formatsql = 'SELECT %s FROM @role_group';
		$sql = sprintf($formatsql, $fields);
		
		$row = $this->_db->fetchFirst($sql);
		$rowcount = $row['rowcount'];
		$sql = sprintf($formatsql, '*');
		$sql .=' LIMIT ' . $pagestart . ',' . $offset;
		$grouplist = $this->_db->fetchAll($sql);
		$this->assign('grouplist', $grouplist);
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
		//$this->show ( 'admin/listgroup.html' );
	}

	function listrole() {
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}
		$fields = ' COUNT(1) rowcount';
		$formatsql = 'SELECT %s FROM @role r LEFT JOIN @role_group rg ON r.gid=rg.id';
		$sql = sprintf($formatsql, $fields);
		$sql .=' WHERE gid != 10 ';
		if ($_REQUEST['gid']) {
			$sql .=' AND gid = '. $_REQUEST['gid'];
		}
		if ($_POST['ruid']) {
			$sql .=' AND uid = "'.$_POST['ruid'].'"';
		}

		$sql .= $_REQUEST['author'] ? ' AND r.author = "'.$_REQUEST['author'].'"' : '';
		$sql .= $_REQUEST['start_date'] ? " AND r.addtime >= '" . $_REQUEST['start_date'] ."'" : '';
		$sql .= $_REQUEST['end_date'] ? " AND r.addtime < '" . date('Y-m-d',strtotime($_REQUEST['end_date']."+ 1 day"))."'" : '';
		$row = $this->_db->fetchFirst($sql);
		$rowcount = $row['rowcount'];
		$fields = 'r.uid,r.gid,rg.name,r.author,r.addtime,r.lastlogintime,r.lastloginip';
		$sql = sprintf($formatsql, $fields);
		//不显示渠道用户
		$sql .=' WHERE r.gid != 10 ';
		if ($_REQUEST['gid']) {
			$sql .=' AND r.gid = '. $_REQUEST['gid'];
		}
		if ($_POST['ruid']) {
			$sql .=' AND r.uid = "'.$_POST['ruid'].'"';
		}

		$sql .= $_REQUEST['author'] ? ' AND r.author = "'.$_REQUEST['author'].'"' : '';
		$sql .= $_REQUEST['start_date'] ? " AND r.addtime >= '" . $_REQUEST['start_date'] ."'" : '';
		$sql .= $_REQUEST['end_date'] ? " AND r.addtime < '" . date('Y-m-d',strtotime($_REQUEST['end_date']."+ 1 day"))."'" : '';

		$sql .=' LIMIT ' . $pagestart . ',' . $offset;
		$rolelist = $this->_db->fetchAll($sql);
		$groupSql = 'SELECT `id`, `name` FROM role_group WHERE id != 10';
		$grouplist = $this->_db->fetchAll($groupSql);

		$this->assign('grouplist', $grouplist);
		$this->assign('rolelist', $rolelist);
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
		$this->assign('ruid', $_POST['ruid']);
		$this->assign('gid', $_REQUEST['gid']);
		$this->assign('author', $_REQUEST['author']);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		// $this->show ( 'admin/listrole.html' );
	}

	function addgroup() {
		$this->checkLogin();
		if (isset($this->_REQUEST ['do'])) {
			$name = !empty($this->_REQUEST ['form']['name']) ? $this->_REQUEST ['form']['name'] : '';
			$display = !empty($this->_REQUEST ['form']['display']) ? $this->_REQUEST ['form']['display'] : 0;
			if (empty($name)) {
				$this->showMsg($this->_L['name_not_allow_empty'], - 1);
				exit;
			}
			$privs = join(',', $this->_REQUEST ['form'] ['aid']);
			$update ['priv'] = $privs;
			$update ['display'] = $display;
			if ($this->_db->fetchFirst('SELECT * FROM @role_group WHERE name=\'' . $name . '\'')) {
				$this->_db->update('@role_group', $update, ' name=\'' . $name . '\'');
				$this->showMsg($this->_L['op_finish'], - 1);
				exit;
			} else {
				// $sql = 'REPLACE INTO @role_group(`name`,priv`) values';
				//$sql .= '(\'' . $name . '\',\'' . $privs . '\')';
				$this->_db->insert('@role_group', $update);
				exit;
			}
		}
		$name = !empty($this->_REQUEST ['name']) ? $this->_REQUEST ['name'] : '';
		$modules = $this->_db->fetchAll('
		SELECT ra.id,rm.name mname,ra.name aname,rm.sort,ra.action,ra.isadmin,rm.module 
		FROM @role_module rm , @role_action ra 
		WHERE rm.module=ra.module 
		AND ra.isadmin=1');
		$list = array();
		if (is_array($modules)) {
			foreach ($modules as $key => $value) {
				if (($value ['module']=='home' && $value ['action'] == 'index')||($value ['module']=='priv'&&($value ['action']=='login'||$value ['action']=='logout'))||$this->_role->hasGroupPermission($value ['module'], $value ['action'], $name)) {
					$value ['hasperm'] = 1;
				}
				$list [$value ['module']] ['list'] [] = $value;
				$list [$value ['module']] ['sort'] = $value ['sort'];
				$list [$value ['module']] ['name'] = $value ['mname'];
			}
		}
		$grouplist = $this->_role->fetchAllRoleGroup();
		$grouprow = $this->_role->fetchGroup($name);
		if (!empty($grouprow)) {
			$this->assign('id', $grouprow['id']);
			$this->assign('isfound', 1);
			foreach ($grouplist as $key => $value) {
				if ($value['id'] == $grouprow['id']) {
					$value['iscurrent'] = 1;
					$grouplist[$key] = $value;
					break;
				}
			}
		}
		
		$this->assign('grouprow', $grouprow);
		$this->assign('name', $name);
		$this->assign('grouplist', $grouplist);
		$this->assign('modulelist', $list);
		// $this->show ( 'admin/add_group.html' );
	}

	function delgroup() {
		$this->checkLogin();
		$id = intval($this->_REQUEST ['id']);
		$this->_db->query('DELETE FROM @role_group WHERE id=' . $id);
		$this->ShowMsg($this->_L['del_success'], 'index.php?m=priv&a=listgroup');
	}

	function delrole() {
		$this->checkLogin();
		$uid = $this->_REQUEST ['uid'];
		$this->_db->query('DELETE FROM @role WHERE uid=\'' . $uid . '\'');
		$this->ShowMsg($this->_L['del_success'], 'index.php?m=priv&a=listrole');
	}

	function delmenu() {
		$this->checkLogin();
		$id = $this->_REQUEST ['id'];
		$this->_db->query('DELETE FROM @role_menu WHERE id=\'' . $id . '\'');
		$this->ShowMsg($this->_L['del_success'], -1);
	}

	function listmenu() {
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}
		$fields = 'COUNT(1) rowcount';
		$formatsql = 'SELECT %s FROM @role_menu rm RIGHT JOIN @role_menu rm1 ON rm.id=rm1.parentid ORDER BY rm1.sort';
		$sql = sprintf($formatsql, $fields);
		$rowcount = $this->_db->fetchFirst($sql);
		$rowcount = $rowcount['rowcount'];

		$fields = 'rm.name pname,rm1.name,rm1.action,rm1.id,rm1.sort,rm1.display';
		$sql = sprintf($formatsql, $fields);
		$sql .= ' LIMIT ' . $pagestart . ',' . $offset;
		$menulist = $this->_db->fetchAll($sql);
		$this->assign('list', $menulist);
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
	}

	function addmenu() {
		//   error_reporting(2047);
		$this->checkLogin();
		$parentid = isset($this->_REQUEST ['parentid']) ? $this->_REQUEST ['parentid'] : '';
		if (isset($this->_REQUEST ['do'])) {
			$name = !empty($this->_REQUEST ['form']['name']) ? $this->_REQUEST ['form']['name'] : '';
			$sort = !empty($this->_REQUEST ['form']['sort']) ? $this->_REQUEST ['form']['sort'] : 0;
			$parentid = isset($this->_REQUEST ['form']['parentid']) ? intval($this->_REQUEST ['form']['parentid']) : 0;

			$display = isset($this->_REQUEST ['form']['display']) ? $this->_REQUEST ['form']['display'] : 0;
			$flag = isset($this->_REQUEST ['form']['flag']) ? $this->_REQUEST ['form']['flag'] : '';
			if (empty($name)) {
				$this->showMsg($this->_L['name_not_allow_empty'], - 1);
				exit;
			}
			$actions = join(',', $this->_REQUEST ['form'] ['aid']);
			$update ['action'] = $actions;
			$update ['name'] = $name;
			$update ['sort'] = $sort;
			$update ['author'] = $this->_uid;
			$update ['parentid'] = $parentid;
			$update ['display'] = $display;
			$update ['flag'] = $flag;
			//  $sql = 'REPLACE INTO @role_menu(`name`,`action`,`sort`,`author`,`parentid`,`display`) values';
			//  echo $sql .= '(\'' . $name . '\',\'' . $actions . '\','.$sort.',\''.$this->_uid.'\','.$parentid.','.$display.')';
			//   exit;
			$this->_db->insert('@role_menu', $update);
			$this->showMsg($this->_L['op_finish'], - 1);
			exit;
		}

		$modules = $this->_db->fetchAll('
		SELECT ra.id,rm.name mname,ra.name aname,rm.sort,ra.action,ra.isadmin,rm.module
		FROM @role_module rm , @role_action ra
		WHERE rm.module=ra.module
		AND ra.isadmin=1');
		$list = array();
		//error_reporting(2047);
		$topmenulist = $this->_db->fetchAll('SELECT * FROM @role_menu WHERE parentid=0');

		if (is_array($modules)) {
			$p = false;
			if ($parentid != 0)
			$p = true;
			foreach ($modules as $key => $value) {
				$ret = $this->_role->checkRepeatAction($value['module'], $value['action'], '', $value['param'], $p);
				if ($ret)
				continue;
				$list [$value ['module']] ['list'] [] = $value;
				$list [$value ['module']] ['sort'] = $value ['sort'];
				$list [$value ['module']] ['name'] = $value ['mname'];
			}
		}
		$this->assign('parentid', $parentid);
		$this->assign('topmenulist', $topmenulist);
		$this->assign('modulelist', $list);
	}

	function editmenu() {
		$this->checkLogin();
		// error_reporting(2047);
		$id = intval($this->_REQUEST ['id']);
		$parentid = isset($this->_REQUEST ['parentid']) ? $this->_REQUEST ['parentid'] : '';
		if (isset($this->_REQUEST ['do'])) {
			$name = !empty($this->_REQUEST ['form']['name']) ? $this->_REQUEST ['form']['name'] : '';
			$sort = !empty($this->_REQUEST ['form']['sort']) ? $this->_REQUEST ['form']['sort'] : 0;
			$parentid = isset($this->_REQUEST ['form']['parentid']) ? intval($this->_REQUEST ['form']['parentid']) : 0;

			$display = isset($this->_REQUEST ['form']['display']) ? $this->_REQUEST ['form']['display'] : 0;
			$link = isset($this->_REQUEST ['form']['link']) ? $this->_REQUEST ['form']['link'] : '';
			$flag = isset($this->_REQUEST ['form']['flag']) ? $this->_REQUEST ['form']['flag'] : '';
			// echo 'SELECT 1 FROM @role_menu WHERE flag=\''.$flag.'\' AND id !='.$id;
			if ($this->_db->fetchFirst('SELECT 1 FROM @role_menu WHERE flag=\'' . $flag . '\' AND flage!=\'\' AND id !=' . $id)) {
				$this->showMsg($this->_L['falg_repeat'], - 1);
				exit;
			}
			if (empty($name)) {
				$this->showMsg($this->_L['name_not_allow_empty'], - 1);
				exit;
			}
			if ($this->_REQUEST['form']['aid']) {
				$actions = join(',', $this->_REQUEST ['form'] ['aid']);
			}

			$update ['action'] = $actions;
			$update ['name'] = $name;
			$update ['sort'] = $sort;
			$update ['author'] = $this->_uid;
			$update ['parentid'] = $parentid;
			$update ['display'] = $display;
			$update ['link'] = $link;
			$update ['flag'] = $flag;
			$this->_db->update('@role_menu', $update, ' id=\'' . $id . '\'');
			$this->showMsg($this->_L['op_finish'], - 1);
			exit;
		}

		$menurow = $this->_db->fetchFirst('SELECT * FROM @role_menu WHERE id=' . $id);
		if ($parentid == '')
		$parentid = $menurow['parentid'];
		$modules = $this->_db->fetchAll('
		SELECT ra.id,rm.name mname,ra.name aname,rm.sort,ra.action,ra.isadmin,rm.module,ra.param 
		FROM @role_module rm , @role_action ra
		WHERE rm.module=ra.module
		AND ra.isadmin=1');
		$list = array();

		if (is_array($modules)) {
			$p = false;
			if ($parentid != 0)
			$p = true;
			foreach ($modules as $key => $value) {

				$ret = $this->_role->checkRepeatAction($value['module'], $value['action'], $id, $value['param'], $p);
				if ($ret)
				continue;
				if ($this->_role->hasMenuPermission($value ['module'], $value ['action'], $id, $value['param'])) {
					$value ['hasmenu'] = 1;
				}
				$list [$value ['module']] ['list'] [] = $value;
				$list [$value ['module']] ['sort'] = $value ['sort'];
				$list [$value ['module']] ['name'] = $value ['mname'];
			}
		}

		$topmenulist = $this->_db->fetchAll('SELECT * FROM @role_menu WHERE parentid=0');


		setCurrentArray($topmenulist, 'id', $menurow['parentid']);
		$this->assign('parentid', $parentid);
		$this->assign('id', $id);
		$this->assign('menurow', $menurow);
		$this->assign('topmenulist', $topmenulist);
		$this->assign('modulelist', $list);
	}

	function listuser() {
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}
		$fields = 'COUNT(1) rowcount';
		$formatsql = 'SELECT %s FROM @role';
		$sql = sprintf($formatsql, $fields);
		$row = $this->_db->fetchFirst($sql);
		$rowcount = $row['rowcount'];
		$sql = sprintf($formatsql, '*');
		$sql .= ' LIMIT ' . $pagestart . ',' . $offset;
		$list = $this->_db->fetchAll($sql);
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
	}

	function listmastermodule() {
		error_reporting(2047);
		$this->checkLogin();
		$page = 1;
		$offset = self::OFFSET;
		$pagestart = 0;
		if (isset($this->_REQUEST['page'])) {
			$page = intval($this->_REQUEST['page']);
			$pagestart = ($page - 1) * $offset;
		}
		$fields = 'COUNT(1) rowcount';
		$formatsql = 'SELECT %s FROM @role_module';
		$sql = sprintf($formatsql, $fields);
		$row = $this->_db->fetchFirst($sql);
		$rowcount = $row['rowcount'];
		$sql = sprintf($formatsql, '*');
		$sql .= ' LIMIT ' . $pagestart . ',' . $offset;
		$list = $this->_db->fetchAll($sql);
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('offset', $offset);
		$this->assign('rowcount', $rowcount);
	}

	function editmastermodule() {
		$this->checkLogin();
		$module = $this->_REQUEST['module'];
		if (isset($this->_REQUEST['do'])) {
			$reqdata = $this->_REQUEST['form'];
			$this->_db->update('@role_module', $reqdata, 'module=\'' . $module . '\'');
			$this->showMsg($this->_L['op_finish'], -1);
			exit;
		}
		$row = $this->_db->fetchFirst('SELECT * FROM @role_module WHERE module=\'' . $module . '\'');
		$this->assign('row', $row);
		$this->assign('module', $module);
	}

	function delmastermodule() {
		$this->checkLogin();
		$module = $this->_REQUEST['module'];
		$row = $this->_db->query('DELETE FROM @role_module WHERE module=\'' . $module . '\'');
		$this->showMsg($this->_L['op_finish'], -1);
	}

	function edituser() {
		$this->checkLogin();
		$id = intval($this->_REQUEST['id']);
		if (isset($this->_REQUEST['do'])) {
			$reqdata = $this->_REQUEST['form'];
			$reqdata['password'] = md5($this->_config['cookie_prefix'] . $reqdata['password']);
			$this->_db->update('@role', $reqdata, 'id=' . $id);
			$this->showMsg($this->_L['op_finish'], -1);
			exit;
		}
		$row = $this->_db->fetchFirst('SELECT * FROM @role WHERE id=' . $id);
		$this->assign('row', $row);
		$this->assign('id', $id);
	}

	function adduser() {
		$this->checkLogin();
		$id = intval($this->_REQUEST['id']);
		if (isset($this->_REQUEST['do'])) {
			$reqdata = $this->_REQUEST['form'];
			$reqdata['author'] = $this->_uid;
			$reqdata['password'] = md5($this->_config['cookie_prefix'] . $reqdata['password']);
			$this->_db->insert('@role', $reqdata);
			$this->showMsg($this->_L['op_finish'], -1);
			exit;
		}
	}

	function deluser() {
		$this->checkLogin();
		$id = intval($this->_REQUEST['id']);
		$this->_db->execute('DELETE FROM @role WHERE id=' . $id);
		$this->showMsg($this->_L['op_finish'], '-1');
	}

	function logout() {
		cookie::set('oss_admin_user', '', time() - 3600000, $this->_config['cookie_path'], $this->_config['cookie_domain']);
		$this->showMsg($this->_L['logout_success'], 'index.php?m=priv&a=login');
		exit();
	}

	function login() {
		// error_reporting(2047);
		if (isset($this->_REQUEST['do'])) {

			// 开启session服务
			session_start();
			// 1. 获取到用户提交的验证码
			$captcha = $_POST["captcha"];
			// 2. 将session中的验证码和用户提交的验证码进行核对,当不成功则提示不正确，重新提交
			if(strtolower($_SESSION["captcha"]) != strtolower($captcha)){
				showMsg('验证码不正确!', '-1');
				exit;
			}

			$uid = isset($this->_REQUEST['username']) ? $this->_REQUEST['username'] : '';
			$pwd = isset($this->_REQUEST['userpass']) ? $this->_REQUEST['userpass'] : '';
			$password = md5($this->_config['cookie_prefix'] . $pwd);
			if (empty($uid) || empty($password)) {
				$this->showMsg($this->_L['uid_or_password_not_empty'], '-1');
				exit;
			}
			$uid = mysql_real_escape_string($uid);
            $password = mysql_real_escape_string($password);
			$sql = 'SELECT uid FROM @role WHERE uid=\'' . $uid . '\' AND password=\'' . $password . '\'';
			$row = $this->_db->fetchFirst($sql);
			
			
			if ($row) {
				cookie::set('oss_admin_user', $row['uid'], $this->_config['cookie_expire'], $this->_config['cookie_path'], $this->_config['cookie_domain']);
				$ip = GetIP();
				$sql = 'UPDATE @role SET `prevlogintime`=`lastlogintime`,`prevloginip`=`lastloginip`,`lastlogintime` = \''.date('Y-m-d H:i:s').'\',`lastloginip`=\''.$ip.'\' WHERE uid=\''.$uid.'\'';
				$this->_db->query($sql);
				$this->showMsg($this->_L['login_success'], 'index.php?m=home&a=index');
				exit;
			} else {
				showMsg($this->_L['login_faild'], '-1');
				exit;
			}
		}
	}

}

?>
