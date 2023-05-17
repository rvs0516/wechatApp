<?php /* Smarty version Smarty-3.1.11, created on 2022-10-26 11:43:43
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\priv\addrole.html" */ ?>
<?php /*%%SmartyHeaderCode:384561761d382b38f1-14915585%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '229dc6cb43f42e54cf47e7b1fc20bebbecca1a17' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\priv\\addrole.html',
      1 => 1665743385,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '384561761d382b38f1-14915585',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61761d3858e1f6_63795638',
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
<?php if ($_valid && !is_callable('content_61761d3858e1f6_63795638')) {function content_61761d3858e1f6_63795638($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
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
    	
<script src="/js/jquery-1.8.1.min.js"></script>
<link href="../js/layui/css/layui.css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script>
$(function(){
$('.delect').click(function(){
	return confirm("確定刪除？");
})
$('#search').click(function(){
	var uid = $('#uid').val();
	location.href="index.php?m=priv&a=addrole&op=add&menuid=<?php echo $_smarty_tpl->tpl_vars['menuid']->value;?>
&uid=" + uid;
})
$('#gameStr').hide();
$('#chargingModel').hide();
$('#ads').hide();
$('#playData').hide();
$('#gameSelect').hide();
$('#profitData').hide();
<?php if ((($_smarty_tpl->tpl_vars['rolerow']->value['gid']==8))){?>
$('#games').show();
$('#channels').show();
$('#tips').show();
<?php }elseif(($_smarty_tpl->tpl_vars['rolerow']->value['gid']==13)){?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#gameSelect').show();
$('#chargingModel').show();
<?php }elseif(($_smarty_tpl->tpl_vars['rolerow']->value['gid']==18)){?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#ads').show();
<?php }elseif(($_smarty_tpl->tpl_vars['rolerow']->value['gid']==2||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==11||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==14||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==19||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==15)){?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#gameSelect').show();
<?php }elseif(($_smarty_tpl->tpl_vars['rolerow']->value['gid']==17)){?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#playData').show();
$('#profitData').show();
$('#gameSelect').show();
<?php }elseif(($_smarty_tpl->tpl_vars['rolerow']->value['gid']==22)){?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#gameSelect').show();
<?php }else{ ?>
$('#games').hide();
$('#channels').hide();
$('#tips').hide();
$('#ads').hide();
<?php }?>
<?php if (($_smarty_tpl->tpl_vars['allGame']->value=='part')&&($_smarty_tpl->tpl_vars['rolerow']->value['gid']==2||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==11||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==14||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==19||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==15||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==17||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==13||$_smarty_tpl->tpl_vars['rolerow']->value['gid']==22)){?>
$('#gameStr').show();
<?php }?>
$("select#gid").change(function(){
	var group = $(this).val();
	$('#chargingModel').hide();
	$('#ads').hide();
	$('#playData').hide();
	$('#profitData').hide();
	$('#gameSelect').hide();
	$('#gameStr').hide();
	if((group == 8)){
		$('#games').show();
		$('#channels').show();
		$('#tips').show();
	}else if(group == 2 || group == 11 || group == 14  || group == 19){
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
		$('#gameSelect').show();
	}else if(group == 17 ){
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
		$('#playData').show();
		$('#profitData').show();
		$('#gameSelect').show();
	}else if(group == 15){
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
		$('#gameSelect').show();
	}else if(group == 22){
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
		$('#gameSelect').show();
	}else if( group == 13){
		$('#chargingModel').show();
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
		$('#gameSelect').show();
	}else if( group == 18){
		$('#ads').show();
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
	}else{
		$('#games').hide();
		$('#channels').hide();
		$('#tips').hide();
	}
	var allGame = <?php echo $_smarty_tpl->tpl_vars['allGame']->value;?>
.value
	if((group == 2 || group == 11 || group == 14  || group == 19 || group == 15 || group == 17 || group == 13 || group == 22) && allGame == 'part'){
		$('#gameStr').show();
	}
})
});
</script> 
<h3><span><a href="index.php?m=priv&a=listrole">返回角色列表</a></span><?php if ($_GET['op']=='add'){?>添加角色<?php }else{ ?>修改角色<?php }?></h3>
<br>
	<form action="/index.php?m=priv&a=addrole" method="post" class="layui-form" >
		<div class="layui-form-item">
			<label class="layui-form-label">用户ID：</label>
			<div class="layui-input-inline" >
				<input type="text" name="form[uid]" id="uid" value="<?php echo $_smarty_tpl->tpl_vars['ruid']->value;?>
"  class="layui-input text">
			</div>
			<?php if ($_GET['op']=='add'){?>
				<button type="button" id="search" class="layui-btn layui-btn-primary " lay-on="get-vercode">查找用户</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['isfound']->value==2){?>
				<font style="margin-left:5px;" color="red">搜索<?php echo $_smarty_tpl->tpl_vars['ruid']->value;?>
:没有找到此用户,请为此用户添加权限</font>
			<?php }?>
		</div>
		<div class="layui-form-item" >
			<label class="layui-form-label" >用户组：</label>
			<div class="layui-input-block"  style="width: 190px;">
			  <select id="gid" name="form[gid]"  lay-filter="gid">
				<option value="0">请选择用户组</option>
				<?php  $_smarty_tpl->tpl_vars['v2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v2']->_loop = false;
 $_smarty_tpl->tpl_vars['k2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['grouplist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v2']->key => $_smarty_tpl->tpl_vars['v2']->value){
$_smarty_tpl->tpl_vars['v2']->_loop = true;
 $_smarty_tpl->tpl_vars['k2']->value = $_smarty_tpl->tpl_vars['v2']->key;
?>
				<option <?php if (($_smarty_tpl->tpl_vars['v2']->value['id']==$_smarty_tpl->tpl_vars['rolegid']->value)){?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['v2']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v2']->value['name'];?>
</option>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="layui-form-item" id="games">
			<label class="layui-form-label" >负责游戏：</label>
			<div class="layui-input-block"  style="width: 190px;">
			  <select id="game" name="form[game]"  lay-filter="game">
				<option value="0">请选择关联游戏</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['gamelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
					<option <?php if ($_smarty_tpl->tpl_vars['data']->value['alias']==$_smarty_tpl->tpl_vars['rolerowGame']->value){?> selected="selected"<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['data']->value['alias'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
</option>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div id="ads">
			<div class="layui-form-item" >
				<label class="layui-form-label" >推广渠道：</label>
				<div class="layui-input-block "  style="width: 190px;">
				  <select name="form[adsChannel]" id="adsChannel" lay-filter="adsChannel">
					<option value="0">请选择</option>
					<option value="000368" <?php if ($_smarty_tpl->tpl_vars['rolerow']->value['adsChannel']=='000368'){?> selected="selected" <?php }?>>VIVO</option>
					<option value="000020" <?php if ($_smarty_tpl->tpl_vars['rolerow']->value['adsChannel']=='000020'){?> selected="selected" <?php }?>>OPPO</option>
					<option value="500001" <?php if ($_smarty_tpl->tpl_vars['rolerow']->value['adsChannel']=='500001'){?> selected="selected" <?php }?>>华为</option>
				  </select>
				</div>
			</div>
			<div class="layui-form-item" >
				<label class="layui-form-label" >推广账号：</label>
				<div class="layui-input-block "  style="width: 190px;">
				  <select name="form[adsAccount]" id="adsAccount" lay-filter="adsAccount">
					<option value="0">请选择</option>
					<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['accountList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['account'];?>
" <?php if ($_smarty_tpl->tpl_vars['rolerow']->value['adsAccount']==$_smarty_tpl->tpl_vars['name']->value['account']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['account'];?>
</option>
                	<?php } ?>
				  </select>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">真实姓名：</label>
			<div class="layui-input-inline">
				<input id="realname" type="text" name="form[realname]" class="layui-input text" value="<?php echo $_smarty_tpl->tpl_vars['rolerow']->value['realname'];?>
">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">EMAIL：</label>
			<div class="layui-input-inline">
				<input id="mail" type="text" name="form[mail]" class="layui-input text" value="<?php echo $_smarty_tpl->tpl_vars['rolerow']->value['mail'];?>
">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">电话：</label>
			<div class="layui-input-inline">
				<input id="mobile" type="text" name="form[mobile]" class="layui-input text"  value="<?php echo $_smarty_tpl->tpl_vars['rolerow']->value['mobile'];?>
">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码：</label>
			<div class="layui-input-inline" >
				<input type="password" value="" name="form[password]" id="password"  class="layui-input text" onchange="checkPassword()">
			</div>
			<font style="margin-left:5px;" color="red">【注意】1.若密码为空，则不修改密码；2.至少包含8个字符；3.必须包含数字或字母，以及任意一个或多个特殊符号!*$%#@&.</font>
		</div>
		<div id="chargingModel">
			<div class="layui-form-item">
				<label class="layui-form-label">充值扣量：</label>
				<div class="layui-input-inline">
					<input id="payCharging" type="text" name="form[payCharging]" class="layui-input text" value="<?php echo $_smarty_tpl->tpl_vars['rolerow']->value['payCharging'];?>
" placeholder="最大支持小数点后两位">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">统计显示时间：</label>
				<div class="layui-input-inline">
					<input type="text" name="form[limitTime]" value="<?php if ($_smarty_tpl->tpl_vars['rolerow']->value['limitTime']){?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['rolerow']->value['limitTime'],'%Y-%m-%d');?>
<?php }?>" onclick="WdatePicker({ maxDate: '9999-12-30', readOnly: true });" class="layui-input text" >
				</div>
				<font color="#FF0000">&nbsp;*</font>
			</div>
		</div>
		
		<div class="layui-form-item" pane="" id="channels">
			<label class="layui-form-label">关联渠道：</label>
			<input type="checkbox"  id="selectBtn" lay-filter="menu" lay-skin="primary"  title="全选/全不选"  >
			<div class="layui-input-block">
				<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
				<input type="checkbox" class="checkone" name="channels[]" lay-skin="primary" title="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['key1']->value,$_smarty_tpl->tpl_vars['openChannel']->value)){?> checked="checked"<?php }?>/>
				<?php } ?>
			</div>
		</div>
		<div class="layui-form-item" id="gameSelect">
			<label class="layui-form-label" >关联所负责游戏：</label>
			<div class="layui-input-block"  style="width: 190px;">
				<select  name="form[allGame]" id="gameid" lay-filter="gameid">
					<option value="0">请选择</option>
					<option value="all" <?php if ($_smarty_tpl->tpl_vars['allGame']->value=='all'){?>selected="selected"<?php }?>>全部游戏</option>
					<option value="part" id="part" <?php if ($_smarty_tpl->tpl_vars['allGame']->value=='part'){?>selected="selected"<?php }?>>部分游戏</option>
				</select>
			</div>
		</div>
		<div class="layui-form-item" id="gameStr">
			<label class="layui-form-label"></label>
			<div id="Shuttle_box" class="demo-transfer" name="form[gameStr]"></div>
		</div>
		<div class="layui-form-item" pane="" id="playData">
			<label class="layui-form-label">玩家综合数据统计维度：</label>
			<input type="checkbox"  id="selectBtn" lay-filter="menu_o" lay-skin="primary"  title="全选/全不选"  >
			<div class="layui-input-block">
				<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['player_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
?>
					<input type="checkbox" class="checkone_ju" name="headerData[]" lay-skin="primary" title="<?php echo $_smarty_tpl->tpl_vars['name']->value['header_name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['name']->value['header_id'];?>
" <?php if (in_array($_smarty_tpl->tpl_vars['name']->value['header_id'],$_smarty_tpl->tpl_vars['header_id']->value)){?> checked="checked"<?php }?>/>
				<?php } ?>
			</div>
		</div>
		<div class="layui-form-item" pane="" id="profitData">
			<label class="layui-form-label">利润详情统计维度：</label>
			<input type="checkbox"  id="selectBtn" lay-filter="menu_p" lay-skin="primary"  title="全选/全不选"  >
			<div class="layui-input-block">
				<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['priv_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
?>
					<input type="checkbox" class="checkone_pr" name="headerData[]" lay-skin="primary" title="<?php echo $_smarty_tpl->tpl_vars['name']->value['header_name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['name']->value['header_id'];?>
" <?php if (in_array($_smarty_tpl->tpl_vars['name']->value['header_id'],$_smarty_tpl->tpl_vars['header_id']->value)){?> checked="checked"<?php }?>/>
				<?php } ?>
			</div>
		</div>
		<input name="do" type="hidden"/>
		<input type="hidden" name="form[gameStr]" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" id="form[gameStr]"/>

		<div class="layui-form-item">
			<div class="layui-input-block">
			  <button type="button" class="layui-btn" id="getSub">立即提交</button>
			  <button type="reset" class="layui-btn layui-btn-primary ">条件重置</button>
			</div>
		</div>
	</form>
<style>
	.layui-transfer-data {
    padding: 5px 0;
    overflow: auto;
	height: 360px !important;
}
</style>
<script type="text/javascript" src="/js/jQueryLabel/js/tab.js"></script>
<!-- 关联游戏穿梭框 -->
<script src="../js/layui/layui.js"></script>

<script>
	var op = "<?php echo $_GET['op'];?>
";
	// 使用input输入域变化事件，通过JS正则表达式判断密码强度
	function checkPassword(){
		var passwordString = $('#password').val();
		/*if (passwordString == '' || passwordString.length < 8) {
			alert('密码长度不符，请输入8位字符！');
			return false;
		}*/
		if (op == 'edit') {
			if (passwordString != '' && passwordString.length < 8) {
				alert('密码长度不符，请输入8位字符！');
				return false;
			}
		}else{
			if (passwordString == '' || passwordString.length < 8) {
				alert('密码长度不符，请输入8位字符！');
				return false;
			}
		}
		// 必须包含数字或字母，以及任意一个或多个特殊符号!*$%#@&
		var reg = new RegExp(/^[A-Za-z0-9]+[!*$%#@&.]+$/);
		if (!reg.test(passwordString) && passwordString != '') {
			alert('必须包含数字或字母，以及任意一个或多个特殊符号!*$%#@&.');
			return false;
		}
	}

    layui.use(['transfer', 'layer', 'util','form','jquery'], function(){
        var $ = layui.$
        ,transfer = layui.transfer
        ,layer = layui.layer
        ,util = layui.util
		,form = layui.form; 
		
		form.on('select(gid)', function (data){
			$(data.elem).trigger('change',data.elem);
		});

		form.on('select(gameid)', function (data){
			var gameid = data.value
			if (gameid == 'part') {
				$('#gameStr').show();
			}else{
				$('#gameStr').hide();
			}
		});

		// 推广渠道 下拉点击
		form.on('select(adsChannel)', function(data){
			var adsChannel = data.value;
			var adsAccount = $('#adsAccount').val();
			if(adsChannel == ''){
				$("#adsAccount option[text!='']").remove();
				$("#adsAccount").append('<option value="">请选择</option>').change();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "/?m=ads&a=getChannelAccount",
				data: "channel="+adsChannel+"&account="+adsAccount,
				dataType: 'text',
				success: function(result){
					$("#adsAccount option[text!='0']").remove();
					$("#adsAccount").append(result);
					form.render('select');
				}
			});
			}); 
		

		//全选被选中  其他都被勾选
		form.on('checkbox(menu)', function(data){
			var id = data.value;
			//这里实现勾选 
			$('.checkone').each(function(index, item){
				item.checked = data.elem.checked;
			});
			form.render('checkbox');
		});  
		form.on('checkbox(menu_o)', function(data){
			var id = data.value;
			//这里实现勾选 
			$('.checkone_ju').each(function(index, item){
				item.checked = data.elem.checked;
			});
			form.render('checkbox');
		}); 
		form.on('checkbox(menu_p)', function(data){
			var id = data.value;
			//这里实现勾选 
			$('.checkone_pr').each(function(index, item){
				item.checked = data.elem.checked;
			});
			form.render('checkbox');
		});

		form.render();

		//选择后调用的函数
		var changeFun = function (elem)
		{
			var selectd = elem.value;//得到选择后的值
		}
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
 $_from = $_smarty_tpl->tpl_vars['gameStr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
            ,height:450 //修改高度也要修改style的.layui-transfer-data 高度
            ,id:"key"
            ,value: value
        })

		$(document).on('click','#getSub',function(){
			
			var checkData = $('.layui-transfer-box[data-index="0"] input[placeholder="关键词搜索"]').val()
			var checkData1 = $('.layui-transfer-box[data-index="1"] input[placeholder="关键词搜索"]').val()
			var checkBox = $('.layui-transfer-box[data-index="0"] div[lay-skin="primary"]').attr('class')
			var checkBox1 = $('.layui-transfer-box[data-index="1"] div[lay-skin="primary"]').attr('class')

			if (checkData != '') {
				alert("关联所负责游戏左侧搜索框存在非必要内容，请清除")
				$('.layui-transfer-box[data-index="0"] input[placeholder="关键词搜索"]').css('border-color','red')
			}else if(checkData1 != ''){
				alert("关联所负责游戏右侧搜索框存在非必要内容，请清除")
				$('.layui-transfer-box[data-index="1"] input[placeholder="关键词搜索"]').css('border-color','red')
			}else if (checkBox.search("layui-form-checked") != '-1' ) {
				alert("关联所负责游戏左侧勾选框存在非必要勾选，请取消")
				$('.layui-transfer-box[data-index="0"] i.layui-icon-ok ').css('background','red')
			}else if(checkBox1.search("layui-form-checked") != '-1' ){
				alert("关联所负责游戏右侧勾选框存在非必要勾选，请取消")
				$('.layui-transfer-box[data-index="1"] i.layui-icon-ok ').css('background','red')
			}else{
				var getData = transfer.getData('key'); //获取右侧穿梭数据
				
                // 关联游戏数据
                var assGame = ''
                for (let i = 0; i < getData.length; i++) {

                    if (i===getData.length-1){
                        assGame += getData[i].value;
                    }else{
                        assGame += getData[i].value +'|'
                    }

                }

				$('input[name="form[gameStr]"]').val(assGame);
                
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

				var passwordString = $('#password').val();
				if (op == 'edit') {
					if (passwordString != '' && passwordString.length < 8) {
						alert('密码长度不符，请输入8位字符！');
						return false;
					}
				}else{
					if (passwordString == '' || passwordString.length < 8) {
						alert('密码长度不符，请输入8位字符！');
						return false;
					}
				}
				
				
                // 提交表单
                var r = confirm('你確定要修改该玩家的信息？');
                if (r == true) {
                    $('.layui-form').submit();
                }
			}
        });
    })
</script>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>