<?php

/**
 * 处理HTTP请求的头信息
 * 初始化它后可以用它来读取相应的头信息
 */
class requestHeader {
    
    /**
     * 存放头信息
     * @var array
     */
    private static $_header = array();
    
    /**
     * 初始化标记
     * @var string
     */
    private static $_init = false;
    
    /**
     * 单例
     */
    private function __construct() {
        ;
    }
    
    /**
     * 初始化数据
     */
    public static function init() {
        if(!self::$_init) {
            self::_praseAllHeaders();
            self::$_init = true;
        }
    }
    
    /**
     * 解释HTTP头参数
     * 所有参数名的'_'号都会被转为'-'，字母转为大写
     * 然后会被放到self::$_header中
     */
    private static function _praseAllHeaders() {
        $header = array();
        //这是apache特有的函数，可以很方便取到数据
        if(function_exists('getallheaders')) {
            $header = getallheaders();
        }
        //其它服务器，在$_SERVER里取，有点麻烦
        else {
            foreach($_SERVER as $name => $value) {
                if(strcasecmp( substr($name, 0, 5), 'HTTP_' ) == 0) {
                    $name = substr($name, 5);
                    $header[$name] = $value;
                }
            }
        }
        //格式化参数
        foreach($header as $name => $value) {
            $name = strtoupper( str_replace('_', '-', $name) );
            self::$_header[$name] = $value;
        }
    }
    
    /**
     * 获取头信息
     * 
     * @param string $name [optional] 如果提供此项，则返回相应的头信息，否则返回全部
     * @return array|string
     */
    public static function getHeaders($name=null) {
        return empty($name) ? self::$_header : self::$_header[$name];
    }
}