<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name date.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_datetime {

//----------------------------------------------------------
/**
* 构造控制器
*/
public function __construct(){}

//重新格式化日期
function format_date($date) {
	if (! preg_match ( '/^\d+$/', $date ))
		$date = strtotime ( trim ( $date ) );
	$sec = time () - $date;

	//Sec 1 day is 86400
	if ($sec < 86400) {
		return round ( $sec / 3600 ) . ' hours ago';
	} elseif ($sec < (86400 * 7)) {
		return round ( $sec / 86400 ) . ' days ago';
	} elseif ($sec < (86400 * 7 * 4)) {
		return round ( $sec / (86400 * 7) ) . ' weeks ago';
	} else {
		return date ( 'Y-m-d', $date );
	}
}
//标准时间转化为UNX时间戳
function GetUnixTime($dtime) {
	if (! ereg ( "[^0-9]", $dtime ))
		return $dtime;
	$dt = Array (1970, 1, 1, 0, 0, 0 );
	$dtime = ereg_replace ( "[\r\n\t]|日|秒", " ", $dtime );
	$dtime = str_replace ( "年", "-", $dtime );
	$dtime = str_replace ( "月", "-", $dtime );
	$dtime = str_replace ( "时", ":", $dtime );
	$dtime = str_replace ( "分", ":", $dtime );
	$dtime = trim ( ereg_replace ( "[ ]{1,}", " ", $dtime ) );
	$ds = explode ( " ", $dtime );
	$ymd = explode ( "-", $ds [0] );
	if (isset ( $ymd [0] ))
		$dt [0] = $ymd [0];
	if (isset ( $ymd [1] ))
		$dt [1] = $ymd [1];
	if (isset ( $ymd [2] ))
		$dt [2] = $ymd [2];
	if (strlen ( $dt [0] ) == 2)
		$dt [0] = '20' . $dt [0];
	if (isset ( $ds [1] )) {
		$hms = explode ( ":", $ds [1] );
		if (isset ( $hms [0] ))
			$dt [3] = $hms [0];
		if (isset ( $hms [1] ))
			$dt [4] = $hms [1];
		if (isset ( $hms [2] ))
			$dt [5] = $hms [2];
	}
	foreach ( $dt as $k => $v ) {
		$v = ereg_replace ( "^0{1,}", "", trim ( $v ) );
		if ($v == "")
			$dt [$k] = 0;
	}
	$mt = mktime ( $dt [3], $dt [4], $dt [5], $dt [1], $dt [2], $dt [0] );
	return $mt;

}

//日期格式转换
function _mktimeday($sDate, $month_ = 0, $day_ = 0, $year = 0) {
	if (empty ( $year_ )) {
		$year_ = 0;
	}
	$year = substr ( $sDate, 0, 4 );
	$month = substr ( $sDate, 5, 2 );
	$day = substr ( $sDate, 8, 2 );
	$hour = substr ( $sDate, 11, 2 );
	$minute = substr ( $sDate, 14, 2 );
	$second = substr ( $sDate, 17, 2 );
	return date ( "Y-m-d", mktime ( $hour, $minute, $second, $month + $month_, $day + $day_, $year + $year_ ) );
}


//格式化日期
//date('Y/m/d',mkDate($v));
function mkDate($v) {
	do {
		if (empty ( $v ))
			break;

		$ymdhis = split ( ' ', trim ( $v ) );

		if (count ( $ymdhis ) < 1)
			break;

		$ymd = split ( '-', $ymdhis [0] );

		$his = split ( ':', $ymdhis [1] );

		return mktime ( $his [0], $his [1], $his [2], $ymd [1], $ymd [2], $ymd [0] );
	} while ( false );

	return time ();
}


function secondmtime($time) {

	$v = floor ( $time / 3600 ) . "小时:" . floor ( ($time % 3600) / 60 ) . "分:" . ($time % 60) . "秒";

	return $v;
}
//返回时间相差的准确时间
// 时间格式为YYYYMMDDHHmmss
function timeDiff($aTime, $bTime) {
	$timeDiff = $aTime - $bTime;
	// 采用了四舍五入,可以修改
	return $timeDiff;
}

function _mktime($sDate, $month_ = 0, $day_ = 0, $year = 0) {
	if (empty ( $year_ )) {
		$year_ = 0;
	}
	$year = substr ( $sDate, 0, 4 );
	$month = substr ( $sDate, 5, 2 );
	$day = substr ( $sDate, 8, 2 );
	$hour = substr ( $sDate, 11, 2 );
	$minute = substr ( $sDate, 14, 2 );
	$second = substr ( $sDate, 17, 2 );
	return date ( "Y-m-d H:i:s", mktime ( $hour, $minute, $second, $month + $month_, $day + $day_, $year + $year_ ) );
}

//时间格式化
function sgmdate($dateformat, $timestamp = '', $format = 0) {
	global $_SCONFIG, $_SGLOBAL;
	if (empty ( $timestamp )) {
		$timestamp = $_SGLOBAL ['timestamp'];
	}
	$result = '';
	if ($format) {
		$time = $_SGLOBAL ['timestamp'] - $timestamp;
		if ($time > 24 * 3600) {
			$result = gmdate ( $dateformat, $timestamp + $_SCONFIG ['timeoffset'] * 3600 );
		} elseif ($time > 3600) {
			$result = intval ( $time / 3600 ) . lang ( 'hour' ) . lang ( 'before' );
		} elseif ($time > 60) {
			$result = intval ( $time / 60 ) . lang ( 'minute' ) . lang ( 'before' );
		} elseif ($time > 0) {
			$result = $time . lang ( 'second' ) . lang ( 'before' );
		} else {
			$result = lang ( 'now' );
		}
	} else {
		$result = gmdate ( $dateformat, $timestamp + $_SCONFIG ['timeoffset'] * 3600 );
	}
	return $result;
}

//字符串时间化
function sstrtotime($string) {
	global $_SGLOBAL, $_SCONFIG;
	$time = '';
	if ($string) {
		$time = strtotime ( $string );
		if (sgmdate ( 'H:i' ) != date ( 'H:i' )) {
			$time = $time - $_SCONFIG ['timeoffset'] * 3600;
		}
	}
	return $time;
}
// make day format
function mkday( $month_ = 0, $day_ = 0, $year_ = 0) {
	if($year_/1000 <= 1){
	return false;
	}elseif($month_/100 >= 1 or $month_ > 12){
	return false;
	}elseif($day_/100 >= 1 or $day_ > 31){
	return false;
	}else{
	$day_ = sprintf("%02d", $day_);
	$month_ = sprintf("%02d", $month_);
	$f_date = $year_ .'-'. $month_ .'-'. $day_ ;
	return $f_date;
	}

}

  function isDate($str,$format="Y-m-d"){
        $unixTime=strtotime($str);
        $checkDate= date($format,$unixTime);
        if($checkDate==$str)
            return true;
        else
            return false;
    }

function second_convert($s){
	if($s<0) return $s.'s';
	$hour = floor($s/3600);
	$minute = floor(($s-$hour*3600)/60);
	$second = $s-$hour*3600-$minute*60;
	return $hour.'h '.$minute.'m '.$second.'s ';
}

}
?>