<?php /* Smarty version Smarty-3.1.11, created on 2022-10-21 14:52:57
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\priv\editmenu.html" */ ?>
<?php /*%%SmartyHeaderCode:3189761e4c8bd215e57-39156786%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '029a06d6cdec9d6b162d77431867193e13e897d8' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\priv\\editmenu.html',
      1 => 1542352471,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3189761e4c8bd215e57-39156786',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61e4c8bd3c39c7_85658168',
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
<?php if ($_valid && !is_callable('content_61e4c8bd3c39c7_85658168')) {function content_61e4c8bd3c39c7_85658168($_smarty_tpl) {?><!DOCTYPE html>
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
    	
<script>
$(function(){
	$('#search').click(function(){
		var uid = $('#uid').val();
		location.href="index.php?m=priv&a=addrole&menuid=<?php echo $_smarty_tpl->tpl_vars['menuid']->value;?>
&uid="+uid;
	});
	$('#parentid').change(function(){
		location.href = 'index.php?m=priv&a=editmenu&id=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
&parentid='+$('#parentid').val();
	});
});
</script> 
<h3><span><a href="index.php?m=priv&a=listmenu">返回菜单列表</a></span>修改菜单</h3>
<form action="index.php?m=priv&a=editmenu&do" method="post" class="searchbox">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" />
        <p><span>父菜单:</span>
        <select name="form[parentid]" id="parentid"/>
            <option value="0">頂級菜单</option>
            <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topmenulist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
            <option <?php if ($_smarty_tpl->tpl_vars['parentid']->value){?> <?php if ($_smarty_tpl->tpl_vars['parentid']->value==$_smarty_tpl->tpl_vars['v']->value['id']){?>selected="selected"<?php }?><?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['v']->value['current']){?>selected="selected"<?php }?><?php }?> value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" ><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</option>
            <?php } ?>
        </select>
        </p>
        <p><span>菜单名:</span>
        <input type="text" name="form[name]" class="text" value="<?php echo $_smarty_tpl->tpl_vars['menurow']->value['name'];?>
"/>
        </p>
        <p><span>排序:</span>
        <input type="text" name="form[sort]" class="text" value="<?php echo $_smarty_tpl->tpl_vars['menurow']->value['sort'];?>
"/>
        </p>
        <p><span>链接:</span>
        <input type="text" name="form[link]" class="text" value="<?php echo $_smarty_tpl->tpl_vars['menurow']->value['link'];?>
"/>
        </p>
        <p><span>唯一标识:</span>
        <input type="text" name="form[flag]" class="text" value="<?php echo $_smarty_tpl->tpl_vars['menurow']->value['flag'];?>
" /><font color="red">(英文)</font>
        </p>
        <p><span>是否显示:</span>
        <select name="form[display]" />
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['menurow']->value['display']==1){?>selected="selected"<?php }?>>是</option>
            <option value="0" <?php if ($_smarty_tpl->tpl_vars['menurow']->value['display']==0){?>selected="selected"<?php }?>>否</option>
        </select>
        </p>
        
        <?php if ($_smarty_tpl->tpl_vars['parentid']->value!=0){?>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['modulelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
        <h3><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</h3><p>
        <?php  $_smarty_tpl->tpl_vars['v1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v1']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v1']->key => $_smarty_tpl->tpl_vars['v1']->value){
$_smarty_tpl->tpl_vars['v1']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['v1']->key;
?>
        <span style="min-width:160px; text-align:left; margin:3px 15px 0px 0px;"><input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['menurow']->value['parentid']!=0){?> name="form[aid][]" <?php }?><?php if ($_smarty_tpl->tpl_vars['v1']->value['hasmenu']){?>checked="checked"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['v1']->value['id'];?>
"/><?php echo $_smarty_tpl->tpl_vars['v1']->value['aname'];?>
</span>
        <?php } ?>
        </p>
        <?php } ?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['parentid']->value==0){?>
        <p><span>选择入口菜单:</span>
        <select <?php if ($_smarty_tpl->tpl_vars['menurow']->value['parentid']==0){?> name="form[aid][]" <?php }?> >
        <option value="">请选择</option>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['modulelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
        <?php  $_smarty_tpl->tpl_vars['v1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v1']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v1']->key => $_smarty_tpl->tpl_vars['v1']->value){
$_smarty_tpl->tpl_vars['v1']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['v1']->key;
?>          
        <option <?php if ($_smarty_tpl->tpl_vars['v1']->value['hasmenu']){?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['v1']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
/<?php echo $_smarty_tpl->tpl_vars['v1']->value['aname'];?>
</option>
        <?php } ?>
        <?php } ?>
        </select>
        </p>
        <?php }?>
        <p class="inline"><button type="submit" name="do" class="su"/ style="margin-left:4px;">立即提交</button><button type="reset" class="su">條件重置</button></p>						
  </form>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>