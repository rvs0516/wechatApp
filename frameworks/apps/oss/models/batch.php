<?php

/**
 * 批量处理
 */

class batch {
	/*
	 * 批量获取用户数据
	 */
	public function getBatchesMemberList($uid) {
		$sql = "SELECT * FROM ms_member WHERE userName IN(".$uid.") ORDER BY FIELD(userName,".$uid.")";

		$result = Model::getBySql($sql);
		return $result;
	}

	/*
	 * 批量获取用户数据
	 */
	public function getAssData($userName) {
		$sql = "SELECT * FROM ms_member_info WHERE userName = '{$userName}'";

		$result = Model::getBySql($sql);
		return $result[0];
	}
}