<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.staticpage.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_staticpage {


	var $fileName; //静态页面的文件名
	var $root; //存放静态页面的目录
	/*
                *方法 staticPage
                *功能构造函数
                *参数 $f
                      $r
                *返回无
        */
	function __construct($f, $r = '.') {
		$this->fileName = $f; //实际的文件名
		$this->root = $this->setRoot ( $r ); //文件所在的根目录
	}
	/*  ==========>  设定部分<=============  */
	/*
                *方法        setRoot
                *功能        建立目录
                *参数        $path:目录
                                $mode:格式
                *返回        str
        */
	function setRoot($path, $mode = 0700) {
		//  $path=str_replace("/","\",$path);
		if (! is_dir ( $path )) {
			mkdir ( $path, $mode );
		}

		if (is_dir ( $path )) {
			return $path; //判断是否存在的目录
		} else {
			echo "无法建立目录";
			exit ();
		}

	}

	/*  ==========>取得部分<=============  */

	/*
                *方法        getRoot
                *功能        得到目录
                *参数        $num:从第几个数组开始取
                *返回        str
        */
	function getRoot($num = 0) {
		$dirs = explode ( "/", $this->root ); //将根目录分解
		for($i = $num; $i < count ( $dirs ); $i ++) { //从哪层开始取
			$path .= "/" . $dirs [$i];
		}
		return $path; //返回根目录的路径
	}
	/*
                *方法        getFile
                *功能        得到文件名
                *参数        无
                *返回        str
        */
	function getFile() {
		return $this->fileName; //返回提交的文件名
	}

	/*
                *方法        getFullName
                *功能        得到目录+文件名
                *参数        $num:截取第几个目录
                *返回        str
        */
	function getFullName($num = 0) { //获得完整的路径的文件名如：www.csjauto.com/html/2006-07-12/34223.html参数默认为０
		return $this->getRoot ( $num ) . "/" . $this->fileName;
	}

	/*  ==========>建立部分<=============  */

	/*
                *方法        buildPage
                *功能        生成静态页面
                *参数        无
                *返回        str  :生成的文件地址
        */
	function buildPage($page = '') { //生成静态页面


		$file_name = $this->root . "/" . $this->fileName;
		if (is_file ( $file_name )) {
			@unlink ( $file_name );
		}
		$fp = fopen ( $file_name, "w" );
		if (! is_writable ( $file_name )) {
			return false;
		} else {
			if (! fwrite ( $fp, $page )) {
				return false;
			} else {
				return true;
			}
			fclose ( $fp );
		}

	//ob_end_clean();
	}
}

?>

