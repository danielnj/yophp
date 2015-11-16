<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name pager.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_page {

	var $page_list = '';
	var $get_page = 1;
	var $url = '';
	var $search = '';
	var $sql = '';
	var $num_mount = 1;
	var $page_string1 = '';
	var $page_str = '';
	var $page_str1 = '';
	var $page_string2 = '';
	var $get_count = 1;
	var $pass_condition = '';


	function pager($page_url, $get_page, $get_count, $num_mount) {
		$this->get_page = $get_page;
		$this->url = $page_url;
		$this->sql = $sql_condtion; //SQL语句
		$this->num_mount = $num_mount;
		$this->get_count = $get_count;
		if ($sql_condtion != '') {
			$this->pass_condition = "&sqlcondition=" . $this->sql . "";
		} else {
			$this->pass_condition = '';
		}

	}
	function getPageNumber() {
		if ($this->get_page == 1) {
			$this->page_string1 = "Previous";
			$this->page_str = "First";
		} else {
			$this->page_str = "<a href=" . $this->url . "&page=1" . $this->pass_condition . ">First</a>";
			$this->page_string1 = "<a href=" . $this->url . "&page=" . ($this->get_page - 1) . $this->pass_condition . ">Previous</a>";
		}
		if (($this->get_page == $this->get_count) or ($this->get_count == 1)) {
			$this->page_string2 = "Next";
			$this->page_str1 = "Last";
		} else {
			$this->page_str1 = "<a href=" . $this->url . "&page=" . $this->get_count . $this->pass_condition . ">Last</a>";
			$this->page_string2 = "<a href=" . $this->url . "&page=" . ($this->get_page + 1) . $this->pass_condition . ">Next</a>";
		}
		/**********************************/
		if ($this->get_count == '') {
			$nowcount = 1;
		} else {
			$nowcount = $this->get_count;
		}
		$pout = "【 ALL <font color=#ff0000>" . $this->num_mount . "</font> Records  】 ";
		$this->page_list = "<div align=right><SPAN style=FONT-SIZE: 12px; FONT-FAMILY: Tahoma,宋体>" . $pout . "" . $this->page_str . " | " . $this->page_string1 . " | " . $this->page_string2 . " | " . $this->page_str1 . " Now In： " . $this->get_page . " Page</SPAN> ";
	}

	function outpage() {
		if ($this->num_mount != 0) {
			return $this->page_list;
		}
	}

}
?>
