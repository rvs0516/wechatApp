<?php
//T-坦克2项目返利接口
class benefits0004 {
	private $_benefits_model;

	public function __construct() {
		$this->_benefits_model = new Model('ms_benefits');
	}
	/**
	 * 获取渠道广告账户列表
	 */
	public function grantBenefits($data, $benefitsList, $type){
		//$url = 'http://data.tank2.gzyouai.com:888/tank2_data/qianyou/rebate.do';
		$url = 'http://data.tank2bt2.gzyouai.com/tank2_data/qianyou/rebate.do';
		$roleData = explode('qy_', $data['roleId']);
		if (count($roleData) == 2) {
			$only = $roleData[1];
		}else{
			$only = $data['roleId'];
		}
		
		$reqData = array(
			'rebate_no' => $data['benefitOid'], 
			'user_id' => $data['userName'], 
			'server_id' => $data['serverId'], 
			'role_id' => $only, 
			'time' => time()*1000, 
			'role_name' => $data['roleName'], 
			);


		$sendKey = 'q11minizj_tk2&y11';
		$reqData['gold'] = $data['grantData'];
		$reqData['sign'] = md5($reqData['rebate_no'] . urlencode($reqData['user_id']) . $reqData['gold'] . urlencode($reqData['server_id']) . urlencode($reqData['role_id']) . $reqData['time'] . $sendKey);
		$str = $reqData['rebate_no'] . urlencode($reqData['user_id']) . $reqData['gold'] . urlencode($reqData['server_id']) . urlencode($reqData['role_id']) . $reqData['time'] . $sendKey;
		$result = httpRequest($url . "?" . implodeWithKey('&', $reqData));
		$result = json_decode($result, true);
		//记录请求的返回信息
		load('@mgameapi.model.libs.helperTool');
		$helper = new helperTool();
		$helper->writeLogs(
		C('DEDE_DATA_PATH'),
		'benefits0004',
		json_encode($reqData) . '====' . json_encode($result) . '====' . $url . "?" . implodeWithKey('&', $reqData) . '====' . $str
		);
		if ($result['code'] === 0) {
			return true;
		}else{
			return false;
		}
	}
}