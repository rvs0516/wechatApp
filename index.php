<?php
header("Content-type: text/html; charset=utf-8"); 
var_dump($_REQUEST);
echo "乾游企业微信内部应用开发测试";exit;

/*
// 企业 corp_id
$corp_id = 'wwe6ce267036e47037';
// 当前应用的 secret
$secret = '2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM';
// 获取的 access_token
$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corp_id}&corpsecret={$secret}";
var_dump($url);
$data = file_get_contents($url);
echo json_encode($data);
if($data){
    var_dump('获取 access_token 成功:'.json_encode($data));
}else{
	echo "获取失败".json_encode($data);exit;
}*/