<?php
/**
 * Project:    Simple mvc for apps
 * File:       cookie.php
 *
 * 封装Cookies操作类
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Mvc
 * @version 1.0.0
 */

class cookie {
	
	/**
	 * cookie命名空间
	 * @static
	 */
	private static $namespace;	

	/**
	 * 设置命名空间名称
	 *
	 * @access public
	 * @static
	 * @param string $namespace
	 */
	public static function start($namespace) {
		self::$namespace = $namespace;
	}
	
	/**
	 * 获取Cookie
	 *
	 * 传入cookie名称返回cookie的值, 如果cookie不存在则返回null
	 *
	 * @access public
	 * @static
	 * @param string $name
	 * @return string
	 */
	public static function get($name) {
		return isset($_COOKIE[self::$namespace.$name]) ? unserialize(base64_decode($_COOKIE[self::$namespace.$name])) : null;
	}
	
	/**
	 * 设置Cookie
	 *
	 * @access public
	 * @static
	 * @param string $name cookie名称
	 * @param string $value cookie值, 如果传入的值为空, 那么本方法代表删除cookie
	 * @param integer $expire 超时时间, 单位是秒
	 * @param string $page cookie保存目录
	 * @param string $domain cookie作用域, 只在提供的网址生效
	 */
	public static function set($name, $value=null, $expire=null, $path=null, $domain=null) {
		if(is_null($path)) {
			$path = C('COOKIE_PATH');
		}		
		if(is_null($domain)) {
			$domain = C('COOKIE_DOMAIN');
		}
        $expire = empty($expire) ? 0 : time() + C('COOKIE_EXPIRE');
        $value  = base64_encode(serialize($value));
        setcookie(self::$namespace.$name, $value, $expire, $path, $domain);
        $_COOKIE[self::$namespace.$name] = $value;
	}
	
	/**
	 * 删除Cookie
	 *
	 * @access public
	 * @static
	 * @param string $name
	 */
	public static function delete($name) {
		self::set($name, '', -3600);
		unset($_COOKIE[self::$namespace.$name]);
	}
	
	/**
	 * 清除当前域名/路径下的cookie值
	 *
	 * @access public
	 * @static
	 */
	public static function destroy() {
		// 了解一下是否需要像delete方法那样子先设置超时
		unset($_COOKIE);
	}
}