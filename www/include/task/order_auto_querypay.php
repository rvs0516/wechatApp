<?php
/**
 * 當前腳本用于將收集到的失敗訂單自動發起補單操作
 * 将收集到的失败订单再次发起请款操作，以降低丢单率
 * 由 crontab 发起
 * 
 * order_auto_querypay.php
 */
define('CHECK_SETTLE', true);
//每個訂單請款次數上限
define('CHECK_NUMBER', 3);
$_GET['m'] = 'index';
$_GET['a'] = 'autoQueryPayByCron';
//檢查訂單請款
require_once(dirname(__FILE__).'/../../pay/index.php');