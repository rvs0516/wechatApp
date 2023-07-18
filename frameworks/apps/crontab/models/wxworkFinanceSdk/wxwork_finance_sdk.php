<?php
/**
+----------------------------------------------------------
* 企业微信会话存档SDK
+----------------------------------------------------------
* @author heyongzhen
+----------------------------------------------------------
*/
class wxwork_finance_sdk
{

	private $_obj;

    /**
     * 构造函数
     * 
     * @param [string] $corpId 企业ID
     * @param [string] $secret 会话内容存档应用secret
     */
	public function __construct($corpId, $secret) 
    {
        // $this->_obj = new WxworkFinanceSdk("wwe6ce267036e47037", "hagJzm92QxR2-lHySWEAeCRAb720Suq6pxp2eqFJgCY");
        
        // 实例化企业微信会话存档SDK
        $this->_obj = new WxworkFinanceSdk($corpId, $secret);
        // echo "<pre>";
        // var_dump($this->_obj);
        // var_dump($corpId);
        // var_dump($secret);
        // exit;
	}

    /**
     * 拉取聊天数据
     * 
     * 注：获取会话记录内容不能超过5天，如果企业需要全量数据，则企业需要定期拉取聊天消息。
     *
     * @param [int] $seq 起始位置。从指定的seq开始拉取消息，注意的是返回的消息从seq+1开始返回，seq为之前接口返回的最大seq值。首次使用请使用seq:0
     * @param [int] $limit 获取条数。最大值1000条，超过1000条会返回错误。一次拉取调用上限1000条会话记录，可以通过分页拉取的方式来依次拉取。调用频率不可超过600次/分钟。
     * @return [array] $decryptData 返回本次拉取消息的数据
     */
    public function getWxChatData($seq = 0, $limit = 1000)
    {
        try {
            
            $chats = json_decode($this->_obj->getChatData($seq, $limit), true);
        
            // echo "<pre>";
            // var_dump($chats);
            // exit;
        
            // array(3) {
            //     ["errcode"]=>
            //     int(0)
            //     ["errmsg"]=>
            //     string(2) "ok"
            //     ["chatdata"]=>
            //     array(2) {
            //       [0]=>
            //       array(5) {
            //         ["seq"]=>
            //         int(1)
            //         ["msgid"]=>
            //         string(43) "10934223231912468435_1687870520882_external"
            //         ["publickey_ver"]=>
            //         int(1)
            //         ["encrypt_random_key"]=>
            //         string(344) "NURfvdAjWBlJ4uWvZfBmxe2YU2j/HLMbI5Uo2cLTE9b0H6xR2BEC4oC6y14zcrNejbfJ/9XD5VAiOescp3eCqwM35bwjKpLV7UAiqCzrAmmJngONxkm73kDQ8r3bXaQ3y1eSOXZHSCSj4y6zxt60Z+4Mi650ufPuz2kXWeTCl8BjV90kcnPiwK1ydTmdW2jCQoGHzY5wARTGTvXClyplOebt8fGCTTWhskAtccEHIXSumQLMzbpZgG2i8j7nUhhG4BNH2vDxca2DXZpt7e00KQFTpEmmXY3HrNMEJm8D/WN319tafz36a2G3kI3/JNd6xFpEeobaRbIk2OPkeoZlZQ=="
            //         ["encrypt_chat_msg"]=>
            //         string(384) "BcharL/sif+3paAD6v1phDOnPshS5V3Bus813OfblGi8eOQBKIBugz8jz60jD9oHZsGR74RHZ1w/P7JMGKXLPFd0QfCrQ8p1R2DsOdxsAd0Cldljv7cjAI0hMSgKmaTvQ4cLWdkfZIzl0KK0HoanwE2GZkpc3Kg2jhOjPFiCRAnz0wY3qxxGE7iz7pcWtk/i4muUpkO2ZXjgSANc7fbpxlwxHyOYkLYHpzfIHmFQqJBtk+pqEAR6geuidMzeHABJ8zc1wH+dIyKRkc7rBJ9IqK3+ojrJnyIccfMcp7loXMke6vXiu3KfDRkqTLg2hMbHyNmq8RU+UiSEfgy9wrpsPbcAo/Fil6EtMntr+SXrwRkDlNu2PUmhc48TrzDVeE6j"
            //       }
            //       [1]=>
            //       array(5) {
            //         ["seq"]=>
            //         int(2)
            //         ["msgid"]=>
            //         string(43) "13119947673575496451_1687870537797_external"
            //         ["publickey_ver"]=>
            //         int(1)
            //         ["encrypt_random_key"]=>
            //         string(344) "nfenplHKatbmCTWlaNUcdlSDdZykGMBOlX58LRBfY/ixQZbdWiwgrsYEIxLzXb29gnmXMczz/2axqZd8qYE0crem0UCOqVb+OjSbpWE6JB+ahHg7NZ4K159ULdm1VKmX1L4f0UEAq4aLUiYWZ/2h9fycsCc3G9iZ9nuqJOKyc1zZCju0G6WnUMedwXvPs/yzJPqHqOC8Q+QSGEvoS/fAXZmb8cEboA6sB3VElyKWEh3yqXej/OJ9jsihupyMMNFF5wMA9bWsGyDeWTPUGykAj14fz4T0rrqJF71oNZQnZJVrcLQqskCnyOdmRinQ9s5FpeXCatzfXQgGEkx4SQx9eg=="
            //         ["encrypt_chat_msg"]=>
            //         string(391) "pIS3C1re9rFBXKpbMVRiRDGAx4nz7YNjVA2+HChREZdoN/XcKkOhQ9+pzkeBFHJFIMF0OPTf424zipyXF9sLD/MH/ypXRV5Z1+7ED8/xnHIaFVejfygnwqda0d2DnRKbdcfH5gip7XIToQKMPs0xw+dCCdi1UvS/QKt4ni19uMsYgekiaVy8RCm0MWiA0auCcRc59zGHd1thonebhXqZPPVnzu7ffN0QJKQm5DUvn4pD1Pwx+byupyq0tZ9JyA7DT11qkHumXUnuO10qVNX6CVyfnjccQyvsef1MrGhm+avt03pKNi89OAs3jZx6cPNoRbdEuvS4mNe6VIlJLXWWdZlx7rlyTuE9OCJvCJFr2AixIfLhZjoqu4E7FLRg8DwkiWpKpjI"
            //       }
            //     }
            //   }
        
            // 私钥地址
            $privateKey = file_get_contents(C('APP_LIST_PATH').'crontab/models/wxworkFinanceSdk/rsa_private.pem');

            // var_dump($privateKey);
            // exit;
        
            if ($chats['chatdata']) {
                $decryptData = array();
                foreach ($chats['chatdata'] as $val) {
                    $decryptRandomKey = null; // 使用私钥解密后内容
                    $encryptRandomKey = base64_decode($val['encrypt_random_key']); // 加密内容待解密
                    openssl_private_decrypt($encryptRandomKey, $decryptRandomKey, $privateKey, OPENSSL_PKCS1_PADDING);
            
                    // decryptData(string $randomKey, string $encryptStr);
                    // $randomKey 通过openssl解密后的key
                    // $encryptStr encrypt_chat_msg 的加密数据
                    $decryptDataJson = $this->_obj->decryptData($decryptRandomKey, $val['encrypt_chat_msg']);
                    $decryptData[] = json_decode($decryptDataJson, true);
                    // var_dump($privateKey);
                    // var_dump($val);
                    // var_dump($encryptRandomKey);
                    // var_dump($decryptRandomKey);
                    
                    // exit;
                }
                // var_dump($decryptData);

                return $decryptData;
            }
        
        }catch(\WxworkFinanceSdkException $e) {
            var_dump($e->getMessage(), $e->getCode());
            exit;
        }
    }

    /**
     * 下载并保存媒体文件
     * 
     * @param [string] $sdkfileid 资源id。来自getWxChatData中的数据sdkfileid
     * @param [string] $filePath 本地保存的路径
     * @return [bool] $saveRes 下载并保存结果是否成功
     */
    public function downloadWxMedia($sdkFileId, $filePath)
    {
        $saveRes = $this->_obj->downloadMedia($sdkFileId, $filePath);

        return $saveRes;
    }

    
}

