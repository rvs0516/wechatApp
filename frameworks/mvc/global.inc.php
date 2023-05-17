<?php

return array(
	'DISPLAY_ERROR'         => 1,   //是否显示错误 
    'LOG_MODE'              => 0,   //日志模式，0：关闭 1：开发者 2：全开
    'DEVELOPER_LOG_TYPE'    => 'NOTICE,WARNING,ERROR,DEBUG',
    'DEVELOPER_LOG_TOKEN'   => 'qy', //日志令牌
    'ALL_LOG_TYPE'          => 'NOTICE,WARNING,ERROR,DEBUG',
	'tpl_suffix'			=> '.html',						// 模板后缀
	
	//cookie
	'COOKIE_EXPIRE'			=> '3600',						// cookie 的有效期
	'COOKIE_PATH'			=> '/',							// cookie服务器路径
	'COOKIE_DOMAIN'			=> '.2y9y.co',				// cookie的域名
	'COOKIE_ENCODE'			=> 'BxKRv5971R',				// cookie加密字符串
	
	//域名
	'PC_HOME'				=> 'http://anysdk.2y9y.co/',		// PC平台
	'STATIC_SOURCE_SITE'	=> 'http://statis.yysqi.co/',		// 静态资源域名
	'MOBILE_HOME'			=> 'http://anysdk.2y9y.co/',		// 手机平台
	'ANYSDK_HOME'			=> 'http://anysdk.2y9y.co/',	// 手机平台
	'ADS_URL'				=> 'http://test.fff.2y9y.com/',	// 手机平台
	'MIS_URL'				=> 'http://api.2y9y.co/',
	'H5_HOME'				=> 'http://anysdk.2y9y.co/',
	'NOTIFY_SITE'			=> 'http://notify.yysqi.com/',
	
	//路径
	'DEDE_ROOT_PATH'		=> '/phpstudy/www/wechatApp/www/',			// 根目录
	'DEDE_DATA_PATH'		=> '/phpstudy/www/wechatApp/data/',			// 数据目录
	'APP_LIST_PATH'		    => '/phpstudy/www/wechatApp/frameworks/apps/',		// 应用目录

	'VAR_PAGE' => 'p',
	'AJAX_FUNCTION' => 'goNext'	,
	
	//储值通道
	'payment' => array(
		1 => 'alipay',	//支付宝
		2 => 'upmp',	//银联
		3 => 'heepay',	//汇元
		4 => 'tenpay_yd',//财付通_移动
		5 => 'tenpay_lt',//财付通_联通
		6 => 'tenpay_dx',//财付通_电信
		7 => 'weixin',//微信
	),
	//储值通道
	'paymentChannel' => array(
		'alipay' 	=> '支付宝',
		'upmp' 		=> '银联',
		'heepay' 	=> '汇元',
		'tenpay_yd' => '移动储值卡',
		'tenpay_lt' => '联通储值卡',
		'tenpay_dx' => '电信储值卡',
		'weixin'    => '微信',
	),	
	
	//数据库
	'dbengines' => array(
		'local' => array(
			'engine'  => 'mysql',
			'charset' => 'utf8',
			'nodes'   => array(
				//array('host'=>'localhost', 'user'=>'root', 'password'=>'root', 'database'=>'p2y9y_anysdk', 'type'=>'master'),
				array('host'=>'139.159.198.70', 'user'=>'root', 'password'=>'917ee6aaa39da70a6cd572cf840e392d', 'database'=>'p2y9y_anysdk', 'type'=>'master'),
			),
		),
		// …… other clusters.917ee6aaa39da70a6cd572cf840e392d
	)
);