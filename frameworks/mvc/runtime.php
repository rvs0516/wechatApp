<?php
// 任何使用框架的都必须先指定一个应用名字, 并且该名字在apps目录存在
defined('APP_NAME') || die('未授权的应用！');

header('Content-type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
//系统时间
define('TIME', time());
//调用使用的时间
define('MICROTIME', microtime(true));

// 路径分隔符
define('DS', DIRECTORY_SEPARATOR);

// 框架路径
define('MVC_PATH', dirname(__FILE__) . DS);
// 框架主体路径
define('LIB_PATH', MVC_PATH . 'libs' . DS);
// 功能模块路径
define('MODEL_PATH', MVC_PATH . 'models' . DS);
// 公共缓存路径
define('CACHE_PATH', MVC_PATH . 'cache' . DS);

//应用列表目录
define('APP_LIST_PATH', dirname(MVC_PATH) . DS . 'apps' . DS); 
// 应用目录
define('APP_PATH', dirname(MVC_PATH) . DS . 'apps' . DS . APP_NAME . DS);
// 应用私有模块
define('APP_MODEL_PATH', APP_PATH . 'models' . DS);
// 应用控制器目录
define('APP_CONTROLLER_PATH', APP_PATH . 'controllers' . DS);
// 应用模板目录
define('APP_VIEW_PATH', APP_PATH . 'view' . DS);
// 应用预编译目录
define('APP_COMPILED_PATH', APP_PATH . 'compiled' . DS);
// 应用缓存目录
define('APP_CACHE_PATH', APP_PATH . 'cache' . DS);

// 获取登录的企业成员所属企业主体简称拼音

// 企业成员微信userid
// $follow_userid = "";
// define('FOLLOW_USERID', $follow_userid);

// 数据库名称前缀
define('DATABASES_PREFIX', "qianyou");

error_reporting(0);
set_error_handler(array('P7725', 'errorHandler'));
register_shutdown_function(array('P7725', 'shutdownHandler'));

// 加载函数库
require_once 'functions.php';
//加载全局配置
C(require 'global.inc.php');

/**
 * 基础运行类
 */
class P7725 {
    
    /**
     * 路由实例
     * @var Route
     */
    private static $_route = null;
    
    /**
     * 是否使用路由
     * 
     * 注：是否使用路由不是由当前默认参数决定的，是由self::_initRoute计算的
     * @var boolean
     */
    private static $_use_route = false;
    
    /**
     * 控制器实例
     * @var string
     */
    private static $_control;
    
    /**
     * 控制器名字
     * @var string
     */
    private static $_control_name;
    
    /**
     * 方法名字
     * @var string
     */
    private static $_action_name;
    
    /**
     * 要记录的日志类型
     * @var string
     */
    private static $_log;

    /**
     * 单例静态类
     */
    private function __construct() {
        ;
    }
    
    /**
     * 加载基础运行库
     */
    private static function _loadBasicLibrary() {
        self::log('DEBUG', '加载基础运行库');
        load('p7725Exception');
        // 加载主体框架
        load('model');
        load('view');
        // 决定采用REST还是MVC模式
        if(defined('REST')) {
            load('restController');
        } else {
            load('controller');
        }
        // 载入路由器
        load('route');
    }
    
    /**
     * 初始化配置
     */
    private static function _initConfig() {
        self::log('DEBUG', '初始化配置');
        // 初始化应用私有配置
        $app_config = APP_PATH . 'config.inc.php';
        if (file_exists($app_config)) {
            C(require $app_config);
        }
        /*配置的数据库连接参数
        dbengines示例值：
        'dbengines' => array(
            'local' => array(
                'engine'  => 'mysql',
                'charset' => 'utf8',
                'nodes'   => array(
                    array('host'=>'127.0.0.1', 'user'=>'root', 'password'=>'root', 'database'=>DATABASES_PREFIX.'_corpWeixin', 'type'=>'master'),
                ),
            ),
        )*/
        if ($dbEngines = C('dbengines')) {
            model::getInstance($dbEngines); // 使用load('model')中的getInstance函数，也就是mvc/lib/model.php下的getInstance函数
        }
    }
    
    /**
     * 初始化路由
     */
    private static function _initRoute() {
        self::log('DEBUG', '初始化路由');
        self::$_route = new Route(include 'route.inc.php', loadC('route.inc.php'));
        //PATHINFO或者GET参数为空时，就使用路由解析
        $pathinfo = self::$_route->getPathInfo();
        $use_get = (isset($_GET['m']) || isset($_GET['a']));
        self::$_use_route = !$use_get;
    }
    
    /**
     * 初始化控制器
     */
    private static function _initControl() {
        self::log('DEBUG', '初始化控制器');
        $control = null;
        if(self::$_use_route) {
            if(self::$_route->parse()) {
                self::$_control_name = self::$_route->getContorller();
                self::$_action_name = self::$_route->getMethod();
                $control = self::_getControlInstance();
            }
            // 实例化控制器
            if (!$control) {
                self::log('DEBUG', '找不到控制器或方法，尝试路由至404配置');
                self::$_route->to404Error();
                self::$_control_name = self::$_route->getContorller();
                self::$_action_name = self::$_route->getMethod();
                $control = self::_getControlInstance();
            }
        }
        //兼容GET的方式
        else {
            self::$_control_name = isset($_GET['m']) ? $_GET['m'] : 'index';
            self::$_action_name = isset($_GET['a']) ? $_GET['a'] : 'index';
            $control = self::_getControlInstance();
        }
        self::$_control = $control;
    }
    
    /**
     * 获取一个控制器实例
     * 这个实例对象包含一个属性：
     * object 实现控制器逻辑的类
     */
    private static function _getControlInstance() {
        // 加载控制器
        $file = APP_CONTROLLER_PATH . self::$_control_name . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        $control = new stdClass();
        $class  = self::$_control_name . 'Controller';
        if (!method_exists($class, self::$_action_name)) {
            return false;
        }
        $control->object = new $class();
        return $control;
    }
    
    /**
     * 错误处理
     * 
     * @param int $errno 错误编号
     * @param string $errstr 错误描述
     * @param string $errfile 文件
     * @param int $errline 文件行号
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline) {
        $error_types = array(
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_NOTICE => 'NOTICE'
        );
        if(!isset($error_types[$errno])) {
            return;
        }
        $type = $error_types[$errno];
        $message =  $errstr . ' ' . $errfile . ' ' . $errline;
        if(C('DISPLAY_ERROR')) {
            echo $type . ': ' . $message . "<br />\n";
        }
        self::log($type, $message);
    }
    
    /**
     * 脚本停止后执行一些额外操作，主要是记录异常
     */
    public static function shutdownHandler() {
        //获取最后一个错误
        $error = error_get_last();
        //只对致命错误操作，因为一般错误已经被self::errorHandler捕获
        $error_types = array( E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR );
        if( in_array($error['type'], $error_types) ) {
            self::errorHandler(E_ERROR, $error['message'],
                $error['file'], $error['line']);
        }
        if(!empty(self::$_log)) {
            //记录程序运行信息
            $time_consuming = number_format(microtime(true) - MICROTIME, 4);
            if(method_exists('model', 'getQueryCount')) {
                $query_count = model::getQueryCount() . '次';
            } else {
                $query_count = '未知';
            }
            self::log('DEBUG', "程序结束");
            self::log('DEBUG', "数据库查询次数：{$query_count}; 程序耗时: {$time_consuming}秒");
        }
    }
    
    /**
     * 初始化日志
     */
    private static function _initLog() {
        load('log');
        $mode = C('LOG_MODE');
        if($mode == 1) {
            $type = C('DEVELOPER_LOG_TYPE');
            $token = C('DEVELOPER_LOG_TOKEN');
        } else {
            $type = C('ALL_LOG_TYPE');
            $token = '';
        }
        self::$_log = log::getInstance($mode, explode(',', $type), $token);
    }
    
    /**
     * 记录系统日志
     * 
     * @param string $type 类型
     * @param string $message 描述
     * @param string $null_line [optional] 新起一个空行
     */
    public static function log($type, $message, $null_line=false) {
        //日志可能还没正确初始化之前就出现错误，增加一个if判断是有必要的
        if(self::$_log) {
            self::$_log->write($type, $message, $null_line);
        }
    }
    
    /**
	 * 获取一个日志助手
	 * 
	 * @param string [可选] $filePrefix 日志前缀
	 * @param string [可选] $splitSize 自动分割大小，单位M，当为0时不进行分割
	 * @throws p7725Exception
	 */
    public static function logger($filePrefix = 'log', $splitSize = 5) {
        static $load = false;
        if(!$load) {
            load('logger');
            $load = true;
        }
        return new logger( C('DEDE_DATA_PATH') . '/logs', $filePrefix, $splitSize );
    }
    
    /**
     * Let's go!
     */
    public static function run() {
        self::_initLog();
        self::log('DEBUG', '外部连接：' .
                $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], true);
        self::log('DEBUG', "程序开始");
        self::_loadBasicLibrary();
        self::_initConfig();
        self::_initRoute();
        self::_initControl();
        if(!self::$_control) {
            self::log('DEBUG', '找不到控制器或方法，输出默认404页面');
            show404();
        }
        // 设置模板路径和解析文件保存路径
        self::$_control->object->setTemplateDir(APP_VIEW_PATH)->setCompileDir(APP_COMPILED_PATH);
        self::$_control->object->assign('cookie_domain', C('COOKIE_DOMAIN'));
        // 调用控制器的方法
        self::log('DEBUG', '调用' . self::$_control_name . '控制器的' . self::$_action_name .'方法');
        if(self::$_use_route) {
            call_user_func_array(
                array(self::$_control->object, self::$_action_name),
                self::$_route->getParams()
            );
        } else {
            self::$_control->object->{ self::$_action_name }(); // 执行请求
        }
        
        // 记载模板页面
        self::log('DEBUG', '输出结果');
        $template_file = self::$_control_name . DS . self::$_action_name . C('tpl_suffix');
        self::$_control->object->display($template_file);
    }
}

P7725::run();
