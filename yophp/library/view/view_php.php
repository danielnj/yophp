<?php

/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name php.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

/**
 * 原生PHP文件模板视图类
 *
 * @desc 使用PHP原生程序作为模板
 */
class YO_php extends View
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
	 * @var bool 是否调试模式
	 */
	public $debug = false;


	/**
	 * 保证对象不被clone
	 */
	private function __clone() {}

    /**
	 * 构造函数
	 */
	public function __construct() {
      $this->filepath = APP_PATH.'view';
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


	/**
	 * 解析处理一个模板文件
	 *
	 * @param  string $filePath  模板文件路径
	 * @param  array  $vars 需要给模板变量赋值的变量
	 * @return void
	 */
	public function display($filePath, $vars) {
		$filePath = $this->filepath.'/' . $filePath.'.php';
		if(!is_file($filePath) || !is_readable($filePath)){
			throw new exception("View file ". $filePath ." not exist or not readable");
		}

		if (!empty($vars)){
			foreach($vars as $key => $value){
				$$key=$value;
			}
		}
		require_once($filePath);

		if ($this->debug){
			var_dump($vars);
		}
	}

}