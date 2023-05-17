<?php
error_reporting(0);

/**
 * 游戏管理
 */

class game {

	private $_game_model;
	private $_baned_model;
	private $_server_model;
	private $_ms_game_pay;
	private $_special_model;
	private $_isolate_model;

	public function __construct() {
		$this->_game_model = new Model('ms_game');
		$this->_baned_model = new Model('ms_baned');
		$this->_server_model = new Model('ms_game_server');
		$this->_ms_game_pay = new model('ms_game_pay');
		$this->_special_model = new model('ms_special_list');
		$this->_isolate_model = new model('ms_game_isolate');
	}

	/**
	 * 获取一个游戏的详细信息
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_game_model->get("`id`='{$id}'");
	}

	/**
	 * 根据游戏别名获取游戏详细信息
	 * 
	 * @param string alias
	 * @return array
	 */
	public function getInfoByAlias($alias) {
		$alias = mysql_real_escape_string($alias);
		return $this->_game_model->get("`alias`='{$alias}'");
	}

	/**
	 * 获取游戏列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getList($offset=null, $length=null, $game, $status=0, $keywords="", $weixinType="", $alipayType="", $payMethod="", $gameStr = ''){
		$where = 1;
		if($game){
			$where .= " AND `alias` = '".$game."'";
		}
		if($status == 1){
			$where .= " AND `status` = '1'";
		}elseif($status == 2){
			$where .= " AND `status` = '0'";
		}
		if($keywords){
			$where .= " AND `name` LIKE '%". $keywords ."%'";
		}
/*		if($weixinType){
			if ($weixinType == 'zhangling|yiyao') {
				$where .= " AND (`weixinType` = '' OR `weixinType` = '" . $weixinType . "')";
			}else{
				$where .= " AND `weixinType` = '" . $weixinType . "'";
			}
		}
		if($alipayType){
			if ($alipayType == 'alipay|qianyou') {
				$where .= " AND (`alipayType` = '' OR `alipayType` = '" . $alipayType . "')";
			}else{
				$where .= " AND `alipayType` = '" . $alipayType . "'";
			}
		}*/
		if ($payMethod == 1) {
			$where .= " AND `payMethod` = '1'";
			if($weixinType){
				if ($weixinType == 'zhangling|fandian') {
					$where .= " AND (`wxAppType` = '' OR `weixinType` = '" . $weixinType . "')";
				}else{
					$where .= " AND `wxAppType` = '" . $weixinType . "'";
				}
			}
			if($alipayType){
				if ($alipayType == 'alipay|qianyou') {
					$where .= " AND (`aliAppType` = '' OR `aliAppType` = '" . $alipayType . "')";
				}else{
					$where .= " AND `aliAppType` = '" . $alipayType . "'";
				}
			}
		}elseif ($payMethod == 2) {
			$where .= " AND `payMethod` = '0'";
			if($weixinType){
				if ($weixinType == 'huifubao|fandian') {
					$where .= " AND (`weixinType` = '' OR `weixinType` = '" . $weixinType . "')";
				}else{
					$where .= " AND `weixinType` = '" . $weixinType . "'";
				}
			}
			if($alipayType){
				if ($alipayType == 'alipay|qianyou') {
					$where .= " AND (`alipayType` = '' OR `alipayType` = '" . $alipayType . "')";
				}else{
					$where .= " AND `alipayType` = '" . $alipayType . "'";
				}
			}
		}
		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND alias in (" . $gameStr . ") ";
			}else {
				$where .= " AND alias = $gameStr";
			}
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_game_model->select('*', $where, null, '`id` ASC', $limit);
	}

	/**
     * 获取游戏列表，以别名作为键值
	 * 
	 * @return array
     */
	public function getListByAlias() {
		$game_list = $this->_game_model->select('*', null, null, '`sort` ASC');
		$$game_list_by_alias = array();
		foreach($game_list as $game) {
			$game_list_by_alias[ $game['alias'] ] = $game;
		}
		return $game_list_by_alias;
	}
	/**
     * 获取游戏列表，以别名作为键值，同时格式化游戏
	 * 
	 * @return array
     */
	public function getListByAliasFormat() {
		$game_list = $this->_game_model->select('*', null, null, '`sort` ASC');
		if(!empty($game_list)) {
			$game_list = $this->formatData($game_list);
		}
		$$game_list_by_alias = array();
		foreach($game_list as $game) {
			$game_list_by_alias[ $game['alias'] ] = $game;
		}
		return $game_list_by_alias;
	}

	/**
	 * 根据类型获取游戏列表
	 * 
	 * @param string $type 类型
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getListByType($type='all', $offset=null, $length=null) {
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$type = mysql_real_escape_string($type);
		$where = $type == 'all' ? '' : "FIND_IN_SET('{$type}', `type`)";
		return $this->_game_model->select('*', $where, null, '`sort` ASC', $limit);
	}

	/**
	 * 根据类型获取该类型游戏总数
	 *
	 * @param unknown_type $type
	 * @return unknown
	 */
	public function getTotalByType($type='all') {
		$type = mysql_real_escape_string($type);
		$where = $type == 'all' ? '' : "FIND_IN_SET('{$type}', `type`)";
		$result = $this->_game_model->select("COUNT(*) as total", $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 根据分类获取游戏列表
	 * 
	 * @param string $category 分类id
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getListByCategory($category, $offset=null, $length=null) {
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$cid = mysql_real_escape_string($cid);
		$where = "`category`='{$category}'";
		return $this->_game_model->select('*', $where, null, '`sort` ASC', $limit);
	}

	/**
	 * 获取游戏总数
	 * 
	 * @return int
	 */
	public function getTotal($offset=null, $length=20, $game, $status=0, $keywords="", $weixinType="", $alipayType="", $payMethod="", $gameStr = '') {
		$where = 1;
		if($game){
			$where .= " AND `alias` = '".$game."'";
		}
		if($status == 1){
			$where .= " AND `status` = '1'";
		}elseif($status == 2){
			$where .= " AND `status` = '0'";
		}
		if($keywords){
			$where .= " AND `name` LIKE '%". $keywords ."%'";
		}
		if ($payMethod == 1) {
			$where .= " AND `payMethod` = '1'";
			if($weixinType){
				if ($weixinType == 'zhangling|fandian') {
					$where .= " AND (`wxAppType` = '' OR `weixinType` = '" . $weixinType . "')";
				}else{
					$where .= " AND `wxAppType` = '" . $weixinType . "'";
				}
			}
			if($alipayType){
				if ($alipayType == 'alipay|qianyou') {
					$where .= " AND (`aliAppType` = '' OR `aliAppType` = '" . $alipayType . "')";
				}else{
					$where .= " AND `aliAppType` = '" . $alipayType . "'";
				}
			}
		}elseif ($payMethod == 2) {
			$where .= " AND `payMethod` = '0'";
			if($weixinType){
				if ($weixinType == 'huifubao|fandian') {
					$where .= " AND (`weixinType` = '' OR `weixinType` = '" . $weixinType . "')";
				}else{
					$where .= " AND `weixinType` = '" . $weixinType . "'";
				}
			}
			if($alipayType){
				if ($alipayType == 'alipay|qianyou') {
					$where .= " AND (`alipayType` = '' OR `alipayType` = '" . $alipayType . "')";
				}else{
					$where .= " AND `alipayType` = '" . $alipayType . "'";
				}
			}
		}
		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND alias in (" . $gameStr . ") ";
			}else {
				$where .= " AND alias = $gameStr";
			}
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$result = $this->_game_model->select('COUNT(*) as total',$where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取分类下的游戏总数
	 * 
	 * @param string $cid 分类id
	 * @return int
	 */
	public function getTotalByCategory($cid) {
		$cid = mysql_real_escape_string($cid);
		$result = $this->_game_model->select("COUNT(*) as total", "`category`='{$cid}'");
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 增加游戏
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function add(array $data) {
		return $this->_game_model->set($data);
	}

	/**
	 * 编辑游戏
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function edit($id, array $data) {
		$id = mysql_real_escape_string($id);
		return $this->_game_model->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除游戏
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function delete($id) {
		$id = mysql_real_escape_string($id);
		$game = $this->getInfo($id);
		if(!empty($game)) {
			$success = $this->_game_model->delete("`id`='{$id}'");
			if($success) {
				//删除文件
				$static_path = C('STATIC_PATH');
				@unlink( $static_path . $game['icon'] );
				@unlink( $static_path . $game['image'] );
				@unlink( $static_path . $game['app_file'] );
			}
		}
	}

	/**
	 * 对游戏的数据进行格式化
	 * 方法在原有字段中增加一些新字段
	 * 
	 * @param array data 可以是一维（一行）或者多维（多行）数组
	 * @return array format_data
	 */
	public function formatData($game, $type='normal') {
		if(empty($game)) {
			return array();
		}
		$only_one = !isset($game[0]);
		$game_list = $only_one ? array($game) : $game;

		$category_model = getInstance('model.sdkGame.category');
		$category_list = $category_model->getList();
		//游戏记录了分类的id，现在需要获取分类名称给用户显示，在这之前要做一些特别处理
		//使用表驱动法，创建一个键值为分类id，值为分类名的数组映射
		$category_with_name_key = array();
		foreach($category_list as $category) {
			$category_with_name_key[ $category['id'] ] = $category['name'];
		}
		$game_list_format = ($type == 'detail') ? $game_list : array();

		//静态资源地址
		$static_source_site = trim(C('STATIC_SOURCE_SITE'));

		foreach($game_list as $key => $game) {
			//现在游戏可以轻松获取所属分类的名称
			$game_list_format[$key]['type'] = $category_with_name_key[ $game['category'] ];

			$game_list_format[$key]['download_total'] = $game['download_total'];
			$game_list_format[$key]['attr'] = $game['type'];
			$game_list_format[$key]['name'] = $game['name'];
			$game_list_format[$key]['alias'] = $game['alias'];
			//$game_list_format[$key]['itunes_id'] = $game['itunes_id'];
			$game_list_format[$key]['stars'] = $game['stars'];
			$game_list_format[$key]['home'] = $game['home'] . '';
			$game_list_format[$key]['info'] = $game['detail'];
			$game_list_format[$key]['size'] = round( filesize( C('DEDE_DATA_PATH').$game['app_file']) / (1024 * 1024) ) . "M";
			$game_list_format[$key]['qr_code'] =
			$game_list_format[$key]['link'] =
			$game_list_format[$key]['icon'] =
			$game_list_format[$key]['picture'] = '';
			$game_list_format[$key]['sdk_pic'] = '';

			if(!empty($game['qr_code'])) {
				//下载连接
				$game_list_format[$key]['qr_code'] = $static_source_site . $game['qr_code'];
			}
			if(!empty($game['app_file'])) {
				//下载连接
				$game_list_format[$key]['link'] = $game['app_file'];
			}
			if(!empty($game['icon'])) {
				//图标连接
				$game_list_format[$key]['icon'] = $static_source_site. $game['icon'];
			}
			if(!empty($game['picture'])) {
				//大图连接
				$game_list_format[$key]['picture'] = $static_source_site. $game['picture'];
			}

			if(!empty($game['sdk_pic1'])) {
				$game_list_format[$key]['sdk_pic1'] = $static_source_site . $game['sdk_pic1'];
			}
			if(!empty($game['sdk_pic2'])) {
				$game_list_format[$key]['sdk_pic2'] = $static_source_site . $game['sdk_pic2'];
			}
			if(!empty($game['sdk_pic3'])) {
				$game_list_format[$key]['sdk_pic3'] = $static_source_site . $game['sdk_pic3'];
			}
			if(!empty($game['sdk_pic4'])) {
				$game_list_format[$key]['sdk_pic4'] = $static_source_site . $game['sdk_pic4'];
			}

			$game_list_format[$key]['os'] = 'android';
			$game_list_format[$key]['package_name'] = $game['package_name'];
		}
		return $only_one ? $game_list_format[0] : $game_list_format;
	}

	/**
	 * 获取游戏列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getSettingList($offset=null, $length=2, $type='list', $game, $keywords) {
		$limit = '';
		$where = 1;
		if($game){
			$where .= " AND `alias` = '".$game."'";
		}
		if($keywords){
			$where .= " AND `name` LIKE '%". $keywords ."%' OR`alias` LIKE '%". $keywords ."%'";
		}
		if ($type == 'total') {
			$cols = 'COUNT(g.`alias`) AS num';
		}else {
			$cols = 'g.`name`, g.`alias`, g.`detail`';
			if($offset !== null || $length !== null) {
				$limit = 'limit ' . intval($offset) . ',' . intval($length);
			}
		}

		$sql = 'SELECT ' . $cols
		. ' FROM `ms_game` g where ' .$where .' '.$limit;

		return $result = $this->_game_model->getBySql($sql);
	}

	/**
	 * 获取专服游戏列表
	 */
	public function getSpecialName($upperName, $gameStr=null) {
		$where = 1;
		if($upperName){
			$where .= " AND `upperName` = '$upperName'";
		}
		if($gameStr){
			$where .= " AND `alias` IN (".$gameStr.")";
		}

		return $this->_game_model->select('DISTINCT `specialName`', $where, null, '`id` ASC', '');
	}

	/**
	 * 获取接入的游戏下换名游戏列表
	 */
	public function getGameName($upperName, $specialName, $gameStr=null) {
		$where = 1;
		if($upperName){
			$where .= " AND `upperName` = '$upperName'";
		}
		if($specialName){
			$where .= " AND `specialName` = '$specialName'";
		}
		if($gameStr){
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND `alias` IN (".$gameStr.")";
			}else {
				$where .= " AND `alias` = $gameStr";
			}
			
		}
		return $this->_game_model->select('DISTINCT `alias`, `name`', $where, null, '`id` ASC', '');
	}

	/**
	 * 增加屏蔽ip或设备号
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function banedAdd(array $data) {
		return $this->_baned_model->set($data);
	}

	/**
	 * 删除屏蔽ip或设备号
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function banedDelete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_baned_model->delete("`id`='{$id}'");
	}

	/**
	 * 获取登录屏蔽列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getBanedList($offset=null, $length=20, $banedKey, $start, $end) {
		$where = 1;
		if($banedKey){
			$where .= " AND `baned` = '" . $banedKey . "'";
		}
		if($start){
			$where .= " AND time >= " . $start;
		}
		if($end){
			$where .= " AND time <= " . $end;
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_baned_model->select('*', $where, null, '`time` DESC', $limit);
	}

	/**
	 * 获取登录屏蔽总数
	 */
	public function getBanedTotal($offset=null, $length=20, $banedKey, $start, $end) {
		$where = 1;
		if($banedKey){
			$where .= " AND `baned` = '" . $banedKey . "'";
		}
		if($start){
			$where .= " AND time >= " . $start;
		}
		if($end){
			$where .= " AND time <= " . $end;
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		$result = $this->_baned_model->select('COUNT(*) as total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取一个游戏区服的信息
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getServerInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_server_model->get("`id`='{$id}'");
	}

	/**
	 * 增加游戏区服信息
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function serverAdd($data) {
		return $this->_server_model->set($data);
	}

	/**
	 * 编辑游戏区服信息
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function serverEdit($id, $data) {
		$id = mysql_real_escape_string($id);
		return $this->_server_model->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除游戏区服信息
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function serverDelete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_server_model->delete("`id`='{$id}'");
	}

	/**
	 * 获取游戏区服信息列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getServerList($offset=null, $length=null, $upperName, $specialName, $reference) {
		$where = 1;
		if($upperName){
			$str = strpos($upperName, ',');
			if ($str) {
				$where .= " AND `upperName` IN (" . $upperName . ")";
			}else {
				$where .= " AND `upperName` = '" . $upperName . "'";
			}
			
		}
		if($specialName){
			$where .= " AND specialName = '" . $specialName . "'";
		}
		if($reference == 1){
			$where .= " AND reference = 1";
		}elseif($reference == 2){
			$where .= " AND reference != 1";
		}
		$limit = '';
		if($offset != null || $length != null) {
			$limit = intval($offset) . ',' . intval($length);
		}

		return $this->_server_model->select('*', $where, null, '`id` DESC', $limit);
	}

	/**
	 * 获取游戏区服信息总数
	 */
	public function getServerTotal($upperName, $specialName, $reference) {
		$where = 1;
		if($upperName){
			$where .= " AND `upperName` = '" . $upperName . "'";
		}
		if($specialName){
			$where .= " AND specialName = '" . $specialName . "'";
		}
		if($reference == 1){
			$where .= " AND reference = 1";
		}elseif($reference == 2){
			$where .= " AND reference != 1";
		}
		$result = $this->_server_model->select('COUNT(*) as total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
     * 取得游戏项目支出数据
     *
     */
	public function getGamePayList($offset=null, $length=null, $start_date, $end_date, $upper, $special, $alias, $channel, $apkNum, $module, $type){
		$where = 1;
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		$where .= $upper ? " AND upperName = '" . $upper . "' " : '';
		$where .= $special ? " AND specialName = '" . $special . "' " : '';
		$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
		if ($module) {
			$where .= " AND module = '" . $module . "' ";
			$where .= $type ? " AND type = '" . $type . "' " : '';
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_ms_game_pay->select('*', $where, null, '`date` DESC', $limit);
	}

	/**
     * 取得游戏项目支出数据条数
     *
     */
	public function getGamePayListTotal($start_date, $end_date, $upper, $special, $alias, $channel, $apkNum, $module, $type, $demand){
		$where = 1;
		$where .= $start_date ? " AND date >= '" . $start_date . "' " : '';
		$where .= $end_date ? " AND date <= '" . $end_date . "' " : '';
		$where .= $upper ? " AND upperName = '" . $upper . "' " : '';
		$where .= $special ? " AND specialName = '" . $special . "' " : '';
		$where .= $alias ? " AND gameAlias = '" . $alias . "' " : '';
		$where .= $channel ? " AND channelId = '" . $channel . "' " : '';
		$where .= $apkNum ? " AND apkNum = '" . $apkNum . "' " : '';
		if ($module) {
			$where .= " AND module = '" . $module . "' ";
			$where .= $type ? " AND type = '" . $type . "' " : '';
		}
		if ($demand == 'count') {
			$result = $this->_ms_game_pay->select('COUNT(*) as total', $where);
			return is_array($result) && isset($result[0]['total']) ? $result[0]['total'] : 0;
		}elseif ($demand == 'sum') {
			$result = $this->_ms_game_pay->select('SUM(pay) as pay', $where);
			return is_array($result) && isset($result[0]['pay']) ? $result[0]['pay'] : 0;
		}
	}

	/**
	 * 获取一条游戏支出的信息
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getgamePayInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_ms_game_pay->get("`id`='{$id}'");
	}

	/**
	 * 增加游戏支出信息
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function gamePayAdd($data) {
		return $this->_ms_game_pay->set($data);
	}

	/**
	 * 编辑游戏支出信息
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function gamePayEdit($id, $data) {
		$id = mysql_real_escape_string($id);
		return $this->_ms_game_pay->set($data, "`id`='{$id}'");
	}

	/**
	 * 删除游戏支出信息
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function gamePayDelete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_ms_game_pay->delete("`id`='{$id}'");
	}

	/**
	 * 增加特殊处理用户
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function specialAdd(array $data) {
		return $this->_special_model->set($data);
	}

	/**
	 * 删除特殊处理用户
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function specialDelete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_special_model->delete("`id`='{$id}'");
	}

	/**
	 * 获取特殊处理用户列表
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getSpecialList($offset=null, $length=20, $userName, $game, $sumString, $specialString, $type, $gameStr = '') {
		$where = 1;
		if($userName){
			$where .= " AND `userName` = '" . $userName . "'";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($game) {
			$where .= " AND gameAlias = '" . $game . "'";
		}
		if($type) {
			$where .= " AND type = '" . $type . "'";
		}
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND gameAlias in (" . $gameStr . ") ";
			}else {
				$where .= " AND gameAlias = $gameStr";
			}
		}

		return $this->_special_model->select('*', $where, null, '`time` DESC', $limit);
	}

	/**
	 * 获取特殊处理用户总数
	 */
	public function getSpecialTotal($userName, $game, $sumString, $specialString, $type, $gameStr = '') {
		$where = 1;
		if($userName){
			$where .= " AND `userName` = '" . $userName . "'";
		}
		if($sumString && !$game){
			$string = " AND gameAlias IN (" . $sumString . ") ";
			if($specialString){
				$string = " AND gameAlias IN (" . $specialString . ") ";
			}
			$where .= $string;
		}
		if($game) {
			$where .= " AND gameAlias = '" . $game . "'";
		}
		if($type) {
			$where .= " AND type = '" . $type . "'";
		}
		if ($gameStr) {
			$str = strpos($gameStr, ',');
			if ($str) {
				$where .= " AND gameAlias in (" . $gameStr . ") ";
			}else {
				$where .= " AND gameAlias = $gameStr";
			}
		}
		$result = $this->_special_model->select('COUNT(*) as total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}


	/**
	 * 批量添加
	 */
	public function specialBatchAdd($userNamestr, $data) {
		$strPattern = "/[a-z]{2}[0-9]{6}/";
	    $arrMatches = [];
	    preg_match_all($strPattern, $userNamestr, $arrMatches);
		if (count($arrMatches[0]) > 100) {
			ShowMsg('单次处理条数不能超过100条', '-1');
		}
		foreach ($arrMatches[0] as $key => $value) {
			$va .= "('{$value}', '{$data['time']}', '{$data['uid']}', '{$data['type']}', '{$data['gameAlias']}', '{$data['ext']}'),"; 
		}
		//关联账号也需要被处理
		$relate = "'".implode("','",$arrMatches[0])."'";
		$rSql = "select * from ms_member_info where userName in (".$relate.")";
		$rData = MODEL::getBySql($rSql);
		$vaRelate = '';
		if ($rData) {
			foreach ($rData as $k => $v) {
				if (!empty($v['assUserName'])) {
					$vaRelate .= "('{$v['assUserName']}', '{$data['time']}', '{$data['uid']}', '{$data['type']}', '{$data['gameAlias']}', '{$data['ext']}'),"; 
				}
			}
		}
		$rasSql = "select * from ms_member_info where assUserName in (".$relate.")";
		$rasData = MODEL::getBySql($rasSql);
		$rasVaRelate = '';
		if ($rasData) {
			foreach ($rasData as $k1 => $v1) {
				if (!empty($v1['userName'])) {
					$rasVaRelate .= "('{$v1['userName']}', '{$data['time']}', '{$data['uid']}', '{$data['type']}', '{$data['gameAlias']}', '{$data['ext']}'),"; 
				}
			}
		}
		
		$sql = 'INSERT IGNORE INTO ms_special_list(`userName`, `time`, `uid`, `type`, `gameAlias`, `ext`) VALUES'.$vaRelate.$rasVaRelate.substr($va, 0, -1);
		$res = MODEL::getBySql($sql);
		return $res;
	}


	/**
	 * 获取配置渠道隔离的游戏
	 * 
	 * @param int $offset 位置
	 * @param int $length 数量
	 * @return array
	 */
	public function getIsolateList($offset=null, $length=null, $upperName, $specialName, $gameAlias, $status) {
		$where = 1;
		$where .= $upperName ? " AND `upperName` = '" . $upperName . "'" : "";
		$where .= $specialName ? " AND `specialName` = '" . $specialName . "'" : "";
		$where .= $gameAlias ? " AND `gameAlias` = '" . $gameAlias . "'" : "";
		if ($status == 2) {
			$where .= " AND `status` = 0";
		}else{
			$where .= $status ? " AND `status` = '" . $status . "'" : "";
		}

		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_isolate_model->select('*', $where, null, '`id` DESC', $limit);
	}


	/**
	 * 获取配置渠道隔离的游戏总数
	 */
	public function getIsolateTotal($upperName, $specialName, $gameAlias, $status)  {
		$where = 1;
		$where .= $upperName ? " AND `upperName` = '" . $upperName . "'" : "";
		$where .= $specialName ? " AND `specialName` = '" . $specialName . "'" : "";
		$where .= $gameAlias ? " AND `gameAlias` = '" . $gameAlias . "'" : "";
		if ($status == 2) {
			$where .= " AND `status` = 0";
		}else{
			$where .= $status ? " AND `status` = '" . $status . "'" : "";
		}

		$result = $this->_isolate_model->select('COUNT(*) as total', $where);
		return is_array($result) && isset($result[0]['total']) ? intval($result[0]['total']) : 0;
	}

	/**
	 * 获取添加的渠道隔离的游戏是否存在
	 */
	public function getIsolateIsset($upperName, $specialName, $gameAlias) {
		$where = 1;
		$where .= " AND `upperName` = '" . $upperName . "'";
		$where .= " AND `specialName` = '" . $specialName . "'";
		$where .= " AND `gameAlias` = '" . $gameAlias . "'";

		return $this->_isolate_model->select('*', $where, null, null, $limit);
	}

	/**
	 * 增加渠道隔离的游戏
	 * @param array $data
	 * @return boolean
	 */
	public function isolateAdd(array $data, $id) {
		if ($id) {
			return $this->_isolate_model->set($data, "`id`='{$id}'");
		}else{
			return $this->_isolate_model->set($data);
		}
	}

	/**
	 * 删除渠道隔离的游戏
	 * @param int $id
	 * @return boolean
	 */
	public function isolateDelete($id) {
		$id = mysql_real_escape_string($id);
		return $this->_isolate_model->delete("`id`='{$id}'");
	}

	/**
	 * 获取渠道隔离项信息
	 * @param int $id
	 * @return array
	 */
	public function getIsolateInfo($id) {
		$id = mysql_real_escape_string($id);
		return $this->_isolate_model->get("`id`='{$id}'");
	}
}