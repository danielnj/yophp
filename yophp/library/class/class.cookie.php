<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name cookie.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_cookie {

	var $cookiepre = '';
	var $cookiepath = '/';
	var $cookiedomain = '';
	var $search = '';

//cookie设置
function ssetcookie($var, $value, $life ) {
	
	if($life != -1){
	$life = time() + $life*8;
	}
	setcookie ( $cookiepre . $var, $value,time()+ $life, $cookiepath, $cookiedomain, $_SERVER ['SERVER_PORT'] == 443 ? 1 : 0 );

}

//cookie获取
function getcookie($key) {
		return $_COOKIE [$cookiepre . $key];
}


}
?>
