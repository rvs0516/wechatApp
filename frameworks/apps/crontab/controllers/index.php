<?php
/**
 * 本功能主要为全平台提供计划任务
 * 在使用时，计划任务中须加上 key 作为参数，
 * 以防止非服务器之调用
 */
class indexController extends controller {

	public function __construct() {
		//tester
		//$_SERVER['argv'][1] = 'gameIngots';

		if (empty($_SERVER['argv'][1])){
			exit;
		}

		parent::__construct();

	}

	public function cron() {
		$do = trim($_SERVER['argv'][1]);

		load('model.'. $do. '.business');
		$businessModel = new business();

		switch($do) {
			case 'syncEmployeeEvent':
				// 同步员工
				$businessModel->syncEmployees();
				break;

			case 'syncDepartmentEvent':
				// 同步部门
				$businessModel->syncDepartments();
				break;

			case 'syncAllCustomerEvent':
				// 同步团队客户
				$businessModel->syncAllCustomers();
				break;
			
			case 'synsMyCustomersEvent':
				// 同步我的客户
				$businessModel->synsMyCustomers();
				break;

			case 'synsConversationRecord':
				// 实时同步获取企业微信会话记录
				$businessModel->synsConversationRecord();
				break;
	
			default:
				//tood..
				break;
		}

		

		echo 'done.';exit;
	}
}