<?php /* Smarty version Smarty-3.1.11, created on 2022-03-21 19:48:44
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\retention.html" */ ?>
<?php /*%%SmartyHeaderCode:139556238661c619d68-14173371%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e055eb94cf7b3b574ec3ffe600f5bd69c9f2205a' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\retention.html',
      1 => 1634897461,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '139556238661c619d68-14173371',
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
  'unifunc' => 'content_6238661caabe82_01389670',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6238661caabe82_01389670')) {function content_6238661caabe82_01389670($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
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
<?php if ($_smarty_tpl->tpl_vars['gid']->value!==10){?>
<h3>玩家留存</h3>
<p align="right" style="color:red; margin-right:30px; margin-top:10px;">
	快速导出&nbsp;
	<a href="javascript:void(0);" class="quick_query" data-time="today">今日</a>
	<a href="javascript:void(0);" class="quick_query" data-time="last_week">上周</a>
	<a href="javascript:void(0);" class="quick_query" data-time="current_week">本周</a>
	<a href="javascript:void(0);" class="quick_query" data-time="last_month">上月</a>
	<a href="javascript:void(0);" class="quick_query" data-time="current_month">本月</a>
</p>
<div class="clear"></div>
<form class="searchbox" action="/index.php?m=statistics&a=retention" method="post">
    <input type="hidden" name="page" value="1" id="page" />
    <input type="hidden" name="operation" value="" id="operation" />
    <?php if ($_smarty_tpl->tpl_vars['allow']->value==1){?>
    <p>
        <span style="width: 80px;">留存类型：</span>
        <select name="rType" style="width: 98px;">
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['rType']->value==1){?>selected="selected"<?php }?>>用户留存</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['rType']->value==2){?>selected="selected"<?php }?>>付费用户留存</option>
        </select>
    </p>
    <?php }?>
    <p>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==14||$_smarty_tpl->tpl_vars['gid']->value==17){?>
            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
        <?php }?>
		<span style="width: 80px;">来自游戏：</span>
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
            <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="uid" id="uid">
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
" <?php if ($_smarty_tpl->tpl_vars['game']->value==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>> <?php echo smarty_modifier_truncate(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['key1']->value),1,'',true);?>
 — <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
       		<?php } ?>
		</select>
		<span style="width: 80px;">渠道：</span>
		<select name="channel" id="channel" style="width: 160px;">
			<option value="">请选择</option>
        	<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
			<option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
       		<?php } ?>
		</select>
        <span>包号：</span>
        <select name="apkNum" id="apkNum" style="width: 160px;">
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
		<span style="width: 80px;">起始时间：</span>
		<input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
    </p>
    <P>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="su" value="list" id="list">查询</button>
		<button type="submit" class="su" value="report" id="report">导出</button>
	</p>
</form>
<?php }else{ ?>
<div style="font-size: 20px; color:red; margin:5% 0 0 30%;">无权限查看相关数据</div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='list'){?>
<div class="auto-layout-table">
    <table class="list" style="width: 90%;">
        <tr style="background-color:#CCCCCC;">
            <th>日期</th>
            <th>新增用户数</th>
            <th>次日留存</th>
            <th>三日留存</th>
            <th>四日留存</th>
            <th>五日留存</th>
            <th>六日留存</th>
            <th>七日留存</th>
            <th>双周留存</th>
            <th>月留存</th>
            <th>双月留存</th>
            <th>三月留存</th>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
        <tr>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['date'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['total'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_1'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_2'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_3'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_4'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_5'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_6'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_13'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_29'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_59'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value['admix_89'];?>
</td>
        </tr>
        <?php } ?>
        <?php if ($_smarty_tpl->tpl_vars['total_data']->value){?>
        <tr style="background-color:#CCCCCC;">
            <td>数据汇总</td>
            <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['total_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
            <!--<td><?php if ($_smarty_tpl->tpl_vars['row']->value){?><?php if ($_smarty_tpl->tpl_vars['total_data']->value[0]&&$_smarty_tpl->tpl_vars['key1']->value>0){?><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['row']->value/($_smarty_tpl->tpl_vars['count_data']->value-$_smarty_tpl->tpl_vars['key1']->value)));?>
%<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
<?php }?><?php }?></td>
            <td><?php if ($_smarty_tpl->tpl_vars['row']->value){?><?php if ($_smarty_tpl->tpl_vars['total_data']->value[0]&&$_smarty_tpl->tpl_vars['key1']->value>0){?><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['row']->value/$_smarty_tpl->tpl_vars['count_data']->value));?>
%<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
<?php }?><?php }?></td>-->
            <td><?php echo $_smarty_tpl->tpl_vars['row']->value;?>
</td>
            <?php } ?>
        </tr>
        <?php }?>
    </table>
</div>
<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$("#list").click(function() {
	$("#operation").val("list");
	$('.searchbox').submit();
});
$("#report").click(function() {
	$("#operation").val("report");
	$('.searchbox').submit();
});

$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
	$('#page').val(page);
	$("#list").click();
}
window.onload = function(){
	var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['limit']->value;?>
','gotoNext').GetText();
	document.getElementById('pager').innerHTML = pageStr;
}
</script>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['gid']->value==8){?>
<script>
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
        var uid = $('#uid').val();
		if(game == ''){
			$("#channel option[text!='']").remove();
			$("#channel").append('<option value="">请选择</option>').change();
			return false;
		}

		$.ajax({
			type: "POST",
			url: "/?m=sdkChannel&a=getGameChannels",
			data: "game="+game+"&channelId="+channel+"&uid="+uid,
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
<script>
$(function() {
    $('.quick_query').click(function() {
        var date = getDate( $(this).attr('data-time') );
        $('#start_date').val( date.start );
        $('#end_date').val( date.end );
        $("#list").click();
        return false;
    });
});
</script>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>