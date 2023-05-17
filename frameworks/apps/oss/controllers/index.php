<?php

require_once APP_CONTROLLER_PATH . '/master.php';
//入口类
class indexController extends masterControl  {	
	
	public function index() {
		$this->checkLogin();
		self::conf();
		self::users();
		self::trans();
		self::payment();
		self::editUser();
		self::readUser();
		//don't delete this line
		$this->assign('title', '');
	}
	
	/* 会员数据 */
	public function users() {
		$this->checkLogin();
		$userObj = getInstance('model.user');
		load('model.channel');
		//搜索起始时间与结束时间
		$perpage	= !isset($_GET['a']) ? 10 : 25;
		
		if (intval($_GET['start_date']))$_REQUEST['start_date'] = date("Y-m-d", $_GET['start_date']);
		if (intval($_GET['end_date'])) 	$_REQUEST['end_date'] 	= date("Y-m-d", $_GET['end_date']);
		
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? strtotime($_REQUEST['start_date']): '';
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? strtotime($_REQUEST['end_date'].' '.'23:59:59') 	: time();
		
		//用户日期控件的显示
		if ($start) $start_date = date('Y-m-d', $start);
		if ($end)	$end_date   = date('Y-m-d', $end);
		
		//---默认的排顺
		$order_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'm.jointime';
		$order_ord 	= isset($_REQUEST['order_ord']) ? (($_REQUEST['order_ord']=='DESC') ? 'ASC' : 'DESC') : 'DESC';
		
		//---搜索条件
		$channel 	= (isset($_REQUEST['alias']) 	&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';//按渠道搜索
		$money 		= (isset($_REQUEST['money']) 	&& $_REQUEST['money'] != 'NULL') 	? $_REQUEST['money'] : '';//按金币搜索
		$user 		= (isset($_REQUEST['user']) 	&& $_REQUEST['user'] != 'NULL') 	? $_REQUEST['user']  : '';//名称搜索
		$level 		= (isset($_REQUEST['level']) 	&& $_REQUEST['level'] != 'NULL') 	? $_REQUEST['level'] : '';//级别搜索
		
		$firstcount = isset($_GET['page']) && ((intval($_GET['page']) - 1 ) * $perpage > 0) ? ((intval($_GET['page']) - 1 ) * $perpage) : 0;
        if($_REQUEST['user']) {
            if(empty($_REQUEST['user'])) {
                $this->showMsg('用戶名不能為空', -1);
            }
            $total		= $userObj->count_menber_data($start, $end, $channel, $money, $user, $level);//总数据条数		
            $data 		= $userObj->get_member_data($channel, $money, $user, $level, $start, $end, $order_type, $order_ord, $firstcount, $perpage);
        } else {
            $data = array();
            $total = 0;
        }
		$pager		= pageft($total, $perpage, '?m=index&a=users&');
		if ($_GET['do'] == 'export') {
			$header = array('帳號', 'openuid', '暱稱' , '會員點數', '註冊時間', '登錄時間');
			$exportDate = $userObj->get_exMember_data($channel, $money, $user, $level, strtotime($_GET['start_date']), strtotime($_GET['end_date'].' '.'23:59:59'));
			excel_export('user-'.date("YmdHis"), $header, $exportDate);
			exit;
		}
		
		$this->assign('userdata', $data);
		$this->assign('pager', $pager);
		$this->assign('aliasdata', channel::$names);
		if (isset($_REQUEST['start_date'])) 	$this->assign('start_date', $_REQUEST['start_date']);
		if (isset($_REQUEST['end_date'])) 		$this->assign('end_date', 	$_REQUEST['end_date']);
		if (isset($channel))	$this->assign('falias', 	$channel);
		if (isset($money)) 		$this->assign('money', 		$money);
		if (isset($level)) 		$this->assign('level', 		$level);
		if (isset($user)) 		$this->assign('user', 		$user);
		
		$this->assign('title', '平台會員信息詳細數據');
	}
	
	/* 会员转点 */
	public function trans() {
		$this->checkLogin();	
		$userObj 	= getInstance('model.user');
		//搜索起始时间与结束时间
		$perpage	= !isset($_GET['a']) ? 10 : 25;
		
		if (intval($_GET['start_date']))$_REQUEST['start_date'] = date("Y-m-d", $_GET['start_date']);
		if (intval($_GET['end_date'])) 	$_REQUEST['end_date'] 	= date("Y-m-d", $_GET['end_date']);
		
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? strtotime($_REQUEST['start_date']): '';
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? strtotime($_REQUEST['end_date'].' '.'23:59:59') 	: time();
		
		//用户日期控件的显示
		if ($start) $start_date = date('Y-m-d', $start);
		if ($end)	$end_date   = date('Y-m-d', $end);
		
		//搜索条件
		$type 	= (isset($_REQUEST['type'])	&& $_REQUEST['type'] != 'NULL')		? $_REQUEST['type'] : '';//按渠道搜索
		$game 	= (isset($_REQUEST['game']) && $_REQUEST['game'] != 'NULL') 	? $_REQUEST['game'] : '';//按金币搜索
		$server = (isset($_REQUEST['server']) && $_REQUEST['server'] != 'NULL') ? $_REQUEST['server']  : '';//名称搜索
		$user 	= (isset($_REQUEST['user']) && $_REQUEST['user'] != 'NULL') 	? $_REQUEST['user'] : '';//级别搜索
		
		$total		= $userObj->count_trans_orders($start, $end, $type, $game, $server, $user);//总数据条数	
		$pager		= pageft($total, $perpage, '?m=index&a=trans&');
		
		$firstcount = ((intval($_GET['page']) - 1 ) * $perpage > 0) ? ((intval($_GET['page']) - 1 ) * $perpage) : 0;
		$data 		= $userObj->get_trans_orders($start, $end, $type, $game, $server, $user, $firstcount, $perpage);
		if ($_GET['do'] == 'export') {
			$header = array('訂單號', '帳號', '遊戲', '伺服器', '會員點數', '遊戲幣', '轉點時間');
			$exportDate = $userObj->get_exTrans_orders(strtotime($_GET['start_date']), strtotime($_GET['end_date'].' '.'23:59:59'), $type, $game, $server, $user);
			excel_export('trans-'.date("YmdHis"), $header, $exportDate);
			exit;
		}
		
		$this->assign('orderdata', $data);
		$this->assign('pager', $pager);		

		$this->assign('gamesName', 		$userObj->get_games(0, 0));
		$sql='SELECT id FROM `blk_games_inter` WHERE evalue = '.$game;
		$gameid = model::getBySql($sql);	
		$this->assign('serversName', 	$userObj->get_games(0, $gameid[0]['id']));		
		if (isset($_REQUEST['start_date'])) 	$this->assign('start_date', $_REQUEST['start_date']);
		if (isset($_REQUEST['end_date'])) 		$this->assign('end_date', 	$_REQUEST['end_date']);
		if (isset($type))	$this->assign('type', $type);
		if (isset($game)) 	$this->assign('game', $game);
		if (isset($server)) $this->assign('server',	$server);
		if (isset($user)) 	$this->assign('user', $user);		
		
		$this->assign('title', '平台會員轉點詳細數據');
	}
	
	/* 会员支付 */
	public function payment() {
		$this->checkLogin();	
		$paymentObj = getInstance('model.payment');
		$userObj 	= getInstance('model.user');
		//搜索起始时间与结束时间
		$perpage	= !isset($_GET['a']) ? 10 : 25;		
		
		if (intval($_GET['start_date']))$_REQUEST['start_date'] = date("Y-m-d", $_GET['start_date']);
		if (intval($_GET['end_date'])) 	$_REQUEST['end_date'] 	= date("Y-m-d", $_GET['end_date']);
		
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? strtotime($_REQUEST['start_date']): '';
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? strtotime($_REQUEST['end_date'].' '.'23:59:59') 	: time();
		
		//用户日期控件的显示
		if ($start) $start_date = date('Y-m-d', $start);
		if ($end)	$end_date   = date('Y-m-d', $end);
		
		//搜索条件
		$type 	= (isset($_REQUEST['type'])	&& $_REQUEST['type'] != 'NULL')		? $_REQUEST['type'] : '';//按付款方式搜索
		$game 	= (isset($_REQUEST['game']) && $_REQUEST['game'] != 'NULL') 	? $_REQUEST['game'] : '';//按金币搜索
		$server = (isset($_REQUEST['server']) && $_REQUEST['server'] != 'NULL') ? $_REQUEST['server']  : '';//名称搜索
		$user 	= (isset($_REQUEST['user']) && $_REQUEST['user'] != 'NULL') 	? $_REQUEST['user'] : '';//级别搜索
		$state = (isset($_REQUEST['state']) && $_REQUEST['state'] != 'NULL')    ? $_REQUEST['state'] : '1';//付款狀態搜索
		$total		= $paymentObj->count_payment_orders($start, $end, $type, $game, $server, $user, $state);//总数据条数	
		$pager		= pageft($total, $perpage, '?m=index&a=payment&');
		
		$firstcount = ((intval($_GET['page']) - 1 ) * $perpage > 0) ? ((intval($_GET['page']) - 1 ) * $perpage) : 0;
		$data 		= $paymentObj->get_payment_orders($start, $end, $type, $game, $server, $user, $state, $firstcount, $perpage);
		if ($_GET['do'] == 'export') {
			$header = array('訂單號','帳號', '去向', '付費方式', '充值金額', '付款狀態', '充值時間');
			$exportDate = $paymentObj->get_exPayment_orders(strtotime($_GET['start_date']), strtotime($_GET['end_date'].' '.'23:59:59'), $type, $game, $server, $user, $state);
			excel_export('payment-'.date("YmdHis"), $header, $exportDate);
			exit;
		}
		
		$this->assign('paymentdata', $data);
		$this->assign('pager', $pager);		
		$this->assign('gamesName', 		$userObj->get_games(0, 0));
		$sql='SELECT id FROM `blk_games_inter` WHERE evalue = '.$game;
		$gameid = model::getBySql($sql);		
		$this->assign('serversName', 	$userObj->get_games(0, $gameid[0]['id']));	
			
		if (isset($_REQUEST['start_date'])) 	$this->assign('start_date', $_REQUEST['start_date']);
		if (isset($_REQUEST['end_date'])) 		$this->assign('end_date', 	$_REQUEST['end_date']);
		if (isset($type))	$this->assign('type', $type);
		if (isset($game)) 	$this->assign('game', $game);
		if (isset($server)) $this->assign('server',	$server);
		if (isset($user)) 	$this->assign('user', $user);
		if (isset($state)) 	$this->assign('state', $state);
		
		$this->assign('title', '平台充值信息詳細數據');
	}
	
	/* Ajax	*/
	public function ajax() {
		$this->checkLogin();
		$game = intval($_POST['game']);
		$option_str = '<option value="">請選擇</option>';
		if ($game > 1) {
			$userObj= getInstance('model.user');
			$sql='SELECT id FROM `blk_games_inter` WHERE evalue = '.$game;
			$gameid = model::getBySql($sql); 
			$server	=$userObj->get_games(0, $gameid[0]['id']);
			foreach ($server as $value){
				$option_str .= '<option value="' . $value['evalue'] . '">' . $value['ename'] . '</option>';
			}
		}
		echo $option_str;
	}
	
	/* 赋值	*/
	public function conf() {
		$this->assign('appsroot', APPSROOT);
	}
	
	// 修改會員資料
	public function editUser () {
		$this->checkLogin();
		if (isset($_REQUEST['member'])) {
			$id = intval($this->_REQUEST ['mid']);
			$member = array(
					'sex'		=> $_REQUEST['sex'],
					'uname'		=> $_REQUEST['uname'],
					'email'		=> $_REQUEST['email'],
					'rank'		=> $_REQUEST['rank'],
					'regIdCard' => $_REQUEST['regIdCard'],
				);
			$mPerson= array(
					'mobile'	=> $_REQUEST['mobile'],
					'uname'		=> $_REQUEST['uname'],
					'sex'		=> $_REQUEST['sex'],
					'nickname'  => $_REQUEST['nickname'],
					'birthday'  => $_REQUEST['birthday'],	
				);
			$user = new model('blk_member');
			$userP= new model('blk_member_person');
			$user->set($member, " `mid` = '$id'");
			$userP->set($mPerson, " `mid` = '$id'");
			$this->showMsg($this->_L['op_success'], - 1);
			exit();
		}
		$userid = !empty($_REQUEST['userid']) && $_REQUEST['userid'] != 'NULL' ? $_REQUEST['userid'] : '';
		/*$sql    = " SELECT 	m.*, mp.*, mp.`uname` AS `trueName`	 
					FROM `blk_member` m JOIN `blk_member_person` mp ON m.`mid` = mp.`mid`
					WHERE m.`userid` = '$userid'
					LIMIT 1";*/
				
		$sql    = "SELECT blk_member.* FROM `blk_member` WHERE blk_member.userid = '".$userid."'";
		/*" SELECT m.*	 
					FROM `blk_member`
					WHERE m.`userid` = '$userid'
					LIMIT 1";*/
				
		$user   = model::getBySql($sql);
		
		//是否是一键注册
		//判斷是否綁定账号		
		if($user[0]['site'] == 'autoregs') {
			if($user[0]['openidverify'] == '1') {			
				$user[0]['isbind'] = '是';
			}else{
				$user[0]['isbind'] = '否';
			}					
		}else if($user[0]['site'] == 'facebook') {
			$sql_bind = "SELECT * FROM `blk_member_bind` WHERE userid = '".$userid."'";
			$facebook = model::getBySql($sql_bind);
			if($facebook){
				$user[0]['isbind'] = '是';
			}else{
				$user[0]['isbind'] = '否';
			}			
		}else if($user[0]['site'] == '7725'){
			$user[0]['isbind'] = '是';
		}else{
			$user[0]['isbind'] = '否';
		}
					
		$this->assign('user', $user[0]);	
	}
	
	// 查看會員詳細資料
	public function readUser() {
		$this->checkLogin();
		$this->editUser();
	}
	
	// 不重複儲值人數
	public function payUsers($field = 'payusers') {
		load('model.channel');
		load('model.page');
		C('AJAX_FUNCTION', '');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;
		$this->assign('start_date', $start);
		$this->assign('end_date', $end);
		$game 		= (isset($_REQUEST['game']) && $_REQUEST['game'] != 'NULL') 	? $_REQUEST['game'] : '';
		$this->assign('game', $game);
		if ($game) {
			$gameSql = " AND o.`gameid` = '$game' ";
		}
		$gameName = model::getBySql("SELECT * FROM `blk_games_inter` WHERE `parent_id` = 0");
		$this->assign('gamesName', $gameName);
		$hourSign  = '';
		if ($start == $end) {
			$gDate = '%Y-%m-%d %H';
			$hourSign  = ':00';
		}
		else {
			$gDate = '%Y-%m-%d';
		}		
		$sql = "SELECT FROM_UNIXTIME(o.`time`, '$gDate') AS `day`, o.`time` AS `date`, SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) AS `channel`, COUNT(DISTINCT m.`mid`) AS `payusers`, SUM(o.`money`) AS `totalPrice`
				FROM `fg_all_orders` o JOIN `blk_member` m ON o.`username` = m.`userid`
				WHERE 1 AND o.`status` = 1 AND o.`time` BETWEEN ".strtotime($start).' AND '.strtotime($end.' '.'23:59:59').$gameSql."
				GROUP BY IF(SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1)!='ff',SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1), ''), FROM_UNIXTIME(o.`time`, '$gDate')";
		$users = model::getBySql($sql);
		if (is_array($users) && count($users) > 0 ) {
			$num = count($users);
			for ($i=0; $i<$num; $i++){
				$users[$i]['channelName'] = channel::get_user_type($users[$i]['channel']);
			}
			foreach ($users as $k=> $v) {	
				$tData[$v['day'].$hourSign][$v['channelName']] = $v[$field];
				$tData[$v['day'].$hourSign]['total'] = $tData[$v['day'].$hourSign]['total'] + $v[$field];
				$tData['總計'][$v['channelName']] = $tData['總計'][$v['channelName']] + $v[$field];
				$tData['總計']['total'] = $tData['總計']['total'] + $v[$field];
			}
			$tLastData = $tData['總計'];
			unset($tData['總計']);
			ksort($tData);
			$tData['總計'] = $tLastData;
			$page = new page(count($tData), 30);
			$pageData = array_slice($tData, $page->firstRow, $page->listRows);
			
			// 搜索查詢參數
			$map = array (
				'start_date' => $start,
				'end_date'   => $end,
			);
			foreach ($map as $key=> $value) {
				if ($value) {
					$page->parameter .= "&$key=".urlencode($value);
				}
			}
			
			$pageStr = $page->show();
			foreach (channel::$names as $val) {
				$table .= "<th>$val</th>";
			}
			$table .= "<th>小計</th>";
			$th = "<thead><tr><th>日期/渠道</th>".$table."</tr></thead>";
			foreach ($pageData as $key=>$value) {
				$tr .= "<tr><td>$key</td>";
				foreach (channel::$names as $val) {
					if (array_key_exists($val, $pageData[$key])) {
						$tr .= "<td>$value[$val]</td>";
					}
					else {
						$tr .= "<td>0</td>";
					}								
				}
				$tr .= "<td>$value[total]</td>";
				$tr .= "</tr>";
			}
			$str = $th.'<tbody>'.$tr.'</tbody>';
			$this->assign('table', $str);
			$this->assign('page', $pageStr);
			$this->assign('sign', 1);
		}
		else {
			$this->assign('sign', 0);
		}
	}
	
	// 帳號分時創建
	public function createUserTime($type = 'users', $field = 'users') {
		load('model.channel');
		load('model.page');
		C('AJAX_FUNCTION', '');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;
		$this->assign('start_date', $start);
		$this->assign('end_date', $end);
		switch($type) {
			case 'users':
				$channelName = !empty($_REQUEST['alias']) ? $_REQUEST['alias'] : '';
				$notSql = channel::get_notLike_sql(' AND `userid` NOT LIKE "', '%" ');
				$channelSql	= !empty($channelName) ? ($channelName == 'ff' ? $notSql : " AND SUBSTR(`userid`, 1, INSTR(`userid`, '_')-1) = '$channelName'") : '';
				// $groupSql   = " IF(SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1)!='ff', SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1),''), ";		
				$sql = "SELECT SUBSTR(`userid`, 1, INSTR(`userid`, '_')-1) AS `channel`, COUNT(`userid`) AS `users`, FROM_UNIXTIME(`jointime`, '%Y-%m-%d') AS `day`, FROM_UNIXTIME(`jointime`, '%k') AS `hour`
						FROM `blk_member`
						WHERE 1 AND `jointime` BETWEEN ".strtotime($start).' AND '.strtotime($end.' '.'23:59:59').$channelSql."
						GROUP BY FROM_UNIXTIME(`jointime`, '%Y-%m-%d %H')";
				break;
			case 'pay'  :
				$game 		= (isset($_REQUEST['game']) && $_REQUEST['game'] != 'NULL') 	? $_REQUEST['game'] : '';
				$this->assign('game', $game);
				if ($game) {
					$gameSql = " AND o.`gameid` = '$game' ";
				}
				$gameName = model::getBySql("SELECT * FROM `blk_games_inter` WHERE `parent_id` = 0");
				$this->assign('gamesName', $gameName);				
				$sql = "SELECT FROM_UNIXTIME(o.`time`, '%Y-%m-%d') AS `day`, FROM_UNIXTIME(o.`time`, '%k') AS `hour`, SUM(o.`money`) AS `totalPrice`
						FROM `fg_all_orders` o
						WHERE 1 AND o.`status` = 1 AND o.`time` BETWEEN ".strtotime($start).' AND '.strtotime($end.' '.'23:59:59').$gameSql."
						GROUP BY FROM_UNIXTIME(o.`time`, '%Y-%m-%d %H')";
				break;
			default:break;
		}
		$this->assign('channel', channel::$names);
		$users = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			foreach ($users as $k=> $v) {	
				$tData[$v['day']][$v['hour']] = $v[$field];
				$tData[$v['day']]['total'] = $tData[$v['day']]['total'] + $v[$field];
				$tData['總計'][$v['hour']] = $tData['總計'][$v['hour']] + $v[$field];
				$tData['總計']['total'] = $tData['總計']['total'] + $v[$field];
			}
			$tLastData = $tData['總計'];
			unset($tData['總計']);
			$tData['總計'] = $tLastData;
			$page = new page(count($tData), 30);
			$pageData = array_slice($tData, $page->firstRow, $page->listRows);
			// 搜索查詢參數
			$map = array (
				'start_date' => $start,
				'end_date'   => $end,
			);
			foreach ($map as $key=> $value) {
				if ($value) {
					$page->parameter .= "&$key=".urlencode($value);
				}
			}
			
			$pageStr = $page->show();
			$hour = $this->getHour();
			foreach ($hour as $key=> $val) {
				$table .= "<th>$key<br/>|<br/>$val</th>";
			}
			$table .= "<th>小計</th>";
			$th = "<thead><tr><th>日期/時間段</th>".$table."</tr></thead>";
			foreach ($pageData as $key=>$value) {
				$tr .= "<tr><td>$key</td>";
				foreach ($hour as $val) {
					if (array_key_exists($val, $pageData[$key])) {
						$tr .= "<td>$value[$val]</td>";
					}
					else {
						$tr .= "<td>0</td>";
					}								
				}
				$tr .= "<td>$value[total]</td>";
				$tr .= "</tr>";
			}
			$str = $th.'<tbody>'.$tr.'</tbody>';
			$this->assign('table', $str);
			$this->assign('page', $pageStr);	
			$this->assign('sign', 1);
		}
		else {
			$this->assign('sign', 0);
		}	
	}
	
	// 帳號創建報表
	public function createUser() {
		load('model.channel');
		load('model.page');
		C('AJAX_FUNCTION', '');
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: $ent_time;
		$hourSign  = '';
		if ($start == $end) {
			$gDate = '%Y-%m-%d %H';
			$hourSign  = ':00';
		}
		else {
			$gDate = '%Y-%m-%d';
		}
		$this->assign('start_date', $start);
		$this->assign('end_date', $end);
		$channelName = !empty($_REQUEST['alias']) ? $_REQUEST['alias'] : '';
		$notSql = channel::get_notLike_sql(' AND m.`userid` NOT LIKE "', '%" ');
		$channelSql	= !empty($channelName) ? ($channelName == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channelName'") : '';
		$groupSql   = " IF(SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1)!='ff', SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1),''), ";		
		$sql = "SELECT SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) AS `channel`, COUNT(m.`userid`) AS `users`, FROM_UNIXTIME(m.`jointime`, '$gDate') AS `day`, FROM_UNIXTIME(m.`jointime`, '%k') AS `hour`
				FROM `blk_member` m
				WHERE 1 AND m.`jointime` BETWEEN ".strtotime($start).' AND '.strtotime($end.' '.'23:59:59').$channelSql."
				GROUP BY ".$groupSql."FROM_UNIXTIME(m.`jointime`, '$gDate')";
		$users = model::getBySql($sql);
		if (is_array($users) && count($users)) {
			$num = count($users);
			for ($i=0; $i<$num; $i++){
				$users[$i]['channelName'] = channel::get_user_type($users[$i]['channel']);
			}
			foreach ($users as $k=> $v) {	
				$tData[$v['day'].$hourSign][$v['channelName']] = $v['users'];
				$tData[$v['day'].$hourSign]['total'] = $tData[$v['day'].$hourSign]['total'] + $v['users'];
				$tData['總計'][$v['channelName']] = $tData['總計'][$v['channelName']] + $v['users'];
				$tData['總計']['total'] = $tData['總計']['total'] + $v['users'];
			}
			$tLastData = $tData['總計'];
			unset($tData['總計']);
			ksort($tData);
			$tData['總計'] = $tLastData;
			$page = new page(count($tData), 30);
			$pageData = array_slice($tData, $page->firstRow, $page->listRows);
			
			// 搜索查詢參數
			$map = array (
				'start_date' => $start,
				'end_date'   => $end,
			);
			foreach ($map as $key=> $value) {
				if ($value) {
					$page->parameter .= "&$key=".urlencode($value);
				}
			}
			
			$pageStr = $page->show();
			foreach (channel::$names as $val) {
				$table .= "<th>$val</th>";
			}
			$table .= "<th>小計</th>";
			$th = "<thead><tr><th>日期/渠道</th>".$table."</tr></thead>";
			foreach ($pageData as $key=>$value) {
				
				$tr .= "<tr><td>$key</td>";
				foreach (channel::$names as $val) {
					if (array_key_exists($val, $pageData[$key])) {
						$tr .= "<td>$value[$val]</td>";
					}
					else {
						$tr .= "<td>0</td>";
					}								
				}
				$tr .= "<td>$value[total]</td>";
				$tr .= "</tr>";
				
			}
			$str = $th.'<tbody>'.$tr.'</tbody>';
			$this->assign('table', $str);
			$this->assign('page', $pageStr);			
			$this->assign('sign', 1);
		}
		else {
			$this->assign('sign', 0);
		}	
	}

	// 儲值金額報表
	public function storedValue() {
		$this->payUsers('totalPrice');		
	}
	
	public function storedValueTime() {
		$this->checkLogin();
		$this->createUserTime('pay', 'totalPrice');
	}
	// 初始化本月開始結束時間
	public function setInitTime() {
		$bef_time	= date('Y-m-01', time());
		$ent_time   = date('Y-m-d', strtotime("$bef_time +1 month -1 day"));
		$this->assign('start_date', $bef_time);
		$this->assign('end_date', $ent_time);		
	}
	
	public function getHour() {
		for ($i = 0; $i < 24; $i++) {
			$hour[$i] = $i + 1;
		}
		return $hour;
	}
}