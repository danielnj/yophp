<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.fitphp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name control.class.php
 *
 * @Author Hongbin Hsu <Hongbin.Hsu@gmail.com> Initial.
 * @Since 2009/08/09
 * @Version $Id:$
 */

abstract class Control extends Base
{
	public $view = null;
	protected $load = null;

	//----------------------------------------------------------
	/**
	 * 构造控制器
	 *
	 * @return void
	 */
	public function __construct($config){

		$this->init();
		$this->config = $config;

	}

	//----------------------------------------------------------
	/**
	 * 初始化控制器
	 *
	 * @return void
	 */
	public function init(){

	    //核心加载类
		require_once(S_ROOT.'loader.php');
		$this->load = new Loader();

        //加载常用函数库
        $this->load->load_func('string');
        $this->load->load_func('utils');
		//加载核心类
		$loader_class = array('args','post','file','cookie','session','log');
        foreach($loader_class as $key => $value){
		   $this->$value = $this->load->load_class( $value);
		}
	   //载入视图类
       $this->view = $this->load->load_view('php');

	}

	//----------------------------------------------------------
	/**
	 * 获取Loader对象私有属性
	 *
	 * @param string $name
	 * @return object
	 */
	public function __get($name){
		return (isset($this->$name)) ? $this->$name : $this->load->$name;
	}
}