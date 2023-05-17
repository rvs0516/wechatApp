<?php
/**
 * 储值结果异步通知机制
 * 由 crontab 发起
 * 
 * ordedr_asynchronous_mechanism.php
 */

//每個訂單請款次數上限
define('CHECK_NUMBER', true);
$_GET['m'] = 'index';
$_GET['a'] = 'ordedrAsynchronousMechanism';
//檢查訂單請款
require_once(dirname(__FILE__).'/../../pay/index.php');