<?php

/**
 * 优化服务性能，
 * 
 */
class optimize {

	/**
	 * 同步数据到分表
	 */
	public function importTableData() {
		$hour = date('G');
		//$hour = 5;
		if ($hour == '3' || $hour == '4') {
			$table = 'ms_role_seted';
		}elseif($hour == '5' || $hour == '6'){
			$table = 'ms_order';
		}else{
			return false; 
		}
		if ($table == 'ms_role_seted') {
			$row = 200000;
		}elseif($table == 'ms_order'){
			$row = 100000;
		}

		//先查出上次执行的记录
		$sql = "SELECT * FROM ms_sub_table_log WHERE sourceTable = '{$table}' ORDER BY id DESC LIMIT 1";
		$sqlRes = model::getBySql($sql);

		//根据记录得到查询时间
		if (empty($sqlRes)) {
			$year = '2017';
			$start = strtotime("2017-01-01 00:00:00");
			$end = strtotime("2017-12-31 23:59:59");
		}else{
			if ($table == 'ms_role_seted') {
				$timeData = explode('ms_role_seted_', $sqlRes[0]['targetTable']);
			}elseif($table == 'ms_order'){
				$timeData = explode('ms_order_', $sqlRes[0]['targetTable']);
			}
			$year = $timeData[1];
			if ($year != 'main') {
				//注意order表和role表的年份不一样
				if ($sqlRes[0]['finish'] >= $sqlRes[0]['target']) {
					//因为订单表和角色表需要导出的数据年份不一样
					if ($table == 'ms_role_seted' && $year == '2019') {
						$year = 'main';
						$start = strtotime("2020-01-01 00:00:00");
						$end = strtotime(date("Y-m-d",strtotime("-1 day"))) -1;//最多获取到前天的数据
						//$type = 1;//用于控制导入数据的范围
					}elseif($table == 'ms_order' && $year == '2020'){
						$year = 'main';
						$start = strtotime("2021-01-01 00:00:00");
						$end = strtotime(date("Y-m-d",strtotime("-1 day"))) -1;//最多获取到前天的数据
						//$type = 1;//用于控制导入数据的范围
					}else{
						$year = $year + 1;
						$start = strtotime($year . "-01-01 00:00:00");
						$end = strtotime($year . "-12-31 23:59:59");
					}
				}else{
					$start = strtotime($year . "-01-01 00:00:00");
					$end = strtotime($year . "-12-31 23:59:59");
				}
			}else{
				if ($table == 'ms_role_seted') {
					$start = strtotime("2020-01-01 00:00:00");
					$end = strtotime(date("Y-m-d",strtotime("-1 day"))) -1;//最多获取到前天的数据
				}elseif($table == 'ms_order'){
					$start = strtotime("2021-01-01 00:00:00");
					$end = strtotime(date("Y-m-d",strtotime("-1 day"))) -1;//最多获取到前天的数据
				}
			}
		}

		$beginSql = "SELECT id FROM {$table}_{$year} ORDER BY id DESC LIMIT 1";
		$begin = model::getBySql($beginSql)[0]['id'];

		$begin = empty($begin) ? 0 : $begin;

		if ($table == 'ms_role_seted') {
			$insertWhere = " id > {$begin} AND time >= {$start} AND time <= {$end}  LIMIT {$row} ";
			$totalWhere = " time >= {$start} AND time <= {$end} ";
		}elseif($table == 'ms_order'){
			$insertWhere = " time >= {$start} AND time <= {$end} AND id > {$begin} LIMIT {$row}";
			$totalWhere = " time >= {$start} AND time <= {$end}";
		}

		$insertSql = "INSERT INTO {$table}_{$year} SELECT * FROM {$table} WHERE {$insertWhere}";
		$insert = model::getBySql($insertSql);

		$totalSql = "SELECT COUNT(1) AS total FROM {$table} WHERE {$totalWhere}";
		$target = model::getBySql($totalSql)[0]['total'];

		$eTime = microtime(true);
		$executionTime = round($eTime - $sTime, 2); 

		if ($year == $timeData[1]) {
			$finish = $sqlRes[0]['finish'] + $row > $target ? $target : $sqlRes[0]['finish'] + $row;
		}else{
			$finish = $row  > $target ? $target : $row;
		}
		$set = array(
			'sourceTable' => $table, 
			'targetTable' => $table.'_'.$year, 
			'time' => time(), 
			'startId' => $begin, 
			'row' => $row, 
			'finish' => $finish, 
			'target' => $target, 
			'executionTime' => $executionTime, 
			);

		$logModel = new model('ms_sub_table_log');
		$logModel->set($set);	
	}


}