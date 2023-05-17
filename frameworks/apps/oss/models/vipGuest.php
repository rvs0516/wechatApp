<?php

/**
 * VIP用户统计记录
 */

class vipGuest {

	private $_guest_model;
	private $_maintain_model;

	public function __construct() {
		$this->_ms_vipguest = new Model('ms_vipguest');
		$this->_maintain_model = new Model('ms_member_maintain');
	}

	/**
	 * 获取单个VIP用户信息
	 */
	public function getVipInfo($userName){
		return $this->_ms_vipguest->get("`userName`='{$userName}'");
	}

	/**
	 * 添加VIP用户信息
	 */
	public function add($data){
		return $this->_ms_vipguest->set($data);
	}

	/**
	 * 编辑VIP用户信息
	 */
	public function edit($id, array $data) {
		$id = mysql_real_escape_string($id);
		return $this->_ms_vipguest->set($data, "`id`='{$id}'");
	}

	/**
	 * vip列表数据
	 */
	public function getVipGuestList($sort, $examine, $start_date, $end_date, $lastGameName, $offset, $row, $userName, $source, $sumString, $specialString, $gameStr, $gid, $majorperson){
		$where = " where 1 ";
		$group = " GROUP BY mv.userName";
		$order_by = ' ORDER BY mv.vipJoinTime desc';

		$limit = " limit $offset,$row";

		if($start_date){
			$where .= " AND mv.`loginTime` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND mv.`loginTime` <= '$end_date'";
		}

		//用户账号筛选
		if ($userName) {
			$where .= " AND mv.`userName` = '{$userName}'";
		}

		//项目筛选
		if($sumString && !$lastGameName){
			$string = " AND  mv.`lastGameAlias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND mv.`lastGameAlias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}else if (!$lastGameName && $gid == 22  && $gameStr) {
			$where .= " AND mv.`lastGameAlias` IN (".$gameStr.") ";
		}else if ($lastGameName) {
			$where .= " AND mv.`lastGameAlias` = '{$lastGameName}'";
		}

		//专员账号筛选
		if ($majorperson) {
			$where .= " AND mv.`uid` = '{$majorperson}'";
		}

		// 排序
		switch ($sort) {
			case '1':
				//充值金额
				$order_by = ' ORDER BY mv.sumMoney desc';
				break;
			case '2':
				//最后登录时间
				$order_by = ' ORDER BY loginTime desc';
				break;
			case '3':
				//注册时间
				$order_by = ' ORDER BY mv.joinTime desc';
				break;
			default:
				break;
		}

		//审核状态筛选
		switch ($examine) {
			case '1':
				//未审核
				$where .= ' AND mv.status = 0';
				break;
			case '2':
				//已审核
				$where .= ' AND mv.status = 1';
				break;
			case '-1':
				//审核失败
				$where .= ' AND mv.status = -1';
				break;	
			case '3':
				//待认证
				$where .= ' AND mv.status = 2';
				break;
			default:
				break;
		}

		//玩家来源
		switch ($source) {
			case '1':
				$where .= ' AND mv.vipSource = 1';
				break;
			case '2':
				$where .= ' AND mv.vipSource = 0';
				break;
			default:
				break;
		}
		
		$sql = "select 
		mv.id ,
		mv.uid ,
		mv.userName userName , 
		mv.channelName channelName , 
		mv.loginTime , 
		mv.relationUserName , 
		mv.sumMoney , 
		returnImg ,
		phoneNum ,
		mv.weixin ,
		mv.qq ,
		mv.birthday ,
		mv.status,
		mv.firstCharge ,
		mv.lastPayTime ,
		mv.joinTime ,
		mv.relationLoginTime ,
		mv.lastGameName ,
		mv.revisitTime ,
		mv.examineRemark ,
		mv.relationStatus
		from ms_vipguest mv " .$where .$group .$order_by .$limit;
		$res = model::getBySql($sql);
		return $res;
	}
	
	/**
	 * vip列表数据总数
	 */
	public function getVipGuestListTotal($examine, $start_date, $end_date, $lastGameName, $userName, $source, $sumString, $specialString, $gameStr, $gid, $majorperson){
		$where = " where 1 ";
		$group = " GROUP BY mv.userName";

		if($start_date){
			$where .= " AND mv.`loginTime` >= '$start_date'";
		}
		if($end_date){
			$where .= " AND mv.`loginTime` <= '$end_date'";
		}
		if ($userName) {
			$where .= " AND mv.`userName` = '{$userName}'";
		}
		if($sumString && !$lastGameName){
			$string = " AND  mv.`lastGameAlias` IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND mv.`lastGameAlias` IN (" . $specialString . ") ";
			}
			$where .= $string;
		}else if (!$lastGameName && $gid == 22  && $gameStr) {
			$where .= " AND mv.`lastGameAlias` IN (".$gameStr.") ";
		}else if ($lastGameName) {
			$where .= " AND mv.`lastGameAlias` = '{$lastGameName}'";
		}

		//专员账号筛选
		if ($majorperson) {
			$where .= " AND mv.`uid` = '{$majorperson}'";
		}
		
		switch ($examine) {
			case '1':
				//未审核
				$where .= ' AND mv.status = 0';
				break;
			case '2':
				//已审核
				$where .= ' AND mv.status = 1';
				break;
			case '-1':
				//审核失败
				$where .= ' AND mv.status = -1';
				break;
			case '3':
				//待认证
				$where .= ' AND mv.status = 2';
				break;
			default:
				break;
		}
		//玩家来源
		switch ($source) {
			case '1':
				$where .= ' AND mv.vipSource = 1';
				break;
			case '2':
				$where .= ' AND mv.vipSource = 0';
				break;
			default:
				break;
		}

		$sql = "select 
		mv.id uid 
		from ms_vipguest mv " .$where .$group;
		$res = model::getBySql($sql);
		return count($res);
	}
	
	/**
	 * vip列表回访
	 */
	public function getRetVisit($uid, array $data){
		return $this->_ms_vipguest->set($data, "`id`='{$uid}'");
	}

	/**
	 * vip列表回访周期查询
	 */
	public function getRetVisitTime($uid,$type){
		switch ($type) {
			case '1':
				$sql = "select `revisitTime` from ms_vipguest where userName = '{$uid}'";
				break;
			case '2':
				$sql = "select `revisitTime` from ms_vipguest where id = '{$uid}'";
				break;
			default:
				$sql = '';
				break;
		}
		
		$res = model::getBySql($sql);
		return $res[0];
	}

	/**
	 * vip列表注册时间
	 */
	public function getRetVisitReTime($userName){
		$sql = "select joinTime from ms_member WHERE userName = '{$userName}'";
		$res = model::getBySql($sql);
		return $res[0]['joinTime'];
	}

	/**
	 * vip列表关联账号最后登录时间
	 */
	public function getRetVisitPlatformUserIdTime($userName){
		$sql = "select max(loginTime) as loginTime from ms_member WHERE userName in (' {$userName} ')";
		$res = model::getBySql($sql);
		return $res[0]['loginTime'];
	}

	/**
	 * vip列表审核
	 */
	public function getExamine($data,$id){
		return $this->_ms_vipguest->set($data, "`id`='{$id}'");
	}

	/**
	 * vip列表回访审核状态
	 */
	public function getRetVisitStatus($id){
		$sql = "select status,examineRemark from ms_vipguest where `id` = '{$id}'";
		$res = model::getBySql($sql);
		return $res[0];
	}

	/**
	 * vip回访图片路径
	*/
	public function getRetVisitImg($id){
		$sql = "select returnImg from ms_vipguest where `id` = '{$id}'";
		$res = model::getBySql($sql);
		return $res[0]['returnImg'];
	}

	/**
	 * vip列表用户
	 */
	public function getRetVisitUser($id){
		$sql = "select userName from ms_vipguest where `id` = '{$id}'";
		$res = model::getBySql($sql);
		return $res[0]['userName'];
	}
	
	/**
	 * vip列表审核状态
	 */
	public function getRetVisitUserStatus($id){
		$sql = "select status from ms_vipguest where `id` = '{$id}'";
		$res = model::getBySql($sql);
		return $res[0]['status'];
	}

	/**
	 * vip添加用户获取最后登录游戏
	 */
	public function getRetVisitUserGame($userName){
		$sql = "select Max(time),gameAlias,gameName from ms_role_seted where `userName` = '{$userName}'";
		$res = model::getBySql($sql);
		return $res[0];
	}

	/**
	 * 查询用户跟进列表
	 */
	public function getMaintainList($offset = null, $length = null, $userName, $start, $end, $status, $editUid, $sort) {
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}

		$where = 1;
		$where .= $userName ? " AND userName = '$userName'" : '';
		$where .= $start ? " AND time >= '$start'" : '';
		$where .= $end ? " AND time <= '$end'" : '';
		$where .= $editUid ? " AND uid = '$editUid'" : '';
		if ($status == 2) {
			$where .= " AND status = 0";
		}elseif (!empty($status)) {
			$where .= " AND status = ".$status;
		}
		$sort = $sort ? $sort : 'id';
		return $this->_maintain_model->select('*', $where, null, $sort.' DESC', $limit);
	}

	/**
	 * 查询用户跟进列表总数
	 */
	public function getMaintaintotal($userName, $start, $end, $status, $editUid) {
		$where = 1;
		$where .= $userName ? " AND userName = '$userName'" : '';
		$where .= $start ? " AND time >= '$start'" : '';
		$where .= $end ? " AND time <= '$end'" : '';
		$where .= $editUid ? " AND uid = '$editUid'" : '';
		if ($status == 2) {
			$where .= " AND status = 0";
		}elseif (!empty($status)) {
			$where .= " AND status = ".$status;
		}
		return $this->_maintain_model->select('COUNT(1) as total', $where);
	}

	/**
	 * 插入新的数据(限制重复写入)
	 */
	public function set($data) {
		$sql = "INSERT INTO ms_member_maintain (userName, handleType, contactType, frequency, contactAddress, time, status, data, uid)
				SELECT '{$data['userName']}', '{$data['handleType']}', '{$data['contactType']}', 0, '{$data['contactAddress']}', '{$data['time']}', 0, 0, '{$data['uid']}'
				FROM DUAL
				WHERE NOT EXISTS(
				    SELECT 1 FROM ms_member_maintain WHERE userName='{$data['userName']}' AND handleType='{$data['handleType']}' AND contactType='{$data['contactType']}'
				)";
		return model::getBySql($sql);
	}
}
