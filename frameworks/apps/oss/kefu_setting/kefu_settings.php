<?php 
  
 $kefuconfig=array (
  'robot_enable' => 0,
  'rewrite_enable' => 0,
  'site_closed' => 0,
  'access_enable' => 0,
  'ad_enable' => 0,
  'charset' => 'utf-8',
  'compiled_root_path' => './cache/templates/',
  'constants' => 
  array (
  ),
  'lang' =>'zh',
  'upload_allow_exts' =>array('jpg','png','docx','doc','bmp','gz','zip','xls','rar'),
  'upload_allow_type' =>array('image/png','image/jpeg','image/pjpeg','image/bmp','application/vnd.msexcel','application/octetstream','application/octet-stream','rar','application/gzip','application/vnd.openxmlformatsofficedocument.wordprocessingml.document','application/msword'),
  'upload_maxsize' => 5000000,
  'upload_rule' => 'upload_rule',

  'steppath' =>'',
  'upload_path' => '/img/uploads/',
  'cookie_domain' => '',
  'cookie_expire' => '30',
  'cookie_path' => '/',
  'cookie_prefix' => 'rhhj7k_',
  'cookie_namespace' => 'oss_admin',
  'copyright' => 'asfasdfasfda',
  'count_online_user' => '1',
  'default_module' => array('index'=>'index'),
  'template_root_path' => '/templates',
  'template_path' => '/default'
);

$kefuconfig['status'][0] = '未處理';
$kefuconfig['status'][1] = '處理中';
$kefuconfig['status'][2] = '已解決';
$kefuconfig['ico']['rar'] = 'rar.jpg';
$kefuconfig['ico']['png'] = 'rar.jpg';
$kefuconfig['ico']['zip'] = 'rar.jpg';
$kefuconfig['ico']['jpg'] = 'jpg.jpg';
$kefuconfig['ico']['doc'] = 'doc.jpg';
$kefuconfig['ico']['docx'] = 'doc.jpg';
$kefuconfig['ico']['xls'] = 'xls.jpg';
$kefuconfig['main_host'] = 'http://www.7725.com';
$kefuconfig['main_host_templets_skin'] = $kefuconfig['main_host'] .'/templets/kings';


 ?>