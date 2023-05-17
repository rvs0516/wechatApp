<?php

/**
 * 简繁互转
 */
class utf8_ts {

	private static $_st_table = null;

	public function __construct(){
		if(is_null(self::$_st_table)) {
			$s2t_table = require dirname(__FILE__) . '/st_table.php';
			self::$_st_table = array_flip($s2t_table);
		}
	}
    
    /**
     * 简 -> 繁
     * 
     * @param string $word
     * @return string
     */
	public static function s2t($word){
		return strtr($word, self::$_st_table);
	}

    /**
     * 繁 -> 简
     * 
     * @param string $word
     * @return string
     */
	public static function t2s($word){
		return strtr($word, self::$_st_table);
	}
}