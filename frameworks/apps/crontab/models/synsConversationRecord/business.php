<?php
/**
+----------------------------------------------------------
 * 封装获取企业微信会话记录相关计划任务对象
+----------------------------------------------------------
 * @author heyonzghen
 * @version 2023.7.14
+---------------------------------------------------------- 
 */
class business
{
    
    public $_database_model;
	public $_wxworkFinanceSdk;

    public function __construct() 
    {
        $corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
        $chatSecret = "hagJzm92QxR2-lHySWEAeCRAb720Suq6pxp2eqFJgCY"; // 会话内容存档应用secret

        // 实例化数据访问对象
		$this->_database_model = getInstance('model.synsConversationRecord.database');

        // 实例化企业微信会话存档SDK
        load('@crontab.model.wxworkFinanceSdk.wxwork_finance_sdk');
        $this->_wxworkFinanceSdk = new wxwork_finance_sdk($corpId, $chatSecret);
    }

    /**
     * 实时同步获取企业微信会话记录
     *
     *【说明】
	 * 执行命令：/usr/bin/php /usr/share/nginx/html/wechatApp/www/crontab/index.php synsConversationRecord
	 * 执行时间：每分钟定时执行一次 * * * * *
     * 
     * 目前执行一次当前程序可以拉取到的聊天数据量是：$circulateTimes * $limit = 100 * 1000 = 100000（条），即一次可以拉取10万条聊天记录。
     * 如果5天内的聊天记录数量超过10万条，则需要调大循环次数。不能调整每页记录条数了，因为最大值已经是1000了。
     * 
     */
    public function synsConversationRecord()
    {
        $circulateTimes = 100; // 循环次数。即允许一次拉取聊天数据的最大页数。

        // 用循环结构模拟选择分页数据操作
        for ($i=1; $i <= $circulateTimes; $i++) { 
           
            $limit = 1000; // 偏移量，即获取的每页记录条数，允许每页的最大数据量是1000条。最大值1000条。
            $seq = ($i - 1) * $limit; // 每页记录起点

            $chatDataCount = $this->getChatDatas($seq, $limit);

            // var_dump($chatDataCount);
            // exit;

            if ($chatDataCount < 1000) {
                // 获取聊天数据条数小于1000，则表示数据已拉取完毕，结束循环逻辑程序
                break;
            }
        }

        echo "成功执行完成";exit;

    }

    /**
     * 获取企业微信会话记录，然后保存到数据表
     * 
     * 注：获取会话记录内容不能超过5天，如果企业需要全量数据，则企业需要定期拉取聊天消息。即每次只能获取5天内的会话记录内容。
     *
     * @param [int] $seq 起始位置。从指定的seq开始拉取消息，注意的是返回的消息从seq+1开始返回，seq为之前接口返回的最大seq值。首次使用请使用seq:0
     * @param [int] $limit 获取条数。最大值1000条，超过1000条会返回错误。一次拉取调用上限1000条会话记录，可以通过分页拉取的方式来依次拉取。调用频率不可超过600次/分钟。
     * @return [int] $chatDataCount 返回本次拉取到的聊天数据总记录数
     */
    public function getChatDatas($seq = 0, $limit = 1000)
    {
        // 获取聊天数据
        $chatData = $this->_wxworkFinanceSdk->getWxChatData($seq, $limit);

        // var_dump($chatData);
        // exit;

        if ($chatData) {
            // $data = array();
            foreach ($chatData as $key => $value) {

                // tolist：消息接收方列表，可能是多个。即群发消息给多个接收方。
                for ($i=0; $i < count($value['tolist']); $i++) { 

                    // 文件访问域名路径默认为空，也可以用于清空上次保存到当前变量的值
                    $fileUrl = '';

                    //TODO: 目前只对文本、表情、链接等内容处理，后续还需要处理音频、视频号、卡片、图片等内容。
                    if ($value['msgtype'] == 'text') {

                        $msgcontent = base64_encode( json_encode( $value['text']['content'] ) );
                        
                    } elseif ($value['msgtype'] == 'emotion') {
                        // 表情

                        $msgcontent = base64_encode( json_encode( $value['emotion'] ) );

                        // 表情类型，png或者gif.1表示gif 2表示png。
                        $fileType = $value['emotion']['type'] == 1 ? 'gif' : 'png';

                        // 文件名称。使用资源的md5值作为文件名称
                        $fileName = $value['emotion']['md5sum'];

                        $sdkFileId = $value['emotion']['sdkfileid']; // 资源ID
                        $filePath = C('DEDE_DATA_PATH')."downloadMedia/".$fileName.".".$fileType; // 文件保存路径
                        $fileUrl = C('STATIC_SOURCE_SITE')."downloadMedia/".$fileName.".".$fileType; // 文件访问域名路径

                        // 下载并保存媒体文件
                        $this->_wxworkFinanceSdk->downloadWxMedia($sdkFileId, $filePath);

                    } elseif ($value['msgtype'] == 'link') {

                        $msgcontent = base64_encode( json_encode( $value['link'] ) );

                    }

                    // 根据消息ID、消息发送方和消息接收方的唯一组合数据索引判断当前消息是否已经存在于聊天记录表中
                    $msgIdChat = $this->_database_model->getMsgIdChat($value['msgid'], $value['from'], $value['tolist'][$i]);

                    if ( empty($msgIdChat) ) {
                        // 新增消息数据
                        $data = array(
                            'msgid' => $value['msgid'],
                            'action' => $value['action'], // 消息动作:目前有send(发送消息)/recall(撤回消息)/switch(切换企业日志)三种类型
                            'sender' => $value['from'], // 消息发送方ID
                            'receiver' => $value['tolist'][$i], // 消息接收方ID
                            'msgtype' => $value['msgtype'],
                            'msgcontent' => $msgcontent,
                            'file_url' => $fileUrl ? $fileUrl : '',
                            'msgtime' => strtotime( date( 'Y-m-d H:i:s', substr($value['msgtime'], 0, 10) ) ),
                        );
                        // var_dump($data);

                        // 保存聊天数据到聊天记录表
                        if ($data) {
                            $this->_database_model->setChatData($data);
                        }
                    }
                }
            }
            // exit;

            // 本次拉取到的聊天数据总记录数
            $chatDataCount = count($chatData);
            return $chatDataCount;
        }

        // var_dump($chatData);
        // exit;
    }

}