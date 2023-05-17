<?php

/**
 * 获取SDK初始化时必需的一些信息
 * 
 */
class mGameSDKApi {

	//自定义渠道号
	private $_anysdk_channel;


	//渠道参数
	private $_params;

	/**
	 * 渠道回调接口
	 */
	public function callback() {
		// 假设企业号在公众平台上设置的参数如下
		$encodingAesKey = "alaNrIv2NDA6dktf4Rxn3gcrcnYjYUd5F4GV94Nz0Sh";
		$token = "q9slNpUKoiM";
		$receiveid = "wwe6ce267036e47037";
		


		load('model.callback.WXBizMsgCrypt');
		$wxb = new WXBizMsgCrypt($token, $encodingAesKey, $receiveid);

		// //$dd = '{"m":"index","a":"callback","msg_signature":"f60b813920e8060a5c40741fb80e4a68ae09a1b4","timestamp":"1676881602","nonce":"1677040124","echostr":"bfGTwnbxL53zi2p7r6Kc+bfvNqoUN0cH2O5VU1sQSDt37LdU4dH+BT0AJGGMnXqFSXtJgIFlAXjiH32Rg+2SuA=="}';
		// //$_GET = json_decode($dd, true);
		// //$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $receiveid);

		// // http://cq.xuduan.tech:62606/weworkapi_php/callback_json/callbackverify.php?msg_signature=5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3&timestamp=1409659589&nonce=263014780&echostr=P9nAzCzyDtyTWESHep1vC5X9xho%2FqYX3Zpb4yKa9SKld1DsH3Iyt3tP3zNdtp%2B4RPcs8TgAE7OaBO%2BFZXvnaqQ%3D%3D
		// //
		// // get the paramters
		$sVerifyMsgSig = $_GET['msg_signature'];
		$sVerifyTimeStamp = $_GET['timestamp'];
		$sVerifyNonce = $_GET['nonce'];
		$sVerifyEchoStr = empty($_GET['echostr']) ? '' : $_GET['echostr'];

		// $sEchoStr = "";

		// // call verify function
		// $errCode = $wxb->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
		// //var_dump($sEchoStr);exit;
		// if ($errCode == 0) {
		// 	echo $sEchoStr . "\n";
		// } else {
		// 	print("ERR: " . $errCode . "\n\n");
		// }

		$sMsg = '';
		$errCode = $wxb->DecryptMsg($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, file_get_contents("php://input"), $sMsg);
		if($errCode == 0){
			$result = xmlToArray($sMsg);
			$result = json_encode($result, true);
			// array(7) {
			// 	["ToUserName"]=>string(18) "wwe6ce267036e47037"
			// 	["FromUserName"]=>string(22) "jianghua.zhou@2y9y.com" // 员工userid
			// 	["CreateTime"]=>string(10) "1678761655" // 消息创建时间
			// 	["MsgType"]=>string(4) "text" // 消息类型
			// 	["Content"]=>string(1) "t" // 内容
			// 	["MsgId"]=>string(19) "7210226406973313812" // 消息id
			// 	["AgentID"]=>string(7) "1000004" 
			// }
		}else{
			exit("ERR:{$errCode}");
		}

		$xml = file_get_contents("php://input");
		$str = "\n" . date("[Y-m-d H:i:s]") . "\n" .
			$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ."\n".
			json_encode($_REQUEST) ."\n".
			$xml . "\n" . 
			"结果代码：{$errCode}\n" . 
			"结果：{$result}" . 
			"\n\n";
		error_log($str, 3, C('DEDE_DATA_PATH') ."logs/callback".date("Y-m-d").".txt");
		exit; // 当接收成功后，http头部返回200表示接收ok，其他错误码企业微信后台会一律当做失败并发起重试。
		/*$this->_anysdk_channel = trim($_REQUEST['pb_channel']);
		$this->_params = $_POST;

		$sdkChannel = 'mGameSDK' . $this->_anysdk_channel;
		load('@main.model.api.' . $sdkChannel);
		$channel = new $sdkChannel($this->_params);

		$channel->callback();*/
		//echo "string";exit;
		//$this->getAccessToken();
	}

	/**
	 * 渠道回调接口
	 */
	public function getAccessToken() {
		// 企业 corp_id
		$corp_id = 'wwe6ce267036e47037';
		// 当前应用的 secret
		$secret = '2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM';
		// 获取的 access_token
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corp_id}&corpsecret={$secret}";

		$data = file_get_contents($url);
		/*$data = '{
   "errcode": 0,
   "errmsg": "ok",
   "access_token": "accesstoken0000012222",
   "expires_in": 72200
}';*/
		$data = json_decode($data, true);
//var_dump($data);exit;
		//写入配置文件
		if(!empty($data['access_token']) && $data['errcode'] == 0){
			$config = array(
				'accessToken' => $data['access_token'], 
				'validTime' => $data['expires_in'], 
				'reportTime' => time(), 
				);
		    $output = "<?php";
			$output .= "\n";
			$output .= "return ";
			$output .= var_export($config, true);
			$output .= ";";
			file_put_contents(APP_LIST_PATH . "api/config.accessToken.php", $output);
		}
		return $data['access_token'];
	}

	/**
	 * 渠道回调接口
	 */
	public function getTeammateId() {
		$config = require(APP_LIST_PATH . "api/config.accessToken.php");

		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/list_id?debug=1&access_token={$config['accessToken']}";
		
		$url = "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token={$config['accessToken']}";
		$data = file_get_contents($url);
		$data = json_decode($data, true);
		var_dump($data);
		var_dump($url);
		if ($data['errcode'] == 40014 || $data['errcode'] == 42001) {
			$accessToken = $this->getAccessToken();

		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/list_id?access_token={$accessToken}";
		$data = file_get_contents($url);
		$data = json_decode($data, true);
		}
		var_dump($data);exit;
	}

}