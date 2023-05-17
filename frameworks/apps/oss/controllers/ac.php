<?php
/**
 * Account Checking 对账
 */
require_once APP_CONTROLLER_PATH . '/master.php';


class acController extends masterControl{
	
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * 充值汇总
     */
	public function sumpay(){
		$this->checkLogin();
		//取得游戏列表		
		load('model.channel');
		$userObj = getInstance('model.user');
		$this->assign('gamesName', $userObj->get_games(0, 0));	
		$this->assign('channel', channel::$channeler);
				
  	    $acob = getInstance('model.ac');
		$dailyData = $acob->get_payment($_GET);
		$monthData = $acob->get_payment(array(), 'month');	
		$yearData  = $acob->get_payment(array(), 'year');	
		
		$isajax = array_key_exists('isajax', $_GET) ? intval($_GET['isajax']) : 0;
		//ajax
		if ($isajax == 1) {
			$acob->list_search_ajax($_GET, $dailyData);
		}	
		$this->assign('daily', $dailyData);
		$this->assign('month', $monthData);
		$this->assign('year',  $yearData);
	}
	/**
	 * 
	 * 充值查询
	 */
	public function querypay(){
		$this->checkLogin();
  	    $acob = getInstance('model.ac');
		if (intval($_GET['isajax']) == 1 ) {
			@header("Content-type: application/json");
			$data = $acob->search_user_info($_GET);	
			echo json_encode($data);
			exit;
		}
	}
	/**
	 * 
	 * 订单信息查询
	 */
	public function oinfo(){
		$this->checkLogin();
  	    $acob = getInstance('model.ac');
		//ajax
		if (intval($_GET['isajax']) == 1 ) {
			@header("Content-type: application/json");
			$data = $acob->search_order_info($_GET);		
			echo json_encode($data);
			exit;
		}
	}

	/**
	 * 
	 * 持有飞币玩家汇总
	 */
	public function coinshold(){
		$this->checkLogin();
  	    $acob = getInstance('model.ac');
		$holder = $acob->get_coins_holders();		
		$this->assign('holder', $holder);  	    
	}
	
	/**
	 * 
	 * 平台每日飞币剩余汇总
	 */
	public function dailycoins(){
		$this->checkLogin();
  	    $acob = getInstance('model.ac');
  	    $isajax = array_key_exists('isajax', $_GET) ? intval($_GET['isajax']) : 0;
  	    
		if ($isajax == 1) {
			@header("Content-type: application/json");
			$data = $acob->get_daily_coins($_GET);
			echo json_encode($data);
			exit;
		}
  	    
		$data = $acob->get_daily_coins(array());		
		$this->assign('xdata', $data);  	    
	}
	
	/**
	 * 查找差异
	 */
	public function finddifference(){
		$this->checkLogin();
		$acMod = getInstance('model.ac');
		if( empty($_GET['type']) ||
			empty($_GET['project']) ||
			empty($_GET['specific']) ||
			empty($_GET['ordertime'])
		){
			ShowMsg('-1', '参数不足或有误');
		}
		$type      = $_GET['type'];
		$project   = $_GET['project'];
		$specific  = $_GET['specific'];
		$ordertime = $_GET['ordertime'];
		$filename  = $type . $project . $specific . $ordertime;
		$filepath  = C('ORDER_FILE_PATH');
		$fileallpath = $filepath . $filename . '.csv';
		if($_FILES['orderfile']){
			load('uploadfile');
			$filetypes = array('csv');
 			$upload = new uploadfile($_FILES['orderfile'], $filepath, 1000000, $filetypes);
 			$num = $upload->upload($filename);
 			if($num<1){
 				ShowMsg('上传文件失败','-1');
 			}
		}
		if(file_exists($fileallpath)){
			$source_list = $acMod->getSourceList($type, $project, $specific, $ordertime);
			$target_list = $acMod->getTargetList($fileallpath);
			$this->assign('fileurl','/static/orderfile/'. $filename . '.csv');
			$diff_source_list = array();
			foreach ($source_list as $key => $val){
				if(!isset($target_list[$key])){
					$diff_source_list[$key] = $source_list[$key];		
				}else{
					if($source_list[$key]['gold'] != $target_list[$key]['gold']){
						$diff_source_list[$key] = $source_list[$key];
					}
				}
			}
			foreach ($target_list as $key => $val){
				if(!isset($source_list[$key])){
					$diff_target_list[$key] = $target_list[$key];		
				}else{
					if($source_list[$key]['gold'] != $target_list[$key]['gold']){
						$diff_target_list[$key] = $target_list[$key];
					}
				}
			}
			$this->assign('diff_source_list',$diff_source_list);
			$this->assign('diff_target_list',$diff_target_list);
		}
		$acconfig    = C('ac');
			$mycards = C('mycard');
			$specifictitle = isset($mycards[$specific]) ? $mycards[$specific] : $specific;
			$header = array(
				'typetitle' => $acconfig[$type]['title'],
				'projecttitle' =>  $acconfig[$type]['project'][$project],
				'specifictitle' => $specifictitle,
			);
			$this->assign('header', $header);
	}
	/**
	 * 平台与金流对账
	 */
	public function checkgoldstream(){
		$this->checkLogin();
		$acMod = getInstance('model.ac');
		if(isset($_GET['action']) && $_GET['action'] == 'ajax'){
				if($acMod->confirmOrder($_POST, 'goldstream')){
					echo json_encode(array('code' => 1));
				}else{
					echo json_encode(array('code' => 0));
				}
				exit;
		}
		$year  = isset($_POST['year']) ? $_POST['year'] : '2012';
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$goldtype = isset($_POST['goldtype']) ? $_POST['goldtype'] : null;
		$isconfirm = isset($_POST['isconfirm']) ? $_POST['isconfirm'] : null;
		$ordertime = empty($month) ? null : ($year . sprintf("%02d", $month));
		$confirmOrders = $acMod->getConfirmOrders('goldstream', $goldtype, $ordertime);
		$total = $acMod->getGoldstreamsTotal($year, $month, $goldtype);
		if($total && count($total)){
			$mycards = C('mycard');
			foreach ($total as $key => $val){
				$time = $val['ym'].sprintf("%02d", $val['orderdate']);
				$total[$key]['starttime'] = date('Y-m-d', strtotime($time)); 
				$total[$key]['endtime'] = date('Y-m-d', strtotime("$time +1 month -1 day"));
				$total[$key]['specific'] = $mycards[$val['pcode']];
				foreach ($confirmOrders as $key1 => $val1){
					if($val['goldtype'] == $val1['project'] && $val['pcode'] == $val1['specific'] && $val['ym'] == $val1['ordertime']){
						$total[$key]['confirmOrder'] = 1;
					}
				}
				if($isconfirm == 1 && $total[$key]['confirmOrder'] != 1){
					unset($total[$key]);
				}else if($isconfirm == 2 && $total[$key]['confirmOrder'] == 1){
					unset($total[$key]);
				}
			}
		}
		$acconfig    = C('ac');
		$goldstreamsInfo = $acconfig['goldstream']['project'];
		$this->assign(
			array(  'goldstreamsInfo' => $goldstreamsInfo,
					'total' => $total,
					'year'  => $year,
					'month' => $month,
					'goldtype'  => $goldtype,
			));
	}
	
	/**
	 * 平台与游戏对账
	 */
	public function checkgame(){
		$this->checkLogin();
		$acMod = getInstance('model.ac');
		if(isset($_GET['action']) && $_GET['action'] == 'ajax'){
				if($acMod->confirmOrder($_POST, 'game')){
					echo json_encode(array('code' => 1));
				}else{
					echo json_encode(array('code' => 0));
				}
				exit;
		}
		$year  = isset($_POST['year']) ? $_POST['year'] : '2012';
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$game  = isset($_POST['game']) ? $_POST['game'] : null;
		$isconfirm = isset($_POST['isconfirm']) ? $_POST['isconfirm'] : null;
		$ordertime = empty($month) ? null : ($year . sprintf("%02d", $month));
		$confirmOrders = $acMod->getConfirmOrders('game', $game, $ordertime);
		$acconfig    = C('ac');
		$total = $acMod->getGameTotal($year, $month, $game, $acconfig['game']['project']);
		if($total && count($total)){
			foreach ($total as $key => $val){
				$time = $val['ym'].sprintf("%02d", $val['orderdate']);
				$total[$key]['starttime'] = date('Y-m-d', strtotime($time)); 
				$total[$key]['endtime'] = date('Y-m-d', strtotime("$time +1 month -1 day"));
				$total[$key]['gamename'] = $acconfig['game']['project'][$val['game']];
				foreach ($confirmOrders as $key1 => $val1){
					if($val['game'] == $val1['project'] && $val['server'] == $val1['specific'] && $val['ym'] == $val1['ordertime']){
						$total[$key]['confirmOrder'] = 1;
					}
				}
				if($isconfirm == 1 && $total[$key]['confirmOrder'] != 1){
					unset($total[$key]);
				}else if($isconfirm == 2 && $total[$key]['confirmOrder'] == 1){
					unset($total[$key]);
				}
			}
		}
		$this->assign(
			array(  'gameInfo' => $acconfig['game']['project'],
					'total' => $total,
					'year'  => $year,
					'month' => $month,
					'game'  => $game,
			));
	}
	
	/**
	 * 平台与联运对账
	 */
	public function checkagent(){
		$this->checkLogin();
		$acMod = getInstance('model.ac');
		if(isset($_GET['action']) && $_GET['action'] == 'ajax'){
				if($acMod->confirmOrder($_POST, 'agent')){
					echo json_encode(array('code' => 1));
				}else{
					echo json_encode(array('code' => 0));
				}
				exit;
		}
		$year  = isset($_POST['year']) ? $_POST['year'] : '2012';
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$agent  = isset($_POST['agent']) ? $_POST['agent'] : null;
		$isconfirm = isset($_POST['isconfirm']) ? $_POST['isconfirm'] : null;
		$ordertime = empty($month) ? null : ($year . sprintf("%02d", $month));
		$confirmOrders = $acMod->getConfirmOrders('agent', $agent, $ordertime);
		$acconfig    = C('ac');
		$agents = $acMod->getAgentList();
		$total = $acMod->getAgentTotal($year, $month, $agent, $agents);
		if($total && count($total)){
			foreach ($total as $key => $val){
				$time = $val['ym'].sprintf("%02d", $val['orderdate']);
				$total[$key]['starttime'] = date('Y-m-d', strtotime($time));
				$total[$key]['endtime'] = date('Y-m-d', strtotime("$time +1 month -1 day"));
				$total[$key]['gamename'] = $acconfig['game']['project'][$val['game']];
				foreach ($confirmOrders as $key1 => $val1){
					if($val['agentname'] == $val1['project'] && $val['game'] == $val1['specific'] && $val['ym'] == $val1['ordertime']){
						$total[$key]['confirmOrder'] = 1;
					}
				}
				if($isconfirm == 1 && $total[$key]['confirmOrder'] != 1){
					unset($total[$key]);
				}else if($isconfirm == 2 && $total[$key]['confirmOrder'] == 1){
					unset($total[$key]);
				}
			}
		}
		$this->assign(
			array(
				'agents' => $agents,
				'total' => $total)
			);
	}
	
	/**
	 * 列出录入订单
	 */
	public function listorderdate(){
		$this->checkLogin();
		$acconfig = C('ac');
		$type 	 = isset($_POST['type']) ? $_POST['type'] : null;
		$project = isset($_POST['project']) ? $_POST['project'] : null;
		$acMod    = getInstance('model.ac');
		$orderdateList  = $acMod->getOrderdateList($type, $project);
		
		$agents = $acMod->getAgentList();
		foreach ($agents as $val){
			$agentlist[$val['channel']]=$val['channel'];
		}
		foreach ($acconfig as $key => $val){
			if($key == 'agent'){
				$acconfig[$key]['project']=$agentlist;
			}
		}
		
		foreach ($orderdateList as $key => $val){
			$orderdateList[$key]['typetitle'] = $acconfig[$val['type']]['title'];
			if($val['type'] == 'agent'){
				$orderdateList[$key]['projecttitle'] = $val['project'];
			}else{
				$orderdateList[$key]['projecttitle'] = $acconfig[$val['type']]['project'][$val['project']];
			}
		}
		$this->assign(array(
			'type' 	   => $type,
			'project'  => $project,
			'acconfig' => $acconfig,
			'orderdateList'   => $orderdateList,
			)
		);
	}

	/**
	 * 录入对账时间
	 */
	public function editorderdate(){
		$this->checkLogin();
		$acMod = getInstance('model.ac');
		$odid = empty($_GET['odid']) ?  null : $_GET['odid'];
		//提交表单，录入数据库
		if($_POST){
			list($ret, $code, $msg) = $acMod->editOrderdate($_POST, $odid);
			if($ret === true){
				ShowMsg($msg, '?m=ac&a=listorderdate');
			}else{
				ShowMsg($msg, '-1');
			}
		}
		if($odid){
			$orderdate = $acMod->getOrderdate($odid);
			if(empty($orderdate)){
				ShowMsg('不存在此对账时间记录', '?m=ac&a=listorderdate');
			}
			$this->assign('orderdate',$orderdate);
		}
		$acconfig = C('ac');
		$agents = $acMod->getAgentList();
		foreach ($agents as $val){
			$agentlist[$val['channel']]=$val['channel'];
		}
		foreach ($acconfig as $key => $val){
			if($key == 'agent'){
				$acconfig[$key]['project']=$agentlist;
			}
		}
		$this->assign('acconfig',$acconfig);
	}
	
	/**
	 * 向gash查詢訂單信息
	 */
	public function getOrderFromGash() {
		$this->checkLogin();
		//引入pay配置文件
		$pay_config = loadC('config.inc.php', 'pay');
		$gash_config = $pay_config['gash'];
		$ordercode = $_REQUEST['ordercode'];
		//獲取訂單信息
		$acob = getInstance('model.ac');
		$order_info = $acob->search_order_info( array('order' => $ordercode), true );
		if($order_info['code'] == 0) {
			exit($order_info['msg']);
		}
		
		if($order_info['paytype'] == 8) {			
			if ($order_info['paycode'] == '卡密儲值') {
				if ($order_info['areaid'] == 2) {
					$gash_type = 'gashoverseacard';
				}else {
					$gash_type = 'gashcard';
				}
			}else {
				$gash_type = 'telecom';
			}
		}else {
			exit('訂單類型錯誤');
		}
		
        load('@pay.model.gashSetting');
		$gash_token = gashSetting::getKeysByVersion($order_info['gash_version'], $order_info['gash_item'], $gash_type);
		$order = getInstance('model.gashOrder');
		//配置
		$order->setConfig($gash_token['pas'], $gash_token['key1'], $gash_token['key2'], $gash_config['checkorder_url']);
		//執行查詢
        $money = empty($order_info['realmoney']) ? $order_info['price'] : $order_info['realmoney'];
        $curreny = empty($order_info['currency']) ? 'TWD' : $order_info['currency'];
		$trans_data = $order->check($gash_token['cid'], $order_info['oid'], $money, $curreny);
		if($order->getRecode() !== '0000' && $order->getPayStatus() === 'S') {
			$error_msg = $gash_config['error_msg'][$order->getRecode()];
			if(empty($error_msg)) {
				$error_msg = '未知錯誤';
			}
			exit($error_msg);
		}
		if($order->getRecode() === '0000' && $order->getPayStatus() !== 'S') {
			$error_msg = $gash_config['pay_msg'][$order->getPayStatus()];
			if(empty($error_msg)) {
				$error_msg = '未知錯誤';
			}
			exit($error_msg);
		}
		$this->assign('order', $order_info);
		$this->assign('trans_data', $trans_data);
	}
	
	/**
	 * 將訂單設置為有效
	 */
	public function setOrderInEffect() {
		$this->checkLogin();
		$ordercode = $_REQUEST['ordercode'];
		
		$result = array(
			'code' => 2,
			'msg' => '設置失敗'
		);
		//獲取訂單信息
		$acob = getInstance('model.ac');
		$order_info = $acob->search_order_info( array('order' => $ordercode), true );
		if($ordercode) {
			//引入pay配置文件以及pay類，執行請款操作（無論曾經是否請款成功）
			$pay_config = require C('PAY_APP_PATH') . 'config.inc.php';
			require C('PAY_APP_PATH') . 'models/gash/pay.php';
			require C('PAY_APP_PATH') . 'models/gash/gash_common.php';
			require C('DEDEDATA') .'enums/games_transform.php';
			$pay_model = new pay();
			$types = $pay_model->getOrderType($order_info['paycode'], $pay_config['gash']);
			$trans = new Trans();
            $money = empty($order_info['realmoney']) ? $order_info['price'] : $order_info['realmoney'];
			$settle_result = $pay_model->doSettle($order_info['oid'], $money, $order_info['currency'], $transform_scale, $trans, $pay_config['gash']);
			//請款成功會自動更改訂單狀態，因此不需要再進行任何改動操作
			if($settle_result) {
				$result['code'] = 1;
				$result['msg'] = '設置成功';
			} else {
				$result['code'] = 0;
				$result['msg'] = '請款失敗';
			}
		}
		exit(json_encode($result));
	}
	
	/**
	 * 更正订单金额
	 */
	public function changeOrderMoney() {
        if($_POST) {
            $ac_model = getInstance('model.ac');
            echo json_encode(
                $ac_model->changeOrderMoney($_REQUEST['ordercode'], $_REQUEST['money'], $_REQUEST['currency'])
            );
            exit;
        }
        $this->assign('oid', $_REQUEST['ordercode']);
	}
    
    /**
     * 更改联运商订单信息
     */
    public function changeAgentOrderInfo() {
        if($_POST) {
            $ac_model = getInstance('model.ac');
            echo json_encode(
                $ac_model->changeAgentOrderInfo($_REQUEST['oid'], $_REQUEST)
            );
            exit;
        }
        $agent_order_model = new model('ms_agent_orders');
        $oid = mysql_real_escape_string($_REQUEST['oid']);
        $order = $agent_order_model->get("oid='$oid'");
        $this->assign('order', $order);
        $currency_list = array('USD', 'CAD', 'AUD', 'NZD', 'SGD', 'MYR', 'HKD', 'RMB', 'TWD');
        $this->assign('oid', $_REQUEST['oid']);
        $this->assign('currency_list', $currency_list);
    }
	
	/**
	 * facebook报表导出
	 */
	public function facebookReports() {
		if($_POST) {
			$start_time = strtotime($_POST['start_date']);
			$end_time = strtotime($_POST['end_date']);
			if(empty($start_time) || empty($end_time) || $start_time > $end_time) {
				showMsg('請選擇正確的日期');
			}
			$date_list = $this->_getDateList($start_time, $end_time);
			
			//扫描报表目录，得到所有报表
			$payment_dir = C('DEDE_DATA_PATH') . '/fbpayment';
			$dir_list = scandir($payment_dir);
			$payment_list = array();
			foreach($dir_list as $filename) {
				//如果当前报表文件在查询范围，解释并返回数据
				if(in_array(substr($filename, 0, -4), $date_list)) {
					$file = $payment_dir . '/' . $filename;
					$payment_list = array_merge( $payment_list, $this->_parseFbPayment($file) );
				}
			}
			if(empty($payment_list)) {
				showMsg('找不到數據', -1);
			}
			$payment_list = $this->_formatPayment($payment_list);
			
			excel_export(
				"Facebook_{$_POST['start_date']}_{$_POST['end_date']}.xls",
				array_shift($payment_list),
				$payment_list
			);
			exit;
		}
	}
	
	private function _getDateList($start_time, $end_time) {
		$format = 'Y-m-d';
		$day_time = 86400;

		//计算时间差
		$diff_time = $end_time - $start_time;
		//当前时间，它会遍历而增加
		$current_time = strtotime( date($format, $start_time) );
		//这个数组保存查询的时间范围的所有日期
		$date_list = array( date($format, $start_time) );
		while( ($diff_time -= $day_time) && $diff_time > 0 ) {
			//每遍历一次增加一天
			$current_time += $day_time;
			$date_list[] = date($format, $current_time);
		}
		//如果起始和结束不是同一天，需要加上最后一天
		if($end_time - $start_time >= $day_time) {
			$date_list[] = date($format, $end_time);
		}
		return $date_list;
	}
	
	/**
	 * 解释fb的csv报表数据
	 * 
	 * @param string $csv 文件路径
	 * @link https://developers.facebook.com/docs/payments/developer_reports_api/
	 */
	private function _parseFbPayment($csv) {
		$start = false;
		$data = array();
		$file = fopen($csv, "r");
		while(!feof($file)) {
			$line = fgetcsv($file);
			//CH下一行就是订单的数据
			if($line[0] === 'CH') {
				$start = true;
				continue;
			}
			//结束
			if($line[0] === 'SF') {
				break;
			}
			if($start) {
				$data[] = $line;
			}
		}
		fclose($file);
		return $data;
	}
	
	/**
	 * 格式化订单数据
	 * 
	 * @param array $payment_list
	 * @link https://developers.facebook.com/docs/payments/developer_reports_api/
	 */
	private function _formatPayment($payment_list) {
		$type_list = array(
			'S' => '成功',
			'R' => '已退款',
			//不知道什么意思，不过下面的情况基本不会发生
			'C' => 'Chargeback',
			'D' => 'out of window chargeback',
			'K' => 'chargeback reversal',
			'J' => 'out of window chargeback reversal'
		);
		$format_payment_list = array(
			'header' => array('FB訂單號', 'FB幣', 'FB訂單狀態', '交易时间')
		);
		$agent_order_list = array();
		foreach($payment_list as $payment) {
			$format_payment = array(
				'agent_order' => $payment[4],
				'money' => $payment[7],
				'pay_type' => $type_list[ $payment[2] ],
				'pay_date' => date('Y-m-d H:i:s', strtotime($payment[5]))
			);
			$agent_order_list[] = $format_payment['agent_order'];
			$format_payment_list[ $format_payment['agent_order'] ] = $format_payment;
		}
		
		//增加字段
		array_unshift($format_payment_list['header'], '遊戲', '平臺訂單號', '金額（台幣）');
		$agent_model = new model('ms_agent_orders');
		$order_list = $agent_model->select('*', 'FIND_IN_SET(agent_oid, \''. implode(',', $agent_order_list) .'\')');
		foreach($order_list as $order) {
			array_unshift($format_payment_list[ $format_payment['agent_order'] ], $order['game'], $order['oid'], $order['omoney']);
		}
		return $format_payment_list;
	}
	
	public function sdkordersFail() {
		$operation_list = array('index', 'update');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		load('model.sdkOrder');
		//私有操作
		$operation_method = '_' . $operation . 'SdkordersFail';
		if(method_exists($this, $operation_method)) {
			$this->{$operation_method}($_REQUEST);
		} else {
			//取出开服列表
			$order_list = sdkOrder::getList();
			$this->assign('order_list', $order_list);
		}
		$this->assign('operation', $operation);
	}
	
	private function _updateSdkordersFail() {
		if(empty($_GET['oid'])) {
			ShowMsg('订单不存在', -1);
		}
		sdkOrder::setOrderSuccess($_GET['oid']);
		ShowMsg('设置成功', '/index.php?m=ac&a=sdkOrdersFail&operation=index');
	}
    
    public function orderList() {
        //忽略的channel，因为数据库仍有这些数据，但已经没用了
        $ignore_channel = array(
            'ff',
            'gamebase',
            'yahoo',
            'msn',
            'gafee',
            'SDK订单'
        );
        
        //取出所有channel
        $order_model = new Model('ms_all_orders');
        $channel_row = $order_model->getBySql("SELECT DISTINCT channel"
                . " FROM ms_all_orders WHERE channel!='' AND channel IS NOT NULL");
        $channel = array();
        foreach($channel_row as $row) {
            if(!in_array($row['channel'], $ignore_channel)) {
                $channel[] = $row['channel'];
            }
        }
        //取出所有游戏
        $game_row = $order_model->getBySql("SELECT DISTINCT game"
                . " FROM ms_all_orders WHERE game!='' AND game IS NOT NULL");
        $game = array();
        foreach($game_row as $row) {
            $game[] = $row['game'];
        }
        $this->assign('channel', $channel);
        $this->assign('game', $game);
        $this->assign('game_config', loadC('config.inc.php', 'mgame_sdk_api'));
        
        $where = '';
        
        $status = intval($_REQUEST['status']);
        $where .= "status=$status";
        $this->assign('status', $status);
        
        $game = is_array($_REQUEST['game']) ? $_REQUEST['game'] :
            (strpos($_REQUEST['game'], ',') ? explode(',', $_REQUEST['game']) : array());
        $channel = is_array($_REQUEST['channel']) ? $_REQUEST['channel'] :
            (strpos($_REQUEST['channel'], ',') ? explode(',', $_REQUEST['channel']) : array());
        //游戏
        if(!empty($game)) {
            $where .= ' AND FIND_IN_SET(game, \'' . implode(',', $game) . '\')';
        }
        //儲值來源
        if(!empty($channel)) {
            $where .= ' AND FIND_IN_SET(channel, \'' . implode(',', $channel) . '\')';
        }
        $this->assign('game_array', $game);
        $this->assign('channel_array', $channel);
        //时间范围
        if(!empty($_REQUEST['start_date'])) {
            $start_time = strtotime($_REQUEST['start_date']);
            $where .= " AND $start_time <= time";
        }
        if(!empty($_REQUEST['end_date'])) {
            $end_time = strtotime($_REQUEST['end_date']);
            $where .= " AND time <= " . ($end_time + 86399);
        }
        $this->assign('start_date', $_REQUEST['start_date']);
        $this->assign('end_date', $_REQUEST['end_date']);
        
        
        //查询数据
        $page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
        $row = 10;
        $offset = ($page - 1) * $row;
        
        $order_model = new Model('ms_all_orders');
        $total_row = $order_model->getBySql("SELECT COUNT(*) AS total FROM ms_all_orders WHERE $where");
        $total = $total_row[0]['total'];
        $order_list = $order_model->getBySql("SELECT * FROM ms_all_orders WHERE $where ORDER BY oid DESC LIMIT $offset, $row");
        
        if(!empty($order_list)) {
            foreach($order_list as $key => $order) {
                $agent_order = $order_model->getBySql("SELECT agent_pay_currency, agent_pay_message FROM ms_agent_orders WHERE oid={$order['oid']}");
                if(!empty($agent_order)) {
                    $order_list[$key]['pay_message'] = $agent_order[0]['agent_pay_message'];
                    $order_list[$key]['currency'] = $agent_order[0]['agent_pay_currency'];
                } else {
                    $shops_order = $order_model->getBySql("SELECT currency, pay_message FROM blk_shops_orders WHERE oid={$order['oid']}");
                    $order_list[$key]['pay_message'] = $shops_order[0]['pay_message'];
                    $order_list[$key]['currency'] = ($shops_order[0]['currency']) ? $shops_order[0]['currency'] : 'TWD';
                }
            }
        }
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('offset', $row);
        $this->assign('order_list', is_array($order_list) ? $order_list : array());
    }
    
    /**
     * 搜索谷歌订单日志
     */
    public function searchLog() {
        $order_model = new Model('ms_all_orders');
        if($_POST) {
            load('@mgame_sdk_api.model.libs.mGameSDKLogParse');
            $game = $_POST['game'];
            $agent_oid = $_POST['agent_oid'];
            $log_path = C('DEDE_DATA_PATH') . '/logs/';
            $log_files = scandir($log_path);
            foreach($log_files as $file) {
                if(strpos($file, $game) && strpos($file, 'error')) {
                    //解释日志
                    $logparse_model = new mGameSDKLogParse($file);
                    $data = $logparse_model->parse('all', null, $game, false);
                    if(!$data) {
                        continue;
                    }
                    foreach($data as $value) {
                        //解释参数
                        $order_data = json_decode($value['request_params']['order_data'], true);
                        //抓取单号
                        if(!empty($order_data) && $value['action'] == 'billingOrderComfirm' && $order_data['agentOrder'] === $agent_oid) {
                            unset($order_data['callbackInfo']);
                            $order = $order_data;
                            $bind_user = getInstance('@mgame_sdk_api.model.libs.mGameSDKBindUser')->getUser($value['request_params']['username']);
                            $user = getInstance('@mgame_sdk_api.model.libs.mGameSDKUser')->getUser($bind_user['bind_userid']);
                            $order['hash_username'] = $user['hash_username'];
                            $order['bind_userid'] = $value['request_params']['username'];
                            $order['userid'] = $user['userid'];
                            $order['time'] = date('Y-m-d H:i:s', $order['timestamp']);
                            echo json_encode($order);
                            exit;
                        }
                    }
                }
            }
            echo json_encode(array());
            exit;
        }
        
        //取出所有游戏
        $game_row = $order_model->getBySql("SELECT DISTINCT game"
                . " FROM ms_all_orders WHERE game!='' AND game IS NOT NULL");
        $game = array();
        foreach($game_row as $row) {
            $game[] = $row['game'];
        }
        $game[] = 'fengyuntx';
        $this->assign('game', $game);
    }
	
	
	/*廣告數據查詢 power by zhang*/
	public function advTotal(){		
		$sql = "select id, channel from ms_adv_channel where channel != ''";
		$channel = model::getBySql($sql);
		$sql = "select distinct alias, ename from blk_games_inter where parent_id = 0";
		$game = model::getBySql($sql);
		$sql = "select * from `ms_adv`";
		$material = model::getBySql($sql);
		
		$searchData = array('media' => $_REQUEST['media'], 'game' => $_REQUEST['game'], 'material' => $_REQUEST['material']);
		//时间范围
		if(!empty($_REQUEST['start_date'])) {
			$t1 = $_REQUEST['start_date'];
		}
		if(!empty($_REQUEST['end_date'])) {
			$t2 = $_REQUEST['end_date'];
		}
		
		$adsObj = getInstance('model.ac');
		
		$count  = $adsObj->count_adv_total($searchData, $t1, $t2);
		$total  = $count[0]['t'];
		
		$row = 7;
		if(ceil($total / $row) < intval($_REQUEST['page'])) {
			$page = 1;
		}else {
			$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		}
		
		$offset = ($page - 1) * $row;
		$data = $adsObj->get_adv_total($searchData, $offset, $row, $t1, $t2);
		$this->assign('total', $total);
		$this->assign('page', $page);
		$this->assign('offset', $row);
		$this->assign('start_date', $_REQUEST['start_date']);
		$this->assign('end_date', $_REQUEST['end_date']);
		$this->assign('game', $game);
		$this->assign('channel', $channel);
		$this->assign('material', $material);	
		$this->assign('id_href', $id_href);
		$this->assign('list', $data);
		$this->assign('search', $searchData);
	}
        
    /**
     * 帐号绑定
     */
    public function accountBind() {
        $sdk_user = getInstance('@mgame_sdk_api.model.libs.mGameSDKUser');
        $bind_user = getInstance('@mgame_sdk_api.model.libs.mGameSDKBindUser');
        //检查帐号是否可用
        if($_REQUEST['type'] == 'checkuser') {
            $user = $sdk_user->getUser($_REQUEST['bind_username']);
            $bind = $bind_user->getUser($_REQUEST['bind_username']);
            if($user || $bind) {
                echo json_encode(array(
                    'code' => 0,
                    'message' => '帳號已存在'
                ));
            } elseif(strlen($_REQUEST['bind_username']) < 6 || preg_match('/[^\w+]/', $_REQUEST['bind_username'])) {
                echo json_encode(array(
                    'code' => 0,
                    'message' => '帳號格式非法'
                ));
            } else {
                echo json_encode(array(
                    'code' => 1,
                    'message' => '帳號可用'
                ));
            }
            exit;
        } else if($_REQUEST['type'] == 'bind') {
            
            if($sdk_user->getUserByHashUsername($_REQUEST['bind_username'])) {
                echo json_encode(array(
                    'code' => 0,
                    'message' => '帳號已存在'
                ));
            }
            $user = $sdk_user->getUserByHashUsername($_REQUEST['hash_username']);
            load('@agent.model.api.openUser');
            load('@agent.model.forgameUser');
            openUser::getInstance();
            $openuser = openUser::getUserByUserid($user['userid']);
            if(empty($openuser)) {
                echo json_encode(array(
                    'code' => 0,
                    'message' => '該帳號不是openid'
                ));
                exit;
            } elseif($openuser['isbind']) {
                $bind = $bind_user->getUserByBind($openuser['userid']);
                echo json_encode(array(
                    'code' => 0,
                    'message' => '該帳號已綁定了' . $bind['userid']
                ));
                exit;
            } elseif($openuser['site'] != 'facebook') {
                echo json_encode(array(
                    'code' => 0,
                    'message' => "該帳號是{$openuser['site']}帳號，目前只允許facebook帳號綁定"
                ));
                exit;
            }
            $login_bind = openUser::login($openuser['username'], $openuser['nickname'], $openuser['site'],
                        $openuser['data'], $_REQUEST['bind_email'], '', $_REQUEST['bind_username'], $_REQUEST['bind_password']);
            if($login_bind) {
                if($bind_user->getUserByBind($openuser['userid'])) {
                    //发送邮件
                    require APP_LIST_PATH . '/agent/controllers/functions.php';
                    $html = <<<EOT
                            <p>
	<br />
</p>
<p>
	親愛的玩家您好：<br />
您的Yahoo帳號已經成功綁定7725平台帳號，即刻起請您使用下述帳號與密碼登入遊戲。
</p>
<p>
	<br />
帳號：<span style="color:#E53333;">{$_REQUEST['bind_username']}</span>
</p>
<p>
	密碼：<span style="color:#E53333;">{$_REQUEST['bind_password']}</span>
</p>
<p>
	<br />
</p>
建議您立即<a href="http://www.7725.com/forget.html" target="_blank">修改密碼</a>，如造成不便敬請見諒。<br />
《7725營運團隊&nbsp;敬上》&nbsp;
<p>
	<br />
</p>
<p>
	<br />
</p>
<p>
	<br />
</p>
EOT;
                    smtp_mail($_REQUEST['bind_email'], '您的Yahoo帳號已經成功綁定7725平台帳號', $html, true);
                    $helper = getInstance('@mgameapi.model.libs.helperTool');
                    $helper->writeLogs( C('DEDE_DATA_PATH'), 'account_bind_', $_REQUEST);
                    echo json_encode(array(
                        'code' => 1,
                        'message' => "綁定成功"
                    ));
                    exit;
                }
            }
            echo json_encode(array(
                'code' => 0,
                'message' => "綁定失敗，請聯繫管理員"
            ));
            exit;
        }
    }
}
