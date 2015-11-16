<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.Exception.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_exception extends Exception {

	private $_appMessage;

   // 构造函数
	public function __construct($msg) {
     $this->appMessage($msg);
     $this-> display( $this-> toString());

	}

	public function appMessage($message) {
		$this->_appMessage [] = $message;
	}

	/**
	 * @return String
	 */
	public function toString() {
		if (is_array ( $this->_appMessage )) {
			$str = implode ( "\n", $this->_appMessage ) . "\n";
		} else {
			$str = $this->_appMessage;
		}
		$str .= parent::__toString ();

		return $str;
	}

	public function display($message = null) {
		if (is_null ( $message )) {
			$message = $this->getMessage ();
		}

		echo '<div style="background-color: #EAEAEA; font-family: Courier New; font-size: 10pt; padding: 4px">';
		echo nl2br ( $this->getFile () . ' (' . $this->getLine () . '): ' . $message );
		echo '</div>';
	}
}
?>