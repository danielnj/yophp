<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name global.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

 /**
 * 自动加载核心模块
 *
 * @param string $classname
 */

function __autoload($class){
	try {


		if(preg_match ('/^YO_/i',trim($class))){
		  $_class = preg_replace ('/^YO_/i','',$class);
		}else{
		  $_class = str_replace('_', '/', strtolower($class));
		}

		$_file = S_CLASS.'/class.'.$_class.'.php';
		if (is_file($_file)) {
			require_once($_file);
		}else{
			throw new YO_exception("Unfound {$_file} file!");
		}
	}catch (YO_exception $e){
		$e->getMessage();
	}
}




 /**
 * 自定义调式函数
 *
 */
function dump($vars, $label = '', $return = false) {
	if (ini_get ( 'html_errors' )) {
		$content = "<pre>\n";
		if ($label != '') {
			$content .= "<strong>{$label} :</strong>\n";
		}
		$content .= htmlspecialchars ( print_r ( $vars, true ) );
		$content .= "\n</pre>\n";
	} else {
		$content = $label . " :\n" . print_r ( $vars, true );
	}
	if ($return) {
		return $content;
	}
	echo $content;
	return null;
}

/**
 * import  载入包含文件
 *
 * @param filename    需要载入的文件名或者文件路径
 * @param auto_search    载入文件找不到时是否搜索系统路径或文件，搜索路径的顺序为：应用程序包含目录 -> 应用程序Model目录 -> sp框架包含文件目录
 * @param auto_error    自动提示扩展类载入出错信息
 */
function import($vars, $path) {

try {
	static $ob_file = array();

	if (isset($ob_file[$vars]))
	{
		return $ob_file[$vars];

	}else{
    $file_name = $path.'/'.$vars.'.php';
	if (file_exists($file_name)){
      require_once ($file_name);

	}else{
      throw new YO_exception("Unfound {$vars} file!");
	}
	}
	}catch (YO_exception $e){

		$e->getMessage();
	}


}

/**
 * config   快速将用户配置覆盖到框架默认配置
 *
 * @param preconfig    默认配置
 * @param useconfig    用户配置
 */
function config( $preconfig, $useconfig = null){
	$nowconfig = $preconfig;

	if (is_array($useconfig)){
		foreach ($useconfig as $key => $val){
			if (is_array($useconfig[$key])){
				@$nowconfig[$key] = is_array($nowconfig[$key]) ? config($nowconfig[$key], $useconfig[$key]) : $useconfig[$key];
			}else{
				@$nowconfig[$key] = $val;
			}
		}
	}

	return $nowconfig;
}

/**
 * print_r  彩色输出
 *
 * @param var    需要载入的文件名或者文件路径
 */


function print_c($var,$memo = null)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $color_bg = "RGB(" . rand(100, 255) . "," . rand(100, 255) . "," . rand(100, 255) . ")";
            if (! is_null($memo)) {
                $prefix = '<FIELDSET style="font-size:12px;font-family:Courier New;"><LEGEND style="padding:5px;">' . $memo . '</LEGEND>';
                $postfix = '</FIELDSET>';
            } else {
                $prefix = $postfix = "";
            }
            echo $prefix . '<pre style="font-size:12px;padding:5px;border-left:5px solid #0066cc;font-family:Courier New;color:black;text-align:left;background-color:' . $color_bg . '">' . "\n";
            print_r($var);
            echo "\n</pre>" . $postfix;
        } else {
            if (! is_null($memo)) {
                echo $memo . " - - - - -\n";
            }
            print_r($var);
            echo "\n";
        }
    }

  /**
     * 返回 HTTP 请求头中的指定信息，如果没有指定参数则返回 false
     * @param string $header 要查询的请求头参数
     * @return string 参数值
     */
    function header_to($header)
    {
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (! empty($_SERVER[$temp]))
            return $_SERVER[$temp];

        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (! empty($headers[$header]))
                return $headers[$header];
        }

        return false;
    }
  /**
     * 返回 HTTP 请求来源
     * @param string $header 要查询的请求头参数
     * @return string 参数值
     */
    function requesturl()
    {

		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) { // SSI, MEDIATEMPLE
            $uri = $_SERVER['REDIRECT_URL'] . '?' . $_SERVER['REDIRECT_QUERY_STRING'];
        } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['HTTP_X_REWRITE_URL']; // IIS
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
            $uri = $_SERVER['ORIG_PATH_INFO'];
            if (! empty($_SERVER['QUERY_STRING'])) {
                $uri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            $uri = '';
        }
        return $uri;
    }

   /**
     *
     * 跳转程序
     * 应用程序的控制器类可以覆盖该函数以使用自定义的跳转程序
     * @param $url  需要前往的地址
     * @param $delay   延迟时间
     */
    function jump($url, $delay = 0){
		echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
		exit;
    }
?>