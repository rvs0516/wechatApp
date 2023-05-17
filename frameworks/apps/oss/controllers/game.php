<?php

require_once APP_CONTROLLER_PATH . '/master.php';
//error_reporting(E_ALL);
class gameController extends masterControl {
	/**
	 * 开服信息
	 */
	public function openServiceInfo() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';

		load('model.openService');
		//私有操作
		$operation_method = '_' . $operation . 'OpenServiceInfo';
		if(method_exists($this, $operation_method)) {
			$this->{$operation_method}($_REQUEST);
		} else {
			//取出开服列表
			$service_list = openService::getList();
			$this->assign('service_list', $service_list);
		}
		$this->assign('operation', $operation);
	}

	/**
	 * 增加开服信息
	 * 
	 * @see gameController::openServiceInfo
	 */
	private function _addOpenServiceInfo($data) {

	}

	/**
	 * 编辑开服信息
	 * 
	 * @see gameController::openServiceInfo
	 */
	private function _editOpenServiceInfo($data) {
		$id = intval($data['id']);
		if(empty($id)) {
			showMsg('數據不存在');
		}
		$service = openService::get($id);
		$this->assign('service', $service);
	}

	/**
	 * 保存开服信息
	 * 
	 * @see gameController::openServiceInfo
	 */
	private function _saveOpenServiceInfo($data) {
		$check_data = !( empty($data['game']) || empty($data['server']) ||
		empty($data['date']) || empty($data['url']) );
		if(!$check_data) {
			showMsg('標星的數據必需填寫', -1);
		}
		openService::save($data);
		showMsg('保存成功', '/index.php?m=game&a=openServiceInfo');
	}

	/**
	 * 删除开服信息
	 * 
	 * @see gameController::openServiceInfo
	 */
	private function _delOpenServiceInfo($data) {
		$id = intval($data['id']);
		if(empty($id)) {
			showMsg('數據不存在');
		}
		openService::delete($id);
		showMsg('刪除成功', '/index.php?m=game&a=openServiceInfo');
	}

	/**
	 * sdk 退出对话框广告推播内容管理
	 */
	public function sdkAdvInfo() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';

		load('model.pushAdv');
		$operation_method = '_' . $operation . 'SdkAdvInfo';
		if(method_exists($this, $operation_method)) {
			$this->{$operation_method}($_REQUEST);
		} else {
			$push = pushAdv::getList();
			$this->assign('pushData', $push);
			$this->assign('image_tri', trim(C('STATIC_SOURCE_SITE'), '/'));
		}

		$this->assign('operation', $operation);
	}

	/**
	 * 编辑推播内容
	 * 
	 */
	private function _editSdkAdvInfo($data) {
		$id = intval($data['id']);
		if(empty($id)) {
			showMsg('數據不存在');
		}
		$push = pushAdv::getAdvInfo('', $id);
		$this->assign('pushData', $push[0]);
		
		$game_model = getInstance('model.sdkGame.game');
		$gameData = $game_model->getList(0, 100);
		$this->assign('data', array_values($gameData));
		
		if (!empty($push[0]['game'])) {
			$alias = explode(",", $push[0]['game']);
			$this->assign('alias', $alias);
		}
		
		$this->assign('image_tri', trim(C('STATIC_SOURCE_SITE'), '/'));
	}
	
	/**
	 * 编辑推播内容
	 * 
	 */
	private function _addSdkAdvInfo($data) {		
		$game_model = getInstance('model.sdkGame.game');
		$gameData = $game_model->getList(0, 100);
		$this->assign('data', array_values($gameData));
	}	

	/**
	 * 保存推播内容
	 * 
	 */
	private function _saveSdkAdvInfo($data) {
		$check_data = !( empty($data['title']) );
		if(!$check_data) {
			showMsg('標星的數據必需填寫', -1);
		}
		
		$string = '';
		if ($_POST['game']) {
			foreach ($_POST['game'] as $key => $value) {
				$string .= $value . ",";
			}
		}
		$data['game'] = (!empty($string)) ? substr($string, 0, -1) : '';
		
        load('uploadfile');
		if(!empty($_FILES['image']['name'][0])) {
			$filetypes = array('png', 'gif', 'jpg', 'jpeg');
			$dir = '/sdkfile/task/';
			$path = C('DEDE_DATA_PATH') . $dir;
			if (!file_exists($path)) {
				@mkdir($path);
			}
			
			$upload = new uploadfile($_FILES['image'], $path, 999999999999, $filetypes);
			
			$file_name = 'SdkAdvInfo_' . time();
			$success = !!$upload->upload( $file_name );
			if(!$success) {
				ShowMsg("上傳圖標失敗", -1);
			}
			$data['image'] = $dir . $file_name . '.' .
					pathinfo($_FILES['image']['name'][0], PATHINFO_EXTENSION);
		}

		pushAdv::save($data);
		showMsg('保存成功', '/index.php?m=game&a=sdkAdvInfo');
	}
	
	/**
	 * 删除开服信息
	 * 
	 * @see gameController::openServiceInfo
	 */
	private function _delSdkAdvInfo($data) {
		$id = intval($data['id']);
		if(empty($id)) {
			showMsg('數據不存在');
		}
		pushAdv::delete($id);
		showMsg('刪除成功', '/index.php?m=game&a=sdkAdvInfo');
	}
	
}
?>