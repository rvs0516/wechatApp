<?php /* Smarty version Smarty-3.1.11, created on 2022-10-21 14:41:19
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkGame\baned.html" */ ?>
<?php /*%%SmartyHeaderCode:31166178b82d6baeb2-01279904%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2277b143e421ea3a6589b0e2ca53df23a76b7cc3' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkGame\\baned.html',
      1 => 1542769784,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31166178b82d6baeb2-01279904',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6178b82da22109_41705606',
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
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6178b82da22109_41705606')) {function content_6178b82da22109_41705606($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
?><!DOCTYPE html>
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
        <?php if (count($_smarty_tpl->tpl_vars['menu']->value['list'])){?>
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
        <?php }?>
        <?php } ?>
        </ul>
        <?php }?>
    </div>
    <div class="content">
    	
<style>
	.checked{ width: 75px; margin-right: 8px; margin-left: 15px; height: 30px; text-align: center; line-height: 25px; color: #FFF; font-size: 12px; display: block; float: right; background: url(/static/submit.jpg) no-repeat;text-decoration:none;}
	.insert{ width: 75px; margin-right: 8px; height: 30px; text-align: center; line-height: 25px; color: #FFF; font-size: 12px; display: block; float: right; background: url(/static/submit.jpg) no-repeat;text-decoration:none;}
	.checked:hover { text-decoration: none;}
	.insert:hover { text-decoration: none;}
</style>
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
	<h3>
		登录屏蔽列表
	</h3>
	<form class="searchbox" action="/index.php?m=sdkGame&a=baned" method="post">
	<input type="hidden" name="operation" value="" id="operation" />
    <p>
		<span style="width: 130px;">输入IP/phoneID：</span>
		<input style="width: 180px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['banedKey']->value;?>
" name="banedKey" id="banedKey" placeholder="请输入屏蔽IP或phoneID">
		<span>起始时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})" style="width: 115px;"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )" style="width: 115px;">
        <!--<button type="submit" class="su" id="checked">查询</button>
        <button type="submit" class="su" id="insert">添加</button>-->
        <a href="javascript:void(0)" id="insert" class="insert"  onclick="document:search_form.submit();">添加屏蔽</a>
        <a href="javascript:void(0)"  id="checked" class="checked"  onclick="document:search_form.submit();">查询</a>
    </p>
	</form>
	<table class="list">
		<tr style="background-color:#CCCCCC;">
			<th style="width: 30%">IP/phoneID</th>
			<th style="width: 30%">添加时间</th>
			<th style="width: 20%">操作用户来源</th>
			<th style="width: 20%">操作</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['banedList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
		<tr>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['baned'];?>
</td>
			<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['game']->value['time'],"%y-%m-%d %H:%M");?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['uid'];?>
</td>
			<td style="width:160px"><a href="/index.php?m=sdkGame&a=baned&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a> </td>
		</tr>
		<?php }
if (!$_smarty_tpl->tpl_vars['game']->_loop) {
?>
            <td colspan="8" ><font color="red">暂无该用户数据，如需屏蔽请添加</font></td>
		<?php } ?>
	</table>
	<div id="pager"></div>

<script src="js/pager.js"></script>
<script>
$("#checked").click(function() {
	$("#operation").val("");
	$('.searchbox').submit();
});
$("#insert").click(function() {
	var r = confirm('你確定要屏蔽该用户的登录嗎？');
	if (r == true) {
		$("#operation").val("insert");
		$('.searchbox').submit();
	}
});
$('.delete_confirm').click(function() {
	return confirm('數據不可恢復，你確定要刪除嗎？');
});

function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=sdkGame&a=baned&page=" + page+ "&banedKey=<?php echo $_smarty_tpl->tpl_vars['banedKey']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>