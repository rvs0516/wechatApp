<?php

require_once APP_CONTROLLER_PATH . '/master.php';
class shareRecordController extends masterControl {

	/**
	 * 分享信息
	 */
	public function shareRecord() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';

		load('model.shareRecord');
		//私有操作
		$operation_method = '_' . $operation . 'ShareRecord';
		if(method_exists($this, $operation_method)) {
			$this->{$operation_method}($_REQUEST);
		} else {
			//取出开服列表
			$share_list = shareRecord::getShareList();
			$this->assign('share_list', $share_list);
		}
		$this->assign('operation', $operation);
	}

	/**
	 * 分享信息列表
	 * 
	 */
	private function _indexShareRecord() {
		$res_game = trim($_REQUEST['game']);
		$start_date = trim($_REQUEST['start_date']);
		$end_date = trim($_REQUEST['end_date']);

		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
		$row = 25;
		$offset = ($page - 1) * $row;

		$share = getInstance('model.shareRecord');
		$share_list = $share->getShareList($res_game, $start_date, $end_date, $offset, $row);

		$game_model = getInstance('model.sdkGame.game');
		$game_list = $game_model->getList(0, 100);

		foreach($share_list as $key => $value){
			foreach ($game_list as $k => $val){
				if($value['game'] == $val['alias']){
					$share_list[$key]['game_name'] = $val['name'];
				}
			}
		}

		$share_total = $share->getShareTotal($res_game, $start_date, $end_date);
		$array = array(
		'share_list' => $share_list,
		'list_page' => $page,
		'list_length' => $row,
		'list_total' => $share_total,
		'game_list' => $game_list,
		'res_game' => $res_game,
		'start_date' => $start_date,
		'end_date' => $end_date
		);
		$this->assign($array);
	}
}