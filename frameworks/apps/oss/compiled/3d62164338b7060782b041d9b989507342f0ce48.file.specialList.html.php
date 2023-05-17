<?php /* Smarty version Smarty-3.1.11, created on 2022-10-21 15:50:48
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkGame\specialList.html" */ ?>
<?php /*%%SmartyHeaderCode:1120563524f58072685-51106990%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d62164338b7060782b041d9b989507342f0ce48' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkGame\\specialList.html',
      1 => 1662104831,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1120563524f58072685-51106990',
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
  'unifunc' => 'content_63524f5823b773_35250207',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_63524f5823b773_35250207')) {function content_63524f5823b773_35250207($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
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
    	
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
	<h3>
		<span><a href="/index.php?m=sdkGame&a=specialList&operation=add">添加处理</a></span>
		账号特殊处理
	</h3>
	<form class="searchbox" action="/index.php?m=sdkGame&a=specialList" method="post">
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==14||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==17){?>
    <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
    <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
    <?php }?>
	<input type="hidden" name="operation" value="" id="operation" />
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
            <select name="rgame" id="game" style="width: 98px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['rgame']->value==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
            <?php } ?>
        </select>
        <span>处理属性：</span>
		<select  name="type">
            <option value="">请选择</option>
            <option value="allBan" <?php if ($_smarty_tpl->tpl_vars['type']->value=='allBan'){?>selected="selected"<?php }?>>全游戏封禁</option>
            <option value="singleBan" <?php if ($_smarty_tpl->tpl_vars['type']->value=='singleBan'){?>selected="selected"<?php }?>>单游戏封禁</option>
            <option value="whiteList" <?php if ($_smarty_tpl->tpl_vars['type']->value=='whiteList'){?>selected="selected"<?php }?>>单游戏白名单</option>
        </select>
        <span>用户账号：</span>
		<input style="width: 180px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" name="userName" id="userName" placeholder="请输入账号">
    </p>
    <p>
        <button type="submit" name="do" class="su">查询</button>
    </p>
	</form>
	<table class="list">
		<tr style="background-color:#CCCCCC;">
			<th style="width: 10%">用户账号</th>
			<th style="width: 10%">上级游戏</th>
			<th style="width: 10%">游戏</th>
			<th style="width: 10%">添加时间</th>
			<th style="width: 10%">类型</th>
			<th style="width: 10%">关联游戏</th>
			<th style="width: 10%">操作用户来源</th>
			<th style="width: 10%">操作</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
		<tr>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['userName'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['upperName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>
<?php }else{ ?>全部游戏<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['gameName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['gameName'];?>
<?php }else{ ?>全部游戏<?php }?></td>
			<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['game']->value['time'],"%y-%m-%d %H:%M");?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['type']=='allBan'){?><font color="red">全游戏封禁</font><?php }elseif($_smarty_tpl->tpl_vars['game']->value['type']=='singleBan'){?><font color="blue">单游戏封禁</font><?php }elseif($_smarty_tpl->tpl_vars['game']->value['type']=='whiteList'){?><font color="green">单游戏白名单</font><?php }?></td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['relateGame'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['uid'];?>
</td>
			<td style="width:160px"><a href="/index.php?m=sdkGame&a=specialList&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a> </td>
		</tr>
		<?php }
if (!$_smarty_tpl->tpl_vars['game']->_loop) {
?>
            <td colspan="8" ><font color="red">暂无该用户数据</font></td>
		<?php } ?>
	</table>
	<div id="pager"></div>

<script src="js/pager.js"></script>
<script>
$("#checked").click(function() {
	$("#operation").val("");
	$('.searchbox').submit();
});
$('.delete_confirm').click(function() {
    return confirm('删除数据不可恢复，是否继续');
});
function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=sdkGame&a=specialList&page=" + page+ "&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&rgame=<?php echo $_smarty_tpl->tpl_vars['rgame']->value;?>
&type=<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>

<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
	<h3>
		<span><a href="/index.php?m=sdkGame&a=specialList">返回列表</a></span>
		添加处理
	</h3>
	<form action="/index.php?m=sdkGame&a=specialList&operation=save" method="post" class="searchbox" enctype="multipart/form-data">
		<p>
			<span>是否全游戏：</span>
			<label><input type="checkbox" name="all" value="1" id="all" /></label>
		</p>
		<p id="gameList">
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
"><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
            <select name="specialName" id="specialName" style="width: 98px;">
                <option value="">请选择</option>
            </select>
            <select name="game" id="game" style="width: 98px;">
            <option value="">请选择</option>
    		</select>
		</p>
		<p>
			<span>处理属性：</span>
			<select  name="type" style="width: 150px;" id="type">
	            <option value="ban">封禁</option>
	            <option value="whiteList">白名单</option>
	        </select>
			<font color="#FF0000">&nbsp;*&nbsp;白名单不能选择全游戏处理</font>
		</p>

        <p id="whiteType">
			<span>白名单类型：</span>
			<select  name="whiteType" style="width: 150px;" id="whiteTypeSelect">
	            <option value="">请选择</option>
	            <option value="simulator">允许模拟器登录</option>
	        </select>
		</p>
        
		<!--<p>
			<span>批量处理：</span>
			<label><input type="checkbox" name="batch" value="1" id="batch" /></label>
		</p>-->
		<p>
			<span>用户账号：</span>
			<!--<input style="width: 180px;" type="text" value="" name="userName" id="userName" placeholder="请输入账号">-->
			<textarea style="width:800px; height:60px;"  name="userNameStr" id="userNameStr" placeholder="单次处理条数不能超过100条"></textarea>
		</p>
		<p id="whiteGameList">
			<span>关联游戏：</span>
            <select name="whiteUpper" id="whiteUpper" style="width: 98px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['UpperList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
            <select name="whiteSpecial" id="whiteSpecial" style="width: 98px;">
                <option value="">请选择</option>
            </select>
            <select name="whiteGame" id="whiteGame" style="width: 98px;">
            <option value="">请选择</option>
    		</select>
    		<font color="#FF0000">&nbsp;*&nbsp;该游戏选项即为跳转游戏</font>
		</p>
		<p>
	        <button type="submit" name="do" class="su" id="submit">提交</button>
	    </p>
	</form>
<script>
$('#submit').click(function() {
    return confirm('将对账号进行特殊处理，是否继续');
});
$('#all').change(function() {
    var dd = document.getElementById("all").checked;
	if (dd) {
		$('#gameList').hide();
	}else{
		$('#gameList').show();
	}
});

/*$('#userNameStr').hide();
$('#batch').change(function() {
    var dd = document.getElementById("batch").checked;
	if (dd) {
		$('#userNameStr').show();
		$('#userName').hide();
	}else{
		$('#userNameStr').hide();
		$('#userName').show();
	}
});*/
$('#whiteGameList').hide();
$('#whiteType').hide();
$('#type').change(function() {
    var typer = $(this).val();
	if (typer == 'whiteList') {
        $('#whiteGameList').show();
		$('#whiteType').show();
	}else{
		$('#whiteGameList').hide();
		$('#whiteType').hide();
	}
});

$('#whiteType').change(function() {
    var whiteType = $('#whiteTypeSelect').val();
	if (whiteType == 'simulator') {
        $('#whiteGameList').hide();
    }else{
        $('#whiteGameList').show();
    }
});

$(function() {
    get_w_specialName();
    $("#whiteUpper").change(function(){
        $("#whiteSpecial option[text!='']").remove();
        $("#whiteSpecial").append('<option value="">请选择</option>').change();
        get_w_specialName();
        return false;
    });
    get_w_games();
    $("#whiteSpecial").change(function(){
        get_w_games();
        return false;
    });

    function get_w_specialName() {
        var whiteUpper = $('#whiteUpper').val();
        var whiteSpecial = $('#whiteSpecial').val();
        if(whiteUpper == ''){
            $("#whiteSpecial option[text!='']").remove();
            $("#whiteSpecial").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkGame&a=getSpecialName",
            data: "upperName="+whiteUpper+"&specialName="+whiteSpecial,
            dataType: 'text',

            success: function(result){
                $("#whiteSpecial option[text!='0']").remove();
                $("#whiteSpecial").append(result);
            }
        });
        return false;
    }

    function get_w_games() {
            var whiteUpper = $('#whiteUpper').val();
            var whiteSpecial = $('#whiteSpecial').val();
            var whiteGame = $('#whiteGame').val();
            if(whiteSpecial == ''){
                $("#whiteGame option[text!='']").remove();
                $("#whiteGame").append('<option value="">请选择</option>').change();
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/?m=sdkGame&a=getGameName",
                data: "upperName="+whiteUpper+"&specialName="+whiteSpecial+"&game="+whiteGame,
                dataType: 'text',

                success: function(result){
                    $("#whiteGame option[text!='0']").remove();
                    $("#whiteGame").append(result);
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