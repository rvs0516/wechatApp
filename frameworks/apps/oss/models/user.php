<?php

class user {
	public function getUser() {
		return 'Amou';
	}
	
	public function getList($where = null, $orderby = null, $limit = null) {
		$user = new model('blk_member');
		return $user->select('`mid`, `userid`, `uname`, `sex`, `jointime`, `joinip`, `logintime`, `loginip`, `money`', $where, null, $orderby, $limit);
	}

	/* 取得会员记录总数	*/
	public function count_menber_data($start, $end, $channel, $money, $user, $vip){
		$channelObj = getInstance('model.channel');
		$name = $channelObj->get_name_key(',');
		$notSql = $channelObj->get_notLike_sql(' AND m.userid NOT LIKE "', '%" ');
		$whereSql = !empty($start) ? " AND m.`jointime` BETWEEN ". $start .' AND '. $end : '';
		$channel_sql= !empty($channel)  ? ($channel == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channel'") : '';	
		$money_sql	= !empty($money)	? ($money == 1 ? ' AND m.`money`>0 ' : 'AND m.`money`=0') : '';
		$level_sql	= !empty($vip)		? ($vip == 1 ? ' AND m.`rank`=200 ' : 'AND m.`rank`!=200') : '';
        if(!empty($user))
        {
          $user=  trim($user); //去除郵箱兩邊空格
            /***匹配邮箱正则***/
            if(preg_match("/^[0-9a-z_]+@(([0-9a-z]+)[.]){1,2}[a-z]{2,3}$/",$user)==1)
            {
                 $guidmodel=new model('ms_guid');
                 $userid = $guidmodel->getBySql("SELECT userid from `ms_guid` where email=%s",array($user));  
                
                 if($userid) //判斷ms_guid是否有記錄
                 {
                    foreach($userid as $key=>$val)
                    {
                       $str.="m.`userid`='".$val['userid']."' or ";
                       
                    }
                  $user_sql = 'AND ('. rtrim($str,' or ').' or m.email="'.$user.'")';

                 }else
                 {
                    
                     $mailmodel=new model('blk_member');
                     //通過聯繫郵箱查找userid
                     $email = $mailmodel->getBySql("SELECT userid from `blk_member` WHERE email = '$user'");
                     if($email)
                     {
                            foreach($email as $key=>$val)
                            {
                               $str.="m.`userid`='".$val['userid']."' or ";
                            }
                           $user_sql = 'AND ('. rtrim($str,' or ').')';
                     }else
                     {
                       $user_sql	= ' AND (m.`userid` LIKE "%'.$user.'%" OR m.`hash_username` LIKE "%'.$user.'%") ';
           
                     }
                 }
            }else
            {
             $user_sql	= ' AND (m.`userid` LIKE "%'.$user.'%" OR m.`hash_username` LIKE "%'.$user.'%") ';
           
            }
        }
        $guid_sql = 'SELECT `username`,`userid` FROM `ms_guid` WHERE `username` LIKE "%'.$user.'%" OR `nickname` LIKE "%'.$user.'%"';
		$sql='SELECT count(m.`mid`) as `user_all` '
                . 'FROM `blk_member` m LEFT JOIN ('.$guid_sql.') g ON m.`userid`=g.`userid` '
                . 'WHERE 1 '. $whereSql . ' 
			 '. $channel_sql .' '. $money_sql .' '. $level_sql .' '. $user_sql .' OR g.`username` IS NOT NULL';
		$data = model::getBySql($sql);
		return $data[0]['user_all'];
	}
	
	/* 取得会员详细数据 */
	public function get_member_data($channel,$money, $user, $vip, $start, $end, $type, $ord, $firstrow, $listrow) {
		$whereSql = !empty($start) ? " AND m.`jointime` BETWEEN ". $start .' AND '. $end : '';
		$channelObj = getInstance('model.channel');
		$name = $channelObj->get_name_key(',');		
		$notSql = $channelObj->get_notLike_sql(' AND m.userid NOT LIKE "', '%" ');
		$channel_sql= !empty($channel)  ? ($channel == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channel'") : '';	
		$money_sql	= !empty($money)	? ($money == 1 ? ' AND m.`money`>0 ' : ' AND m.`money`=0 ') : '';
		$level_sql	= !empty($vip) 		? ($vip == 1 ? ' AND m.`rank`=200 ' : ' AND m.`rank`!=200 ') : '';
        if(!empty($user))
        {
          $user=  trim($user); //去除郵箱兩邊空格
            /***匹配邮箱正则***/
            if(preg_match("/^[0-9a-z_]+@(([0-9a-z]+)[.]){1,2}[a-z]{2,3}$/",$user)==1)
            {
                 $guidmodel=new model('ms_guid');   
                 $userid = $guidmodel->getBySql("SELECT userid from `ms_guid` where email=%s",array($user));  
                
                 if($userid) //判斷ms_guid是否有記錄
                 {
                    foreach($userid as $key=>$val)
                    {
                       $str .= "m.`userid`='" . $val['userid'] . "' or ";                       
                    }
                  	$user_sql = 'AND ('. rtrim($str,' or ').' or m.email="'.$user.'")';

                 }else
                 {                    
                     $mailmodel=new model('blk_member');
                     //通過聯繫郵箱查找userid
                     $email = $mailmodel->getBySql("SELECT userid from `blk_member` WHERE email = '$user'");
                     if($email)
                     {
						foreach($email as $key=>$val)
						{
							$str .= "m.`userid`='".$val['userid']."' or ";
						}
						$user_sql = 'AND ('. rtrim($str,' or ').')';
                     }else{
                           $user_sql = ' AND (m.`userid` LIKE "%'.$user.'%" OR m.`hash_username` LIKE "%'.$user.'%") ';           
                     }
                 }
            }else{
             $user_sql	= ' AND (m.`userid` LIKE "%'.$user.'%"  OR m.`hash_username` LIKE "%'.$user.'%") ';           
            }
        }
		$guid_sql = 'SELECT `username`,`userid` FROM `ms_guid` WHERE `username` LIKE "%'.$user.'%" OR `nickname` LIKE "%'.$user.'%"';
        $sql="	SELECT m.*, g.username, SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) AS `channel` 
			FROM `blk_member` m LEFT JOIN ($guid_sql) g ON m.`userid`=g.`userid`
			WHERE 1 ". $whereSql.' 
		 '. $channel_sql .' '. $money_sql .' '. $level_sql .' '. $user_sql .' OR g.`username` IS NOT NULL
		 ORDER BY '. $type . ' ' . $ord . ' limit '. $firstrow .',' .$listrow;
		$data = model::getBySql($sql);
		if (is_array($data)) {
			if (($num = count($data)) > 0) {
				for ($i=0; $i<$num; $i++){
					$data[$i]['channelName'] = channel::get_user_type($data[$i]['channel']);
                    $guid = model::getBySql('SELECT * FROM `ms_guid` WHERE `userid`="'.$data[$i]['userid'].'"');
                    if($guid) {
                        $data[$i]['username'] = $guid[0]['username'];
                    }
				}
			}			
		}
		
		return $data;
	}
	
		
	/* 取得导出会员详细数据 */
	public function get_exMember_data($channel,$money, $user, $vip, $start, $end) {
		$whereSql = !empty($start) ? " AND m.`jointime` BETWEEN ". $start .' AND '. $end : '';
		$channelObj = getInstance('model.channel');		
		$name = $channelObj->get_name_key(',');
		$notSql = $channelObj->get_notLike_sql(' AND m.userid NOT LIKE "', '%" ');
		$channel_sql= !empty($channel)  ? ($channel == 'ff' ? $notSql : " AND SUBSTR(m.`userid`, 1, INSTR(m.`userid`, '_')-1) = '$channel'") : '';		
		$money_sql	= !empty($money)	?  ($money == 1 ? 'AND m.`money`>0' : 'AND m.`money`=0') : '';
		$level_sql	= !empty($vip)		? ($vip == 1 ? 'AND m.`rank`=200' : 'AND m.`rank`!=200') : '';
		$user_sql	= !empty($user)		? 'AND (m.`userid` LIKE "%'.$user.'%" OR m.`hash_username` LIKE "%'.$user.'%")'  : '';
        $guid_sql = 'SELECT `username`,`userid` FROM `ms_guid` WHERE `username` LIKE "%'.$user.'%" OR `nickname` LIKE "%'.$user.'%"';
        
		$sql="	SELECT m.userid, g.username, m.nickname, m.money, FROM_UNIXTIME( m.`jointime`, '%Y-%m-%d %H:%i:%s' ) as `registTime`, FROM_UNIXTIME( m.`logintime`, '%Y-%m-%d %H:%i:%s' ) as `loginTime`,hash_username
				FROM `blk_member` m LEFT JOIN ($guid_sql) g ON m.`userid`=g.`userid` 
				WHERE 1 ".$whereSql.' 
			 '. $channel_sql .' '. $money_sql .' '. $level_sql .' '. $user_sql.' OR g.`username` IS NOT NULL ORDER BY `jointime` DESC';	
		$data = model::getBySql($sql);
        if (is_array($data)) {
			if (($num = count($data)) > 0) {
				for ($i=0; $i<$num; $i++){
					$data[$i]['channelName'] = channel::get_user_type($data[$i]['channel']);
                    $guid = model::getBySql('SELECT * FROM `ms_guid` WHERE `userid`="'.$data[$i]['userid'].'"');
                    if($guid) {
                        $data[$i]['username'] = $guid[0]['username'];
                    }
				}
			}			
		}
		return $data;
	}

	/* 统计转点数 */
	public function count_trans_orders($start, $end, $type, $game, $server, $user){
		$start_sql	= !empty($start)	? ' AND `time` >= ' . $start : '';
		$end_sql	= !empty($end)		? ' AND `time` <= ' . $end : ' AND `time` <= ' . time();
		$type_sql	= !empty($type) 	? ($type == 'online') ? ' AND `ordertype` = 0 ' : ' AND `ordertype` = 1 ' : '';
		$game_sql	= !empty($game) 	? ' AND `game` = ' . $game : '';
		$server_sql	= !empty($server) 	? ' AND `server` = ' . $server : '';
		$user_sql 	= !empty($user) 	? ' AND (username LIKE "%' . $user . '%" OR orderid LIKE "%' . $user . '%") ' : '';
		
		$sql='SELECT count(`orderid`) as `user_all` FROM `ms_point_logs` WHERE 1 '. 
		$start_sql . ' ' . $end_sql . ' ' . $type_sql . ' ' . $game_sql . ' ' . $server_sql . ' ' . $user_sql;
		$data = model::getBySql($sql);
		
		return $data[0]['user_all'];
	}
	
	/* 取得转点数据 */
	public function get_trans_orders($start, $end, $type, $game, $server, $user, $firstcount, $perpage){
		$start_sql	= !empty($start)	? ' AND `time` >= ' . $start : '';
		$end_sql	= !empty($end)		? ' AND `time` <= ' . $end : ' AND `time` <= ' . time();
		$type_sql	= !empty($type) 	? ($type == 'online') ? ' AND `ordertype` = 0 ' : ' AND `ordertype` = 1 ' : '';
		$game_sql	= !empty($game) 	? ' AND `game` = ' . $game : '';
		$server_sql	= !empty($server) 	? ' AND `server` = ' . $server : '';
		$user_sql 	= !empty($user) 	? ' AND (username LIKE "%' . $user . '%" OR orderid LIKE "%' . $user . '%") ' : '';
		
		$sql='SELECT * FROM `ms_point_logs` WHERE 1 '. 
		$start_sql . ' ' . $end_sql . ' ' . $type_sql . ' ' . $game_sql . ' ' . $server_sql . ' ' . $user_sql . ' 
		ORDER BY `time` DESC limit '. $firstcount .',' .$perpage;
		$data = model::getBySql($sql);
		if (($num = count($data)) > 0) {
			for ($i=0; $i<$num; $i++){
				$data[$i]['gameName'] 	= $this->get_games($data[$i]['game']);
				$data[$i]['serverName'] = $this->get_games($data[$i]['server']);
			}
		}
		return $data;
	}
	
	/* 取得导出转点数据 */
	public function get_exTrans_orders($start, $end, $type, $game, $server, $user){
		$start_sql	= !empty($start)	? ' AND `time` >= ' . $start : '';
		$end_sql	= !empty($end)		? ' AND `time` <= ' . $end : ' AND `time` <= ' . time();
		$type_sql	= !empty($type) 	? ($type == 'online') ? ' AND `ordertype` = 0 ' : ' AND `ordertype` = 1 ' : '';
		$game_sql	= !empty($game) 	? ' AND `game` = ' . $game : '';
		$server_sql	= !empty($server) 	? ' AND `server` = ' . $server : '';
		$user_sql 	= !empty($user) 	? ' AND (username LIKE "%' . $user . '%" OR orderid LIKE "%' . $user . '%") ' : '';
		
		$sql='SELECT orderid, username, game, server, points, gold, FROM_UNIXTIME( `time`, "%Y-%m-%d %H:%i:%s" ) as time  FROM `ms_point_logs` WHERE 1 '. 
		$start_sql . ' ' . $end_sql . ' ' . $type_sql . ' ' . $game_sql . ' ' . $server_sql . ' ' . $user_sql.' '.'ORDER BY `time` DESC';
		
		$data = model::getBySql($sql);
		if (($num = count($data)) > 0) {
			for ($i=0; $i<$num; $i++){
				$data[$i]['game'] 	= $this->get_games($data[$i]['game']);
				$data[$i]['server'] = $this->get_games($data[$i]['server']);
			}
		}
		
		return $data;
	}
	
	/* 取得游戏数据 */
	public function get_games($evalue=0, $parentID=''){
		$where_sql	= !empty($parentID) ? ' AND parent_id = ' . intval($parentID) : ' AND evalue = ' . $evalue;
		if (empty($evalue) && empty($parentID)) {
			$where_sql = ' AND parent_id = 0';
		}	
		$sql='SELECT id, parent_id, ename, interface, alias, evalue FROM `blk_games_inter` WHERE 1 ' . $where_sql;
		$data = model::getBySql($sql);		
		$result = ($evalue == 0) ? $data : $data[0]['ename'];
		return $result;
	}
}