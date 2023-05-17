<?php
/**
 * Ajax模块
 * @author lijianjun
 *
 */
require_once APP_CONTROLLER_PATH.'/master.php';
class ajaxController extends masterControl {
    function __construct() {
        parent::__construct();

    }
    function addmodule() {
        $result = array();
        $insert['name'] = $this -> _REQUEST['name'];
        $insert['module'] = $this -> _REQUEST['module'];
        $insert['sort'] = $this -> _REQUEST['sort'];
        $this -> _db -> insert('@role_module',$insert);
        ajaxSuccess();
    }
    function addgroup() {
        $result = array();
        $insert['name'] = $this -> _REQUEST['name'];
        $this -> _db -> insert('@role_group',$insert);
        ajaxSuccess();
    }

    function delarc() {
        $result = array();
        $this -> loadTable('question_arc');
        $id = intval($this -> _REQUEST['id']);
        empty($id) && ajaxError('empty id');
        $row = $this -> question_arc->getOne('id='.$id);
        if($row['arcpath'])unlink(ROOT_PATH.$this->_config['upload_path'].'/'.$row['arcpath']);
        if(!$this -> question_arc -> del('id='.$id)) {
            ajaxError('delete error');
        }
        //      echo $this->_config['upload_path'].'/'.$row['arcpath'];
        ajaxSuccess();
    }
    function delworkflow () {
        $this -> loadTable('question_arc');
        $this -> loadTable('question_workflow');
        $id = intval($this->_REQUEST['id']);
        $this->_db->start();
        if(!$this->question_workflow->del('id='.$id)) {
            $this->_db->rollback();
            ajaxError('delete error');
            exit;
        }
        if(!$this->question_arc->del('qwid='.$id)) {
            $this->_db->rollback();
            ajaxError('delete error');
            exit;
        }
        $this->_db->commit();
        ajaxSuccess();
    }

    function checkuser() {
        $gameid = intval($this->_REQUEST['gameid']);
        $serverid = intval($this->_REQUEST['serverid']);
        $url = 'http://fg.hx.gzfeiyin.com/payment/index.php?dopost=checkUser&&exchange=1&gameid='.$gameid.'&serverid='.$serverid;
        echo httpGet($url);
    }
}
?>
