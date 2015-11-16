<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.dir.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
class YO_file {

	// 遍历的目录数组
	var $mFolders = Array ();
	// 遍历的文件数组
	var $mFiles = Array ();
	var $mDateTime = "Y-m-d H-i-s";
	var $mTimeOffset = 8;
	var $aa = 0;

	function mkDirs($dir) {
		$dir = str_replace ( "\\", "/", $dir );
		$dirs = explode ( '/', $dir );
		$total = count ( $dirs );
		$temp = '';
		for($i = 0; $i < $total; $i ++) {
			$temp .= $dirs [$i] . '/';
			if (! is_dir ( $temp )) {
				if (! @mkdir ( $temp ))
					exit ( "不能建立目录 $temp" );
					// 改变目录权限为0777
				@chmod ( $temp, 0777 );
			}
		}
	}

	function rmDirs($dir, $rmself = true) {
		//如果给定路径末尾包含"/",先将其删除
		if (substr ( $dir, - 1 ) == "/") {
			$dir = substr ( $dir, 0, - 1 );
		}
		//如给出的目录不存在或者不是一个有效的目录，则返回
		if (! file_exists ( $dir ) || ! is_dir ( $dir )) {
			return false;
			//如果目录不可读，则返回
		} elseif (! is_readable ( $dir )) {
			return false;
		} else {
			//打开目录，
			$dirs = opendir ( $dir );
			//当目录不空时，删除目录里的文件
			while ( false !== ($entry = readdir ( $dirs )) ) {
				//过滤掉表示当前目录的"."和表示父目录的".."
				if ($entry != "." && $entry != "..") {
					$path = $dir . "/" . $entry;
					//为子目录，则递归调用本函数
					if (is_dir ( $path )) {
						$this->rmDirs ( $path );
						//为文件直接删除
					} else {
						unlink ( $path );
					}
				}
			}
			//关闭目录
			closedir ( $dirs );
			//当$rmself==false时,只清空目录里的文件及目录,$rmself=true时,也删除$dir目录
			if ($rmself) {
				//删除目录
				if (! rmdir ( $dir )) {
					return false;
				}
				return true;
			}
		}
	}

	function delFile($file) {
		if (! is_file ( $file ))
			return false;
		@unlink ( $file );
		return true;
	}
	function renameFile($file, $newfile) {
		if (! is_file ( $file ))
			return false;
		rename ( $file, $newfile );
		return true;
	}

//作用:检测指定目录大小
//参数:$path 要检测的目录
//返回:返回文件夹所占用的空间的字节数
function dir_size($path)
{
	static $dir_size;
	$list_dir = list_dir($path);

	if(is_array($list_dir))
	{
		foreach($list_dir as $row)
		{
			if($row['type']=='dir')
			{
				dir_size($row['path']);
			}
			else
			{
				$dir_size += filesize($row['path']);
			}
		}
	}

	return $dir_size;
}


	function createFile($file, $content = "", $mode = "w") {
		if (in_array ( $mode, array ("w", "a" ) ))
			$mode = "w";
		if (! $hd = fopen ( $file, $mode ))
			return false;
		if (! false === fwrite ( $hd, $content ))
			return false;
		return true;
	}

	function getFolders($dir) {
		$this->mFolders = Array ();
		//如果给定路径末尾包含"/",先将其删除
		if (substr ( $dir, - 1 ) == "/") {
			$dir = substr ( $dir, 0, - 1 );
		}
		//如给出的目录不存在或者不是一个有效的目录，则返回
		if (! file_exists ( $dir ) || ! is_dir ( $dir )) {
			return false;
		}
		//打开目录，
		$dirs = opendir ( $dir );
		//把目录下的目录信息写入数组
		$i = 0;
		while ( false !== ($entry = readdir ( $dirs )) ) {
			//过滤掉表示当前目录的"."和表示父目录的".."
			if ($entry != "." && $entry != "..") {
				$path = $dir . "/" . $entry;
				//为子目录，则采集信息
				if (is_dir ( $path )) {
					$filetime = @filemtime ( $path );
					$filetime = @date ( $this->mDateTime, $filetime + 3600 * $this->mTimeOffset );
					// 目录名
					$this->mFolders [$i] ['name'] = $entry;
					// 目录最后修改时间
					$this->mFolders [$i] ['filetime'] = $filetime;
					// 目录大小,不计,设为0
					$this->mFolders [$i] ['filesize'] = 0;
					$i ++;
				}
			}
		}
		return $this->mFolders;
	}

	function getFiles($dir) {
		$this->mFiles = Array ();
		//如果给定路径末尾包含"/",先将其删除
		if (substr ( $dir, - 1 ) == "/") {
			$dir = substr ( $dir, 0, - 1 );
		}
		//如给出的目录不存在或者不是一个有效的目录，则返回
		if (! file_exists ( $dir ) || ! is_dir ( $dir )) {
			return false;
		}
		//打开目录，
		$dirs = opendir ( $dir );
		//把目录下的文件信息写入数组
		$i = 0;
		while ( false !== ($entry = readdir ( $dirs )) ) {
			//过滤掉表示当前目录的"."和表示父目录的".."
			if ($entry != "." && $entry != "..") {
				$path = $dir . "/" . $entry;
				//为子目录，则采集信息
				if (is_file ( $path )) {
					$filetime = @filemtime ( $path );
					$filetime = @date ( $this->mDateTime, $filetime + 3600 * $this->mTimeOffset );
					$filesize = $this->getFileSize ( $path );
					// 文件名
					$this->mFiles [$i] ['name'] = $entry;
					// 文件最后修改时间
					$this->mFiles [$i] ['filetime'] = $filetime;
					// 文件的大小
					$this->mFiles [$i] ['filesize'] = $filesize;
					$i ++;
				}
			}
		}
		return $this->mFiles;
	}

	function getFileSize($file) {
		if (! is_file ( $file ))
			return 0;
		$f1 = $f2 = "";
		$filesize = @filesize ( "$file" );
		// 大于1GB以上的文件
		if ($filesize > 1073741824) {
			// 大于1MB以上的文件
		} elseif ($filesize > 1048576) {
			$filesize = $filesize / 1048576;
			list ( $f1, $f2 ) = explode ( ".", $filesize );
			$filesize = $f1 . "." . substr ( $f2, 0, 2 ) . "MB";
			// 大于1KB小于1MB的文件
		} elseif ($filesize > 1024) {
			$filesize = $filesize / 1024;
			list ( $f1, $f2 ) = explode ( ".", $filesize );
			$filesize = $f1 . "." . substr ( $f2, 0, 2 ) . "KB";
			// 小于1KB的文件
		} else {
			$filesize = $filesize . "字节";
		}
		return $filesize;
	}

	function getFolderSize($dir) {

		if ($handle = opendir ( $dir )) {
			while ( false !== ($file = readdir ( $handle )) ) {
				if ($file != "." && $file != "..") {
					if (is_dir ( $dir . "/" . $file )) {
						$this->getFolderSize ( $dir . "/" . $file );
					} else {
						$this->aa += filesize ( $dir . "/" . $file );

					}
				}
			}

		}
		return $this->aa;

	}


//作用:复制文件或目录下的所有文件到指定目录
function icopy($path, $dir)
{
	if(!file_exists($path))
	{
		return false;
	}

	$tmpPath = parse_path($path);


	if(!is_dir($path))
	{
		create_dir($dir);
		if(!copy($path, $dir.'/'.$tmpPath['filename']))
		{
			return false;
		}
	}
	else
	{
		create_dir($dir);
		foreach((array)list_dir($path) as $lineArray)
		{
			if($lineArray['type'] == 'dir')
			{
				icopy($lineArray['path'], $dir.'/'.$lineArray['filename']);
			}
			else
			{
				icopy($lineArray['path'], $dir);
			}
		}
	}

	return true;
}

//获取目录
function sreaddir($dir, $extarr = array()) {
	$dirs = array ();
	if ($dh = opendir ( $dir )) {
		while ( ($file = readdir ( $dh )) !== false ) {
			if (! empty ( $extarr ) && is_array ( $extarr )) {
				if (in_array ( strtolower ( fileext ( $file ) ), $extarr )) {
					$dirs [] = $file;
				}
			} else if ($file != '.' && $file != '..') {
				$dirs [] = $file;
			}
		}
		closedir ( $dh );
	}
	return $dirs;
}

//获取文件内容
function sreadfile($filename) {
	$content = '';
	if (function_exists ( 'file_get_contents' )) {
		@$content = file_get_contents ( $filename );
	} else {
		if (@$fp = fopen ( $filename, 'r' )) {
			@$content = fread ( $fp, filesize ( $filename ) );
			@fclose ( $fp );
		}
	}
	return $content;
}

//写入文件
function swritefile($filename, $writetext, $openmod = 'w') {
	if (@$fp = fopen ( $filename, $openmod )) {
		flock ( $fp, LOCK_EX );
		fwrite ( $fp, $writetext );
		fclose ( $fp );
		return true;
	} else {
		return false;
	}
}

//文件权限检查
function file_info($file_path) {
		/* 如果不存在，则不可能读、不可能写、不可能改 */
		if (! file_exists ( $file_path )) {
			return false;
		}
		$mark = 0;
		if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) == 'WIN') {
			/* 测试文件 */
			$test_file = $file_path . '/cf_test.txt'; /* 如果shi目录 */
			if (is_dir ( $file_path )) {
				/* 检查目录shi否可能读 */
				$dir = @opendir ( $file_path );
				if ($dir === false) {
					return $mark; //如果目录打开失败，直接返回目录不可能修改、不可能写、不可能读
				}
				if (@readdir ( $dir ) !== false) {
					$mark ^= 1; //目录可能读 001，目录不可能读 000
				}
				@closedir ( $dir ); /* 检查目录shi否可能写 */
				$fp = @fopen ( $test_file, 'wb' );
				if ($fp === false) {
					return $mark; //如果目录中的文件创建失败，返回不可能写。
				}
				if (@fwrite ( $fp, 'directory access testing.' ) !== false) {
					$mark ^= 2; //目录可能写可能读011，目录可能写不可能读 010
				}
				@fclose ( $fp );
				@unlink ( $test_file ); /* 检查目录shi否可能修改 */
				$fp = @fopen ( $test_file, 'ab+' );
				if ($fp === false) {
					return $mark;
				}
				if (@fwrite ( $fp, "modify test.\r\n" ) !== false) {
					$mark ^= 4;
				}
				@fclose ( $fp ); /* 检查目录下shi否有执行rename()函数的权限 */
				if (@rename ( $test_file, $test_file ) !== false) {
					$mark ^= 8;
				}
				@unlink ( $test_file );
			} /* 如果shi文件 */
			elseif (is_file ( $file_path )) {
				/* 以读方式打开 */
				$fp = @fopen ( $file_path, 'rb' );
				if ($fp) {
					$mark ^= 1; //可能读 001
				}
				@fclose ( $fp ); /* 试着修改文件 */
				$fp = @fopen ( $file_path, 'ab+' );
				if ($fp && @fwrite ( $fp, '' ) !== false) {
					$mark ^= 6; //可能修改可能写可能读 111，不可能修改可能写可能读011...
				}
				@fclose ( $fp ); /* 检查目录下shi否有执行rename()函数的权限 */
				if (@rename ( $test_file, $test_file ) !== false) {
					$mark ^= 8;
				}
			}
		} else {
			if (@is_readable ( $file_path )) {
				$mark ^= 1;
			}
			if (@is_writable ( $file_path )) {
				$mark ^= 14;
			}
		}
		return $mark;
	}


}
?>
