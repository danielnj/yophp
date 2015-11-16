<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name string.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

/**
 * 字符串过滤
 */
function daddslashes($string, $force = 0) {
	if (! MAGIC_QUOTES_GPC || $force) {
		if (is_array ( $string )) {
			foreach ( $string as $key => $val ) {
				$string [$key] = daddslashes ( $val, $force );
			}
		} else {
			$string = addslashes ( $string );
		}
	}
	return $string;
}

/* 随机函数 */
function filter_email($s_email)
{
     return preg_replace('/http:\/\//','',$s_email);
}

/**
* 生成随机不重复字串
* m打头为同m+MD5的字串，长度33,s打头为s+SHA1的字串，长度为41
*/
function getRandomString()
    {
        $computer = $_ENV["COMPUTERNAME"].'/'.$_SERVER["SERVER_ADDR"];
        $long = (rand(0,1)?'-':'').rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100, 999).rand(100, 999);
        $microtime = microtime(true);
        return rand(0,1) ? 's'.sha1($computer.$long.$microtime) : 'm'.md5($computer.$long.$microtime);
    }

/*
 * 产生随机字符串
 */
function randstr($len = 6) {

	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; // characters to build the password from
	mt_srand ( ( double ) microtime () * 1000000 * getmypid () ); // seed the
	$password = '';
	while ( strlen ( $password ) < $len )
	$password .= substr ( $chars, (mt_rand () % strlen ( $chars )), 1 );
	return $password;
}


//检查变量是否存在
function _isset($value) {
	if(is_array($value)){
		return true;
	}else{
		if (isset($value) and trim($value)!="") {
			return true;
		} else {
			return false;
		}
	}
}

//检查是否存在数组的索引
function checkarray($index, $s_array) {

	if (array_key_exists ( $index, $s_array )) {
		return true;
	} else {
		return false;
	}
}

function stripslashes_array(&$array) {
	while ( list ( $k, $v ) = each ( $array ) ) {
		if ($k != 'argc' && $k != 'argv' && (strtoupper ( $k ) != $k ||

		'' . intval ( $k ) == "$k")) {
		if (is_string ( $v )) {
			$array [$k] = stripslashes ( $v );
		}
		if (is_array ( $v )) {
			$array [$k] = stripslashes_array ( $v );
		}
		}
	}
	return $array;

}

//截取字符串
function string_substr($string, $start, $lenght) {
	if (function_exists ( 'mb_substr' )) {
		return mb_substr ( $string, $start, $length );
	}
	preg_match_all ( "/[\\x80-\\xff]?./", $string, $arr );
	return @implode ( array_slice ( $arr [0], $start, $length ), "" );
}

function msubstr($str, $start, $len) {
	$tmpstr = "";
	$strlen = $start + $len;
	for($i = 0; $i < $strlen; $i ++) {
		if (ord ( substr ( $str, $i, 1 ) ) > 0xa0) {
			$tmpstr .= substr ( $str, $i, 2 );
			$i ++;
		} else
		$tmpstr .= substr ( $str, $i, 1 );
	}
	return $tmpstr;
}

//特殊字符替换函数
function dhtmlspecialchars($string) {
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = dhtmlspecialchars ( $val );
		}
	} else {
		$string = str_replace ( '&', '&amp;', $string );
		$string = str_replace ( '"', '&quot;', $string );
		$string = str_replace ( '<', '&lt;', $string );
		$string = str_replace ( '>', '&gt;', $string );
		$string = preg_replace ( '/&amp;(#\d;)/', '&\1', $string );
	}
	return $string;
}


//截取utf8字符串
function utf8Substr($str, $from, $len) {
	return preg_replace ( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' . '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $str );
}


//判断字符串是否存在
function strexists($haystack, $needle) {
	return ! (strpos ( $haystack, $needle ) === FALSE);
}
//获取文件名后缀
function fileext($filename) {
	return strtolower ( trim ( substr ( strrchr ( $filename, '.' ), 1 ) ) );
}
//获取PHP版本
function check_version() {
	if (PHP_VERSION < '4.1.0') {
		$_GET = &$HTTP_GET_VARS;
		$_POST = &$HTTP_POST_VARS;
		$_COOKIE = &$HTTP_COOKIE_VARS;
		$_SERVER = &$HTTP_SERVER_VARS;
		$_ENV = &$HTTP_ENV_VARS;
		$_FILES = &$HTTP_POST_FILES;
	}
}

//字符串编码转换
function siconv($str, $out_charset, $in_charset = '') {
	global $_SGLOBAL;

	$in_charset = empty ( $in_charset ) ? strtoupper ( $_SC ['charset'] ) : strtoupper ( $in_charset );
	$out_charset = strtoupper ( $out_charset );
	if ($in_charset != $out_charset) {
		if (function_exists ( 'iconv' ) && (@$outstr = iconv ( "$in_charset//IGNORE", "$out_charset//IGNORE", $str ))) {
			return $outstr;
		} elseif (function_exists ( 'mb_convert_encoding' ) && (@$outstr = mb_convert_encoding ( $str, $out_charset, $in_charset ))) {
			return $outstr;
		}
	}
	return $str; //转换失败
}

//取消HTML代码
function shtmlspecialchars($string) {
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = shtmlspecialchars ( $val );
		}
	} else {
		$string = preg_replace ( '/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace ( array ('&', '"', '<', '>' ), array ('&amp;', '&quot;', '&lt;', '&gt;' ), $string ) );
	}
	return $string;
}


//字符过滤
function words_filter($string, $obscenities) {
	foreach ( $obscenities as $fuck_word ) {
		if (stristr ( trim ( $string ), $fuck_word )) {
			$length = strlen ( $fuck_word );
			for($i = 1; $i <= $length; $i ++) {
				$stars .= "*";
			}
			$string = eregi_replace ( $fuck_word, $stars, trim ( $string ) );
			$stars = '';
		}
	}
	return $string;
}



//字符串输出
function Output($str) {
	$str=str_replace("'","",$str);
	$str=str_replace('"',"",$str);
	$str=str_replace(" ","",$str);
	$str=str_replace("\n;","",$str);
	$str=str_replace("<","",$str);
	$str=str_replace(">","",$str);
    $str=str_replace("%","",$str);
	$str=str_replace("\t","",$str);
	$str=str_replace("\r","",$str);
	$str=str_replace("/[\s\v]+/"," ",$str);
	return trim($str);
}


//只是允许数字下划线字母
function isvalidate($str){
	if(ereg("^[0-9a-zA-Z\_]*$",$str))
	return true;
	else
	return false;
}

function valid_email($address)
{
	// check an email address is possibly valid
	if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address))
	return true;
	else
	return false;
}

/**
 * 格式化文本数据
 *
 * @access public
 * @param string
 * @return void
 */
function clean_note($text) {
	$text = htmlspecialchars(trim($text));
	/* turn urls into links */
	$text = preg_replace("/((mailto|http|ftp|nntp|news):.+?)
(&gt;|\s|\)|\"|\.\s|$)/","<a href=\"\1\">\1</a>\3",$text);
	/* this 'fixing' code will go away eventually. */
	$fixes = array('<br>', '<p>', '</p>');
	reset($fixes);
	while (list(,$f) = each($fixes)) {
		$text = str_replace(htmlspecialchars($f), $f, $text);
		$text = str_replace(htmlspecialchars(strtoupper($f)), $f,
		$text);
	}
	/* <p> tags make things look awfully weird (breaks things out of
	 the <code>
	 tag). Just convert them to <br>'s
	 */
	$text = str_replace (array ('<P>', '<p>'), '<br>', $text);

	/* Remove </p> tags to prevent it from showing up in the note */
	$text = str_replace (array ('</P>', '</p>'), '', $text);
	/* preserve linebreaks */
	$text = str_replace("\n", "<br>", $text);
	/* this will only break long lines */
	if (function_exists("wordwrap")) {
		$text = wordwrap($text);
	}
	// Preserve spacing of user notes
	$text = str_replace("  ", " &nbsp;", $text);
	return $text;
}
?>