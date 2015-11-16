<?php

/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.benchmark.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

/**
 * Smarty文件模板视图类
 *
 * @desc 针对 Smarty Template 的模板View的模板加载
 *
 * 相关链接：
 *	Smarty官网：http://www.smarty.net/
 *	Smarty手册：http://www.phpchina.com/manual/smarty/
 *	Smarty入门：http://www.google.cn/search?q=%E8%8F%9C%E9%B8%9F%E5%AD%A6PHP%E4%B9%8BSmarty%E5%85%A5%E9%97%A8&btnG=Google+%E6%90%9C%E7%B4%A2
 */
require_once(S_3RD.'/smarty/Smarty.class.php');

class YO_smarty extends Smarty
{

	/**
	 * @var object 对象单例
	 */
	static $_instance = NULL;
	/**
	 * @var object 控制器对象
	 */
	public $controller = NULL;
	/**
	 * @var array Smarty对象参数
	 */
	public $params = array();
	/**
	 * @var bool 是否调试模式
	 */
	public $debug = false;


	/**
	 * 保证对象不被clone
	 */
	private function __clone() {}

    /**
	 * 构造函数
	 *
	 * @param object $controller 控制器对象
	 *
	 * @param array $params 需要传递的选项参数
	 */
	function __construct() {


        $this->Smarty();
        $this->template_dir = APP_PATH . 'view';
        $this->compile_dir = APP_PATH . 'cache/compiles/';
        $this->cache_dir = APP_PATH . 'cache/cache/';
        $this->config_dir = APP_PATH . 'cache/config/';
        $this->compile_check = true;
        $this->caching = false;
        //$this->debugging = true;
        //$this->cache_lifetime = 0;
        $this->left_delimiter = '<{';
        $this->right_delimiter = '}>';

	}


	/**
	 * 获取对象唯一实例
	 *
	 * @param void
	 * @return object 返回本对象实例
	 */
	public static function getInstance(){
		if (!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * 设置模板相应的调试模式
	 *
	 * @param bool $debug 是否调试模式，true or false
	 * @return void
	 */
	public function setDebug($debug = false){
		$this->debug = $debug;
	}


}
