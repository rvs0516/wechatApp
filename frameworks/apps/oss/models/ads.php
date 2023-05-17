<?php

class ads {
	private $_channel_account_model;

	public function __construct() {
		$this->_channel_account_model = new Model('ms_ads_channel_account');
	}


	/**
	 * 获取渠道广告账户列表
	 */
	public function getAccountList($offset = null, $length = null, $channel, $account){
		$limit = '';
		if ($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$where = 1;
		if($account){
			$where .= " AND account = '$account'";
		}
		if($channel){
			$where .= " AND channelId = '$channel'";
		}

		return $this->_channel_account_model->select('*', $where, null, '`id` DESC', $limit);
	}

	/**
	 * 获取渠道广告账户总数
	 * 
	 * @return int
	 */
	public function getAccountTotal($channel, $account) {
		$where = 1;
		if($account){
			$where .= " AND account = '$account'";
		}
		if($channel){
			$where .= " AND channelId = '$channel'";
		}
		$result = $this->_channel_account_model->select('COUNT(*) as total',$where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取渠道广告充值数据
	 * 
	 * @return int
	 */
	public function getAdData($channel, $type, $start, $end, $offset = null, $row = null, $planId) {
		$limit = '';
		if ($offset !== null && $row !== null) {
			$limit = " LIMIT ".intval($offset) . ',' . intval($row);
		}
		$where = "channelId = '$channel' AND `day` >= '$start' AND `day` <= '$end'";
		if ($planId) {
			$where .= " AND adid = '$planId'";
		}
		if ($type == 1) {
			$sql= "SELECT *  FROM ms_ads_integrated_hourly WHERE " . $where . $limit;
		}elseif ($type == 2) {
			$sql= "SELECT day, adid, channelId, SUM(newRegist) AS newRegist, SUM(newAmount) AS newAmount, SUM(amount) AS amount, SUM(newPayUser) AS newPayUser, SUM(payUser) AS payUser  FROM ms_ads_integrated_hourly WHERE " . $where . " GROUP BY adid, day".$limit;
		}elseif ($type == 3) {
			$sql= "SELECT adid, channelId, SUM(newRegist) AS newRegist, SUM(newAmount) AS newAmount, SUM(amount) AS amount, SUM(newPayUser) AS newPayUser, SUM(payUser) AS payUser  FROM ms_ads_integrated_hourly WHERE " . $where . " GROUP BY adid".$limit;
		}

		$result = Model::getBySql($sql);
		return $result;
	}

	/**
	 * 获取渠道广告充值数据条数
	 * 
	 * @return int
	 */
	public function getAdDataTotal($channel, $type, $start, $end, $planId) {
		$limit = '';
		if ($offset !== null && $row !== null) {
			$limit = " LIMIT ".intval($offset) . ',' . intval($row);
		}
		$where = "channelId = '$channel' AND `day` >= '$start' AND `day` <= '$end'";
		if ($planId) {
			$where .= " AND adid = '$planId'";
		}

		if ($type == 1) {
			$sql= "SELECT *  FROM ms_ads_integrated_hourly WHERE " . $where;
		}elseif ($type == 2) {
			$sql= "SELECT day, adid, channelId, SUM(newRegist) AS newRegist, SUM(newAmount) AS newAmount, SUM(amount) AS amount  FROM ms_ads_integrated_hourly WHERE " . $where . " GROUP BY adid, day";
		}elseif ($type == 3) {
			$sql= "SELECT adid, channelId, SUM(newRegist) AS newRegist, SUM(newAmount) AS newAmount, SUM(amount) AS amount  FROM ms_ads_integrated_hourly WHERE " . $where . " GROUP BY adid";
		}
		$result = Model::getBySql($sql);
		$result = count($result);
		return $result;
	}
}