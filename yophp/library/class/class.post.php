<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name remote post.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
class YO_post {

//远程socket POST提交类


	function __construct() {}

   //remote post data
    public function socket_post($URL,$data){
	$referrer="";
	// parsing the given URL
	$URL_Info=parse_url($URL);

	// Building referrer
	if($referrer==""){ // if not given use this script as referrer
		$referrer=$_SERVER["SCRIPT_URI"];
	}

	// making string from $data
	foreach($data as $key=>$value)
	$values[]="$key=".urlencode($value);

	$data_string=implode("&",$values);
	// Find out which port is needed - if not given use standard (=80)
	if(!isset($URL_Info["port"])){
		$URL_Info["port"]=80;
	}
	// building POST-request:
	$request.="POST ".$URL_Info["path"]." HTTP/1.1\n";
	$request.="Host: ".$URL_Info["host"]."\n";
	$request.="Referer: $referrer\n";
	$request.="Content-type: application/x-www-form-urlencoded\n";
	$request.="Content-length: ".strlen($data_string)."\n";
	$request.="Connection: close\n";
	$request.="\n";
	$request.=$data_string."\n";

	$fp = fsockopen($URL_Info["host"],$URL_Info["port"]);
	if(!$fp){
	exit;
	}
	fputs($fp, $request);
	while(!feof($fp)) {
		$result .= fgets($fp, 128);
	}
	fclose($fp);
	@list($header, $body) = explode("\r\n\r\n", $result, 2);
	unset($res);
	if(empty($header)){
		return '';
	}
	$body = trim( $body );
	if (preg_match('/\r\n[\d]{1,}$/', $body)) {
		$pos = strpos($body, "\r\n");
		$body = substr($body, $pos);
		$rpos = strrpos($body, "\r\n");
		$body = substr($body, 0, $rpos);
		$body =trim($body);
	}
	return $body;
}

}
?>