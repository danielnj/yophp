<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name loader.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class Loader extends Base
{

	public function __construct(){}
	//----------------------------------------------------------
	/**
	 * 实例化业务模型
	 *
	 * @param file $model
	 * @return mixed
	 */
	public function load_model($model){
		try {
			//载入父驱动
            $p_modle = S_DB.'/db.php';
			if(is_file($p_modle)){
              include_once($p_modle);
			}else{
              throw new YO_exception("not find {$p_modle}");
			}
			//载入子驱动
          	$_model = strtolower($model);
			$_model = $_model.'_model';
			$_file = APP_PATH.'model/'.$_model.'.php';
			$_object = ucfirst($model).'Model';
			if (is_file($_file)) {
					include_once($_file);
				if (class_exists($_object)) {
		        	$this->model = new $_object();
					return $this->model ;
		        }else{
		        	throw new YO_exception("not find {$_object}");
		        }
			}else{
				throw new YO_exception("not found {$_file} file.");
			}
		}catch (YO_exception $e){
			echo $e->getMessage();
		}
	}

	/**
	 * 实例化视图模型
	 *
	 * @param file $model
	 * @return mixed
	 */
	public function load_view($type){
		try {
			$_type = strtolower($type);
			$_file = S_VIEW.'/view_'.$_type.'.php';
			$_object = 'YO_'.$_type;
			if (is_file($_file)) {

				include_once($_file);
				if (class_exists($_object)) {
					$this->v = $_object::getInstance();
					return $this->v;

		        }else{
		        	throw new YO_exception("not find {$_object}");
		        }
			}else{
				throw new YO_exception("not found {$_file} file.");
			}
		}catch (YO_exception $e){
			echo $e->getMessage();
		}
	}



/**
 * import  载入包含文件
 *
 * @param filename    需要载入的文件名或者文件路径
 * @param auto_search    载入文件找不到时是否搜索系统路径或文件，搜索路径的顺序为：应用程序包含目录 -> 应用程序Model目录 -> sp框架包含文件目录
 * @param auto_error    自动提示扩展类载入出错信息
 */


public function load_class($class, $instantiate = TRUE){

try {
	static $objects = array();

	if (isset($objects[$class]))
	{
		return $objects[$class];
	}else{
    $file_name = S_CLASS.'/class.'.$class.'.php';
	if (file_exists($file_name)){
      require_once ($file_name);
	  $class_name = 'YO_'.$class;
	  $objects[$class] =new $class_name();
	  return $objects[$class] ;
	}else{
      throw new YO_exception("Unfound {$class} class!");
	}
	}
	}catch (YO_exception $e){
		$e->getMessage();
	}
}


/**
 * import  载入包含文件
 *
 * @param filename    需要载入的文件名或者文件路径
 * @param auto_search    载入文件找不到时是否搜索系统路径或文件，搜索路径的顺序为：应用程序包含目录 -> 应用程序Model目录 -> sp框架包含文件目录
 * @param auto_error    自动提示扩展类载入出错信息
 */

function load_func($filename){

try {

    $file_name = S_FUNC.'/func_'.$filename.'.php';
	if (file_exists($file_name)){
      require_once ($file_name);
	  return true ;
	}else{
      throw new YO_exception("Unfound {$filename} fcuntion file!");
	  return false ;
	}
	}catch (YO_exception $e){
		$e->getMessage();
	}
}


}


?>