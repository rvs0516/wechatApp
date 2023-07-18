<?php
/**
 * 当前脚本用于自动增加 APK 下载数
 * 由 crontab 发起
 * 
 * 每日零時發起
 * 
 * crontab.php
 */
$_GET['m'] = 'index';
$_GET['a'] = 'cron';

define('APP_NAME', 'crontab');
include dirname(__FILE__).'/../../frameworks/mvc/runtime.php';