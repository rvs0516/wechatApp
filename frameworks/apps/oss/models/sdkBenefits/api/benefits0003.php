<?php
//T5项目BT返利接口
class benefits0003 {
	private $_benefits_model;

	public function __construct() {
		$this->_benefits_model = new Model('ms_benefits');
	}
	/**
	 * 获取渠道广告账户列表
	 */
	public function grantBenefits($data){
		$url = 'http://app.server.jfcq.50ia.com/Recharge/QybtRebate';
		$roleData = explode('qy_', $data['roleId']);
		if (count($roleData) == 2) {
			$only = $roleData[1];
		}else{
			$only = $data['roleId'];
		}


		$reqData = array(
			'orderId' => $data['benefitOid'], 
			'userId' => $data['userName'], 
			'serverId' => $data['serverId'], 
			'roleId' => $only, 
			'time' => time(), 
			'quantity' => $data['grantData'], 
			'prop' => 'gold', 
			);

		$sendKey = 'q8&rongyaoby&y24';
		ksort($reqData);
		$reqData['sign'] = md5(implodeWithKey('', $reqData).$sendKey);
		$reqData['ext'] = '';

		$result = httpRequest($url, $reqData);
		$result = json_decode($result, true);
		//记录请求的返回信息
		load('@mgameapi.model.libs.helperTool');
		$helper = new helperTool();
		$helper->writeLogs(
		C('DEDE_DATA_PATH'),
		'benefits0003',
		json_encode($reqData) . '====' . json_encode($result) . '====' . $url 
		);
		if ($result['code'] == 1) {
			return true;
		}else{
			return false;
		}
	}
}