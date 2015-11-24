<?php
	require_once 'cache.class.php';
	require_once 'fileCacheDriver.class.php';

	function getPrinters(){
		return 'chenxiang';
	}
//test
	try {

		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());


		$printers=$cache->get('products', 'printers', 5); # get data identified as printers from group products which is not older than 500 seconds

		if($printers===false) { #there is no data in cache
		    echo "无缓存";
			$printers=getPrinters();
			$cache->set('products', 'printers', $printers); #set data identified as printers in group products
		}else{
			echo "有缓存";
		    echo $printers;
		}


		//var_dump($printers);
	}
	catch (CacheException $e){
		echo 'Error: '.$e->getMessage();
	}
?>