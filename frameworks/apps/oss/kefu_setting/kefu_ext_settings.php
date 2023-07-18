<?php

$kefuconfig['upload_allow_exts'][]='rar';
$kefuconfig['upload_allow_exts'][]='txt';
$kefuconfig['upload_allow_exts'][]='gif';

$kefuconfig['upload_allow_type'][]='rar';
$kefuconfig['upload_allow_type'][]='text/plain';
$kefuconfig['upload_allow_type'][]='image/gif';
$kefuconfig['upload_allow_type'][]='image/pjpeg';
$kefuconfig[ 'upload_max_filesize'] = 5*1024*1024*1024;
$kefuconfig[ 'host'] = 'http://'.$_SERVER['HTTP_HOST'];
$kefuconfig['ico']['txt'] = 'txt.jpg';
$kefuconfig['ico']['gif'] = 'jpg.jpg';
?>
