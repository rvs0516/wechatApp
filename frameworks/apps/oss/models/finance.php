<?php
/**
 * 财务模块-利润详情
 */
class finance {

    private $_statistics_model;
	private $_ms_intermodal_daily;
	private $_ms_members;
	private $_ms_agent_orders;
	private $_ms_intermodal_hour;
	private $_ms_game;
	private $ms_integrated_daily;
	private $_ms_role;
	private $_ms_profit;

	public function __construct() {
		$this->_statistics_model = new Model('ms_statistics');
		$this->_ms_members = new Model('ms_member');
		$this->_ms_agent_orders = new Model('ms_order');
		$this->_ms_game = new model('ms_game');
		$this->_integrated_model = new model('ms_integrated_daily');
		$this->_ms_role = new model('ms_role_seted');
		$this->_ms_profit = new model('ms_profit_daily');
	}

    /**
    * 获取上级游戏列表(GS角色组)
    * @return array
    */
    public function getUpperListGs($game) {
        $sql = 'SELECT DISTINCT `upperName` FROM ms_game WHERE `upperName` != "" AND `alias` IN ('.$game.')';
        $result = Model::getBySql($sql);
        return $result;
    }

    /**
    * 获取上级游戏列表(超级管理员组)
    * @return array
    */
    public function getUpperList() {
        $sql = 'SELECT DISTINCT `upperName` FROM ms_game WHERE `upperName` != ""';
        $result = Model::getBySql($sql);
        return $result;
    }

    /**
    * 获取专服游戏列表(超级管理员组)
    * @return array
    */
    public function getSpecialList($upperName) {
        $sql = 'SELECT DISTINCT `specialName` FROM ms_game WHERE `upperName` = "' . $upperName . '" AND `specialName` !=""';
        $result = Model::getBySql($sql);
        return $result;
    }

    /**
    * 取得利润数据列表
    *
    */
    public function getProfit($game, $channel, $start_date, $end_date, $offset, $row, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = '', $dataTable){

        if ($refine == 2 || $refine == 3 || $refine == 4 || $refine == 5) {
            $where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
            $where .= $type ? " AND p.`type` = '" . $type . "' " : '';	
            if($source == 1){
                $where .= " AND p.`source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND p.`source` = 0 ";
            }
        }else{
            $where = 1;
            $where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
            $where .= $type ? " AND type = '" . $type . "' " : '';
            $where .= $channel ? " AND channelId = '" . $channel . "' " : '';
            if($source == 1){
                $where .= " AND `source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND `source` = 0 ";
            }
            if($status){
                $where .= " AND `status` = '$status' ";
            }else{
                $where .= " AND `status` = 1 ";
            }
        }

        if ($gameStr) {
            $str = strpos($gameStr, ',');
            if ($str) {
                $where .= " AND gameAlias in (" . $gameStr . ") ";
            }else {
                $where .= " AND gameAlias = $gameStr";
            }
        }

        $limit = '';
        if($offset !== null || $row !== null) {
            $limit = intval($offset) . ',' . intval($row);
        }
        if ($refine == 1) {
            $sql = 'SELECT SUM(`amount`) AS amount, SUM(`cpAmount`) AS cpAmount, SUM(`channelAmount`) AS channelAmount, SUM(`profit`) AS profit, SUM(`adPay`) AS adPay, SUM(`exPay`) AS exPay, SUM(`disAmount`) AS disAmount, date, SUM(`actualPay`) AS actualPay, SUM(`income`) AS income FROM '. $dataTable. ' WHERE '.$where.' GROUP BY `date` ORDER BY date DESC LIMIT ' . $limit;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 2) {
            $sql = 'SELECT g.`specialName`, SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`exPay`) AS exPay, SUM(p.`disAmount`) AS disAmount, p.`date`, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM '. $dataTable. ' AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where.' GROUP BY g.`specialName`, p.`date` ORDER BY p.`date` DESC , g.`specialName` LIMIT ' . $limit;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 3) {
            $sql = 'SELECT g.`name`, SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`exPay`) AS exPay, SUM(p.`disAmount`) AS disAmount, p.`date`,p.`gameAlias`, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM '. $dataTable. ' AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND  g.`specialName` = "'.$specialName.'"'. $where.' GROUP BY g.`alias`, p.`date` ORDER BY p.`date` DESC, g.`name`LIMIT ' . $limit;
            $result = Model::getBySql($sql);

            return $result;
        }elseif ($refine == 4) {

            $where .= $game ? " AND p.gameAlias = '" . $game . "' " : '';
            $where .= $channel ? " AND p.channelId = '" . $channel . "' " : '';
            $sql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelName`, SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` FROM  '. $dataTable. '  as p LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` WHERE 1 '. $where. ' AND h.`channelId` = p.`channelId` GROUP BY g.`name`, p.`channelName` ORDER BY p.date DESC LIMIT ' . $limit;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 5) {

            // 非乾游数据
            $sql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`apkNum`, p.`gameAlias`, 
            SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` 
            FROM  '. $dataTable. '  as p 
            LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
            LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` 
            WHERE p.`channelId` != "160068" 
            GROUP BY p.`gameAlias` , p.`channelName`, p.`apkNum` 
            ORDER BY p.date DESC 
            LIMIT ' . $limit;
            $result = Model::getBySql($sql);

            // 乾游数据
            $qySql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`gameAlias`, 
            SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` 
            FROM  '. $dataTable. '  as p 
            LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
            LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` 
            WHERE p.`channelId` = "160068" 
            GROUP BY p.`gameAlias` , p.`channelName` 
            ORDER BY p.date DESC 
            LIMIT ' . $limit;
            $qyResult = Model::getBySql($qySql);
            $res = array_merge($result, $qyResult);

            // 非乾游数据和乾游数据
            // $qySql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`gameAlias`, 
            // SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` 
            // FROM  '. $dataTable. ' as p 
            // LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
            // LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` 
            // GROUP BY p.`gameAlias` , p.`channelName`, p.`apkNum` 
            // ORDER BY p.date DESC 
            // LIMIT ' . $limit;
            // $res = Model::getBySql($qySql);

            return $res;
        }
    }

    /**
    * 取得利润数据总条数
    *
    */
    public function getProfitTotal($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = ''){

        if ($refine == 2 || $refine == 3 || $refine == 4 || $refine == 5) {
            $where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
            $where .= $type ? " AND p.`type` = '" . $type . "' " : '';
            if($source == 1){
                $where .= " AND p.`source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND p.`source` = 0 ";
            }
        }else{
            $where = 1;
            $where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
            $where .= $type ? " AND type = '" . $type . "' " : '';
            if($source == 1){
                $where .= " AND `source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND `source` = 0 ";
            }
            if($status){
                $where .= " AND `status` = '$status' ";
            }else{
                $where .= " AND `status` = 1 ";
            }
        }

        if ($gameStr) {
            $str = strpos($gameStr, ',');
            if ($str) {
                $where .= " AND gameAlias in (" . $gameStr . ") ";
            }else {
                $where .= " AND gameAlias = $gameStr";
            }
        }

        if ($refine == 1) {
            $sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily WHERE '.$where.' GROUP BY `date`';
            $result = Model::getBySql($sql);
            return count($result);
        }elseif ($refine == 2) {
            $sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where.' GROUP BY g.`specialName`, p.`date`';
            $result = Model::getBySql($sql);
            return count($result);
        }elseif ($refine == 3) {
            $sql = 'SELECT COUNT(1) AS total FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND  g.`specialName` = "'.$specialName.'"'. $where.' GROUP BY g.`alias`, p.`date`';
            $result = Model::getBySql($sql);
            return count($result);
        }elseif ($refine == 4) {
            $where .= $game ? " AND gameAlias = '" . $game . "' " : '';
            $where .= $channel ? " AND channelId = '" . $channel . "' " : '';
            $where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
            $result = $this->_ms_profit->select('COUNT(1) AS total', $where);
            return $result[0]['total'];
        }elseif ($refine == 5) {
            // 非乾游数据
            $sql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`apkNum`, p.`gameAlias`, 
            SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` 
            FROM `ms_profit_daily` as p 
            LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
            LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` 
            WHERE 1 '. $where. ' AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` AND p.`channelId` != "160068" 
            GROUP BY p.`gameAlias` , p.`channelName`, p.`apkNum` 
            ORDER BY p.date DESC';
            $result = Model::getBySql($sql);

            // 乾游数据
            $qySql = 'SELECT g.`upperName`, g.`specialName`, g.`name`, g.`cpName`, g.`cpAllowance`, h.`mainPart`, h.`channelAllowance`, p.`channelId`, p.`channelName`, p.`gameAlias`, 
            SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit, p.`date` 
            FROM `ms_profit_daily` as p 
            LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` 
            LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` 
            WHERE 1 '. $where. ' AND h.`channelId` = p.`channelId` AND h.`apkNum` = p.`apkNum` AND p.`channelId` = "160068" 
            GROUP BY p.`gameAlias` , p.`channelName` 
            ORDER BY p.date DESC';
            $qyResult = Model::getBySql($qySql);

            $res = array_merge($result, $qyResult);

            return count($res);
        }
    }

    /**
    * 取得项目支出总数
    *
    */
    public function getGamePay($start_date, $end_date, $refine, $upper, $special, $alias, $channel, $apkNum, $model, $gameStr = '', $resertSpecialName = ''){

        $where = '';
        $where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
        $where .= $end_date ? " AND date <= '" . $end_date . "' " : '';

        if ($model == 'consumption') {

            // 筛选专服
            if ($resertSpecialName) {
                $where .= " AND specialName NOT IN ($resertSpecialName)";
            }

            if ($channel) {
                $marketChannel = strpos($channel, ',');
                $where .= $marketChannel ? " AND channelId in (". $channel. ")" : " AND channelId = '" . $channel . "' ";
            }

            if ($upper) {
                $where .= " AND upperName = '" . $upper . "' ";
                $where .= $special ? " AND specialName = '" . $special . "' " : '';
                $where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';
                $where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';

                $sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
            }else{
                $sql = 'SELECT SUM(pay) as pay, upperName FROM ms_game_pay WHERE module != 1 '.$where.' GROUP BY upperName';
            }

        }elseif ($model == 'profit') {
            
            $group = '';
            if ($upper) {
                $where .= " AND upperName = '" . $upper . "' ";
                $where .= $special ? " AND specialName = '" . $special . "' " : '';
                $where .= $channel ? " AND channelId = '" . $channel . "' " : '';
                $where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
                $group .= ',specialName';
                if ($refine == 3) {
                    $group .= ', specialName, gameAlias';
                }
                $sql = 'SELECT SUM(pay) as pay, date, module, remark '.$group.' FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date, module '.$group;
                if ($refine == 4) {
                    $sql = 'SELECT * FROM ms_game_pay WHERE 1 '.$where;
                }
            }else{
                $sql = 'SELECT SUM(pay) as pay, date, module FROM ms_game_pay WHERE 1 '.$where.' GROUP BY date, module';
                if ($refine == 5) {
                    $sql = 'SELECT SUM(pay) as pay, module, upperName FROM ms_game_pay WHERE 1 '.$where.' GROUP BY module, upperName';
                }
            }
        }
        $result = Model::getBySql($sql);
        return $result;
    }

    /**
    * 取得利润数据总数
    *
    */
    public function getProfitSummary($game, $channel, $start_date, $end_date, $apkNum, $upperName, $specialName, $refine, $type, $source, $status, $gameStr = ''){
        if ($refine == 2 || $refine == 3 || $refine == 4 || $refine == 5) {
            $where .= $start_date ? " AND p.`date` >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND p.`date` <= '" . $end_date . "' " : '';
            $where .= $type ? " AND p.`type` = '" . $type . "' " : '';
            if($source == 1){
                $where .= " AND p.`source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND p.`source` = 0 ";
            }
        }else{
            $where = 1;
            $where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
            $where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
            $where .= $type ? " AND type = '" . $type . "' " : '';
            if($source == 1){
                $where .= " AND `source` = 1 ";
            }elseif ($source == 2) {
                $where .= " AND `source` = 0 ";
            }
            if($status){
                $where .= " AND `status` = '$status' ";
            }else{
                $where .= " AND `status` = 1 ";
            }
        }

        if ($gameStr) {
            $str = strpos($gameStr, ',');
            if ($str) {
                $where .= " AND gameAlias IN (" . $gameStr . ") ";
            }else {
                $where .= " AND gameAlias = " . $gameStr . " ";
            }
        }

        if ($refine == 2) {
            $sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'"'. $where;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 3) {
            $sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(p.`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE g.`upperName` = "'.$upperName.'" AND g.`specialName` = "'.$specialName.'"'. $where;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 4) {
            $where .= $game ? " AND p.gameAlias = '" . $game . "' " : '';
            $sql = 'SELECT SUM(p.`amount`) as amount, SUM(p.`disAmount`) as disAmount, SUM(p.`cpAmount`) as cpAmount, SUM(p.`channelAmount`) as channelAmount, SUM(p.`profit`) as profit FROM `ms_profit_daily` as p LEFT JOIN ms_game as g ON g.`alias` = p.`gameAlias` LEFT JOIN ms_channel as h ON h.`gameAlias` = p.`gameAlias` WHERE 1 '. $where. ' AND h.`channelId` = p.`channelId` GROUP BY g.`name`';
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 1) {
            $sql = 'SELECT SUM(`amount`) AS amount, SUM(`cpAmount`) AS cpAmount, SUM(`channelAmount`) AS channelAmount, SUM(`profit`) AS profit, SUM(`adPay`) AS adPay, SUM(`disAmount`) AS disAmount, SUM(`exPay`) AS exPay, SUM(`actualPay`) AS actualPay, SUM(`income`) AS income FROM ms_profit_daily WHERE '.$where;
            $result = Model::getBySql($sql);
            return $result;
        }elseif ($refine == 5) {
            $sql = 'SELECT SUM(p.`amount`) AS amount, SUM(p.`cpAmount`) AS cpAmount, SUM(p.`channelAmount`) AS channelAmount, SUM(p.`profit`) AS profit, SUM(p.`adPay`) AS adPay, SUM(`disAmount`) AS disAmount, SUM(p.`exPay`) AS exPay, SUM(p.`actualPay`) AS actualPay, SUM(p.`income`) AS income FROM ms_profit_daily AS p LEFT JOIN ms_game AS g ON g.`alias` = p.`gameAlias` WHERE 1 '.$where;
            $result = Model::getBySql($sql);
            return $result;
        }
    }
}