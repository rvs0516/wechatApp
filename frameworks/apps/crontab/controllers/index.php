<?php
/**
 * 本功能主要为全平台提供计划任务
 * 在使用时，计划任务中须加上 key 作为参数，
 * 以防止非服务器之调用
 */
class indexController extends controller {
	public function __construct() {
		//tester
		//$_SERVER['argv'][1] = 'sendMail';

		if (empty($_SERVER['argv'][1])){
			exit;
		}

		parent::__construct();
		load('model.crontab');
		$this->_api = new crontab();
	}

	public function cron() {
		$do = trim($_SERVER['argv'][1]);

		//tester
		//$do = 'fixedRoleSeted';

		switch($do) {
			case 'msIntermodal':
				//$this->_api->msIntermodal('1588123841');
				$this->_api->msIntermodal();
				break;

			case 'gameIngots':
				$this->_api->gameIngots();
				break;

			case 'gameParamConf':
				$this->_api->gameParamConf();
				break;
				
			/*case 'IpUpdate'://新浪接口失效，已停止
				$this->_api->IpUpdate();
				break;
				
			case 'fixedRoleSeted'://因消耗资源过大，且目前作用不大，暂时停止
				$this->_api->fixedRoleSeted();
				break;

			case 'dataMigration'://广告后台不完善，暂时停止
				$this->_api->dataMigration();
				break;*/

			case 'feedbackReduce':
				$this->_api->feedbackReduce();
				break;

			case 'qyPaySwitch':
				$this->_api->qyPaySwitch();
				break;

			case 'roleReduce':
				$this->_api->roleReduce();
				break;

			case 'msProfit':
				/*$day = array('2020-04-29');

				$hours = array('1', '2', '3', '4');
				foreach ($day as $key => $value) {
					foreach ($hours as $key1 => $value1) {
						$this->_api->msProfit($value, $value1);
					}
				}*/
				$this->_api->msProfit();
				break;

			case 'remindToSms':
				$this->_api->remindToSms();
				break;

			case 'profitAdded':
				/*$day = array('2020-12-01','2020-12-02','2020-12-03','2020-12-04','2020-12-05','2020-12-06','2020-12-07','2020-12-08','2020-12-09','2020-12-10','2020-12-11','2020-12-12','2020-12-13','2020-12-14','2020-12-15','2020-12-16','2020-12-17','2020-12-18','2020-12-19','2020-12-20');
				foreach ($day as $key => $value) {
					$this->_api->profitAdded($value);
				}*/
				$this->_api->profitAdded();
				break;
			
			case 'clearTestData':
				$this->_api->clearTestData();
				break;

			case 'ipCheck':
				$this->_api->ipCheck();
				break;

			case 'jzOrderRepost':

				$channelId = ''; // 000066 500001 000368 000020
				$startTime = '';
				$endTime = '';
				
				$this->_api->jzOrderRepost($channelId, $startTime, $endTime);
				break;

			case 'getDkwAssociatedProfit':

				$this->_api->getDkwAssociatedProfit();
				break;

			case 'getYaowanAssociatedProfit':

				$this->_api->getYaowanAssociatedProfit();
				break;		

			case 'diskSpaceOptimize':

				$this->_api->diskSpaceOptimize();
				break;

			case 'outputFiles':
				
				$this->_api->outputFiles();
				break;

			case 'deleteDir':
		
				$this->_api->deleteDir();
				break;
		
			case 'dkwOldReportRelation':
	
				$this->_api->dkwOldReportRelation();
				break;
			
			case 'sendMail':
	
				$this->_api->sendMail();
				break;

			case 'importVip':
			
				$this->_api->importVip();
				break;

			default:
				//tood..
				break;
		}

		echo 'done.';exit;
	}

}