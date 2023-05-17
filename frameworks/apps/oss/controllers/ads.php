<?php

require_once APP_CONTROLLER_PATH . '/master.php';
//入口类
class adsController extends masterControl  {


	public function adsAccount() {
		$operation_list = array('index', 'edit', 'save', 'add', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		$this->assign('operation', $operation);
		$channel = trim($_REQUEST['channel']);
		$account = trim($_REQUEST['account']);
		$channel_account_model = new Model('ms_ads_channel_account');
		$channel_model = getInstance('model.sdkChannel.channel');
		$gidarr = $channel_model->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);
		$this->assign('gid', $gid);
		$ads_model = getInstance('model.ads');
		require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
		$this->assign('channels', $channels);
		switch ($operation) {
			case 'index':
				$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
				$this->assign('list_page', $page);
				$row = 25;
				$offset = ($page - 1) * $row;
				$this->assign('list_length', $row);
				$list = $ads_model->getAccountList($offset, $row, $channel, $account);
				$total = $ads_model->getAccountTotal($channel, $account);
				foreach ($list as $key => $value) {
					$list[$key]['channelName'] = $channels[$value['channelId']];
				}
				$this->assign('list', $list);
				$this->assign('list_total', $total);
				$this->assign('page', $page);
				$this->assign('list_length', $row);
				$this->assign('channel', $channel);
				$this->assign('account', $account);
				break;
			
			case 'add':
				# code...
				break;

			case 'edit':
				$accountData = $channel_account_model->get('`id` = '. $_GET['id']);
				$this->assign('accountData', $accountData);
				break;

			case 'save':
				if (empty($channel) || empty($_POST['data'])) {
					ShowMsg('参数不能为空', -1);
					exit;
				}
				$array = array(
					'channelId' => $channel,  
					'data' => trim($_POST['data']), 
					);
				$existing = $channel_account_model->get('`id` = '. $_POST['id']);

				if (!$existing) {
					if (empty($account)) {
						ShowMsg('账户不能为空', -1);
						exit;
					}
					$array['account'] = $account;
					$channel_account_model->set($array);
				}else{
					$channel_account_model->set($array,"`id`='" . $_POST['id'] . "'");
				}
				ShowMsg('操作成功', '/index.php?m=ads&a=adsAccount');
				exit;
				break;
			case 'del':
				$channel_account_model->delete('`id` = '. $_GET['id']);
				ShowMsg('操作成功', '/index.php?m=ads&a=adsAccount');exit;
				break;
		}
	}

	public function advertising() {
		$channelModel = getInstance('model.sdkChannel.channel');
		$gidarr = $channelModel->returnUidGroup($this->_uid);
		$gid = intval($gidarr[0]['gid']);

		$this->assign('gid', $gid);
		if ($gid == '18') {
			$ads = explode(',', $gidarr[0]['ads']);
			require_once APP_LIST_PATH . 'main/models/api/anyChannels.php';
			$channelName = $channels[$ads[0]];
			$adsChannel = $ads[0];
			$this->assign('adsChannel', $adsChannel);
			$this->assign('channelName', $channelName);
		}
		
		$ads_model = getInstance('model.ads');
		$accountList = $ads_model->getAccountList($offset, $row);
		$this->assign('accountList', $accountList);

		$channel = trim($_REQUEST['channel']);
		$account = trim($_REQUEST['account']);
		$formType = trim($_REQUEST['formType']);
		$planId = trim($_REQUEST['planId']);
		$version = $channel == '000020' ? trim($_REQUEST['version']) : '';
		$mediaType = trim($_REQUEST['mediaType']);
		$campaignId = trim($_REQUEST['campaignId']);
		$appPackage = trim($_REQUEST['appPackage']);
		$creativeId = trim($_REQUEST['creativeId']);
		$advertisementName = trim($_REQUEST['advertisementName']);

		/*$start = date('Ymd', strtotime($_POST['start_date']));
		$end = date('Ymd', strtotime($_POST['end_date']));*/

		$this->assign('channel', $channel);
		$this->assign('account', $account);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('formType', $_REQUEST['formType']);
		$this->assign('planId', $planId);
		$this->assign('version', $_REQUEST['version']);
		$this->assign('mediaType', $mediaType);
		$this->assign('campaignId', $campaignId);
		$this->assign('appPackage', $appPackage);
		$this->assign('creativeId', $creativeId);
		$this->assign('advertisementName', $advertisementName);
		
		//$channel_account_model = new Model('ms_ads_channel_account');
		if (!empty($channel)) {
			if (empty($_REQUEST['start_date']) || empty($_REQUEST['end_date']) || empty($_REQUEST['account'])) {
				ShowMsg('参数不为空', -1);
				exit;
			}
		}
		if($_POST['operation'] === 'report') {
			$page = 1;
			$row = 2000;
			$offset = 0;
		}else {
			//查询数据
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
			$row = 25;
			$offset = ($page - 1) * $row;
		}
		//$total = 4;
		$this->assign('list_length', $row);
		$this->assign('page', $page);
		$td = (strtotime($_REQUEST['end_date']) - strtotime($_REQUEST['start_date'])) / 86400;
		if ($formType == 1) {
			if ($td > 6) {
				ShowMsg('类型为“小时”选择的时间范围不能超过7天', -1);
				exit;
			}
		}else{
			if ($td > 89) {
				ShowMsg('选择的时间范围不能超过90天', -1);
				exit;
			}
		}

		if ($channel) {
			$adsChannelModel = getInstance('model.adsChannel.ads'.$channel );
			$version = trim($_REQUEST['version']);
			$list = $adsChannelModel->getAdsDatalist($account, $formType, $_REQUEST['start_date'], $_REQUEST['end_date'], $offset, $row, $planId, $version, $mediaType, $campaignId, $appPackage, $creativeId, $advertisementName);
			if($_POST['operation'] === 'report') {
				$adsChannelModel->formatReport($list['list'], $formType);
			}
		}

		//if ($channel == '000368') {
			/*if ($formType == 1) {
				$type = 'HOUR';
			}elseif ($formType == 2) {
				$type = 'DAY';
			}elseif ($formType == 3) {
				$type = 'SUMMARY';
			}
			$url = 'https://ad-market.vivo.com.cn/v1/adStatement/query';


			$accountData = $channel_account_model->get('`account` = "'.$account.'" AND channelId = "000368"');
			$params = explode('|', $accountData['data']);*/
			/*$apiKey = 'd0f7e9806ccac34227d95e6d71fb913d';
			$apiUuid = 'b84c02c655fcd28269a0bc0f8b41deeb';*/
			/*$apiKey = $params[0];
			$apiUuid = $params[1];
			$requestStr = '{"startDate":"'.$start.'","endDate":"'.$end.'","summaryType":"'.$type.'"}';

			$sign  = strtoupper(hash("sha256", $apiKey.$requestStr));

			$data['apiUuid'] = $apiUuid;
			$data['requestStr'] = $requestStr;
			$data['sign'] = $sign;
			$result = httpRequest($url, $data);
			$result = json_decode($result, true);
			if ($result['code'] == 0) {
				$list = $result['data']['list'];
				$ads_model = getInstance('model.ads');
				$adAmount = $ads_model->getAdAmount($channel, $formType, $_POST['start_date'], $_POST['end_date']);

				foreach ($list as $key => $value) {
					$list[$key]['amount'] = 0;
					foreach ($adAmount as $k => $v) {
						if ($formType == 1) {
							$date = $v['date'].':00:00';
							if ($value['reportTime'] == $date && $value['creativeId'] == $v['adid'] ) {
								$list[$key]['amount'] = $v['amount'];
							}
						}elseif ($formType == 2) {
							if ($value['reportDate'] == $v['date'] && $value['creativeId'] == $v['adid'] ) {
								$list[$key]['amount'] = $v['amount'];
							}
						}elseif ($formType == 3) {
							if ($value['creativeId'] == $v['adid'] ) {
								$list[$key]['amount'] = $v['amount'];
							}
						}
					}
				}
				$this->assign('list', $list);
			}*/
		//}elseif ($channel == '000020') {
			/*$url = 'https://sapi.ads.heytapmobi.com/v2/communal/ad/list';

			$accountData = $channel_account_model->get('`account` = "'.$account.'" AND channelId = "000020"');
			$params = explode('|', $accountData['data']);
			$updata = array(
				'owner_id' => $params[0], 
				'api_id' => $params[1], 
				'api_key' => $params[2], 
				'time_stamp' => time(), 
				);*/

			//var_dump($adAmount);exit;
			/*$updata = array(
				'owner_id' => '1000070527', 
				'api_id' => 'fc1a54e42fdc439988817b55422a1134', 
				'api_key' => 'f48b5c97c418496aad7ac890aa977f2e', 
				'time_stamp' => time(), 
				);*/
			/*$updata['sign'] = sha1($updata['api_id'].$updata['api_key'].$updata['time_stamp']);
			$token = base64_encode($updata['owner_id'].",".$updata['api_id'].",".$updata['time_stamp'].",".$updata['sign']);
			$result = $this->verify($updata, $token, $url);
			$result = json_decode($result, true);
			if ($result['code'] == 0) {
				$ads_model = getInstance('model.ads');
				$adAmount = $ads_model->getAdAmount($channel, $formType, $_POST['start_date'], $_POST['end_date']);
				foreach ($result['data']['items'] as $key => $value) {
					foreach ($adAmount as $k => $v) {
						if ($v['adid'] == $value['adId']) {
							$adAmount[$k]['adName'] = $value['adName'];
						}
					}
				}
			}
			/*$ads_model = getInstance('model.ads');
			$adAmount = $ads_model->getAdAmount($channel, $formType, $_POST['start_date'], $_POST['end_date']);
			var_dump($result);exit;*/
			//$this->assign('list', $adAmount);
			/*var_dump($updata);
			var_dump($adList);exit;
			if ($formType == 1) {
				$type = 'HOUR';
			}elseif ($formType == 2) {
				$type = 'DAY';
			}elseif ($formType == 3) {
				$type = 'SUMMARY';
			}*/
		//}
		$this->assign('list', $list['list']);
		$this->assign('list_total', $list['total']);
		$this->assign('sum', $list['sum']);
	}

	/**
	 * 获取渠道用户号
	 */
	public function getChannelAccount() {

		$option = '';
		//$_POST['channel'] = '000368';
		if (!empty($_POST['channel'])) {
			$channelModel = getInstance('model.sdkChannel.channel');
			$gidarr = $channelModel->returnUidGroup($this->_uid);
			$gid = intval($gidarr[0]['gid']);

			$this->assign('gid', $gid);
			if ($gid == '18') {
				$ads = explode(',', $gidarr[0]['ads']);
				$sql= "SELECT account FROM ms_ads_channel_account WHERE channelId = '{$ads[0]}' AND account = '{$ads[1]}'";
			}else{
				$sql= "SELECT account FROM ms_ads_channel_account WHERE channelId = '{$_POST['channel']}'";
			}
			
			$account = Model::getBySql($sql);

			if ($account) {
				$option .= '<option value="">请选择</option>';
				$accountList = '';
				foreach ($account as $key => $value){
					if ($value['account']) {
						$accountList = (trim($_POST['account']) == $value['account']) ? 'selected="selected"' : '';
						$option .= '<option value="' . $value['account'] . '"' . $accountList . '>' . $value['account'] . '</option>';
					}
				}
			}
		}

		if (empty($option)) {
			$option = '<option value="">无相关数据</option>';
		}

		echo $option;exit;
	}
}