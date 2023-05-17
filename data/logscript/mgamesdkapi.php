<?php
error_reporting(0);

define('LOG_DOMAIN', true);

if(!isset($_SERVER['argv'][1])) {
	exit('无法获取参数');
}
$request_param = array();
parse_str($_SERVER['argv'][1], $request_param);
$_REQUEST = $_POST = $_GET = $request_param;

require '/srv/http/myapp/p2y9y/www/mgame_sdk_api/index.php';
