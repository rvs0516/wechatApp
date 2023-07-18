<?php

require_once APP_CONTROLLER_PATH . '/master.php';
//入口类
class indexController extends masterControl  {	
	
	public function index() {
		$this->checkLogin();
	}
	
}