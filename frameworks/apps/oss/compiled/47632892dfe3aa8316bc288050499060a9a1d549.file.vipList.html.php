<?php /* Smarty version Smarty-3.1.11, created on 2022-10-21 15:19:26
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\vipGuest\vipList.html" */ ?>
<?php /*%%SmartyHeaderCode:8891635247fecef4b4-57955963%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47632892dfe3aa8316bc288050499060a9a1d549' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\vipGuest\\vipList.html',
      1 => 1666167703,
      2 => 'file',
    ),
    '36a85c56f5ac9e535f805f91f1e86b65655eae28' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\layout\\new.html',
      1 => 1662104832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8891635247fecef4b4-57955963',
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
  'unifunc' => 'content_635247ff07bd53_72129496',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_635247ff07bd53_72129496')) {function content_635247ff07bd53_72129496($_smarty_tpl) {?><!DOCTYPE html>
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
<script language="javascript" type="text/javascript" src="/js/linkage.js"></script>
<h3>
    VIP列表
</h3>
<body id="app">
    <div class="image_large" onclick="hideImg();" hidden >
    </div>
    <form class="searchbox" action="/index.php?m=vipGuest&a=vipList" method="post">
        <p>
            <input type="hidden" id="gid" value="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" />
            <input type="hidden" id="gameStr" value="<?php echo $_smarty_tpl->tpl_vars['gameStr']->value;?>
" />
            <input type="hidden" id="refine" value="<?php echo $_smarty_tpl->tpl_vars['refine']->value;?>
" />
            <input type="hidden" name="operation" value="" id="operation" />
            <span style="width: 125px;">最后登录的游戏：</span>
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
            <span >排序：</span>
            <select name="sort" id="sort"  style="width: 170px;">
                <option value="">请选择</option>
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['sort']->value==1){?>selected="selected"<?php }?>>充值金额</option>
                <option value="2" <?php if ($_smarty_tpl->tpl_vars['sort']->value==2){?>selected="selected"<?php }?>>最后登录时间</option>
                <option value="3" <?php if ($_smarty_tpl->tpl_vars['sort']->value==3){?>selected="selected"<?php }?>>注册时间</option>
            </select>
            <span >审核状态</span>
            <select name="examine" id="examine"  style="width: 170px;">
                <option value="" <?php if ($_smarty_tpl->tpl_vars['examine']->value==''){?>selected="selected"<?php }?>>全部</option>
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['examine']->value==1){?>selected="selected"<?php }?>>待审核</option>
                <option value="2" <?php if ($_smarty_tpl->tpl_vars['examine']->value==2){?>selected="selected"<?php }?>>审核通过</option>
                <option value="-1" <?php if ($_smarty_tpl->tpl_vars['examine']->value==-1){?>selected="selected"<?php }?>>审核未通过</option>
                <option value="3" <?php if ($_smarty_tpl->tpl_vars['examine']->value==3){?>selected="selected"<?php }?>>待认证</option>
            </select>
            <span >vip来源</span>
            <select name="source" id="source"  style="width: 170px;">
                <option value="" <?php if ($_smarty_tpl->tpl_vars['source']->value==''){?>selected="selected"<?php }?>>请选择</option>
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['source']->value==1){?>selected="selected"<?php }?>>玩家列表</option>
                <option value="2" <?php if ($_smarty_tpl->tpl_vars['source']->value==2){?>selected="selected"<?php }?>>付费</option>
            </select>
            <span >专员账号</span>
            <select name="majorperson" id="majorperson"  style="width: 170px;">
                <option value="" <?php if ($_smarty_tpl->tpl_vars['majorperson']->value==''){?>selected="selected"<?php }?>>请选择</option>
                <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['key1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['majorpersonList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['key1']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value['uid'];?>
" <?php if ($_smarty_tpl->tpl_vars['majorperson']->value==$_smarty_tpl->tpl_vars['name']->value['uid']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value['uid'];?>
</option>
                <?php } ?>
            </select>
        </p>
        <p>
            <span style="width: 125px;">最后登录时间：</span>
            <input type="text" name="start_date" id="start_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['start_date']->value;?>
" onclick="var end_date=$dp.$('end_date');WdatePicker({ onpicked:function(){ end_date.focus();},minDate:'#F{ ($dp.$D(\'end_date\',{ y:-1,d:0}))}',maxDate:'#F{ $dp.$D(\'end_date\')}'})"> 至 <input type="text" name="end_date" id="end_date" class="date" value="<?php echo $_smarty_tpl->tpl_vars['end_date']->value;?>
" onclick="WdatePicker({ minDate:'#F{ ($dp.$D(\'start_date\',{ d:0,H:0}))}',maxDate:'#F{ ($dp.$D(\'start_date\',{ y:1,d:0}))}'} )">
            <span>账号：</span>
            <input style="width: 186px;" type="text" value="<?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
" name="userName" id="userName" placeholder="请输入需要搜索的账号" />
        </p>
        <p style="width: 950px;">
            &nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="su inline" id="sub">查询</button>
            <button type="submit" class="su" value="report" id="report">导出</button>
        </p>
        <!--<p style="margin-left: 10px; color: red;">*&nbsp;&nbsp;该页面显示的用户数为角色数，选择游戏后可查询所有时间，否则只显示最近45天数据</p>-->
    </form>
    <table class="list">
        <tr style="background-color:#CCCCCC;">
            <th>账号</th>
            <th>渠道</th>
            <th style="width: 50px;">累充</th>
            <th style="width: 50px;">首充</th>
            <th style="width: 80px;">最后登录游戏</th>
            <th style="width: 100px;">注册时间</th>
            <th>停充天数</th>
            <th style="width: 100px;">最后付费时间</th>
            <th>停登天数</th>
            <th style="width: 100px;">最后登录时间</th>
            <th>是否曾关联</th>
            <th style="line-height: 20px;width: 100px;">
                关联账号最后登录时间
                <span class="question—relationLoginTime">
                    <em class="question-content" style="height: 30px;">当前关联账号是指玩家登录所使用账号</em>
                </span>
            </th>
            <th style="line-height: 20px;width: 100px;">
                最后登录关联账号
                <span class="question—relationUserName">
                    <em class="question-content" style="height: 30px;">当前关联账号是指玩家登录所使用账号</em>
                </span>
            </th>
            <th style="width: 120px;">回访图片</th>
            <th>审核状态</th>
            <th>专员</th>
            <th>联系方式</th>
            <th style="line-height: 20px;width: 100px;">上次回访时间</th>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22||$_smarty_tpl->tpl_vars['gid']->value==1){?>
            <th style="width: 70px;">操作</th>
            <?php }?>
        </tr>
        <?php if ($_smarty_tpl->tpl_vars['vipGuestList']->value){?>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['vipGuestList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        <tr style="height: 100px;">
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['userName'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['channelName'];?>
</td>
            <td style="width: 50px;"><?php if ($_smarty_tpl->tpl_vars['item']->value['sumMoney']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['sumMoney'];?>
<?php }else{ ?>0<?php }?></td>
            <td style="width: 50px;"><?php if ($_smarty_tpl->tpl_vars['item']->value['firstCharge']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['firstCharge'];?>
<?php }else{ ?>0<?php }?></td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['lastGameName'];?>
</td>
            <td style="width: 100px;">
                <?php if ($_smarty_tpl->tpl_vars['item']->value['joinTime']){?>
                <?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['joinTime']);?>

                <?php echo date('H:i',$_smarty_tpl->tpl_vars['item']->value['joinTime']);?>

                <?php }?>
            </td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['end_recharge_day']!=-1){?><?php echo $_smarty_tpl->tpl_vars['item']->value['end_recharge_day'];?>
天<?php }?></td>
            <td style="width: 100px;">
                <?php if ($_smarty_tpl->tpl_vars['item']->value['lastPayTime']){?>
                <?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['lastPayTime']);?>

                <?php echo date('H:i',$_smarty_tpl->tpl_vars['item']->value['lastPayTime']);?>

                <?php }?>
            </td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['end_login_day']!=-1){?><?php echo $_smarty_tpl->tpl_vars['item']->value['end_login_day'];?>
天<?php }?></td>
            <td style="width: 100px;">
                <?php if ($_smarty_tpl->tpl_vars['item']->value['loginTime']){?>
                <?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['loginTime']);?>

                <?php echo date('H:i',$_smarty_tpl->tpl_vars['item']->value['loginTime']);?>

                <?php }?>
            </td>
            <td><?php if ($_smarty_tpl->tpl_vars['item']->value['relationStatus']==1){?>是<?php }else{ ?>否<?php }?></td>
            <td style="width: 100px;">
                <?php if ($_smarty_tpl->tpl_vars['item']->value['relationLoginTime']){?>
                <?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['relationLoginTime']);?>

                <?php echo date('H:i',$_smarty_tpl->tpl_vars['item']->value['relationLoginTime']);?>

                <?php }?>
            </td>
            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['relationUserName'];?>
</td>
            <td class="row img-<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" id="video" style="line-height: 0px;">
                <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['item']->value['returnImg'];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1){?>
                <div class="image">
                    <img src='<?php echo $_smarty_tpl->tpl_vars['route']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['returnImg'];?>
' onclick="imgshow('<?php echo $_smarty_tpl->tpl_vars['route']->value;?>
<?php echo $_smarty_tpl->tpl_vars['item']->value['returnImg'];?>
');" style="width: 109px;height: 100px;margin: 5px;"/>
                </div>
                <?php }?>
            </td>
            <?php if ($_smarty_tpl->tpl_vars['item']->value['status']==1){?>
            <td style="color: green;">审核通过</td>
            <?php }elseif($_smarty_tpl->tpl_vars['item']->value['status']==-1){?>
            <td style="color: red;">
                审核未通过
                <br>
                <span style="color: black;">
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['examineRemark']==1){?>
                    不是vip用户
                    <?php }elseif($_smarty_tpl->tpl_vars['item']->value['examineRemark']==2){?>
                    回访信息不合格
                    <?php }?>
                </span>
            </td>
            <?php }elseif($_smarty_tpl->tpl_vars['item']->value['status']==2){?>
            <td style="color: #FFA500;">待认证</td>
            <?php }else{ ?>
            <td style="color: blue;">待审核</td>
            <?php }?>
            <td>
                <?php if ($_smarty_tpl->tpl_vars['item']->value['uid']){?>
                    <?php echo $_smarty_tpl->tpl_vars['item']->value['uid'];?>

                <?php }?>
            </td>
            <td>
                <?php if ($_smarty_tpl->tpl_vars['item']->value['phoneNum']){?>电话：<?php echo $_smarty_tpl->tpl_vars['item']->value['phoneNum'];?>
<?php }?>
                <br>
                <?php if ($_smarty_tpl->tpl_vars['item']->value['weixin']){?>微信：<?php echo $_smarty_tpl->tpl_vars['item']->value['weixin'];?>
<?php }?>
                <br>
                <?php if ($_smarty_tpl->tpl_vars['item']->value['qq']){?>Q Q：<?php echo $_smarty_tpl->tpl_vars['item']->value['qq'];?>
<?php }?>
                <br>
                <?php if ($_smarty_tpl->tpl_vars['item']->value['birthday']){?>生日：<?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['birthday']);?>
<?php }?>
            </td>
            <td style="width: 100px;">
                <?php if ($_smarty_tpl->tpl_vars['item']->value['revisitTime']!=0){?>
                <?php echo date('Y-m-d',$_smarty_tpl->tpl_vars['item']->value['revisitTime']);?>

                <?php echo date('H:i',$_smarty_tpl->tpl_vars['item']->value['revisitTime']);?>

                <?php }?>
            </td>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==22||$_smarty_tpl->tpl_vars['gid']->value==1){?>
            <td style="width: 70px;">
                <?php if ($_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==1){?>
                <a onclick="Examine('<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
')" <?php if ($_smarty_tpl->tpl_vars['item']->value['status']!=0){?> style="pointer-events: none;color: #ccc;"<?php }?>>审核</a>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['gid']->value==1||$_smarty_tpl->tpl_vars['gid']->value==21||$_smarty_tpl->tpl_vars['gid']->value==24){?>
                <a onclick="RetVisit('<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
')" <?php if ($_smarty_tpl->tpl_vars['item']->value['revisitStatus']==0||$_smarty_tpl->tpl_vars['item']->value['status']==0||($_smarty_tpl->tpl_vars['item']->value['status']==-1&&$_smarty_tpl->tpl_vars['item']->value['examineRemark']==1)){?> style="pointer-events: none;color: #ccc;"<?php }?>>回访</a>
                <?php }elseif($_smarty_tpl->tpl_vars['gid']->value==22&&$_smarty_tpl->tpl_vars['item']->value['uid']==$_smarty_tpl->tpl_vars['uid']->value){?>
                <a onclick="RetVisit('<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
')" <?php if ($_smarty_tpl->tpl_vars['item']->value['revisitStatus']==0||$_smarty_tpl->tpl_vars['item']->value['status']==0||($_smarty_tpl->tpl_vars['item']->value['status']==-1&&$_smarty_tpl->tpl_vars['item']->value['examineRemark']==1)){?> style="pointer-events: none;color: #ccc;"<?php }?>>回访</a>
                <?php }?>
            </td>
            <?php }?>
        </tr>
        <?php } ?>
        <?php }else{ ?>
        <tr>
            <td colspan="23">无相关数据</td>
        </tr>
        <?php }?>
    </table>
    <div id="pager"></div>
</body>
<form class="retVisit_from" action="/index.php?m=vipGuest&a=vipList&RetVisit" method="post"  enctype="multipart/form-data">
    <input type="hidden" name="form[uid]" value="" id="uid">
    <div>
        <div class="add_from_title">回访</div>
        <div class="add_from_box">
            <div class="box_q">
                <label>QQ：</label>
                <input name="form[qq]">
            </div>
            <div class="box_q">
                <label>微信：</label>
                <input name="form[wx]">
            </div>
            <div class="box_q">
                <label>电话：</label>
                <input name="form[phone]">
            </div>
            <div class="box_q">
                <label>维护号：</label>
                <input name="form[wid]">
            </div>
            <div class="box_q">
                <label>认证图</label>
                <input type="file" name="file" οnchange="changeSrc(this)" id="btn_file">
                <span style="color: red;">限制1M</span>
            </div>
        </div>
        <div style="margin-top: 30px;text-align: center;">
            <button style="width: 80px;" type="button" class="getSub">提交</button>
            <button style="margin-left: 15px;width: 80px;" id="cancel" type="button">取消</button>
        </div>
    </div>
</form>
<form class="examine_from" action="/index.php?m=vipGuest&a=vipList&Examine" method="post">
    <input type="hidden" name="form[uid]" value="" id="userid">
    <div>
        <div class="add_from_title">审核</div>
        <div style="text-align: center;margin-top: 10px;">
            <input style="margin-left: 25px;" type="radio" name="form[status]" value="1" checked="checked">通过
            <input style="margin-left: 25px;" type="radio" name="form[status]" value="-1" >不通过<br>
            <div class="select" style="display: none;">
                <label>原因：</label>
                <select name="form[examineRemark]" id="examineRemark"  style="width: 170px;margin-top: 15px;">
                    <option value="1">不是vip用户</option>
                    <option value="2">回访信息不合格</option>
                </select>
            </div>
            <br>
            <button style="margin-left: 25px;margin-top: 20px;" class="getExe" type="button">提交</button>
            <button style="margin-left: 25px;margin-top: 20px;" class="getExit" type="button">取消</button>
        </div>
    </div>
</form>
<style>
    .retVisit_from{
        display: none;
        width: 400px;
        height: 380px;
        background-color: #fff;
        position: fixed;
	    top: 30%;
	    left: 40%;
	    z-index: 12;
        border: 1px solid black;
    }
    .examine_from{
        display: none;
        width: 370px;
        height: 170px;
        background-color: #fff;
        position: fixed;
	    top: 30%;
	    left: 40%;
	    z-index: 12;
        border: 1px solid black;
    }
    .add_from input{
        font-size: 15px;
    }
    .add_from_title{
        height: 35px;
        line-height: 35px;
        width: 100%;
        text-align: center;
        background-color: beige;
    }
    .add_from_box{
        font-size: 15px;
        margin: 25px 25px 25px 25px;
    }
    .box_q{
        width: 80px;
    }
    /* 问题图标CSS */
    .list tr th span{
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        background: url('static/question_black.png');
        background-size: 100%;
    }
    .list tr th span:hover{
        display: inline-block;
        width: 20px;
        height: 20px;
        background: url('static/question_blue.png');
        background-size: 100%;
    }
    .question—relationLoginTime .question-content{
        width: 210px;
        background: #d1dee4;
        position: absolute;
        top: -50px;
        right: -40px;
        border-radius: 10px;
        color: #224f72;
        display: none;
        text-align: left;
    }
    .question—relationUserName .question-content{
        width: 210px;
        background: #d1dee4;
        position: absolute;
        top: -50px;
        right: -90px;
        border-radius: 10px;
        color: #224f72;
        display: none;
        text-align: left;
    }
</style>
<!-- 图片预览 开始 -->
<script>
    var login=document.getElementById("login");
    function imgshow(src){
        var large_image = '<img src= '+ src +'></img>';
        $('.image_large').show();
        $('.image_large').html($(large_image).attr("style","display:block;height:65%;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);z-index:5;box-shadow: 0 0 5px 5px #33FFFF ;"));
    }
    function hideImg() {
        $('.image_large').hide();
    }
    function bg() {
        $('.image_large').hide();
    }
</script>
<script src="js/pager.js"></script>
<!-- 图片预览 结束 -->
<!-- 回访弹窗 开始-->
<script>
    function RetVisit(uid){
        $('#uid').val(uid)
        $('.retVisit_from').show()
    }
    function Examine(uid) {
        $('#userid').val(uid)
        $('.examine_from').show()
    }
    $('.getExe').on('click',function(){
        var r = confirm('信息是否核对无误？');
        if (r == true) {
            $('.examine_from').submit();
        }
    })
    $('.getExit').on('click',function(){
        $('.examine_from').hide()
    })
    
    $('#cancel').on('click',function(){
        $('.retVisit_from').hide()
    })
    $('.getSub').on('click',function(){
        // 提交表单
        var file = document.getElementById('btn_file').files[0];
        if (file) {
            if (file.size > 1048576) {
                alert("上传的图片大小不能超过1M");
            }else{
                var r = confirm('信息是否核对无误？');
                if (r == true) {
                    $('.retVisit_from').submit();
                }
            }
        }else{
            alert("请上传图片");
        }
    })
    $('input[type=radio][name=form[status]]').change(function () {
        var myvalue = $(this).val();
        if (myvalue == 1) {
            $('.select').hide()
        }else{
            $('.select').show()
        }
    });
    // 页码
    var upperName = $('#upperName').val();
    var specialName = $('#specialName').val();
    var game = $('#game').val();
    var sort = $('#sort').val();
    var examine = $('#examine').val();
    var source = $('#source').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var userName = $('#userName').val();
    function gotoNext(page,pagesize){
         window.location.href = "/index.php?m=vipGuest&a=vipList&page=" + page+"&upperName="+upperName+"&specialName="+specialName+"&game="+game+"&sort="+sort+"&examine="+examine+"&source="+source+"&start_date="+start_date+"&end_date="+end_date+"&userName="+userName;
    }
    var pageStr = new Page('<?php echo $_smarty_tpl->tpl_vars['list_page']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['list_total']->value;?>
', 5, '<?php echo $_smarty_tpl->tpl_vars['list_length']->value;?>
', 'gotoNext').GetText();
    document.getElementById('pager').innerHTML = pageStr;
    // 鼠标经过问题图标显示提示或者取消提示
    $('.list tr th span').mouseenter(function(){
            var current_class = $(this).attr('class');
            $('.'+current_class).find(".question-content").css('display','block');
        })
    $('.list tr th span').mouseleave(function(){
        var current_class = $(this).attr('class');
        $('.'+current_class).find(".question-content").css('display','none');
    })

    $("#sub").click(function() {
        $("#operation").val("");
    });
    $("#report").click(function() {
        $("#operation").val("report");
        $('.searchbox').submit();
    });

</script>

    </div>
</div>

<!--腳部版權-->
<!-- <div class="footer middles">广州乾游网络科技有限公司|2Y9Y.com 版权所有 Copyright&copy;2014</div> -->
</body>
</html><?php }} ?>