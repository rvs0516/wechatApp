<?php

/**
 * 主入口控制器
 * 
 */

class indexController extends controller {

	private $_api = null;

	/**
     * 构造函数
     */
	public function __construct() {
		parent::__construct();

		load('model.mGameSDKApi');
		$this->_api = new mGameSDKApi();
	}
	
	/**
	 * 触发事件回调
	 */
	public function callback() {
		$this->_api->callback();
		exit;
	}

	/**
	 * 获取成员ID列表
	 */
	public function getTeammateId() {
		$this->_api->getTeammateId();
		exit;
	}
}