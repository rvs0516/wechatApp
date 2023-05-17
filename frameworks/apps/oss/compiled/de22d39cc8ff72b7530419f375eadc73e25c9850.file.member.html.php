<?php /* Smarty version Smarty-3.1.11, created on 2022-10-21 14:41:31
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\member.html" */ ?>
<?php /*%%SmartyHeaderCode:164416246b5408a89d7-25593910%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de22d39cc8ff72b7530419f375eadc73e25c9850' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\member.html',
      1 => 1665743386,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '164416246b5408a89d7-25593910',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6246b540e82da2_08277389',
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
<?php if ($_valid && !is_callable('content_6246b540e82da2_08277389')) {function content_6246b540e82da2_08277389($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
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
    	
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script src="/js/jquery-1.8.1.min.js"></script>
<?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
<h3>
<?php if ($_smarty_tpl->tpl_vars['uid']->value=='baohuan'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='luojunri'||$_smarty_tpl->tpl_vars['uid']->value=='yfdata'||$_smarty_tpl->tpl_vars['uid']->value=='jianjianxiang'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi'){?>
    <span><a href="/index.php?m=statistics&a=batches">账号批量处理</a>  /  <a href="/index.php?m=batch&a=batchRelation">账号批量关联</a></span>
<?php }?>
    账号列表
</h3>
<form class="searchbox" action="/index.php?m=statistics&a=member" method="post">
    <p>
        <span>来自游戏：</span>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==19||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==22){?>
            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
        <?php }?>
            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
        <?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
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
            <select name="game" id="game" style="width: 98px;">
        <?php }else{ ?>
            <select name="game" id="game">
        <?php }?>
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
" <?php if (($_smarty_tpl->tpl_vars['channel_array']->value==$_smarty_tpl->tpl_vars['key1']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
            <?php } ?>
        </select>
        <span>包号：</span>
        <select name="apkNum" id="apkNum">
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

    </p>
    <p>
        <span>账号：</span>
        <input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
" name="userid" id="userid" placeholder="请输入需要搜索的账号" />
        <span>渠道账号：</span>
        <input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['platformUserId']->value;?>
" name="platformUserId" id="platformUserId" placeholder="请输入需要搜索的渠道账号" />
        <span style="width: 194px;">输入省/市：</span>
        <input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" placeholder="请输入省/市" name="keywords" id="keywords">
        <span>注册时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
    </p>
    <p>
    <span>IP/phoneID：</span>
    <input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['info']->value;?>
" name="info" placeholder="请输入IP/phoneID" />
    </p>
    <table style="clear:both;margin-top:10px; float:right;width:100%;">
        <tr>
            <td align="left"><button type="submit" class="su inline" style=" margin-left: 30px;">查询</button><font style="color:red;">&nbsp;*&nbsp;单击渠道账号或设备ID即可复制</font></td>
        </tr>
    </table>
</form>
<table class="list">
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==6||$_smarty_tpl->tpl_vars['gid']->value==9||$_smarty_tpl->tpl_vars['gid']->value==12||$_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22){?>
        <tr style="background-color:#CCCCCC;">
            <th width="7%">账号</th>
            <th width="10%">渠道账号</th>
            <th width="5%">包号</th>
            <th width="9%">来自游戏</th>
            <th width="7%">渠道</th>
            <th width="5%">区服</th>
            <th width="10%">角色名称</th>
            <th width="10%">注册时间</th>
            <th width="10%">IP</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==6||$_smarty_tpl->tpl_vars['gid']->value==12||$_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22){?>
                <th width="12%">设备信息</th>
                <th width="14%">操作</th>
            <?php }else{ ?>
                <th width="15%">设备信息</th>
                <th width="7%">操作</th>
            <?php }?>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
        <tr>
            <td>
            <?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='baohuan'||$_smarty_tpl->tpl_vars['uid']->value=='luojunri'||$_smarty_tpl->tpl_vars['uid']->value=='yfdata'||$_smarty_tpl->tpl_vars['uid']->value=='jianjianxiang'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi')){?>
            <a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['password'];?>
" style="text-decoration:none; color: #444;"><?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
</a>
            <?php }else{ ?>
            <?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>

            <?php }?>
            </td>
            <td><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['platformUserId'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['platformUserId'],13,"..",true);?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['gameName'],7,"..",true);?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
</td>
            <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['serverId'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['serverId'],7,"..",true);?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
</td>
            <td style="color: red;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['joinTime'],"%y-%m-%d %H:%M");?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['loginIp'];?>
</td>
            <td><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['loginPhoneId'];?>
" style="text-decoration:none; color: #444;"><?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='baohuan'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi')&&$_smarty_tpl->tpl_vars['order']->value['nickName']){?><?php echo $_smarty_tpl->tpl_vars['order']->value['nickName'];?>
| <?php }?><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['loginPhoneId'],13,"..",true);?>
</a></td>
            <td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==9||$_smarty_tpl->tpl_vars['gid']->value==12||$_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22){?>
            <a href="index.php?m=vipGuest&a=vipGuest&operation=add&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
&gameName=<?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
&gameAlias=<?php echo $_smarty_tpl->tpl_vars['order']->value['gameAlias'];?>
&loginTime=<?php echo $_smarty_tpl->tpl_vars['order']->value['loginTime'];?>
&channelName=<?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
&channelId=<?php echo $_smarty_tpl->tpl_vars['order']->value['channelId'];?>
&joinTime=<?php echo $_smarty_tpl->tpl_vars['order']->value['joinTime'];?>
">VIP|</a>
            <?php }?>
            <?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='baohuan'||$_smarty_tpl->tpl_vars['uid']->value=='luojunri'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='yfdata'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi'||$_smarty_tpl->tpl_vars['uid']->value=='jianjianxiang')){?>
                <a href="index.php?m=statistics&a=member&operation=bindingRelieve&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
" class="relieve">解绑|</a>
                <a href="index.php?m=statistics&a=member&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
&platformUserId=<?php echo $_smarty_tpl->tpl_vars['order']->value['platformUserId'];?>
&channelId=<?php echo $_smarty_tpl->tpl_vars['order']->value['channelId'];?>
&operation=edit">改密</a>&nbsp;|
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==21){?>
                <a href="index.php?m=statistics&a=member&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
&platformUserId=<?php echo $_smarty_tpl->tpl_vars['order']->value['platformUserId'];?>
&channelId=<?php echo $_smarty_tpl->tpl_vars['order']->value['channelId'];?>
&operation=edit">改密</a>&nbsp;|
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==6||$_smarty_tpl->tpl_vars['gid']->value==12){?>
                <a href="index.php?m=sdkGame&a=baned&banedKey=<?php echo $_smarty_tpl->tpl_vars['order']->value['loginPhoneId'];?>
" target="_blank">屏蔽|</a>
            <?php }?>
                <a  href="javascript:void(0)" onclick="onModified(this)" id="<?php echo $_smarty_tpl->tpl_vars['order']->value['more'];?>
">更多</a>
            </td>
        </tr>
        <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
        <tr>
            <td colspan="6">无数据</td>
        </tr>
        <?php } ?>
    <?php }else{ ?>
        <tr style="background-color:#CCCCCC;">
            <th width="10%">账号</th>
            <th width="15%">渠道账号</th>
            <th width="8%">包号</th>
            <th width="10%">来自游戏</th>
            <th width="10%">渠道</th>
            <th width="15%">注册时间</th>
            <th width="15%">IP</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==11){?>
                <th width="12%">phoneID</th>
                <th width="5%">操作</th>
            <?php }else{ ?>
                <th width="17%">phoneID</th>
            <?php }?>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
        <tr>
            <td>
            <?php if ($_smarty_tpl->tpl_vars['uid']->value=='zhangyi'){?>
            <a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['password'];?>
" style="text-decoration:none; color: #444;"><?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
</a>
            <?php }else{ ?>
            <?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>

            <?php }?>
            </td>
            <td><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['platformUserId'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['platformUserId'],15,"..",true);?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
</td>
            <td style="color: red;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['joinTime'],"%y-%m-%d %H:%M");?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['loginIp'];?>
</td>
            <td><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['loginPhoneId'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['loginPhoneId'],13,"..",true);?>
</a></td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==11){?>
                <td>
                <a  href="javascript:void(0)" onclick="onModified(this)" id="<?php echo $_smarty_tpl->tpl_vars['order']->value['more'];?>
">更多</a>
                </td>
            <?php }?>
        </tr>
        <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
        <tr>
            <td colspan="6">无数据</td>
        </tr>
        <?php } ?>
    <?php }?>
</table>

<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$('.relieve').click(function() {
    return confirm('确定要解除用户绑定？');
});
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
    $('#page').val(page);
    $('.searchbox').submit();
}
function gotoNext(page,pagesize){
        window.location.href = "/index.php?m=statistics&a=member&page=" + page+"&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel_array']->value;?>
&userid=<?php echo $_smarty_tpl->tpl_vars['userid']->value;?>
&keywords=<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&info=<?php echo $_smarty_tpl->tpl_vars['info']->value;?>
&gid=<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
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
<script>
function b64Decode(str) {
  return decodeURIComponent(atob(str));
}
function onModified(btn){
var btnData = new Array(); //定义一数组
var atob = b64Decode(btn.id);
btnData = atob.split("|"); //字符分割 
var btnUseName = btnData[0];
var btnCall = btnData[1];
var idcard = btnData[2];
var gid = document.getElementById("gid").value;
        $.ajax({
            type: "POST",
            url: "/?m=statistics&a=getUserRole",
            data: "userName="+btnUseName,
            dataType: 'text',

            success: function(result){
                var res = new Array(); //定义一数组
                res = result.split("||"); //字符分割 
                if (btnCall != 0) {
                    if (gid == 2 || gid == 11 || gid == 9) {
                        resStr = '绑定的手机：已绑定\n';
                    }else{
                        resStr = '绑定的手机：' + btnCall + '\n';
                    }
                }else{
                    resStr = '';
                }
                if (idcard && (gid == 1 || gid == 12)) {
                    resStr += '身份证：' + idcard + '\n';
                }
                for (var i = 0; i < res.length; i++) {
                    resStr +=  res[i] + '\n';
                }
                alert(resStr);
            }
        });
}
</script>
<?php if ($_smarty_tpl->tpl_vars['gid']->value==8){?>
<script type="text/javascript">
$(function() {
    get_servers();
    $("#game").change(function(){
        get_servers();
        return false;
    });
    get_apkNum();
    $("#channel").change(function(){
        get_apkNum();
        return false;
    });

    function get_servers() {
        var game = $('#game').val();
        var channel = $('#channel').val();
        if(game == ''){
            $("#channel option[text!='']").remove();
            $("#channel").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkChannel&a=getGameChannels",
            data: "game="+game+"&channelId="+channel,
            dataType: 'text',

            success: function(result){
                $("#channel option[text!='0']").remove();
                $("#channel").append(result);
            }
        });
        return false;
    }
    function get_apkNum() {
        var game = $('#game').val();
        var channel = $('#channel').val();
        var apkNum = $('#apkNum').val();
        if(channel == ''){
            $("#apkNum option[text!='']").remove();
            $("#apkNum").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=statistics&a=getApkNum",
            data: "game="+game+"&channelId="+channel+"&apkNum="+apkNum,
            dataType: 'text',

            success: function(result){
                $("#apkNum option[text!='0']").remove();
                $("#apkNum").append(result);
            }
        });
        return false;
    }
});
</script>
<?php }?>
<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='edit'){?>
<style>
    .insert{ width: 75px; margin-top: 10px; height: 30px; text-align: center; line-height: 25px; color: #FFF; font-size: 12px; display: block; background: url(/static/submit.jpg) no-repeat;text-decoration:none;}
    .insert:hover { text-decoration: none;}
    .scroll-body{
        max-height: 150px !important;
    }
    .searchbox .label-content .xm-label-block {
        width: auto;
    }
    /* 关联游戏 */
    .label-content{
        margin-top: 42px !important;
        padding: unset !important;
    }
    /* 查找按钮 */
    #userToGame{
        width: 30px;
        height: 30px;
        cursor: pointer;
    }
    #hide{
        display: none;
    }
    /* 收起按钮 */
    #stop{
        width: 30px;
        color: #f00;
        display:none;
    }
</style>
<link href="../js/layui/css/layui.css" rel="stylesheet">
<h3>
    修改玩家信息
</h3>
<br/>
<form class="layui-form"  action="/index.php?m=statistics&a=member&operation=save&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" method="post">
    
    <div class="layui-form-item">
        <label class="layui-form-label">玩家账号：</label>
        <div class="layui-input-inline" >
            <input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" disabled="disabled" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"  id="userToGame">查找</div>
        <div class="layui-form-mid layui-word-aux"  id="stop" >收起</div>
    </div>
    <div id="gameNameLists" style="display: none;"></div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码：</label>
        <div class="layui-input-inline">
            <input type="password" name="password" placeholder="请输入密码"  class="layui-input">
        </div>
        <font color="red">注：若密码为空，则不修改密码。</font>
    </div>

    <?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='baohuan'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='yfdata'||$_smarty_tpl->tpl_vars['uid']->value=='luojunri'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi'||$_smarty_tpl->tpl_vars['uid']->value=='jianjianxiang'||$_smarty_tpl->tpl_vars['gid']->value==21)){?>
    <div class="layui-form-item">
        <label class="layui-form-label">用户状态：</label>
        <div class="layui-input-block "  style="width: 190px;">
          <select name="type" lay-filter="aihao">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['type']->value==1){?>selected="selected"<?php }?>>回流</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['type']->value==2){?>selected="selected"<?php }?>>二次流失</option>
          </select>
        </div>
    </div> 

    <div class="layui-form-item">
        <label class="layui-form-label">关联游戏：</label>
        <div id="Shuttle_box" class="demo-transfer" name="assGame"></div>
    </div>

    <div class="layui-form-item ">
        <label class="layui-form-label">关联账号：</label>
        <div class="layui-input-inline tags plus-tag-add" style="display: flex;">
            <input type="text"  class="layui-input inputTags"  id="whiteListIP" style="margin-right: 5px;" placeholder="请输入账号并按回车确认"/>
            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm" style="height: 38px;"><i class="layui-icon"></i></button>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <p class="plus-tag tagbtn clearfix" id="myTags" style="margin-left: 50px;width: 764px;">
            <?php if ($_smarty_tpl->tpl_vars['assUserName']->value){?>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['assUserName']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
            <a value="-1" title="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" href="javascript:void(0);"><span><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</span><em></em></a>
            <?php } ?>
            <?php }?>
        </p>
    </div>

    <input type="hidden" name="assGame" value="<?php echo $_smarty_tpl->tpl_vars['assGame']->value;?>
" id="assGame"/>
    <input type="hidden" id="assUserName" name="assUserName" value="<?php echo $_smarty_tpl->tpl_vars['assUserName']->value;?>
"    >
    <input type="hidden" name="platformUserId" value="<?php echo $_smarty_tpl->tpl_vars['platformUserId']->value;?>
" />
    <input type="hidden" name="channelId" value="<?php echo $_smarty_tpl->tpl_vars['channelId']->value;?>
" />
    <input type="hidden" name="is_new" value="<?php echo $_smarty_tpl->tpl_vars['is_new']->value;?>
" />

    <?php }?>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" lay-demotransferactive="getData" id="insert">提交</button>
        </div>
    </div> 
</form>

<!-- 添加input自定义标签 -->

<script type="text/javascript" src="/js/jQueryLabel/js/tab.js"></script>
<link href="/js/jQueryLabel/css/tab.css" type="text/css" rel="stylesheet" />
<!-- 关联游戏穿梭框 -->
<script src="../js/layui/layui.js"></script>
<script>
    layui.use(['transfer', 'layer', 'util'], function(){
        var $ = layui.$
        ,transfer = layui.transfer
        ,layer = layui.layer
        ,util = layui.util;

        //全部游戏
        var data = [
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gameLists']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                {
                    value:"<?php echo $_smarty_tpl->tpl_vars['item']->value['alias'];?>
",
                    title:"<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
"
                },
            <?php } ?>
        ]

        //已关联的游戏
        var value = [
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['assGame']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                "<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
",
            <?php } ?>
        ]

        // 穿梭框基本配置
        transfer.render({
            elem: '#Shuttle_box'
            ,data: data
            ,title: ['未选择的游戏/回车搜索', '已选择的游戏/回车搜索']
            ,showSearch: true
            ,width:450
            ,height:450
            ,id:"key123"
            ,value: value
        })

        //批量办法定事件
        util.event('lay-demoTransferActive', {
            getData: function(othis){

                //清空穿梭框的搜索内容
				var e = jQuery.Event("keyup");
				$('.layui-transfer-box[data-index="0"] input[placeholder="关键词搜索"]').removeAttr("value");
				$('.layui-transfer-box[data-index="1"] input[placeholder="关键词搜索"]').removeAttr("value");
				$('.layui-transfer-box[data-index="0"] input[placeholder="关键词搜索"]').trigger(e);
				$('.layui-transfer-box[data-index="1"] input[placeholder="关键词搜索"]').trigger(e);

                //取消默认勾选功能
                $('.layui-transfer-box[data-index="0"] div[lay-skin="primary"]').removeClass("layui-form-checked");
				$('.layui-transfer-box[data-index="1"] div[lay-skin="primary"]').removeClass("layui-form-checked");

                var getData = transfer.getData('key123'); //获取右侧穿梭数据

                // 关联游戏数据
                var assGame = ''
                for (let i = 0; i < getData.length; i++) {

                    if (i===getData.length-1){
                        assGame += getData[i].value;
                    }else{
                        assGame += getData[i].value +','
                    }

                }
                $("#assGame").attr("value",assGame);
                
                // 关联账号数据
                var assGameArray = [];  
                var assUserName

                if ( $('#myTags a').length > 1 ) {
                $("#myTags a span").each(function(){
                    assGameArray.push( $(this).html() );
                });
                    assUserName = getStr(assGameArray)
                }else{
                    assUserName = $("#myTags a span").html()
                }
                $('#assUserName').val(assUserName)

                // 提交表单
                var r = confirm('你確定要修改该玩家的信息？');
                if (r == true) {
                    $('.layui-form').submit();
                }
    
            }
            
        });
    })
</script>
<script>
    // 查找玩家账号登录过的游戏
    $('#userToGame').click(function(){
        // 展示收起按钮
        $("#stop").css('display', 'inline-block');

        // 清除掉原先的展示数据
        $("#gameNameLists").empty()

        var spanHtml = '<div>登录过的游戏:</div>';
        $('#gameNameLists').append(spanHtml)

        // 展示账号登录过的游戏
        $("#gameNameLists").css('display', 'block');
        // 获取玩家账号
        var userName = $('input[name="userName"]').val()
        $.ajax({
            type: "post",
            url: "/index.php?m=statistics&a=member",
            data: {
                userNameToGame : userName
            },
            dataType: "json",
            success: function (data) {
                for (let i = 0; i < data.length; i++) {
                    if(i == 0){
                        var gameHtml = '<ul style="margin-left: 100px;">'+
                                   '<li style="color: #141e91;margin-top: -37px;" class="btn" data-clipboard-text="' + data[i] + '">' + data[i] + '</li>'+
                                   '</ul>'
                    }else{
                        var gameHtml = '<ul style="margin-left: 100px;">'+
                                   '<li style="color: #141e91;" class="btn" data-clipboard-text="' + data[i] + '">' + data[i] + '</li>'+
                                   '</ul>'
                    }
                    $('#gameNameLists').append(gameHtml)
                }
            }
        });
    })
    // 收起账号登录过的游戏
    $('#stop').click(function(){
        $("#gameNameLists").css('display', 'none');
        $("#stop").css('display', 'none');
    })
    //遍历数组，加逗号
    function getStr(assGameArray){
            var str="";
            for (var i = 0; i < assGameArray.length ; i++) {
                if (i===assGameArray.length-1){
                    str+=assGameArray[i];
                }else{
                    str+=assGameArray[i]+",";
                }
            }
            return str;
    }
</script>

<?php }?>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>