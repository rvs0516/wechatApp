<?php /* Smarty version Smarty-3.1.11, created on 2022-01-17 15:52:40
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\linkData\linkOrder.html" */ ?>
<?php /*%%SmartyHeaderCode:1713561e4d3ba50b579-05488547%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67852c08dd0194b0cd5d4bf545dda2a9a6d68303' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\linkData\\linkOrder.html',
      1 => 1642405952,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1713561e4d3ba50b579-05488547',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61e4d3ba768d84_05976786',
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
<?php if ($_valid && !is_callable('content_61e4d3ba768d84_05976786')) {function content_61e4d3ba768d84_05976786($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
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
    	
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<?php }?>
<h3>
    关联订单列表
</h3>
<form class="searchbox" action="/index.php?m=linkData&a=linkOrder" method="post">
    <input type="hidden" name="page" value="<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
" id="page" />
    <input type="hidden" name="operation" value="" id="operation" />
    <p>
        <span>年份:</span>
        <select name="years" style="width: 100px;">
            <option value="2022" <?php if ($_smarty_tpl->tpl_vars['years']->value==2022){?>selected="selected"<?php }?>>2022年</option>
        </select>
    </p>
    <p>
        <span>起始时间：</span>
        <input type="text" name="start" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})" style="width: 115px;"> 至 <input type="text" name="end" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )" style="width: 115px;">
        <span>订单号：</span>
        <input type="text" placeholder="请输入订单号" name="orderId" value="<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
" style="width: 147px;"/>
        <span>帐号：</span>
        <input type="text" placeholder="请输入帐号" name="userName" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" style="width: 147px;"/>
        <span>支付方式：</span>
        <select name="payType" style="width: 100px;">
            <option value="">请选择</option>
            <option value="kb" <?php if ($_smarty_tpl->tpl_vars['payType']->value=='kb'){?>selected="selected"<?php }?>>咖币</option>
            <option value="coin" <?php if ($_smarty_tpl->tpl_vars['payType']->value=='coin'){?>selected="selected"<?php }?>>dkw平台币</option>
        </select>
    </p>
    <p>
        <span>来自游戏：</span>
        <select name="upperName" id="upperName" style="width: 90px;">
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
        <select name="specialName" id="specialName" style="width: 90px;">
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
        <select name="game" id="game" style="width: 90px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
            <?php } ?>
        </select>
        <span>渠道：</span>
        <select name="channel" id="channel" style="width: 160px;">
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
        <select name="apkNum" id="apkNum" style="width: 160px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['apk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['apk']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committeApknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['apk']->key => $_smarty_tpl->tpl_vars['apk']->value){
$_smarty_tpl->tpl_vars['apk']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['apk']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['apkNum']->value==$_smarty_tpl->tpl_vars['apk']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
</option>
            <?php } ?>
        </select>
        </p>
        <p>
        <span>区服ID：</span>
        <input type="text" placeholder="区服ID" name="serverId" value="<?php echo $_smarty_tpl->tpl_vars['serverId']->value;?>
" style="width: 147px;"/>
        <span style="width: 205px;">关联渠道：</span>
        <select name="linkChannelId" style="width: 160px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['linkChannels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['linkChannelId']->value==$_smarty_tpl->tpl_vars['key1']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
            <?php } ?>
        </select>
        <span>关联账号：</span>
        <input type="text" placeholder="请输入关联账号" name="linkUserName" value="<?php echo $_smarty_tpl->tpl_vars['linkUserName']->value;?>
" style="width: 147px;"/>
    </p>
	<table style="clear:both;margin-top:10px; float:right;width:98%;">
		<tr>
			<td align="left" style="width: 100px"><button type="submit" class="su inline" id="ccc" >查询</button></td>
			<td align="left">
				<button type="submit" class="su" value="report" id="report">导出</button><font><!--考虑服务器性能损耗，一次导出最多导出三千条--></font>
			</td>
		</tr>
	</table>	
</form>
<table class="list">
    <tr style="background-color:#CCCCCC;">
        <th>订单号</th>
        <th>账号</th>
        <th>渠道</th>
        <th>所属包体</th>
        <th>上级游戏名</th>
        <th>游戏</th>
        <th>服务器</th>
        <th>角色</th>
        <th>角色ID</th>
        <th>充值时间</th>
        <th>金额</th>
        <th>实付</th>
        <th>支付方式</th>
        <th>关联渠道</th>
        <th>关联账号</th>
        <th>达咖玩标识</th>
    </tr>
	<?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
	<tr >
        <td><font color="red"><?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
</font></td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['server'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['roleId'];?>
</td>
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['time'],"%y-%m-%d %H:%M");?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['money'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['realMoney'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['payTypeName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['linkChannelName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['linkUserName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['mark'];?>
</td>
        <!--<td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userName'],15,"..",true);?>
<br><font style="color:blue;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userid'],15,"..",true);?>
</font></td>
        <td><font color="red"><?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
</font></td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['upperName'],7,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['gameName'],8,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['server'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['server'],6,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
"><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['roleName'],7,"..",true);?>
</a></td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleId'];?>
"><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleId'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['roleId'],7,"..",true);?>
</a></td>
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['time'],"%y-%m-%d %H:%M");?>
</td>
        <td <?php if ($_smarty_tpl->tpl_vars['order']->value['channelname']){?>style="color:red;"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['money'];?>
/<?php echo $_smarty_tpl->tpl_vars['order']->value['gold'];?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['channelName'],4,"..",true);?>
</td>
        <td>
			<?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>

		</td>-->
    </tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="11">无渠道数据</td>
    </tr>
	<?php } ?>
</table>
<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
	$('#page').val(page);
	$('.searchbox').submit();
}
$("#ccc").click(function() {
	$("#operation").val("");
});
$("#report").click(function() {
	$("#operation").val("report");
	$('.searchbox').submit();
});
function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=linkData&a=linkOrder&page=" + page+"&years=<?php echo $_smarty_tpl->tpl_vars['years']->value;?>
&start=<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
&end=<?php echo $_smarty_tpl->tpl_vars['end']->value;?>
&payType=<?php echo $_smarty_tpl->tpl_vars['payType']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
&serverId=<?php echo $_smarty_tpl->tpl_vars['serverId']->value;?>
&linkChannelId=<?php echo $_smarty_tpl->tpl_vars['linkChannelId']->value;?>
&linkUserName=<?php echo $_smarty_tpl->tpl_vars['linkUserName']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>
<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });

</script>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>