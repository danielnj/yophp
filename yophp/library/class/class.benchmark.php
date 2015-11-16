<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.benchmark.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */


//基准测试
class YO_benchmark {

	public $marker = array ();

	// --------------------------------------------------------------------


	function mark($name) {
		$this->marker [$name] = microtime ();
	}

	// --------------------------------------------------------------------


	function elapsed_time($point1 = '', $point2 = '', $decimals = 10) {
		if ($point1 == '') {
			return '{elapsed_time}';
		}

		if (! isset ( $this->marker [$point1] )) {
			return '';
		}

		if (! isset ( $this->marker [$point2] )) {
			$this->marker [$point2] = microtime ();
		}

		list ( $sm, $ss ) = explode ( ' ', $this->marker [$point1] );
		list ( $em, $es ) = explode ( ' ', $this->marker [$point2] );

		return number_format ( ($em + $es) - ($sm + $ss), $decimals );
	}

	function memory_usage() {
		return memory_get_usage ();
	}

}

