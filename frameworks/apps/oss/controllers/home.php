<?php

/**
 * 后台首页
 * @author lijianjun
 *
 */
require_once APP_CONTROLLER_PATH . '/master.php';

class homeController extends masterControl {
    function index() {
        $this->checkLogin();
        $row = $this->_db->fetchFirst('SELECT lastlogintime,lastloginip,prevlogintime,prevloginip FROM @role WHERE uid=\''.$this->_uid.'\'');
        $this->assign('row', $row);
    }

}

?>
