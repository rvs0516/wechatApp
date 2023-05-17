<?php
/**
 * 储值接口回调URL不接受get参数，所以
 * 需要将URL灌入
 */

$_GET['m'] = 'index';
$_GET['a'] = 'callback';
//檢查訂單請款
require_once(dirname(__FILE__).'/../pay/index.php');