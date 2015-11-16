<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name base.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class Base {

	private static $instance;

	public function __construct()
	{
		self::$instance =& $this;
	}

	public static function &get_instance()
	{
		return self::$instance;
	}

    public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return (isset($this->$name)) ? $this->$name : null;
	}


    public function __call($method, $arguments){

		try {
           throw new YO_exception('Not find action:'.$method);
	    }catch (YO_exception $e){

		 $e->getMessage();
	    }

       }


}
