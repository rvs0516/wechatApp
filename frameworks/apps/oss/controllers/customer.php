<?php

require_once APP_CONTROLLER_PATH . '/master.php';

class customerController extends masterControl {

	public $_REQUEST;
	public $_customer_business_model;

	function __construct() {
        parent::__construct();

		$this->_REQUEST = $_REQUEST;

		// 引用客户业务逻辑模型
		$this->_customer_business_model = getInstance('model.customer.business');

    }

	/**
	 * 我的客户列表
	 * 
	 * 通过查询数据表
	 */
	public function customerList()
	{
		// 根据scrm平台角色名称获取对应的企业成员userid
		$userArrray = $this->_customer_business_model->getAgentUid($this->_uid);
		$userid = $userArrray['agent_uid'];
		// echo "<pre>";
		// var_dump($agentUid);
		// exit;

		// 获取配置了客户联系功能的成员列表
		// $followuserList = $this->_customer_business_model->getFollowuserList();

		// 企业成员的userid
		// $userid = "HeYongZhen";
		// $userid = array("HeYongZhen", "cr7");
		// $userid = $followuserList;
		
		// 分页
		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$row = 10; // 偏移量，即每页记录条数
		$offset = ($page - 1) * $row; // 每页记录起点

		// 获取客户列表
		// $customerListData = $this->_customer_business_model->customerList($userid, $offset, $row);
		$customerListData = $this->_customer_business_model->myCustomerList($userid, $offset, $row);

		// echo "<pre>";
		// var_dump($customerListData);
		// exit;

		$this->assign('userid', $userid);
		$this->assign('page', $page);
        $this->assign('row', $row);
		$this->assign('rowcount', $customerListData["customersCount"]); // 总记录数
		$this->assign('customerList', $customerListData["customerData"]);
	}

	/**
	 * 修改客户备注信息
	 */
	public function updateCustomerRemark()
	{
		// 获取客户详情信息
		$customerData = $this->_customer_business_model->updateCustomerRemark($this->_REQUEST);

		$this->assign('external_userid', $customerData["external_userid"]);
		$this->assign('follow_userid', $customerData["follow_userid"]);
		$this->assign('type', $customerData["type"]);
		$this->assign('remark', $customerData["remark"]);
		$this->assign('remark_corp_name', $customerData["remark_corp_name"]);
		$this->assign('remark_mobiles', $customerData["remark_mobiles"]);
		$this->assign('description', $customerData["description"]);

		// echo "<pre>";
		// var_dump($customerData);
		// exit;
	}

	/**
	 * 保存修改后的客户备注信息
	 */
	public function saveCustomerRemark()
	{
		// echo "<pre>";
		// var_dump($this->_REQUEST);
		// exit;

		// 获取客户详情信息
		$saveRes = $this->_customer_business_model->saveCustomerRemark($this->_REQUEST);

		// echo "<pre>";
		// var_dump($saveRes);
		// exit;

		// {
		// 	"errcode": 0,
		// 	"errmsg": "ok"
		// }

		if ($saveRes['errcode'] == 0) {
			// ShowMsg($msg, $gourl, $onlymsg = 0, $limittime = 0)
			ShowMsg('操作成功', '/index.php?m=customer&a=customerList');
			
			// header("location:http://test.admin.wechat.online128.com/index.php?m=customer&a=customerList");
			// die();
		}

	}

	/**
	 * 团队客户
	 * 
	 * 获取应用下可见范围内配置了客户联系功能的企业成员微信下的所有客户
	 */
	public function allCustomerList()
	{
		// 获取配置了客户联系功能的成员列表
		// $followuserList = $this->_customer_business_model->getFollowuserList();

		// echo "<pre>";
		// var_dump($followuserList);
		// exit;

		// 企业成员的userid
		// $userid = "HeYongZhen";
		// $userid = array("HeYongZhen", "cr7");
		// $userid = $followuserList;
		// $userid = '';
		
		// 分页
		$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
		$page = $page === 0 ? 1 : $page;
		$row = 10; // 偏移量，即每页记录条数
		$offset = ($page - 1) * $row; // 每页记录起点

		// 获取客户列表
		// $customerListData = $this->_customer_business_model->customerList($userid, $offset, $row);
		$customerListData = $this->_customer_business_model->allCustomerList($offset, $row);

		// echo "<pre>";
		// var_dump($customerListData);
		// exit;

		$this->assign('page', $page);
        $this->assign('row', $row);
		$this->assign('rowcount', $customerListData["customersCount"]); // 总记录数
		$this->assign('customerList', $customerListData["customerData"]);
	}

	/**
	 * 单个客户在职继承
	 */
	public function transferCustomer()
	{
		// echo "<pre>";
		// var_dump($this->_REQUEST);
		// exit;

		// 获取客户详情信息
		$customerData = $this->_customer_business_model->transferCustomer($this->_REQUEST);

		// 获取配置了客户联系功能的成员列表
		$followuserList = $this->_customer_business_model->getFollowuserList();
		// echo "<pre>";
		// var_dump($followuserList);
		// exit;

		$this->assign('external_userid', $customerData["external_userid"]);
		$this->assign('follow_userid', $customerData["follow_userid"]);
		$this->assign('name', $customerData["name"]);
		$this->assign('followuserList', $followuserList);

	}

	/**
	 * 更新单个客户的跟进企业成员，上报企业微信
	 */
	public function saveTransferCustomer()
	{
		// echo "<pre>";
		// var_dump($this->_REQUEST);
		// exit;

		

		// 更新客户的跟进企业成员，上报企业微信
		$customerArray = $this->_customer_business_model->saveTransferCustomer($this->_REQUEST["form"]);

        //     array(1) {
        //       [0]=>
        //       array(2) {
        //         ["errcode"]=>
        //         int(0)
        //         ["external_userid"]=>
        //         string(32) "wmFrvHCQAAWBOh3HVu3OCzcFL-G6KcQQ"
        //       }
        //     }

		foreach ($customerArray as $key => $value) {
			if ($value["errcode"] == 0) {
				// 24小时后接替企业成员微信自动成功添加客户微信，触发添加企业客户事件回调
				$sMsg = '在职转接操作成功！';

			} elseif ($value["errcode"] == 40129) {
				// 重复客户正在转接给同一个企业成员微信的情况下，提示：客户正在转接中，无需重复操作！
				$sMsg = '客户正在转接中，无需重复操作！';

			} elseif ($value["errcode"] == 40130) {
				// 原跟进人与接手人一样
				$sMsg = '原跟进人与接手人一样，不可继承！';

			} elseif ($value["errcode"] == 40128 || $value["errcode"] == 10001) {
				// 客户转接过于频繁（90个自然日内，在职成员的每位客户仅可被转接2次）
				$sMsg = '客户转接过于频繁（90个自然日内，在职成员的每位客户仅可被转接2次）！';

			} elseif ($value["errcode"] == 10000) {
				// 重复客户已经在同一个企业成员微信的好友列表的情况下，提示：客户已在企业成员微信的好友列表中，无需再转接！
				$sMsg = '客户已在接替企业成员微信的好友列表中，无需再转接！';
				
			}
		}
		ShowMsg($sMsg, '/index.php?m=customer&a=allCustomerList');

	}

	/**
	 * 离职继承
	 */
	public function dimissionTransfer()
	{
		// echo "<pre>";
		// var_dump($this->_REQUEST);
		// exit;

		ShowMsg('开发中！', '/index.php?m=customer&a=allCustomerList');

		// 获取离职员工列表

	}

	// public function saveDimissionTransfer()
	// {

	// }

	/**
     * 同步团队客户
     */
    public function syncAllCustomers()
    {
       
        $this->_customer_business_model->syncAllCustomers($this->_uid);
        
    }

	/**
	 * 同步“我的”客户，即同步当前登录SCRM平台的企业成员跟进的客户
	 */
	public function synsMyCustomers()
	{
		$this->_customer_business_model->synsMyCustomers($this->_uid);
	}
	
}