<?php

/**
 * 回应请求
 * 提供基本的报头处理
 */
class respondRequest {
    
    /**
     * HTTP版本
     * @var string
     */
    private $_http_version = '1.1';
    
    /**
     * 状态码
     * @var type 
     */
    private $_status = 200;
    
    /**
     * 媒体内容
     * @var string
     */
    private $_body;
    
    /**
     * 媒体类型
     * @var string
     * @link http://zh.wikipedia.org/wiki/%E4%BA%92%E8%81%94%E7%BD%91%E5%AA%92%E4%BD%93%E7%B1%BB%E5%9E%8B
     */
    private $_content_type = 'text/html';
    
    /**
     * 媒体编码
     * @var string
     */
    private $_charset = 'utf-8';
    
    /**
     * HTTP头设置
     * @var array
     * @link http://kb.cnblogs.com/page/92320/
     */
    private $_header = array();

    /**
     * 状态码消息
     * @var array
     * @link http://zh.wikipedia.org/wiki/HTTP%E7%8A%B6%E6%80%81%E7%A0%81
     */
    private $_status_message = array(
        //请求消息
        100 => 'Continue',
        101 => 'Switching Protocols',
        //成功
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        //重定向
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        //请求错误
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        //服务器错误
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );
    
    /**
     * 构造器
     * 
     * @param string $http_version [optional] 要使用哪个HTTP版本，为空则使用默认值
     */
    public function __construct($http_version=null) {
        if(!is_null($http_version)) {
            $this->_http_version = $http_version;
        }
    }
    
    /**
     * 发送回应结果
     * 
     * @param int $status [optional] 状态码
     * @param mixed $body [optional] 媒体内容
     * @param string $content_type [optional] 媒体格式
     */
    public function send($status=null, $body='', $content_type='') {
        if(!is_null($status)) {
            $this->setStatus($status);
        }
        if(!empty($body)) {
            $this->setBody($body);
        }
        if(!empty($content_type)) {
            $this->setContentType($content_type);
        }
        //HTTP状态行
        $message = $this->getStatusMessage();
        header("HTTP/{$this->_http_version} {$this->_status} {$message}");
        //HTTP其它头信息
        $this->setHeader("Content-Type", "{$this->_content_type};charset={$this->_charset}");
        foreach($this->_header as $name => $value) {
            header("{$name}:{$value}");
        }
        //内容
        if(!empty($this->_body)) {
            echo $this->_body;
        }
    }
    
    /**
     * 快速发送一个回应结果，状态和媒体类型都采用预置值
     * 
     * @param string $body [optional] 媒体内容
     */
    public function quickSend($body='') {
        $this->send($this->_status, $body, $this->_content_type);
    }
    
    /**
     * 设置状态码
     * 
     * @param string $status
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
    }
    
    /**
     * 设置媒体内容
     * 
     * @param mixed $body
     */
    public function setBody($body) {
        $this->_body = $body;
    }
    
    /**
     * 设置媒体格式
     * 
     * @param string $content_type
     */
    public function setContentType($content_type) {
        $this->_content_type = $content_type;
    }
    
    /**
     * 获取媒体格式
     * 
     * @return string $content_type
     */
    public function getContentType() {
        return $this->_content_type;
    }
    
    /**
     * 设置媒体编码
     * 
     * @param string $charset
     */
    public function setCharset($charset) {
        $this->_charset = $charset;
    }
    
    /**
     * 设置HTTP头
     * 
     * @param string $name 配置
     * @param string $value 值
     */
    public function setHeader($name, $value) {
        $this->_header[$name] = $value;
    }
    
    /**
     * 根据status获取相应的状态描述
     * 
     * @return string
     */
    public function getStatusMessage() {
        return isset($this->_status_message[ $this->_status ]) ?
            $this->_status_message[ $this->_status ] : 'Unknown';
    }
}