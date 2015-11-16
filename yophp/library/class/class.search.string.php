<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name search.string.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
//字符串替换类
class YO_search.string{


	var $find;
	var $replace;
	var $files;
	var $directories;
	var $include_subdir;
	var $ignore_lines;
	var $ignore_sep;
	var $occurences;
	var $search_function;
	var $last_error;
	function search_replace($find, $replace, $files, $directories = '', $include_subdir = 1, $ignore_lines = array()){
		$this->find            = $find;
		$this->replace         = $replace;
		$this->files           = $files;
		$this->directories     = $directories;
		$this->include_subdir  = $include_subdir;
		$this->ignore_lines    = $ignore_lines;
		$this->occurences      = 0;
		$this->search_function = 'search';
		$this->last_error      = '';
	}
}
function get_last_error(){
	return $this->last_error;
}
function set_find($find){
	$this->find = $find;
}
function set_replace($replace){
	$this->replace = $replace;
}
function set_files($files){
	$this->files = $files;
}
function set_directories($directories){
	$this->directories = $directories;
}
function set_include_subdir($include_subdir){
	$this->include_subdir = $include_subdir;
}
function set_ignore_lines($ignore_lines){
	$this->ignore_lines = $ignore_lines;
}
function set_search_function($search_function){
	switch($search_function){
		case 'normal': $this->search_function = 'search';
		return TRUE;
		break;

		case 'quick' : $this->search_function = 'quick_search';
		return TRUE;
		break;

		case 'preg'  : $this->search_function = 'preg_search';
		return TRUE;
		break;

		case 'ereg'  : $this->search_function = 'ereg_search';
		return TRUE;
		break;

		default      : $this->last_error      = 'Invalid search function specified';
		return FALSE;
		break;
	}
}
function search($filename){

	$occurences = 0;
	$file_array = file($filename);

	for($i=0; $i<count($file_array); $i++){
		$continue_flag = 0;
		if(count($this->ignore_lines) > 0){
			for($j=0; $j<count($this->ignore_lines); $j++){
				if(substr($file_array[$i],0,strlen($this->ignore_lines[$j])) == $this->ignore_lines[$j]) $continue_flag = 1;
			}
		}
		if($continue_flag == 1) continue;
		$occurences += count(explode($this->find, $file_array[$i])) - 1;
		$file_array[$i] = str_replace($this->find, $this->replace, $file_array[$i]);
	}
	if($occurences > 0) $return = array($occurences, implode('', $file_array)); else $return = FALSE;
	return $return;

}
function quick_search($filename){

	clearstatcache();

	$file       = fread($fp = fopen($filename, 'r'), filesize($filename)); fclose($fp);
	$occurences = count(explode($this->find, $file)) - 1;
	$file       = str_replace($this->find, $this->replace, $file);

	if($occurences > 0) $return = array($occurences, $file); else $return = FALSE;
	return $return;

}
function preg_search($filename){

	clearstatcache();

	$file       = fread($fp = fopen($filename, 'r'), filesize($filename)); fclose($fp);
	$occurences = count($matches = preg_split($this->find, $file)) - 1;
	$file       = preg_replace($this->find, $this->replace, $file);

	if($occurences > 0) $return = array($occurences, $file); else $return = FALSE;
	return $return;

}
function ereg_search($filename){

	clearstatcache();

	$file = fread($fp = fopen($filename, 'r'), filesize($filename)); fclose($fp);

	$occurences = count($matches = split($this->find, $file)) -1;
	$file       = ereg_replace($this->find, $this->replace, $file);

	if($occurences > 0) $return = array($occurences, $file); else $return = FALSE;
	return $return;

}
function writeout($filename, $contents){

	if($fp = @fopen($filename, 'w')){
		fwrite($fp, $contents);
		fclose($fp);
	}else{
		$this->last_error = 'Could not open file: '.$filename;
	}

}
function do_files($ser_func){
	if(!is_array($this->files)) $this->files = explode(',', $this->files);
	for($i=0; $i<count($this->files); $i++){
		if($this->files[$i] == '.' OR $this->files[$i] == '..') continue;
		if(is_dir($this->files[$i]) == TRUE) continue;
		$newfile = $this->$ser_func($this->files[$i]);
		if(is_array($newfile) == TRUE){
			$this->writeout($this->files[$i], $newfile[1]);
			$this->occurences += $newfile[0];
		}
	}
}
function do_directories($ser_func){
	if(!is_array($this->directories)) $this->directories = explode(',', $this->directories);
	for($i=0; $i<count($this->directories); $i++){
		$dh = opendir($this->directories[$i]);
		while($file = readdir($dh)){
			if($file == '.' OR $file == '..') continue;

			if(is_dir($this->directories[$i].$file) == TRUE){
				if($this->include_subdir == 1){
					$this->directories[] = $this->directories[$i].$file.'/';
					continue;
				}else{
					continue;
				}
			}

			$newfile = $this->$ser_func($this->directories[$i].$file);
			if(is_array($newfile) == TRUE){
				$this->writeout($this->directories[$i].$file, $newfile[1]);
				$this->occurences += $newfile[0];
			}
		}
	}
}
function do_search(){
	if($this->find != ''){
		if((is_array($this->files) AND count($this->files) > 0) OR $this->files != '') $this->do_files($this->search_function);
		if($this->directories != '')                                       $this->do_directories($this->search_function);
	}
}

        } // End of class
?>