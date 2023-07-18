<?php
/**
+----------------------------------------------------------
 * 封装企业微信相关接口的对象
+----------------------------------------------------------
 * @author heyonzghen
 * @version 2023.6.21
+---------------------------------------------------------- 
 */
class workWeixin
{

    public $_appAccessToken;
	public $_contactAccessToken;

    /**
     *
     * @param [string] $corpId 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
     * @param [string] $appSecret   自建应用Secret
     * @param [string] $contactSecret 通讯录同步Secret
     */
    function __construct($corpId, $appSecret, $contactSecret = '') 
    {
        // 获取自建应用access_token
		$this->_appAccessToken = $this->getAccessToken($corpId, $appSecret);

        // 获取通讯录同步secret
        if ($contactSecret) {
            $this->_contactAccessToken = $this->getAccessToken($corpId, $contactSecret);
        }
    }


}
