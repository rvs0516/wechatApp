<?php /* Smarty version Smarty-3.1.11, created on 2022-02-15 12:31:39
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\profit.html" */ ?>
<?php /*%%SmartyHeaderCode:2495361529916021616-47991748%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a7a1b3e8b8a13da993820672cbd0d415eda973f6' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\profit.html',
      1 => 1644899492,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2495361529916021616-47991748',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_615299167286f1_24877209',
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
<?php if ($_valid && !is_callable('content_615299167286f1_24877209')) {function content_615299167286f1_24877209($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
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
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<script src="/js/pop.js"></script>
<style>
    .showHide{ color:#3d203f; text-decoration:none; position:relative; left:0; top:0; z-index:10; color:green;}
    .showHide p{ display:none;}
    .showHide:hover{ display:block; text-decoration:none; height:100%; position:relative; z-index:1000 !important;}
    .showHide:hover p{ display:block; color:#3d203f; background:#cff0fc; position:absolute; left:30px; top:30px; white-space:normal; width:500px;; padding:10px; box-shadow:1px 1px 10px #333; cursor:pointer;}
</style>
<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=''){?>
<h3>
    游戏利润
    <?php if ($_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'){?>
    <span><a  href="#" class="showbox">数据导入</a></span>
    <?php }?>
</h3>

<form class="searchbox" action="/index.php?m=statistics&a=profit" method="post">
    <p>
        <span>来自游戏：</span>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==17){?>
            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
            <?php }?>
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
" <?php if (($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['key1']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
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
        <span>时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})" style="width: 128px;"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )" style="width: 128px;">
        <span>类型：</span>
        <select name="type">
            <option value="">请选择</option>
            <option value="1" <?php if (($_smarty_tpl->tpl_vars['type']->value==1)){?>selected="selected"<?php }?>>联运</option>
            <option value="2" <?php if (($_smarty_tpl->tpl_vars['type']->value==2)){?>selected="selected"<?php }?>>广告/投放</option>
            <option value="3" <?php if (($_smarty_tpl->tpl_vars['type']->value==3)){?>selected="selected"<?php }?>>cps</option>
        </select>
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==1){?>
        <span>数据来源：</span>
        <select name="source" id="source"  style="width: 200px;">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['source']->value=='1'){?>selected="selected"<?php }?>>导入数据</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['source']->value=='2'){?>selected="selected"<?php }?>>SDK数据</option>
        </select>
        <?php if ($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'){?>
        <span>数据类型：</span>
        <select name="status" id="status"  style="width: 200px;">
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value=='1'){?>selected="selected"<?php }?>>常规数据</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value=='2'){?>selected="selected"<?php }?>>QA数据</option>
        </select>
        <?php }?>
    <?php }?>
    </p>
    <table style="clear:both;margin-top:10px; float:right;width:100%;">
        <tr>
            <td align="left"><button type="submit" class="su inline" style=" margin-left: 30px;">查询</button>
        </tr>
    </table>
    <p style="margin-left: 10px; color: red;">*&nbsp;&nbsp;每天凌晨4点更新前一天统计的数据<?php echo $_smarty_tpl->tpl_vars['refine']->value;?>
</p>
</form>
<table class="list">
<?php if ($_smarty_tpl->tpl_vars['refine']->value==1){?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <th>日期</th>
            <th>总流水</th>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>真实充值</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><th>CP分成</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><th>渠道支出（含折扣）</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>分成后流水</th><?php }?>
            <th>额外支出</th>
            <th>投放支出</th>
            <th>实际支出</th>
            <th>GS收入</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <th>项目支出</th>
            <?php }?>
            <th>公司支出</th>
            <th><?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>净利润<?php }else{ ?>分成后流水<?php }?></th>
            <th>利润率</th>
        <?php }else{ ?>
            <th>日期</th>
            <th>总流水</th>
            <th>CP分成</th>
            <th>渠道支出（含折扣）</th>
        <?php }?>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profitList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
    <tr>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gamePay'];?>
</td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['coPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td>
        <?php }?>
    </tr>
    <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="20">无相关数据</td>
    </tr>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['pay'];?>
</td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['coPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
<?php }elseif($_smarty_tpl->tpl_vars['refine']->value==2){?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <th>日期</th>
            <th>游戏</th>
            <th>游戏流水</th>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>真实充值</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><th>CP分成</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><th>渠道支出（含折扣）</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>分成后流水</th><?php }?>
            <th>额外支出</th>
            <th>投放支出</th>
            <th>实际支出</th>
            <th>GS收入</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <th>项目支出</th>
            <?php }?>
            <th><?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>净利润<?php }else{ ?>分成后流水<?php }?></th>
            <th>利润率</th>
        <?php }else{ ?>
            <th>日期</th>
            <th>游戏</th>
            <th>游戏流水</th>
            <th>CP分成</th>
            <th>渠道支出（含折扣）</th>
        <?php }?>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profitList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
    <?php if ($_smarty_tpl->tpl_vars['order']->value['pay']&&$_smarty_tpl->tpl_vars['checkRoot']->value!=4&&!$_smarty_tpl->tpl_vars['gameStr']->value){?>
    <tr style="color:green;background-color:#eff2d0;">
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
        <td><a class="showHide">项目支出:<?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['remark'],10,"..",true);?>
<?php if ($_smarty_tpl->tpl_vars['order']->value['remark']){?><p><?php echo $_smarty_tpl->tpl_vars['order']->value['remark'];?>
</p><?php }?></a></td>
        <td></td>
        <td></td>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td></td><?php }?>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td></td><?php }?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td></td><?php }?>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['pay'];?>
</td>
        <td></td>
        <td></td>
    </tr>
    <?php }?>
    <tr>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['specialName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <td></td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['specialName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td>
        <?php }?>
    </tr>
    <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="20">无相关数据</td>
    </tr>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17||($_smarty_tpl->tpl_vars['gid']->value==17&&!$_smarty_tpl->tpl_vars['gameStr']->value)){?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['pay'];?>
</td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
<?php }elseif($_smarty_tpl->tpl_vars['refine']->value==3){?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <th>日期</th>
            <th>游戏</th>
            <th>游戏流水</th>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>真实流水</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><th>CP分成</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><th>渠道支出（含折扣）</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>分成后流水</th><?php }?>
            <th>额外支出</th>
            <th>投放支出</th>
            <th>实际支出</th>
            <th>GS收入</th>
            <th><?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>净利润<?php }else{ ?>分成后流水<?php }?></th>
            <th>利润率</th>
        <?php }else{ ?>
            <th>日期</th>
            <th>游戏</th>
            <th>游戏流水</th>
            <th>CP分成</th>
            <th>渠道支出（含折扣）</th>
        <?php }?>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profitList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
    <tr>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['name'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['income'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['name'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td>
        <?php }?>
    </tr>
    <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="20">无相关数据</td>
    </tr>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['income'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
<?php }elseif($_smarty_tpl->tpl_vars['refine']->value==4){?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <th>日期</th>
            <th>游戏</th>
            <th>渠道</th>
            <th>包号</th>
            <th>游戏流水</th>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>真实充值</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><th>CP分成</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><th>渠道支出（含折扣）</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>分成后流水</th><?php }?>
            <th>额外支出</th>
            <th>投放支出</th>
            <th>实际支出</th>
            <th>GS收入</th>
            <th><?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>净利润<?php }else{ ?>分成后流水<?php }?></th>
            <th>利润率</th>
            <?php if ($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'){?>
                <th>操作</th>
            <?php }?>
        <?php }else{ ?>
            <th>日期</th>
            <th>游戏</th>
            <th>渠道</th>
            <th>包号</th>
            <th>游戏流水</th>
            <th>CP分成</th>
            <th>渠道支出（含折扣）</th>
        <?php }?>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profitList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
    <tr>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
<?php if ($_smarty_tpl->tpl_vars['order']->value['type']=='2'){?>(投放)<?php }?></td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmount'];?>
<?php if ($_smarty_tpl->tpl_vars['uid']->value=='chenjh'){?>(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmountss'];?>
</font>)<?php }?></td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['profit'];?>
</td><?php }?>
            <td id="<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['order']->value['exPay'];?>
</td>
            <td <?php if (($_smarty_tpl->tpl_vars['uid']->value=='guojian'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='wangyinping'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['gid']->value==17)){?> class="change2" <?php }?> id="<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <td <?php if (($_smarty_tpl->tpl_vars['uid']->value=='guojian'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='wangyinping'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['gid']->value==17)){?> class="change" <?php }?> id="<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['order']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['income'];?>
</td>
            <td id="final_<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['order']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['profitMargin'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'){?>
                <td><a href="index.php?m=statistics&a=changeDataStatus&id=<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
&model=profit" id="qa">QA</a></td>
            <?php }?>
        <?php }else{ ?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
<?php if ($_smarty_tpl->tpl_vars['order']->value['type']=='2'){?>(投放)<?php }?></td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td>
        <?php }?>
    </tr>
    <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="20">无相关数据</td>
    </tr>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profit'];?>
</td><?php }?>
            <td id="sum_ex"><?php echo $_smarty_tpl->tpl_vars['sum']->value['exPay'];?>
</td>
            <td id="sum_ad"><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td id="sum_act"><?php echo $_smarty_tpl->tpl_vars['sum']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['income'];?>
</td>
            <td id="sum_final"><?php echo $_smarty_tpl->tpl_vars['sum']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
<?php }elseif($_smarty_tpl->tpl_vars['refine']->value==5){?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <th>游戏</th>
            <th>游戏流水</th>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>真实充值</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><th>CP分成</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><th>渠道支出（含折扣）</th><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><th>分成后流水</th><?php }?>
            <th>额外支出</th>
            <th>投放支出</th>
            <th>实际支出</th>
            <th>GS收入</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17){?>
            <th>项目支出</th>
            <?php }?>
            <th><?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>净利润<?php }else{ ?>分成后流水<?php }?></th>
            <th>利润率</th>
        <?php }else{ ?>
            <th>游戏</th>
            <th>游戏流水</th>
            <th>CP分成</th>
            <th>渠道支出（含折扣）</th>
        <?php }?>
    </tr>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profitList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
    <tr>
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['order']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17){?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['pay'];?>
</td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['cpAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['cpRate'];?>
</font>)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['order']->value['channelAmount'];?>
(<font style="color:green;"><?php echo $_smarty_tpl->tpl_vars['order']->value['channelRate'];?>
</font>)</td>
        <?php }?>
    </tr>
    <?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="20">无相关数据</td>
    </tr>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=4){?>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['disAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=3){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value!=2){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td><?php }?>
            <?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?><td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profit'];?>
</td><?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['exPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['actualPay'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['income'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value!=17){?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['pay'];?>
</td>
            <?php }?>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['final'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['profitMargin'];?>
</td>
        <?php }else{ ?>
            <td></td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['cpAmount'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['sum']->value['channelAmount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
<?php }?>
    <!--隐藏域 start-->
    <div id="popbg"></div>
    <div class="pop fastDopost">
        <form action="/index.php?m=statistics&a=orderImport&target=profit" method="post" enctype="multipart/form-data">
            <input name="cardid" id="thisComment" type="hidden" value="" />
            <h3><span><a href="#" class="popclose">关闭</a></span>导入订单数据</h3>
            <p ><input name="file[]" id="iconfile" type="file" style=" height:25px; line-height:25px; display:block; margin: 20px 0px 20px 25px;" /></p>
            <p align="right"><button type="submit" class="su popsubmit">提交</button></p>
        </form>
    </div>
    <!--隐藏域 end-->
</table>
<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$('#qa').click(function() {
    return confirm('数据属性将修改，确定要执行？');
});
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
    $('#page').val(page);
    $('.searchbox').submit();
}
function gotoNext(page,pagesize){
        window.location.href = "/index.php?m=statistics&a=profit&page=" + page+"&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&type=<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
&source=<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
";
    }
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>
<?php if ($_smarty_tpl->tpl_vars['uid']->value=='guojian'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='wangyinping'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['gid']->value==17){?>
<script>
$(function() {
    $(".change").click(function() {
        var td = $(this);
        var txt = td.text();
        var id = td.attr("id");
        var final = document.getElementById("final_"+id);
        var sum_final = document.getElementById("sum_final");
        var sum_final_in = sum_final.innerText;
        var sum_act = document.getElementById("sum_act");
        var sum_act_in = sum_act.innerText;
        var input = $("<input type='text'value='" + txt + "'/>");
        td.html(input);
        input.click(function() { return false; });
        //获取焦点
        input.trigger("focus");
        input.blur(function() {
        var newtxt = $(this).val();
        if(isNaN(newtxt)){
            alert('请填写数字');
    　　　     return false; 
        }else if(newtxt == ''){
            newtxt = 0;
        }
        var final_in = final.innerText;
        //判断文本有没有修改
        if (newtxt != txt) {
            $.ajax({
                type: "POST",
                url: "index.php?m=statistics&a=exPayChange",
                data:"id=" + id + "&actualPay=" + newtxt,
                success:function (result) {   
                    var str = JSON.parse(result);
                    if(str['status'] == "15"){
                        td.html(newtxt);
                        final.innerText = Number(final_in) + Number(txt) - Number(newtxt);
                        sum_final.innerText = Number(sum_final_in) + Number(txt) - Number(newtxt);
                        sum_ex.innerText = Number(sum_act_in) - Number(txt) + Number(newtxt);
                    }
                    else { 
                        alert('没有对应的数据');
                        td.html(txt);
                    }
                },
                error:function () {            
                    alert("系统出错了。。。");
                },
            });
        }else{
            td.html(newtxt);
        }
        });
    });
}); 
$(function() {
    $(".change2").click(function() {
        var td = $(this);
        var txt = td.text();
        var id = td.attr("id");
        var final = document.getElementById("final_"+id);
        var sum_final = document.getElementById("sum_final");
        var sum_final_in = sum_final.innerText;
        var sum_ad = document.getElementById("sum_ad");
        var sum_ad_in = sum_ad.innerText;
        var input = $("<input type='text'value='" + txt + "'/>");
        td.html(input);
        input.click(function() { return false; });
        //获取焦点
        input.trigger("focus");
        input.blur(function() {
        var newtxt = $(this).val();
        if(isNaN(newtxt)){
            alert('请填写数字');
    　　　     return false; 
        }else if(newtxt == ''){
            newtxt = 0;
        }
        var final_in = final.innerText;
        //判断文本有没有修改
        if (newtxt != txt) {
            $.ajax({
                type: "POST",
                url: "index.php?m=statistics&a=exPayChange",
                data:"id=" + id + "&adPay=" + newtxt,
                success:function (result) {   
                    var str = JSON.parse(result);
                    if(str['status'] == "15"){
                        td.html(newtxt);
                        /*final.innerText = Number(final_in) + Number(txt) - Number(newtxt);
                        sum_final.innerText = Number(sum_final_in) + Number(txt) - Number(newtxt);
                        sum_ad.innerText = Number(sum_ad_in) - Number(txt) + Number(newtxt);*/
                    }
                    else { 
                        alert('没有对应的数据');
                        td.html(txt);
                    }
                },
                error:function () {            
                    alert("系统出错了。。。");
                },
            });
        }else{
            td.html(newtxt);
        }
        });
    });
}); 
</script>
<?php }?>
<?php }else{ ?>
<div style="font-size: 20px; color:red; margin:5% 0 0 30%;">无权限查看相关数据</div>
<?php }?>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>