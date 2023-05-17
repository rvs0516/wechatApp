<?php
/**
 * Project:    Simple mvc for apps
 * File:       session.php
 *
 * 封装session操作类
 * 每个应用的session全部存放在指定命名空间的二维数组里面
 * 如果方法支持操作, 那么你可以这样：session::set('a')::set('b', 'set-value'); 这里表示删除a并且设置b的值为"set-value"
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Mvc
 * @version 1.0.0
 */

class session {
	
	/**
	 * session命名空间
	 * @static
	 */
	private static $namespace;
	
	/**
	 * 设置命名空间名称并且启动session
	 *
	 * @access public
	 * @static
	 * @param string $namespace
	 */
	public static function start($namespace) {
		self::$namespace = $namespace;
		if(!isset($_SESSION)) {
		    session_start();
		}
	}
	
	/**
	 * 获取session
	 *
	 * 传入session名称返回session的值, 如果session不存在则返回null
	 *
	 * @access public
	 * @static
	 * @param string $name
	 * @return string
	 */
	public static function get($name) {
		return isset($_SESSION[self::$namespace][$name]) ? $_SESSION[self::$namespace][$name] : null;
	}
	
	/**
	 * 设置session
	 *
	 * @access public
	 * @static
	 * @param string $name session名称
	 * @param string $value session值, 如果传入的值是null, 代表删除session
	 */
	public static function set($name, $value=null) {
		if(is_array($name)) {
			foreach($name as $key => $val) {
				$_SESSION[self::$namespace][$key] = $val;
			}	
		}
		else if(is_null($value)) {
			self::delete($name);
		}
		else {
			$_SESSION[self::$namespace][$name] = $value;
		}
	}
    
	/**
	 * 删除session
	 *
	 * @access public
	 * @static
	 * @param string $name
	 */
	public static function delete($name) {
		if(is_array($name)) {
			foreach($name as $key => $val) {
				unset($_SESSION[self::$namespace][$key]);
			}
		}
		else {
			unset($_SESSION[self::$namespace][$name]);
		}
	}
	
	/**
	 * 清除session
	 *
	 * @access public
	 * @static
	 */
	public static function destroy() {
		unset($_SESSION[self::$namespace]);
	}
}