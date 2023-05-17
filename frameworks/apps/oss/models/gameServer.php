<?php
error_reporting(0);

/**
 * 游戏管理
 */

class game {
	
	private $_game_model;
	
	public function __construct() {
		$this->_game_model = new Model('gc_game');
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
	public function getList($offset=null, $length=2) {
		$limit = '';
		if($offset !== null || $length !== null) {
			$limit = intval($offset) . ',' . intval($length);
		}
		return $this->_game_model->select('*', null, null, '`sort` ASC', $limit);
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
	public function getTotal() {
		$result = $this->_game_model->select("COUNT(*) as total");
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
		
		$category_model = getInstance('model.gamebox.category');
		$category_list = $category_model->getList();
		//游戏记录了分类的id，现在需要获取分类名称给用户显示，在这之前要做一些特别处理
		//使用表驱动法，创建一个键值为分类id，值为分类名的数组映射
		$category_with_name_key = array();
		foreach($category_list as $category) {
			$category_with_name_key[ $category['id'] ] = $category['name'];
		}
        $game_list_format = ($type == 'detail') ? $game_list : array();
		foreach($game_list as $key => $game) {
			//现在游戏可以轻松获取所属分类的名称
			$game_list_format[$key]['type'] = $category_with_name_key[ $game['category'] ];
            
			$game_list_format[$key]['attr'] = $game['type'];
			$game_list_format[$key]['name'] = $game['name'];
			$game_list_format[$key]['alias'] = $game['alias'];
			$game_list_format[$key]['itunes_id'] = $game['itunes_id'];
			$game_list_format[$key]['stars'] = $game['stars'];
			$game_list_format[$key]['home'] = $game['home'] . '';
			$game_list_format[$key]['info'] = $game['detail'];
			//格式化程序大小
			$game_list_format[$key]['size'] = formatSize($game['app_size'], 'M');
			//格式化时间
			$game_list_format[$key]['date'] = date('Y-m-d', $game['date']);
			
			//静态资源地址
			$static_source_site = trim(C('STATIC_SOURCE_SITE'), '/');
			$game_list_format[$key]['qr_code'] = 
			$game_list_format[$key]['link'] =
			$game_list_format[$key]['icon'] =
			$game_list_format[$key]['picture'] = '';
			if(!empty($game['qr_code'])) {
				//下载连接
				$game_list_format[$key]['qr_code'] = $static_source_site . $game['qr_code'];
			}
			if(!empty($game['app_file'])) {
				//下载连接
				$game_list_format[$key]['link'] = $static_source_site . $game['app_file'];
			}
			if(!empty($game['icon'])) {
				//图标连接
				$game_list_format[$key]['icon'] = $static_source_site. $game['icon'];
			}
			if(!empty($game['picture'])) {
				//大图连接
				$game_list_format[$key]['picture'] = $static_source_site. $game['picture'];
			}
            $game_list_format[$key]['os'] = 'android';
		}
		return $only_one ? $game_list_format[0] : $game_list_format;
	}
}