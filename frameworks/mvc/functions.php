<?php

function require_cache($filename) {
	static $files = array();
	$filename = realpath($filename);
	if (!isset($files[$filename])) {
		if (file_exists($filename)) {
			require $filename;
			$files[$filename] = true;
		}
		else {
			$files[$filename] = false;
		}
	}
	return $files[$filename];
}

/**
 * 自动加载类
 *
 * 支持多级目录加载, 例如加载Smarty可以使用: load('plugins.Smarty');
 * 
 * 追加跨應用調用功能，只要在參數前加上'@應用名稱'即可
 * 例：在mobile應用中調用flash的類 load('@flash.model.flashgame');
 * @lib 2013/03/20
 * 
 *
 * @param string $filepath
 * @return boolean
 */
function load($class_name) {

	$token = explode('.', $class_name);
	//看看是否是調用其它APP
	$call_app = (substr($class_name, 0, 1) === '@');
	if($call_app) {
		$app = substr( array_shift($token), 1 );
	}
	//沒有指定某個應用的時候，我們將當前調用的類看作為 '絕對于應用'的類
	//也就是說，如果當前類是處于某個應用之下，那么這個類必然屬於這個應用
	//這是理所當然的，但因為跨應用調用的時候，類中某些方法在調用load的時候沒有指明'我加載的類也是屬於這個應用的'，這時就需要幫助它們找到所屬的應用才行
	else {
		//追溯來源路徑
		$debug_bacetrace = debug_backtrace();
		$trace = getTraceByFiltrator($debug_bacetrace, 0);
		$trace_app_path = $trace['file'];
		//getInstance引用了load，但不屬于任何應用，針對它做特殊處理
		if($debug_bacetrace[1]['function'] === 'getInstance') {
			$trace = getTraceByFiltrator($debug_bacetrace, 1);
			$trace_app_path = $trace['file'];
		}

		$app = APP_NAME;
		//計算出對應的APP
		if(stripos($trace_app_path, APP_LIST_PATH) !== false) {
			$trace_app_path = str_replace(APP_LIST_PATH, '', $trace_app_path);
			$match = array();
			preg_match('/^\w+/', $trace_app_path, $match);
			$app = $match[0];
		}
	}

	// 实例化模块
	if (($token[0] == 'model') && isset($token[1])) {
		unset($token[0]);
		$filepath = implode(DS, $token) . '.php';

		//根據不同的app調用相對應的模型
		$app_model_dir = APP_LIST_PATH . $app . DS . 'models' . DS;
		if (file_exists($app_model_dir . $filepath)) {
			$filepath = $app_model_dir . $filepath;
		}
		//最后需要在全局模型里看看有沒有
		elseif (file_exists(MODEL_PATH . $filepath)) {
			$filepath = MODEL_PATH . $filepath;
		}
		else {
			return false;
		}
	}
	else {
		$filepath = LIB_PATH . implode(DS, $token) . '.php';
	}

	// 包含文件
	if (require_cache($filepath)) {
		return array_pop($token);
	}
	else {
		return false;
	}
}

/**
 * 因为call_user_func等内部调用的问题导致backtrace一些file参数没有值
 * 该函数的作用是如果当前backtrace的值为空，则取下一个
 * 
 * @param array $debugBacktrace
 * @param int $index
 * @return boolean
 */
function getTraceByFiltrator( array $debugBacktrace, $index ) {
	if( ! isset( $debugBacktrace[ $index ] ) ) {
		return false;
	}
	$_index = $index;
	while( ! isset( $debugBacktrace[ $_index ]['file'] ) ) {
		if( ! isset( $debugBacktrace[ ++$_index ] ) ) {
			return false;
		}
	}
	return $debugBacktrace[ $_index ];
}

/**
 * 实例化对象
 *
 * 支持多级目录加载, 例如加载Smarty可以使用: getInstance('plugins.Smarty');
 * 
 * 跨應用調用
 * 只要在參數前加上'@應用名稱'即可
 * 例：在mobile應用中調用flash的類 getInstance('@flash.model.flashgame');
 *
 * @param string $class_name
 */
function getInstance($class_name) {
	// 判断是否正常加载类文件, 并且类名是否正确
	if ($class = load($class_name)) {
		if (class_exists($class)) {
			return new $class;
		}
	}
	throw new Exception('无法实例化' . $class_name . '对象, 文件不存在或者类名字错误.');
}

/**
 * 简单实现变量配置函数
 *
 */
function C($name, $value=null) {
	static $vars = array();
	if (is_array($name)) {
		foreach ($name as $key=>$value) {
			C($key, $value);
		}
	}
	else {
		if (is_null($value)) {
			return $vars[$name];
		}
		else {
			$vars[$name] = $value;
		}
	}
}

/**
 * 加载配置文件
 * 
 * @param string $filename
 * @param string $app
 * @return array
 */
function loadC($filename, $app=null) {
	$filename = $filename ? $filename : 'config.inc.php';
	$app = is_null($app) ? APP_NAME : $app;
	$filepath = APP_LIST_PATH . $app . DS . $filename;
	if(file_exists($filepath)) {
		return (require $filepath);
	}
	return array();
}

/**
 *  修复浏览器XSS hack的函数
 *
 * @param     string   $val  需要处理的内容
 * @return    string
 */
function RemoveXSS($val) {
	$val = mysql_real_escape_string($val);

	$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	for ($i = 0; $i < strlen($search); $i++) {
		$val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	}

	$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	$ra = array_merge($ra1, $ra2);

	$found = true;
	while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[xX]0{0,8}([9ab]);)';
					$pattern .= '|';
					$pattern .= '|(&#0{0,8}([9|10|13]);)';
					$pattern .= ')*';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
			$val = preg_replace($pattern, $replacement, $val);
			if ($val_before == $val) {
				$found = false;
			}
		}
	}
	return $val;
}

/**
 *  获取用户真实地址
 *
 * @return    string  返回用户ip
 */
function GetIP()
{
	static $realip = NULL;
	if ($realip !== NULL)
	{
		return $realip;
	}
	if (isset($_SERVER))
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			/* 取X-Forwarded-For中第x个非unknown的有效IP字符? */
			foreach ($arr as $ip)
			{
				$ip = trim($ip);
				if ($ip != 'unknown')
				{
					$realip = $ip;
					break;
				}
			}
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else
		{
			if (isset($_SERVER['REMOTE_ADDR']))
			{
				$realip = $_SERVER['REMOTE_ADDR'];
			}
			else
			{
				$realip = '0.0.0.0';
			}
		}
	}
	else
	{
		if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_CLIENT_IP'))
		{
			$realip = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			$realip = getenv('REMOTE_ADDR');
		}
	}
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	return $realip;
}

/**
 * 分页
 * @$totle(总页)
 * @$dosplay(每页显示条数,case=20) 
 * @$url(默认的URL)
*/
function pageft($totle, $displaypg = 20, $model = '', $url = '') {
	global $page, $firstcount, $pagenav, $_SERVER;
	$GLOBALS["displaypg"] = $displaypg;
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	if(!$page) $page = 1;
	if(!$url){
		$url = $_SERVER["REQUEST_URI"];
	}
	//------------URL分析--------------
	$parse_url = parse_url($url);
	$url_query = isset($parse_url["query"]) ? $parse_url['query'] : null;
	if($url_query){
		$url_query = preg_replace("/[&]?page = $page/i", "", $url_query);
		$url = str_replace($parse_url["query"], $url_query, $url);
		if($url_query) $url .= "&page"; else $url .= "page";
	}else {
		$url .= "?page";
	}
	//页码计算：
	$lastpg	= ceil($totle / $displaypg);
	$page 	= min($lastpg, $page);
	$prepg 	= $page - 1;
	$nextpg = ($page == $lastpg ? 0 : $page+1);
	$firstcount = ($page-1) * $displaypg;

	$start_date = !empty($_REQUEST['start_date']) ? strtotime($_REQUEST['start_date']) : 0;
	$end_date 	= !empty($_REQUEST['end_date']) ? strtotime($_REQUEST['end_date']) : 0;
	//
	$alias 		= !empty($_REQUEST['alias']) ? trim($_REQUEST['alias']) : '';
	$money 		= intval($_REQUEST['money']) ? intval($_REQUEST['money']) : '';
	$level 		= intval($_REQUEST['level']) ? intval($_REQUEST['level']) : '';
	//
	$ordertype	= $_REQUEST['type'];
	$game 		= intval($_REQUEST['game'])  ? intval($_REQUEST['game']) : '';
	$server		= intval($_REQUEST['server'])? intval($_REQUEST['server']) : '';
	//
	$user 		= !empty($_REQUEST['user'])  ? trim($_REQUEST['user']) : '';
	$state 		= (isset($_REQUEST['state']) && $_REQUEST['state'] != 'NULL')    ? $_REQUEST['state'] : '1';
	$url_cont = '';
	if ($_REQUEST['a']) {
		if ($start_date) $url_cont .= '&start_date=' . $start_date;
		if ($end_date) $url_cont .= '&end_date=' . $end_date;
		if ($model == '?m=index&a=users&') {
			if ($alias) $url_cont .= '&alias=' . $alias;
			if ($money) $url_cont .= '&money=' . $money;
			if ($level) $url_cont .= '&level=' . $level;
		}elseif ($model == '?m=index&a=trans&'){
			if ($ordertype == 'online') {
				$url_cont .= '&type=online';
			}
			elseif ($ordertype == 'transfer') {
				$url_cont .= '&type=transfer';
			}
			if ($game) $url_cont .= '&game=' . $game;
			if ($server) $url_cont .= '&server=' . $server;
		}elseif ($model == '?m=index&a=payment&'){
			if ($ordertype) $url_cont .= '&type=' . $ordertype;
			if ($game) $url_cont .= '&game=' . $game;
			if ($server) $url_cont .= '&server=' . $server;
			$url_cont .= '&state=' . $state;
		}
		if ($user) $url_cont .= '&user=' . $user;
	}

	//开始分页导航条代码：
	$pagenav = "显示第 <B>" . ($totle ? ($firstcount+1) : 0) . "</B>-<B>" . min($firstcount+$displaypg, $totle) . "</B> 条记录，共 $totle 条记录";

	//如果只有一页则跳出函数：
	if($lastpg <= 1) return false;
	$pagenav 				.= " <a href='" . $model . "page=1" . $url_cont . "'>首页</a> ";
	if($prepg) $pagenav 	.= " <a href='" . $model . "page=$prepg" . $url_cont . "'>上一页</a> "; else $pagenav .= " 上一页 ";
	if($nextpg) $pagenav 	.= " <a href='" . $model . "page=$nextpg" . $url_cont . "'>下一页</a> "; else $pagenav .= " 下一页 ";
	$pagenav 				.= " <a href='" . $model . "page=$lastpg" . $url_cont . "'>尾页</a> ";

	//下拉跳转列表：
	$pagenav .= "　到第 <select name='topage' size='1' onchange='window.location=\"" . $model . "page=\"+this.value+\"" . $url_cont . "\"'>\n";
	for($i = 1;$i <= $lastpg;$i++){
		if($i == $page) $pagenav .= "<option value='$i' selected>$i</option>\n";
		else $pagenav .= "<option value='$i'>$i</option>\n";
	}
	$pagenav .= "</select> 页, 共 $lastpg 页";
	return $pagenav;
}


/**
 * 
 * 对象转为数组
 * @param object $object
 * @throws Exception
 */
function object2array($object){
	$return = NULL;
	if(is_array($object))
	{
		foreach($object as $key => $value)
		$return[$key] = object2array($value);
	}
	else
	{
		if(!is_object($object)) return $object;
		$var = get_object_vars($object);
		if(isset($var)&&$var)
		{
			foreach($var as $key => $value){
				if(!isset($var))	$return[$key] = '';
				else
				$return[$key] = ($key && !isset($var)) ? NULL : object2array($value);
			}
		}
		else{
			return '';
		}
	}

	return $return;
}


/**
 * xml转数组格式
 * @param string $str xml数据，接收：$str = file_get_contents("php://input");
 * @return array
 */
function xmlToArray($str){
	// 判断格式
	$xml_parser = xml_parser_create();
	if(!xml_parse($xml_parser, $str, true)){
		xml_parser_free($xml_parser); // 释放指定的 XML 解析器
		return $str;
	}

	$result = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
	$result = json_encode($result);
	$result = json_decode($result, true);
	return $result;
}


function xmlprase($str){
	return simplexml_load_string($str);
}

/**
 * 导出 excel 报表函数
*/
function excel_export($filename, $header, $rows) {
	$content  = '<tr>';
	foreach ($header as $title) {
		$content .= '<td class=xl2216681 nowrap>' . $title . '</td>';
	}
	$content .= '</tr>' . "\n";

	foreach ($rows as $row) {
		$content .= '<tr>';
		foreach ($header as $title) {
			$content .= '<td class=xl2216681 nowrap>' . array_shift($row) . '</td>';
		}
		$content .= '</tr>' . "\n";
	}

	$html = <<<EOT
	<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<style id="Classeur1_16681_Styles"></style>
	</head>
	<body>
		<div id="Classeur1_16681" align=center x:publishsource="Excel">
		<table x:str border=0 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">
			{$content}
		</table>
		</div>
	</body>
	</html>
EOT;
	//发送Http Header信息 开始下载,解決中文亂碼問題
	header('Content-type: application/vnd.ms-excel;');
	$ua = $_SERVER["HTTP_USER_AGENT"];
	$encoded_filename = urlencode($filename);
	$encoded_filename = str_replace("+", "%20", $encoded_filename);
	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	} else if (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '.xls"');
	} else {
		header('Content-Disposition: attachment; filename="'. $filename . '.xls"');
	}
	header('Pragma: no-cache');
	die($html);
	exit;
}

/**
 * 输出excel报表头部
 * 
 * @param string $filename 报表文件名
 * @param array $header 标题行
 */
function excelPrintHeader($filename, array $header) {
	//发送Http Header信息 开始下载,解決中文亂碼問題
	header('Content-type: application/vnd.ms-excel;');
	$ua = $_SERVER["HTTP_USER_AGENT"];
	$encoded_filename = urlencode($filename);
	$encoded_filename = str_replace("+", "%20", $encoded_filename);
	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	} else if (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '.xls"');
	} else {
		header('Content-Disposition: attachment; filename="'. $filename . '.xls"');
	}
	header('Pragma: no-cache');
	echo <<<EOT
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html>
    <head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <style id="Classeur1_16681_Styles"></style>
    </head>
    <body>
        <div id="Classeur1_16681" align=center x:publishsource="Excel">
        <table x:str border=0 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">
EOT;
	excelPrintRow($header);
}

/**
 * 输出excel报表底部（结束）
 */
function excelPrintFooter() {
	echo <<<EOT
            </table>
            </div>
        </body>
        </html>
EOT;
	exit;
}

/**
 * 输出一行excel数据
 */
function excelPrintRow(array $row) {
	$content  = '<tr>';
	foreach ($row as $field) {
		$content .= '<td class=xl2216681 nowrap>' . $field . '</td>';
	}
	$content .= '</tr>' . "\n";
	echo $content;
}

/**
 * 根据字段名来过滤数据
 * 只有存在于$keys的字段，在$data中才会被保留
 * 
 * @param array $keys
 * @param array $data 多维数组
 * @return array $data
 */
function filterValueByKeys(array $keys, array $data) {
	$redata = array();
	foreach($data as $index => $row) {
		$quote_row = &$redata[$index];
		foreach($row as $key => $value) {
			if(in_array($key, $keys)) {
				$quote_row[$key] = $value;
			}
		}
	}
	return $redata;
}

function getRandNum($length = 5) {
	$code_list = "0123456789";
	$code_length = strlen ( $code_list ) - 1;
	$rand_code = "";
	for($i = 0; $i < $length; $i ++) {
		$rand_num = rand ( 0, $code_length );
		$rand_code .= $code_list [$rand_num];
	}
	return $rand_code;
}

function getLastday($date) {
	$firstday = date('Y-m-01', strtotime($date));
	$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
	return date('d', strtotime($lastday));
}

function is_utf8($string) {
	return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}

/**
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 *
 * @access      public
 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
 *
 * @return      bool
 */
function make_dir($folder)
{
	$reval = false;

	if (!file_exists($folder))
	{
		/* 如果目录不存在则尝试创建该目录 */
		@umask(0);

		/* 将目录路径拆分成数组 */
		preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

		/* 如果第一个字符为/则当作物理路径处理 */
		$base = ($atmp[0][0] == '/') ? '/' : '';

		/* 遍历包含路径信息的数组 */
		foreach ($atmp[1] AS $val)
		{
			if ('' != $val)
			{
				$base .= $val;

				if ('..' == $val || '.' == $val)
				{
					/* 如果目录为.或者..则直接补/继续下一个循环 */
					$base .= '/';

					continue;
				}
			}
			else
			{
				continue;
			}

			$base .= '/';

			if (!file_exists($base))
			{
				/* 尝试创建目录，如果创建失败则继续循环 */
				if (@mkdir(rtrim($base, '/'), 0777))
				{
					@chmod($base, 0777);
					$reval = true;
				}
			}
		}
	}
	else
	{
		/* 路径已经存在。返回该路径是不是一个目录 */
		$reval = is_dir($folder);
	}

	clearstatcache();

	return $reval;
}

/**
 * 获得当前格林威治时间的时间戳
 *
 * @return  integer
 */
function gmtime()
{
	return (time() - date('Z'));
}

/**
 * 检查文件类型
 *
 * @access      public
 * @param       string      filename            文件名
 * @param       string      realname            真实文件名
 * @param       string      limit_ext_types     允许的文件类型
 * @return      string
 */
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
	if ($realname)
	{
		$extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
	}
	else
	{
		$extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
	}

	if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
	{
		return '';
	}

	$str = $format = '';

	$file = @fopen($filename, 'rb');
	if ($file)
	{
		$str = @fread($file, 0x400); // 读取前 1024 个字节
		@fclose($file);
	}
	else
	{
		if (stristr($filename, ROOT_PATH) === false)
		{
			if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' ||
			$extname == 'xls' || $extname == 'txt'  || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' ||
			$extname == 'pdf' || $extname == 'rm'   || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' ||
			$extname == 'swf' || $extname == 'chm'  || $extname == 'sql' || $extname == 'cert')
			{
				$format = $extname;
			}
		}
		else
		{
			return '';
		}
	}

	if ($format == '' && strlen($str) >= 2 )
	{
		if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
		{
			$format = 'mid';
		}
		elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
		{
			$format = 'wav';
		}
		elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
		{
			$format = 'jpg';
		}
		elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
		{
			$format = 'gif';
		}
		elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
		{
			$format = 'png';
		}
		elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
		{
			$format = 'bmp';
		}
		elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
		{
			$format = 'swf';
		}
		elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
		{   // D0CF11E == DOCFILE == Microsoft Office Document
			if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
			{
				$format = 'doc';
			}
			elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
			{
				$format = 'xls';
			} elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
			{
				$format = 'ppt';
			}
		} elseif (substr($str ,0, 4) == "PK\x03\x04")
		{
			$format = 'zip';
		} elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
		{
			$format = 'rar';
		} elseif (substr($str ,0, 4) == "\x25PDF")
		{
			$format = 'pdf';
		} elseif (substr($str ,0, 3) == "\x30\x82\x0A")
		{
			$format = 'cert';
		} elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
		{
			$format = 'chm';
		} elseif (substr($str ,0, 4) == "\x2ERMF")
		{
			$format = 'rm';
		} elseif ($extname == 'sql')
		{
			$format = 'sql';
		} elseif ($extname == 'txt')
		{
			$format = 'txt';
		}
	}

	if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
	{
		$format = '';
	}

	return $format;
}

/**
 * 将上传文件转移到指定位置
 *
 * @param string $file_name
 * @param string $target_name
 * @return blog
 */
function move_upload_file($file_name, $target_name = '')
{
	if (function_exists("move_uploaded_file"))
	{
		if (move_uploaded_file($file_name, $target_name))
		{
			@chmod($target_name,0755);
			return true;
		}
		else if (copy($file_name, $target_name))
		{
			@chmod($target_name,0755);
			return true;
		}
	}
	elseif (copy($file_name, $target_name))
	{
		@chmod($target_name,0755);
		return true;
	}
	return false;
}


/**
 *  中文截取，单字节截取模式
 *
 * @access    public
 * @param     string  $str  需要截取的字符串
 * @param     int  $slen  截取的长度
 * @param     int  $startdd  开始标记处
 * @return    string
 */
if ( ! function_exists('cn_substr'))
{
	function cn_substr($str, $slen, $startdd=0)
	{
		global $cfg_soft_lang;
		if($cfg_soft_lang=='utf-8')
		{
			return cn_substr_utf8($str, $slen, $startdd);
		}
		$restr = '';
		$c = '';
		$str_len = strlen($str);
		if($str_len < $startdd+1)
		{
			return '';
		}
		if($str_len < $startdd + $slen || $slen==0)
		{
			$slen = $str_len - $startdd;
		}
		$enddd = $startdd + $slen - 1;
		for($i=0;$i<$str_len;$i++)
		{
			if($startdd==0)
			{
				$restr .= $c;
			}
			else if($i > $startdd)
			{
				$restr .= $c;
			}

			if(ord($str[$i])>0x80)
			{
				if($str_len>$i+1)
				{
					$c = $str[$i].$str[$i+1];
				}
				$i++;
			}
			else
			{
				$c = $str[$i];
			}

			if($i >= $enddd)
			{
				if(strlen($restr)+strlen($c)>$slen)
				{
					break;
				}
				else
				{
					$restr .= $c;
					break;
				}
			}
		}
		return $restr;
	}
}


function ShowMsg($msg, $gourl, $onlymsg = 0, $limittime = 0) {
	if(is_mobile()) {
		ShowMsgForMobile($msg, $gourl, $onlymsg, $limittime);
		exit;
	}
	if (empty($GLOBALS['cfg_basehost']))
	$GLOBALS['cfg_basehost'] = '/';

	//行销跟踪
	$script_str = '
		<!-- Google Code for &#20877;&#33829;&#38144;&#20195;&#30721; -->
		<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 988981687;
		var google_conversion_label = "O3PoCInQxQQQt9PK1wM";
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
		/* ]]> */
		</script>
		<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/988981687/?value=0&amp;label=O3PoCInQxQQQt9PK1wM&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript>
	';

	//转换跟踪
	if((strpos($msg, "註冊成功") === false) && (strpos($msg, "授權成功") === false)){
		$script_str .= '';
	}else{
		$script_str .= '
			<!-- Google Code for &#35387;&#20874;&#25104;&#21151; Conversion Page -->
			<script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 988981687;
			var google_conversion_language = "en";
			var google_conversion_format = "2";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "y8mrCJnOxQQQt9PK1wM";
			var google_conversion_value = 0;
			/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/988981687/?value=0&amp;label=y8mrCJnOxQQQt9PK1wM&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>		
		';
	}

	$htmlhead = "<html>\r\n<head>\r\n<title>信息提示</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
	$htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>" . (isset($GLOBALS['ucsynlogin']) ? $GLOBALS['ucsynlogin'] : '') . "\r\n<center>\r\n<script>\r\n";
	$htmlfoot = "</script>" . $script_str . "\r\n</center>\r\n</body>\r\n</html>\r\n";

	$litime = ($limittime == 0 ? 1000 : $limittime);
	$func = '';

	if ($gourl == '-1') {
		if ($limittime == 0)
		$litime = 3000;
		$gourl = "javascript:history.go(-1);";
	}

	if ($gourl == '' || $onlymsg == 1) {
		$msg = "<script>alert(\"" . str_replace("\"", "“", $msg) . "\");</script>";
	} else {
		//当网址为:close::objname 时, 关闭父框架的id=objname元素
		if (preg_match('/close::/', $gourl)) {
			$tgobj = trim(preg_replace('/close::/', '', $gourl));
			$gourl = 'javascript:;';
			$func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
		}

		$func .= "      var pgo=0;
  function JumpUrl(){
    if(pgo==0){ location='$gourl'; pgo=1; }
  }\r\n";
		$rmsg = $func;
		$rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #D8E4EE;background:#ffffff'>";
		$rmsg .= "<div style='padding:6px;font-size:12px;border-bottom:1px solid #D8E4EE;background:#D8E4EE url({$GLOBALS['cfg_basehost']}style/blue/images/wbg.gif)';'><b>信息提示</b></div>\");\r\n";
		$rmsg .= "document.write(\"<div style='height:130px;font-size:14px;background:#ffffff'><br />\");\r\n";
		$rmsg .= "document.write(\"" . str_replace("\"", "“", $msg) . "\");\r\n";
		$rmsg .= "document.write(\"";

		if ($onlymsg == 0) {
			if ($gourl != 'javascript:;' && $gourl != '') {
				$rmsg .= "<br /><a href='{$gourl}'>如果您的瀏覽器沒有返回，请点击这里...</a>";
				$rmsg .= "<br/></div>\");\r\n";
				$rmsg .= "setTimeout('JumpUrl()',$litime);";
			} else {
				$rmsg .= "<br/></div>\");\r\n";
			}
		} else {
			$rmsg .= "<br/><br/></div>\");\r\n";
		}
		$msg = $htmlhead . $rmsg . $htmlfoot;
	}
	echo $msg;
	exit;
}

function ShowMsgForMobile($msg, $gourl, $onlymsg = 0, $limittime = 0) {
	if (empty($GLOBALS['cfg_basehost']))
	$GLOBALS['cfg_basehost'] = '/';

	$htmlhead = "<html>\r\n<head>\r\n<title>信息提示</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
	$htmlhead .= "<base target='_self'/>\r\n<style>
html {-webkit-text-size-adjust:none; }
body{ background:#fff; padding:0; margin:0;font-size: 62.5%;/*10 ÷ 16 × 100% = 62.5%*/;color:#333}
p{ padding:0; margin:0;}
.jumpbox{ width:80%; margin:10% auto; height:auto; overflow:hidden;}
.jumpbox .jumptitle{ width:100%;display:block; color:#000; font-weight:bold;font-size:0.8rem;/*1.4 × 10px = 14px */; font-weight:bold ; border-bottom:1px solid #dde2e5; display: none;
background:#eff0f2; background:-moz-linear-gradient(top, #ffffff, #eff0f2);background:-webkit-gradient(linear, 0 0, 0 bottom, from(#ffffff), to(#eff0f2)); text-align:center; }
.jumpbox p{ text-align:center; line-height:25px;}
.jumpbox p a{ color:#4d1a77}
</style></head>\r\n<body>" . (isset($GLOBALS['ucsynlogin']) ? $GLOBALS['ucsynlogin'] : '') . "\r\n<section class=\"jumpbox\">\r\n<script>\r\n";
	$htmlfoot = "</script>" . "\r\n</section>\r\n</body>\r\n</html>\r\n";

	$litime = ($limittime == 0 ? 3000 : $limittime);
	$func = '';

	if ($gourl == '-1') {
		if ($limittime == 0)
		$litime = 3000;
		$gourl = "javascript:history.go(-1);";
	}

	if ($gourl == '' || $onlymsg == 1) {
		$msg = "<script>alert(\"" . str_replace("\"", "“", $msg) . "\");</script>";
	} else {
		//当网址为:close::objname 时, 关闭父框架的id=objname元素
		if (preg_match('/close::/', $gourl)) {
			$tgobj = trim(preg_replace('/close::/', '', $gourl));
			$gourl = 'javascript:;';
			$func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
		}

		$func .= "      var pgo=0;
  function JumpUrl(){
    if(pgo==0){ location='$gourl'; pgo=1; }
  }\r\n";
		$rmsg = $func;
		$rmsg .= "document.write(\"<p class=\\\"jumptitle\\\">信息提示</p>\");\r\n";
		$rmsg .= "document.write(\"<p>" . str_replace("\"", "“", $msg) . "</p>";

		if ($onlymsg == 0) {
			if ($gourl != 'javascript:;' && $gourl != '') {
				$rmsg .= "<p><a href='{$gourl}'>如果没有响应，请点击这里...</a></p>\");\r\n";
				$rmsg .= "setTimeout('JumpUrl()',$litime);";
			} else {
				$rmsg .= "\");\r\n";
			}
		} else {
			$rmsg .= "\");\r\n";
		}
		$msg = $htmlhead . $rmsg . $htmlfoot;
	}
	echo $msg;
}


/**
 *  获得当前的脚本网址
 *
 * @return    string
 */
if ( ! function_exists('GetCurUrl'))
{
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$scriptName = $_SERVER["REQUEST_URI"];
			$nowurl = $scriptName;
		}
		else
		{
			$scriptName = $_SERVER["PHP_SELF"];
			if(empty($_SERVER["QUERY_STRING"]))
			{
				$nowurl = $scriptName;
			}
			else
			{
				$nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
			}
		}
		return $nowurl;
	}
}

//SQL语句过滤程序，由80sec提供，这里作了适当的修改
if (!function_exists('CheckSql'))
{
	function CheckSql($db_string,$querytype='select')
	{
		$clean = '';
		$error='';
		$old_pos = 0;
		$pos = -1;
		$log_file = '/../data/'.md5('BxKRv5971R').'_safe.txt';
		$userIP = GetIP();
		$getUrl = GetCurUrl();

		//如果是普通查询语句，直接过滤一些特殊语法
		if($querytype=='select')
		{
			$notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";

			//$notallow2 = "--|/\*";
			if(preg_match("/".$notallow1."/", $db_string))
			{
				fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||SelectBreak\r\n");
				exit("<font size='5' color='red'>Safe Alert: Request Error step 1 !</font>");
			}
		}

		//完整的SQL检查
		while (TRUE)
		{
			$pos = strpos($db_string, '\'', $pos + 1);
			if ($pos === FALSE)
			{
				break;
			}
			$clean .= substr($db_string, $old_pos, $pos - $old_pos);
			while (TRUE)
			{
				$pos1 = strpos($db_string, '\'', $pos + 1);
				$pos2 = strpos($db_string, '\\', $pos + 1);
				if ($pos1 === FALSE)
				{
					break;
				}
				elseif ($pos2 == FALSE || $pos2 > $pos1)
				{
					$pos = $pos1;
					break;
				}
				$pos = $pos2 + 1;
			}
			$clean .= '$s$';
			$old_pos = $pos + 1;
		}
		$clean .= substr($db_string, $old_pos);
		$clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

		//老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
		if (strpos($clean, 'union') !== FALSE && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="union detect";
		}

		//发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
		elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== FALSE || strpos($clean, '#') !== FALSE)
		{
			$fail = TRUE;
			$error="comment detect";
		}

		//这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
		elseif (strpos($clean, 'sleep') !== FALSE && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="slown down detect";
		}
		elseif (strpos($clean, 'benchmark') !== FALSE && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="slown down detect";
		}
		elseif (strpos($clean, 'load_file') !== FALSE && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="file fun detect";
		}
		elseif (strpos($clean, 'into outfile') !== FALSE && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="file fun detect";
		}

		//老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
		elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
		{
			$fail = TRUE;
			$error="sub select detect";
		}
		if (!empty($fail))
		{
			fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||$error\r\n");
			exit("<font size='5' color='red'>Safe Alert: Request Error step 2!</font>");
		}
		else
		{
			return $db_string;
		}
	}
}

/**
 * 获取用户浏览器信息
 * @global array $_SERVER
 * @return array
 */
function getBrowser() {
	$sys = $_SERVER['HTTP_USER_AGENT'];
	if (stripos($sys, "NetCaptor") >= 0 && stripos($sys, "NetCaptor") !== false) {
		$exp[0] = "NetCaptor";
		$exp[1] = "";
	} elseif (stripos($sys, "Firefox/") >= 0 && stripos($sys, "Firefox/") !== false) {
		preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
		$exp[0] = "Mozilla Firefox";
		$exp[1] = $b[1];
	} elseif (stripos($sys, "MAXTHON") >= 0 && stripos($sys, "MAXTHON") !== false) {
		preg_match("/MAXTHON\s+([^;)]+)+/i", $sys, $b);
		preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
		$exp[0] = $b[0] . " (IE" . $ie[1] . ")";
		$exp[1] = $ie[1];
	} elseif (stripos($sys, "MSIE") >= 0 && stripos($sys, "MSIE") !== false) {
		preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
		$exp[0] = "Internet Explorer";
		$exp[1] = $ie[1];
	} elseif (stripos($sys, "Netscape") >= 0 && stripos($sys, "Netscape") !== false) {
		$exp[0] = "Netscape";
		$exp[1] = "";
	} elseif (stripos($sys, "Opera") >= 0 && stripos($sys, "Opera") !== false) {
		$exp[0] = "Opera";
		$exp[1] = "";
	} elseif (stripos($sys, "Chrome") >= 0 && stripos($sys, "Chrome") !== false) {
		$exp[0] = "Chrome";
		$exp[1] = "";
	} elseif (stripos($sys, "Safari") >= 0 && stripos($sys, "Safari") !== false) {
		$exp[0] = "Safari";
		$exp[1] = "";
	} else {
		$exp[0] = "未知浏览器";
		$exp[1] = "";
	}
	return $exp[0] . ' ' . $exp[1];
}

/**
 * 判斷是否是手機訪問
 * @return boolean
 */
function is_mobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
		return true;
	}
	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA'])) {
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}

	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
		foreach ($mobile_agents as $device) {
			if (stristr($user_agent, $device)) {
				return true;
			}
		}
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

/**
 * 发送一个异步请求
 * 异步请求不会阻塞程序运行，代价是无法知道请求结果
 * 如果需要得到请求结果和数据，请使用curl或者其它方法
 * 
 * @param string $url
 * @param string $post post内容，如果为空，则当做get方法
 * @param string $cookie
 * @param string $ip
 * @param int $timeout
 */
function asyncRequest($url, $data = '', $cookie = '', $ip = '', $timeout = 30) {
	@ignore_user_abort();
	$return = '';
	$matches = parse_url($url);
	$scheme = $matches['scheme'];
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$header = "Accept: */*\r\n";
		$header .= "Accept-Language: zh-cn\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$header .= "Host: $host\r\n";
		$header .= 'Content-Length: '.strlen($post)."\r\n";
		$header .= "Connection: Close\r\n";
		$header .= "Cache-Control: no-cache\r\n";
		$header .= "Cookie: $cookie\r\n\r\n";
		$out .= $header.$post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$header = "Accept: */*\r\n";
		$header .= "Accept-Language: zh-cn\r\n";
		$header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$header .= "Host: $host\r\n";
		$header .= "Connection: Close\r\n";
		$header .= "Cookie: $cookie\r\n\r\n";
		$out .= $header;
	}
	$fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	stream_set_timeout($fp, $timeout);
	fwrite($fp, $out);
	fclose($fp);
	return $ret;
}

/**
 * 發起http請求
 * 
 * @param string $url
 * @param false|array $post 是否使用post方式提交，如果使用post，則此項是post的數據數組
 * @param string $cookie cookie文件保存路徑
 * @return mixed 返回結果
 */
function httpRequest($url, $post=false, $cookie='', $format='', $timeout='') {

	if (!function_exists('curl_init')) {
		return false;
	}
	$purl = parse_url($url);
	$ch = curl_init();

	if ($purl['scheme'] == 'https') {
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	if('json' === $format){// header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);

	//CURLOPT_CONNECTTIMEOUT 在发起连接前等待的时间,应对目标服务器的过载,下线或者崩溃等可能状况
	//CURLOPT_TIMEOUT 应对目标服务器生成内容速度过慢或者链路速度过慢
	//单位：秒
	if (!empty($timeout)) {
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	}else{
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	}

	if ($post != false) {
		curl_setopt($ch, CURL_POST, true);
		if (is_array($post)) {
			$post = http_build_query($post);
		} else {
			$post = $post;
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}

	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}


function ShowSdkMsg($data) {
	//改此处记得检查mgameapi/libs/mGameView里的相关逻辑是否同步
	$time = time();
	$config = loadC('config.inc.php', 'main');
	$gamer_server_key = $config['key'][ $data['game'] ]['game_server'];

	$accesstoken_params = array(
	'action' => 'autoLogin' ,
	'username' => $data['userid'] ,
	'timestamp' => $time ,
	'password' => $data['pwd'] ,
	'phoneid' => $data['phoneid']
	);
	ksort($accesstoken_params);
	$accesstoken = md5( implodeWithKey('', $accesstoken_params) . $config['common_key'] );

	//为gamecenter的用户创建center数据
	if($data['game'] == 'gamecenter') {
		load('@member.model.memberInfo');
		if(!empty($data['userid'])) {
			load('model.memberInfo');
			$member_info = memberInfo::getInstance($data['userid']);
			if($member_info) {
				$member_info->createCenterAccount();
			}
		}
	}

	load('@agent.model.forgameUser');
	load('@agent.model.api.openUser');
	openUser::getInstance();
	$profix = openUser::getProfix($data['site']);
	$uid = substr($data['userid'], strlen($profix));
	$openuser = openUser::getUserByUid($uid);
	$data['openuid'] = $openuser['username'];
	$array = array(
	'a' => $data['openid'] == 'google' ? 'googleLogin' : 'openLoginByWeb',
	'code' => $data['code'],
	'message' => $data['message'],
	'data' => array(
	'username' => $data['userid'],
	'userid' => $data['hash_username'],
	'bind_username' => $data['bind_username'],
	'openuid' => $data['openuid'],
	'nickname' => $data['nickname'].'',
	'sex' => $data['sex'],
	'channel' => $data['site'],
	'logintime' => $time,
	'account_status' => 0,
	'accesstoken' => $accesstoken,
	'sign' => md5(
	$gamer_server_key .
	$data['hash_username'] .
	$data['openuid'] .
	$data['nickname'] .
	$data['sex'] .
	$time
	),
	)
	);

	//设置登入流水
	load('@main.model.libs.mGameSDKUserLog');
	mGameSDKUserLog::setLogin(
	'sdk',
	$data['game'],
	$data['userid'],
	$time
	);

	//记录渠道数据
	if($data['isnewuser']) {
		//记录渠道数据
		$member_model = new model('fg_members');
		$tracking_model = new model('fg_sdk_ad_tracking');
		$tracking = $tracking_model->get('`phoneid`=%s AND`game`=%s ORDER BY `time` DESC', array($data['phoneid'], $data['game']));
		$member_model->set(array(
		'trackingid' => strtolower($tracking['channel']),
		'ads_id' => $tracking['ads_id'],
		'game' => $data['game']
		), '`userid`=%s', array($data['userid']));


		$channel_id = intval($tracking['ads_id']);
		if ($channel_id) {
			$mod = new model('fg_adv');
			$where = '`id` = \''. $channel_id .'\'';
			$rowAd = $mod->get($where);
			$mod = new model('fg_adv_reg');
			$data = array(
			'adv_id'   => $channel_id,
			'media'    => $rowAd['media'],
			'game'     => $rowAd['game'],
			'material' => $rowAd['material'],
			'userid'   => $data['userid'],
			'username' => $data['userid'],
			'times'	   => time(),
			'ip'       => GetIP(),
			'channel_id' => $rowAd['channel_id'],
			);
			$mod->set($data);
		}
	}

	$str = json_encode($array);

	$msg = "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<script type='text/javascript'>  
		function inform() {
            var toast = '$str';
			//IOS
			window.inform = function() {
				return toast;
			}
			//安卓
			android.openidData(toast);
		}
	</script>
	</head>
	<body onload=inform();>
	</body>
	</html>	
	";

	echo $msg;
	exit;
}

/**
 * 通知手机关闭webview
 */
function closeWebView() {
	$msg = "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<script>
		function closeWebView() {
			//IOS
			window.inform = function() {
				return 'closeWebView';
			}
			//安卓
			android.closeWebView();
		}
	</script>
	</head>
	<body onload=closeWebView();>
	</body>
	</html>	
	";

	echo $msg;
	exit;
}

/**
 * 向sdk发送gash储值结果
 * 
 * @param string $tips 储值结果提示
 */
function ShowSdkGashReturn($tips) {
	$msg = "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<script type='text/javascript'>  
		function showAndroidToast(toast) {
			alert(toast);
			return false;
		}
	</script>
	</head>
	<body onload=showAndroidToast('$tips');>
	</body>
	</html>	
	";

	echo $msg;
	exit;
}

/**
 * 向sdk发送gash储值结果
 * 
 * @param string $tips 储值结果提示
 */
function ShowSDKLiteGashReturn($tips) {
	$msg = "
	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<script type='text/javascript'>  
		function showAndroidToast(toast) {
			android.deposit(toast);   
			return false;
		}
	</script>
	</head>
	<body onload=showAndroidToast('$tips');>
	</body>
	</html>	
	";

	echo $msg;
	exit;
}

/**
 * 格式化容量
 * 
 * @param int $file_size 容量大小，目前只能处理Kbps
 * @param mixed $type 格式化类型 GB、MB、KB，如果不填此项，则根据大小自动判断类型
 * 除KB外，其它类型都自动保留1位小数
 * @return string
 */
function formatSize($file_size, $type=null) {
	if (($type === null && $file_size >= 1048576) || $type === "G") {
		$file_size = round($file_size / 1048576, 1) . 'G';
	} elseif (($type === null && $file_size >= 1024) || $type === "M") {
		$file_size = round($file_size / 1024, 1) . 'M';
	} else {
		$file_size = $file_size . 'K';
	}
	return $file_size;
}


/**
 * 友好格式化时间 
 * 
 * @param int 时间 
 * @param array $formats 
 * @return string 
 */
function diff($timestamp, $formats = null) {
	if ($formats == null) {
		$formats = array(
		'DAY' => '%s天前',
		'DAY_HOUR' => '%s天%s小时前',
		'HOUR' => '%s小时',
		'HOUR_MINUTE' => '%s小时%s分前',
		'MINUTE' => '%s分钟前',
		'MINUTE_SECOND' => '%s分钟%s秒前',
		'SECOND' => '%s秒前',
		);
	}
	/* 计算出时间差 */
	$seconds = time() - $timestamp;
	$minutes = floor($seconds / 60);
	$hours = floor($minutes / 60);
	$days = floor($hours / 24);

	if ($days > 0) {
		$diffFormat = $days > 30 ? 'NORMAL' : 'DAY_HOUR';
	} else {
		$diffFormat = ($hours > 0) ? 'HOUR' : 'MINUTE';
		if ($diffFormat == 'HOUR') {
			$diffFormat .= ($minutes > 0 && ($minutes - $hours * 60) > 0) ? '_MINUTE' : '';
		} else {
			$diffFormat = (($seconds - $minutes * 60) > 0 && $minutes > 0) ? $diffFormat . '_SECOND' : 'SECOND';
		}
	}

	$dateDiff = null;
	switch ($diffFormat) {
		case 'DAY':
			$dateDiff = sprintf($formats[$diffFormat], $days);
			break;
		case 'DAY_HOUR':
			$dateDiff = sprintf($formats[$diffFormat], $days, $hours - $days * 60);
			break;
		case 'HOUR':
			$dateDiff = sprintf($formats[$diffFormat], $hours);
			break;
		case 'HOUR_MINUTE':
			$dateDiff = sprintf($formats[$diffFormat], $hours, $minutes - $hours * 60);
			break;
		case 'MINUTE':
			$dateDiff = sprintf($formats[$diffFormat], $minutes);
			break;
		case 'MINUTE_SECOND':
			$dateDiff = sprintf($formats[$diffFormat], $minutes, $seconds - $minutes * 60);
			break;
		case 'SECOND':
			$dateDiff = sprintf($formats[$diffFormat], $seconds);
		default :
			$dateDiff = date('Y-m-d H:i', $timestamp);
			break;
	}
	return $dateDiff;
}

/**
 * 连接数组的键名和值，键名和值之间被 = 隔开
 * 
 * @param string $glue 元素和元素之间的分隔符
 * @param array $arr 数组
 * @return string
 */
function implodeWithKey($glue, $arr) {
	$str = '';
	foreach($arr as $key => $value) {
		$str .= $key . '=' . $value . $glue;
	}
	//如果分隔符不为空，最后一个是不必要的，需要去掉
	if(!empty($glue)) {
		$str = substr($str, 0, strlen($str) - strlen($glue));
	}
	return $str;
}

function show404() {
	include C('DEDE_ROOT_PATH') . '404.html';
	exit;
}

/**
 * 使用message支持简单的BBCODE语法
 * <o>消息</o>替换后： <font color="#ea3405">消息</font>
 * 各种标签语义：
 * o: orange
 * r: red
 * y: yellow
 * g: green
 * 
 * @param string $comment
 * @return string
 */
function bbcode($comment) {
	$replace = preg_replace_callback('/<(\w+)>(.*?)<\/\1>/', '_bbcodeReplace', $comment);
	return nl2br($replace, false);
}

/**
 * 替换BBCODE
 * 
 * @param array $match
 * @return string
 */
function _bbcodeReplace($match) {
	$type = $match[1];
	$comment = $match[2];
	$color = '';
	switch($type) {
		case 'o':
			$color = '#ea3405';
			break;
		case 'r':
			$color = '#FF0000';
			break;
		case 'y':
			$color = '#FFFF00';
			break;
		case 'g':
			$color = '#00FF00';
		case 'h1':
			return "<font color=\"#999999\">{$comment}</font>";
			break;
	}
	return "<font color=\"{$color}\">{$comment}</font>";
}

/**
 * 去除数组键名
 * 
 * @param string $arr
 * @return array
 */
function removeArrayKey($arr) {
	$_arr = array();
	foreach($arr as $value) {
		$_arr[] = $value;
	}
	return $_arr;
}

/**
 * 随机数字
 *
 * @return string
 */
function get_srand_sn()
{
	/* 选择一个随机的方案 */
	return substr(date('ymd'), 1) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * 为数组设置键值
 * 
 * @param array $arr
 * @param string $key
 * @return array
 */
function setKey($arr, $key) {
	$_arr = array();
	foreach($arr as $value) {
		$_arr[$value[$key]] = $value;
	}
	return $_arr;
}

/**
 * 随机订单号
 *
 * @return string
 */
function get_order_sn()
{
	/* 选择一个随机的方案 */
	mt_srand((double) microtime() * 1000000);
	return date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * 邮件发送
 */
function smtp_mail($sendto_email, $subject, $body, $cc_email=null, $flag=null){
	include_once('libs/plugins/phpmailer/phpmailer.php');

	$mail = new PHPMailer();
	$mail->IsSMTP();                  // send via SMTP
	$mail->Host = "smtp.exmail.qq.com";   // SMTP servers
	$mail->SMTPAuth = true;           // turn on SMTP authentication
	$mail->Username = "root@2y9y.com";     // SMTP username  注意：普通邮件认证不需要加 @域名
	$mail->Password = "Qy224562."; // SMTP password
	$mail->From = "root@2y9y.com";      // 发件人邮箱

	$mail->FromName =  "2Y9Y 平台";  // 发件人
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->SMTPAuth = true;

	$mail->CharSet = "UTF-8";   // 这里指定字符集！
	$mail->Encoding = "base64";
	$mail->AddAddress($sendto_email);  // 收件人邮箱和姓名
	//if($cc_email) {
	//$mail->AddCC('denglh1990@qq.com');
	//}
	$mail->IsHTML(true);  // send as HTML
	// 邮件主题
	$mail->Subject = $subject;
	// 邮件内容
	$mail->Body = $body;
	$mail->AltBody ="text/html";
	if($mail->Send()){
		return  true;
	}
	return false;
}

//生成指定长度的随机数
function getRandNums($len){
	$length = $len;
	return rand(pow(10, ($length-1)), pow(10, $length)-1);
}

/**
 * 发送手机短信
 */
/**
 * 发送手机短信
 */
function sendSms($telphone, $num, $message='') {
	//设置默认时区为北京时间
	date_default_timezone_set('PRC');

	//短信接口用户名 $uid
	$uid = 'GZLKJ0003350';

	//短信接口密码 $passwd
	$passwd = 'qy666666';

	//短信内容 $message(短信服务商限定了内容只有两种)
	if (empty($message)) {
		$message = "爱游就游游戏平台验证码：" . $num . "（泄漏有风险）官网：www.2y9y.com";
	}else {
		$message = "系统已经成功为您重置了登入密码，新密码为 " . $num . "。为保障账号安全，请登入游戏后修改密码。";
	}

	//字符转换
	$msg = rawurlencode(mb_convert_encoding($message, 'GB2312', 'UTF-8'));

	$gateway = "http://sdk.zhongguowuxian.com:98/ws/batchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$telphone}&Content={$msg}&Cell=&SendTime=";
	$result = file_get_contents($gateway);

	return $result;
}


//POST 数据
function postsite($url, $post_data = '', $timeout = 5){//curl
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);

	if($post_data != ''){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_HEADER, false);

	$file_contents = curl_exec($ch);
	curl_close($ch);

	return $file_contents;
}
/**
 * 加载IP分析
 */
function GetIpLookup($ip = ''){
	if(empty($ip)){
		return false;
	}
	$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
	if(empty($res)){
		return false;
	}
	$jsonMatches = array();
	preg_match('#\{.+?\}#', $res, $jsonMatches);
	if(!isset($jsonMatches[0])){ return false; }
	$json = json_decode($jsonMatches[0], true);
	if(isset($json['ret']) && $json['ret'] == 1){
		$json['ip'] = $ip;
		unset($json['ret']);
	}else{
		return false;
	}

	return $json;
}


/**
 * 获取数值范围（如根据金额得出折扣等）
 */
function getSize($rule, $current_value){
	//默认值
	$data = 1;
	if (empty($rule)) {
		return $data;
	}

	//0,1000,0.9;1001,2000,0.8;3001,5000,0.5;5001,10000000,0.2
	$array1 = explode(';', $rule);
	if ($array1) {
		foreach ($array1 as $key1 => $value1) {
			$array2 = explode(',', $value1);

			if ($array2[0] <= $current_value && $array2[1] >= $current_value) {
				$data = $array2[2];
			}
		}
	}
	if ($data == 0) {
		$data = 1;
	}

	return $data;
}

/**
 * 公钥转化为PEM格式
 * 仅用于本地和测试服调试
 */
function mkPubKey($filename, $pubKey){
	@chmod($filename, 0777);
	unlink($filename);
	$devPubKey = $pubKey;
	$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
	$end_public_key = "-----END PUBLIC KEY-----\r\n";

	$fp = fopen($filename,'ab');
	fwrite($fp,$begin_public_key,strlen($begin_public_key)); 

	$raw = strlen($devPubKey)/64;
	$index = 0;
	while($index <= $raw )
	{
		$line = substr($devPubKey,$index*64,64)."\r\n";
		if(strlen(trim($line)) > 0)
		fwrite($fp,$line,strlen($line)); 
		 $index++;
	}
	fwrite($fp,$end_public_key,strlen($end_public_key)); 
	fclose($fp);
}

/**
 * 私钥转化为PEM格式
 * 仅用于本地和测试服调试
 */
function mkPriKey($filename, $priKey){
	@chmod($filename, 0777);
	unlink($filename);
	$devPriKey = $priKey;
	$begin_private_key = "-----BEGIN PRIVATE KEY-----\r\n";
	$end_private_key = "-----END PRIVATE KEY-----\r\n";

	$fp = fopen($filename,'ab');
	fwrite($fp,$begin_private_key,strlen($begin_private_key)); 

	$raw = strlen($devPriKey)/64;
	$index = 0;
	while($index <= $raw )
	{
		$line = substr($devPriKey,$index*64,64)."\r\n";
		if(strlen(trim($line)) > 0)
		fwrite($fp,$line,strlen($line)); 
		 $index++;
	}
	fwrite($fp,$end_private_key,strlen($end_private_key)); 
	fclose($fp);
}


/**
 * 发送手机短信
 */
function noerSendSms($telphone, $num, $tempCode='') {
	$appId = '154034821922460';
	$md5Key = 'Q65WqWW9QWoGrVYwHtBuB393C4PHHKed';
	$desKey = 'Rrxwe5SNKMXCyWRBehnX6SmX';
	$url = 'https://sms.ipaynow.cn';

	
	if (empty($tempCode)) {
		$templateInfo['tempCode'] = '5000001895098568';//短信模板
	}else{
		$templateInfo['tempCode'] = $tempCode;//短信模板
	}
	$templateInfo['param']['code'] = (string)$num;
	$templateInfo = json_encode($templateInfo);
	$data = array(
		'funcode' => 'INDUSTRY_TEMP', 
		'appId' => $appId,
		'mhtOrderNo' => get_order_sn(),
		'mobile' => $telphone,
		'templateInfo' => $templateInfo,
		'notifyUrl' => urlencode(C('MIS_URL') . 'api/index.php?m=index&a=smsCallBack')
	);
	ksort($data);
	$dataStr = implodeWithKey('&', $data);

	$post_data = array(
		'funcode' => 'INDUSTRY_TEMP', 
		'message' => urlencode(base64_encode('appId='.$appId).'|'.base64_encode(encryptDES($dataStr,$desKey)).'|'.base64_encode(md5($dataStr.'&'. $md5Key)))
	);

	$reqStr = implodeWithKey('&', $post_data);
	$resp = httpRequest($url, $reqStr);
	return 1;
}


/*
* 3DES加密（短信验证使用）
 */
function encryptDES($data, $des_key){
    if($len = strlen($data) % 8) {
        for($i=0;$i<8-$len;$i++) {
            $data .= ' ';
        }
    }

    return openssl_encrypt(
        $data,
        'DES-EDE3' ,
        $des_key,
        OPENSSL_RAW_DATA | OPENSSL_NO_PADDING ,
        ''
    );

}


function httpcode($url, $timeout=3){
	$ch = curl_init();
	//$timeout = 3;
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_exec($ch);
	return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
}

/**
 * 返回当前的毫秒时间戳
 */
function getMillisecond()
{ 
	list($t1, $t2) = explode(' ', microtime()); 
	return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000); 
} 

/**
 * 随机字符串
 * 
 * @param $length 字符串长度
 */
function createNonceStr($length = 16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
}

/**
 * 按照字母分组并排序
 * 
 * @param {Array} $list    需要排序的数据， 一维数组
 * @param {string} $field  排序需要依据的字段，该字段首字母必须为拼音 
 */
function data_letter_sort($list, $field) {
	$resault = array();
	foreach( $list as $key => $val ){
		// 添加#分组，用来存放首字母不能转为大写英文数据
		$resault['#'] = array();
		// 首字母转大写英文
		$letter = strtoupper( substr($val[$field], 0, 1) );
		// 是否大写英文字母
		if( !preg_match('/^[A-Z]+$/', $letter) ){
			$letter = '#';
		}
		// 创建字母分组
		if( !array_key_exists($letter, $resault) ){
			$resault[$letter] = array();
		}
		// 字母分组添加数据
		array_push($resault[$letter], $val);
	}
	// 依据键名字母排序
	ksort($resault);
	// 注销#分组
	unset($resault['#']);
	
	return $resault;
}

/**
 * 雪花算法SnowFlake生成唯一ID
 *
 * @return string
 */
function snowFlakeID()
{
	$machineId = 1234567890;
	$time = floor(microtime(true) * 1000);
	$suffix = 0;
	$base = decbin(pow(2,40) - 1 + $time);
	$machineid = decbin(pow(2,9) - 1 + $machineId);
	$random = mt_rand(1, pow(2,11)-1);
	$random = decbin(pow(2,11)-1 + $random);
	$base64 = $suffix.$base.$machineid.$random;
	$base64 = bindec($base64);
	$id = sprintf('%.0f', $base64);
	return $id;
}