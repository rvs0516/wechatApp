<?php /* Smarty version Smarty-3.1.11, created on 2022-03-15 10:16:14
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\sdkGame\game.html" */ ?>
<?php /*%%SmartyHeaderCode:32106178b831dfb634-53520877%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d18c9c2c6f6ffd74d4cb8e4e8b07ceb4c03c263' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\sdkGame\\game.html',
      1 => 1645690996,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1592546901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32106178b831dfb634-53520877',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6178b8326e31a9_13606165',
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
<?php if ($_valid && !is_callable('content_6178b8326e31a9_13606165')) {function content_6178b8326e31a9_13606165($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.truncate.php';
if (!is_callable('smarty_modifier_capitalize')) include 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\mvc\\libs\\plugins\\smarty\\plugins\\modifier.capitalize.php';
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
    	
	<style>
		.list td{ padding:10px 0;}
		.show{ display:block; color:#3d203f; text-decoration:none; width:140px; position:relative; left:0; top:0; z-index:10; float:left; margin-left: 20px;}
		.show p{ display:none;}
		.show:hover{ display:block; text-decoration:none; height:100%; position:relative; z-index:1000 !important;}
		.show:hover p{ display:block; color:#3d203f; background:#cff0fc; position:absolute; left:-10px; top:-10px; white-space:normal; width:700px;; padding:10px; box-shadow:1px 1px 10px #333; cursor:pointer;}
	</style>
<!--START 列表頁-->
<?php if ($_smarty_tpl->tpl_vars['operation']->value=='index'){?>
	<h3>
		<?php if ($_smarty_tpl->tpl_vars['gid']->value==1){?>
		<span><a href="/index.php?m=sdkGame&a=game&operation=add">新增游戏</a></span>
		<?php }?>
		游戏列表
	</h3>
	<form class="searchbox" action="/index.php?m=sdkGame&a=game" method="post">
    <p>
        <span>上架情况：</span>
        <select  name="status" style="width: 150px;">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value=='1'){?>selected="selected"<?php }?>>上架</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value=='2'){?>selected="selected"<?php }?>>下架</option>
        </select>
		<span>输入关键词：</span>
		<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" name="keywords" id="keywords">
	<?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen')){?>
	</p>
		<p>
		<span>支付方式：</span>
		<select  name="payMethod" style="width: 150px;" id="payMethod">
            <option value="">请选择</option>
            <option value="1" <?php if ($_smarty_tpl->tpl_vars['payMethod']->value==1){?>selected="selected"<?php }?>>APP支付</option>
            <option value="2" <?php if ($_smarty_tpl->tpl_vars['payMethod']->value==2){?>selected="selected"<?php }?>>H5支付</option>
        </select>
		<span>微信来源：</span>
		<select  name="weixinType" style="width: 157px;" id="weixinType">
            <option value="">请选择</option>
            <option value="huifubao|feituo" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|feituo'){?>selected="selected"<?php }?>>汇付宝|飞拓</option>
            <option value="huifubao|yule" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|yule'){?>selected="selected"<?php }?>>汇付宝|鱼乐</option>
            <option value="huifubao|yaofei" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|yaofei'){?>selected="selected"<?php }?>>汇付宝|耀非</option>
            <option value="huifubao|zhangyue" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|zhangyue'){?>selected="selected"<?php }?>>汇付宝|掌跃</option>
            <option value="huifubao|yiyao" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|yiyao'){?>selected="selected"<?php }?>>汇付宝|义耀</option>
            <option value="huifubao|fandian" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|fandian'){?>selected="selected"<?php }?>>汇付宝|凡点</option>
            <option value="huifubao|qianyou" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='huifubao|qianyou'){?>selected="selected"<?php }?>>汇付宝|乾游</option>

            <option value="xianzai|qianyou" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='xianzai|qianyou'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xianzai|fandian" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='xianzai|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>
            <option value="xianzai|yiyao" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='xianzai|yiyao'){?>selected="selected"<?php }?>>现在|义耀</option>

            <option value="zhangling|zhangyue" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='zhangling|zhangyue'){?>selected="selected"<?php }?>>掌灵|掌跃</option>
            <option value="zhangling|fandian" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='zhangling|fandian'){?>selected="selected"<?php }?>>掌灵|凡点</option>
            <option value="zhangling|yiyao" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='zhangling|yiyao'){?>selected="selected"<?php }?>>掌灵|义耀</option>
            <option value="zhangling|yaofei" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='zhangling|yaofei'){?>selected="selected"<?php }?>>掌灵|耀非</option>
            <option value="zhangling|feituo" <?php if ($_smarty_tpl->tpl_vars['weixinType']->value=='zhangling|feituo'){?>selected="selected"<?php }?>>掌灵|飞拓</option>
        </select>
        <select  name="wxAppType" style="width: 157px;" id="wxAppType">
            <option value="">请选择</option>
            <option value="zlapp|fandian" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='zlapp|fandian'){?>selected="selected"<?php }?>>掌灵|凡点</option>
            <option value="zlapp|yiyao" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='zlapp|yiyao'){?>selected="selected"<?php }?>>掌灵|义耀</option>
            <!--<option value="hywapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='hywapp|qianyou'){?>selected="selected"<?php }?>>汇雅威|乾游</option>
            <option value="xzapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='xzapp|qianyou'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xzapp|yiyao" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='xzapp|yiyao'){?>selected="selected"<?php }?>>现在|义耀</option>
            <option value="xzapp|fandian" <?php if ($_smarty_tpl->tpl_vars['wxAppType']->value=='xzapp|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>-->
        </select>
		<span>支付宝来源：</span>
		<select  name="alipayType" style="width: 150px;" id="alipayType">
            <option value="">请选择</option>
            <option value="alipay|qianyou" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay|qianyou'){?>selected="selected"<?php }?>>支付宝|乾游</option>
            <option value="alipay|yiyao" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay|yiyao'){?>selected="selected"<?php }?>>支付宝|义耀</option>
            <option value="alipay|fandian" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay|fandian'){?>selected="selected"<?php }?>>支付宝|凡点</option>

            <option value="xianzai|qianyou" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='xianzai|qianyou'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xianzai|fandian" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='xianzai|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>

            <option value="alipay2|qianyou" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay2|qianyou'){?>selected="selected"<?php }?>>支付宝2.0|乾游</option>
            <option value="alipay2|yiyao" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay2|yiyao'){?>selected="selected"<?php }?>>支付宝2.0|义耀</option>
            <option value="alipay2|fandian" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay2|fandian'){?>selected="selected"<?php }?>>支付宝2.0|凡点</option>
            <option value="alipay2|zhangyue" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='alipay2|zhangyue'){?>selected="selected"<?php }?>>支付宝2.0|掌跃</option>

            <option value="zhanglingslh5|zhangyue" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='zhanglingslh5|zhangyue'){?>selected="selected"<?php }?>>掌灵识路h5|掌跃</option>
            <option value="zhanglingslapi|zhangyue" <?php if ($_smarty_tpl->tpl_vars['alipayType']->value=='zhanglingslapi|zhangyue'){?>selected="selected"<?php }?>>掌灵识路api|掌跃</option>

        </select>
        <select  name="aliAppType" style="width: 150px;" id="aliAppType">
            <option value="">请选择</option>
            <option value="aliapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['aliAppType']->value=='aliapp|qianyou'){?>selected="selected"<?php }?>>支付宝|乾游</option>
        </select>
        </p>
	<p style=" margin-top: 10px;">
	<?php }?>
        <button type="submit" class="su"  style="margin-top: 0px;">查询</button>
    </p>
	</form>
	<table class="list">
		<tr style="background-color:#CCCCCC;">
			<th>项目</th>
			<th>游戏</th>
			<th>TOKEN</th>
			<th>SERVER KEY</th>
			<th>游戏介绍</th>
			<th></th>
			<?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen')){?>
			<th>支付方式</th>
			<th>微信来源</th>
			<th>支付宝来源</th>
			<?php }?>
			<th>排序</th>
			<th>上架</th>
			<th>操作</th>
		</tr>
		<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['game_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value){
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
		<tr>
			<td style="text-align: center;"><?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>
</td>
			<td style="text-align: center;"><?php echo $_smarty_tpl->tpl_vars['game']->value['name'];?>
</td>
			<td style="text-align: center;"><font color="green"><?php echo $_smarty_tpl->tpl_vars['game']->value['tokenMd5'];?>
</font></td>
			<td style="text-align: center;"><font color="green"><?php echo $_smarty_tpl->tpl_vars['game']->value['serverKey'];?>
</font></td>
			<td style="text-align: center;"><a class="show"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['game']->value['detail'],15,"..",true);?>
<?php if ($_smarty_tpl->tpl_vars['game']->value['detail']){?><p><?php echo $_smarty_tpl->tpl_vars['game']->value['detail'];?>
</p><?php }?></a></td>
			<td title="<?php echo $_smarty_tpl->tpl_vars['game']->value['info'];?>
">				
				<?php if ($_smarty_tpl->tpl_vars['game']->value['isration']==1){?>定额支 <?php }?>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['visibleFloat']==1){?>悬浮控件 <?php }?>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['logger']==1){?>服务端日志<?php }?>
			</td>
			<?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen'||$_smarty_tpl->tpl_vars['uid']->value=='yangzhenwei')){?>
			<!--<td><?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType1']=='youluo'){?>优络|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType1']=='huiyawei'){?>汇雅威|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType1']=='xianzai'){?>现在|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType1']=='zhangling'){?>掌灵|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType1']=='huifubao'){?>汇付宝|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType1']==null){?>现在|
				<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['game']->value['weixinType1'];?>
<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType2']=='qianyou'){?>乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType2']=='fandian'){?>凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType2']=='yiyao'){?>义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType2']==null){?>凡点
				<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['game']->value['weixinType2'];?>
<?php }?>
			</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType1']=='alipay'){?>支付宝|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType1']=='xianzai'){?>现在|
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType1']==null){?>支付宝|
				<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['game']->value['alipayType1'];?>
<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType2']=='qianyou'){?>乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType2']=='fandian'){?>凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType2']==null){?>乾游
				<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['game']->value['alipayType2'];?>
<?php }?>
			</td>-->
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['payMethod']==1){?><span style="color: red;">APP支付</span><?php }else{ ?><span style="color: green;">H5支付</span><?php }?></td>
			<?php if ($_smarty_tpl->tpl_vars['game']->value['payMethod']==0){?>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huiyawei|qianyou'){?>汇雅威|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|qianyou'){?>现在|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|fandian'){?>现在|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|yiyao'){?>现在|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|fandian'){?>掌灵|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|yiyao'){?>掌灵|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|zhangyue'){?>掌灵|掌跃
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|feituo'){?>掌灵|飞拓
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|yaofei'){?>掌灵|耀非
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yiyao'){?>汇付宝|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|fandian'){?>汇付宝|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|qianyou'){?>汇付宝|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yule'){?>汇付宝|鱼乐
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|feituo'){?>汇付宝|飞拓
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yaofei'){?>汇付宝|耀非
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|zhangyue'){?>汇付宝|掌跃
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['weixinType']==null){?>汇付宝|凡点<?php }?>
			</td>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|qianyou'){?>支付宝|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|yiyao'){?>支付宝|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|fandian'){?>支付宝|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='xianzai|qianyou'){?>现在|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='xianzai|fandian'){?>现在|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|qianyou'){?>支付宝2.0|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|yiyao'){?>支付宝2.0|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|fandian'){?>支付宝2.0|凡点
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|zhangyue'){?>支付宝2.0|掌跃
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='zhanglingslh5|zhangyue'){?>掌灵识路h5|掌跃
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']=='zhanglingslapi|zhangyue'){?>掌灵识路api|掌跃
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['alipayType']==null){?>支付宝|乾游<?php }?>
			</td>
			<?php }elseif($_smarty_tpl->tpl_vars['game']->value['payMethod']==1){?>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|yiyao'){?>掌灵|义耀
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|qianyou'){?>掌灵|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['wxAppType']==null||$_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|fandian'){?>掌灵|凡点<?php }?>
			</td>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['game']->value['aliAppType']=='aliapp|qianyou'){?>支付宝|乾游
				<?php }elseif($_smarty_tpl->tpl_vars['game']->value['aliAppType']==null){?>支付宝|乾游<?php }?>
			</td>
			<?php }?>
			<?php }?>
			<td><?php echo $_smarty_tpl->tpl_vars['game']->value['sort'];?>
</td>
			<td><?php if ($_smarty_tpl->tpl_vars['game']->value['status']==1){?><font color="#00FF00">√</font><?php }else{ ?><font color="#FF0000">×</font><?php }?></td>
			<td style="width:160px">
			<a href="/index.php?m=sdkGame&a=game&operation=view&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" target="_blank">查看</a>
			<?php if ($_smarty_tpl->tpl_vars['gid']->value==1&&$_smarty_tpl->tpl_vars['editor']->value==1){?>
			 | <a href="/index.php?m=sdkGame&a=game&operation=edit&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" target="_blank">编辑</a>
			 | <a href="/index.php?m=sdkGame&a=game&operation=del&id=<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" class="delete_confirm">刪除</a> 
			<?php }?>
			 </td>
		</tr>
		<?php } ?>
	</table>
	<div id="pager"></div>

<script src="js/pager.js"></script>
<script>
select();
$("#payMethod").change(function(){
    select();
    return false;
});
function select(){
	var payMethod = $('#payMethod').val();
	$("#weixinType ").prop("disabled", false);
    $("#alipayType ").prop("disabled", false);
   	if(payMethod == 1){
        $('#wxAppType').show();
        $('#aliAppType').show();
        $('#weixinType').hide();
        $('#alipayType').hide();
    	$("#weixinType  option[value=''] ").attr("selected",true);
    	$("#alipayType  option[value=''] ").attr("selected",true);
    } else if (payMethod == 2) {
        $('#wxAppType').hide();
        $('#aliAppType').hide();
        $('#weixinType').show();
        $('#alipayType').show();
    	$("#wxAppType  option[value=''] ").attr("selected",true);
    	$("#aliAppType  option[value=''] ").attr("selected",true); 
	} else {
        $('#wxAppType').hide();
        $('#aliAppType').hide();
        $('#weixinType').show();
        $('#alipayType').show();
        $("#weixinType ").prop("disabled", true) 
        $("#alipayType ").prop("disabled", true) 
	}
}
$('.delete_confirm').click(function() {
	return confirm('數據不可恢復，你確定要刪除嗎？');
});

function gotoNext(page,pagesize){
	window.location.href = "/index.php?m=sdkGame&a=game&page=" + page+ "&status=<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
&keywords=<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
&weixinType=<?php echo $_smarty_tpl->tpl_vars['weixinType']->value;?>
&alipayType=<?php echo $_smarty_tpl->tpl_vars['alipayType']->value;?>
&payMethod=<?php echo $_smarty_tpl->tpl_vars['payMethod']->value;?>
&wxAppType=<?php echo $_smarty_tpl->tpl_vars['wxAppType']->value;?>
&aliAppType=<?php echo $_smarty_tpl->tpl_vars['aliAppType']->value;?>
";
}
var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
document.getElementById('pager').innerHTML = pageStr;
</script>


<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='add'||$_smarty_tpl->tpl_vars['operation']->value=='edit'){?>
<script language="javascript" type="text/javascript" src="/js/DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="/js/DatePicker/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>

	<h3>
		<span><a href="/index.php?m=sdkGame&a=game">返回列表</a></span>
		<?php if ($_smarty_tpl->tpl_vars['operation']->value=='add'){?>
			新增游戏
		<?php }else{ ?>
			编辑游戏
		<?php }?>
	</h3>
	<form action="/index.php?m=sdkGame&a=game&operation=save" method="post" class="searchbox" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['id'];?>
" />
		<p>
			<span>是否显示：</span>
			<label><input type="checkbox" name="status" value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['status']==1){?> checked="checked"<?php }?> /></label>
		</p>
		<p>
			<span>上级游戏名：</span>
			<input type="text" name="upperName" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>
" /><font color="#FF0000">&nbsp;*&nbsp;</font>规则：游戏名前面为拼音首字母大写，后面文字汉语，如魔石英雄为"M-魔石英雄"
		</p>
		<p>
			<span>专服游戏名：</span>
			<input type="text" name="specialName" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['specialName'];?>
" /><font color="#FF0000">&nbsp;*&nbsp;</font>规则：游戏名前面为拼音首字母大写，后面文字汉语，如魔石英雄为"M-魔石英雄"
		</p>
		<p>
			<span>游戏名：</span>
			<input type="text" name="name" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['name'];?>
" /><font color="#FF0000">&nbsp;*</font>
		</p>
		<p>
			<span>游戏別名：</span>
			<input type="text" name="alias" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['alias'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['game']->value)){?>disabled="disabled"<?php }?> /><font color="#FF0000">&nbsp;*</font>&nbsp;规则：游戏名前面两字全拼，后面其他文字汉语拼音的首字母，如魔石英雄为moshiyx
		</p>
		<p>
			<span>描述：</span>
			<textarea style="width:400px; height:128px;" name="detail"><?php echo $_smarty_tpl->tpl_vars['game']->value['detail'];?>
</textarea>
		</p>
		<p>
			<span>游戏包名：</span>
			<input type="text" name="packageName" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['packageName'];?>
" /><font color="#FF0000">&nbsp;*&nbsp;</font>必须填写游戏包名，否则有可能影响正常充值
		</p>
		<p>
			<span>CP名称：</span>
			<input type="text" name="cpName" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['cpName'];?>
" /><font color="#FF0000">&nbsp;*&nbsp;</font>
		</p>
        <p>
			<span>兑换倍数：</span>
			<input type="text" name="scale" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['scale'];?>
" /><font color="#FF0000">&nbsp;*</font>
		</p>
        <p>
			<span>货币单位：</span>
			<input type="text" name="monetaryUnit" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['monetaryUnit'];?>
" /><font color="#FF0000">&nbsp;*</font>
		</p>
        <p>
			<span>TOKEN：</span>
			<input type="text" name="token" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['token'];?>
" /><font color="#FF0000">&nbsp;*</font>
		</p>
        <p>
			<span>SERVER KEY：</span>
			<input type="text" name="serverKey" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['serverKey'];?>
" /><font color="#FF0000">&nbsp;*</font>
		</p>
		<p>
			<span>充值回调：</span>
			<input type="url" name="callbackUrl" id="callbackUrl" style="width:250px;" placeholder="http://" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['callbackUrl'];?>
" />
		</p>
		<p>
			<span>定额支付：</span>
			<input type="checkbox" name="isration" id="isration"  value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['isration']==1){?> checked="checked"<?php }?> />
		</p>
		<p>
			<span>悬浮控件：</span>
			<input type="checkbox" name="visibleFloat" id="visibleFloat"  value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['visibleFloat']==1){?> checked="checked"<?php }?> />
		</p>
		<p>
			<span>服务端日志：</span>
			<input type="checkbox" name="logger" id="logger"  value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['logger']==1){?> checked="checked"<?php }?> />
		</p>
		<?php if (($_smarty_tpl->tpl_vars['uid']->value=='luojiang'||$_smarty_tpl->tpl_vars['uid']->value=='chenjh'||$_smarty_tpl->tpl_vars['uid']->value=='heyongzhen')){?>
		<p>
			<span>APP支付：</span>
			<input type="checkbox" name="payMethod" id="payMethod"  value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['payMethod']==1){?> checked="checked"<?php }?> />
		</p>
		<p id="weixin">
		<span>微信H5：</span>
		<select  name="weixinType" style="width: 150px;">
            <option value="zhangling|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|fandian'){?>selected="selected"<?php }?>>掌灵|凡点</option>
            <option value="huifubao|feituo" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|feituo'){?>selected="selected"<?php }?>>汇付宝|飞拓</option>
			<option value="huifubao|yule" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yule'){?>selected="selected"<?php }?>>汇付宝|鱼乐</option>
			<option value="huifubao|yaofei" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yaofei'){?>selected="selected"<?php }?>>汇付宝|耀非</option>
            <option value="huifubao|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|fandian'){?>selected="selected"<?php }?>>汇付宝|凡点</option>
            <option value="huifubao|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|yiyao'){?>selected="selected"<?php }?>>汇付宝|义耀</option>
            <option value="huifubao|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|qianyou'){?>selected="selected"<?php }?>>汇付宝|乾游</option>
            <option value="huifubao|zhangyue" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huifubao|zhangyue'){?>selected="selected"<?php }?>>汇付宝|掌跃</option>
            <option value="xianzai|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>
            <option value="xianzai|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|qianyou'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xianzai|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='xianzai|yiyao'){?>selected="selected"<?php }?>>现在|义耀</option>
            <option value="zhangling|zhangyue" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|zhangyue'){?>selected="selected"<?php }?>>掌灵|掌跃</option>
            <option value="zhangling|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|yiyao'){?>selected="selected"<?php }?>>掌灵|义耀</option>
            <option value="zhangling|yaofei" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|yaofei'){?>selected="selected"<?php }?>>掌灵|耀非</option>
            <option value="zhangling|feituo" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|feituo'){?>selected="selected"<?php }?>>掌灵|飞拓</option>
            <!--<option value="huiyawei|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='huiyawei|qianyou'){?>selected="selected"<?php }?>>汇雅威|乾游</option>
            <option value="zhangling|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['weixinType']=='zhangling|yiyao'){?>selected="selected"<?php }?>>掌灵|义耀</option>-->
        </select>
        <font color="#FF0000">&nbsp;*&nbsp;默认 汇付宝|飞拓</font>
        </p>
        <p id="alipay">
		<span>支付宝H5：</span>
		<select  name="alipayType" style="width: 150px;">
            <option value="alipay|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|fandian'){?>selected="selected"<?php }?>>支付宝|凡点</option>
            <option value="alipay|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|qianyou'){?>selected="selected"<?php }?>>支付宝|乾游</option>
            <option value="alipay|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay|yiyao'){?>selected="selected"<?php }?>>支付宝|义耀</option>
            <option value="xianzai|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='xianzai|qianyou'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xianzai|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='xianzai|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>
            <!--<option value="xianzai|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='xianzai|yiyao'){?>selected="selected"<?php }?>>现在|义耀</option>-->
            <option value="alipay2|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|qianyou'){?>selected="selected"<?php }?>>支付宝2.0|乾游</option>
            <option value="alipay2|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|yiyao'){?>selected="selected"<?php }?>>支付宝2.0|义耀</option>
            <option value="alipay2|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|fandian'){?>selected="selected"<?php }?>>支付宝2.0|凡点</option>
            <option value="alipay2|zhangyue" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='alipay2|zhangyue'){?>selected="selected"<?php }?>>支付宝2.0|掌跃</option>
            <option value="zhanglingslh5|zhangyue" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='zhanglingslh5|zhangyue'){?>selected="selected"<?php }?>>掌灵识路h5|掌跃</option>
            <option value="zhanglingslapi|zhangyue" <?php if ($_smarty_tpl->tpl_vars['game']->value['alipayType']=='zhanglingslapi|zhangyue'){?>selected="selected"<?php }?>>掌灵识路api|掌跃</option>
        </select>
        <font color="#FF0000">&nbsp;*&nbsp;默认 支付宝|凡点</font>
        </p>
		<p  id="wxApp">
		<span style="color: green;">微信APP：</span>
		<select  name="wxAppType" style="width: 150px;">
            <option value="zlapp|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|fandian'){?>selected="selected"<?php }?>>掌灵|凡点</option>
            <option value="zlapp|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|yiyao'){?>selected="selected"<?php }?>>掌灵|义耀</option>
            <option value="zlapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='zlapp|qianyou'){?>selected="selected"<?php }?>>掌灵|乾游</option>
            <!--<option value="xzapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='xzapp|yiyao'){?>selected="selected"<?php }?>>现在|乾游</option>
            <option value="xzapp|yiyao" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='xzapp|yiyao'){?>selected="selected"<?php }?>>现在|义耀</option>
            <option value="xzapp|fandian" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='xzapp|fandian'){?>selected="selected"<?php }?>>现在|凡点</option>
            <option value="hywapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['wxAppType']=='hywapp|qianyou'){?>selected="selected"<?php }?>>汇雅威|乾游</option>-->
        </select>
        <font color="#FF0000">&nbsp;*&nbsp;默认 掌灵|凡点</font>
        </p>
        <p  id="aliApp">
		<span style="color: green;">支付宝APP：</span>
		<select  name="aliAppType" style="width: 150px;">
            <option value="aliapp|qianyou" <?php if ($_smarty_tpl->tpl_vars['game']->value['aliAppType']=='aliapp|qianyou'){?>selected="selected"<?php }?>>支付宝|乾游</option>
        </select>
        <font color="#FF0000">&nbsp;*&nbsp;默认 支付宝|乾游</font>
        </p>
		<?php }?>
		<p>
			<span>数据同步：</span>
			<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['syn']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
			<label><input type="checkbox" name="channelSyn[]" value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['key1']->value,$_smarty_tpl->tpl_vars['channelSyn']->value)){?>checked="checked"<?php }?> id="open_channels" /><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</label>
			<?php } ?>
		</p>
		<p>
			<span>角色升级记录：</span>
			<input type="checkbox" name="upGradeMark" id="upGradeMark"  value="1" <?php if ($_smarty_tpl->tpl_vars['game']->value['upGradeMark']==1){?> checked="checked"<?php }?> />
		</p>
		<?php if ($_smarty_tpl->tpl_vars['checkRoot']->value==1){?>
		<p>
			<span>CP分成：</span>
			<input type="text" name="proportion" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['proportion'];?>
" id="proportion" />
			<font color="#FF0000">&nbsp;*&nbsp;若cp分成所得为20%，即填0.2</font>
		</p>
		<p>
			<span>CP通道费：</span>
			<input type="text" name="cpAllowance" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['cpAllowance'];?>
"  id="cpAllowance" />
			<font color="#FF0000">&nbsp;*&nbsp;若通道费为5%，即填0.05</font>
		</p>
		<?php }?>
		<p>
			<span>H5游戏链接：</span>
			<input type="url" name="h5Url" id="h5Url" style="width:250px;" placeholder="http://" value="<?php echo $_smarty_tpl->tpl_vars['game']->value['h5Url'];?>
" />
		</p>
		<p>
		<span>关联游戏：</span>
            <select name="relateUpper" id="upperName" style="width: 90px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['UpperList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value['relateUpper']==$_smarty_tpl->tpl_vars['name']->value['upperName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['upperName'];?>
</option>
                <?php } ?>
            </select>
            <select name="relateSpecial" id="specialName" style="width: 90px;">
                <option value="">请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['specialList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value['relateSpecial']==$_smarty_tpl->tpl_vars['name']->value['specialName']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['specialName'];?>
</option>
                <?php } ?>
            </select>
            <select name="relateGame" id="game" style="width: 90px;">
                <option value="">请选择</option>
        	<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['key1']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['game']->value['relateGame']==$_smarty_tpl->tpl_vars['key1']->value){?>selected="selected"<?php }?>> <?php echo smarty_modifier_truncate(smarty_modifier_capitalize($_smarty_tpl->tpl_vars['key1']->value),1,'',true);?>
 — <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
        	<?php } ?>
        </select>
		</p>
		<?php if (!empty($_smarty_tpl->tpl_vars['game']->value['basePic'])){?>
		<p style="margin-top: 30px;">
			<span>游戏底图：</span>
			<img src="<?php echo $_smarty_tpl->tpl_vars['static']->value;?>
<?php echo $_smarty_tpl->tpl_vars['game']->value['basePic'];?>
" style="width: 300px; height: 200px;" />
		</p>
		<?php }?>
		<p>
			<span><?php if (!empty($_smarty_tpl->tpl_vars['game']->value['basePic'])){?>更换<?php }?>游戏底图：</span>
			<input type="file" name="basePic[]" /><font color="red">图标标准尺寸：<font style=" font-weight: bold;">640*1280px</font></font>
		</p>
		<p class="line">
			<button type="submit" name="do" class="su" id="su">立即提交</button>
			<button type="reset" class="re">条件重置</button>
		</p>
	</form>
<script language="javascript">
$('#su').click(function() {
	var proportion = $('#proportion').val();
	var cpAllowance = $('#cpAllowance').val();
	if (proportion == '' || cpAllowance == '') {
		return confirm('cp分成或通道费尚未填写，确定要保存？');
	}
	
});
select();
$("#payMethod").change(function(){
    select();
    return false;
});
function select(){
   if(document.getElementById("payMethod").checked==false){
        $('#weixin').show();  
        $('#alipay').show();  
        $('#wxApp').hide();    
        $('#aliApp').hide();  
    } else {
        $('#weixin').hide();  
        $('#alipay').hide();  
        $('#wxApp').show();  
        $('#aliApp').show();  
	}
}
</script>
<?php }elseif($_smarty_tpl->tpl_vars['operation']->value=='view'){?>
	<h3>
		<span><a href="/index.php?m=sdkGame&a=game">返回列表</a></span>
	</h3>
	<form  class="searchbox">
		<p>
			<span>上级游戏名：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['upperName'];?>

		</p>
		<p>
			<span>专服游戏名：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['specialName'];?>

		</p>
		<p>
			<span>游戏名：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['name'];?>

		</p>
		<p>
			<span>游戏別名：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['alias'];?>

		</p>
		<p>
			<span>描述：</span>
			<textarea style="width:400px; height:128px;" name="detail"disabled="disabled" ><?php echo $_smarty_tpl->tpl_vars['game']->value['detail'];?>
</textarea>
		</p>
		<p>
			<span>游戏包名：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['packageName'];?>

		</p>
        <p>
			<span>兑换倍数：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['scale'];?>

		</p>
        <p>
			<span>货币单位：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['monetaryUnit'];?>

		</p>
        <p>
			<span>TOKEN：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['token'];?>

		</p>
        <p>
			<span>SERVER KEY：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['serverKey'];?>

		</p>
		<p>
			<span>充值回调：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['callbackUrl'];?>

		</p>
		<p>
			<span>CP分成：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['proportion'];?>

		</p>
		<p>
			<span>CP通道费：</span><?php echo $_smarty_tpl->tpl_vars['game']->value['cpAllowance'];?>

		</p>
		<p>
			<span>H5游戏链接:</span><?php echo $_smarty_tpl->tpl_vars['game']->value['h5Url'];?>

		</p>
		<p>
			<span>关联游戏:</span><?php if ($_smarty_tpl->tpl_vars['game']->value['relateUpper']){?><?php echo $_smarty_tpl->tpl_vars['game']->value['relateUpper'];?>
 / <?php echo $_smarty_tpl->tpl_vars['game']->value['relateSpecial'];?>
 / <?php echo $_smarty_tpl->tpl_vars['game']->value['relateName'];?>
<?php }?>
		</p>
	</form>
<?php }?>
<!--END 列表頁-->

    </div>
</div>

<!--腳部版權-->
<div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div>
</body>
</html><?php }} ?>