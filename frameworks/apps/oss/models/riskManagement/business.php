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
	public $_riskManagement_database_model;
	public $_access_token;
	public $_workWeixin;

	function __construct() {

		$corpId = "wwe6ce267036e47037"; // 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
		$appSecret = "2KxH1ihA8Sx3EcZniY_1ZVs90xnFfk-9QQ0EeTxxLIM"; // 企业内部应用secret，只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录
		$contactSecret = "FSibQ2uBHVzxxc_xEJJopsFSy3qx5Q_bCcW4YzOxnKM"; // 使用通讯录同步专有的secret
        $conversationSecret = "hagJzm92QxR2-lHySWEAeCRAb720Suq6pxp2eqFJgCY"; // 会话内容存档应用secret

        // 实例化数据访问对象
		$this->_riskManagement_database_model = getInstance('model.riskManagement.database');

        // 实例化企业微信相关接口对象
        // require_once APP_PATH.'models/api/workWeixin.php';
        load('@oss.model.api.workWeixin');
        $this->_workWeixin = new workWeixin($corpId, $appSecret, $contactSecret, $conversationSecret);
    }

    /**
     * 获取部门列表
     */
    public function getDepartmentList()
    {
        $departmentList = $this->_riskManagement_database_model->getDepartmentList();

        // 部门选择列表划分等级，方便查看
        foreach ($departmentList as $k => $v) {
            
            if ($v['parentid'] > 0) {
                for ($i=1; $i <= $v['parentid']; $i++) { 
                    $departmentList[$k]['symbol'] .= '—';
                }
            } else {
                $departmentList[$k]['symbol'] = '';
            }

        }

        return $departmentList;
    }

    /**
     * 获取指定部门的员工
     * 
     * @param [int] $departmentId 部门ID
     */
    public function getDepartmentEmployee($departmentId)
    {
        return $this->_riskManagement_database_model->getDepartmentEmployeeList($departmentId);
    }

    /**
     * 获取指定员工的客户列表
     * 
     * @param [int] $userId 员工ID
     */
    public function getCustomersList($userId, $offset, $row)
    {
        $customersList = $this->_riskManagement_database_model->getCustomersList($userId, $offset, $row);
        
        // 获取员工列表总记录数
        $customersCount = $this->_riskManagement_database_model->getCustomersCount($userId);

        // echo "<pre>";
        // var_dump($customersList);
        // var_dump($customersCount);
        // exit;

        $data = array(
            'customersList' => $customersList ? $customersList : array(),
            'customersCount' => $customersCount ? $customersCount : 0
        );

        return $data;
    }

    /**
     * 查看会话
     */
    public function checkConversation($followUserid, $externalUserid)
    {
        // var_dump($followUserid);
        // var_dump($externalUserid);
        // exit;

        // 获取客户发送的聊天信息
        $customerChatMsg = $this->_riskManagement_database_model->getCustomerChatMsg($followUserid, $externalUserid);
        foreach ($customerChatMsg as $key => $value) {
            // 标识客户发送的聊天信息
            $customerChatMsg[$key]['msgTag'] = 'customer';

            // 获取客户名字和头像
            $customerInfo = $this->_riskManagement_database_model->getCustomerInfo($value['sender']);
            // remark:员工给客户微信的备注名称,name:客户微信名称,优先使用员工给客户微信的备注名称
            $customerChatMsg[$key]['customerName'] = $customerInfo['remark'] ? $customerInfo['remark'] : $customerInfo['name'];
            // 客户微信头像
            $customerChatMsg[$key]['customerAvatar'] = $customerInfo['avatar'];

            // 获取员工企业微信头像
            // TODO 获取员工头像信息，目前还没获取到员工头像信息，需要员工登录scrm平台后提示员工授权平台获取头像等信息。会话记录商员工头像暂时先留空。
            $customerChatMsg[$key]['employeeAvatar'] = '';
            // 员工名字
            $employeeInfo = $this->_riskManagement_database_model->getEmployeeInfo($value['receiver']);
            $employeeChatMsg[$key]['employeeName'] = $employeeInfo['name'];
        }

        // echo "<pre>";
        // var_dump($employeeChatMsg);
        // exit;

        // 获取员工发送的聊天信息
        $employeeChatMsg = $this->_riskManagement_database_model->getEmployeeChatMsg($followUserid, $externalUserid);
        foreach ($employeeChatMsg as $key => $value) {
            // 标识员工发送的聊天信息
            $employeeChatMsg[$key]['msgTag'] = 'employee';

            // 获取客户名字和头像
            $customerInfo = $this->_riskManagement_database_model->getCustomerInfo($value['receiver']);
            // remark:员工给客户微信的备注名称,name:客户微信名称,优先使用员工给客户微信的备注名称
            $employeeChatMsg[$key]['customerName'] = $customerInfo['remark'] ? $customerInfo['remark'] : $customerInfo['name'];
            // 客户微信头像
            $employeeChatMsg[$key]['customerAvatar'] = $customerInfo['avatar'];

            // 获取员工企业微信头像
            // TODO 获取员工头像信息，目前还没获取到员工头像信息，需要员工登录scrm平台后提示员工授权平台获取头像等信息。会话记录商员工头像暂时先留空。
            $employeeChatMsg[$key]['employeeAvatar'] = '';
            // 员工名字
            $employeeInfo = $this->_riskManagement_database_model->getEmployeeInfo($value['sender']);
            $employeeChatMsg[$key]['employeeName'] = $employeeInfo['name'];
        }

        // echo "<pre>";
        // var_dump($employeeChatMsg);
        // exit;

        // 合并
        $chatMsg = array_merge($customerChatMsg, $employeeChatMsg);

        // 根据消息发送时间排序
        $msgtimeArray = array_column($chatMsg, 'msgtime');
        array_multisort($msgtimeArray, SORT_ASC, $chatMsg);

        // echo "<pre>";
        // var_dump($chatMsg);
        // exit;

        // 返回HTML数据到客户端
        $chatMsgHtml = '';
       
        if ($chatMsg) {

            // 第一次遍历用于解压和格式化数据
            foreach ($chatMsg as $key => $value) {
                // 解压会话内容
                $chatMsg[$key]['msgcontent'] = json_decode( base64_decode( $value['msgcontent'] ) , true);
    
                // 处理不同类型的会话内容，同时根据不同类型的会话内容组装返回给客户端的HTML内容
                $chatMsg[$key]['clearHtml'] = '';
                if ($value['msgtype'] == 'link') {
                    // 链接
                    // $chatMsg[$key]['msgcontent'] = '<a href="'. $chatMsg[$key]['msgcontent']['link_url']. '">'. $chatMsg[$key]['msgcontent']['title']. '</a>';
                    // $chatMsg[$key]['msgcontent'] = '<span>'. $chatMsg[$key]['msgcontent']['link_url']. '</span>';
                    // $chatMsg[$key]['msgcontent']['description']
                    // $chatMsg[$key]['msgcontent']['image_url']
    
                    // 截取描述内容
                    $description = '';
                    if ( strlen($chatMsg[$key]['msgcontent']['description']) > 54 ) {
                        $description .= mb_substr($chatMsg[$key]['msgcontent']['description'], 0, 54);
                        $description .= '...';
                    } else {
                        $description = $chatMsg[$key]['msgcontent']['description'];
                    }
    
                    // 拼接链接消息卡片HTML内容
                    $chatMsg[$key]['msgcontent'] = '<div class="link-card" data-link="'. $chatMsg[$key]['msgcontent']['link_url']. '">
                                                        <div class="link-card-title">'. $chatMsg[$key]['msgcontent']['title']. '</div>
                                                        <div class="link-card-content">
                                                            <div class="link-card-description">
                                                            '. $description. '
                                                            </div>
                                                            <div class="link-card-img">
                                                                <img src="'. $chatMsg[$key]['msgcontent']['image_url']. '" alt="">
                                                            </div>
                                                        </div>
                                                    </div>';
    
    
                } elseif ($value['msgtype'] == 'emotion') {
                    // 用于清除浮动影响
                    // $chatMsg[$key]['clearHtml'] = '<div style="width: '.$chatMsg[$key]['msgcontent']['width'].'px;height: '.$chatMsg[$key]['msgcontent']['height'].'px;"></div>';
                    // 表情
                    $chatMsg[$key]['msgcontent'] = '<img src="'. $chatMsg[$key]['file_url']. '" alt="" width="'.$chatMsg[$key]['msgcontent']['width'].'" height="'.$chatMsg[$key]['msgcontent']['height'].'" class="emotion-img">';
    
                } elseif ($value['msgtype'] == 'text') {
                    // 文本
                    $chatMsg[$key]['msgcontent'] = '<span>'. $chatMsg[$key]['msgcontent']. '</span>';
                    
                }
    
                // 格式化时间
                $chatMsg[$key]['msgtime'] = date('Y-m-d H:i:s', $value['msgtime']);
    
                if ( $value['employeeAvatar'] ) {
                    $chatMsg[$key]['employeeAvatar'] = '<img src="'.$chatMsg[$key]['employeeAvatar'].'" alt="">';
                } else {
                    // 员工头像为空时，使用默认员工的企业微信头像
                    $chatMsg[$key]['employeeAvatar'] = '<span>员工</span>';
                }
            }

            // echo "<pre>";
            // var_dump($chatMsg);
            // exit;
            
            // 第二次遍历用于拼接组装返回到客户端的HTML数据
            foreach ($chatMsg as $key => $value) {

                if ($value['msgTag'] == 'customer') {
                    // 客户发送的消息
                    $chatMsgHtml .=  '<div class="customer-info">
                                        <div class="msgTime">
                                            <em>'.$value['msgtime'].'</em>
                                        </div>
                                        <div class="info-main">
                                            <div class="customer-avatar">
                                                <img src="'.$value['customerAvatar'].'" alt="">
                                            </div>
                                            <div class="clear-avatar-float"></div>
                                            <div class="info-content">
                                                <div class="customer-name">
                                                '.$value['customerName'].'
                                                </div>
                                                <div class="customer-content">
                                                '.$value['msgcontent'].'
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                }
                
                if ($value['msgTag'] == 'employee') {
                    // 员工发送的消息
                    $chatMsgHtml .= '<div class="employee-info">
                                        <div class="msgTime">
                                            <em>'.$value['msgtime'].'</em>
                                        </div>
                                        <div class="info-main">
                                            <div class="info-content">
                                                <div class="employee-name">
                                                '.$value['employeeName'].'
                                                </div>
                                                <div class="employee-content">
                                                '.$value['msgcontent'].'
                                                </div>
                                            </div>
                                            <div class="employee-avatar">
                                            '.$value['employeeAvatar'].'
                                            </div>
                                            <div class="clear-avatar-float"></div>
                                        </div>
                                    </div>';
                }

            }

            // var_dump($chatMsgHtml);
            // exit;
        }

        return $chatMsgHtml;

    }
    
}