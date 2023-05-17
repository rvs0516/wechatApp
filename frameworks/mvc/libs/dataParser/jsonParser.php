<?php

load('dataParser.interfaceParser');

class jsonParser implements interfaceParser {
    
    private $_data;
    
    public function __construct($data) {
        $this->_data = $data;
    }
    
    /**
     * 数据转换器
     * 目前只支持array一种
     */
    public function encode() {
        return is_array($this->_data) ? json_encode($this->_data) : $this->_data;
    }
}