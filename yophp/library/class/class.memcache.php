<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name memcache.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_memcache
{


	var $serverip ;
    var $ports ;
	var $enable;
	var $obj;

	public function __construct($server , $port){
		$this->serverip = $server;
        $this->ports = $port;
	}

	function init() {
	  global $_SGLOBAL, $_SCONFIG;
			$this->obj = new Memcache;
			if($_SCONFIG['memcache']['pconnect']) {
				$connect = @$this->obj->pconnect($this->serverip, $this->ports);
			} else {
				$connect = @$this->obj->connect($this->serverip, $this->ports);
			}
			$this->enable = $connect ? true : false;
	}
   //获得运行状态
	function getstats() {
		return $this->obj->getStats();
	}
    //获得版本
	function getversion() {
		return $this->obj->getVersion();
	}
    //获得数据
	function get($key) {
		return $this->obj->get($key);
	}
    //存入数据
	function set($key, $value, $ttl = 0) {
		return $this->obj->set($key, $value, MEMCACHE_COMPRESSED, $ttl);
	}
	//替换数据
	function replace($key , $value ,$ttl =0) {
		return $this->obj->replace($key, $value, MEMCACHE_COMPRESSED, $ttl);
	}
    //删除数据
	function rm($key) {
		return $this->obj->delete($key);
	}

}

?>