<?php /* Smarty version Smarty-3.1.11, created on 2022-10-19 15:07:42
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\platformRecharge.html" */ ?>
<?php /*%%SmartyHeaderCode:299186152d24101d1e6-56343124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '023656ea78f314ff2eb683409009503883462f82' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\platformRecharge.html',
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
  'nocache_hash' => '299186152d24101d1e6-56343124',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6152d241259741_45231706',
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
<?php if ($_valid && !is_callable('content_6152d241259741_45231706')) {function content_6152d241259741_45231706($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
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
    	
<script type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script src="/js/highcharts.js"></script>
<script src="/js/chart.js"></script>
<?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<?php }?>
<div class="content" style="width: 98%; margin-top: -1%; margin-left: -1%">
	<h3>充值金额走势</h3>
	<p class="quick">
		<a style="color: red;">快速查看</a>：
		<a href='javascript:void(0);' onclick="searchTime(0, 'day');">今日</a>
		<a href='javascript:void(0);' onclick="searchTime(1, 'day');">昨日</a>
		<a href='javascript:void(0);' onclick="searchTime(2, 'day');">前日</a>
		<a href='javascript:void(0);' onclick="searchTime(0, 'week');">本周</a>
		<a href='javascript:void(0);' onclick="searchTime(0, 'month');">本月</a>
		<a href='javascript:void(0);' onclick="searchTime(1, 'month');">前一个月</a>
	</p>
	<form name="form1" id="form1" class="searchbox" style="margin:0;">
		<input type="hidden" name="m" value="statistics" />
		<input type="hidden" name="a" value="platformRecharge" />
		<input type="hidden" name="ajax" value="getPlatformRecharge" />
		<input type="hidden" name="qd" value="<?php if ($_GET['channel']){?><?php echo $_GET['channel'];?>
<?php }?>" />
            <p>
                <span>来自游戏：</span>
                <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==22){?>
		            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
		            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
		        <?php }?>
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
		            <select name="game" id="game" style="width: 200px;">
		        <?php }?>
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['alias'];?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value==$_smarty_tpl->tpl_vars['item']->value['alias']){?>selected="selected"<?php }?>> <?php echo smarty_modifier_truncate(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['item']->value['alias']),1,'',true);?>
 — <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</option>
                <?php } ?>
                </select>
                <span style="width: 80px;">渠道：</span>
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
                <span>起始时间：</span>
                <input name="start_date" id="start_date" type="text" class="wdate" value="<?php if ($_GET['start']!=''){?><?php echo $_GET['start'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['bef_time']->value;?>
<?php }?>" onclick="var end_date=$dp.$('end_date');WdatePicker({onpicked:function(){end_date.focus();},minDate:'#F{($dp.$D(\'end_date\',{y:-1,d:0}))}',maxDate:'#F{$dp.$D(\'end_date\')}'})" style="width: 83px;"/>
				至
				<input name="end_date" id="end_date" type="text" class="wdate" value="<?php if ($_GET['end']!=''){?><?php echo $_GET['end'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['ent_time']->value;?>
<?php }?>" onclick="WdatePicker({minDate:'#F{($dp.$D(\'start_date\',{d:0,H:0}))}',maxDate:'#F{($dp.$D(\'start_date\',{y:1,d:0}))}'} )" style="width: 83px;"/>
			</p>
		    <p>
				<input id="sub" type="button" value="查詢" class="search" style=" margin-left: 20px;" />
			</p>
	</form>
</div>
<div class="clear"></div>
<?php if ($_smarty_tpl->tpl_vars['uid']->value!='gsadmin'&&$_smarty_tpl->tpl_vars['gid']->value!=14&&$_smarty_tpl->tpl_vars['gid']->value!=16&&$_smarty_tpl->tpl_vars['gid']->value!=19){?>
<span id="charttip"></span>
<div id="chart">
	<div id="platform" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</div>
<div class="clear"></div>
<table border="0" cellpadding="0" cellspacing="0" class="list" style="display:none;" id="chartTable">
</table>
<?php }else{ ?>
<span><div align="center"><font style="color: red; font-size: 18px">此功能目前已关闭！</font></div></span>
<?php }?>
<div class="pager"></div>
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

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>