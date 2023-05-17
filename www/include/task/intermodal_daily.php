<?php
/**
 * 當前腳本用于收集收集每日聯運數據
 * 由 crontab 发起
 * 
 * 每日零時發起
 * 
 * intermodal_daily.php
 */

define('INTERMO_D', true);

$_GET['m'] = 'index';
$_GET['a'] = 'getIntermodalDaily';
//檢查訂單請款
require_once(dirname(__FILE__).'/../../pay/index.php');