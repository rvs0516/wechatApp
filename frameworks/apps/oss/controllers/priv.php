<?php

/**
 * 权限管理模块
 */

require_once APP_CONTROLLER_PATH . '/master.php';

class privController extends masterControl {

    const OFFSET = 20;

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
            $insertdata = $this->_REQUEST['form'];
            $insertdata['ctime'] = date('Y-m-d H:i:s');
            $insertdata['author'] = $this->_uid;
            $insertdata['sort'] = $this->_REQUEST['form']['sort'] ? $this->_REQUEST['form']['sort'] : 0;

            // echo "<pre>";
            // var_dump($insertdata);
            // exit;

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
        //echo "<pre>";
        //print_r($roleaction);
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
        $fields = ' COUNT(1) rowcount';
        $formatsql = 'SELECT %s
		FROM @role_module rm , @role_action ra
		WHERE rm.module=ra.module';
        $sql = sprintf($formatsql, $fields);
        $row = $this->_db->fetchFirst($sql);
        $rowcount = $row['rowcount'];
        $fields = ' ra.id,rm.name mname,ra.name aname,ra.sort,ra.ctime,ra.author,ra.action,ra.isadmin,rm.module';
        $sql = sprintf($formatsql, $fields);
        $sql .= ' ORDER BY ra.ctime DESC LIMIT ' . $pagestart . ',' . $offset;
        $list = $this->_db->fetchAll($sql);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('offset', $offset);
        $this->assign('rowcount', $rowcount);
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
        if (isset($this->_REQUEST ['do'])) {

            // echo "<pre>";
            // var_dump($this->_REQUEST);
            // exit;

            $uid = !empty($this->_REQUEST ['form']['uid']) ? $this->_REQUEST ['form']['uid'] : '';
            if (empty($uid)) {
                $this->showMsg($this->_L['uid_error'], - 1);
                exit;
            }
            $privs = join(',', $this->_REQUEST ['form'] ['aid']);
            $gid = intval($this->_REQUEST ['form']['gid']);

            // 授权员工
            $update['agent_uid'] = $this->_REQUEST ['form']['employee'];

            $mobile = $this->_REQUEST ['form']['mobile'];
            $mail = $this->_REQUEST ['form']['mail'];
            $realname = $this->_REQUEST ['form']['realname'];
            $update ['uid'] = $uid;
            $update ['priv'] = $privs;
            $update ['gid'] = $gid;
            $update ['realname'] = $realname;
           
            if (!empty($this->_REQUEST ['form']['password'])) {
                $password = md5($this->_config['cookie_prefix'] . $this->_REQUEST ['form']['password']);
                $update ['password'] = $password;
            }
            $update ['mobile'] = $mobile;
            $update ['mail'] = $mail;
            $update ['author'] = $this->_uid;
            if ($this->_db->fetchFirst('SELECT * FROM @role WHERE uid=\'' . $uid . '\'')) {
                $this->_db->update('@role', $update, ' uid=\'' . $uid . '\'');
                $this->showMsg($this->_L['op_finish'], - 1);
                exit;
            } else {
            //    $sql = 'REPLACE INTO @role(`uid`,`priv`,`gid`,`password`,`mobile`,`mail`,`author`) values';
            //    $sql .= '(\'' . $uid . '\',\'' . $privs . '\',\''.$gid.'\',\''.$password.'\',\''.$mobile.'\',\''.$mail.'\',\''.$this->_uid.'\')';
                if ($this->_db->insert('@role', $update)) {
                    $this->showMsg($this->_L['op_finish'], - 1);
                } else {
                    $this->showMsg($this->_L['op_failed'], - 1);
                }
                exit;
            }
        }

        $uid = !empty($this->_REQUEST ['uid']) ? $this->_REQUEST ['uid'] : '';
        $modules = $this->_db->fetchAll('
		SELECT ra.id,rm.name mname,ra.name aname,rm.sort,ra.action,ra.param,ra.isadmin,rm.module
		FROM @role_module rm , @role_action ra 
		WHERE rm.module=ra.module 
		AND ra.isadmin=1 
		AND ra.display=1');
        $grouplist = $this->_role->fetchAllRoleGroup();
        $rolerow = $this->_role->fetchRole($uid);
        if (isset($this->_REQUEST ['uid'])) {
            if (!empty($rolerow)) {
                $this->assign('isfound', 1);
            } else {
                $this->assign('isfound', 2);
            }
        }
        if (!empty($rolerow['gid'])) {
            foreach ($grouplist as $key => $value) {

                if ($value['id'] == $rolerow['gid']) {

                    $value['iscurrent'] = 1;
                    $grouplist[$key] = $value;
                    break;
                }
            }
        }

        // 获取员工列表
        $employeeSql = 'select userid from crm_employees';
        $employeeList = $this->_db->query($employeeSql);

        // echo "<pre>";
        // var_dump($rolerow);
        // exit;
        
        $this->assign('rolerow', $rolerow);
        $this->assign('ruid', $uid);
        $this->assign('grouplist', $grouplist);
        $this->assign('employeeList', $employeeList);
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
        $row = $this->_db->fetchFirst($sql);
        $rowcount = $row['rowcount'];
        $fields = 'r.uid,r.gid,rg.name,r.agent_uid';
        $sql = sprintf($formatsql, $fields);
        //不显示渠道用户
        $sql .=' WHERE r.gid != 10 ';
        $sql .=' LIMIT ' . $pagestart . ',' . $offset;
        $rolelist = $this->_db->fetchAll($sql);

        // echo "<pre>";
        // var_dump($sql);
        // var_dump($rolelist);
        // exit;


        $this->assign('rolelist', $rolelist);
        $this->assign('page', $page);
        $this->assign('offset', $offset);
        $this->assign('rowcount', $rowcount);
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
        // $this->showMsg($this->_L['logout_success'], 'index.php?m=priv&a=login');
        $this->showMsg($this->_L['logout_success'], 'index.php?m=priv&a=wxWebLogin'); // 返回企业微信扫码登录页面
        exit();
    }

    function login() {
        // error_reporting(2047);

        // 如果存在企业成员微信userid，则
        // if (FOLLOW_USERID) {
        //     // 防sql注入, 预防数据库攻击
        //     $follow_userid = mysql_real_escape_string(FOLLOW_USERID);

        //     // 获取当前企业成员微信userid对应的crm系统账号信息
        //     $sql = 'SELECT uid FROM @role WHERE follow_userid=\'' . $follow_userid . '\'';
        //     $row = $this->_db->fetchFirst($sql);
        //     if ($row) {
        //         // 保存登录信息到cookie
        //         cookie::set('oss_admin_user', $row['uid'], $this->_config['cookie_expire'], $this->_config['cookie_path'], $this->_config['cookie_domain']);
        //         // 更新登录时间和IP
        //         $ip = GetIP();
        //         $sql = 'UPDATE @role SET `prevlogintime`=`lastlogintime`,`prevloginip`=`lastloginip`,`lastlogintime` = \''.date('Y-m-d H:i:s').'\',`lastloginip`=\''.$ip.'\'';
        //         $this->_db->query($sql);
        //         $this->showMsg($this->_L['login_success'], 'index.php?m=home&a=index');
        //         exit;
        //     } else {
        //         showMsg($this->_L['login_faild'], '-1');
        //         exit;
        //     } 
        // }
        

        // 平台用户使用账号和密码登录方式
        if (isset($this->_REQUEST['do'])) {

            if ( !session_id() ) {
                // 当前用户不存在session_id(会话ID)，说明没有使用session储存过信息，则开启session服务
                session_start();
            }

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

            // 防sql注入, 预防数据库攻击
            $uid = $this->_db->quote($uid);
            $password = $this->_db->quote($password);

            $sql = 'SELECT `uid` FROM @role WHERE `uid`=\'' . $uid . '\' AND password=\'' . $password . '\'';
            $row = $this->_db->fetchFirst($sql);

            if ($row) {
                // 保存登录信息到cookie
                cookie::set('oss_admin_user', $row['uid'], $this->_config['cookie_expire'], $this->_config['cookie_path'], $this->_config['cookie_domain']);
                // 更新登录时间和IP
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

    /**
     * 企业微信扫码登录
     * 
     * 【说明】
     * 通过构造登录链接方式实现企业微信扫码登录
     * 
     * 前提
     * 登录 企业管理端后台->进入需要开启的自建应用->点击 “企业微信授权登录”
     * 
     * 示例
     * 
     * 回调地址：http://test.admin.wxwork.2y9y.com/priv/wxWebLogin urlencode后为 http%3A%2F%2Ftest.admin.wxwork.2y9y.com%2Fpriv%2FwxWebLogin
     * 
     * 扫码页面URL
     * https://login.work.weixin.qq.com/wwlogin/sso/login?appid=wwe6ce267036e47037&agentid=1000004&redirect_uri=http%3A%2F%2Ftest.admin.wxwork.2y9y.com%2Fpriv%2FwxWebLogin&state=STATE
     * 
     * 扫码授权登录后跳转到回调地址带上code：http://test.admin.wxwork.2y9y.com/priv/wxWebLogin?code=aKxB5aHGUMwhwBf5BTp_Ux2AWTw94maGf5dNUTufyFk&state=STATE
     * 
     */
    public function wxWebLogin()
    {
        ob_clean(); // 清除缓存
        
        error_log("\n".date("[Y-m-d H:i:s]").":\n". 'REQUEST: '. json_encode($this->_REQUEST). "\n\n", 3, C('DEDE_DATA_PATH')."logs/wxWebLogin_".date("ymd").".txt");
        // REQUEST: {"m":"priv","a":"wxWebLogin","code":"aKxB5aHGUMwhwBf5BTp_Ux2AWTw94maGf5dNUTufyFk","state":"STATE"}

        // echo '企业微信扫码登录';exit;
        // echo "<pre>";
        // var_dump($this->_REQUEST);
        // exit;

        $code = $this->_REQUEST['code'];

        // 获取当前访问域名
        $http = $_SERVER['REQUEST_SCHEME'];
        $domain = $_SERVER['SERVER_NAME'];

        if ($code) {

            // 扫码登录成功

		    $appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录

            // 实例化企业微信相关接口对象
            require_once APP_PATH.'models/api/workWeixin.php';
            $workWeixin = new workWeixin($corpId, $appSecret);

            // 获取用户登录身份
            $userId = $workWeixin->getUserInfo($code);

            // echo "<pre>";
            // var_dump($userId);
            // exit;

            if ($userId) {

                // 获取企业成员详情信息
                // $userDetail = $workWeixin->getEmployeeList($userId);

                // echo "<pre>";
                // var_dump($userDetail);
                // exit;

                // 根据企业成员userid获取scrm平台角色数据表中对应的角色名称，判断是否授权登录scrm平台
                $sql = 'SELECT `uid` FROM @role WHERE `agent_uid`=\'' . $userId . '\'';
                $row = $this->_db->fetchFirst($sql);

                // echo "<pre>";
                // var_dump($row);
                // exit;
    
                if ($row) {
                    // 保存登录信息到cookie
                    cookie::set('oss_admin_user', $row['uid'], $this->_config['cookie_expire'], $this->_config['cookie_path'], $this->_config['cookie_domain']);
                    // 更新登录时间和IP
                    $ip = GetIP();
                    $sql = 'UPDATE @role SET `prevlogintime`=`lastlogintime`,`prevloginip`=`lastloginip`,`lastlogintime` = \''.date('Y-m-d H:i:s').'\',`lastloginip`=\''.$ip.'\' WHERE uid=\''.$row['uid'].'\'';
                    $this->_db->query($sql);

                    // echo "<pre>";
                    // var_dump($row);
                    // exit;

                    $this->showMsg($this->_L['login_success'], $http.'://'.$domain.'/index.php?m=home&a=index');
                    exit;
                    
                } else {

                    $this->showMsg('当前账号还未授权，请联系平台管理员！', $http.'://'.$domain.'/index.php?m=priv&a=wxWebLogin');
                    exit;
                }
                
            } else {
                $this->showMsg('登录失败，请联系平台管理员！', $http.'://'.$domain.'/index.php?m=priv&a=wxWebLogin');
                exit;
            }

        } else {

            // 扫码登录页面

            // 重定向URL，即登录成功后的回调地址
            $callbackUrl = $http.'://'.$domain.'/priv/wxWebLogin';

            // 构造扫描企业微信登录二维码链接
            $corpId = 'wwe6ce267036e47037'; // 企业ID
            $agentId = '1000004'; // 应用ID
            $redirectUri = urlencode($callbackUrl);
            $state = createNonceStr(16); // 自定义参数，登录成功后企业微信原样返回
            $wxLoginUrl = "https://login.work.weixin.qq.com/wwlogin/sso/login"; // 企业微信登录二维码链接
            $wxLoginUrl .= "?appid=$corpId";
            $wxLoginUrl .= "&agentid=$agentId";
            $wxLoginUrl .= "&redirect_uri=$redirectUri";
            $wxLoginUrl .= "&state=$state";

            // echo "<pre>";
            // var_dump($wxLoginUrl);
            // exit;

            $this->assign('wxLoginUrl', $wxLoginUrl);

        }

    }

    

}

?>
