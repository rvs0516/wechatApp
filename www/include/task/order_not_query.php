<?php
/**
 * 當前腳本用于收集失敗訂單
 * 為自動補單提供數據
 * 由 crontab 发起
 * 
 * order_not_query.php
 */
define('CHECK_SETTLE', true);
//每個訂單請款次數上限
define('CHECK_NUMBER', 3);
$_GET['m'] = 'index';
$_GET['a'] = 'getNotQueryByCron';
//檢查訂單請款
require_once(dirname(__FILE__).'/../../pay/index.php');