<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name appliaction.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class Application extends Base
{
	private $control = 'c';
	private $action = 'm';
    private $basename ;

	public function __construct(){

    //数据过滤
		$_GET = $this->addslashes($_GET, 1, true);
		$_POST = $this->addslashes($_POST, 1, true);
		$_COOKIE = $this->addslashes($_COOKIE, 1, true);
		$_SERVER = $this->addslashes($_SERVER);
		$_FILES = $this->addslashes($_FILES);
		$_REQUEST = $this->addslashes($_REQUEST, 1, true);
        //载入核心配置文件
        $this->conf= config( require(S_CONFIG.'/config.php'));

	}

	//----------------------------------------------------------
	/**
	 * 重定义控制器名称
	 *
	 * @param string $control
	 */
	public function set_default_control($control){
		$this->control = strtolower($control);
	}

	//----------------------------------------------------------
	/**
	 * 重定义方法名称
	 *
	 * @param string $action
	 */
	public function set_default_action($action){
		$this->action = strtolower($action);
	}

	//----------------------------------------------------------
	/**
	 * 执行应用程序
	 *
	 */
	public  function run() {
		try {
			$this->dispatcher();
	        $_control = strtolower($this->control);
	        $_object = ucfirst($_control).'Control';
	        $_action = strtolower($this->action);
	        $_controlfile = 'controller/'.$_control.'.php';
	        if (is_file($_controlfile)) {
	        	require_once(S_ROOT.'control.php');
	        	require_once($_controlfile);
	        	if (class_exists($_object)) {
		        	$app = new $_object(&$this->conf);
		        	$app->$_action();
		        }else{
		        	throw new YO_exception("not find {$_object}");
		        }
	        }else{
	        	throw new YO_exception("not find {$_controlfile}");
	        }
		}catch (YO_exception $e){
			echo $e->getMessage();
		}
	}

	//----------------------------------------------------------
	/**
	 * 解析URL路径取得控制器与方法名
	 *
	 * @param string $control
	 * @param string $action
	 * @return void
	 */
	private function dispatcher()
	{
		// 当在二级目录中使用框架时，自动获取当前访问的文件名
     if(!isset($this->basename)){
	  if(basename($_SERVER['SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		$this->basename = $_SERVER['SCRIPT_NAME'];
	  elseif (basename($_SERVER['PHP_SELF']) === basename($_SERVER['SCRIPT_FILENAME']))
		$this->basename= $_SERVER['PHP_SELF'];
	  elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		$this->basename = $_SERVER['ORIG_SCRIPT_NAME'];
      }
       //路由解析

		$_request_url = parse_url($_SERVER['REQUEST_URI']);
	    $_splits = array();
		if(empty($_SERVER['PATH_INFO'])){
			if(empty($_request_url['query'])){
		     $this->control = 'do' ;
		     $this->action = 'test';
			}else{
           parse_str($_request_url['query'], $_splits);
		   $_control = addslashes(array_shift($_splits));
		   $_action = addslashes(array_shift($_splits));
		   $this->control = !empty($_control) ? $_control : $this->control ;
		   $this->action = !empty($_action) ? $_action : $this->action;
           foreach($_splits as $k=>$v){
             $_GET[$k] = isset($v) ? addslashes($v) : null;
		   }
		}
		}else{
           $_splits = explode("/", $_SERVER['PATH_INFO']);
		   $_control = addslashes($_splits[2]);
		   $_action = addslashes($_splits[4]);
		   $this->control = !empty($_control) ? $_control : $this->control ;
		   $this->action = !empty($_action) ? $_action : $this->action;
		   $max_u = count($_splits)-1;
		   for($u =1;$u<=$max_u;$u++){
            $_GET[$_splits[$u]] = isset($_splits[$u+1]) ? addslashes($_splits[$u+1]) : null;$u++;
		   }

		}





	}

	//----------------------------------------------------------
    /**
     * 数据过滤
     *
     *@access   public
     *@return   string
     **/
    private function addslashes($str, $force = 0, $strip = false) {
        if (!get_magic_quotes_gpc() || $force) {
        	if (is_array($str)) {
        		foreach ($str as $key => $value){
        			$str[$key] = $this->addslashes($value, $force);
        		}
        	}else{
        		$str = addslashes($strip ? stripslashes($str) : $str);
        	}
        }
        return $str;
    }
}