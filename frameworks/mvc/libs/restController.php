<?php

//准备接收和回应HTTP请求的两个工具
load('processRequest');
load('respondRequest');

//定义CURD四个操作常量
define('METHOD_GET', ProcessRequest::METHOD_GET);
define('METHOD_DELETE', ProcessRequest::METHOD_DELETE);
define('METHOD_POST', ProcessRequest::METHOD_POST);
define('METHOD_PUT', ProcessRequest::METHOD_PUT);

/**
 * REST - 控制器
 */
class restController extends view {
    
    /**
     * 处理HTTP请求的对象
     * @var ProcessRequest
     */
    private $_process;
    
    /**
     * 回应请求的对象
     * @var RespondRequest
     */
    private $_respond;
    
    /**
     * 用来保存GET请求的数据
     * @var array
     */
    private $_get = array();
    
    /**
     * 用来保存POST请求的数据
     * @var array
     */
    private $_post = array();
    
    /**
     * 用来保存PUT请求的数据
     * @var array
     */
    private $_put = array();
    
    /**
     * 媒体格式的别名
     * @var array
     */
    private $_content_types_alias = array(
        'json' => 'application/json',
        'html' => 'text/html',
        'xml' => 'text/xml'
    );
    
    /**
     * 默认accept
     * @var string
     */
    private $_default_accept = 'html';
    
    /**
     * 提供一组正式表达式匹配accept，简化客户端请求类型处理
     * 目前支持html和json，需要支持更多格式请自行添加
     * @var array
     */
    private $_simple_accept_match = array(
        'json' => 'json',
        'html' => 'html',
        'xml' => 'xml'
    );

    /**
     * 构造器
     * 
     * @param string $http_version [optional] 要使用哪个HTTP版本，为空则使用默认值
     * @param string $default_accept [optional] 如果无法知道请求格式（accept），将默认使用哪种格式
     * @param string $default_content_type [optional] 默认使用哪种回应请求的媒体格式
     */
	public function __construct($http_version=null, $default_accept=null, $default_content_type='json') {
		parent::__construct();
        $this->_process = new processRequest($default_accept);
        $this->_respond = new respondRequest($http_version);
        $this->setContentType($default_content_type);
        $this->_setData();
	}
    
    /**
     * 获取请求方法
     * @return string
     */
    public function method() {
        return $this->_process->getMethod();
    }
    
    /**
     * 是否是GET请求
     * 
     * @return boolean
     */
    public function isGet() {
        return ($this->method() == METHOD_GET);
    }
    
    /**
     * 是否是DELETE请求
     * 
     * @return boolean
     */
    public function isDelete() {
        return ($this->method() == METHOD_DELETE);
    }
    
    /**
     * 是否是POST请求
     * 
     * @return boolean
     */
    public function isPost() {
        return ($this->method() == METHOD_POST);
    }
    
    /**
     * 是否是PUT请求
     * 
     * @return boolean
     */
    public function isPut() {
        return ($this->method() == METHOD_PUT);
    }
    
    /**
     * 设置请求数据
     */
    private function _setData() {
        if($this->isGet()) {
            $this->_get = $this->_process->getData();
        } else if($this->isPost()) {
            $this->_post = $this->_process->getData();
        } else if($this->isPut()) {
            $this->_put = $this->_process->getData();
        }
    }
    
    /**
     * 获取GET参数
     * 
     * @param string $key [optional] 为空则返回所有参数
     * @return mixed
     */
    public function get($key=null) {
        if(empty($key)) {
            return $this->_get;
        }
        return (isset($this->_get[$key])) ? $this->_get[$key] : null;
    }
    
    /**
     * 获取POST参数
     * 
     * @param string $key [optional] 为空则返回所有参数
     * @return mixed
     */
    public function post($key=null) {
        if(empty($key)) {
            return $this->_post;
        }
        return (isset($this->_post[$key])) ? $this->_post[$key] : null;
    }
    
    /**
     * 获取PUT参数
     * 
     * @param string $key [optional] 为空则返回所有参数
     * @return mixed
     */
    public function put($key=null) {
        if(empty($key)) {
            return $this->_put;
        }
        return (isset($this->_put[$key])) ? $this->_put[$key] : null;
    }
    
    /**
     * 获取请求头信息
     * 
     * @param string $name [optional] 为空则返回所有参数
     * @return array|string
     */
    public function getHeaders($name=null) {
        return $this->_process->getHeaders($name);
    }
    
    /**
     * 设置回应头信息
     * 
     * @param string $name 配置
     * @param string $value 值
     */
    public function setHeader($name, $value) {
        $this->_respond->setHeader($name, $value);
    }
    
    /**
     * 发送回应结果
     * 
     * @param int $status [optional] 状态码
     * @param mixed $body [optional] 媒体内容
     * 媒体内容可使用PHP的数据类型，比如数组
     * 程序将自动根据$content_type的类型将其进行转换
     * 目前支持的转换格式：
     * PHP数组 => JSON
     * @param string $content_type [optional] 媒体格式，可使用别名
     */
    public function send($status=null, $body='', $content_type='') {
        if(empty($content_type)) {
            $content_type = $this->_respond->getContentType();
        }
        $body = $this->_bodyEncode($body, $content_type);
        //使用媒体格式支持别名
        $content_type = $this->_getContentTypeByAlias($content_type);
        $this->_respond->send($status, $body, $content_type);
        $this->halt();
    }
        
    /**
     * 快速发送一个回应结果，状态和媒体类型都采用预置值
     * 
     * @param string $body [optional] 媒体内容
     */
    public function quickSend($body='') {
        $this->send(null, $body);
        $this->halt();
    }
    
   /**
     * 设置状态码
     * 
     * @param string $status
     */
    public function setStatus($status) {
        $this->_respond->setStatus($status);
    }
    
    /**
     * 设置媒体内容
     * 
     * @param mixed $body
     */
    public function setBody($body) {
        $this->_respond->setBody($body);
    }
    
    /**
     * 设置媒体格式
     * 
     * @param string $content_type 媒体格式，可使用别名
     */
    public function setContentType($content_type) {
        $this->_respond->setContentType(
            $this->_getContentTypeByAlias($content_type)
        );
    }
    
    /**
     * 根据别名获取正确的媒体格式
     * 
     * @param string $alias
     * @return string
     */
    private function _getContentTypeByAlias($alias) {
        return isset( $this->_content_types_alias[$alias] ) ?
            $this->_content_types_alias[$alias] : $alias;
    }
    
    /**
     * 获取媒体格式别名
     * 
     * @return string|false
     */
    private function _getContentTypeAlias($content_type) {
        $alias = array_search($content_type, $this->_content_types_alias);
        if(empty($alias) && isset($this->_content_types_alias[$content_type])) {
            $alias = $content_type;
        }
        return empty($alias) ? false : $alias;
    }
    
    /**
     * 设置媒体编码
     * 
     * @param string $charset
     */
    public function setCharset($charset) {
        $this->_respond->setCharset($charset);
    }
    
    /**
     * 是否关闭TCP长连接
     */
    public function closeConnection() {
        $this->setHeader('Connection', 'close');
    }
        
    /**
     * 一次请求可能有多种格式，它用来确定一种请求格式，好让程序做出回应
     * 
     * @return string
     */
    public function getSimpleAccept() {
        $accept = $this->getHeaders('ACCEPT');
        foreach($this->_simple_accept_match as $exp => $type) {
            if(preg_match('#'.$exp.'#', $accept)) {
                return $type;
            }
        }
        return $this->_default_accept;
    }
    
    /**
     * 解释身份信息
     * 
     * @return array|false 如果成功，会返回一组类似这样的数据：
     * array( method => 验证方式, data => array( key => value, key => value, ... ) )
     */
    public function parseAuthorization() {
        static $data = array();
        if(empty($data)) {
            $authorization = trim( $this->getHeaders('AUTHORIZATION') );
            if(empty($authorization)) {
                return false;
            }
            $match = array();
            if(preg_match('/^\w+/', $authorization, $match)) {
                $data['method'] = strtoupper($match[0]);
            }
            if(preg_match_all('/([\w-]+)=["\']?(.*?)["\']?(?:,|$)\s?/s', $authorization, $match)) {
                foreach($match[1] as $index => $key) {
                    $data['data'][$key] = $match[2][$index];
                }
            }
        }
        return empty($data) ? false : $data;
    }
    
    /**
     * 格式化body
     * 
     * @param mixed $body body数据
     * @param string $content_type 数据格式
     */
    private function _bodyEncode($body, $content_type) {
        //转换数据格式
        $type_alias = $this->_getContentTypeAlias($content_type);
        if($type_alias === false) {
            return $body;
        }
        load('dataParser.factoryParser');
        $parser = factoryParser::getInstance($type_alias, $body);
        if($parser === false) {
            return $body;
        }
        return $parser->encode();
    }
    
    /**
     * 301重定向
     * 
     * @param string $url
     */
    public function redirect($url) {
        $this->setStatus(301);
        $this->setHeader('Location', $url);
        $this->send();
    }
    
    /**
     * 显示结果
     */
    public function display() {
        $this->_respond->send();
        $this->halt();
    }
    
    /**
     * 结束程序
     */
    public function halt() {
        exit;
    }
}