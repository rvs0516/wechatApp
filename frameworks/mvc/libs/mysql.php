<?php
/**
 * Project:    Simple mvc for apps
 * File:       mysql.php
 *
 * MYSQL数据库操作类
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Libaray
 * @version 1.0.0
 */

include 'cluster.php';

/**继承集群类*/
class mysql extends cluster {
	
	/**
	 * last insert id
	 */
	private $last_insert_id = null;
	
	/**
	 * last query sql
	 */
	private $last_sql = null;
	
	/**
	 * last affected rows
	 */
	private $last_affected_rows = null;
	
	/**
	 * query handler
	 */
	private $query = null;
    
    /**
     * 查询统计
     */
    private $query_count = 0;
	
	/**
	 * 构造函数, 实例化数据库连接
	 *
	 * @param array $config
	 * @return object
	 */
	public function __construct() {
		
	}
	
	/**
	 * 连接数据库
	 *
	 * @param string $host 连接服务器
	 * @param string $user 连接用户
	 * @param string $password 用户密码
	 * @param string $database 数据库名称
	 * @param string $charset 数据库使用编码
	 * @return boolean
	 */
	public function connect($host, $user, $password, $database, $charset='utf8') {
		
		// 连接服务器
	
		$link = mysql_connect($host, $user, $password, true, 131072);
		
		if (!is_resource($link)) {
			throw new Exception('Remote server is not available.');
		}
		
		// 版本设置
		
		$version = mysql_get_server_info($link);
		
		if ($version >= '4.1') {
			mysql_query('SET NAMES ' . $charset, $link);
		}
		if ($version > '5.0.1') {
			
			mysql_query('SET sql_mode=\'\'', $link);
			
		}
		
		// 选择需要操作的数据库
	   
		$selected = mysql_select_db($database, $link);
		
		
		if (!$selected) {
			
			throw new Exception('Can\'t use ' . $database);
		}
		return $link;
	}
	
	/**
	 * 执行一条无返回值的SQL
	 *
	 * 这里用来执行update、insert、delete等无返回记录集的SQL语句
	 * 在执行的时候把自增id和影响的行数保存下来, 避免应用之间的影响
	 *
	 * @param string $sql
	 * @return void
	 */
	public function execute($sql) {
		$this->query($sql);
		$this->last_insert_id = mysql_insert_id($this->link);
		$this->last_affected_rows = mysql_affected_rows($this->link);
	}

	/**
	 * 查询记录集
	 * 
	 * 成功以数组方式返回查询记录集, 失败返回null
	 *
	 * @param string $sql
	 * @return array|null
	 */
	public function query($sql) {
		// 自动判断读写设置
		parent::query($sql);
		
		// 记录最后一条sql
		$this->last_sql = $sql;
		
		// 释放当前的查询句柄
		$this->free();
		
		// 连接超时
		
		if (!is_resource($this->link)) {
			throw new Exception('Connection timeout.');
		}
		// 执行
    
		$this->query = mysql_query($sql, $this->link);
		$this->query_count++;	
		
		if (is_resource($this->query)) {	
			$data = array();
			while ($row = mysql_fetch_array($this->query, MYSQL_ASSOC)) {
				$data[] = $row;
			}
			// 避免记录集过多浪费内存, 先释放查询句柄
			$this->free();
			return $data;
		}
		else {
			return $this->query;
		}
	}
	
	/**
	 * 拼凑INSERT INTO/UPDATE的字段和字段值
	 *
	 * @param $data array 字段与值的键值对数组, array('id'=>1, 'title'=>'this is title.');
	 * @return string 返回拼凑好的语句
	 * @todo 未判断是否自动转义, $value值可能存在危险
	 */
	public function parseData($data=array()) {
		$token = array();
		foreach ($data as $field=>$value) {
                    if($value instanceof dbexpression) {
                        $token[] = '`' . $field . '` = ' . $value;
                    } else {
			$token[] = '`' . $field . '` = \'' . $this->quote($value) . '\'';
                    }
		}
		return implode(', ', $token);
	}
	
	/**
	 * 转义 SQL 语句中使用的字符串中的特殊字符
	 *
	 * @param string $value
	 * @return string
	 */
	public function quote($value) {
		return mysql_real_escape_string($value, $this->link);
	}
	
	/**
	 * 获取最后一次自增编号
	 *
	 * @return integer
	 */
	public function insertId() {
		return $this->last_insert_id;
	}
	
	/**
	 * 返回最后一次影响的记录数
	 *
	 * @return integer
	 */
	public function affectedRows() {
		return $this->last_affected_rows;
	}
	
	/**
	 * 获取最后一条执行的SQL
	 *
	 * @return string
	 */
	public function getSql() {
		return $this->last_sql;
	}
	
	/**
	 * 获取活动连接句柄的版本号
	 *
	 * @return string
	 */
	public function version() {
		return is_resource($this->link) ? mysql_get_server_info($this->link) : null;
	}
	
	/**
	 * 释放当前的查询句柄, 一般不用手动执行
	 */
	public function free() {
		if (is_resource($this->query)) {
			mysql_free_result($this->query);
			$this->query = null;
		}
	}
	
	/**
	 * 断开活动的连接句柄, 一般不用手动执行
	 */
	public function close() {
		if (is_resource($this->link)) {
			mysql_close($this->link);
			$this->link = null;
		}
	}
	
	/**
	 * 析构函数, 释放内存, 一般不用手动执行
	 *
	 * 释放当前的查询句柄和断开活动的连接句柄
	 */
	public function __desctruct() {
		$this->free();
		$this->close();
	}
    
    /**
     * 获取查询总数
     * 
     * @return int
     */
    public function getQueryCount() {
        return $this->query_count;
    }
    
    /**
     * 获取上一次查询的错误信息
     * 
     * @return string
     */
    public function error() {
        return mysql_error();
    }
}