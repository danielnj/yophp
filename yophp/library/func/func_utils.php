<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name untils.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

function getmicrotime() {
	list ( $usec, $sec ) = explode ( " ", microtime () );
	return (( float ) $usec + ( float ) $sec);
}

//Get starnd datatime
function gettime() {
	$time = date ( "Y-m-d H:i:s", time () );
	return $time;
}
function getdates() {
	$times = date ( "Y-m-d", time () );
	return $times;
}


function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? ''
	: ($_SERVER["HTTPS"] == "on") ? "s"
	: "";
	$protocol = substr($_SERVER["SERVER_PROTOCOL"],0,strpos($_SERVER["SERVER_PROTOCOL"],  "/")).$s;
	$protocol = strtolower($protocol);
	$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
	: (":".$_SERVER["SERVER_PORT"]);

	$arrayRequestURI = array();
	if(isset($_POST)){
		foreach($_POST as $key => $value) {
			$arrayRequestURI[] = "$key=" . $value;
		}
	}
	if(isset($_GET)){
		foreach($_GET as $key => $value) {
			$arrayRequestURI[] = "$key=" . $value;
		}
	}
	$requestURI = "";
	if($arrayRequestURI)
	$requestURI =  "?" . implode("&", $arrayRequestURI);

	return urlencode($protocol."://".$_SERVER['HTTP_HOST']. $port . $_SERVER['PHP_SELF'] . $requestURI);
}


//获取客户端IP
function getip() {
	if (isset ( $_SERVER )) {
		if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
			$realip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] )) {
			$realip = $_SERVER ['HTTP_CLIENT_IP'];
		} else {
			$realip = $_SERVER ['REMOTE_ADDR'];
		}
	} else {
		if (getenv ( "HTTP_X_FORWARDED_FOR" )) {
			$realip = getenv ( "HTTP_X_FORWARDED_FOR" );
		} elseif (getenv ( "HTTP_CLIENT_IP" )) {
			$realip = getenv ( "HTTP_CLIENT_IP" );
		} else {
			$realip = getenv ( "REMOTE_ADDR" );
		}
	}
	return $realip;
}
?>