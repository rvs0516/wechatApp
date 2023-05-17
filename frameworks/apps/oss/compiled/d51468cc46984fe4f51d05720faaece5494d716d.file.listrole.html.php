<?php /* Smarty version Smarty-3.1.11, created on 2022-09-08 15:39:03
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\priv\listrole.html" */ ?>
<?php /*%%SmartyHeaderCode:1572761761d32466246-49176959%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd51468cc46984fe4f51d05720faaece5494d716d' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\priv\\listrole.html',
      1 => 1602671837,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1572761761d32466246-49176959',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61761d32729439_43137267',
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
<?php if ($_valid && !is_callable('content_61761d32729439_43137267')) {function content_61761d32729439_43137267($_smarty_tpl) {?><!DOCTYPE html>
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
    	
<div id="popbg"></div>
<div class="pop" style="display:none">
    <p class="popclose"><a href="#"></a></p>
    <p class="popcontent">
    <div style="padding:5px;"> <span style="font-size:12px;width:80px;">用戶组名称:</span> <span>
            <input type="text" id="name" />
        </span> </div>
    <div style="padding:5px;"> <span style="font-size:12px;width:80px; display:block;float:left">是否可見:</span> <span style="font-size:12px;width:80px; display:block;float:left">
            <select id="display">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
        </span> </div>
    <div style="text-align:center;clear:both;padding:5px;">
        <input type="button" value="提交" id="btn1" />
    </div>
</p>
</div>
<h3><span><a href="index.php?m=priv&a=addrole&op=add">添加角色</a></span>角色列表</h3>
    <form class="searchbox" action="/index.php?m=priv&a=listrole" method="post">
        <p>
            <span>角色组：</span>
            <select name="gid">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['grouplist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['gid']->value==$_smarty_tpl->tpl_vars['name']->value['id']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['name'];?>
</option>
                <?php } ?>
            </select>
            <span>角色名：</span>
            <input type="text" placeholder="请输入角色名" name="ruid" value="<?php echo $_smarty_tpl->tpl_vars['ruid']->value;?>
"/>
        </p>
        <table style="clear:both;margin-top:10px; float:right;width:100%;">
            <tr>
                <td align="left"><button type="submit" class="su inline" style=" margin-left: 30px;">查询</button>
            </tr>
        </table>
    </form>
<table class="list">
	<thead>
        <th>角色名</th>
        <th>角色组</th>
        <th>操作</th>
    </thead>
    <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['rolelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
    <tr>
        <td><?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
</td>
        <td><a style="cursor:pointer" href="index.php?m=priv&a=delrole&uid=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
" class="delete">刪除</a>&nbsp;<a href="index.php?m=priv&a=addrole&uid=<?php echo $_smarty_tpl->tpl_vars['v']->value['uid'];?>
&menuid=<?php echo $_smarty_tpl->tpl_vars['menuid']->value;?>
&op=edit">修改</a></td>
    </tr>
    <?php } ?>
</table>
</td>
</tr>
</table>
<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
    function gotoNext(page,pagesize){
        window.location.href="index.php?m=priv&a=listrole&page="+page;
    }
    $(function(){
		$(".delete").click(function(){
				return confirm("確定刪除？");
			})
        var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['rowcount']->value;?>
',5,'<?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
','gotoNext').GetText();
        $('#pager').html('');
        $('#pager').html(pageStr);
        $('#btn1').click(function(){
            var name=$('#name').val();
            var display=$('#display').val();
            $.post('index.php?m=ajax&a=addaroup','name='+encodeURIComponent(name)+'&display='+display,function(data){
                var o=eval('('+data+')');
                if(o.status){
                    alert('操作成功！');
                    location.href="index.php?m=priv&a=listgroup";
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
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>