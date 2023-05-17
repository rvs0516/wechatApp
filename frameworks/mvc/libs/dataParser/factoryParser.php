<?php

/**
 * 解释器工厂
 */
class factoryParser {
    
    /**
     * 获取一个解释器实例
     * 
     * @param string $parser html json xml
     * @param array $data 原始数据
     * @return object|false;
     */
    static function getInstance($parser, $data) {
        $parser_class = $parser . 'Parser';
        $success = load('dataParser.' . $parser_class);
        if(!$success) {
            return false;
        }
        return new $parser_class($data);
    }
}