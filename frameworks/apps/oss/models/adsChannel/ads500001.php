<?php

class ads500001 {
	private $_channel_account_model;

	public function __construct() {
		$this->_channel_account_model = new Model('ms_ads_channel_account');
	}


	/**
	 * 获取渠道广告账户列表（查看智能分包归因方式的数据）
	 */
	public function getAdsDatalist($account, $formType, $start, $end, $offset, $row, $planId, $version){
		if ($formType == 1) {
			$type = 'HOUR';
		}elseif ($formType == 2) {
			$type = 'DAY';
		}elseif ($formType == 3) {
			$type = 'SUMMARY';
		}

		// 获取广告账户参数
		$ads_model = getInstance('model.ads');
		$accountData = $this->_channel_account_model->get('`account` = "'.$account.'" AND channelId = "500001"');

		// 获取智能分包的数据
		$adData = $ads_model->getAdData('500001', $formType, $start, $end);

		// 根据指定智能分包渠道包号筛查数据
		if (!empty($planId)) {
			$accountData['data'] = $planId;
		}

		// 获取智能分包渠道包号，注意这个智能分包渠道包号在中间件是存储在adid参数中的
		$params = explode('|', $accountData['data']);

		// 根据中间件后台广告账户参数匹配智能分包渠道号，也就是在中间件后台的广告模块下配置华为的广告账户参数是填的智能分包渠道包号
		$list = array();
		foreach ($adData as $key => $value) {
			foreach ($params as $k => $v) {
				if ($value['adid'] == $v) {
					$list[] = $value;
				}
			}
		}

		foreach ($list as $key => $value) {
			if ($value['adid'] == $accountData['data']) {
				$list[$key]['account'] = $accountData['account'];
			}else{
				foreach ($params as $key1 => $value1) {
					if ($value['adid'] == $value1) {
						$res = Model::getBySql('SELECT account  FROM `ms_ads_channel_account` WHERE `data` = "'.$value1.'"');
						$list[$key]['account'] = $res[0]['account'];
					}
				}
			}
		}

		// 获取渲染数据
		if ($formType != 1) {
				$sum = array();
				foreach ($list as $key1 => $value1) {
					$sum['newRegist'] += $value1['newRegist'];
					$sum['newAmount'] += $value1['newAmount'];
					$sum['amount'] += $value1['amount'];
					$sum['newPayUser'] += $value1['newPayUser'];
					$sum['payUser'] += $value1['payUser'];
				}
			}
		$total = count($list);

		$data = array(
			'list' => $list, 
			'total' => $total, 
			'sum' => $sum, 
			);

		return $data;
	}


	/**
	 * 格式化导出数据
	 */
	public function formatReport($list, $formType){
			$reports = array();
			foreach ($list as $keyr => $valuer) {
				if ($formType != 3) {
					$reports[$keyr]['date'] = $valuer['reportDate'] ? $valuer['reportDate'] : $valuer['reportTime'];
				}
				$reports[$keyr]['adid'] = $valuer['adid'];
				$reports[$keyr]['newRegist'] = $valuer['newRegist'];
				$reports[$keyr]['newAmount'] = $valuer['newAmount'];
				$reports[$keyr]['amount'] = $valuer['amount'];
			}

			$name = array('日期', '广告ID','新增注册', '新增充值', '充值');
			if ($formType == 3) {
				unset($name[0]);
			}

			excel_export("《中央数据后台》华为广告数据", $name, $reports);
			exit;
	}
}