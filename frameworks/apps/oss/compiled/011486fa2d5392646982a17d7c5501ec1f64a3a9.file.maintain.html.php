<?php /* Smarty version Smarty-3.1.11, created on 2022-11-17 11:53:59
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\vipGuest\maintain.html" */ ?>
<?php /*%%SmartyHeaderCode:1696463524b822ac190-81778215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '011486fa2d5392646982a17d7c5501ec1f64a3a9' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\vipGuest\\maintain.html',
      1 => 1668657236,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1696463524b822ac190-81778215',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_63524b823e49e9_71028652',
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
<?php if ($_valid && !is_callable('content_63524b823e49e9_71028652')) {function content_63524b823e49e9_71028652($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.date_format.php';
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
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
    <h3>
        <span><a href="/index.php?m=vipGuest&a=maintain&operation=add">添加处理</a></span>
        账号跟进
    </h3>
    <form class="searchbox" action="/index.php?m=vipGuest&a=maintain" method="post">
    <input type="hidden" name="op" value="" id="op" />
    <p>
        <span>跟进情况：</span>
        <select  name="status" style="width: 150px;">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value=='1'){?>selected="selected"<?php }?>>已跟进</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value=='2'){?>selected="selected"<?php }?>>未跟进</option>
            <option value="3" <?php if ($_smarty_tpl->tpl_vars['status']->value=='3'){?>selected="selected"<?php }?>>停止通知</option>
        </select>
        <span>用户账号：</span>
        <input style="width: 180px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" name="userName" placeholder="请输入账号">
        <span>操作uid：</span>
        <input style="width: 180px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['editUid']->value;?>
" name="editUid">
        <span>操作时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
        <span>排序：</span>
        <select  name="sort" style="width: 150px;">
            <option value="id" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='id'){?>selected="selected"<?php }?>>记录降序</option>
            <option value="time" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='time'){?>selected="selected"<?php }?>>操作时间降序</option>
            <option value="frequency" <?php if ($_smarty_tpl->tpl_vars['sort']->value=='frequency'){?>selected="selected"<?php }?>>发送次数降序</option>
        </select>
    </p>
    <p>
        <button type="submit" name="do" class="su" id="submit">查询</button>
        <button type="submit" class="su" value="report" id="report">导出</button><font style="color: #f00;"> * 考虑服务器性能损耗，单次最多导出5000条</font>
    </p>
    </form>
    <table class="list">
        <tr style="background-color:#CCCCCC;">
            <th style="width: 10%">用户账号</th>
            <th style="width: 10%">处理类型</th>
            <th style="width: 10%">通知方式</th>
            <th style="width: 10%">通知邮件</th>
            <th style="width: 10%">通知次数</th>
            <th style="width: 10%">状态</th>
            <th style="width: 10%">操作时间</th>
            <th style="width: 10%">操作uid</th>
            <th style="width: 10%">操作</th>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
        <tr>
            <td><?php echo $_smarty_tpl->tpl_vars['game']->value['userName'];?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['game']->value['handleType']=='login'){?>登录通知<?php }?></td>
            <td><?php if ($_smarty_tpl->tpl_vars['game']->value['contactType']=='email'){?>邮件通知<?php }?></td>
            <td title="<?php echo $_smarty_tpl->tpl_vars['game']->value['contactAddress'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['contactAddress'],30,"..",true);?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['game']->value['frequency'];?>
</td>
            <td><?php if ($_smarty_tpl->tpl_vars['game']->value['status']==1){?><a style="color: green;">已跟进</a><?php }elseif($_smarty_tpl->tpl_vars['game']->value['status']==3){?><a style="color: red;">停止通知</a><?php }else{ ?>未跟进<?php }?></td>
            <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['game']->value['time'],"%y-%m-%d %H:%M");?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['game']->value['uid'];?>
</td>
            <td style="width:160px">
            <a href="/index.php?m=vipGuest&a=maintain&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a>
            |<a href="/index.php?m=vipGuest&a=maintain&operation=maintain&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="maintain">跟进</a>
            </td>
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
$('.delete_confirm').click(function() {
    return confirm('删除数据不可恢复，是否继续');
});
$("#report").click(function() {
    $("#op").val("report");
    $('.searchbox').submit();
});
$("#submit").click(function() {
    $("#op").val("");
});
function gotoNext(page,pagesize){
    window.location.href = "/index.php?m=vipGuest&a=maintain&page=" + page+ "&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
&editUid=<?php echo $_smarty_tpl->tpl_vars['editUid']->value;?>
&sort=<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
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
        <span><a href="/index.php?m=vipGuest&a=maintain">返回列表</a></span>
        添加处理
    </h3>
    <form action="/index.php?m=vipGuest&a=maintain&operation=save" method="post" class="searchbox" enctype="multipart/form-data">
        <p>
            <span>用户账号：</span>
            <!--<input style="width: 180px;" type="text" value="" name="userName" id="userName" placeholder="请输入账号">-->
            <textarea style="width:800px; height:60px;"  name="userNameStr" id="userNameStr" placeholder="单次处理条数不能超过50条"></textarea>
        </p>
        <p>
            <span>处理类型：</span>
            <label><input type="checkbox" value="login" name="handleType[]" id="login" checked="checked"/> 登录通知</label>
        </p>
        <p>
            <span>通知类型：</span>
            <label><input type="checkbox" value="email" name="contactType[]" id="login" checked="checked" /> 邮件</label>
        </p>
        <p>
            <span>通知邮箱：</span>
            <textarea style="width:800px; height:60px;"  name="contactAddress" id="contactAddress" placeholder="可添加多个邮箱地址，两个地址间用 | 分隔;不填默认使用chenhaogeng@jieyougame.com"></textarea>
        </p>
        <p>
            <button type="submit" name="do" class="su" id="submit">提交</button>
        </p>
    </form>

<?php }?>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>