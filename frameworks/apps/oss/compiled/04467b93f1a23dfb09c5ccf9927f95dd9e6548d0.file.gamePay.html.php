<?php /* Smarty version Smarty-3.1.11, created on 2021-11-03 17:23:20
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkGame\gamePay.html" */ ?>
<?php /*%%SmartyHeaderCode:1731361825508d904e0-72816124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04467b93f1a23dfb09c5ccf9927f95dd9e6548d0' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkGame\\gamePay.html',
      1 => 1632802584,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1731361825508d904e0-72816124',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'uid' => 0,
    'topmenus' => 0,
    'menuid' => 0,
    'v' => 0,
    'k' => 0,
    'menus' => 0,
    'menu' => 0,
    'a' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_618255091b5438_87250171',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_618255091b5438_87250171')) {function content_618255091b5438_87250171($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/static/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="/js/jquery.treeview/jquery.treeview.css" />
<title>中央数据后台</title>
<script src="/js/jquery-1.7.2.min.js"></script>
<script src="/js/jquery.treeview/lib/jquery.cookie.js" type="text/javascript"></script>
<script src="/js/jquery.treeview/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/easyui.css">
<script src="/js/common.js" type="text/javascript"></script>
<script src="/js/clipboard.min.js"></script>
<script>
// third example
function delCookie() {
	$.cookie("treeview-black", null);
	return false;
}
$(document).ready(function(){
	// fourth example
	$("#black, #gray").treeview({
		control: "#treecontrol",
		persist: "cookie",
		cookieId: "treeview-black"
	});
});
</script>

</head>
<body>
<!--頭部-->
<div id="header">
    <div class="wrapper">
        <p class="title">中央数据后台</p>
        <p class="user">&nbsp;&nbsp;欢迎您，<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
！ </br><a href="?m=home&a=index" title="" style="color:#a1dfd7;">系统首页</a> | </span><a href="?m=priv&a=logout" onclick="delCookie();"style="color:#a1dfd7;">退出系統</a></p>
        <img src="/img/user.png" style="width: 2%; float: right; margin-top: 1.2%;"></img>
        <ul id="menu">
            <span>
            	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topmenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
                <li<?php if ($_smarty_tpl->tpl_vars['menuid']->value==$_smarty_tpl->tpl_vars['v']->value['id']){?> class="active"<?php }?>><a href="/<?php if (empty($_smarty_tpl->tpl_vars['v']->value['link'])){?>index.php?m=<?php echo $_smarty_tpl->tpl_vars['v']->value['module'];?>
&a=<?php echo $_smarty_tpl->tpl_vars['v']->value['action'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
<?php }?>" onclick="delCookie();">&nbsp;&nbsp;&nbsp;<img src="/img/<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
.png" style="width: 18%; vertical-align: middle;"></img>&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
&nbsp;&nbsp;</a></li>
                <?php } ?>
            </span>
        </ul>
    </div>
</div>

<!--中間內容區域-->

<div class="wrapper body">
    <div id="subMenu">    
    	<?php if (!empty($_smarty_tpl->tpl_vars['menus']->value)){?>
    	<!--  
		<div id="treecontrol" style="margin-left:7px;">
			<a href="#"><img src="/js/jquery.treeview/images/plus.gif" />&nbsp;全關閉</a>
			<a href="#"><img src="/js/jquery.treeview/images/minus.gif" />&nbsp;全打開</a>
		</div>
		-->
    	<ul id="black" class="treeview-black">
        <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
?>
        <li><span style="font-size:16px; color:#1b54a8; font-weight:bold; background:#000; display:block; padding:5px 0 5px 10px;background: -webkit-gradient(linear, 0 0, 0 100%, from(#fff), to(#d7d7d7)); font-family: 微软雅黑; font-weight: 600;"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</span>
        <ul>
        	<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
            <li style=" padding-left:20px;"><span>&nbsp;<a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['a']->value['module'];?>
&a=<?php echo $_smarty_tpl->tpl_vars['a']->value['action'];?>
<?php echo $_smarty_tpl->tpl_vars['a']->value['param'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['name'];?>
</a></span></li>
            <?php } ?>
        </ul>
        </li>
        <?php } ?>
        </ul>
        <?php }?>
    </div>
    <div class="content">
    	
<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
	<style>
		.list td{ padding:10px 0;}
		.show{ display:block; color:#3d203f; text-decoration:none; width:110px; position:relative; left:0; top:0; z-index:10; float:left;}
		.show p{ display:none;}
		.show:hover{ display:block; text-decoration:none; height:100%; position:relative; z-index:1000 !important;}
		.show:hover p{ display:block; color:#3d203f; background:#cff0fc; position:absolute; left:-10px; top:-10px; white-space:normal; width:700px;; padding:10px; box-shadow:1px 1px 10px #333; cursor:pointer;}
	</style>
<!--START 列表頁-->
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
	<h3>
		<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>
		<span><a href="/index.php?m=sdkGame&a=gamePay&operation=add">新增支出</a></span>
		<?php }?>
		项目支出
	</h3>
	<form class="searchbox" action="/index.php?m=sdkGame&a=gamePay" method="post">
        <p>
        <span>来自游戏：</span>
            <select name="upperName" id="upperName" style="width: 98px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['UpperList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
" <?php if ($_smarty_tpl->tpl_vars['upperName']->value==$_smarty_tpl->tpl_vars['name']->value['upperName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
            <select name="specialName" id="specialName" style="width: 98px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['specialList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
" <?php if ($_smarty_tpl->tpl_vars['specialName']->value==$_smarty_tpl->tpl_vars['name']->value['specialName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
</option>
                <?php } ?>
            </select>
            <select name="game" id="game" style="width: 98px;"><option value="">请选择</option>
	            <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
	                <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['gameAlias']->value==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>> <?php echo smarty_modifier_truncate(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['key1']->value),1,'',true);?>
 — <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
	            <?php } ?>
	        </select>
		<span>渠道： </span>
		<select name="channel" id="channel">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['key1']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
            <?php } ?>
        </select>
        <span>包号：</span>
        <select name="apkNum" id="apkNum" style="width: 180px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['apk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['apk']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['apk']->key => $_smarty_tpl->tpl_vars['apk']->value){
$_smarty_tpl->tpl_vars['apk']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['apk']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['apkNum']->value==$_smarty_tpl->tpl_vars['apk']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
</option>
            <?php } ?>
        </select>
        <span>时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
    </p>
    <p>
		<span>支出模块：</span>
		<select name="module" id="module" onchange="fn()">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['module']->value==1){?>selected="selected"<?php }?>>公司模块</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['module']->value==2){?>selected="selected"<?php }?>>项目模块</option>
            <option value="3" <?php if ($_smarty_tpl->tpl_vars['module']->value==3){?>selected="selected"<?php }?>>游戏模块</option>
        </select>
		<span style="width: 192px;">支出类型：</span>
		<select name="type" id="type">
            <option value="">请选择</option>
        </select>
	</p>
    <table style="clear:both;margin-top:10px; float:right;width:100%;">
        <tr>
            <td align="left"><button type="submit" class="su inline" style=" margin-left: 30px;">查询</button>
        </tr>
    </table>
	</form>
	<table class="list">
		<tr style="background-color:#CCCCCC;">
			<th>日期</th>
			<th>支出模块</th>
			<th>支出类型</th>
			<th>游戏项目</th>
			<th>游戏名称</th>
			<th>渠道</th>
			<th>包号</th>
			<th>运营模式</th>
			<th>支出</th>
			<th>详情</th>
			<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>
			<th>操作</th>
			<?php }?>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['game_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
		<tr>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['date'];?>
</td>
			<td><font <?php if ($_smarty_tpl->tpl_vars['game']->value['module']==1){?> color="red" <?php }elseif($_smarty_tpl->tpl_vars['game']->value['module']==2){?> color="green" <?php }else{ ?> color="blue" <?php }?>><?php echo $_smarty_tpl->tpl_vars['game']->value['moduleName'];?>
</font></td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['typeName'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['upperName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>
<?php }else{ ?>-<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['gameName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['gameName'];?>
<?php }else{ ?>-<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['channelName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['channelName'];?>
<?php }else{ ?>-<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['apkNum']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['apkNum'];?>
<?php }else{ ?>-<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['pattern']==1){?>联运<?php }elseif($_smarty_tpl->tpl_vars['game']->value['pattern']==2){?>投放<?php }elseif($_smarty_tpl->tpl_vars['game']->value['pattern']==2){?>cps<?php }else{ ?>-<?php }?></td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['pay'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['remark'];?>
</td>
			<?php if ($_smarty_tpl->tpl_vars['game']->value['changeRoot']==1){?>
			<td style="width:160px"><a href="/index.php?m=sdkGame&a=gamePay&operation=edit&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" target="_blank">编辑</a> | <a href="/index.php?m=sdkGame&a=gamePay&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a></td>
			<?php }?>
		</tr>
		    <?php }
if (!$_smarty_tpl->tpl_vars['game']->_loop) {
?>
		<tr>
		    <td colspan="21">无相关数据</td>
		</tr>
		<?php } ?>
		<?php if ($_smarty_tpl->tpl_vars['sum']->value>0){?>
		<tr style="background-color:#CCCCCC;">
			<td>汇总</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $_smarty_tpl->tpl_vars['sum']->value;?>
</td>
			<td></td>
			<td></td>
		</tr>
		<?php }?>
	</table>
	<div id="pager"></div>

<script src="js/pager.js"></script>
<script>
$('.delete_confirm').click(function() {
	return confirm('數據不可恢復，你確定要刪除嗎？');
});

function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=sdkGame&a=gamePay&page=" + page+ "&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&game=<?php echo $_smarty_tpl->tpl_vars['gameAlias']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&module=<?php echo $_smarty_tpl->tpl_vars['module']->value;?>
&type=<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>


<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='add'||$_smarty_tpl->tpl_vars['operation']->value=='edit'){?>
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>

	<h3>
		<span><a href="/index.php?m=sdkGame&a=gamePay">返回列表</a></span>
		<?php if ($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
			新增支出
		<?php }else{ ?>
			编辑支出
		<?php }?>
	</h3>
	<form action="/index.php?m=sdkGame&a=gamePay&operation=save" method="post" class="searchbox" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['gameList']->value['id'];?>
" />
		<p>
			<span>支出模块：</span>
			<select name="module" id="module" onchange="fn()">
	            <option value="">请选择</option>
	            <option value="1" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['module']==1){?>selected="selected"<?php }?>>公司模块</option>
	            <option value="2" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['module']==2){?>selected="selected"<?php }?>>项目模块</option>
	            <option value="3" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['module']==3){?>selected="selected"<?php }?>>游戏模块</option>
	        </select>
	        <font color="#FF0000">&nbsp;*&nbsp;</font>
		</p>
		<p>
			<span>支出类型：</span>
			<select name="type" id="type">
	            <option value="">请选择</option>
	        </select>
	        <font color="#FF0000">&nbsp;*&nbsp;</font>
		</p>
		<span id="m2">
		<p>
			<span>游戏项目：</span>
            <select name="upperName" id="upperName">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['UpperList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['upperName']==$_smarty_tpl->tpl_vars['name']->value['upperName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
		</p>
		<span id="m3">
		<p>
			<span>专服游戏名：</span>
            <select name="specialName" id="specialName">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['specialList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['specialName']==$_smarty_tpl->tpl_vars['name']->value['specialName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
</option>
                <?php } ?>
            </select>
        </p>
        <p>
			<span>游戏：</span>
                        <select name="game" id="game">
	            <option value="">请选择</option>
	            <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
	                <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['gameAlias']==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
	            <?php } ?>
	        </select>
		</p>
		<p>
			<span>渠道：</span>
			<select name="channel" id="channelList">
                <option value="">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['gameList']->value['channelId'])){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>
		</p>
		<p>
			<span>包号：</span>
			<select name="apkNum">
                <option value="">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['data']->value==$_smarty_tpl->tpl_vars['gameList']->value['apkNum'])){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>
		</p>
		<p>
			<span>运营模式：</span>
			<select name="pattern">
                <option value="">请选择</option>
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['pattern']==1){?>selected="selected"<?php }?>>联运</option>
	            <option value="2" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['pattern']==2){?>selected="selected"<?php }?>>投放</option>
	            <option value="3" <?php if ($_smarty_tpl->tpl_vars['gameList']->value['pattern']==3){?>selected="selected"<?php }?>>cps</option>
			</select>
		</p>
		</span>
		</span>
		<p>
			<span>日期：</span>
			<input type="text" name="date" value="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['gameList']->value['date'],'%Y-%m-%d');?>
" onclick="WdatePicker({ maxDate: '9999-12-30', readOnly: true });" placeholder="请填写支出日期" />
			<font color="#FF0000">&nbsp;*&nbsp;</font>
		</p>
		<p>
			<span>项目支出：</span>
			<input type="text" name="pay" value="<?php echo $_smarty_tpl->tpl_vars['gameList']->value['pay'];?>
" /><font style="color: red">&nbsp;*&nbsp;请填写人民币金额</font>
		</p>
		<p>
			<span>支出详情：</span>
			<input type="text" name="remark" value="<?php echo $_smarty_tpl->tpl_vars['gameList']->value['remark'];?>
" /><font style="color: red"></font>
		</p>
		<p class="line">
			<button type="submit" name="do" class="su">立即提交</button>
			<button type="reset" class="re">条件重置</button>
		</p>
	</form>
<script>
<?php if ($_smarty_tpl->tpl_vars['gameList']->value['module']==2){?>
$('#m2').show();
$('#m3').hide();
<?php }elseif($_smarty_tpl->tpl_vars['gameList']->value['module']==3){?>
$('#m2').show();
$('#m3').show();
<?php }else{ ?>
$('#m2').hide();
$('#m3').hide();
<?php }?>
$(function(){
	$("select#module").change(function(){
		var group = $(this).val();
		if((group == 2)){
			$('#m2').show();
			$('#m3').hide();
		}else if(group == 3){
			$('#m2').show();
			$('#m3').show();
		}else{
			$('#m2').hide();
			$('#m3').hide();
		}
	})
});
</script> 
<?php }?>
<?php }else{ ?>
<div style="font-size: 20px; color:red; margin:5% 0 0 30%;">无权限查看相关数据</div>
<?php }?>
<!--END 列表頁-->
<script>
fn();
function fn(){
	var select=document.getElementById("module");
	var c=select.value;
	var type=document.getElementById("type");
	switch(c)
	{
	case "1": type.innerHTML="<option value=''>请选择</option>\
	<option value='1' <?php if ($_smarty_tpl->tpl_vars['type']->value==1){?>selected='selected'<?php }?>>资质费用</option>\
	<option value='2' <?php if ($_smarty_tpl->tpl_vars['type']->value==2){?>selected='selected'<?php }?>>推广费用</option>\
	<option value='3' <?php if ($_smarty_tpl->tpl_vars['type']->value==3){?>selected='selected'<?php }?>>企业签</option>\
	<option value='4' <?php if ($_smarty_tpl->tpl_vars['type']->value==4){?>selected='selected'<?php }?>>服务器</option>";
	break;
	case "2":type.innerHTML="<option value=''>请选择</option>\
	<option value='1' <?php if ($_smarty_tpl->tpl_vars['type']->value==1){?>selected='selected'<?php }?>>项目支出</option>";
	break;
	case "3":type.innerHTML="<option value=''>请选择</option>\
	<option value='1' <?php if ('type'==1){?>selected='selected'<?php }?>>自充支出</option>\
	<option value='2' <?php if ('type'==2){?>selected='selected'<?php }?>>红包支出</option>\
	<option value='3' <?php if ($_smarty_tpl->tpl_vars['type']->value==3){?>selected='selected'<?php }?>>返利补单</option>\
	<option value='4' <?php if ($_smarty_tpl->tpl_vars['type']->value==4){?>selected='selected'<?php }?>>渠道推广</option>";
	break;
	default:type.innerHTML="<option>请选择</option>";
	}
};
</script> 

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>