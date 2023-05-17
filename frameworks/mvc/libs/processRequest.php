<?php

load('requestHeader');

/**
 * 处理HTTP请求
 * 你可以在这里获取相应的请求信息
 */
class processRequest {
    
    //一组方法列表，目前支持四种常见的CURD操作方法
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    /**
     * 构造器
     * 
     * @param string $default_accept [optional] 默认媒体格式
     */
    public function __construct() {
        requestHeader::init();
    }
    
    /**
     * 获取头信息
     * 
     * @param string $name [optional] 如果提供此项，则返回相应的头信息，否则返回全部
     * @return string
     */
    public function getHeaders($name=null) {
        return requestHeader::getHeaders($name);
    }
    
    /**
     * 获取请求方法
     * 
     * @return string
     */
    public function getMethod() {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * 获取请求数据
     * 
     * @staticvar array $data
     * @return string
     */
    public function getData() {
        static $data = array();
        switch ($this->getMethod()) {
            case self::METHOD_GET:
                $data = $_GET;
                break;
            case self::METHOD_POST:
                $data = $_POST;
                break;
            case self::METHOD_PUT:
                parse_str(file_get_contents('php://input'), $put_vars);
                $data = $put_vars;
                break;
        }
        return $data;
    }
}