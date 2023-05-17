<?php

interface interfaceParser {
    
    public function __construct($data);
    
    /**
     * 数据转换器
     */
    public function encode();
    
}