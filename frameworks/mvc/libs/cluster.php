<?php
/**
 * Project:    Simple mvc for apps
 * File:       cluster.php
 *
 * 集群操作类
 *
 * 每个集群可以包含多个主节点（读写）和多个备份节点（只读）
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Libaray
 * @version 1.0.0
 */

/**
 * abstract关键字用于定义抽象类
 */
abstract class cluster {
	
	/**
	 * 节点
	 */
	private $nodes = array();
	
	/**
	 * 节点搜寻位移
	 */
	private $pos = 0;
	
	/**
	 * 有效连接句柄
	 */
	private $links = array();
	
	/**
	 * 活动的连接句柄
	 */
	public $link = null;
	
	/**
	 * 集群使用的字符编码
	 */
	private $charset = 'utf8';
	
	/**
	 * 设置集群的字符编码
	 *
	 * @param string $charset 默认utf8
	 * @return object cluster
	 */
	public function setCharSet($charset = 'utf8') {
		$this->charset = $charset;
	}
	
	/**
	 * 添加节点
	 *
	 * @param array $params 节点参数
	 * 例子：array('host'=>'localhost:3306', 'user'=>'root', 'password'=>'123456', 'charset'=>'utf8', 'database'=>'app_db_name', 'type'=>'master');
	 * @return object cluster
	 */
	public function add($node = array()) {
		$this->nodes[] = $node;
		return $this;
	}
	
	/**
	 * 实现读写分离, 动态连接节点
	 *
	 * @access private
	 * @param string $id 读写操作判断. 主节点支持读写, 备份节点只支持读
	 * @return array
	 * @TODO 有待完善, 再补充。@heyonzgen 2023.6.30 目前读写分离功能没有写完，所以是不支持在框架内实现读写分类的。只支持主节点master，不支持备份节点的。
	 */
	public function find($type = 'master') {

		if (!is_array($this->nodes)) {
			throw new Exception('Please call function append() first.');
		}
		// 如果已经连接, 直接使用
		if (isset($this->links[$type]) && is_resource($this->links[$type])) {
			$this->link = $this->links[$type];
			return true;
		}
		
		// 保存当前的坐标
		$curPos = $this->pos;
		// 第二轮筛选
		$research = false;
		// 逐个排查
		while (true) {
			
			// 循环结束
			if (count($this->nodes) <= $this->pos) {
				if ($research) {
					// 第二轮筛选失败
					throw new Exception('Can\'t find any active link.');
				}
				else {
					// 坐标重新寻址
					$research = true;
					$this->pos = 0;
				}
			}
			
			/**
			 * 如果当前坐标大于实际的坐标, 那么代表已经循环一圈
			 * 或者已经找到指定的主、从连接
			 * 以上两种情况可以使用当前坐标的参数连接数据库
			 */
			if ($research || ($this->nodes[$this->pos]['type'] == $type)) {
				$node = $this->nodes[$this->pos];
				
				// 参数分解
				$host     = $node['host'];
				$user     = $node['user'];
				$password = $node['password'];
				$database = $node['database'];
				// 连接数据库
			
				$this->link = $this->connect($host, $user, $password, $database, $this->charset);

				// 检查连接
				if ($this->link) {
					
					// 保存当前有效的连接句柄
					$this->links[$type] = $this->link;
					// 坐标下移
					++ $this->pos;
					//print_r($params);
					// 返回
					return true;
				}

			}
			
			// 坐标下移
			++$this->pos;
		}
	}
	
	/**
	 * 执行语句, 实现读写分离
	 *
	 * @param string $sql
	 */
	public function query($sql) {
		// $linkType = preg_match("/^(\s*)select/i", $sql) ? 'slave' : 'master';

		// @heyonzgen 2023.6.30 目前读写分离功能没有写完，所以是不支持在框架内实现读写分类的。只支持主节点master，不支持备份节点的。
		$linkType = 'master';

		// 根据语句判断读写操作
		if (!$this->find($linkType)) {
			throw new Exception('找不到可操作的节点.');
		}
	}
	
	/**
	 * 数据库连接函数, 所有继承的数据库类都必须实现这个方法
	 *
	 * @abstract
	 * @access public
	 * @param string $host 连接服务器
	 * @param string $user 连接用户
	 * @param string $password 用户密码
	 * @param string $database 数据库名称
	 * @param string $charset 数据库使用编码
	 * @return mixed
	 */
	abstract public function connect($host, $user, $password, $database, $charset = 'utf8');
}

/**
 * 这个类可以用于 model::set() , 例如可以做 update set xxx=xxx+1
 * 用法是
 * $model->set(
 *    'a' => new dbexpression('a+1')
 * );
 * 
 */
class dbexpression {
    private $_expression;
    
    public function __construct($expression) {
        $this->_expression = $expression;
    }
    
    public function __toString() {
        return $this->_expression;
    }
    
}