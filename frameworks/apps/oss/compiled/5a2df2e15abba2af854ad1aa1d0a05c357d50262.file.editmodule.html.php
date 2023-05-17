<?php /* Smarty version Smarty-3.1.11, created on 2022-01-17 15:51:52
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\priv\editmodule.html" */ ?>
<?php /*%%SmartyHeaderCode:891561e52018a6f177-63293830%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a2df2e15abba2af854ad1aa1d0a05c357d50262' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\priv\\editmodule.html',
      1 => 1542352471,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '891561e52018a6f177-63293830',
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
  'unifunc' => 'content_61e52018b6d036_62446863',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_61e52018b6d036_62446863')) {function content_61e52018b6d036_62446863($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    	
<h3><span><a href="index.php?m=priv&a=listmodule">返回模块列表</a></span>修改模块</h3>
<form action="index.php?m=priv&a=editmodule" method="post" class="searchbox">
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['id'];?>
" />
	<p><span>选择模块：</span><select name="form[module]">
    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['modulelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
    <option value="<?php echo $_smarty_tpl->tpl_vars['v']->value['module'];?>
" <?php if ($_smarty_tpl->tpl_vars['v']->value['current']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
/<?php echo $_smarty_tpl->tpl_vars['v']->value['module'];?>
</option>
    <?php } ?>
    </select>&nbsp;<a href="#" class="showbox">添加</a></p>
    <p><span>动作名称：</span><input type="text" class="text" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['name'];?>
" name="form[name]"/></p>
    <p><span>动作标识：</span><input type="text" class="text" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['action'];?>
" name="form[action]"/></p>
    <p><span>参数：</span><input type="text" class="text" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['param'];?>
" name="form[param]"/></p>
    <p><span>外鏈：</span><input type="text" class="text" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['outlink'];?>
" name="form[outlink]"/></p>
    <p><span>排序：</span><input type="text" class="text" value="<?php echo $_smarty_tpl->tpl_vars['roleaction']->value['sort'];?>
" name="form[sort]"/></p>
    <p class="inline">是否显示：<input type="checkbox" value="1" name="form[display]" <?php if ($_smarty_tpl->tpl_vars['roleaction']->value['display']=='1'){?> checked<?php }?>/> 是否後台<input type="checkbox" value="1" name="form[isadmin]" 
<?php if ($_smarty_tpl->tpl_vars['roleaction']->value['isadmin']=='1'){?> checked<?php }?> /></p>
    <p><span style="float:left;vertical-align:middle;margin-top:30px;">说明：</span><textarea name="form[des]" class="text" ><?php echo $_smarty_tpl->tpl_vars['roleaction']->value['des'];?>
</textarea></p>
    <p class="inline" style="margin-top:4px;"><button type="submit" name="do" class="su">立即提交</button><button type="reset" class="su">條件重置</button></p>
</form>

<div id="popbg"></div>
<div class="pop searchbox">
	<h3><span><a href="#" class="popclose">關閉</a></span>新增模块</h3>
    <p><span>模块名称：</span><input type="text" id="name"/></p>
    <p><span>模块标识：</span><input type="text" id="module"/></p>
    <p><span>排序：</span><input type="text" id="sort"/></p>
    <p class="inline"><button type="submit" class="su popsubmit" style="margin-top:2px;">立即提交</button></p>
</div>
<script src="/js/pop.js"></script>
<script>
$(function(){
	$('.popsubmit').click(function(){
		var name=$('#name').val();
		var module=$('#module').val();
		var ssort=$('#sort').val();
		$.post('index.php?m=ajax&a=addmodule','name='+name+'&module='+module+'&sort='+ssort,function(data){
			var o=eval('('+data+')');
			if(o.status){
				alert('操作成功！');
				location.reload();
			} else {
				alert(o.msg);
			}
		});
	});
});
</script>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>