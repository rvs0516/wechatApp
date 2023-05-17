<?php /* Smarty version Smarty-3.1.11, created on 2022-01-17 09:36:06
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\honouredGuest\honouredGuest.html" */ ?>
<?php /*%%SmartyHeaderCode:2352561e4c8068f1fc4-39668453%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '442b324c8379a8bdfd58396d26746adbe885879b' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\honouredGuest\\honouredGuest.html',
      1 => 1565665468,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2352561e4c8068f1fc4-39668453',
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
  'unifunc' => 'content_61e4c806d1c747_18612660',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_61e4c806d1c747_18612660')) {function content_61e4c806d1c747_18612660($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
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
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
<h3>
    VIP用户列表
</h3>
<form class="searchbox" action="/index.php?m=honouredGuest&a=honouredGuest" method="post">
    <p>
        <input type="hidden" name="type" value="" id="type" />
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
		<span>账号：</span>
		<input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" name="userName" id="userName" placeholder="请输入需要搜索的账号" />
        <span>登录时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
    </p>
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==12){?>
    <p>
        <span>状态：</span>
        <select name="status">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected="selected"<?php }?>>已审核</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value==2){?>selected="selected"<?php }?>>未审核</option>
        </select>
    </p>
    <?php }?>
	<table style="clear:both;margin-top:10px; float:right;width:100%;">
		<tr>
			<td align="left" style="width: 100px"><button type="submit" class="su inline" id="ccc" >查询</button></td>
            <td align="left">
                <?php if ((($_smarty_tpl->tpl_vars['gid']->value==1)||($_smarty_tpl->tpl_vars['gid']->value==12))){?><button type="submit" class="su" value="report" id="report">导出</button><font>考虑服务器性能损耗，一次导出最多导出三千条</font><?php }?>
            </td>

		</tr>
	</table>
</form>
<table class="list">
    <tr style="background-color:#CCCCCC;">
        <th width="10%">账号</th>
        <th width="10%">上级游戏名</th>
        <th width="10%">来自游戏</th>
        <th width="10%">微信</th>
        <th width="10%">QQ</th>
        <th width="10%">最近登录时间</th>
        <th width="10%">最后点击充值</th>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==12){?>
        <th width="10%">归属</th>
        <?php }?>
        <th width="15%">操作</th>
    </tr>
	<?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dataList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
	<tr>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['weixin'];?>
</td>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['qq'];?>
</td>
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['loginTime'],"y-m-d");?>
</td>
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['payTime'],"y-m-d");?>
</td>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==12){?>
        <td><?php echo $_smarty_tpl->tpl_vars['order']->value['replace'];?>
</td>
        <?php }?>
        <td>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==12){?>
            <?php if ($_smarty_tpl->tpl_vars['order']->value['status']==1){?>
                <a href="javascript:void(0)" style="color: #CCCCCC">审核|</a>
            <?php }else{ ?>
                <a href="/index.php?m=honouredGuest&a=honouredGuest&operation=allow&id=<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
">审核|</a>
            <?php }?>
            <a href="/index.php?m=honouredGuest&a=honouredGuest&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
" class="del">删除|</a>
        <?php }?>
        <a href="/index.php?m=honouredGuest&a=honouredGuest&operation=edit&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
&gameName=<?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
&gameAlias=<?php echo $_smarty_tpl->tpl_vars['order']->value['gameAlias'];?>
">修改|</a>
        <?php if ($_smarty_tpl->tpl_vars['order']->value['same']==1){?>
        <a href="javascript:void(0)" style="color: #CCCCCC">回访</a>
        <?php }else{ ?>
       <a href="/index.php?m=honouredGuest&a=honouredGuest&operation=revisit&id=<?php echo $_smarty_tpl->tpl_vars['order']->value['id'];?>
" class="revisit">回访</a>
        <?php }?>
        </td>
    </tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="10">无数据</td>
    </tr>
	<?php } ?>
</table>

<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$('.del').click(function() {
    return confirm('确定要删除用户信息？');
});
$('.revisit').click(function() {
    return confirm('确定已进行用户回访？');
});
$("#ccc").click(function() {
    $("#type").val("");
});
$("#report").click(function() {
    $("#type").val("report");
    $('.searchbox').submit();
});
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
    $('#page').val(page);
    $('.searchbox').submit();
}
function gotoNext(page,pagesize){
        window.location.href = "/index.php?m=honouredGuest&a=honouredGuest&page=" + page+"&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
";
    }
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>
<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='add'||$_smarty_tpl->tpl_vars['operation']->value=='edit'){?>
    <h3>
        <span><a href="/index.php?m=sdkGame&a=game">返回列表</a></span>
        <?php if ($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
            添加VIP用户
        <?php }else{ ?>
            编辑VIP用户
        <?php }?>
    </h3>
    <form action="/index.php?m=honouredGuest&a=honouredGuest&operation=save" method="post" class="searchbox" enctype="multipart/form-data">
        <?php if ($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
        <input type="hidden" name="isNew" value="1" />
        <input type="hidden" name="loginTime" value="<?php echo $_smarty_tpl->tpl_vars['loginTime']->value;?>
" />
        <?php }?>
        <input type="hidden" name="gameName" value="<?php echo $_smarty_tpl->tpl_vars['gameName']->value;?>
" />
        <input type="hidden" name="gameAlias" value="<?php echo $_smarty_tpl->tpl_vars['gameAlias']->value;?>
" />
        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['list']->value['id'];?>
" />
        <p>
            <span>玩家账号：</span>
            <input type="text" name="userName" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" readonly="readonly" />
        </p>
        <p>
            <span>微信：</span>
            <input type="text" name="weixin" value="<?php echo $_smarty_tpl->tpl_vars['list']->value['weixin'];?>
"/>
        </p>
        <p>
            <span>QQ：</span>
            <input type="text" name="qq" value="<?php echo $_smarty_tpl->tpl_vars['list']->value['qq'];?>
"/>
        </p>
        <p>
            <span>联系电话：</span>
            <input type="text" name="phoneNum" value="<?php echo $_smarty_tpl->tpl_vars['list']->value['phoneNum'];?>
"/>
        </p>
        <p>
            <span>用户生日：</span>
            <input type="text" name="birthday" value="<?php if ($_smarty_tpl->tpl_vars['list']->value['birthday']){?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['list']->value['birthday'],'%Y-%m-%d');?>
<?php }?>" onfocus="WdatePicker()" ></input>
        </p>
        <p>
            <span>备注：</span>
            <textarea style=" width: 550px;" name="remark" cols="" rows=""><?php echo $_smarty_tpl->tpl_vars['list']->value['remark'];?>
</textarea>
        </p>
        <p class="line">
            <button type="submit" name="do" class="su">立即提交</button>
        </p>
    </form>
<?php }?>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>