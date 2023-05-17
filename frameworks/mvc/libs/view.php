<?php
/**
 * Project:    Simple mvc for apps
 * File:       view.php
 *
 * View层
 *
 * 允许页面设计师自由编写模版文件源代码控制前端的UI效果输出, 
 * 涉及模版控制语言方面需有相关经验技术人员方可更改
 *
 * 这个类使用工厂模式设计, 方便以后多种模版引擎的扩展使用, 
 * 现阶段只实现Smarty
 *
 * @link http://www.2y9y.com/
 * @copyright 2014 QianYou Group, Inc.
 * @author Prolove
 * @package Mvc
 * @version 1.0.0
 */

class view {
	/**
	 * 当前使用的模版引擎对象
	 */
	protected $engine = null;

	/**
	 * 根据提供的引擎名称初始化模版引擎对象
	 *
	 * @param string $engineName 引擎名称, 默认是Smarty
	 * @return void
	 */
	public function __construct($engineName = 'smarty') {
		$this->engine = getInstance('plugins.' . $engineName . '.' . $engineName);
	}
	
	/**
	 * 设置引擎读取模板的路径
	 *
	 * @param string $templateDir
	 * @return object view
	 */
	public function setTemplateDir($templateDir) {
		$this->engine->setTemplateDir($templateDir);
		return $this;
	}
	
	/**
	 * 设置引擎预编译文件的保存路径
	 *
	 * @param string $compileDir
	 * @return object view
	 */
	public function setCompileDir($compileDir) {
		$this->engine->setCompileDir($compileDir);
		return $this;
	}
	
	/**
	 * 模板赋值
	 *
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $view->assign('name', 'value of key');
	 * 2. 多组赋值 $view->assign( array('a'=>1, 'b'=>2) );
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
    public function assign($key, $value = null) {
		$this->engine->assign($key, $value);
        return $this;
    }
	
	/**
	 * 解析模板文件, 输出页面内容并且返回页面内容
	 *
	 * @param string $templateFile 模板文件, 如果参数为空将使用应用当前的模板文件
	 * @return string
	 */
	public function display($templateFile) {
		$content = $this->fetch($templateFile);
		echo $content;
		return $content;
	}
	
	/**
	 * 解析模板文件, 返回页面内容
	 *
	 * @param string $templateFile 模板文件
	 * @return string
	 */
	public function fetch($templateFile) {
		return $this->engine->fetch($templateFile);
	}
}
