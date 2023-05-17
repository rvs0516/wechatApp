<?php

class ads000020 {
	private $_channel_account_model;

	public function __construct() {
		$this->_channel_account_model = new Model('ms_ads_channel_account');
	}
	/**
	 * 获取渠道广告账户列表
	 */
	public function getAdsDatalist($account, $formType, $start, $end, $offset, $row, $planId, $version='2.0'){
		//查询广告列表(因广告数据值显示有数据的广告，此将查询所有广告并匹配)
		//$url = 'http://sapi.ads.heytapmobi.com/v2/communal/ad/list';
		//$url = 'https://sapi-ads-test.wanyol.com/v3/ad/list';
		

		$accountData = $this->_channel_account_model->get('`account` = "'.$account.'" AND channelId = "000020"');
		$params = explode('|', $accountData['data']);
		$updata = array(
			'owner_id' => $params[0], 
			'api_id' => $params[1], 
			'api_key' => $params[2], 
			'time_stamp' => time(), 
			);

		if ($version == '2.0') {
			$url = 'http://sapi.ads.heytapmobi.com/v2/communal/ad/list';
		}elseif($version == '3.0'){
			$url = 'http://sapi.ads.heytapmobi.com/v3/ad/page';
			$updata['limit'] = 1000;
			$updata['page'] = 1;
		}

		$updata['sign'] = sha1($updata['api_id'].$updata['api_key'].$updata['time_stamp']);
		$token = base64_encode($updata['owner_id'].",".$updata['api_id'].",".$updata['time_stamp'].",".$updata['sign']);
		$result = $this->verify($updata, $token, $url);
		$result = json_decode($result, true);//var_dump($result);exit;

		$adsList =array();
		if ($result['code'] == 0) {
			$ads_model = getInstance('model.ads');
			$allList = $ads_model->getAdData('000020', $formType, $start, $end, null, null, $planId);
			//$total = $ads_model->getAdDataTotal('000020', $formType, $start, $end, $planId);
			if ($version == '2.0') {
				foreach ($result['data']['items'] as $key => $value) {
				foreach ($allList as $k => $v) {
						if ($v['adid'] == $value['adId']) {
							$v['adName'] = $value['adName'];
							$adsList[] = $v;
						}
					}
				}
			}elseif($version == '3.0'){
				foreach ($result['data']['records'] as $key => $value) {
					foreach ($allList as $k => $v) {
						if ($v['adid'] == $value['adId']) {
							$v['adName'] = $value['adName'];
							$v['planId'] = $value['planId'];
							$adsList[] = $v;
						}
					}
				}
			}
			
		}


		$updata['timeLevel'] = "DAY";
		$updata['pageCount'] = 100;
		$updata['beginTime'] = date('Ymd', strtotime($start));
		$updata['ownerId'] = $params[0];
		$updata['endTime'] = date('Ymd', strtotime($end));
		$updata['page'] = 1;

		//因查询广告数据列表
		if ($version == '2.0') {
			$costUrl = 'http://sapi.ads.heytapmobi.com/v2/data/Q/ad/list';
			$costResult = $this->verify($updata, $token, $costUrl);
		}elseif($version == '3.0'){
			//该接口获取异常，用新的接口获取数据
			//$costUrl = 'http://sapi.ads.heytapmobi.com/v3/plan/page';
			//$costResult = $this->verify($updata, $token, $costUrl);
			$updata['paraMap']['groupByColumn'] = "ftime,ad_id";
			$costUrl = 'http://sapi.ads.heytapmobi.com/v3/data/common/query/queryAdData';
			$costResult = $this->verify(json_encode($updata), $token, $costUrl, 'json');
		}
		$costResult = json_decode($costResult, true);

		if ($version == '2.0') {
			$costData = $costResult['data']['items'];
		}elseif($version == '3.0'){
			//$costData = $costResult['data']['records'];
			$costData = $costResult['data']['items'];
		}

		foreach ($adsList as $kr => $vr) {
			foreach ($costData as $kr1 => $vr1) {
				if (($vr['adid'] == $vr1['adId'] && $version == '2.0') || ($vr['adid'] == $vr1['ad_id'] && $version == '3.0')) {

					if ($formType == 3) {
						$adsList[$kr]['cost'] += $vr1['cost'];
						$vr['cost'] += $vr1['cost'];
					}elseif ($formType == 2  && $vr1['statTime'] == date('Ymd', strtotime($vr['day'])) && $version == '2.0' ) {
						$adsList[$kr]['cost'] = $vr1['cost'];
						$vr['cost'] = $vr1['cost'];
					}
				}
				
			}
			if ($kr >= $offset && $kr <= $offset + $row - 1) {
				$list[] = $vr;
			}
		}

		if ($formType != 1) {
			$sum = array();
			foreach ($adsList as $key1 => $value1) {
				$sum['newRegist'] += $value1['newRegist'];
				$sum['newAmount'] += $value1['newAmount'];
				$sum['amount'] += $value1['amount'];
				$sum['cost'] += $value1['cost'];
				$sum['newPayUser'] += $value1['newPayUser'];
				$sum['payUser'] += $value1['payUser'];
			}
		}
		$data = array(
			'list' => $list, 
			'total' => count($adsList), 
			'sum' => $sum, 
			);
		return $data;
	}


	public 	function verify($content, $token, $url, $json=''){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		if ($json == 'json') {
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-type: application/json', 'Authorization:Bearer ' . $token));
		}else{
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization:Bearer ' . $token));
		}
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$result = curl_exec ($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * 格式化导出数据
	 */
	public function formatReport($list, $formType){
			$reports = array();
			foreach ($list as $keyr => $valuer) {
				if ($formType != 3) {
					$reports[$keyr]['date'] = $valuer['day']." ".$valuer['hour'];
				}
				$reports[$keyr]['adid'] = $valuer['adid'];
				$reports[$keyr]['adName'] = $valuer['adName'];
				$reports[$keyr]['newRegist'] = $valuer['newRegist'];
				$reports[$keyr]['newAmount'] = $valuer['newAmount'];
				$reports[$keyr]['amount'] = $valuer['amount'];
			}

			$name = array('日期', '广告ID', '广告名称', '新增注册', '新增充值', '活跃充值');
			if ($formType == 3) {
				unset($name[0]);
			}

			excel_export("《中央数据后台》OPPO广告数据", $name, $reports);
			exit;
	}
}