<?php

require_once APP_CONTROLLER_PATH . '/master.php';

class sdkArchivesController extends masterControl {

	/**
	 * 文章管理
	 */
	public function archives() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		//注入操作标识，用它来告诉程序显示哪些视图
		$this->assign('operation', $operation);

		//初始化分类模型
		$archivesModel = getInstance('model.sdkArchives.archives');
		switch($operation) {
			case 'index':
				$page = array_key_exists('page', $_GET) ? abs( intval($_GET['page']) ) : 0;
				$page = $page === 0 ? 1 : $page;
				$length = 20;
				$offset = ($page - 1) * $length;
				$type = trim($_REQUEST['type']) ? trim($_REQUEST['type']) : "";

				$archivesList = $archivesModel->getList($offset, $length, $type);
				$total = $archivesModel->getTotal($type);
				$this->assign('archivesList', $archivesList);
				$this->assign('length', $length);
				$this->assign('offset', $offset);
				$this->assign('type', $type);
				$this->assign('total', $total);
				$this->assign('page', $page);
				break;

			case 'edit':
				$archives = $archivesModel->getInfo($_GET['id']);
				$this->assign('archives', $archives);
				break;

			case 'add':
				//do nothing
				break;

			case 'save':
				//检查是新增还是编辑
				$archives = $archivesModel->getInfo( $_POST['id'] );
				$is_new = empty($archives);
				$saveData = array(
					'type' => $_POST['type'],
					'title' => $_POST['title'],
					'remark' => $_POST['remark'],
					'desc' => str_replace('\r\n', '', $_POST['desc'])
					);
				//var_dump($saveData);exit;$args = str_replace('\"', '"', $this->_params['args']);

				if($is_new) {
					$success = $archivesModel->add($saveData);
				} else {
					$success = $archivesModel->edit($_POST['id'],$saveData);
				}
				ShowMsg('操作成功', '/index.php?m=sdkArchives&a=archives');
				break;

			
		}
	}

}