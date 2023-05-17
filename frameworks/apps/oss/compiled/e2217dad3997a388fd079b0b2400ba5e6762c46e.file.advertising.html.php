<?php /* Smarty version Smarty-3.1.11, created on 2021-10-25 20:46:21
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\ads\advertising.html" */ ?>
<?php /*%%SmartyHeaderCode:261876176a71d5507f0-71420598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e2217dad3997a388fd079b0b2400ba5e6762c46e' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\ads\\advertising.html',
      1 => 1626871862,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '261876176a71d5507f0-71420598',
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
  'unifunc' => 'content_6176a71d8abec3_38553288',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6176a71d8abec3_38553288')) {function content_6176a71d8abec3_38553288($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<!--START 列表頁-->
	<h3>
		广告数据列表
	</h3>
        <form class="searchbox" action="index.php?m=ads&a=advertising" method="post">
	    <input type="hidden" name="operation" value="" id="operation" />
        <p>
            <span>渠道：</span>
			<select name="channel" id="channel">
                <option value="0">请选择</option>
                <?php if ($_smarty_tpl->tpl_vars['gid']->value==18){?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['adsChannel']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['adsChannel']->value){?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['channelName']->value;?>
</option>
                <?php }else{ ?>
                <option value="000368" <?php if ($_smarty_tpl->tpl_vars['channel']->value=='000368'){?> selected="selected" <?php }?>>VIVO</option>
                <option value="000020" <?php if ($_smarty_tpl->tpl_vars['channel']->value=='000020'){?> selected="selected" <?php }?>>OPPO</option>
                <option value="500001" <?php if ($_smarty_tpl->tpl_vars['channel']->value=='500001'){?> selected="selected" <?php }?>>华为</option>
                <?php }?>
			</select>
            <span>版本：</span>
            <select name="version">
                <option value="2.0" <?php if ($_smarty_tpl->tpl_vars['version']->value=='2.0'){?> selected="selected" <?php }?>>2.0</option>
                <option value="3.0" <?php if ($_smarty_tpl->tpl_vars['version']->value=='3.0'){?> selected="selected" <?php }?>>3.0</option>
            </select>
		</p>
		<p>
            <span>广告账号：</span>
            <select name="account" id="account">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['accountList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['account'];?>
" <?php if ($_smarty_tpl->tpl_vars['account']->value==$_smarty_tpl->tpl_vars['name']->value['account']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['account'];?>
</option>
                <?php } ?>
            </select>
            <span>计划ID：</span>
			<input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['planId']->value;?>
" name="planId"  placeholder="对应渠道的创意id或广告id"/>
            <span>表类型：</span>
			<select name="formType">
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['formType']->value=="1"){?>selected="selected"<?php }?>>小时</option>
                <option value="2" <?php if ($_smarty_tpl->tpl_vars['formType']->value=="2"){?>selected="selected"<?php }?>>天数</option>
                <option value="3" <?php if ($_smarty_tpl->tpl_vars['formType']->value=="3"){?>selected="selected"<?php }?>>汇总</option>
			</select>
			<span>起始时间：</span>
        	<input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})" style="width: 115px;"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )" style="width: 115px;">
		</p>
		<p>
			<input type="submit" class="su" value="查询" id="check" style="margin-left: 30px;" />
            <input type="submit" class="su" value="导出" id="report">
		</p>
        </form>
    <?php if ($_smarty_tpl->tpl_vars['channel']->value){?>
        <?php echo $_smarty_tpl->getSubTemplate ("./advertising/".((string)$_smarty_tpl->tpl_vars['channel']->value)."table.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        <div id="pager"></div>
    <?php }?>

<script src="js/pager.js"></script>
<script>
$("#check").click(function() {
    $("#operation").val("");
});
$("#report").click(function() {
    $("#operation").val("report");
    $('.searchbox').submit();
});
function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=ads&a=advertising&page=" + page + "&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&account=<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
&formType=<?php echo $_smarty_tpl->tpl_vars['formType']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&planId=<?php echo $_smarty_tpl->tpl_vars['planId']->value;?>
&version=<?php echo $_smarty_tpl->tpl_vars['version']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>
<script>
$(function() {
    getChannelAccount();
    $("#channel").change(function(){
        getChannelAccount();
        return false;
    });


    function getChannelAccount() {
            var channel = $('#channel').val();
            var account = $('#account').val();
            if(channel == ''){
                $("#account option[text!='']").remove();
                $("#account").append('<option value="">请选择</option>').change();
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/?m=ads&a=getChannelAccount",
                data: "channel="+channel+"&account="+account,
                dataType: 'text',

                success: function(result){
                    $("#account option[text!='0']").remove();
                    $("#account").append(result);
                }
            });
            return false;
        }

});
</script> 
<!--END 列表頁-->

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>