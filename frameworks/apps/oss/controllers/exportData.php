<?php

require_once APP_CONTROLLER_PATH . '/master.php';

class exportDataController extends masterControl {
    
    /**
	 * 会员报表导出
	 */
	public function member() {
		if($_POST) {
			$start_time = strtotime($_POST['start_date']);
			$end_time = strtotime($_POST['end_date']);
			if(empty($start_time) || empty($end_time) || $start_time > $end_time) {
				showMsg('請選擇正確的日期');
			}
            
            $keys = array(
                'mid' => 'ID',
                'userid' => '帳號',
                'hash_username' => 'hash帳號',
                'nickname' => '暱稱',
                'site' => '帳號來源',
                'sex' => '姓別',
                'money' => '平臺幣',
                'email' => '郵箱',
                'joinip' => '帳號創建IP',
                'jointime' => '帳號創建時間'
            );
            $user_model = new Model('blk_member');
            $filename = "會員_{$_POST['start_date']}_{$_POST['end_date']}";
            
            $site = is_array($_POST['site']) ? $_POST['site'] : array();
            
            excelPrintHeader($filename, $keys);
            //查询总数
            $where = "$start_time <= `jointime` AND `jointime` <= $end_time";
            if(!empty($site)) {
                //空值被认为是7725的用户
                if(in_array('7725', $site)) {
                    $where .= ' AND (FIND_IN_SET(site, \'' . implode(',', $site) . '\') OR site=\'\' OR site IS NULL)';
                } else {
                    $where .= ' AND FIND_IN_SET(site, \'' . implode(',', $site) . '\')';
                }
            }
            $user_total_row = $user_model->getBySql("SELECT COUNT(*) AS total FROM blk_member WHERE $where");
            $user_total = $user_total_row[0]['total'];
            
            $fields = implode(',', array_keys($keys));
            //防止数据太多内存不够，每次只取出500行，分批处理
            for($i = 0; $i < $user_total; $i += 500) {
                $user_info = $user_model->getBySql("SELECT $fields FROM blk_member WHERE $where limit $i, 500");
                foreach($user_info as $row) {
                    $row['jointime'] = date('Y-m-d', $row['jointime']);
                    excelPrintRow($row);
                }
            }
            excelPrintFooter();
			exit;
		}
        $user_model = new Model('blk_member');
        $site_row = $user_model->getBySql("SELECT DISTINCT site FROM blk_member WHERE site!='' AND site IS NOT NULL");
        $site = array();
        foreach($site_row as $row) {
            $site[] = $row['site'];
        }
        $this->assign('site', $site);
	}
    
    /**
	 * 订单报表导出
	 */
	public function order() {
		if($_POST) {
            $start_time = strtotime($_POST['start_date']);
            $end_time = strtotime($_POST['end_date'] . ' 23:59');
            if(empty($start_time) || empty($end_time) || $start_time > $end_time) {
                showMsg('請選擇正確的日期');
            }

            $keys = array(
                'oid' => '訂單號',
                'username' => '用戶名',
                'realmoney' => '原始金額',
                'currency' => '幣種',
                'money' => '轉化金額（TWD）',
                'gold' => '元寶',
                'role' => '角色',
                'game' => '遊戲',
                'server' => '伺服器',
                'time' => '購買時間',
                'channel' => '储值来源',
                'paycode' => '儲值类型',
                'agent_oid' => '联运商单号',
            );
            $game = is_array($_POST['game']) ? $_POST['game'] : array();
            $channel = is_array($_POST['channel']) ? $_POST['channel'] : array();

            $order_model = new Model('ms_all_orders');
            $filename = !empty($channel) ? implode('_', $channel) : '儲值';
            $filename = $filename . "_{$_POST['start_date']}_{$_POST['end_date']}";

            excelPrintHeader($filename, $keys);
            //查询总数
            $where = "`gold` != 0 AND `status` = 1 AND game != '' AND $start_time <= time AND time <= $end_time";
            if(!empty($game)) {
                $where .= ' AND FIND_IN_SET(game, \'' . implode(',', $game) . '\')';
            }
            //儲值來源
            if(!empty($channel)) {
                $where .= ' AND FIND_IN_SET(channel, \'' . implode(',', $channel) . '\')';
            }

            $total_row = $order_model->getBySql("SELECT COUNT(*) AS total FROM ms_all_orders WHERE $where");
            $total = $total_row[0]['total'];

            $fields = implode(',', array_keys($keys));
            //防止数据太多内存不够，每次只取出500行，分批处理
            for($i = 0; $i < $total; $i += 500) {
                $row_info = $order_model->getBySql("SELECT $fields FROM ms_all_orders WHERE $where limit $i, 500");
                foreach($row_info as $row) {
                    //$row['realmoney'] = ($row['realmoney'] != '0.00' && $row['realmoney'] != '1.00') ? $row['realmoney'] : $row['money'];
                    $row['realmoney'] = ($row['currency'] == 'TWD') ? $row['money'] : 
                    						(($row['realmoney'] != '0.00' && $row['realmoney'] != '1.00') ? $row['realmoney'] : $row['money']);
                    $row['currency'] = !empty($row['currency']) ? $row['currency'] : 'TWD';
                    $row['time'] = date('Y-m-d H:i:s', $row['time']);
                    excelPrintRow($row);
                }
            }
            excelPrintFooter();
            exit;
		}
        
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
        $channel_row = $order_model->getBySql("SELECT DISTINCT channel FROM ms_all_orders WHERE channel!='' AND channel IS NOT NULL");
        $channel = array();
        foreach($channel_row as $row) {
            if(!in_array($row['channel'], $ignore_channel)) {
                $channel[] = $row['channel'];
            }
        }
        $game_row = $order_model->getBySql("SELECT DISTINCT game FROM ms_all_orders WHERE game!='' AND game IS NOT NULL");
        $game = array();
        foreach($game_row as $row) {
            $game[] = $row['game'];
        }
        $this->assign('channel', $channel);
        $this->assign('game', $game);
	}
    
    /**
	 * web订单报表导出
	 */
	public function webOrder() {
		if($_POST) {
			$start_time = strtotime($_POST['start_date']);
			$end_time = strtotime($_POST['end_date']);
			if(empty($start_time) || empty($end_time) || $start_time > $end_time) {
				showMsg('請選擇正確的日期');
			}
            
            $keys = array(
                'oid' => '訂單號',
                'username' => '用戶名',
                'realmoney' => '原始金額',
                'currency' => '幣種',
                'price' => '轉化金額（TWD）',
                'gold' => '元寶',
                'role' => '角色',
                'game' => '遊戲',
                'server' => '伺服器',
                'stime' => '購買時間',
                'paycode' => '儲值类型',
            );
            $game = is_array($_POST['game']) ? $_POST['game'] : array();
            
            $order_model = new Model('ms_all_orders');
            $filename = 'WEB儲值' . "_{$_POST['start_date']}_{$_POST['end_date']}";
            
            excelPrintHeader($filename, $keys);
            $where = "`state` = 1 AND game != '' AND serverid != 0 AND $start_time <= stime AND stime <= $end_time";
            if(!empty($game)) {
                $where .= ' AND FIND_IN_SET(game, \'' . implode(',', $game) . '\')';
            }
            
            $total_row = $order_model->getBySql("SELECT COUNT(*) AS total FROM blk_shops_orders WHERE $where");
            $total = $total_row[0]['total'];
            
            $fields = implode(',', array_keys($keys));
            //防止数据太多内存不够，每次只取出500行，分批处理
            for($i = 0; $i < $total; $i += 500) {
                $row_info = $order_model->getBySql("SELECT $fields FROM blk_shops_orders WHERE $where limit $i, 500");
                foreach($row_info as $row) {
                    $row['realmoney'] = !empty($row['realmoney']) ? $row['realmoney'] : $row['price'];
                    $row['currency'] = !empty($row['currency']) ? $row['currency'] : 'TWD';
                    $row['stime'] = date('Y-m-d H:i:s', $row['stime']);
                    excelPrintRow($row);
                }
            }
            excelPrintFooter();
			exit;
		}
        $order_model = new Model('ms_all_orders');
        $game_row = $order_model->getBySql("SELECT DISTINCT game FROM blk_shops_orders WHERE game!='' AND game IS NOT NULL");
        $game = array();
        foreach($game_row as $row) {
            $game[] = $row['game'];
        }
        $this->assign('game', $game);
	}


	/**
	 * 订单表导出（含无效订单）
	 */
	 public function allCreatOrder(){
		if($_POST) {
            $start_time = strtotime($_POST['start_date']);
            $end_time = strtotime($_POST['end_date'] . ' 23:59');	
            if(empty($start_time) || empty($end_time) || $start_time > $end_time) {
                showMsg('請選擇正確的日期',1);
            }
			//$end_three_time  选择 $end_time 日期前三个月
			 $end_three_time = mktime(date('h',$end_time),date('i',$end_time),date('s',$end_time),date('m',$end_time)-3,date('d',$end_time),date('Y',$end_time));
			 if($end_three_time > $start_time){
				showMsg('日期选择应该在三个月内','-1');	
			 }

            $keys = array(
                'oid' => '訂單號',
                'username' => '用戶名',
                'realmoney' => '原始金額',
                'currency' => '幣種',
                'money' => '轉化金額（TWD）',
                'gold' => '元寶',
                'role' => '角色',
                'game' => '遊戲',
                'server' => '伺服器',
                'time' => '購買時間',
                'channel' => '储值来源',
                'paycode' => '儲值类型',
				'status' => '是否有效订单',
            );
            $game = is_array($_POST['game']) ? $_POST['game'] : array();
            $channel = is_array($_POST['channel']) ? $_POST['channel'] : array();

            $order_model = new Model('ms_all_orders');
            $filename = !empty($channel) ? implode('_', $channel) : '儲值';
            $filename = $filename . "(全部)_{$_POST['start_date']}_{$_POST['end_date']}";

            excelPrintHeader($filename, $keys);
            //查询总数
            //$where = "`status` = 1 AND game != '' AND $start_time <= time AND time <= $end_time";
			$where = " game != '' AND $start_time <= time AND time <= $end_time";
            if(!empty($game)) {
                $where .= ' AND FIND_IN_SET(game, \'' . implode(',', $game) . '\')';
            }
            //儲值來源
            if(!empty($channel)) {
                $where .= ' AND FIND_IN_SET(channel, \'' . implode(',', $channel) . '\')';
            }

            $total_row = $order_model->getBySql("SELECT COUNT(*) AS total FROM ms_all_orders WHERE $where");
            $total = $total_row[0]['total'];

            $fields = implode(',', array_keys($keys));
            //防止数据太多内存不够，每次只取出500行，分批处理
            for($i = 0; $i < $total; $i += 500) {
                $row_info = $order_model->getBySql("SELECT $fields FROM ms_all_orders WHERE $where limit $i, 500");
                foreach($row_info as $row) {
                    $row['realmoney'] = !empty($row['realmoney']) ? $row['realmoney'] : $row['price'];
                    $row['currency'] = !empty($row['currency']) ? $row['currency'] : 'TWD';
                    $row['time'] = date('Y-m-d H:i:s', $row['time']);
					$row['status'] = ($row['status'] == 1) ? '有效订单' : '无效订单';
                    excelPrintRow($row);
                }
            }
            excelPrintFooter();
            exit;
		}
        
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
        $channel_row = $order_model->getBySql("SELECT DISTINCT channel FROM ms_all_orders WHERE channel!='' AND channel IS NOT NULL");
        $channel = array();
        foreach($channel_row as $row) {
            if(!in_array($row['channel'], $ignore_channel)) {
                $channel[] = $row['channel'];
            }
        }
        $game_row = $order_model->getBySql("SELECT DISTINCT game FROM ms_all_orders WHERE game!='' AND game IS NOT NULL");
        $game = array();
        foreach($game_row as $row) {
            $game[] = $row['game'];
        }
        $this->assign('channel', $channel);
        $this->assign('game', $game);	
	}
}
