<?php
class Role {
    var $_db;
    var $_uid;
    var $_username;
    var $_fields;
    function __construct($uid, &$db) {
        $this->_uid = $uid;
        $this->setDB ( $db );
    }

    function setDB(&$db) {
        $this->_db = $db;
    }

    function fetchRole($uid) {
        $sql = 'SELECT * FROM @role WHERE uid=\'' . $uid . '\'';
        return $this->_db->fetchFirst ( $sql );
    }

    function fetchGroup($name) {
        $sql = 'SELECT * FROM @role_group WHERE name=\'' . $name . '\'';
        return $this->_db->fetchFirst ( $sql );
    }
    function isSuperAdmin() {
        $row = $this->fetchRole($this->_uid);
        if($row['gid']) {
            $grow = $this->fetchRoleGroup($row['gid']);
            if($grow['priv'] == '*') {
                return true;
            }
        }
        return false;
    }
    function fetchRoleGroup($gid) {
        $sql = 'SELECT * FROM @role_group WHERE id=' . $gid;
        return $this->_db->fetchFirst ( $sql );
    }

    function fetchAllRoleGroup() {
        $sql = 'SELECT * FROM @role_group';
        return $this->_db->fetchAll ( $sql );
    }

    function hasPermission($mod, $act,$param, $uid, $isrole = true, $isgroup = false) {
        $roles = $this->fetchRole ( $uid );
        if ($isrole) {
            $actions = $this->fetchAction ( $mod, $act ,$param);
            if(!$actions['isadmin'])return true;
            if (! empty ( $roles )) {
                $privs = explode ( ',', $roles ['priv'] );
                if (in_array ( $actions ['id'], $privs )) {
                    return true;
                }
            }
            if ($isgroup) {
                if (! empty ( $roles ['gid'] )) {
                    $groupactions = $this->fetchRoleGroup ( $roles ['gid'] );
                    if($groupactions ['priv'] == '*')return true;
                    $privs = explode ( ',', $groupactions ['priv'] );
                    if (in_array ( $actions ['id'], $privs )) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 判断访问的方法是否存在于当前角色组下的所有授权动作中
     *
     * @param [type] $mod 控制器
     * @param [type] $act 控制器下的方法
     * @param [type] $name 角色组名称
     * @param string $p 控制器下的方法参数
     * @return boolean
     */
    function hasGroupPermission($mod, $act, $name ,$p = '') {

       // 获取当前角色组信息
        $groups = $this-> _db ->fetchFirst('SELECT * FROM @role_group WHERE name = \''.$name.'\'');
        
        if (!empty($groups)) {
            // 获取当前控制器下的方法信息
            $action = $this->fetchAction ( $mod, $act ,$p );

            // 判断当前控制器下的方法是否存在
            if (! empty ( $action )) {
                // 获取当前角色组下的所有授权动作ID
                $privs = explode ( ',', $groups ['priv'] );

                // 如果当前角色组下的授权动作信息为*,表示当前角色组允许访问所有控制器下的方法
                if($groups ['priv'] == '*') return true;

                // 判断访问的方法是否存在于当前角色组下的所有授权动作中
                if (in_array ( $action ['id'], $privs )) {
                    // 允许访问
                    return true;
                }
            }
        }

        // 不允许访问
        return false;

    }
    
    function hasMenuPermission($mod, $act, $submenuid ,$p = '') {
        $menus = $this-> _db ->fetchFirst('SELECT * FROM @role_menu WHERE id = \''.$submenuid.'\'');
        if (!empty($menus)) {
            $actions = $this->fetchAction ( $mod, $act ,$p );
            if (! empty ( $actions )) {
                $actionarr = explode ( ',', $menus ['action'] );
                if($menus ['action'] == '*')return true;
                if (in_array ( $actions ['id'], $actionarr )) {
                    return true;
                }
            }
        }
        return false;
    }

    function checkRepeatAction($mod, $act,$menuid='',$param='',$p=false){
        $action = $this->fetchAction ( $mod, $act,$param );
//        $sql = "SELECT 1 FROM @role_menu WHERE action REGEXP '^{$action['id']}$|,{$action['id']}|{$action['id']},'";
        //修正某些模块在所属菜单下无法显示的问题
        //原本的正则表达式查询存在缺陷，比如匹配id=20的数据，任何包含20的id都会被匹配（120,200,220等）
        //但程序的原意不是这样，应该是要准确匹配id，否则一些子菜单无法被显示
        //实际上也不用使用正则，mysql提供find_in_set函数检测逗号分隔的数据
        $sql = "SELECT 1 FROM @role_menu WHERE FIND_IN_SET('{$action['id']}', action)";
        if(!empty($menuid)) $sql .=  'AND id!='.$menuid;
        if($p){
            $sql .=  ' AND parentid!=0';
        } else {
            $sql .=  ' AND parentid=0';
        }
        return $this->_db->fetchFirst($sql);
    }

    /**
     * 获取当前动作详情
     *
     * @param [type] $mod 控制器
     * @param [type] $act 控制器下的方法
     * @param string $p 控制器下的方法参数
     * @return void
     */
    function fetchAction($mod, $act ,$p='') {
        return $this->_db->fetchFirst ( 'SELECT * FROM @role_action WHERE module =\'' . $mod . '\' AND action=\'' . $act . '\' AND param=\''.$p.'\'' );
    }

    function hasRole($uid, $aid) {
        return $this->_db->fetchFirst ( 'SELECT * FROM @role WHERE uid =\'' . $uid . '\' AND aid=' . $aid );
    }
    function hasPermissionByAction() {
    }
} 
