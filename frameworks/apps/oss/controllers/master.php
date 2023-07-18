<?php
//namespace kefuMaster;

require_once dirname(__FILE__).'/../kefu_setting/kefu_functions.php';
require_once dirname(__FILE__).'/../kefu_setting/kefu_settings.php';
require_once dirname(__FILE__).'/../kefu_setting/kefu_ext_settings.php';
require_once dirname(__FILE__).'/../../../mvc/libs/cookie.php';

//新MVC架构在函数局部引入本控制器，所以需要把配置注入到全局中才能使用
//基本配置文件注入到全局中
$GLOBALS['config'] = &$config;
$GLOBALS['kefuconfig'] = &$kefuconfig;
if(!defined('NOT_OSS_CONTROL')) {
    class masterControl Extends controller {

        public $_db = null;
        public $_view = null;
        public $_uid='';
        public $_config='';
        public $_GET='';
        public $_POST='';
        public $_REQUEST='';
        public $_COOKIE='';
        public $_SESSION='';
        public $_SERVER='';
        public $_FILES='';
        public $_M='';
        public $_A='';
        public $_role='';
        public $_L='';
        public $_uploader='';
        public $_loger='';
        public $_is_channel='';
        public $_channel_role='';

        function __construct() {
            global $kefuconfig,$status;
            parent::__construct();
            $this->_config = $kefuconfig;
            $this->_GET = &$_GET;
            $this->_POST = &$_POST;
            $this->_REQUEST = &$_REQUEST;
            $this->_COOKIE = &$_COOKIE;
            $this->_SESSION = &$_SESSION;
            $this->_SERVER = &$_SERVER;
            $this->_FILES = &$_FILES;
            $this->_db = KefuDataBase::getInstance();
            $this->assign('status',$kefuconfig['status']);
            $this->_M = $this->_REQUEST['m'];
            $this->_A = $this->_REQUEST['a'];
            require_once APP_MODEL_PATH.'/role.class.php';
            $this->_role = new Role ( $this->_uid, $this->_db );
            $action  = $this->_role->fetchAction($this->_M,$this->_A);
            if(isset($kefuconfig['lang'])) {
                require_once APP_PATH.'/kefu_setting/'.$kefuconfig['lang'].'.lang.php';
                $this->_L = $L;
            }
		
            $this->initUploader();
            $this->initLoger();
            $this->initUser();
			$this->checkLogin();
			$this->initChannle();
            $this->initTemplateVal();
            if(!empty($this->_uid)) {
                if (!$this->_role->hasPermission ( $action ['module'], $action ['action'], $action ['param'], $this->_uid,true,true )) {
                    $this->showMsg($this->_L['priv_error'],'-1');
                    exit;
                }
            }
            if($this->_M != 'ajax') {
                $this->initMenu();
            }
            $sql = 'ALTER TABLE role ADD COLUMN `lastlogintime` DATETIME DEFAULT NULL AFTER author';
            $this->_db->query($sql);
          //  echo '****************';
            $sql = 'ALTER TABLE role ADD COLUMN `lastloginip` varchar(32) DEFAULT NULL AFTER author';
            $this->_db->query($sql);

            $sql = 'ALTER TABLE role ADD COLUMN `prevlogintime` DATETIME DEFAULT NULL AFTER author';
            $this->_db->query($sql);
          //  echo '****************';
            $sql = 'ALTER TABLE role ADD COLUMN `prevloginip` varchar(32) DEFAULT NULL AFTER author';
            $this->_db->query($sql);
            
            // 根据srcm平台角色名称获取对应的企业成员userid
            $getAgentUidSql = "SELECT agent_uid FROM `role` WHERE `uid` = '{$this->_uid}'";
			$getAgentUidArray = $this->_db->fetchAll($getAgentUidSql);
            $agent_uid = $getAgentUidArray[0]['agent_uid'];
            if ($agent_uid) {
                $this->assign('uid',$agent_uid);
            } else {
                $this->assign('uid',$this->_uid);
            }

            $this->assign('bbcode_comment', C('bbcode_comment'));
        }

        function initTemplateVal() {
            define('TEMPLATE_SKIN','');
//            $cfg_webname = array_key_exists('cfg_webname', $GLOBALS) ? $GLOBALS['cfg_webname'] : '';
//            $this->assign('cfg_webname',$cfg_webname);
            $this->assign('template_skin', $this -> _config ['steppath'] . $this -> _config ['template_root_path'] . $this -> _config ['template_path']);
            $this->assign('plugin_url', $this -> _config ['steppath'] . '/plugin');
            $this->assign('upload_path', $this -> _config ['steppath'].$this->_config['upload_path']);
            $this->assign('status',$this->_config['status']);
            return $this;
        }

        function loadTable($modelname) {
            $this -> $modelname = new KefuModel(strtolower($modelname));
            $this -> $modelname -> setDB($this -> _db);

        }

        function initUser() {
            cookie::start($this->_config['cookie_namespace']);
            $user = cookie::get('oss_admin_user');
            //延长用户登入时间
            if($user) {
                cookie::set('oss_admin_user', $user, $this->_config['cookie_expire'], $this->_config['cookie_path'], $this->_config['cookie_domain']);
            }
            $this->_uid = $user;
        }

        function initUploader() {
            include_once LIB_PATH . '/upload.php';
            $this->_uploader = new Upload($this->_config['upload_maxsize'],$this->_config['upload_allow_exts'],$this->_config['upload_allow_type'],APP_PATH.'../../oss'.$this->_config['upload_path'],$this->_config['upload_rule']);
        }

        function initLoger() {
            define('LOG_PATH',APP_CACHE_PATH.'/log/');
            $this->_loger = new MyLog();
            $this->_loger->setDB($this->_db);
            $this->_loger->setUploader($this->_uploader);
            return $this;
        }

        /**
         * 检查是否登录
         */
        function checkLogin() {
			$ignore = ($_GET['m'] === 'priv' && ($_GET['a'] === 'login' || $_GET['a'] === 'wxWebLogin'));
            if(empty($this->_uid) && !$ignore) {
                // 提示：你还未登入,请先登入
                $this->showMsg($this->_L['login_please'],'index.php?m=priv&a=login');
                exit;
            }
        }
        
        function initChannle() {
			$channel_role_array = array();
            $sql = 'SELECT purview FROM fg_channel WHERE alias = "' . $this->_uid . '" LIMIT 1';
            $channel = $this->_db->fetchAll($sql);
            if ($channel) {
            	$this->_is_channel = true;

				$sql = 'SELECT role FROM fg_channel_role WHERE roleid IN ( ' . $channel[0]['purview'] . ' )';
				$channel_role = $this->_db->fetchAll($sql);
				if ($channel_role) {
					foreach ($channel_role as $key => $value) {
						$channel_role_array[] = $value['role'];
					}
					$this->_channel_role = $channel_role_array;
				}
            }
        }

        function initMenu() {
            //by Prolove
            $sql = 'SELECT rm.name,rm.id,rm.link,ra.action,ra.module FROM @role_menu rm LEFT JOIN @role_action ra ON rm.action=ra.id WHERE parentid=0 AND rm.display =1 ORDER BY rm.sort';
            $topmenuarr = $this->_db->fetchAll($sql);
            
            // echo "<pre>";
            // var_dump($topmenuarr);
            // exit;

            $menuid = 0;
            $twomenus = array();
            //print_r($topmenuarr);
            if(array_key_exists('menuid', $this->_REQUEST) && $this->_REQUEST['menuid']) {
                $menuid = intval($this->_REQUEST['menuid']);
                $sql = 'SELECT * FROM @role_menu WHERE parentid='.$menuid.' AND display=1';
                $twomenus = $this->_db->fetchAll($sql);
            } else {
                $action  = $this->_role->fetchAction($this->_M,$this->_A);
                if(empty($action)) {
                    $this->showMsg($this->_L['action_not_exists'],'index.php?m=home&a=index');
                    exit;
                }

                $sql = "SELECT * FROM @role_menu WHERE id in (SELECT parentid FROM @role_menu WHERE action REGEXP '^{$action['id']}$|,{$action['id']}|{$action['id']},')";
                $row = $this->_db->fetchFirst( $sql );

                if(array_key_exists('parentid', $row) && $row['parentid']==0) {
                    $menuid = $row['id'];
                } else {
                    $menuid = array_key_exists('parentid', $row) ? $row['parentid'] : 0;
                }
                if ($menuid != 0){
                	$sql = 'SELECT * FROM @role_menu WHERE parentid='.$menuid.' AND display=1';
                	$twomenus = $this->_db->fetchAll($sql);
                }
            }
            $menus = array();
            if ($twomenus) {
	            foreach($twomenus as $twomenu) {
	                $sql = 'SELECT * FROM @role_action WHERE id IN ('.$twomenu['action'].')  AND display=1 AND isadmin=1 ORDER BY sort';
	                $threemenus = $this->_db->fetchAll($sql);
	                $temparr = array();
	                if (is_array($threemenus)) {
		                foreach($threemenus as $key=>$value) {
		                    if($this->_role->hasPermission ( $value ['module'], $value ['action'],$value ['param'], $this->_uid ,true,true )) {
		                        $temparr[] = $value;
		                    }
		                }	                	
	                }
	                $menus[$twomenu['id']]['name'] = $twomenu['name'];
	                $menus[$twomenu['id']]['list'] = $temparr;
	                //print_r($menus);
	
	            }            	
            }

            $topmenus = &$topmenuarr;
            // echo "<pre>";
            // var_dump($topmenus);
            // exit;
            //print_r($topmenuarr);
            $threemenus = array();
            $conuter = array();
            foreach($topmenuarr as $value) {
                $conuter[$value['id']] = 0;
                $sql = 'SELECT * FROM @role_menu WHERE parentid='.$value['id'].' AND display=1';
                $submenus = $this->_db->fetchAll($sql);
                if(empty($submenus)) continue;
                foreach($submenus as $submenu) {
                    $sql = 'SELECT * FROM @role_action WHERE id IN ('.$submenu['action'].')  AND display=1 AND isadmin=1';
                    $threemenus = $this->_db->fetchAll($sql);
                    //  print_r($threemenus);
                    if (is_array($threemenus)) {
	                    foreach($threemenus as $key=>$val) {
	                        if($this->_role->hasPermission ( $val ['module'], $val ['action'],$val ['param'], $this->_uid ,true,true )) {
	                            $conuter[$value['id']]++;
	                        }
	                    }                    	
                    }
                }
            }

            //  print_r($conuter);
            foreach($conuter as $ky=>$val) {
                if($val == 0) {
                    foreach($topmenus as $key=>$value) {
                        if($value['id'] == $ky)unset($topmenus[$key]);
                    }
                }
            }
            $this->assign('menuid',$menuid);
            $this->assign('topmenus',$topmenus);
            $this->assign('menus',$menus);
           
            return $this;
        }

        function showMsg($msg, $gourl, $onlymsg = 0, $limittime = 0) {
            showMessge($msg, $gourl, $onlymsg, $limittime,$this);
        }
    }
}
class KefuDataBase {
    private $_tbflag = '@';
    private $_tbprefix ='';
    private $_db;
    public static $_instance = null;
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new KefuDataBase();
        }
        return self::$_instance;
    }
    function __construct() {
        $this->_db = model::$engine;
    }

    function start() {
        $this->query('START TRANSACTION');
    }

    function commit() {
        $this->query ( 'COMMIT' );
    }

    function rollBack() {
        $this->query ( 'ROLLBACK' );
    }

    function setSql($sql) {
        $sql = preg_replace ( '/ ' . $this->_tbflag . '/', ' '.$this->_tbprefix, $sql );
        return $sql;
    }

    function fetchAll($sql) {
        return $this->query($sql);
    }

    function fetchFirst($sql) {
        $rows = $this->fetchAll($sql);
        return array_key_exists( 0, $rows) ? $rows[0] : array();
    }

    function execute($sql) {
        $sql =  $this->setSql($sql);
        return $this->_db->execute($sql);
    //  return mysql_insert_id($this->_db->link);
    }

    function query($sql) {
        $sql =  $this->setSql($sql);
        return $this->_db->query($sql);
    }

    /**
	 * 转义 SQL 语句中使用的字符串中的特殊字符
	 *
	 * @param string $value
	 * @return string
	 */
    function quote($value) {
        return $this->_db->quote($value);
    }

    function debugSql() {
        return $this->_db->getSql();
    }

    function getError() {
        return mysqli_error($this->_db->link);
    }

    function update($table,$data, $condition = null) {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $key => $data) {
            $Item[] = "`$key`='$data'";
        }
        $upStr = implode(',', $Item);
        return $this  -> query("UPDATE  $table SET $upStr WHERE $condition ");

    }
    function insertId() {
        return mysqli_insert_id($this->_db->link);
    }
    function insert($table , $data, $id = true) {
        if (empty ( $data )) {
            return false;
        }

        $kItem = array ();
        $dItem = array ();
        foreach ( $data as $key => $data ) {
            $kItem [] = $key;
            $dItem [] = $data;
        }
        $field = '`' . implode ( '`,`', $kItem ) . '`';
        $values = "'" . implode ( "','", $dItem ) . "'";

        $result = $this->query ( "INSERT INTO  $table ($field) VALUES ($values)" );
        if($id) {
            return $this->insertId();
        }
        return $result;
    }
    function setTableFlag($flag) {
        $this->_tbflag = $flag;
        return $this;
    }

    function setTablePrefix($prefix) {
        $this->_tbprefix = $prefix;
        return $this;

    }

}

class KefuModel {
    private $_db;
    private $_modelName;
    private $_tablePrefix = '';

    function __construct($table='',$tablePrefix='') {

        if(!empty($table))$this -> setTable($table);
        if(!empty($tablePrefix))$this -> setTablePrefix($tablePrefix);
    }



    function setDB(&$db='') {
        $this -> _db = $db;
        return $this;
    }

    function setTable($table) {
        $this -> _modelName = $this->_tablePrefix.$table;
        return $this;
    }

    function setTablePrefix($tablePrefix='') {
        $this->_tablePrefix=$tablePrefix;
        return $this;
    }

    function insert($data,$id=true) {
        if (empty($data)) {
            return false;
        }

        $kItem = array();
        $dItem = array();
        foreach ($data as $key => $data) {
            $kItem[] = $key;
            $dItem[] = $data;
        }
        $field = '`'.implode('`,`', $kItem).'`';
        $values = "'" . implode("','", $dItem) . "'";
        $result=$this -> _db -> query("INSERT INTO  $this->_modelName ($field) VALUES ($values)");
        if($id) {
            return $this->_db->insertId();
        }
        return $result;
    }

    function del($condition) {
        return $this -> _db -> query("DELETE FROM $this->_modelName WHERE " . $condition);
    }

    function delById($id, $keyName = 'id') {
        return $this -> _db -> query("DELETE FROM $this->_modelName WHERE $keyName=" . intval($id) );
    }

    function update($data, $condition = null) {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $key => $data) {
            $Item[] = "`$key`='$data'";
        }
        $upStr = implode(',', $Item);

        $where = $this -> parseWhere($condition);
        return $this -> _db -> query("UPDATE  $this->_modelName SET $upStr $where ");
    }

    function getNum($condition = null) {
        $where = $this -> parseWhere($condition);
        $res = $this -> _db -> query("SELECT COUNT(*)  FROM $this->_modelName $where ");
        $row = $this -> _db -> fetchArray($res);
        $num = $row[0];
        return $num;
    }

    function getOne($condition = null, $colum='*') {
        $where = $this -> parseWhere($condition);
        return $this -> _db -> fetchFirst("SELECT $colum FROM $this->_modelName $where ");
    }

    function getAll($condition = null, $offset=NULL, $size=NULL, $orer=NULL,$colum='*') {
        $where = $this -> parseWhere($condition);

        $limit = '';

        $orerby = '';
        if($orer) {
            $orerby = ' ORDER BY ' . $orer ;
        }

        $sql = "SELECT $colum FROM $this->_modelName $where $orerby %limit%";
        $sql = $this->parsetLimit($sql, $offset, $size);

        return $this -> _db -> query($sql);
    }

    //------------------------------------------------------------------------
    function parseWhere($condition = null) {
        $where = '';
        if ($condition) {
            $where = " WHERE " . $condition;
        }
        return $where;
    }

    function parsetLimit($sql, $offset, $size) {
        $limit = '';
        $offset = intval($offset);
        $size   = intval($size);
        if($size ) {
            $limit = " LIMIT $offset, $size ";
        }
        $sql = str_replace('%limit%', $limit, $sql);
        return $sql;
    }

}


?>
