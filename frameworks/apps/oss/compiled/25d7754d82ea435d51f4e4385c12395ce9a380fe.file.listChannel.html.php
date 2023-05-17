<?php /* Smarty version Smarty-3.1.11, created on 2022-09-29 12:16:53
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkChannel\listChannel.html" */ ?>
<?php /*%%SmartyHeaderCode:830361791449736c06-92333086%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25d7754d82ea435d51f4e4385c12395ce9a380fe' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkChannel\\listChannel.html',
      1 => 1663587006,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '830361791449736c06-92333086',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61791449a4fc34_06279537',
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
<?php if ($_valid && !is_callable('content_61791449a4fc34_06279537')) {function content_61791449a4fc34_06279537($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
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
<!--START 列表頁-->
	<h3>
		<?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==24){?>
		<span><a href="/index.php?m=sdkChannel&a=addChannel">新增渠道</a></span>
		<?php }?>
		渠道列表
	</h3>
        <form class="searchbox" action="index.php?m=sdkChannel&a=listChannel" method="post">
		<?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==14||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==17){?>
        <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
        <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
    	<?php }?>
	    <input type="hidden" name="batchesChannelId" value="" id="batchesChannelId" />
	    <input type="hidden" name="operation" value="" id="operation" />
	    <input type="hidden" name="status" value="" id="status" />
        <p>
            <span style="width: 50px;">游戏：</span>
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
                <option value="0">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['game']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['data']->value===$_smarty_tpl->tpl_vars['gameAlias']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
                <?php } ?>
            </select>
            <span>渠道：</span>
			<select name="channelId">
                <option value="0">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['channelId']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>
            <!--<span>广告商：</span>
            <select name="adsChannel" style="width: 156px;">
                <option value="">请选择</option>
                <option value="gdt">广点通</option>
                <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adsChannels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['adsChannel']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>-->
			<span>备注信息：</span>
            <input type="text" name="remarks" value="<?php echo $_smarty_tpl->tpl_vars['remarks']->value;?>
"/> 
			<span>升级记录：</span>
            <select name="upGradeMark" style="width: 156px;">
                <option value="">请选择</option>
                <option value="1" <?php if (($_smarty_tpl->tpl_vars['upGradeMark']->value==1)){?>selected="selected"<?php }?>>开启</option>
                <option value="2" <?php if (($_smarty_tpl->tpl_vars['upGradeMark']->value==2)){?>selected="selected"<?php }?>>关闭</option>
			</select>
			<input type="submit" class="su" value="查询" style="margin-left: 30px;" />
			<?php if ($_smarty_tpl->tpl_vars['editor']->value==1){?>
			<p>
				<span>升级记录控制：</span>
				<button type="submit" class="su" onclick="fun(1)" id="switch">开启</button>
				<button type="submit" class="su" onclick="fun(0)" id="switch">关闭</button>
			</p>
			<?php }?>
        </form>
	<table class="list">
		<tr style="background-color:#CCCCCC;">
			<?php if ($_smarty_tpl->tpl_vars['editor']->value==1){?><th><input type="checkbox" onclick="swapCheck()" /></th><?php }?>
			<!--<th>游戏别名</th>-->
			<th>游戏名称</th>
			<th width="7%">备注</th>
			<th>渠道appKey</th>
			<th>渠道代号</th>
			<th>渠道名称</th>
			<th>包号</th>
			<th>广告商</th>
			<th>回调参数</th>
			<th>H5链接</th>
			<th>等级记录</th>
			<th>双端互通</th>
			<th>操作</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['game_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
		<tr align="center" <?php if ($_smarty_tpl->tpl_vars['game']->value['appid']){?>style="background-color:#f7fbfe"<?php }?>>
			<?php if ($_smarty_tpl->tpl_vars['editor']->value==1){?><td><input type="checkbox" name="id" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" /></td><?php }?>
			<!--<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['gameAlias'],15,"..",true);?>
</td>-->
			<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['gameName'],10,"..",true);?>
</td>
			<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['remarks'],10,"..",true);?>
</td>
			<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['appKey'],25,"..",true);?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['channelId'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['channelName'];?>
</td>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['apkNum'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['adsName']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['adsName'];?>
<?php }?></td>
			<td><?php echo $_smarty_tpl->tpl_vars['callbackUrl']->value;?>
<?php if ($_smarty_tpl->tpl_vars['game']->value['channelId']<600000){?>callback/<?php }else{ ?>webCallback/<?php }?><?php echo $_smarty_tpl->tpl_vars['game']->value['channelId'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['channelId']>=600000){?><?php echo $_smarty_tpl->tpl_vars['callbackUrl']->value;?>
web/<?php echo $_smarty_tpl->tpl_vars['game']->value['channelId'];?>
/<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
<?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['upGradeMark']==1){?><font color="#FF0000">开启</font><?php }else{ ?><font>关闭</font><?php }?></td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['interflow']==0){?>不互通<?php }elseif($_smarty_tpl->tpl_vars['game']->value['interflow']==1){?><font color="#FF0000">互通<font><?php }?></td>
			<td><a href="/index.php?m=sdkChannel&a=editChannel&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
&do=view" target="_blank">查看</a>
			<?php if (($_smarty_tpl->tpl_vars['gid']->value==1&&$_smarty_tpl->tpl_vars['editor']->value==1)||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==24){?>
			 | <a href="/index.php?m=sdkChannel&a=editChannel&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['game']->value['specialName'];?>
" target="_blank">编辑</a>
			<?php }?>
			<?php if (($_smarty_tpl->tpl_vars['gid']->value==1&&$_smarty_tpl->tpl_vars['editor']->value==1)||$_smarty_tpl->tpl_vars['gid']->value==24){?>
			 | <a href="/index.php?m=sdkChannel&a=delChannel&&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a>
			<?php }?>
			</td>
		</tr>
                <?php }
if (!$_smarty_tpl->tpl_vars['game']->_loop) {
?>
                <td colspan="8" ><font color="red">暂无数据</font></td>
		<?php } ?>
	</table>
	<div id="pager"></div>

<script src="js/pager.js"></script>
<script>
$('.delete_confirm').click(function() {
	return confirm('數據不可恢復，你確定要刪除嗎？');
});
$('#switch').click(function() {
	return confirm('确定修改选中项的升级记录状态？');
});
function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=sdkChannel&a=listChannel&page=" + page + "&game=<?php echo $_smarty_tpl->tpl_vars['gameAlias']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&upGradeMark=<?php echo $_smarty_tpl->tpl_vars['upGradeMark']->value;?>
&remarks=<?php echo $_smarty_tpl->tpl_vars['remarks']->value;?>
&channelId=<?php echo $_smarty_tpl->tpl_vars['channelId']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;

var isCheckAll = false; 
function swapCheck() { 
    if (isCheckAll) { 
        $("input[type='checkbox']").each(function() { 
            this.checked = false; 
        }); 
        isCheckAll = false; 
    } else { 
        $("input[type='checkbox']").each(function() { 
            this.checked = true; 
        }); 
        isCheckAll = true; 
    } 
} 
function fun(status){
    obj = document.getElementsByName("id");
    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
    $("#operation").val("upGradeMark");
    $("#batchesChannelId").val(check_val);
    $("#status").val(status);
}
</script>
<!--END 列表頁-->

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>