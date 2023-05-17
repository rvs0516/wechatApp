<?php /* Smarty version Smarty-3.1.11, created on 2021-10-27 16:59:49
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkChannel\editChannel.html" */ ?>
<?php /*%%SmartyHeaderCode:1075761791450a459c3-96001606%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b40666f5b519adcafcb65024d3a66da900d37ef' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkChannel\\editChannel.html',
      1 => 1635325160,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1075761791450a459c3-96001606',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_61791450e645b9_47563427',
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
<?php if ($_valid && !is_callable('content_61791450e645b9_47563427')) {function content_61791450e645b9_47563427($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    	
<style>
[data-tooltip] { position: relative; z-index: 2; cursor: help;}
[data-tooltip]:before,
[data-tooltip]:after { visibility: hidden; opacity: 0; pointer-events: none; display: inline-table; white-space: pre-line;}
[data-tooltip]:before { content: attr(data-tooltip); position: absolute; width: 500px; padding: 10px; border-radius: 3px; background-color: black; background-color: rgba(51, 51, 51, 0.9); color: white; text-align: left; font-size: 12px; bottom: calc(100% + 4px); left: 50%; -webkit-transform: translate(-50%, -3px); transform: translate(-50%, -3px);}
[data-tooltip]:after { position: absolute; bottom: calc(100% + 3px); left: 50%; margin-left: -5px; width: 0; border-top: 5px solid black; border-top: 5px solid rgba(51, 51, 51, 0.9); border-right: 5px solid transparent; border-left: 5px solid transparent; content: " "; font-size: 0; line-height: 0;}
[data-tooltip]:hover:before,
[data-tooltip]:hover:after { visibility: visible; opacity: 1;}
</style>
<!--START 列表頁-->
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<script type="text/javascript" type="text/javascript" src="js/jquery.select.js"></script>

	<h3>
		<span><a href="/index.php?m=sdkChannel&a=listChannel">返回列表</a></span>
		<?php if ($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
			新增渠道
		<?php }else{ ?>
			编辑渠道
		<?php }?>
	</h3>
	<form action="/index.php?m=sdkChannel&a=saveChannel" method="post" class="searchbox" enctype="multipart/form-data">
		<?php if ($_smarty_tpl->tpl_vars['operation']->value=='edit'){?>
		<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['id'];?>
" />
        <input type="hidden" name="is_new" value="0" />
        <input type="hidden" name="change" value="<?php echo $_smarty_tpl->tpl_vars['change']->value;?>
" />
        <?php }else{ ?>
        <input type="hidden" name="is_new" value="1" />
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['gid']->value==1||($_smarty_tpl->tpl_vars['operation']->value=='add'&&$_smarty_tpl->tpl_vars['gid']->value==15)){?>
		<p>
			<span>上级游戏名：</span>
			<select name="upperName" id="upperName">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['UpperList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
" <?php if ($_smarty_tpl->tpl_vars['channel']->value['upperName']==$_smarty_tpl->tpl_vars['name']->value['upperName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
        </p>
        <p>
			<span>专服游戏名：</span>
            <select name="specialName" id="specialName">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['specialList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
" <?php if ($_smarty_tpl->tpl_vars['channel']->value['specialName']==$_smarty_tpl->tpl_vars['name']->value['specialName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
</option>
                <?php } ?>
            </select>
        </p>
        <p>
			<span>游戏：</span>
            <select name="game" id="game">
                <option value="0">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['game']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['alias'];?>
" <?php if ($_smarty_tpl->tpl_vars['data']->value['alias']===$_smarty_tpl->tpl_vars['channel']->value['gameAlias']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
</option>
				<?php } ?>
			</select>
		</p>
		<p>
			<span>备注：</span>
			<input type="text" name="remarks"  placeholder="字数控制在6个以内" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['remarks'];?>
"/> 
		</p>
		<p>
			<span>渠道：</span>
			<select name="channel" id="channelList">
                <option value="0">请选择</option>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['channels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['channel']->value['channelId'])){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>
		</p>
		<p>
			<span>包号：</span>
			<!-- <select name="apkNum">
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['data']->value==$_smarty_tpl->tpl_vars['channel']->value['apkNum'])){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select> -->
			<input id="inputData" type="text" name="apkNum" value=""/>
		</p>
		<p>
			<span>渠道包名：</span>
			<input type="text" name="pkgName" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['pkgName'];?>
"/>
		</p>
		<p>
			<span>渠道appId：</span>
			<input type="text" name="appid" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['appId'];?>
" /> 
			<a style="color: red;">&nbsp;&nbsp;注意应用宝appId填写用分隔符分隔，示例：QQ的appId|微信的appId</a>
		</p>
		<p>
			<span>渠道appKey：</span>
			<input type="text" name="appkey" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['appKey'];?>
"/>
			<a style="color: red;">&nbsp;&nbsp;注意应用宝appKey填写用分隔符分隔，示例：QQ的appKey|微信的appKey</a>
		</p>
		<p>
			<span>拓展参数1：</span>
			<input type="text" name="ext1" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['ext1'];?>
"/> 
		</p>
		<p>
			<span>拓展参数2：</span>
			<input type="text" name="ext2" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['ext2'];?>
"/> 
			<a style="color: red;">&nbsp;&nbsp;注意应用宝填写格式为appid|广告主id|sign_key;新版广点通参数配置为：应用id|应用secret|账户id|user_action_set_id|new</a>
		</p>
		<p>
			<span>广告商：</span>
			<select name="adsChannel" style="width: 156px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adsChannels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['channel']->value['adsChannel'])){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</option>
				<?php } ?>
			</select>
			</select>
            <a style="color: red;">&nbsp;&nbsp;此处不选同时“拓展参数2”有填写参数默认为广点通</a>
		</p>
		<p>
			<span>广告商参数：</span>
			<input type="text" name="adsParam" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['adsParam'];?>
" placeholder="clientId|clientSecret|srcId"/> 
			<a style="color: red;">&nbsp;&nbsp;注意填写格式为clientId|clientSecret|srcId, 表示应用ID|应用密钥|数据源</a>
		</p>
		<p>
			<span>额外属性：</span>
			<label><input type="checkbox" value="1" name="qyLg" id="qyLg" <?php if ($_smarty_tpl->tpl_vars['channel']->value['qyLg']){?>checked="checked"<?php }?> />登入</label>
			<label>
				<input type="checkbox" value="1" name="qyPy" id="qyPy" <?php if ($_smarty_tpl->tpl_vars['channel']->value['qyPy']){?>checked="checked"<?php }?> />支付
				<input type="text" name="qypy_amount" id="qypy_amount" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['qypyAmount'];?>
" placeholder="金额" style="width:82px; display:none;"/>
			</label>
		</p>
		<p>
			<span>排序：</span>
			<input type="text" name="sort" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['sort'];?>
"/>
		</p>
		<p>
			<span>versionCode：</span>
			<input type="text" name="versionCode" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['versionCode'];?>
"/>
			<a style="color: red;">&nbsp;&nbsp;若需强更包体，此处填写目标包体的versionCode</a>
		</p>
		<p>
			<span>更新链接：</span>
			<input type="text" name="updateUri" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['updateUri'];?>
"/>
			<a style="color: red;">&nbsp;&nbsp;若需强更包体，此处填写目标包体完整的下载链接，需要带http://</a>
		</p>
		<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>
		<p>
			<span>渠道分成：</span>
			<input type="text" name="exProportion" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['exProportion'];?>
"  <?php if ($_smarty_tpl->tpl_vars['change']->value==1){?>disabled="disabled"<?php }?>  id="exProportion"/>
			<font color="#FF0000">&nbsp;*&nbsp;乾游渠道（金克丝包除外，因情况复杂需技术配合填写）、心玩、鲸天互娱、IOS渠道填写折扣</font>
			<span data-tooltip="若渠道分成所得为20%，即填0.2
			不填即使用后台对应的默认渠道比例
			心玩填写折扣
			大咖玩渠道分成应为1-cp分成，即cp分成为0.2，渠道分成则为0.8" style="text-align: center;"><img src="img/icons/qmark.png"></span>
		</p>
		<p>
			<span>渠道通道费：</span>
			<input type="text" name="channelAllowance" value="<?php echo $_smarty_tpl->tpl_vars['channel']->value['channelAllowance'];?>
" id="channelAllowance"/>
			<font color="#FF0000">&nbsp;*&nbsp;填写方式为‘通道费,GS渠道分成’；例如 0.05,0.4</font>
			<span data-tooltip="若通道费为5%，即填0.05，5+7渠道有默认通道费，如不需要通道费填-1
			若心玩、IOS、鲸天互娱、乾游GS渠道分成非默认值，填写方式为‘通道费,GS渠道分成’
			示：GS渠道分成为40% --> 0.05,0.4" style="text-align: center;"><img src="img/icons/qmark.png"></span>
		</p>
		<p>
			<span>结算方式：</span>
			<label><input type="radio" name="settlement" value="0" <?php if ($_smarty_tpl->tpl_vars['channel']->value['settlement']=='0'){?> checked="checked"<?php }?>  />原价结算</label>
			<label><input type="radio" name="settlement" value="2" <?php if ($_smarty_tpl->tpl_vars['channel']->value['settlement']=='2'){?> checked="checked"<?php }?>  />折后结算</label>
			<font color="#FF0000">&nbsp;*&nbsp;仅限乾游渠道使用</font>
		</p>
		<?php }?>
		<p>
			<span>限制属性：</span>
			<label><input type="checkbox" value="1" name="banRegs" id="banRegs" <?php if ($_smarty_tpl->tpl_vars['channel']->value['banRegs']){?>checked="checked"<?php }?> />注册</label>
			<label><input type="checkbox" value="1" name="banLogin" id="banLogin" <?php if ($_smarty_tpl->tpl_vars['channel']->value['banLogin']){?>checked="checked"<?php }?> />登入</label>
			<label><input type="checkbox" value="1" name="banPay" id="banPay" <?php if ($_smarty_tpl->tpl_vars['channel']->value['banPay']){?>checked="checked"<?php }?> />支付</label>
		</p>
		<p>
			<span>限制地区：</span>
			<select id="province" runat="server" onchange="selectprovince(this);" style=" width:95px;"></select>
			<select id="city" runat="server" style=" width:95px;"></select>
			<a id='add'>添加</a>
		</p>
		<p>
			<span></span>
			<input type="text" name="banArea" id="banArea" value="<?php echo $_smarty_tpl->tpl_vars['banArea']->value;?>
"/><font style="color: red;">&nbsp;&nbsp;若只选省份，则该省份所有城市将被限制</font>
		</p>
		<p>
			<span>角色升级记录：</span>
			<input type="checkbox" name="upGradeMark" id="upGradeMark"  value="1" <?php if ($_smarty_tpl->tpl_vars['channel']->value['upGradeMark']==1){?> checked="checked"<?php }?> /><font style="color: red;">&nbsp;&nbsp;勾选即记录角色等级</font>
		</p>
		<p>
			<span>是否测试：</span>
			<input type="checkbox" name="isTest" id="isTest"  value="1" <?php if ($_smarty_tpl->tpl_vars['channel']->value['isTest']==1){?> checked="checked"<?php }?> /><font style="color: red;">&nbsp;&nbsp;勾选即允许渠道测试账号充值到账，订单记录为渠道虚拟支付，反之不到账且订单归为QA订单；目前支持测试渠道为Quick、九尊、风腾；</font>
		</p>
		<p>
			<span>游戏公钥：</span>
			<textarea style="margin: 8px 0 -10px 0; width: 400px; height: 150px;" name="gamePubKey"><?php echo $_smarty_tpl->tpl_vars['gamePubKey']->value;?>
</textarea>
		</p>
		<p>
			<span>支付公钥：</span>
			<textarea style="margin: 8px 0 -10px 0; width: 400px; height: 150px;" name="payPubKey"><?php echo $_smarty_tpl->tpl_vars['payPubKey']->value;?>
</textarea>
		</p>
		<p id="channelText">
			<span>游戏私钥：</span>
			<textarea style="margin-top: 8px; width: 400px; height: 150px;"  name="gamePrivateKey"><?php echo $_smarty_tpl->tpl_vars['gamePrivateKey']->value;?>
</textarea>
		</p>
		<?php }?>
		<p>
			<span>清除测试数据：</span>
			<input type="text" name="clearStart" value="<?php echo $_smarty_tpl->tpl_vars['clearStart']->value;?>
" onclick="WdatePicker({ maxDate: '9999-12-30', readOnly: true });"  />
			<input type="text" name="clearEnd" value="<?php echo $_smarty_tpl->tpl_vars['clearEnd']->value;?>
" onclick="WdatePicker({ maxDate: '9999-12-30', readOnly: true });"  />
			<font color="#FF0000">&nbsp;*清除该时间段内的付费相关数据（含所填写的时间），翌日凌晨执行此选项</font>
		</p>
		<p>
			<span>加载页：</span>
			<input type="text" name="loadInfoDate" value="<?php echo $_smarty_tpl->tpl_vars['loadInfoDate']->value;?>
" onclick="WdatePicker({ maxDate: '9999-12-30', readOnly: true });"  />
			<select name="loadInfoType">
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['loadInfoType']->value==1){?>selected="selected"<?php }?>>有资质信息</option>
				<option value="2" <?php if ($_smarty_tpl->tpl_vars['loadInfoType']->value==2){?>selected="selected"<?php }?>>无资质信息</option>
			</select>
			<font color="#FF0000">&nbsp;*当前时间超过选中时间即显示选中项，反之显示相反项，不填时间即一直显示选中项</font>
		</p>
		<p>
			<span>双端互通：</span>
			<?php if (!$_smarty_tpl->tpl_vars['channel']->value){?>
			<select name="interflow">
                <option value="0">不互通</option>
				<option value="1" <?php if ($_smarty_tpl->tpl_vars['channel']->value['interflow']==1){?>disabled="disabled"<?php }?>>互通</option>
			</select>
			<?php }else{ ?>
			<input type="text" name="alias" value="<?php if ($_smarty_tpl->tpl_vars['channel']->value['interflow']==0){?>不互通<?php }elseif($_smarty_tpl->tpl_vars['channel']->value['interflow']==1){?>互通<?php }?>" disabled="disabled"/>
			<?php }?>
			<font color="#FF0000">&nbsp;*该选项确定后不能修改，一旦开启互通后转端、关联账号等有可能失效，请慎重选择</font>
		</p>
		<p>
			<span>包名白名单：</span>
			<textarea style="margin: 8px 0 -10px 0; width: 400px; height: 150px;" name="packageName"><?php echo $_smarty_tpl->tpl_vars['packageName']->value;?>
</textarea><font color="#FF0000">&nbsp;*留空即不对游戏包名限制，非空即只允许对应填写的包名的游戏登录,多个包名用|分开</font>
		</p>
		<p>
			<span>协议开关：</span>
			<input type="checkbox" name="initTips" id="initTips"  value="2" <?php if ($_smarty_tpl->tpl_vars['initTips']->value==2){?> checked="checked"<?php }?> /><font style="color: red;">&nbsp;&nbsp;勾选即记开启</font>
		</p>
		<p>
			<span>隐私政策id：</span>
			<select name="privacy" style="width: 156px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['privacyArchives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
" <?php if (($_smarty_tpl->tpl_vars['data']->value['id']==$_smarty_tpl->tpl_vars['privacy']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</option>
				<?php } ?>
			</select>
		</p>
		<p>
			<span>用户协议id：</span>
			<select name="agreement" style="width: 156px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['agreementArchives']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
" <?php if (($_smarty_tpl->tpl_vars['data']->value['id']==$_smarty_tpl->tpl_vars['agreement']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</option>
				<?php } ?>
			</select>
		</p>
		<p class="line">
			<button type="submit" name="do" class="su" id="su">立即提交</button>
			<button type="reset" class="re">條件重置</button>
		</p>
	</form>
<!--END 列表頁-->

<script language="javascript" type="text/javascript" src="/js/info.js"></script>
<script>
$('#su').click(function() {
	var exProportion = $('#exProportion').val();
	var channelAllowance = $('#channelAllowance').val();
	if (exProportion == '' || channelAllowance == '') {
		return confirm('渠道分成或通道费尚未填写，确定要保存？');
	}
	
});
$(function() {
	qypy_amount();
	$('#qyPy').click(function() {
		qypy_amount();
	});
	function qypy_amount() {
		if($('#qyPy').is(':checked')) {
			$('#qypy_amount').show();
		}else {
			$('#qypy_amount').hide();
		}
	}
	$(function(){
		$("#channelList").change(function(){
			var channel = $(this).val();
			if((channel == '500001')){
				$('#channelText').show();
			}else{
				$('#channelText').hide();
			}
		})
	});

    $("#add").click(function(){
        get_servers();
        return false;
    });

    function get_servers() {
        var province = $('#province').val();
		var city = $('#city').val();
		var data = '';
		var banArea = document.getElementById("banArea").value;
		if(city == '全部'){
			 data = province+',';
		}else{
			 data = city+',';
		}
		if(banArea != ''){
			 data = banArea+','+data;
		}
		$("#banArea").attr("value",data.substring(0,data.length-1));
        return false;
    }

	// 包号参数下拉框插件
	var datas =[
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
					{ "id":"<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
","text":"<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
"}, 
				<?php } ?>
				];
	$.selectSuggest('inputData',datas);
	// 编辑渠道时显示原来的包号参数
	<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['committe_apknum']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
	<?php if (($_smarty_tpl->tpl_vars['data']->value==$_smarty_tpl->tpl_vars['channel']->value['apkNum'])){?>
	$('#inputData').val(<?php echo $_smarty_tpl->tpl_vars['data']->value;?>
)
	<?php }?>
	<?php } ?>

});
</script>

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>