<?php
/**
 * Project:    Simple mvc for apps
 * File:       mysql.php
 *
 * 功能模块基类
 *
 * 所有逻辑功能模块的类都继承这个类, 这个类本身实现单表模型增、删、改、查的基本方法
 * 比如：
 * 1. 查询一个表的一条记录或者列表, 可以很方便的使用本类的get和select方法实现
 * 2. 需要删除记录, 需指定where参数
 * 3. 为指定primary key的单表模型提供getById、setById、delById查、改、删便捷函数
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Mvc
 * @version 1.0.0
 */

class model {
	
	/**
	 * 当前单表模型的表名
	 */
	private $model_name;
	
	/**
	 * 默认主键名称
	 */
	private $primary_key = 'id';
	
	/**
	 * 数据操作对象
	 *
	 * @static
	 * @access public
	 */
	public static $engine = null;
	
	/**
	 * 单例模式
	 * @static
	 */
	private static $instance = null;
    
    /**
     * 占位符替换值
     * 
     * self::printf中使用preg_replace_callback方法回调self::printfReplace方法处理
     * 但回调不能传参数（PHP5.3以下不支持真正的匿名函数），所以只能通过设置属性传递参数
     * @var array
     */
    private static $_printf_args = array();

    /**
     * 占位符索引
     * @var int
     */
    private static $_printf_index = 0;

    /**
	 * 配置集群信息
	 *
	 * @static
	 * @access public
	 * @param array $params
	 */
	public static function getInstance($params = array()) {
		if (empty(self::$instance)) {
			self::$instance = getInstance('kernel'); // 使用function.php函数库中的实例化对象函数getInstance，实例化对象kernel，即引用mvc/lib/kernel.php
			foreach ($params as $name=> $ci) {
				self::$instance->add($name, $ci);
			}
			self::change(null);
		}
		else {
			throw new Exception('不能重复调用此方法.');
		}
	}
	
	/**
	 * 选择需要使用的集群
	 *
	 * @static
	 * @access public
	 * @param array $params
	 */
	public static function change($name) {
		self::$engine = self::$instance->find($name);

		// echo "<pre>";
		// var_dump(self::$engine instanceof cluster);
		// exit;

		if (self::$engine instanceof cluster) { // instanceof 用于确定一个 PHP 变量是否属于某一类 class 的实例
			
			// self::$engine->find('slave');
			self::$engine->find('master');
		}
		else {
			throw new Exception('找不到名字' . $name . '的节点.');
		}
	}
	
	/**
	 * 提供常规SQL查询
	 *
	 * 以数组的方式返回查询的结果, 如果无结果则返回成功与否
	 *
	 * @static
	 * @access public
	 * @param string $sql
     * @param array $printf_args [optional] 占位符替换值
     * @param string $field_key 使用某字段的值作为数组的键名
	 * @return void
	 */
	public static function getBySql($sql, $printf_args=array(), $field_key='') {
		//todo: 可以考虑把表结构写入缓存文件里
        $sql = self::printf($sql, $printf_args);
		//判断sql类型
		if(preg_match ("/^(\s*)select/i", $sql)){
			$data = self::$engine->query($sql);
	        if (is_array($data)) {
                if($field_key != '') {
                    return self::setKeyByField($data, $field_key);
                }
				return $data;
			}
		}else {
			self::$engine->execute($sql);
			return self::$engine->affectedRows();
	    }
		return false;
	}
    
    /**
     * 使用某个字段的值作为数据库数据数组的键名
     * 
     * @param arrray $data
     * @param string $field
     * @retur array
     */
    public static function setKeyByField(array $data, $field) {
        $_data = array();
        foreach($data as $row) {
            if(!isset($row[$field])) {
                return $data;
            }
            $_data[ $row[$field] ] = $row;
        }
        return $_data;
    }


    /**
	 * 字符串转义
	 *
	 * @param string $value
	 * @return string
	 */
	public static function quote($str) {
		if (method_exists(self::$engine, 'quote')) {
			return self::$engine->quote($str);
		}
		else {
			return $str;
		}
	}
	
	/**
	 * 实例化单表模型
	 *
	 * @param string $tbname 表名
	 */
	public function __construct($tbname) {
		if (empty(self::$engine)) {
			throw new Exception('找不到可用的集群对象.');
		}
		$this->model_name = $tbname; // 数据库名称
	}
	
	public function setPrimaryKey($pkey) {
		$this->primary_key = $pkey;
	}
	
	/**
	 * 获取单条记录
	 *
	 * 无记录返回null
	 *
	 * @param string $where 查询条件
     * @param array $printf_args [optional] 占位符替换值
	 * @return array|null
	 */
	public function get($where = null, $printf_args=array()) {
		$sql = 'SELECT * FROM `' . $this->model_name . '` WHERE '
                . self::printf($where, $printf_args) . ' LIMIT 1';
		// echo $sql;exit;
		$res = self::getBySql($sql);
		if (!empty($res)) {
			return $res[0];
		}
		return false;
	}
	
	/**
	 * 获取记录集
	 *
	 * 以数组方式返回结果集, 无记录返回null
	 *
	 * @param string $fields 查询字段
	 * @param string $where 查询条件
	 * @param string $groupby 聚合条件
	 * @param string $oderby 排序条件
	 * @param string $limit 查询条数
	 * @return array|null
	 */
	public function select($fields = '*', $where = null, $groupby = null, $orderby = null, $limit = null) {
		$sql = 'SELECT ' . $fields . ' FROM `' . $this->model_name . '`';
		if (!empty($where)) {
			$sql .= ' WHERE ' . $where;
		}
		if (!empty($groupby)) {
			$sql .= ' GROUP BY ' . $groupby;
		}
		if (!empty($orderby)) {
			$sql .= ' ORDER BY ' . $orderby;
		}
		if (!empty($limit)) {
			$sql .= ' LIMIT ' . $limit;
		}
		//echo $sql;exit;
		return self::getBySql($sql);
	}
	
	/**
	 * 添加或者修改
	 *
	 * 1. 提供第二个参数的表示更新, 返回影响的条数;
	 * 2. 相反表示新增记录, 返回自增编号.
	 *
	 * @param array $data
	 * @param string $where 更新条件, 如果新增记录请不要提供这个参数
     * @param array $printf_args [optional] 占位符替换值
	 * @return integer
	 */
	public function set($data = array(), $where = null, $printf_args=array()) {
		$columns_value = self::$engine->parseData($data);
		if (empty($where)) {
			$sql = 'INSERT INTO `' . $this->model_name . '` SET ' . $columns_value;

			// echo $sql;exit;
			// error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "sql: ". $sql. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/oss_setFollowCustomers_".date('Ymd').".txt");

			self::$engine->execute($sql);
			return self::$engine->insertId();
		}
		else {
			$sql = 'UPDATE `' . $this->model_name . '` SET ' . $columns_value
                    . ' WHERE ' . $this->printf($where, $printf_args);
			
			// error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "sql: ". $sql. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/oss_updateCustomerData_".date('Ymd').".txt");
			
			// echo $sql;exit;
			self::$engine->execute($sql);
			return self::$engine->affectedRows();
		}
	}
	
	/**
	 * 删除记录
	 *
	 * 返回删除的记录数
	 *
	 * Usage: $m->delete("`id` = '1'");
	 *
	 * @param string $where
     * @param array $printf_args [optional] 占位符替换值
	 * @return integer
	 */
	public function delete($where, $printf_args = array()) {
		$sql = 'DELETE FROM `' . $this->model_name . '` WHERE ' . $this->printf($where, $printf_args);
		self::$engine->execute($sql);
		return self::$engine->affectedRows();
	}
	
	/**
	 * 根据主键查询记录
	 *
	 * 成功返回记录, 失败返回null
	 *
	 * Usage: $m->getById(1);
	 * 产生SQL：SELECT * FROM `$this->model_name` WHERE `$this->primary_key` = '1'
	 *
	 * @param string $pkid
	 * @return void
	 */
	public function getById($pkid) {
		$where = '`' . $this->primary_key . '` = \'' . $this->quote($pkid) . '\'';
		return $this->get($where);
	}
	
	/**
	 * 根据主键更新记录
	 *
	 * Usage: $m->setById(1, array('username' => 'admin'));
	 * 产生SQL：UPDATE `$this->model_name` SET `username`='admin' WHERE `$this->primary_key` = '1'
	 *
	 * @param string $pkid
	 */
	public function setById($pkid, $data = array()) {
		$where = '`' . $this->primary_key . '` = \'' . $this->quote($pkid) . '\'';
		return $this->set($data, $where);
	}
	
	/**
	 * 根据主键删除记录
	 *
	 * 成功返回true, 失败返回false
	 *
	 * Usage: $m->delById(1);
	 * 产生SQL：DELETE FROM `$this->model_name` WHERE `$this->primary_key` = '1'
	 *
	 * @param string $pkid
	 * @return boolean
	 */
	public function delById($pkid) {
		$where = '`' . $this->primary_key . '` = \'' . $this->quote($pkid) . '\'';
		return $this->delete($where) > 0;
	}
    
    /**
     * 使用占位符的形式格式化sql语句
     * 
     * 能更方便的进行防SQL注入：占位符的替换值会被过滤
     * 类似原生的printf方法，占位符分别有如下几种
     * 参数     替换为          接受值
     * %s       字符串          int|string
     * %d       数字            int|string
     * %n       IN('1', '2')    array
     * %i       不做任何处理     mixed
     * 
     * @param string $sql
     * @param array $args
     * @return string $sql
     */
    public function printf($sql, array $args = array()) {
        if(!is_array($args) || empty($args)) {
            return $sql;
        }
        self::$_printf_args = $args;
        self::$_printf_index = 0;
        return preg_replace_callback('/%([sdin])/i', 'model::printfReplace', $sql);
    }
    
    /**
     * 替换printf方法匹配的占位符
     * 
     * @param array $match
     * @return string $replace
     */
    public static function printfReplace($match) {
        if(!isset(self::$_printf_args[ self::$_printf_index ])) {
            return $match[0];
        }
        $arg = self::$_printf_args[ self::$_printf_index ];
        $str = '';
        switch ($match[1]) {
            //字符串
            case 's':
                $str = '\'' . self::quote($arg) . '\'';
                break;
            //数字
            case 'd':
                $str = intval($arg);
                break;
            //in(1, 2)
            case 'n':
                if(is_array($arg)) {
                    $list = array();
                    foreach($arg as $value) {
                        $list[] = '\'' . self::quote($value) . '\'';
                    }
                    $str = implode(',', $list);
                } else {
                    $str = $arg;
                }
                $str = 'IN(' . $str . ')';
                break;
            //直接插入
            case 'i':
                $str = $arg;
            default:
                break;
        }
        self::$_printf_index++;
        return $str;
    }
    
    /**
     * 获取查询总数
     * 
     * @return int
     */
    public function getQueryCount() {
        return self::$engine->getQueryCount();
    }
	
    /**
     * 获取上一次查询的错误信息
     * 
     * @return string
     */
    public function error() {
		return self::$engine->error();
	}
}