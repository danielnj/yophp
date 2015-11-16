<?php

/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name model.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

/**
 * 驱动程序
 */
class DB extends Model
{

	/**
	 * 保证对象不被clone
	 */
	private function __clone() {}

    /**
	 * 构造函数
	 */
	public function __construct() {

       $dbtype= 'mysql';
       switch($dbtype){
	   case 'mysql' : $this->connect_mysql();
       case 'mssql' : $this->connect_mssql();
       case 'mysqli' : $this->connect_mysqli();
       case 'odbc' : $this->connect_odbc();
       case 'postgresql' : $this->connect_postgresql();

	   }


	}


    /**
	 * Mysql驱动
	 */
	public function connect_mysql() {

        //连接数据库驱动
		$ini_array = parse_ini_file(S_CONFIG."/database.ini", true);
        import('mysql', S_DRIVER.'/mysql');
		$this->db = new Driver_mysql($ini_array['mysql']);
        $this->orm();
	}


    /**
	 * Mssql驱动
	 */
	public function connect_mssql() {


	}

    /**
	 * Mysqli驱动
	 */
	public function connect_mysqli() {


	}

    /**
	 * odbc驱动
	 */
	public function connect_odbc() {


	}

    /**
	 * Postgresql驱动
	 */
	public function connect_postgresql() {


	}

    /**
	 * ORM驱动
	 */
	public function orm() {

		import('orm', S_DB);
        $this->orm = new DB_ORM();
	}
}