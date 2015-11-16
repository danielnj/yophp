<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name encrpt.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
class YO_encrypt {

//----------------------------------------------------------
/**
* 构造控制器
*/
public function __construct(){}


public function encrypt($s, $key='key')
{
	$r="";
	for($i=0;$i<strlen($s);$i++){
		$r .= substr(str_shuffle(md5($key)),($i % strlen(md5($key))),1).$s[$i];
	}
	for($i=1;$i<=strlen($r);$i++) {
		$s[$i-1] = chr(ord($r[$i-1])+ord(substr(md5($key),($i % strlen(md5($key)))-1,1)));
	}
	return urlencode(base64_encode($s));
}

public function decrypt($s, $key='key')
{
	$r ='';
	$s=base64_decode(urldecode($s));
	for($i=1;$i<=strlen($s);$i++){
		$s[$i-1] = chr(ord($s[$i-1])-ord(substr(md5($key),($i % strlen(md5($key)))-1,1)));
	}
	for($i=1;$i<=strlen($s)-1;$i=$i+2){
		$r .= $s[$i];
	}
	return $r;
}


//字符串解密加密base64
public function authcode($string, $operation = 'DECODE') {

	$str = $operation == 'DECODE' ? base64_decode ( $string ) : base64_encode ( $string );
	return $str;
}


// $expiry：密文有效期
public function yophp_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 12; //note 随机密钥长度 取值 0-32;
	$key = md5 ( $key ? $key : '' );
	$keya = md5 ( substr ( $key, 0, 16 ) );
	$keyb = md5 ( substr ( $key, 16, 16 ) );
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';

	$cryptkey = $keya . md5 ( $keya . $keyc );
	$key_length = strlen ( $cryptkey );

	$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
	$string_length = strlen ( $string );

	$result = '';
	$box = range ( 0, 255 );

	$rndkey = array ();
	for($i = 0; $i <= 255; $i ++) {
		$rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
	}

	for($j = $i = 0; $i < 256; $i ++) {
		$j = ($j + $box [$i] + $rndkey [$i]) % 256;
		$tmp = $box [$i];
		$box [$i] = $box [$j];
		$box [$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i ++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box [$a]) % 256;
		$tmp = $box [$a];
		$box [$a] = $box [$j];
		$box [$j] = $tmp;
		$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
	}

	if ($operation == 'DECODE') {
		if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
			return substr ( $result, 26 );
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
	}
}


public function hmac($key, $data, $hash="md5") {
	// RFC 2104 HMAC implementation for php.
	$b = 64;
	if (strlen($key) > $b)
	$key = pack("H*", call_user_func($hash, $key));
	$key = str_pad($key, $b, chr(0x00));
	$ipad = str_pad("", $b, chr(0x36));
	$opad = str_pad("", $b, chr(0x5c));
	$k_ipad = $key ^ $ipad ;
	$k_opad = $key ^ $opad;

	return call_user_func($hash, $k_opad . pack("H*", call_user_func($hash, $k_ipad . $data)));
}

}
?>