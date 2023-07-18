<?php
/**
+----------------------------------------------------------
* 业务逻辑模型
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
class business
{

	// 参数
	public $_corpId;
	public $_corpsecret;
	public $_wxcpt;
	public $_token;
	public $_encodingAESKey;
	public $_access_token;
	public $_customer_database_model;

	/**
     * 构造函数
     */
	public function __construct($token, $encodingAESKey)
    {

		$this->_corpid = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$this->_corpsecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 应用的凭证密钥t。自建应用secret。

		// 设置接收事件服务器的Token票据和AES密钥的base64编码结果EncodingAESKey
		$this->_token = $token;
		$this->_encodingAESKey = $encodingAESKey;

		// echo "<pre>";
		// var_dump($this->_token);
		// var_dump($this->_encodingAESKey);
		// exit;

		load('model.aes.WXBizMsgCrypt');
		$this->_wxcpt = new WXBizMsgCrypt($this->_token, $this->_encodingAESKey, $this->_corpid);

		// 获取access_token
		$this->_access_token = $this->getToken();

		// 引用客户数据表数据访问模型
		$this->_customer_database_model = getInstance('model.customer.database');

	}

}