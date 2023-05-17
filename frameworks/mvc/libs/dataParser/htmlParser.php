<?php

load('dataParser.interfaceParser');

class htmlParser implements interfaceParser {
    
    private $_data;
    
    public function __construct($data) {
        $this->_data = $data;
    }
    
    /**
     * 数据转换器
     */
    public function encode() {
        return $this->_data;
    }
}