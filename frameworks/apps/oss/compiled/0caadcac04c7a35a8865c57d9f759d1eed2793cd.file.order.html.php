<?php /* Smarty version Smarty-3.1.11, created on 2022-10-22 10:58:32
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\statistics\order.html" */ ?>
<?php /*%%SmartyHeaderCode:23011620616b9b32743-56310970%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0caadcac04c7a35a8865c57d9f759d1eed2793cd' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\statistics\\order.html',
      1 => 1665743905,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23011620616b9b32743-56310970',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_620616ba2dbe95_05033302',
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
<?php if ($_valid && !is_callable('content_620616ba2dbe95_05033302')) {function content_620616ba2dbe95_05033302($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
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
    	
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<?php }?>
<h3>
    订单列表
</h3>
<form class="searchbox" action="/index.php?m=statistics&a=order" method="post">
    <?php if ($_smarty_tpl->tpl_vars['gid']->value==2||$_smarty_tpl->tpl_vars['gid']->value==11||$_smarty_tpl->tpl_vars['gid']->value==13||$_smarty_tpl->tpl_vars['gid']->value==14||$_smarty_tpl->tpl_vars['gid']->value==15||$_smarty_tpl->tpl_vars['gid']->value==17||$_smarty_tpl->tpl_vars['gid']->value==22){?>
        <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
        <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
    <?php }?>
    <input type="hidden" name="page" value="<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
" id="page" />
    <input type="hidden" name="operation" value="" id="operation" />
    <p>
        <span>订单状态：</span>
        <select name="ostatus" style="width: 160px; margin-right: 120px">
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['ostatus']->value==1){?>selected="selected"<?php }?>>成功</option>
            <option value="0" <?php if ($_smarty_tpl->tpl_vars['ostatus']->value==0){?>selected="selected"<?php }?>>未完成或失败</option>
            <option value="4" <?php if ($_smarty_tpl->tpl_vars['ostatus']->value==4){?>selected="selected"<?php }?>>QA订单</option>
            <option value="5" <?php if ($_smarty_tpl->tpl_vars['ostatus']->value==5){?>selected="selected"<?php }?>>渠道虚拟支付</option>
        </select>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==6||$_smarty_tpl->tpl_vars['gid']->value==17){?>
            <span>投放来源：</span><label><input type="checkbox" name="openAd" value="1" <?php if (($_smarty_tpl->tpl_vars['openAd']->value==1)){?>checked="checked"<?php }?> />渠道推广</label>
        <?php }?>
    </p>
    <p>
        <span>起始时间：</span>
        <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})" style="width: 115px;"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )" style="width: 115px;">
        <span>订单号：</span>
        <input type="text" placeholder="请输入订单号" name="orderId" value="<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
" style="width: 147px;"/>
        <span>帐号：</span>
        <input type="text" placeholder="请输入帐号" name="userName" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" style="width: 147px;"/>
    </p>
    <p>
        <span>来自游戏：</span>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value!=8){?>
            <select name="upperName" id="upperName" style="width: 90px;">
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
            <select name="specialName" id="specialName" style="width: 90px;">
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
            <select name="game" id="game" style="width: 90px;">
        <?php }else{ ?>
            <select name="game" id="game" style="width: 188px;">
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
        <span>渠道：</span>
        <select name="channel" id="channel" style="width: 160px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['channel']->value==$_smarty_tpl->tpl_vars['key1']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
            <?php } ?>
        </select>
        <span>包号：</span>
        <select name="apkNum" id="apkNum" style="width: 160px;">
            <option value="">请选择</option>
            <?php  $_smarty_tpl->tpl_vars['apk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['apk']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['apk']->key => $_smarty_tpl->tpl_vars['apk']->value){
$_smarty_tpl->tpl_vars['apk']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['apk']->key;
?>
            <?php if (($_smarty_tpl->tpl_vars['apkNum']->value!='IOS')){?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['apkNum']->value==$_smarty_tpl->tpl_vars['apk']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['apk']->value;?>
</option>
            <?php }else{ ?>
                <option value="IOS" selected="selected">IOS</option>
            <?php }?>
            <?php } ?>
        </select>
        </p>
        <p>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value!=2&&$_smarty_tpl->tpl_vars['gid']->value!=14){?>
        <span>支付方式：</span>
        <select name="paymentId" style="width: 185px;">
            <option value="">请选择</option>
            <option value="10" <?php if (($_smarty_tpl->tpl_vars['paymentId']->value==10)){?>selected="selected"<?php }?>>H5切换方式</option>
            <option value="9"<?php if (($_smarty_tpl->tpl_vars['paymentId']->value==9)){?>selected="selected"<?php }?>>微信</option>
            <option value="7"<?php if (($_smarty_tpl->tpl_vars['paymentId']->value==7)){?>selected="selected"<?php }?>>支付宝</option>
            <option value="11"<?php if (($_smarty_tpl->tpl_vars['paymentId']->value==11)){?>selected="selected"<?php }?>>ios</option>
            <option value="12"<?php if (($_smarty_tpl->tpl_vars['paymentId']->value==12)){?>selected="selected"<?php }?>>平台币</option>
            <option value="104"<?php if (($_smarty_tpl->tpl_vars['paymentId']->value==104)){?>selected="selected"<?php }?>>渠道平台</option>
        </select>
        <span style="width: 184px;">区服ID：</span>
        <?php }else{ ?>
        <span>区服ID：</span>
        <?php }?>
        <input type="text" placeholder="区服ID" name="serverId" value="<?php echo $_smarty_tpl->tpl_vars['serverId']->value;?>
" style="width: 147px;"/>
        <?php if ($_smarty_tpl->tpl_vars['gid']->value!=2){?>
        <span>角色ID：</span>
        <?php }else{ ?>
        <span style="width: 205px;">角色ID：</span>
        <?php }?>
        <input type="text" placeholder="请输入角色ID" name="roleId" value="<?php echo $_smarty_tpl->tpl_vars['roleId']->value;?>
" style="width: 147px;"/>
    </p>
    <!--<p>
        <span>每页总充值：</span><?php echo $_smarty_tpl->tpl_vars['num_omoney']->value;?>
 <span>总充值：</span><?php if ($_smarty_tpl->tpl_vars['total_omoney']->value){?><?php echo $_smarty_tpl->tpl_vars['total_omoney']->value;?>
<?php }else{ ?>0<?php }?>&nbsp;&nbsp;&nbsp;<span>每页元宝数：</span><?php echo $_smarty_tpl->tpl_vars['num_pay_gold']->value;?>
&nbsp;&nbsp;&nbsp;<span>总元宝数：</span><?php echo $_smarty_tpl->tpl_vars['agent_pay_gold']->value;?>

    </p>-->
	<table style="clear:both;margin-top:10px; float:right;width:98%;">
		<tr>
			<td align="left" style="width: 100px"><button type="submit" class="su inline" id="ccc" >查询</button></td>
			<td align="left">
				<button type="submit" class="su" value="report" id="report">导出</button><font style="color: #f00;"> * 考虑服务器性能损耗，单次最多导出20000条</font>
			</td>
		</tr>
	</table>	
    <?php if ($_smarty_tpl->tpl_vars['gid']->value!=8&&$_smarty_tpl->tpl_vars['gid']->value!=2){?>
        <?php if ($_smarty_tpl->tpl_vars['channel']->value==null){?>
            <p>
                <span>乾游总充值：</span><a style="color: red;text-decoration:none;" id="total"></a><a id="refresh" onclick="get_total()" style="color: red;cursor: pointer;">刷新</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font>考虑服务器性能损耗过大，查看该数据请点击“刷新”</font>
            </p>
        <?php }?>
    <?php }?>	
</form>
<table class="list">
    <tr style="background-color:#CCCCCC;">
        <th style="width: 6%;">账号</th>
        <th style="width:13%;">订单号</th>
        <th style="width:9%;">上级游戏名</th>
        <th style="width:9%;">游戏</th>
        <th style="width:7%;">服务器</th>
        <th style="width:6%">角色</th>
        <th style="width:6%">角色ID</th>
        <th style="width:11%;">充值时间</th>
        <th style="width:10%;">金额/元宝</th>
        <th style="width:4%;">渠道</th>
        <th style="width: 5%">所属包体</th>
        <th style="width: 4%;">通道</th>
        <?php if ($_smarty_tpl->tpl_vars['ostatus']->value!='4'){?>
            <?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi')||($_smarty_tpl->tpl_vars['gid']->value==16&&$_smarty_tpl->tpl_vars['ostatus']->value!='1')||($_smarty_tpl->tpl_vars['gid']->value==15&&$_smarty_tpl->tpl_vars['ostatus']->value=='0')){?>
            <th style="width: 7%;">操作</th>
            <?php }?>
        <?php }?>
    </tr>
	<?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['order_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value){
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
	<tr <?php if ($_smarty_tpl->tpl_vars['order']->value['ostatus']==1){?>style="color:green;"<?php }?>>
        <?php if ($_smarty_tpl->tpl_vars['order']->value['vip_sign']&&($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22||$_smarty_tpl->tpl_vars['gid']->value==24)){?>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
"><a href="?m=vipGuest&a=vipList&userName=<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
" style="color:blue"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userName'],15,"..",true);?>
</a><br><font style="color:blue;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userid'],15,"..",true);?>
</font></td>
        <?php }else{ ?>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['userName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userName'],15,"..",true);?>
<br><font style="color:blue;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['userid'],15,"..",true);?>
</font></td>
        <?php }?>
        <td><font color="red"><?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
</font></td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['upperName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['upperName'],7,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['gameName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['gameName'],8,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['server'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['server'],6,"..",true);?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
"><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleName'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['roleName'],7,"..",true);?>
</a></td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleId'];?>
"><a class="btn" data-clipboard-text="<?php echo $_smarty_tpl->tpl_vars['order']->value['roleId'];?>
" style="text-decoration:none; color: #444;"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['roleId'],7,"..",true);?>
</a></td>
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['order']->value['time'],"%y-%m-%d %H:%M");?>
</td>
        <td <?php if ($_smarty_tpl->tpl_vars['order']->value['channelname']){?>style="color:red;"<?php }?>><?php echo $_smarty_tpl->tpl_vars['order']->value['money'];?>
<?php if ($_smarty_tpl->tpl_vars['order']->value['currency']!='人民币'){?>(<?php echo $_smarty_tpl->tpl_vars['order']->value['currency'];?>
)<?php }?>/<?php echo $_smarty_tpl->tpl_vars['order']->value['gold'];?>
</td>
        <td title="<?php echo $_smarty_tpl->tpl_vars['order']->value['channelName'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['order']->value['channelName'],4,"..",true);?>
</td>
        <td>
			<?php echo $_smarty_tpl->tpl_vars['order']->value['apkNum'];?>

		</td>
        <td><?php if (($_smarty_tpl->tpl_vars['order']->value['paymentId']==9)){?>微信<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==7)){?>支付宝<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==11)){?>ios<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==12)){?>平台币<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==101)){?>谷歌支付<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==102)){?>电子钱包<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==103)){?>sms<?php }elseif(($_smarty_tpl->tpl_vars['order']->value['paymentId']==104)){?>渠道平台<?php }?></td>
        <?php if ($_smarty_tpl->tpl_vars['ostatus']->value!='4'){?>
            <?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='guofengchi')){?>
                <td><a href="index.php?m=statistics&a=orderReplace&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
" id="replace">补单</a><?php if ($_smarty_tpl->tpl_vars['ostatus']->value=='1'){?>|<a href="index.php?m=statistics&a=compenOrderReplace&type=3&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
" id="replace2">QA订单</a><?php }?><?php if ($_smarty_tpl->tpl_vars['ostatus']->value=='0'&&$_smarty_tpl->tpl_vars['order']->value['channelId']=='000000'){?>|<a href="index.php?m=statistics&a=changeOrderMoney&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
" id="changeOrderMoney">防刷单</a><?php }?></td>
            <?php }elseif($_smarty_tpl->tpl_vars['gid']->value==16&&$_smarty_tpl->tpl_vars['ostatus']->value!='1'){?>
                <td><a href="index.php?m=statistics&a=compenOrderReplace&type=1&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
" id="replace2">发货</a></td>
            <?php }elseif($_smarty_tpl->tpl_vars['gid']->value==15&&$_smarty_tpl->tpl_vars['ostatus']->value=='0'&&$_smarty_tpl->tpl_vars['order']->value['channelId']=='000000'){?>
                <td><a href="index.php?m=statistics&a=changeOrderMoney&orderId=<?php echo $_smarty_tpl->tpl_vars['order']->value['orderId'];?>
" id="changeOrderMoney">防刷单</a></td>
            <?php }?>
        <?php }?>
    </tr>
	<?php }
if (!$_smarty_tpl->tpl_vars['order']->_loop) {
?>
    <tr>
        <td colspan="11">无渠道数据</td>
    </tr>
	<?php } ?>
</table>
<div id="pager"></div>
<script src="js/pager.js"></script>
<script>
$('.list:odd').css({ 'backgroundColor': '#f5f5f5' });
function gotoNext(page,pagesize){
	$('#page').val(page);
	$('.searchbox').submit();
}
$("#ccc").click(function() {
	$("#operation").val("");
});
$("#report").click(function() {
	$("#operation").val("report");
	$('.searchbox').submit();
});
function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=statistics&a=order&page=" + page+"&game=<?php echo $_smarty_tpl->tpl_vars['game']->value;?>
&channel=<?php echo $_smarty_tpl->tpl_vars['channel']->value;?>
&start_date=<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
&end_date=<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
&ostatus=<?php echo $_smarty_tpl->tpl_vars['ostatus']->value;?>
&userName=<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
&paymentId=<?php echo $_smarty_tpl->tpl_vars['paymentId']->value;?>
&apkNum=<?php echo $_smarty_tpl->tpl_vars['apkNum']->value;?>
&upperName=<?php echo $_smarty_tpl->tpl_vars['upperName']->value;?>
&specialName=<?php echo $_smarty_tpl->tpl_vars['specialName']->value;?>
&roleId=<?php echo $_smarty_tpl->tpl_vars['roleId']->value;?>
&openAd=<?php echo $_smarty_tpl->tpl_vars['openAd']->value;?>
&serverId=<?php echo $_smarty_tpl->tpl_vars['serverId']->value;?>
&gid=<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
&orderId=<?php echo $_smarty_tpl->tpl_vars['orderId']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>
<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
    $('#replace').click(function() {
        return confirm('订单将重新回调，确定要执行？');
    });
    $('#replace2').click(function() {
        return confirm('订单属性将修改，确定要执行？');
    });
    function get_total() {
        var game = $('#game').val();
        var upperName = $('#upperName').val();
        var specialName = $('#specialName').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        $.ajax({
            type: "POST",
            url: "/?m=statistics&a=platformRechargeTotal",
            data: "game="+game+"&upperName="+upperName+"&specialName="+specialName+"&start_date="+start_date+"&end_date="+end_date,
            dataType: 'text',

            success: function(result){
                $("#total").text(result);
                $("#refresh").hide();
            }
        });
        return false;
    }
</script>
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
        if(game == ''){
            $("#channel option[text!='']").remove();
            $("#channel").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkChannel&a=getGameChannels",
            data: "game="+game+"&channelId="+channel,
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