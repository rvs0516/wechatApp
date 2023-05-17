<?php /* Smarty version Smarty-3.1.11, created on 2022-10-19 16:11:01
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\consumption.html" */ ?>
<?php /*%%SmartyHeaderCode:322906195c80a05f921-80036654%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '43ec48bc8316836d958770465d8c78f1aabd8149' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\consumption.html',
      1 => 1666167037,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '322906195c80a05f921-80036654',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6195c80a8b2b37_15461613',
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
<?php if ($_valid && !is_callable('content_6195c80a8b2b37_15461613')) {function content_6195c80a8b2b37_15461613($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
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
    	
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>

<link href="/plugins/fSelect/fSelect.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="/plugins/fSelect/fSelect.js"></script>
<script>
    $(function() {
        // 加载多选下拉框插件
        $('.fSelect').fSelect();

        // 控制专服筛选框是否显示
        var source = $('#source').val()
        if(source == 3){
            $('.container').css('display','block');
        }else{
            $('.container').css('display','none');
        }
        $('#source').change(function(){
            var source = $(this).val()
            if(source == 3){
                $('.container').css('display','block');
            }else{
                $('.container').css('display','none');
            }
        })

        // 点击查询 赋值name为resertSpecialName的input
        $('#sub').click(function(){
            var resertSpecialName = $('.fSelect').val();
            $('.resertSpecialName').val(resertSpecialName);
        })

        // 在选择框展示已经选择的专服名称
        var specialName = $('.resertSpecialName').val();
        if(specialName){
            $('.fs-label').text(specialName);
        }

        // 已经选择的专服名称保持勾选状态
        specialNameArray = specialName.split(",");
        for(var i=0;i<specialNameArray.length;i++){
            // 获取指定自定义属性的div
            var selector = '[data-value="'+specialNameArray[i]+'"]';
            $(selector).addClass('selected')
        }

        // 鼠标经过问题图标显示提示或者取消提示
        $('.list tr th span').mouseenter(function(){
            var current_class = $(this).attr('class');
            $('.'+current_class).find(".question-content").css('display','block');
        })
        $('.list tr th span').mouseleave(function(){
            var current_class = $(this).attr('class');
            $('.'+current_class).find(".question-content").css('display','none');
        })

    });
</script>
<style>
    .display_table_f{
        display: none;
    }
    .fs-label-wrap .fs-label{
        padding-top: 12px;
    }

    /* 问题图标CSS */
    .list tr th span{
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        background: url('static/question_black.png');
        background-size: 100%;
    }
    .list tr th span:hover{
        display: inline-block;
        width: 20px;
        height: 20px;
        background: url('static/question_blue.png');
        background-size: 100%;
    }
    .list tr th span .question-content{
        width: 140px;
        height: 70px;
        background: #d1dee4;
        position: absolute;
        top: -75px;
        right: -40px;
        border-radius: 10px;
        color: #224f72;
        display: none;
        text-align: left;
    }
</style>
	

<?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<script src="/js/pop.js"></script>
<?php }?>

<h3>
    用户综合数据
    <?php if ($_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'){?>
    <span><a  href="#" class="showbox">数据导入</a></span>
    <?php }?>
</h3>
<p align="right" style="color:red; margin-right:30px; margin-top:10px;">
    快速查询&nbsp;
    <a href="javascript:void(0);" class="quick_query" data-time="yesterday">昨日</a>
    <a href="javascript:void(0);" class="quick_query" data-time="today">今日</a>
    <a href="javascript:void(0);" class="quick_query" data-time="last_week">上周</a>
    <a href="javascript:void(0);" class="quick_query" data-time="current_week">本周</a>
    <a href="javascript:void(0);" class="quick_query" data-time="last_month">上月</a>
    <a href="javascript:void(0);" class="quick_query" data-time="current_month">本月</a>
</p>
<form class="searchbox" action="/index.php?m=statistics&a=consumption" method="post">
    <!-- 专服筛选 -->
    <input type="hidden" name="resertSpecialName" class="resertSpecialName" value="<?php echo $_smarty_tpl->tpl_vars['resertSpecialName']->value;?>
">
    <p>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==14||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==22){?>
            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
        <?php }?>
            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
            <input type="hidden" id="refine" value="<?php echo $_smarty_tpl->tpl_vars['refine']->value;?>
" />
        <span>来自游戏：</span>
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
            <select name="game" id="game" style="width: 205px;">
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
        <span style="width: 60px;" id="channelTitle">渠道： </span>
        <select name="channel" id="channel" style="width: 170px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['key1']->value)){?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
            <?php } ?>
        </select>
        <span id="apkNumTitle">包号：</span>
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
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
        <span style="margin-left: 70px;">排序：</span>
        <select name="sort" id="sort"  style="width: 170px;">
            <option value="">请选择</option>
            <option value="date" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='date'){?>selected="selected"<?php }?>>日期</option>
            <option value="amount" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='amount'){?>selected="selected"<?php }?>>付费金额</option>
            <option value="activeUser" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='activeUser'){?>selected="selected"<?php }?>>活跃用户</option>
            <option value="newUser" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='newUser'){?>selected="selected"<?php }?>>新用户</option>
        </select>
        <span>显示模式：</span>
        <select name="displayMode" id="displayMode"  style="width: 170px;">
            <option value="sum" <?php if ($_smarty_tpl->tpl_vars['displayMode']->value=='sum'){?>selected="selected"<?php }?>>汇总</option>
            <option value="details" <?php if ($_smarty_tpl->tpl_vars['displayMode']->value=='details'){?>selected="selected"<?php }?>>详情</option>
        </select>
    </p>
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==23||$_smarty_tpl->tpl_vars['gid']->value==24){?>
    <p>
        <span>数据来源：</span>
        <select name="source" id="source"  style="width: 200px;">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['source']->value=='1'){?>selected="selected"<?php }?>>导入数据</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['source']->value=='2'){?>selected="selected"<?php }?>>SDK数据</option>
            <option value="3" <?php if ($_smarty_tpl->tpl_vars['source']->value=='3'){?>selected="selected"<?php }?>>市场数据</option>
        </select>
        <?php if ($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'){?>
        <span>数据类型：</span>
        <select name="status" id="status"  style="width: 200px;">
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value=='1'){?>selected="selected"<?php }?>>常规数据</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value=='2'){?>selected="selected"<?php }?>>QA数据</option>
        </select>
        <?php }?>
    </p>
    <?php }?>
    <p class="market">
        <span>年份：</span>
        <select name="years" id="years"  style="width: 200px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['yearArray']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['years']->value==$_smarty_tpl->tpl_vars['item']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
            <?php } ?>
        </select>
    </p>
    <p style="width: 950px;">
        &nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="su inline" id="sub">查询</button>
    </p>
    <!--<p style="margin-left: 10px; color: red;">*&nbsp;&nbsp;该页面显示的用户数为角色数，选择游戏后可查询所有时间，否则只显示最近45天数据</p>-->
</form>

<!-- 专服筛选 -->
<div class="container" style="margin:20px 0;display: none;">
    <span style="color: #36588a;">专服筛选：</span>
    <select class="fSelect" multiple="multiple">
        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['specialNameArray']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
        <optgroup label="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['value']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
            <?php } ?>
        </optgroup>
        <?php } ?>
    </select><font color="red">&nbsp;*&nbsp;注意：勾选后的专服数据将不进行展示</font>
</div>

<table class="list">

    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['refine']->value!=7){?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value!=1&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')||$_smarty_tpl->tpl_vars['refine']->value==6){?>
            <th style="line-height: 18px;" <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>日期</th>
            <?php }?>
            <th style="line-height: 18px;" <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>游戏</th>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=4&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')){?>
            <th style="line-height: 18px;" <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>渠道</th>
            <?php }?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value==6)){?>
            <th style="line-height: 18px;" <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>包号</th>
            <?php }?>
            <th style="line-height: 18px;" <?php if (!in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>新用户</th>
            <th style="line-height: 18px;" <?php if (!in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>老用户</th>
            <th style="line-height: 18px;" <?php if (!in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>活跃用户</th>
            <th style="line-height: 18px;" <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>付费金额</th>
            <th style="line-height: 18px;" <?php if (!in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>倍数<span class="question—multiple"><em class="question-content">倍数=付费金额/新增用户人数</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>付费人数</th>
            <th style="line-height: 18px;" <?php if (!in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>ARPPU<span class="question—arppu"><em class="question-content">ARPPU=付费金额/付费人数<br>ARPPU值是指每个付费用户平均收入</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>ARPU<span class="question—arpu"><em class="question-content">ARPU=付费金额/活跃用户人数<br>ARPU值是指每个活跃用户平均收入</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>活跃</br>付费率</th>
            <th style="line-height: 18px;" <?php if (!in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>新用户</br>付费人数</th>
            <th style="line-height: 18px;" <?php if (!in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>新用户</br>付费金额</th>
            <th style="line-height: 18px;" <?php if (!in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>新用户</br>ARPPU<span class="question—newarppu"><em class="question-content">新用户ARPPU=新用户付费金额/新用户付费人数</em></th>
            <th style="line-height: 18px;" <?php if (!in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>新用户</br>付费率</th>
            <th style="line-height: 18px;" <?php if (!in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>老用户</br>付费人数</th>
            <th style="line-height: 18px;" <?php if (!in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>老用户</br>付费金额</th>
            <th style="line-height: 18px;" <?php if (!in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>老用户</br>付费率</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==23||$_smarty_tpl->tpl_vars['gid']->value==24){?>
            <th style="line-height: 18px;" <?php if (!in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>综合支出<span class="question—allpay"><em class="question-content">综合支出=投放支出+项目支出+额外支出</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>平均单价</th>
            <th style="line-height: 18px;" <?php if (!in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>ROI<span class="question—roi"><em class="question-content">ROI=新增用户付费/综合支出</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>实收ROI<span class="question-reroi"><em class="question-content">实收ROI=新增用户付费利润/综合支出</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>付费A值<span class="question-akey"><em class="question-content"> 付费A值=投放支出/新增付费人数</em></span></th>
            <th style="line-height: 18px;" <?php if (!in_array(23,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>回款率<span class="question—returnMoney"><em class="question-content">回款率=付费金额/综合支出</em></th>
            <?php if ($_smarty_tpl->tpl_vars['channel']->value){?>
            <th style="line-height: 18px;" <?php if (!in_array(24,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?> >实际回款率<span class="question—rereturnMoney"><em class="question-content" style="height: 130px;">不同渠道的实际回款率不同<br>oppo、vivo: 回款率*0.62<br>华为: 回款率*0.5<br>小米: 回款率*0.74<br>ios: 回款率*0.7</em></th>
            <?php }?>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['uid']->value=='zijian'){?>
            <th style="line-height: 18px;" <?php if (!in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>综合支出</th>
            <?php }?>
        <?php }else{ ?>
            <th style="line-height: 18px; width: 15%;" <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>日期</th>
            <th style="line-height: 18px; width: 20%;" <?php if (!in_array(26,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>上级游戏</th>
            <th style="line-height: 18px; width: 20%;" <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>游戏</th>
            <th style="line-height: 18px; width: 15%;" <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>渠道</th>
            <th style="line-height: 18px; width: 15%;" <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>包号</th>
            <th style="line-height: 18px; width: 15%;" <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>付费金额</th>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['refine']->value==6&&($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen')){?>
            <th>操作</th>
        <?php }?>
    </tr>
    <?php if ($_smarty_tpl->tpl_vars['active']->value){?>
    <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['active']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['order']->key;
?>
    <tr <?php if (($_smarty_tpl->tpl_vars['key1']->value+1)%5==0){?>style="background-color:#ecffe5;"<?php }?>>
        <?php if ($_smarty_tpl->tpl_vars['refine']->value!=7){?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value!=1&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')||$_smarty_tpl->tpl_vars['refine']->value==6){?>
            <td <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <?php }?>
            <td <?php if ($_smarty_tpl->tpl_vars['order']->value['gsChannelName']&&$_smarty_tpl->tpl_vars['gid']->value==1){?>title = ' <?php echo $_smarty_tpl->tpl_vars['order']->value['gsChannelName'];?>
 ' <?php }?> <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php if ($_smarty_tpl->tpl_vars['order']->value['name']){?><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['name'],8,"..",true);?>
<?php }elseif($_smarty_tpl->tpl_vars['order']->value['specialName']){?><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['specialName'],8,"..",true);?>
<?php }else{ ?><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['upperName'],8,"..",true);?>
<?php }?></td>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=4&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')){?>
            <td <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
</td>
            <?php }?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value==6)){?>
            <td <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <?php }?>
            <td <?php if (!in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['newUser'];?>
</td>
            <td <?php if (!in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['oldUser'];?>
</td>
            <td <?php if (!in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['activeUser'];?>
</td>
            <td <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
            <td <?php if (!in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['multiple'];?>
</td>
            <td <?php if (!in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['payUser'];?>
</td>
            <td <?php if (!in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['arpu'];?>
</td>
            <td <?php if (!in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['arppu'];?>
</td>
            <td <?php if (!in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['activeUserRate'];?>
</td>
            <td <?php if (!in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['newPayUser'];?>
</td>
            <td <?php if (!in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['newAmount'];?>
</td>
            <td <?php if (!in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['newPayArpu'];?>
</td>
            <td <?php if (!in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['newUserRate'];?>
</td>
            <td <?php if (!in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['oldPayUser'];?>
</td>
            <td <?php if (!in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['oldAmount'];?>
</td>
            <td <?php if (!in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['oldUserRate'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==23||$_smarty_tpl->tpl_vars['gid']->value==24){?>
            <td <?php if (!in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php if ($_smarty_tpl->tpl_vars['order']->value['adPay']){?><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
<?php }else{ ?>0<?php }?></td>
            <td <?php if (!in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['order']->value['adPay']/$_smarty_tpl->tpl_vars['order']->value['newUser']));?>
</td>
            <td <?php if (!in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['roi'];?>
</td>
            <td <?php if (!in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['order']->value['reRoi']);?>
</td>
            <td <?php if (!in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['order']->value['adPay']/$_smarty_tpl->tpl_vars['order']->value['newPayUser']));?>
</td>
            <td <?php if (!in_array(23,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['order']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay']));?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['channel']->value){?>
            <td <?php if (!in_array(24,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>
                <?php if ($_smarty_tpl->tpl_vars['order']->value['amount']&&$_smarty_tpl->tpl_vars['order']->value['adPay']){?>
                    <?php if ($_smarty_tpl->tpl_vars['channel']->value=='000368'||$_smarty_tpl->tpl_vars['channel']->value=='000020'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['order']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay'])*0.62));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='500001'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['order']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay'])*0.5));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='000066'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['order']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay'])*0.74));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='500011'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['order']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay'])*0.7));?>

                    <?php }?>
                <?php }else{ ?>
                0
                <?php }?>
            </td>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['refine']->value==6&&($_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen')){?>
                <td><a href="index.php?m=statistics&a=changeDataStatus&id=<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
&model=consumption" id="change">QA</a></td>
            <?php }?>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['uid']->value=='zijian'){?>
            <td <?php if (!in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['adPay'];?>
</td>
            <?php }?>
        <?php }else{ ?>
            <td <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['date'];?>
</td>
            <td <?php if (!in_array(26,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
</td>
            <td <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['name'];?>
</td>
            <td <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
</td>
            <td <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>
</td>
            <td <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['amount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
    <?php }else{ ?>
    <tr>
        <td colspan="23">无相关数据</td>
    </tr>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['active']->value){?>
    <?php  $_smarty_tpl->tpl_vars['sum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['summary']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sum']->key => $_smarty_tpl->tpl_vars['sum']->value){
$_smarty_tpl->tpl_vars['sum']->_loop = true;
?>
    <tr style="background-color:#CCCCCC;">
        <?php if ($_smarty_tpl->tpl_vars['refine']->value!=7){?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value!=1&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')||$_smarty_tpl->tpl_vars['refine']->value==6){?>
            <td <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <?php }?>
            <td <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>数据汇总</td>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=4&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value=='sum')){?>
            <td <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <?php }?>
            <?php if (($_smarty_tpl->tpl_vars['refine']->value>=5&&$_smarty_tpl->tpl_vars['displayMode']->value!='sum')||($_smarty_tpl->tpl_vars['refine']->value==6)){?>
            <td <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <?php }?>
            <td <?php if (!in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['newUser'];?>
</td>
            <td <?php if (!in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['oldUser'];?>
</td>
            <td <?php if (!in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['activeUser'];?>
</td>
            <td <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
            <td <?php if (!in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['multiple'];?>
</td>
            <td <?php if (!in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['payUser'];?>
</td>
            <td <?php if (!in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['arpu'];?>
</td>
            <td <?php if (!in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['arppu'];?>
</td>
            <td <?php if (!in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['activeUserRate'];?>
</td>
            <td <?php if (!in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['newPayUser'];?>
</td>
            <td <?php if (!in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['newAmount'];?>
</td>
            <td <?php if (!in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['newPayArpu'];?>
</td>
            <td <?php if (!in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['newUserRate'];?>
</td>
            <td <?php if (!in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['oldPayUser'];?>
</td>
            <td <?php if (!in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['oldAmount'];?>
</td>
            <td <?php if (!in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['oldUserRate'];?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==23||$_smarty_tpl->tpl_vars['gid']->value==24){?>
            <td <?php if (!in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['adPay'];?>
</td>
            <td <?php if (!in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['sum']->value['adPay']/$_smarty_tpl->tpl_vars['sum']->value['newUser']));?>
</td>
            <td <?php if (!in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['roi'];?>
</td>
            <td <?php if (!in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['reRoi'];?>
</td>
            <td <?php if (!in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['sum']->value['adPay']/$_smarty_tpl->tpl_vars['sum']->value['newPayUser']));?>
</td>
            <td <?php if (!in_array(23,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo sprintf("%.2f",($_smarty_tpl->tpl_vars['sum']->value['amount']/$_smarty_tpl->tpl_vars['sum']->value['adPay']));?>
</td>
            <?php if ($_smarty_tpl->tpl_vars['channel']->value){?>
            <td <?php if (!in_array(24,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>
                <?php if ($_smarty_tpl->tpl_vars['sum']->value['amount']&&$_smarty_tpl->tpl_vars['sum']->value['adPay']){?>
                    <?php if ($_smarty_tpl->tpl_vars['channel']->value=='000368'||$_smarty_tpl->tpl_vars['channel']->value=='000020'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['sum']->value['amount']/$_smarty_tpl->tpl_vars['sum']->value['adPay'])*0.62));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='500001'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['sum']->value['amount']/$_smarty_tpl->tpl_vars['sum']->value['adPay'])*0.5));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='000066'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['sum']->value['amount']/$_smarty_tpl->tpl_vars['order']->value['adPay'])*0.74));?>

                    <?php }elseif($_smarty_tpl->tpl_vars['channel']->value=='500011'){?>
                        <?php echo sprintf("%.2f",(($_smarty_tpl->tpl_vars['sum']->value['amount']/$_smarty_tpl->tpl_vars['sum']->value['adPay'])*0.7));?>

                    <?php }?>
                <?php }else{ ?>
                0
                <?php }?>
            </td>
            <?php }?>
            <?php }?>
        <?php }else{ ?>
            <td <?php if (!in_array(25,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <td <?php if (!in_array(26,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <td <?php if (!in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <td <?php if (!in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <td <?php if (!in_array(28,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>>-</td>
            <td <?php if (!in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)&&$_smarty_tpl->tpl_vars['gid']->value==17){?>class="display_table_f"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sum']->value['amount'];?>
</td>
        <?php }?>
    </tr>
    <?php } ?>
    <?php }?>
    <!--隐藏域 start-->
    <div id="popbg"></div>
    <div class="pop fastDopost">
        <form action="/index.php?m=statistics&a=orderImport&target=consumption" method="post" enctype="multipart/form-data">
            <input name="cardid" id="thisComment" type="hidden" value="" />
            <h3><span><a href="#" class="popclose">关闭</a></span>导入订单数据</h3>
            <p ><input name="file[]" id="iconfile" type="file" style=" height:25px; line-height:25px; display:block; margin: 20px 0px 20px 25px;" /></p>
            <p align="right"><button type="submit" class="su popsubmit">提交</button></p>
        </form>
    </div>
    <!--隐藏域 end-->
</table>
<?php if ($_smarty_tpl->tpl_vars['gid']->value==17){?>
    <?php if (($_smarty_tpl->tpl_vars['refine']->value==1||$_smarty_tpl->tpl_vars['refine']->value==2||$_smarty_tpl->tpl_vars['refine']->value==3||$_smarty_tpl->tpl_vars['refine']->value==4)&&(in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(23,$_smarty_tpl->tpl_vars['header_id']->value))){?>
        <div id="pager"></div>
    <?php }elseif($_smarty_tpl->tpl_vars['refine']->value==5&&(in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(23,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(24,$_smarty_tpl->tpl_vars['header_id']->value))){?>
        <div id="pager"></div>
    <?php }elseif($_smarty_tpl->tpl_vars['refine']->value==6&&(in_array(1,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(2,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(3,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(4,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(5,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(6,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(7,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(8,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(9,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(10,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(11,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(12,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(13,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(14,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(15,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(16,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(17,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(18,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(19,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(20,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(21,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(22,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(23,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(27,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(24,$_smarty_tpl->tpl_vars['header_id']->value)||in_array(28,$_smarty_tpl->tpl_vars['header_id']->value))){?>
        <div id="pager"></div>
    <?php }?>
    <script src="js/pager.js"></script>
<?php }else{ ?>
    <div id="pager"></div>
    <script src="js/pager.js"></script>
<?php }?>
<script>
$('#change').click(function() {
    return confirm('数据属性将修改，确定要执行？');
});
$(function() {
    $('.quick_query').click(function() {
        var date = getDate( $(this).attr('data-time') );
        $('#start_date').val( date.start );
        $('#end_date').val( date.end );
        $("#sub").click();
        return false;
    });
});
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
    $('#page').val(page);
    $('.searchbox').submit();
}
function gotoNext(page,pagesize){
        window.location.href = "/index.php?m=statistics&a=consumption&page=" + page+"&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&gid=<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
&gsSource=<?php echo $_smarty_tpl->tpl_vars['gsSource']->value;?>
&sourceType=<?php echo $_smarty_tpl->tpl_vars['sourceType']->value;?>
&refine=<?php echo $_smarty_tpl->tpl_vars['refine']->value;?>
&sort=<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
&displayMode=<?php echo $_smarty_tpl->tpl_vars['displayMode']->value;?>
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
<?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==6||$_smarty_tpl->tpl_vars['gid']->value==12){?>
<script>
$(function(){
<?php if (($_smarty_tpl->tpl_vars['sourceType']->value==1)){?>
    $('#gs').show();
    $('#advertising').hide();
    $('#channel').hide();
    $('#apkNum').hide();
    $('#channelTitle').hide();
    $('#apkNumTitle').hide();
<?php }elseif(($_smarty_tpl->tpl_vars['sourceType']->value==2)){?>
    $('#gs').hide();
    $('#advertising').show();
    $('#channel').show();
    $('#apkNum').hide();
    $('#channelTitle').show();
    $('#apkNumTitle').hide();
<?php }else{ ?>
    $('#gs').hide();
    $('#advertising').hide();
    $('#channel').show();
    $('#apkNum').show();
    $('#channelTitle').show();
    $('#apkNumTitle').show();
<?php }?>
$("select#sourceType").change(function(){
    var type = $(this).val();
    if(type == 1){
        $('#gs').show();
        $('#advertising').hide();
        $('#channel').hide();
        $('#apkNum').hide();
        $('#channelTitle').hide();
        $('#apkNumTitle').hide();
    }else if(type == 2){
        $('#gs').hide();
        $('#advertising').show();
        $('#channel').show();
        $('#apkNum').hide();
        $('#channelTitle').show();
        $('#apkNumTitle').hide();
    }else{
        $('#gs').hide();
        $('#advertising').hide();
        $('#channel').show();
        $('#apkNum').show();
        $('#channelTitle').show();
        $('#apkNumTitle').show();
    }
})
});
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

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>