<?php
require_once APP_CONTROLLER_PATH . '/master.php';

class mobileController extends masterControl{
	
	public function __construct() {
        parent::__construct();
        load('model.mobileModel');
    }
    
	/**
	 * 
	 * 帮助问题的添加和编辑
	 */
	public function editquestion(){
		$this->checkLogin();
		$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : null;
		if($_POST){
			$question = $_POST['question'];
			$answer   = $_POST['answer'];
			$sort     = $_POST['sort'] ? $_POST['sort'] : 0;
			$enable   = $_POST['enable'] ?  1 : 0;
			$flag     = mobileModel::editquestion($id, $question, $answer, $sort, $enable);
			if($flag){
				ShowMsg('操作成功', '?m=mobile&a=listquestion');
			}
		}
		if(!empty($id)){
			$question = mobileModel::getQuestionById($id);
			if(empty($question)){
				ShowMsg('问题不存在', '?m=mobile&a=listquestion');
			}
			$this->assign('question',$question);
			$this->assign('action','編輯');
		}else{
			$this->assign('action','添加');
		}
	}
	
	/**
	 * 
	 * 列出全部帮助问题
	 */
	public function listquestion(){
		$this->checkLogin();
		$questions = mobileModel::getQuestions();
		$this->assign('questions',$questions);
	}
	
	
	/**
	 * 
	 * 列出所有游戏
	 */
	public function listgame(){
		$this->checkLogin();
		$games = mobileModel::getGames();
		$this->assign('games',$games);
	}
	
	/**
	 * 
	 * 添加游戏
	 */
	public function addgame(){
		$this->checkLogin();
		if($_POST){
			$name = $_POST['name'];
			$fpath = C('MOBILE_PATH');
			if(!file_exists($fpath.$name)){
				mkdir($fpath.$name,0777);
			}
			$sortpos = $_POST['sortpos'] ? $_POST['sortpos'] : 0;
			$enable = $_POST['enable'] ?  1 : 0;
			$data = array(
				'name' => $name,
				'title' => $_POST['title'],
				'sortpos' => $sortpos,
				'description' =>$_POST['description'],
				'alldesc' => $_POST['alldesc'],
				'enable' => $enable,
				'optime' => time(),
				'downloadurl' => $_POST['downloadurl'],
				'sourceurl' => $_POST['sourceurl'],
				'google_downloadurl' => $_POST['google_downloadurl'],
                'app_store_downloadurl' => $_POST['app_store_downloadurl'],
                'downloadurl_ipa' => $_POST['downloadurl_ipa'],
                'official' => $_POST['official'],
                'sourceurl_ipa' => $_POST['sourceurl_ipa']
			);
	        $flag = mobileModel::addGame($data);
			if($flag){
	        	ShowMsg('添加成功', '?m=mobile&a=editgame&name='.$name);
			}
		}
	}
	
	/**
	 * 
	 * 编辑游戏
	 */
	public function editgame(){
		$this->checkLogin();
		if($_GET['name']){
			$gname = $_GET['name'];
			if($_POST){
				$name = $_POST['name'];
				$fpath = C('MOBILE_PATH');
				if(!file_exists($fpath.$name)){
					ShowMsg('SVN无此游戏文件', '?m=mobile&a=listgame');
				}
				$sortpos = $_POST['sortpos'] ? $_POST['sortpos'] : 0;
				$enable = $_POST['enable'] ?  1 : 0;
				$data = array(
					'name' => $name,
					'title' => $_POST['title'],
					'sortpos' => $sortpos,
					'description' =>$_POST['description'],
					'alldesc' => $_POST['alldesc'],
					'enable' => $enable,
					'optime' => time(),
					'downloadurl' => $_POST['downloadurl'],
					'sourceurl' => $_POST['sourceurl'],
					'google_downloadurl' => $_POST['google_downloadurl'],
					'app_store_downloadurl' => $_POST['app_store_downloadurl'],
					'downloadurl_ipa' => $_POST['downloadurl_ipa'],
					'official' => $_POST['official'],
					'sourceurl_ipa' => $_POST['sourceurl_ipa']
				);
		        $flag = mobileModel::editGame($gname, $data);
				if($flag){
					$gname = $name;
		        	ShowMsg('编辑成功', '?m=mobile&a=editgame&name='.$gname);
				}
			}
			$game = mobileModel::getGameByName($gname);
			$versions = mobileModel::getVersions($gname);
			$this->assign('game', $game);
			$this->assign('versions', $versions);
		}else{
			ShowMsg('游戏不存在', '?m=mobile&a=listgame');
		}
	}
	
	/**
	 * 
	 * 添加游戏版本
	 */
	public function addversion(){
		$this->checkLogin();
		if($_GET['game']){
			$game = $_GET['game'];
			if($_POST){
				$enable = $_POST['enable'] ?  1 : 0;
				$version = $_POST['version'];
				$type = $_POST['type'];
				$size = $_POST['size'];
				$data = array(
					'game' => $game,
					'type' => $type,
					'version' => $version,
					'optime' => time(),
					'size' => $size,
					'enable' => $enable,
					'required' => $_POST['required'],
				);
				$flag = mobileModel::addVersion($data);
				if($flag){
					ShowMsg('添加成功', '?m=mobile&a=editgame&name='.$game);
				}
			}
		$this->assign('action','新增');
		$this->display('mobile/version.html');
		}else{
			ShowMsg('游戏不存在', '?m=mobile&a=listgame');
		}
		exit;
	}
	
	/**
	 * 
	 * 编辑游戏版本
	 */
	public function editversion(){
		$this->checkLogin();
		if($_GET['id'] && $_GET['game']){
			$id = $_GET['id'];
			$game = $_GET['game'];
			if($_POST){
				$enable = $_POST['enable'] ?  1 : 0;
				$version = $_POST['version'];
				$type = $_POST['type'];
				$data = array(
					'game' => $game,
					'type' => $type,
					'version' => $version,
					'optime' => time(),
					'enable' => $enable,
					'size' => $_POST['size'],
					'required' => $_POST['required'],
				);
				$flag = mobileModel::editVersion($id, $data);
				if($flag){
					ShowMsg('編輯成功', '?m=mobile&a=editgame&name='.$game);
				}
			}else{
				$version = mobileModel::getVersionById($id);
				$this->assign('version',$version);
			}
			$this->assign('action','編輯');
			$this->display('mobile/version.html');
		}else{
			ShowMsg('游戏或版本不存在', '?m=mobile&a=listgame');
		}
		exit;
	}
	
	public function uploadimg(){
		$this->checkLogin();
		$games = mobileModel::getGames();
		$position = array(
					'alogo'=>'首頁logo',
					'blogo'=>'詳細頁logo',
					'llogo'=>'引導logo',
					'screenshot1'=>'遊戲截圖1',
					'screenshot2'=>'遊戲截圖2',
					'screenshot3'=>'遊戲截圖3'
		);
		$homepageInfo = mobileModel::getHomepage();
		$homeinfo = array();
		if($homepageInfo){
			foreach ($homepageInfo as $val){
				$homeinfo[$val['position']]['link'] = $val['imglink'];
				$homeinfo[$val['position']]['desc'] = $val['imgdesc'];
			}
		}
		$this->assign('games', $games);
		$this->assign('position', $position);
		$this->assign('homeinfo', $homeinfo);
	}
	
	public function editimg(){
		$this->checkLogin();
		if(empty($_GET['p'])||empty($_GET['n'])){
			ShowMsg('參數有誤', '-1');
		}
		$p = $_GET['p'];
		$n = $_GET['n'];
		$filepath  = C('MOBILE_PATH');
		if($_FILES['img']){
			load('uploadfile');
			$path = $filepath . $p .'/' .$n . '.png';
			$filetypes = array('png');
 			$upload = new uploadfile($_FILES['img'], $filepath . $p, 1000000, $filetypes);
 			$num = $upload->upload($n);
 			if($p!='homepage'){
 				if($num<1){
 					ShowMsg('上传圖片失败','-1');
 				}else{
 					ShowMsg('上传圖片成功','?m=mobile&a=uploadimg&p='.$p);
 				}	
 			}
		}
		if($p=='homepage'){
			if($_POST){
				$data = array(
					'imglink' => $_POST['imglink'],
					'imgdesc' => $_POST['imgdesc'],
					'optime'  => time(),
				);
				if(mobileModel::setHomepageByPosition($data, $n)){
					ShowMsg('修改成功','-1');
				}
			}
			$homepage = mobileModel::getHomepageByPosition($n);
			$this->assign('homepage', $homepage);
			$this->display('mobile/edithomepage.html');
			exit;
		}
	}
	
	/**
	 * SDK广告
	 */
	public function sdkAlarmClock() {
		$operation_list = array('index', 'add', 'edit', 'save', 'del');
		$operation = in_array($_REQUEST['operation'], $operation_list) ? $_REQUEST['operation'] : 'index';
		
		load('model.sdkAlarmClock');
		//私有操作
		$operation_method = '_' . $operation . 'sdkAlarmClock';
		if(method_exists($this, $operation_method)) {
			$this->{$operation_method}( array_merge($_REQUEST, $_FILES) );
		} else {
			$adv_list = sdkAlarmClock::getList();
			$this->assign('adv_list', $adv_list);
		}
		$this->assign('operation', $operation);
	}
	
	private function _addSdkAlarmClock() {
		
	}
	
	/**
	 * 增加SDK广告
	 * 
	 * @param array $data
	 */
	private function _editSdkAlarmClock($data) {
		if(empty($data['game'])) {
			showMsg('數據不存在', '/?m=mobile&a=sdkAlarmClock');
		}
		$adv = sdkAlarmClock::get($data['game']);
		$this->assign('adv', $adv);
	}
	
	/**
	 * 保存SDK广告
	 * 
	 * @param array $data
	 */
	private function _saveSdkAlarmClock($data) {
		//新增广告的游戏不能和现存的冲突
		if(isset($data['isnew'])) {
			if($data['game'] && sdkAlarmClock::get($data['game'])) {
				ShowMsg('游戏已存在', '-1');
			}
		}
		//如果存在圖片附件
		if($data['image']['error'][0] === 0) {
			load('uploadfile');
			$file_types = array('png', 'jpg', 'jpeg', 'gif');
			$image_dir = '/adv/';
			$file_path = C('SDK_PATH') . $image_dir;
			$upload = new uploadfile($data['image'], $file_path, 1000000, $file_types);
			$time = time();
			$file_name = $time . preg_replace('/^.+(?=\.\w+$)/', '', $data['image']['name'][0]);
			$num = $upload->upload($time);
			if($num < 1) {
				ShowMsg('上传圖片失败', '-1');
			}
			$data['image'] =  C('SDK_DIR') . $image_dir . $file_name;
		} else {
			if(isset($data['isnew'])) {
				$data['image'] = '';
			} else {
				//编辑的话，没有新图片就用旧图片了
				$adv = sdkAlarmClock::get($data['game']);
				$data['image'] = $adv['image'] ? $adv['image'] : '';
			}
		}
		sdkAlarmClock::save($data);
		ShowMsg('保存成功', '/?m=mobile&a=sdkAlarmClock');
	}
	
	/**
	 * 删除SDK广告
	 * 
	 * @param array $data
	 */
	private function _delSdkAlarmClock($data) {
		if(empty($data['game'])) {
			showMsg('數據不存在');
		}
		sdkAlarmClock::delete($data['game']);
		showMsg('刪除成功', '/?m=mobile&a=sdkAlarmClock');
	}
	
	
	//吧台游戏管理
	public function litGame() {
		$this->checkLogin();	
		
		$page = $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;		             
        $this->assign('offset', $row);	                		
		load('model.litGame');
		$tb = 'ms_little_game';
		$row = 3; 
		$games = litGame::getAll($tb, $page, $row);
		$total = litGame::total($tb);
		
		$jsonStr = litGame::createJson($games);
		
		$this->assign('page', $page);
		$this->assign('offset', $row);
		$this->assign('total', $total);
		$this->assign('game_list', $games);	
		$this->assign('jsonStr', $jsonStr);
		
		if($_POST){					
			$sel = litGame::deleteSelect($_POST['del_id']);	
			if($sel){		
			 	ShowMsg('删除成功', '?m=mobile&a=litgame');		
			}	
		}
	}
	
	//添加吧台游戏
	public function addLitGame() {
		$this->checkLogin();
		
		if($_POST){
			if($_POST['game'] !== '' && $_POST['title'] !== '') {
				$addtime = time();												
				load('uploadfile');				
				$filepath  = C('KEFU_UPLOAD_DIR');
				$filetypes = array('png', 'jpg', 'jpeg', 'gif');
	 			$upload = new uploadfile($_FILES['image'], $filepath, 1000000, $filetypes);
				$time = date('YmdHms', $addtime);
				$file_name = $time.preg_replace('/^.+(?=\.\w+$)/', '', $_FILES['image']['name'][0]);			
	 			$upload->upload($time);	
	
	 			$imgUrl = C('KEFU_UPLOAD_DIR').$file_name;		
				$data = array(
					'game' => $_POST['game'],
					'alise' => $_POST['alise'],
					'title' =>$_POST['title'],
					'content' => $_POST['content'],	
					'google_down' => $_POST['google_downloadurl'],	
					'image' => $imgUrl,
					'addtime' => $addtime          
				);
				
				load('model.litGame');
		        $flag = litGame::addlitgame($data);
				if($flag) {
		        	ShowMsg('添加成功', '?m=mobile&a=litgame');
				}
			}else {
					ShowMsg('信息不能为空', '?m=mobile&a=addlitgame');
		       }
		}
	}
	
	//吧台游戏修改
	public function editLitGame(){
		$this->checkLogin();
		if($_GET){
			$game = $_GET['game'];
			$lid = $_GET['lid'];
			load('model.litGame');
			$onegame = litGame::getOne($lid);
			$this->assign('onegame', $onegame[0]);
			
			if($_POST){
			$addtime = time();
			$data = array(
				'game' => $_POST['game'],
				'alise' => $_POST['alise'],
				'title' =>$_POST['title'],
				'content' => $_POST['content'],			
				'image' => $_POST['imgurl'],
				'google_down' => $_POST['google_downloadurl'],
				'addtime' => $addtime          
			);
			
				load('model.litGame');
		        $flag = litGame::editlitgame($lid, $data);
				if($flag){
		        	ShowMsg('修改成功', '?m=mobile&a=litgame');
				}
			}
			
		}else{
			ShowMsg('游戏不存在', '?m=mobile&a=listgame');
		}
	}
	
	/**
	 * 删除吧台游戏
	 * 
	 * @param array $data
	 */
	public function deleteLitGame() {
		if($_GET) {
			$lid = $_GET['lid'];
			load('model.litGame');
			$is_OK = litGame::deleteGame($lid);
			if($is_OK) {
				showMsg('刪除成功', '/?m=mobile&a=litgame');
			}
		}		
	}
	
	//创建xml文件
	/*public function createXml() {
		
		load('model.litGame');
		$is_ok = litGame::addFile();
		if($is_ok) {
				showMsg('已生成xml文件', '/?m=mobile&a=litgame');
			}
	}*/
	
	
	
}