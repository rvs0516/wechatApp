<?php
/**
 * Project:    Simple mvc for apps
 * File:       kernel.php
 *
 * 多集群操作类
 * 
 * 可以在多个集群之间自由切换, 完成多个应用系统的数据汇总统计分析. 
 * 每个集群可以包含多个主节点（读写）和多个备份节点（只读）
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Libaray
 * @version 1.0.0
 */

class kernel {
    /**
     * 集群组配置
     */
    private $clusters = array();
    
    /**
     * 添加集群
     * 
     * 传入参数：
     * array(
     *  'engine'  => 'mysql',   集群使用的引擎
     *  'charset' => 'utf8',    集群使用的字符编码
     *  'nodes'   => array(
     *      array('host'=>'localhost:3306', 'user'=>'root', 'password'=>'123456', 'charset'=>'utf8', 'database'=>'app_db_name', 'type'=>'master'),
     *      // 其它节点参数……
     *  )
     * )
     * 
     * @param string $name 集群别名
     * @param array $ci 集群配置参数
     * @return object kernel
     */
    public function add($name, $ci = array()) {
		$engine = $ci['engine']; // $ci['engine'] = "mysql";
		$cluster = getInstance($engine);  // 使用function.php函数库中的实例化对象函数getInstance，实例化对象mysql，即引用mvc/lib/mysql.php
		// 设置字符编码
		if (isset($ci['charset'])) {
			$cluster->setCharSet($ci['charset']);
		}
        // 保存集群节点信息，即数据库服务器信息
		$nodes = $ci['nodes'];
		// array(
		// 	'engine'  => 'mysql',
		// 	'charset' => 'utf8',
		// 	'nodes'   => array(
		// 		array('host'=>'127.0.0.1', 'user'=>'root', 'password'=>'root', 'database'=>DATABASES_PREFIX.'_corpWeixin', 'type'=>'master'),
		// 	),
		// ),
		foreach ($nodes as $node) {
			$cluster->add($node); // $node = array('host'=>'127.0.0.1', 'user'=>'root', 'password'=>'root', 'database'=>DATABASES_PREFIX.'_corpWeixin', 'type'=>'master');
		}
		$this->clusters[$name] = $cluster;
	}
	
	/**
	 * 切换集群
	 *
	 * 返回对应别名的集群对象, 失败返回null
	 *
	 * @param string $name 没有提供别名使用第一个集群
	 * @return mixed
	 */
	public function find($name = null) {
		if (empty($name)) {
			reset($this->clusters); // 重置数组键值指针
			return current($this->clusters); // 获取当前数组指针指向的值，重置指针后，获取的是第一个键的值
		}
		elseif (isset($this->clusters[$name])) {
			return $this->clusters[$name];
		}
		else {
			return null;
		}
    }
}