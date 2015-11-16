<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.guid.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_guid
{

//UUID 产生器
function CurrentTimeMillis() {
		list($usec, $sec) = explode(" ",microtime());
		return $sec . substr($usec, 2, 3);
	}

function Getguid() {
		$netAddr = strtolower( $_SERVER['HTTP_USER_AGENT'] . '/' . $_SERVER["SERVER_ADDR"] );
		$nextLong = ( rand(0,1)?'-':'' ) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(100, 999) . rand(100, 999);
		$valueBeforeMD5 = $netAddr . ':' . CurrentTimeMillis() . ':' . $nextLong;
		$valueAfterMD5 = md5($valueBeforeMD5);
		$raw = strtoupper($valueAfterMD5);
		return substr($raw,0,8).'-'.substr($raw,8,4).'-'.substr($raw,12,4).'-'.substr($raw,16,4).'-'.substr($raw,20);
	}

}
?>