<?php
header("Content-type: text/html; charset=utf-8");
include_once '../frameworks/mvc/functions.php';

var_dump($_REQUEST);
echo "乾游企业微信内部应用开发测试1.0<br><br>";


// 获取的 access_token
// $accessToken = getAccessToken();
// $accessToken = getAccessToken('contacts');
// $accessToken = getAccessToken('customer');
// $accessToken = getAccessToken('kf');
// var_dump($accessToken);die;



// // -----读取成员ID列表
// $tokenType = 'contacts';
// $data = ['cursor' => '', 'limit' => 1000];
// $url = "https://qyapi.weixin.qq.com/cgi-bin/user/list_id?access_token=%s";


// // -----读取成员userid（手机号）
// $data = ['mobile' => 13536171913];
// $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserid?access_token=%s";


// // 读取员工信息
// $userid = 'jianghua.zhou@2y9y.com';
// $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=%s&userid={$userid}";


// // -----创建部门
// $tokenType = 'contacts';
// $data = [
// 	'name' => '测试新建部门',
// 	'parentid' => 1,
// ];
// $url = "https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token=%s";


// // 获取部门id列表
// $tokenType = 'contacts';
// $id = '';
// $url = "https://qyapi.weixin.qq.com/cgi-bin/department/simplelist?access_token=%s";
// if(!empty($id)){
// 	$url .= "&id={$id}";
// }


// // -----发消息
// $data = [
// 	'touser' => 'jianghua.zhou@2y9y.com',
// 	'toparty' => '',
// 	'totag' => '',
// 	'msgtype' => 'text',
// 	'agentid' => 1000004,
// 	'text' => ['content' => '测试发送2'],
// 	// 'safe' => 0,
// 	// 'enable_id_trans' => 0,
// 	// 'enable_duplicate_check' => 0,
// 	// 'duplicate_check_interval' => 1800,
// ];
// $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=%s";


// // -----获取指定员工的客户
// $tokenType = 'customer';
// $data = [
// 	'userid_list' => ['LiZe'],
// 	'cursor' => '',
// 	'limit' => 100,
// ];
// $url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/batch/get_by_user?access_token=%s";




// -----获取客服帐号列表
$tokenType = 'kf';
$data = [
	'offset' => 0,
	'limit' => 100,
];
$url = "https://qyapi.weixin.qq.com/cgi-bin/kf/account/list?access_token=%s";






// 获取token，拼接url
$tokenType = empty($tokenType) ? '' : $tokenType;
$accessToken = getAccessToken($tokenType);
$url = sprintf($url, $accessToken);

// 处理post参数，访问接口
$data = empty($data) ? false : json_encode($data);
$res = httpRequest($url, $data);
dump($res);

exit('<br><br>end');








function dump($data)
{
	echo '<pre>';
	var_dump($data);
	exit('<br><br>end');
}


/**
 * 获取token值
 * @param string $type secret类型，部分接口需要不同secret
 * @return string
 */
function getAccessToken($type = '')
{
	// 企业 corpid
	$corpid = 'wwe6ce267036e47037';
	switch($type){
		case 'contacts':
			// 员工通讯录的 secret
			$secret = 'FSibQ2uBHVzxxc_xEJJopsFSy3qx5Q_bCcW4YzOxnKM';
			break;

		case 'customer':
			// 客户管理的 secret
			$secret = 'zGgAydHP1LB-QWrq5Z2NQBh2bbTp4vSPiKJtq6XnaYc';
			break;
			break;

		case 'kf':
			// 客服的 secret
			$secret = '_pL2gLfj1NDxIZaTq2pkWI_jUUDMKlg9vnpcc7F5FhE';
			break;

		default:
			// 应用的 secret
			$secret = '2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM';
	}
	

	$path = dirname(__FILE__) . '/../frameworks/apps/oss';
	$data = json_decode(get_php_file("{$path}/access_token_{$type}.php"));

	if ($data->expire_time < time()) {
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpid}&corpsecret={$secret}";
		$res = json_decode(httpRequest($url));
		$access_token = $res->access_token;
		if ($access_token) {
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			set_php_file("{$path}/access_token_{$type}.php", json_encode($data));
		}
	} else {
		$access_token = $data->access_token;
	}
	return $access_token;
}
function get_php_file($filename)
{
	return trim(substr(file_get_contents($filename), 15));
}
function set_php_file($filename, $content)
{
	$fp = fopen($filename, "w");
	fwrite($fp, "<?php exit();?>" . $content);
	fclose($fp);
}
