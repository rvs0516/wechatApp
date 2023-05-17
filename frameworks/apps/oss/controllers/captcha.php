<?php
/**
 * 生成验证码
 * 用途：防止恶意频繁请求登录接口
 * @author heyongzhen <yongzhen.he@2y9y.com>
 * @version 2022.09.08
 */

 // 开启session服务
session_start();

// 创建图片资源，设置背景宽高，imagecreatetruecolor(int $width, int $height)
$im = @ imagecreatetruecolor ( 100 ,  20 ) or die( 'Cannot Initialize new GD image stream' );

// 设置背景色白色，imagecolorallocate(resource $image,int $red,int $green,int $blue)
$bg_color = imagecolorallocate( $im, 220, 220, 220 );
imagefill( $im, 0, 0, $bg_color );

// 生成由随机字母和数字组成的验证码内容, 为了避免混淆，去掉了字母L、l、O、o和数字0、1
$str = 'ABCDEFGHJKMNPQRSTUVWXYZabcdeefghjkmnpqrstuvwxyz23456789';
$len = strlen($str);    // 获取字节长度
$c_len = $len / 3;  // 字符数量（UTF-8）;
$char1 = substr( $str, mt_rand( 0, $c_len - 1) * 3, 1 );
$char2 = substr( $str, mt_rand( 0, $c_len - 1) * 3, 1 );
$char3 = substr( $str, mt_rand( 0, $c_len - 1) * 3, 1 );
$char4 = substr( $str, mt_rand( 0, $c_len - 1) * 3, 1 );
$captcha = $char1.$char2.$char3.$char4;

// 保存验证码内容到session中，登录时通过$_POST到的验证码内容跟$_SESSION获取到的验证码内容比对是否相同
$_SESSION['captcha'] = $captcha;

// 设置字体颜色
$text_color = imagecolorallocate ( $im ,  0, 0, 0 );

// 根据坐标将验证码内容随机分布
$num = 4; // 验证码字符位数
$x = rand(4, 9);
for ( $i = 0; $i < $num; $i++) {
    $y = rand(1, 5);
    // 设置文字字体和坐标， imagestring(resource $image,int $font,int $x,int $y,string $s,int $col)
    imagestring ( $im , 5 , $x , $y ,  substr( $captcha, $i, 1 ) , $text_color );
    $x += rand(10, 30);
}

// 设置雪花点作干扰元素，$i为雪花点数量
for( $i = 0; $i < 600; $i++ ){
    $inputcolor = imagecolorallocate( $im, rand( 50, 200 ), rand( 20, 200 ), rand( 50, 200 ) );
    // 画一个单一像素，imagesetpixel(resource $image,int $x,int $y,int $color)
    imagesetpixel( $im, rand(1,200), rand(1,39), $inputcolor );
}

// 设置干扰背景线，$i为线条数
for( $i = 0; $i < 2; $i++){
    $line_color = imagecolorallocate( $im, mt_rand( 150, 250 ), mt_rand(150,250), mt_rand( 150, 250 ) );
    // 画一条线段，imageline(resource $image,int $x1,int $y1,int $x2,int $y2,int $color)
    imageline( $im,mt_rand( 0, 200 ), mt_rand( 0, 50 ), mt_rand( 0, 200 ), mt_rand( 0, 50 ), $line_color );
}

// 设置内容输出格式为图片png
header ( 'Content-Type: image/png' );
// 输出资源，imagepng(resource $image, string $filename = ?)，将 GD 图像流（image）以 PNG 格式输出到标准输出（通常为浏览器），或者如果用 filename 给出了文件名则将其输出到该文件
imagepng ( $im );

// 销毁资源，imagedestroy(resource $image)，释放与 image 关联的内存
imagedestroy ( $im );