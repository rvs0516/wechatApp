<?php
class chart {

	// 獲取渠道註冊人數數據
	public function getRegist() {
		$channelObj = getInstance('model.channel');
		$userObj 	= getInstance('model.user');
		$data  = array();
		$json  = array();
		$arr   = array();

		$bef_time	= date('Y-m-d',time()-24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d');

		$this->assign('start', $start);
		$this->assign('end', $end);

		$channelStr = (isset($_REQUEST['alias']) 		&& $_REQUEST['alias'] != 'NULL') 		? $_REQUEST['alias'] : '';//按渠道搜索
		if (strpos($channelStr,',')) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = $channelStr;
		}

		$fields		= '';
		$group 		= '`site`, ';
		$ord		= '`site`, `jointime`';
		$sign		= 'c';
		$date  		= !empty($_REQUEST['date']) && ($_REQUEST['date'] == 'hour') || intval((strtotime($end) - strtotime($start)) * 1/ (3600 * 24)) == 1	? 	'%Y-%m-%d-%H' : '%Y-%m-%d';
		$title		= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'（每小時）渠道註冊人數走勢' : '渠道註冊人數走勢';

		if (!empty($_REQUEST['qd']) && $_REQUEST['qd'] != 'NULL') {
			$channel = $_REQUEST['qd'];
			$title	 = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'（每小時）'.$channelObj->get_user_type($_REQUEST['qd']).'註冊人數走勢' : $channelObj->get_user_type($_REQUEST['qd']).'註冊人數走勢';
		}

		$data = $userObj->get_member_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign) ;

		if (is_array($data) && count($data)) {
			$dateType	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'time'							:	'datetime';
			$dateFormat = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%H'							:	'%m/%d';
			$dateLength = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	3600 * 1000 * 1					:	3600 * 1000 * 1 * 24 * 2;
			$dateTip	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%Y-%m-%d,%H:00'				:	'%Y-%m-%d';

			$shijian	= !empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])	?	$_REQUEST['start_date'].' 至   '.$_REQUEST['end_date']		:	$start.' 至   '.$end;

			$chart 		= $this->setLine('line', $title, $shijian, $dateType, $dateFormat, $dateLength, '人數', $dateTip);
			$chart->setAjax('ajax', 0);

			$diff = intval((strtotime($end.' '.'24:00:00') - strtotime($start)) * 1/ (3600 * 24));

			if ($diff == 1) {
				$chart->setXaxis( array('type'=>'time', 'labels'=>'%H', 'tickInterval'=>3600 * 1000 * 1) );
				$chart->setTooltip( array('formatter'=> array( '%Y-%m-%d,%H:00', '人' )) );
			}
			else if ($diff >= 60 && $diff < 90) {
				$chart->setXaxis( 'tickInterval', 86400000 * 2 );
			}
			else if ($diff >= 90 && $diff < 180) {
				$chart->setXaxis( 'tickInterval', 86400000 * 7 );
			}
			else if ($diff >= 180 && $diff < 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 14);
			}
			else if ($diff >= 300) {
				$chart->setXaxis( 'tickInterval', 86400000 *30);
			}
			foreach ($data as $key=>$val) {
				$arr[] = array (
				'name'=> "'$key'",
				'data'=>$val
				);
			}
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$json['str'] 	= $chart->toString();
			$temp 			= 1;
		}
		else {
			$temp = 0;
			$json['title'] = $title;
		}
		$json['temp'] = $temp;
		return $json;
	}


	// 獲取渠道充值金額數據
	public function getRecharge() {
		$paymentObj = getInstance('model.payment');
		$data 		= array();
		$json  		= array();
		$arr 		= array();
		$bef_time	= date('Y-m-d', time()-24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d');

		$channelStr = (isset($_REQUEST['alias']) 		&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';//按渠道搜索
		if ($channelStr) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = '';
		}

		if (!empty($_REQUEST['qd']) && $_REQUEST['qd'] != 'NULL') {
			$channel = $_REQUEST['qd'];
		}

		$fields 	= 'sum(o.`priceCount`) as `total`, o.`stime` AS `date`,m.`site` AS `site`,m.`userid`,';
		$group		= ((!empty($_REQUEST['userid'])) && ($_REQUEST['userid'] != 'NULL')) ? ''			:	'm.`site`';
		$ord		= ((!empty($_REQUEST['userid'])) && ($_REQUEST['userid'] != 'NULL')) ? 'o.`stime`'	:	'm.`site`,o.`stime`';
		$sign		= ((!empty($_REQUEST['userid'])) && ($_REQUEST['userid'] != 'NULL')) ? 'who'		:	'c';


		$date  		= !empty($_REQUEST['date'])  ? '%Y-%m-%d-%H' : '%Y-%m-%d';
		$title		= !empty($_REQUEST['date']) 	&& $_REQUEST['date'] == 'hour'		?	'（每小時）渠道充值金額走勢' 	:
		!empty($_REQUEST['userid']) 	&& $_REQUEST['userid'] != 'NULL' 	? 	$_REQUEST['userid'].'充值金額走勢' : '渠道充值金額走勢';

		$user		= !empty($_REQUEST['userid']) 	&& $_REQUEST['userid'] != 'NULL' 	? 	$_REQUEST['userid'] : '';
		$data = $paymentObj->get_payment_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign, $user) ;

		if (is_array($data) && count($data)) {
			$dateType	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'time'							:	'datetime';
			$dateFormat = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%H'							:	'%m/%d';
			$dateLength = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	3600*1000*1						:	3600*1000*1*24;
			$dateTip	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%Y-%m-%d,%H:00'				:	'%Y-%m-%d';

			$shijian	= !empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])	?	$_REQUEST['start_date'].' 至   '.$_REQUEST['end_date']		:	$start.' 至   '.$end;

			$chart 		= $this->setLine('recharge', $title, $shijian, $dateType, $dateFormat, $dateLength, '充值金額', $dateTip);
			$chart->setAjax('ajax', 0);

			$diff = intval((strtotime($end.' '.'24:00:00') - strtotime($start)) * 1/ (3600 * 24));
			if ($diff == 1 || (!empty($_REQUEST['userid']) 	&& $_REQUEST['userid'] != 'NULL')) {
				$chart->setChart('defaultSeriesType',"'column'");
				$chart->setPlotoptions('column', '1');
			}
			else if ($diff >= 60 && $diff < 90) {
				$chart->setXaxis( 'tickInterval', 86400000 * 2 );
			}
			else if ($diff >= 90 && $diff < 180) {
				$chart->setXaxis( 'tickInterval', 86400000 * 7 );
			}
			else if ($diff >= 180 && $diff < 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 14);
			}
			else if ($diff >= 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 30);
			}
			foreach ($data as $key=>$val) {
				$arr[] = array (
				'name'=> "'$key'",
				'data'=>$val
				);
			}
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$temp 			= 1;
			$json['str']	= $chart->toString();
		}
		else {
			$temp 			= 0;
			$json['title'] = $title;
		}
		$json['temp'] = $temp;
		return $json;
	}

	// 獲取平台註冊人數數據
	public function getplatformRegist() {
		$data 		= array();
		$json  		= array();
		$arr		= array();
		$channel 	= array();

		$bef_time	= date('Y-m-d', time() - 24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());

		$channelStr = (isset($_REQUEST['alias']) 		&& $_REQUEST['alias'] != 'NULL') 		? $_REQUEST['alias'] : '';//按渠道搜索
		if ($channelStr) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = '';
		}
		$sign		= 'p';
		$date  		= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ? '%Y-%m-%d-%H' : '%Y-%m-%d';

		$fields		= '';
		$group 		= '';
		$ord		= '`jointime`';
		$userObj 	= getInstance('model.user');
		$title 		= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'平台（每小時）的註冊人數走勢'	:	'平台註冊人數走勢';

		$data  = $userObj->get_member_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign) ;
		if (is_array($data) && count($data)) {
			$dateType	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'time'							:	'datetime';
			$dateFormat = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%H'							:	'%m/%d';
			$dateLength = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	3600*1000*1						:	3600*1000*1*24;
			$dateTip	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%Y-%m-%d,%H:00'				:	'%Y-%m-%d';
			$shijian	= !empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])	?	$_REQUEST['start_date'].' 至   '.$_REQUEST['end_date']		:	date('Y-m-d', $bef_time).' 至   '.date('Y-m-d', time());

			$chart = $this->setLine('platform', $title, $shijian, $dateType, $dateFormat, $dateLength, '人數', $dateTip, '人', '1');
			$chart->setAjax('ajax', 0);

			$diff  = intval((strtotime($end.' '.'24:00:00') - strtotime($start)) * 1 / (3600 * 24));

			if ($diff == 1) {
				$chart->setChart('defaultSeriesType',"'column'");
				$chart->setPlotoptions('column', '1');
			}
			else if ($diff >= 90 && $diff < 180) {
				$chart->setXaxis( 'tickInterval', 86400000 * 7 );
			}
			else if ($diff >= 180 && $diff < 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 14);
			}
			else if ($diff >= 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 30);
			}
			foreach ($data as $key=>$val) {
				$arr[] = array (
				'name'=> "'$key'",
				'data'=>$val
				);
			}
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$json['str'] 	= $chart->toString();
			$temp			= 1;
		}
		else {
			$temp = 0;
			$json['title'] = $title;
		}
		$json['temp'] = $temp;
		return $json;
	}

	// 獲取平台充值金額數據
	public function getPlatformRecharge() {
		$channelObj = getInstance('model.channel');

		$data 		= array();
		$json  		= array();
		$arr		= array();

		$bef_time	= date('Y-m-d', time()-24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());

		$channelStr = (isset($_REQUEST['alias']) 	&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';//按渠道搜索
		if ($channelStr) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = '';
		}

		$sign		= 'p';
		$date  		= !empty($_REQUEST['date'])  ? '%Y-%m-%d-%H' : '%Y-%m-%d';

		$fields 	= 'sum(o.`priceCount`) as `total`, o.`stime` AS `date`,m.`site` AS `site`,m.`userid`,';
		$group		= '';
		$ord		= 'o.`stime`';
		$paymentObj = getInstance('model.payment');
		$title		= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'（每小時）平台充值金額走勢' : '平台充值金額走勢';

		$data = $paymentObj->get_payment_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign) ;
		if (is_array($data) && count($data)) {
			$dateType	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'time'							:	'datetime';
			$dateFormat = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%H'							:	'%m/%d';
			$dateLength = !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	3600*1000*1						:	3600*1000*1*24;
			$dateTip	= !empty($_REQUEST['date']) && $_REQUEST['date'] == 'hour' ?	'%Y-%m-%d,%H:00'				:	'%Y-%m-%d';
			$shijian	= !empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])	?	$_REQUEST['start_date'].' 至   '.$_REQUEST['end_date']		:	date('Y-m-d', $bef_time).' 至   '.date('Y-m-d', time());

			$chart = $this->setLine('platform', $title, $shijian, $dateType, $dateFormat, $dateLength, '充值金額', $dateTip, '', '1');
			$chart->setAjax('ajax', 0);

			$diff = intval((strtotime($end.' '.'24:00:00') - strtotime($start)) * 1 / (3600 * 24));

			if ($diff == 1 || (!empty($_REQUEST['userid']) 	&& $_REQUEST['userid'] != 'NULL')) {
				$chart->setChart('defaultSeriesType',"'column'");
				$chart->setPlotoptions('column', '1');
			}
			else if ($diff < 90) {
				$chart->setXaxis( 'tickInterval', 86400000 * 2);
			}
			else if ($diff >= 90 && $diff < 180) {
				$chart->setXaxis( 'tickInterval', 86400000 * 7 );
			}
			else if ($diff >= 180 && $diff < 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 14);
			}
			else if ($diff >= 300) {
				$chart->setXaxis( 'tickInterval', 86400000 * 30);
			}
			foreach ($data as $key=>$val) {
				$arr[] = array (
				'name'=> "'$key'",
				'data'=>$val
				);
			}
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$json['str'] 	= $chart->toString();
			$temp 			= 1;
		}
		else {
			$temp = 0;
			$json['title']	= $title;
		}
		$json['temp'] = $temp;
		return $json;
	}

	// 獲取渠道註冊人數比例數據
	public function getRegistPie() {
		$data		= array();
		$json  		= array();
		$arr		= array();
		$bef_time	= date('Y-m-d', time()-24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());

		$channelStr = (isset($_REQUEST['alias']) 	&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';//按渠道搜索
		if ($channelStr) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = '';
		}
		$fields		= '';
		$date		= '';
		$group 		= '`site`';
		$ord		= 'count(`mid`)';
		$sign 		= 'cs';

		$userObj 	= getInstance('model.user');
		// 统计时间段内各渠道注册总人数
		$sum  = $userObj->get_member_statistics('count(`mid`) as `sum`,', $channel, $start, $end, $date, '', '', 'sum') ;

		$data = $userObj->get_member_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign) ;
		if (is_array($data) && count($data)) {
			foreach($data as $key=>$val){
				$pp[] = array("'$val[0]'", (intval($val[1])/$sum)*100);
			}
			$temp = 1;
			$time = '';
			if ($_REQUEST['start_date'] && $_REQUEST['end_date']) {
				$time = $_REQUEST['start_date'].' 至   '.$_REQUEST['end_date'];
			}
			else {
				$time = $bef_time.' 至   '.date('Y-m-d', time());
			}
			$chart = $this->setPie('渠道註冊人數比例', 'channelpie', $time);
			$chart->setAjax('ajax', 0);
			$arr['data'] = $pp;
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$json['str'] = $chart->toString();
		}
		else {
			$temp = 0;
			$json['title'] = $title;
		}
		$json['temp'] = $temp;
		return $json;
	}

	// 獲取渠道充值金額比例數據
	public function getRechargePie() {
		$channelObj = getInstance('model.channel');
		$paymentObj = getInstance('model.payment');

		$data 		= array();
		$json  		= array();
		$arr		= array();
		$bef_time	= date('Y-m-d', time()-24 * 3600 * 60);

		$start 		= ((!empty($_REQUEST['start_date']))&& ($_REQUEST['start_date'] != 'NULL')) ? $_REQUEST['start_date']	: $bef_time;
		$end 		= ((!empty($_REQUEST['end_date'])) 	&& ($_REQUEST['end_date'] != 'NULL')) 	? $_REQUEST['end_date'] 	: date('Y-m-d', time());

		$channelStr = (isset($_REQUEST['alias']) 	&& $_REQUEST['alias'] != 'NULL') 	? $_REQUEST['alias'] : '';//按渠道搜索
		if ($channelStr) {
			$channel = explode(',', $channelStr);
		}
		else {
			$channel = '';
		}

		$fields		= 'sum(o.`priceCount`) as `total`, o.`stime` AS `date`,m.`site` AS `site`, m.`userid` AS `uid`,';
		$date		= '';
		$group 		= 'm.`site`';
		$ord		= 'sum(o.`priceCount`)';
		$sign 		= 'cs';
		$sum  		= $paymentObj->get_payment_statistics('sum(o.`priceCount`) as `sum`,', $channel, $start, $end, $date, '', '', 'sum') ;

		$data 		= $paymentObj->get_payment_statistics($fields, $channel, strtotime($start), strtotime($end.' '.'24:00:00'), $date, $group, $ord, $sign) ;

		if (is_array($data) && count($data)) {
			foreach($data as $key=>$val){
				$pp[] = array("'$val[0]'", (intval($val[1]) / $sum) * 100);
			}
			if ($_REQUEST['start_date'] && $_REQUEST['end_date']) {
				$time = $_REQUEST['start_date'].' 至   '.$_REQUEST['end_date'];
			}
			else {
				$time = $bef_time.' 至   '.date('Y-m-d', time());
			}
			$chart 			= $this->setPie('渠道充值金額比例', 'rechargepie', $time);
			$chart->setAjax('ajax', 0);
			$arr['data'] = $pp;
			$chart->setSeries('series', str_replace("\"", '', json_encode($arr)));
			$json['str'] = $chart->toString();
			$temp 			= 1;
		}
		else {
			$temp 			= 0;
			$json['title'] = $title;
		}
		$json['temp'] = $temp;
		return $json;
	}
}