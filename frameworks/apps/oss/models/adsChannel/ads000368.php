<?php

class ads000368 {
	private $_channel_account_model;

	public function __construct() {
		$this->_channel_account_model = new Model('ms_ads_channel_account');
	}

	/**
	 * 获取渠道广告账户列表
	 */
	public function getAdsDatalist($account, $formType, $start, $end, $offset, $row, $planId, $version = '2.0', $mediaType, $campaignId, $appPackage, $creativeId, $advertisementName)
	{	
		if ($version == '2.0') {
			$data = $this -> summarySecond($account, $formType, $start, $end, $offset, $row, $planId, $version);
		} elseif ($version == '3.0'){
			$data = $this -> summaryThird($account, $formType, $start, $end, $offset, $row, $planId, $mediaType, $campaignId, $appPackage, $creativeId, $advertisementName);
		}

		return $data;
	}

	/**
     * 查询广告效果数据(V3)
     * 
	 * 获取当前应用绑定的账户下的所有游戏数据
     */
    public function summaryThird($account, $formType, $start, $end, $offset, $row, $planId, $mediaType, $campaignId, $appPackage, $creativeId, $advertisementName)
    {   
		$accountData = $this->_channel_account_model->get('`account` = "'.$account.'" AND channelId = "000368"');

		// 应用ID和应用密钥
		$paramArray = explode('|', $accountData['data']);
		$clientID = $paramArray[0];
        $clientSecret = $paramArray[1];
        $state = $paramArray[2]; // 账号ID

		if ($formType == 1) {
			$type = 'HOUR';
		}elseif ($formType == 2) {
			$type = 'DAY';
		}elseif ($formType == 3) {
			$type = 'SUMMARY';
		}
		$startDate = date('Ymd', strtotime($start));
		$endDate = date('Ymd', strtotime($end));

        $domain = 'https://marketing-api.vivo.com.cn';
        $uri = '/openapi/v1/adstatement/summary/query';

        $filePath = APP_LIST_PATH . "api/models/api/config/adsParam10006.php";
        $config = require($filePath);
        $accessToken = $config[$clientID][$state]['accessToken'];
        $refreshToken = $config[$clientID][$state]['refreshToken'];
        $tokenDate = $config[$clientID][$state]['tokenDate']; // 时间戳(毫秒),accessToken截止的有效日期
        $refreshTokenDate = $config[$clientID][$state]['refreshTokenDate']; // 时间戳(毫秒),refreshTokenDate截止的有效日期
		$currentTime = getMillisecond();

		// accessToken过期，重新获取
		if ($currentTime > $tokenDate && $currentTime < $refreshTokenDate) {
			$accessToken = $this->refreshToken($state, $clientID, $clientSecret, $refreshToken);
		}

        $timestamp = getMillisecond();
        $nonce = createNonceStr(32);

        $url = $domain. $uri. "?access_token=". $accessToken. "&timestamp=". $timestamp. "&nonce=". $nonce;

        $data  = array(
            "startDate" => $startDate, // 统计开始时间
            "endDate" => $endDate,  // 统计结束时间
            "pageSize" => 1000, // 页面数据量大小，文档标注最大不得超过200，实际最大1000
            "summaryType" => $type, // 汇总方式:DAY(按天汇总),HOUR(按小时汇总),SUMMARY(汇总)
            "level" => "CREATIVE", // 层级:ACCOUNT(账户层级),CAMPAIGN(计划层级),GROUP(广告组层级),ADVERTISEMENT(广告层级),CREATIVE(创意层级，默认层级)
        );
		// 按广告id过滤
		if ($planId) {
			$data['filterFieldIds']['advertisementIds'] = array($planId); 
		}
        $data = json_encode($data);

        $resJson = httpRequest($url, $data);
        $result = json_decode($resJson, true);
		if ($result['code'] == 0) {
			$items = $result['data']['items'];

			// 按包名条件过滤
			if ($appPackage) {
				$apItems = array();
				foreach ($items as $key => $value) {
					if ($value['appPackage'] == $appPackage) {
						$apItems[] = $items[$key];
					}
				}
				$items = $apItems;
			}

			// 按创意ID条件过滤
			if ($creativeId) {
				$ciItems = array();
				foreach ($items as $key => $value) {
					if ($value['creativeId'] == $creativeId) {
						$ciItems[] = $items[$key];
					}
				}
				$items = $ciItems;
			}

			// 按广告名称条件过滤
			if ($advertisementName) {
				$adnameItems = array();
				foreach ($items as $key => $value) {
					if ($value['advertisementName'] == $advertisementName) {
						$adnameItems[] = $items[$key];
					}
				}
				$items = $adnameItems;
			}

			// 查询广告计划类型
			$campaignListArray = array();
			for ($i=1; $i < 100; $i++) { 
				$campaignListArray[$i]['campaignList'] = $this->getCampaign($accessToken, $mediaType, $campaignId, $i);
				if (empty($campaignListArray[$i]['campaignList']) || $campaignListArray[$i]['campaignList'] == null) {
					break;
				}
			}
			$campaignList = array();
			foreach ($campaignListArray as $key => $value) {
				if ($value['campaignList'] == null) {
					unset($campaignListArray[$key]);
				}else {
					foreach ($value['campaignList'] as $k => $v) {
						$campaignList[] = $v;
					}
				}
			}

			$allList = array();
			foreach ($items as $key => $value) {
				foreach ($campaignList as $k => $v) {
					if ($value['campaignId'] ==  $v['id'] && $value['campaignName'] ==  $v['name']) {
						$allList[$key] = $items[$key];
						if ($v['mediaType'] === '0') {
							$allList[$key]['mediaType'] = '商店';
						}elseif ($v['mediaType'] == 1) {
							$allList[$key]['mediaType'] = '非商店';
						}elseif ($v['mediaType'] == 2) {
							$allList[$key]['mediaType'] = '联盟';
						} 
					}
				}
			}

			// 获取广告归因上报转化效果数据 包括新增注册、新增充值
			$ads_model = getInstance('model.ads');
			$adData = $ads_model->getAdData('000368', $formType, $start, $end);

			$total = count($allList);
			$list = array();
			$allList = array_reverse($allList); // 翻转数组key顺序
			foreach ($allList as $kr => $vr) {
				if ($kr >= $offset && $kr <= $offset + $row - 1) {
					$list[] = $vr;
				}
			}
			foreach ($list as $key => $value) {
				$list[$key]['newRegist'] = 0;
				$list[$key]['newAmount'] = 0;
				$list[$key]['amount'] = 0;
				foreach ($adData as $k => $v) {
					if ($formType == 1) {
						// 按小时统计
						$date = $v['day'].' '.sprintf("%02d",$v['hour']).':00:00';
						if ($value['reportTime'] == $date && $value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}elseif ($formType == 2) {
						// 按天数统计
						$date = date('Ymd', strtotime($v['day']));
						if ($value['reportDate'] == $date && $value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}elseif ($formType == 3) {
						// 按汇总统计
						if ($value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}
				}
			}
			if ($formType != 1) {
				$sum = array();
				foreach ($list as $key1 => $value1) {
					$sum['showCount'] += $value1['showCount'];
					$sum['clickCount'] += $value1['clickCount'];
					$sum['downloadCount'] += $value1['downloadCount'];
					$sum['spent'] += $value1['spent'];
					$sum['newRegist'] += $value1['newRegist'];
					$sum['newAmount'] += $value1['newAmount'];
					$sum['amount'] += $value1['amount'];
					$sum['newPayUser'] += $value1['newPayUser'];
					$sum['payUser'] += $value1['payUser'];
				}
			}
		}

		$resData = array(
			'list' => $list, 
			'total' => $total, 
			'sum' => $sum, 
			);
		return $resData;
    }

	/**
	 * 查询广告计划
	 */
	public function getCampaign($accessToken, $mediaType, $campaignId, $pageIndex)
	{	
		$timestamp = getMillisecond();
        $nonce = createNonceStr(32);
		$url = 'https://marketing-api.vivo.com.cn/openapi/v1/ad/campaign/pageInfo'. "?access_token=". $accessToken. "&timestamp=". $timestamp. "&nonce=". $nonce;

		$data  = array(
            "adType" => 2, // 推广目标 应用下载:2 普通网址:1 动态商品:3 快生态:8
            "campaignStatus" => 0, // 计划状态 所有数据:0 投放中: 6
			"optimizeFlag" => 0, // 是否使用流量优选：0-否；1-是
			"pageIndex" => $pageIndex, // 起始页，不填时默认查询第一页
			"pageSize" => 100 //页大小，不填默认size=10 最大pageSize：100
        );
		// 按投放渠道/媒体类型过滤 应用商店:0  非应用商店:1  广告联盟:2
		if ($mediaType || $mediaType === '0') {
			$data['mediaType'] = $mediaType;
		}
		// 按计划ID过滤
		if ($campaignId) {
			$data['ids'] = array($campaignId); // 计划ID，多个以List集合形式保存
		}
        $data = json_encode($data);

        $resJson = httpRequest($url, $data);
        $result = json_decode($resJson, true);

		return $result['data']['list'];
	}

	/**
	 * 
	 * 说明：查询广告效果数据(V3)的token过期，使用refreshToken刷新token
	 *
	 */
	public function refreshToken($state, $clientID, $clientSecret, $refreshToken)
	{
		$domain = 'https://marketing-api.vivo.com.cn/openapi/v1/oauth2/refreshToken';
		$url = "http://marketing-api.vivo.com.cn/openapi/v1/oauth2/refreshToken?client_id=$clientID&client_secret=$clientSecret&refresh_token=$refreshToken";

		$resJson = httpRequest($url);
		$resArray = json_decode($resJson, true);
		$data = $resArray['data'];
		
		$filePath = APP_LIST_PATH . "api/models/api/config/adsParam10006.php";
		// 读取文件内容
		$config = include($filePath);
		// 写入新内容
		$config[$clientID][$state]['accessToken'] = $data['access_token'];
		$config[$clientID][$state]['refreshToken'] = $data['refresh_token'];
		$config[$clientID][$state]['tokenDate'] = $data['token_date'];
		$config[$clientID][$state]['refreshTokenDate'] = $data['refresh_token_date'];

		$output = "<?php";
		$output .= "\n";
		$output .= "return ";
		$output .= var_export($config, true);
		$output .= ";";
		file_put_contents($filePath, $output);

		return $data['access_token'];
	}

	/**
     * 查询广告效果数据(V2)
     * 
     */
	public function summarySecond($account, $formType, $start, $end, $offset, $row, $planId, $version)
	{
		if ($formType == 1) {
			$type = 'HOUR';
		}elseif ($formType == 2) {
			$type = 'DAY';
		}elseif ($formType == 3) {
			$type = 'SUMMARY';
		}
		$startDate = date('Ymd', strtotime($start));
		$endDate = date('Ymd', strtotime($end));
		$url = 'https://ad-market.vivo.com.cn/v1/adStatement/query';

		$accountData = $this->_channel_account_model->get('`account` = "'.$account.'" AND channelId = "000368"');
		$params = explode('|', $accountData['data']);
		/*$apiKey = 'd0f7e9806ccac34227d95e6d71fb913d';
		$apiUuid = 'b84c02c655fcd28269a0bc0f8b41deeb';*/
		$select = '';
		if (!empty($planId)) {
			$select = ',"filterFiledIds":{"creativeIds":['.$planId.']}';
		}
		$apiKey = $params[0];
		$apiUuid = $params[1];
		$requestStr = '{"startDate":"'.$startDate.'","endDate":"'.$endDate.'","summaryType":"'.$type.'","pageSize":"10000"'.$select.'}';

		$sign  = strtoupper(hash("sha256", $apiKey.$requestStr));

		$data['apiUuid'] = $apiUuid;
		$data['requestStr'] = $requestStr;
		$data['sign'] = $sign;
		$result = httpRequest($url, $data);
		$result = json_decode($result, true);
		// var_dump($planId);var_dump($requestStr);var_dump($result);exit;
		if ($result['code'] == 0) {
			$allList = $result['data']['list'];
			$ads_model = getInstance('model.ads');
			$adData = $ads_model->getAdData('000368', $formType, $start, $end);
			$total = count($allList);
			$list = array();
			$allList=array_reverse($allList);
			foreach ($allList as $kr => $vr) {
				if ($kr >= $offset && $kr <= $offset + $row - 1) {
					$list[] = $vr;
				}
			}
			//var_dump($list);var_dump($adData);exit;
			foreach ($list as $key => $value) {
				$list[$key]['newRegist'] = 0;
				$list[$key]['newAmount'] = 0;
				$list[$key]['amount'] = 0;
				foreach ($adData as $k => $v) {
					if ($formType == 1) {
						// 按小时统计
						$date = $v['day'].' '.sprintf("%02d",$v['hour']).':00:00';
						if ($value['reportTime'] == $date && $value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}elseif ($formType == 2) {
						// 按天数统计
						$date = date('Ymd', strtotime($v['day']));
						if ($value['reportDate'] == $date && $value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}elseif ($formType == 3) {
						// 按汇总统计
						if ($value['creativeId'] == $v['adid'] ) {
							$list[$key]['newRegist'] = $v['newRegist'];
							$list[$key]['newAmount'] = $v['newAmount'];
							$list[$key]['amount'] = $v['amount'];
							$list[$key]['newPayUser'] = $v['newPayUser'];
							$list[$key]['payUser'] = $v['payUser'];
						}
					}
				}
			}
			if ($formType != 1) {
				$sum = array();
				foreach ($list as $key1 => $value1) {
					$sum['showCount'] += $value1['showCount'];
					$sum['clickCount'] += $value1['clickCount'];
					$sum['downloadCount'] += $value1['downloadCount'];
					$sum['spent'] += $value1['spent'];
					$sum['newRegist'] += $value1['newRegist'];
					$sum['newAmount'] += $value1['newAmount'];
					$sum['amount'] += $value1['amount'];
					$sum['newPayUser'] += $value1['newPayUser'];
					$sum['payUser'] += $value1['payUser'];
				}
			}
		}

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
	public function formatReport($list, $formType)
	{
		$reports = array();
		foreach ($list as $keyr => $valuer) {
			if ($formType != 3) {
				$reports[$keyr]['date'] = $valuer['reportDate'] ? $valuer['reportDate'] : $valuer['reportTime'];
			}
			$reports[$keyr]['creativeId'] = $valuer['creativeId'];
			$reports[$keyr]['advertisementId'] = $valuer['advertisementId'];
			$reports[$keyr]['advertisementName'] = $valuer['advertisementName'];
			$reports[$keyr]['appPackage'] = $valuer['appPackage'];
			$reports[$keyr]['showCount'] = $valuer['showCount'];
			$reports[$keyr]['clickCount'] = $valuer['clickCount'];
			$reports[$keyr]['downloadCount'] = $valuer['downloadCount'];
			$reports[$keyr]['spent'] = $valuer['spent'];
			$reports[$keyr]['newRegist'] = $valuer['newRegist'];
			$reports[$keyr]['newAmount'] = $valuer['newAmount'];
			$reports[$keyr]['amount'] = $valuer['amount'];
		}

		$name = array('日期', '创意ID', '广告ID', '广告名称', '包名', '展示量', '点击量', '下载量', '花费', '新增注册', '新增充值', '充值');
		if ($formType == 3) {
			unset($name[0]);
		}

		excel_export("《中央数据后台》VIVO广告数据", $name, $reports);
		exit;
	}
}