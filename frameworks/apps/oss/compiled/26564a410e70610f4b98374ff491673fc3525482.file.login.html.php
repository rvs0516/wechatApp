<?php /* Smarty version Smarty-3.1.11, created on 2022-09-28 10:14:24
         compiled from "H:\phpstudy\WWW\p2y9y_anysdk\frameworks\apps\oss\view\priv\login.html" */ ?>
<?php /*%%SmartyHeaderCode:264676152d15909e327-96425199%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '26564a410e70610f4b98374ff491673fc3525482' => 
    array (
      0 => 'H:\\phpstudy\\WWW\\p2y9y_anysdk\\frameworks\\apps\\oss\\view\\priv\\login.html',
      1 => 1663587006,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '264676152d15909e327-96425199',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_6152d15913e5f2_60684179',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6152d15913e5f2_60684179')) {function content_6152d15913e5f2_60684179($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>爱游就游中央数据后台</title>

<style type="text/css">
.loginTable{width: 400px;height: 550px; background: #ffffff; margin: 0 auto; border-radius: 10px; border: 1px solid #d8d8d8;}
.loginTable p input {width: 250px;height: 40px;padding-left: 5px;}
.loginTable p button{width: 250px;height: 40px; margin-top:5px; border:none; color: #fff; font-size: 16px;background: -webkit-gradient(linear, 0 0, 0 100%, from(#fb4949), to(#c60c0c)); border-radius: 5px; cursor:pointer}
.loginTable p button:hover{background: -webkit-gradient(linear, 0 0, 0 100%, from(#fb4949), to(#c60c0c));box-shadow: 0 -1px 3px #c2ced3;}
</style>

</head>
<body style="background:#eceeed;">
<form action="index.php?m=priv&a=login&do" method="post">
    <div class="loginTable" align="center" style="margin-top:80px; padding:0px;">
        <p style="font-size: 36px; color: #555555; letter-spacing: 10px; font-family:华文中宋">中央数据后台</p>
        <p>
            <span>用户名</span>
        </p>
        <p>   
            <input type="text" name="username" id="username" value="root"/>
        </p>
        <p>
            <span>密&nbsp;&nbsp;&nbsp;码</span>
        </p>
        <p>    
            <input type="password"  name="userpass" id="userpass" value="root"/>
        </p>
        <p style="position: relative;">
            <input type="text" name="captcha" placeholder="请输入图片中的验证码" style="width:145px; height:30px; position: absolute;left: 70px;">
            <img src="/index.php?m=captcha" width="100" height="35" alt="验证码" id="verify" style="position: absolute;right: 70px;top: 1px;">
        </p>
        <p style="height: 35px"></p>
        <p>
            <button type="submit">立即登入</button>
        </p>
        <p>
            <button type="reset">重置表单</button>
        </p>
    </div>
</form>
<script type="text/javascript">
    // 刷新验证码
    window.onload = function () {
        var img = document.getElementById("verify");
        img.onclick = function() {
            //我们刷新验证码需要重新请求服务器，我们路径不能是一模一样的，不然浏览器会一直加载本地已经缓存的图片，通过加一个时间后缀来欺骗服务器来达到我们的目的。
            var date = new Date().getTime();
            img.src = "/index.php?m=captcha&time=" + date;	
        }
    }
</script>
</body>
</html><?php }} ?>