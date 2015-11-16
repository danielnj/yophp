<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.upload.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_upload { //文件上传和水印类


	var $cls_upload_dir = ""; //上传的目录
	var $cls_filename = ""; //上传的文件名
	var $cls_tmp_filename = ""; // 临时文件名
	var $cls_max_filesize = 100000; // 文件上传限制.
	var $cls_filesize = ""; // 文件的格式
	var $cls_arr_ext_accepted = array (
		//允许上传格式
	".gif", '.rar', '.zip', '.jpg', '.png' );
	var $cls_file_exists = 0; // Set to 1 to check if file exist before upload.
	var $cls_rename_file = 0; // Set to 1 to rename file after upload.
	var $cls_file_rename_to = ''; // New name for the file after upload.
	var $cls_verbal = 0; // Set to 1 to return an a string instead of an error code.
	/**********************************************************************************************/
	var $watermark = 1; //是否附加水印(1为加水印,其他为不加水印);
	var $watertype = ''; //水印类型(1为文字,2为图片)
	var $waterposition = 1; //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
	var $waterstring = ''; //水印字符串
	var $waterimg = ''; //水印图片
	var $imgpreview = ''; //是否生成预览图(1为生成,其他为不生成);
	var $imgpreviewsize = ''; //缩略图比例
	var $image_size = ''; //图片的大小
	var $pinfo = ''; //图片的绝对路径
	var $ftype = ''; //图片的格式
	var $destination = '';

	function __construct($file_name, $tmp_file_name, $file_size, $file_rename_to = '') {

		$this->cls_filename = $file_name; //上传文件名
		$this->cls_tmp_filename = $tmp_file_name; //临时文件名
		$this->cls_filesize = $file_size; //文件的大小
		$this->cls_file_rename_to = $file_rename_to; //重新的命名
	}

	function water($type = 1, $position = 1, $string = '', $img = '', $imgpreview = 1, $previewsize = '1/2') {
		$this->$watertype = $type; //水印类型(1为文字,2为图片)
		$this->$waterposition = $position; //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
		$this->$waterstring = $string; //水印字符串
		$this->$waterimg = $img; //水印图片
		$this->$imgpreview = $preview; //是否生成预览图(1为生成,其他为不生成);
		$this->$imgpreviewsize = $previewsize; //缩略图比例


	}

	function isUploadedFile() { //上传文件不存在


		if (is_uploaded_file ( $this->cls_tmp_filename ) != true) {
			return "Dir Error";
		} else {
			return 1;
		}
	}

	function checkExtension() { //判断上传格式


		// Check if the extension is valid


		if (! in_array ( strtolower ( strrchr ( $this->cls_filename, "." ) ), $this->cls_arr_ext_accepted )) {
			return "File Format Error"; //文件格式错误
		} else {
			return 1;
		}
	}

	function checkSize() { //判断文件的大小

		if ($this->cls_filesize > $this->cls_max_filesize) {
			return "Too Big"; //文件过大
		} else {
			return 1;
		}
	}

	function setDir($dir) { //设置上传目录


		if (! is_writable ( $dir )) {
			return "make dir error";
		} else {
			$this->cls_upload_dir = $dir;
			return 1;
		}
	}

	function renameFile() { //重新命名文件名
		if ($this->cls_file_rename_to == '') {
			$allchar = "abcdefghijklnmopqrstuvwxyz";
			$this->cls_file_rename_to = "";
			mt_srand ( ( double ) microtime () * 1000000 );
			for($i = 0; $i < 8; $i ++) {
				$this->cls_file_rename_to .= substr ( $allchar, mt_rand ( 0, 25 ), 1 );
			}
		}
		$extension = strrchr ( $this->cls_filename, "." );
		$this->cls_file_rename_to .= $extension;

		if (! rename ( $this->cls_upload_dir . $this->cls_filename, $this->cls_upload_dir . $this->cls_file_rename_to )) {
			return "RENAME_FAILURE"; //重命名失败
		} else {
			return 1;
		}
	}

	function move() { //上传文件


		if (move_uploaded_file ( $this->cls_tmp_filename, $this->cls_upload_dir . $this->cls_filename ) == false) {
			return "MOVE_UPLOADED_FILE_FAILURE"; //返回上传失败
		} else {
			return 1;
		}
	}

	function Get_parameter() {
		$this->$image_size = getimagesize ( $this->cls_tmp_filename );
		$this->$pinfo = pathinfo ( $this->cls_filename );
		$this->$ftype = $pinfo ['extension'];
		$this->$destination = $this->cls_upload_dir . $this->cls_filename;
	}
	function water_outer() {

		if ($this->$watermark == 1) {
			$iinfo = getimagesize ( $this->$destination, $iinfo );
			$nimage = imagecreatetruecolor ( $image_size [0], $image_size [1] );
			$white = imagecolorallocate ( $nimage, 255, 255, 255 );
			$black = imagecolorallocate ( $nimage, 0, 0, 0 );
			$red = imagecolorallocate ( $nimage, 255, 0, 0 );
			imagefill ( $nimage, 0, 0, $white );
			switch ($iinfo [2]) {
				case 1 :
					$simage = imagecreatefromgif ( $this->$destination );
					break;
				case 2 :
					$simage = imagecreatefromjpeg ( $this->$destination );
					break;
				case 3 :
					$simage = imagecreatefrompng ( $this->$destination );
					break;
				case 6 :
					$simage = imagecreatefromwbmp ( $this->$destination );
					break;
				default :
					die ( "file type error" );
					exit ();
			}

			imagecopy ( $nimage, $simage, 0, 0, 0, 0, $image_size [0], $image_size [1] );

			imagefilledrectangle ( $nimage, 1, $image_size [1] - 15, 80, $image_size [1], $white );

			switch ($this->$watertype) {
				case 1 : //加水印字符串
					imagestring ( $nimage, 2, 3, $image_size [1] - 15, $this->$waterstring, $black );
					break;
				case 2 : //加水印图片
					$simage1 = imagecreatefromgif ( $this->$waterimg );
					imagecopy ( $nimage, $simage1, 0, 0, 0, 0, 85, 15 );
					imagedestroy ( $simage1 );
					break;
			}

			switch ($iinfo [2]) {
				case 1 :
					//imagegif($nimage, $destination);
					imagejpeg ( $nimage, $this->$destination );
					break;
				case 2 :
					imagejpeg ( $nimage, $this->$destination );
					break;
				case 3 :
					imagepng ( $nimage, $this->$destination );
					break;
				case 6 :
					imagewbmp ( $nimage, $this->$destination );
					//imagejpeg($nimage, $destination);
					break;
			}

			//覆盖原上传文件
			imagedestroy ( $nimage );
			imagedestroy ( $simage );
		}
	}

	function pre_view() {
		if ($this->$imgpreview == 1) {
			echo "<br>Preview:<br>";
			echo "<img src=\"" . $this->$destination . "\" width=" . ($image_size [0] * $this->$imgpreviewsize) . " height=" . ($image_size [1] * $this->$imgpreviewsize);
			echo " alt=\"Preview:\rFile name:" . $this->$destination . "\r Upload Time:\">";
		}
	}

}

?>
