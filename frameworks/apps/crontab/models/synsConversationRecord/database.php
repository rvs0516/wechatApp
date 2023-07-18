<?php
/**
+----------------------------------------------------------
* 数据访问模型
+----------------------------------------------------------
* @author heyongzhen
* @version 2023.7.14
+----------------------------------------------------------
*/
class database 
{
    private $_crm_chat_msg;

	public function __construct()
    {
		$this->_crm_chat_msg = new model('crm_chat_msg');
	}

    /**
     * 保存聊天记录
     */
    public function setChatData($data)
    {
        return $this->_crm_chat_msg->set($data);
    }

    /**
     * 查询指定消息记录
     */
    public function getMsgIdChat($msgid, $sender, $receiver)
    {
        $where = "`msgid`='{$msgid}' and `sender`='{$sender}' and `receiver`='{$receiver}'";
		// get($where = null, $printf_args=array())
		return $this->_crm_chat_msg->get($where);
    }
}
