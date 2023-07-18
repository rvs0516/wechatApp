<?php
/**
 * 
+----------------------------------------------------------
 * 全局常量、数据库配置文件
+----------------------------------------------------------
 * 
 * 【说明】
 * 使用function.php函数库中的C函数引用当前文件数组数据，示例：获取数据库连接参数，则 C('dbengines')
 * 
 */
return array(
	'DISPLAY_ERROR'         => 0,   //是否显示错误
    'LOG_MODE'              => 2,   //日志模式，0：关闭 1：开发者 2：全开
    'DEVELOPER_LOG_TYPE'    => 'NOTICE,WARNING,ERROR,DEBUG',
    'DEVELOPER_LOG_TOKEN'   => 'qy', //日志令牌
    'ALL_LOG_TYPE'          => 'NOTICE,WARNING,ERROR,DEBUG',
	'tpl_suffix'			=> '.html',						// 模板后缀
	
	//cookie
	'COOKIE_EXPIRE'			=> '3600',						// cookie 的有效期
	'COOKIE_PATH'			=> '/',							// cookie服务器路径
	'COOKIE_DOMAIN'			=> '.7725com.co',				// cookie的域名
	'COOKIE_ENCODE'			=> 'BxKRv5971R',				// cookie加密字符串
	
	//域名
	'PC_HOME'				=> 'http://www.xxxx.com/',		// PC平台
	'STATIC_SOURCE_SITE'	=> 'http://test.resource.wxwork.2y9y.com/',	// 静态资源域名
	'MOBILE_HOME'			=> 'http://www.xxxx.com/',		// 手机平台
	
	//路径
	'DEDE_ROOT_PATH'		=> '/usr/share/nginx/html/wechatApp/www/',			// 根目录
	'DEDE_DATA_PATH'		=> '/usr/share/nginx/html/wechatApp/data/',			// 数据目录
	'APP_LIST_PATH'		    => '/usr/share/nginx/html/wechatApp/frameworks/apps/',		// 应用目录
	
	//数据库，【注意】数据库名称规则是企业主体简称拼音拼接“_corpWeixin”
	'dbengines' => array(
		'local' => array(
			'engine'  => 'mysql',
			'charset' => 'utf8',
			'nodes'   => array(
				array('host'=>'116.205.241.151', 'user'=>'root', 'password'=>'Qy#12345678', 'database'=>DATABASES_PREFIX.'_corpWeixin', 'type'=>'master'),
				// array('host'=>'116.205.241.151', 'user'=>'root', 'password'=>'Qy#12345678', 'database'=>DATABASES_PREFIX.'_corpWeixin', 'type'=>'slave'),
			),
		),
		// …… other clusters.
	)
);
