<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name dom.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

class YO_phpdom {


	/**********************
	 * private variables: *
	 **********************/
	var $__NODE_ID__ = "";
	var $_nodeName = "";
	var $_nodeType = "";
	var $_nodeValue = "";
	var $_attr = array ();
	var $_childNodes = array ();
	var $_parentNode;

	/*********************
	 * public functions: *
	 *********************/
	function Node($NODE_ID = "ID_0") {
		$this->__construct ( $NODE_ID );
	}

	function __construct($NODE_ID = "ID_0") {
		$this->_parentNode = NULL;
		$this->__NODE_ID__ = $NODE_ID;
		$this->_nodeName = "";
		$this->_nodeType = "";
		$this->_nodeValue = "";
		$this->_attr = array ();
		$this->_childNodes = array ();
	}

	/* attributes */
	function NodeName() {
		return $this->_nodeName;
	}

	function SetNodeName($name) {
		$this->_nodeName = $name;
	}

	function NodeType() {
		return $this->_nodeType;
	}

	function Value() {
		return $this->_nodeValue;
	}
	function SetValue($val) {
		$this->_nodeValue = $val;
	}

	function Attribute() {
		return $this->_attr;
	}

	function SetAttribute($attr, $val) {
		$this->_attr [$attr] = $val;
	}

	/* insert */
	function AppendChild($child) {
		/*
         *    should clone a node in php5, php5 treat object $child as reference
         *    so, when the node $child changes, the child of this also changes
         *    we can use "$_child=$child" in php4 as "$_child = clone $child" in php5
         */
		$cnt = count ( $this->_childNodes );
		$this->_childNodes [$cnt] = new Node ();
		$this->_childNodes [$cnt]->_MClone ( $child );
		$this->Serial ();
	}

	function InsertBefore($node, $referenceNode) {
		$flag = false;
		$cnt = count ( $this->_childNodes );

		for($i = 0; $i < $cnt; $i ++) {
			if ($this->_childNodes [$i]->_Equal ( $referenceNode )) {
				$this->_childNodes [$cnt] = new Node ();
				for($k = $cnt; $k > $i; $k --) {
					$this->_childNodes [$k]->_MClone ( $this->_childNodes [$k - 1] );
				}

				$this->_childNodes [$i] = new Node ();
				$this->_childNodes [$i]->_MClone ( $node );
				$flag = true;
				break;
			}
		}
		$this->Serial ();
		return $flag;
	}

	function MInsertBefore($referenceNode) {
		$_parent = &$referenceNode->ParentNode ();
		if ($_parent == NULL)
			return false;
		return $_parent->InsertBefore ( $this, $referenceNode );
	}

	function InsertAfter($node, $referenceNode) {
		$flag = false;
		$cnt = count ( $this->_childNodes );
		for($i = 0; $i < $cnt; $i ++) {
			if ($this->_childNodes [$i]->_Equal ( $referenceNode )) {
				$i ++;
				$this->_childNodes [$cnt] = new Node ();
				for($k = $cnt; $k > $i; $k --) {
					$this->_childNodes [$k]->_MClone ( $this->_childNodes [$k - 1] );
				}
				$this->_childNodes [$i] = new Node ();
				$this->_childNodes [$i]->_MClone ( $node );
				$flag = true;
				break;
			}
		}
		$this->Serial ();
		return $flag;
	}

	function MInsertAfter($referenceNode) {
		$_parent = &$referenceNode->ParentNode ();
		if ($_parent == NULL)
			return false;
		$_parent->InsertAfter ( $this, $referenceNode );
	}
	/* remove */
	function RemoveChild($child) {
		while ( list ( $key, $val ) = each ( $this->_childNodes ) ) {
			if ($val->_Equal ( $child )) {
				for($i = $key, $cnt = count ( $this->_childNodes ); $i < $cnt - 1; $i ++) {
					$this->_childNodes [$i]->_MClone ( $this->_childNodes [$i + 1] );
				}
				unset ( $this->_childNodes [$cnt - 1] );
				reset ( $this->_childNodes );
				$this->Serial ();
				return true;
			}
		}
		return false;
	}

	/* dom, get node */
	function ChildNodes() {
		return $this->_childNodes;
	}

	function &ParentNode() {
		return $this->_parentNode;
	}

	function &FirstChild() {
		if (count ( $this->_childNodes ) == 0) {
			$rNode = new Node ( NULL );
			return $rNode;
		}
		return $this->_childNodes [0];
	}

	function &LastChild() {
		$cnt = count ( $this->_childNodes );
		if ($cnt == 0) {
			$rNode = new Node ( NULL );
			return $rNode;
		}
		return $this->_childNodes [$cnt - 1];
	}

	function &PreviousSibling() {
		$_parent = $this->ParentNode ();
		if ($_parent == NULL) {
			$rNode = new Node ( NULL );
			return $rNode;
		}

		$cnt = count ( $_parent->_childNodes );
		for($i = 0; $i < $cnt; $i ++) {
			if ($this->_Equal ( $_parent->_childNodes [$i] )) {
				if ($i > 0)
					return $_parent->_childNodes [$i - 1];
				break;
			}
		}

		$rNode = new Node ( NULL );
		return $rNode;
	}

	function &NextSibling() {
		$_parent = $this->ParentNode ();
		if ($_parent == NULL) {
			$rNode = new Node ( NULL );
			return $rNode;
		}

		$cnt = count ( $_parent->_childNodes );
		for($i = 0; $i < $cnt - 1; $i ++) {
			//Trace ($_parent->_childNodes[$i]);
			if ($this->_Equal ( $_parent->_childNodes [$i] ))
				return $_parent->_childNodes [$i + 1];
		}

		$rNode = new Node ( NULL );
		return $rNode;
	}

	/* functions */
	function &GetElementById($id) {
		$node = &$this->_GetElementById ( $id );
		return $node;
	}

	function GetElementsByTagName($tagName) {
		$rNodes = array ();
		$this->_GetElementsByTagName ( $tagName, $rNodes );
		return $rNodes;
	}

	function MClone($node) {
		$this->_MClone ( $node );
	}

	function IsNull() {
		return ($this->__NODE_ID__ === NULL);
	}

	/*********************
	 * private function: *
	 *********************/
	function _Equal($node) {
		if ($this->__NODE_ID__ == $node->__NODE_ID__ && $this->_nodeName == $node->_nodeName && $this->_nodeType == $node->_nodeType && $this->_nodeValue == $node->_nodeValue && $this->_attr == $node->_attr) {
		} else
			return false;

		$cnt = count ( $this->_childNodes );
		for($i = 0; $i < $cnt; $i ++) {
			return $this->_childNodes [$i]->_Equal ( $node->_childNodes [$i] );
		}
		return true;
	}

	function &_GetElementById($id) {
		for($i = 0, $cnt = count ( $this->_childNodes ); $i < $cnt; $i ++) {
			$node = &$this->_childNodes [$i];
			$attr = $node->_attr;
			if (@$attr ["id"] == $id) {
				return $node;
			}
			$rNode = &$node->_GetElementById ( $id );
			if (! $rNode->IsNull ())
				return $rNode;
		}
		$rNode = & new Node ( NULL );
		return $rNode;
	}

	function _GetElementsByTagName($tagName, &$rNodes) {
		for($i = 0, $cnt = count ( $this->_childNodes ); $i < $cnt; $i ++) {
			if ($this->_childNodes [$i]->_nodeName == $tagName) {
				$rNodes [] = &$this->_childNodes [$i];
			}
			$this->_childNodes [$i]->_GetElementsByTagName ( $tagName, $rNodes );
		}
	}

	/*
     *    because the difference of php4 and php5
     *    in php4:    $newNode = $refNode;
     *    in php5:    $newNode = clone $refNode;
     */
	function _MClone($node) {
		$this->_nodeName = $node->_nodeName;
		$this->_nodeType = $node->_nodeType;
		$this->_nodeValue = $node->_nodeValue;
		$this->_attr = $node->_attr;
		$this->_childNodes = $node->_childNodes;
		$this->_parentNode = $node->_parentNode;

		for($i = 0, $cnt = count ( $node->_childNodes ); $i < $cnt; $i ++) {
			$this->_childNodes [$i]->_MClone ( $node->_childNodes [$i] );
		}
	}

	function Serial() {
		$cnt = count ( $this->_childNodes );
		for($i = 0; $i < $cnt; $i ++) {
			$this->_childNodes [$i]->__NODE_ID__ = $this->__NODE_ID__ . "_" . $i;
			$this->_childNodes [$i]->_parentNode = &$this;
			$this->_childNodes [$i]->Serial ();
		}
	}

	/*
     *
     *    本来以下是 Node 的子类 XmlDom，但XmlDom的节点是Node类，但是XmlDom的节点又要拥有Parse函数
     *    导致Node类无法调用到Parse函数
     *    因为php没有虚函数（或者没有找到何时方法）
     *    只好讲这两个类合并为一个
     *
     */

	var $_xmlFile;
	var $charset = "UTF-8";

	function LoadXml($data, $charset = "") {
		$fp = fopen ( $data, "r" );
		if (! $fp) {
			$fp = fopen ( $data, "w+" );
			if (! $fp)
				die ( "Fail to open $data" );
		}
		$this->_xmlFile = $data;
		$str = "";
		while ( ! feof ( $fp ) )
			$str .= fgets ( $fp, 1024 );

		if ($charset != "" && $charset != $this->charset)
			if (function_exists ( "iconv" ))
				$str = iconv ( $charset, $this->charset, $str );
			else
				echo "不支持iconv函数，对中文可能会产生乱码！";

		fclose ( $fp );
		$this->Initialize ( $str );
	}

	function SaveXml($file = "") {
		if ($file == "")
			$file = $this->_xmlFile;
		$fp = fopen ( $file, "w" );
		if (! $fp)
			die ( "Fail to open file $file for saving" );
			//$this->Parse($str, 0);
		$str = $this->Parse ( 0 );
		$str = preg_replace ( '/<__TEXT__>\\s*/', '', $str );
		$str = preg_replace ( '/\\s*<\/__TEXT__>/', '', $str );

		$str = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $str;
		fwrite ( $fp, $str );
		fclose ( $fp );
	}

	/* 将xml解析为dom类存储 */
	function Initialize($str) {

		$str = preg_replace ( '/<\\?.*?\\?>/', '', $str );

		/* 多个空字符转换为一个空格 */
		$str = preg_replace ( '/\\s+/i', ' ', $str );

		/* <title name="Hello" /> 转换为 <title name="Hello" ></title> */
		$str = preg_replace ( '/<([a-z0-9_]+)(\\s+.*?)\/>/i', '<\\1\\2></\\1>', $str );

		/* 清除空节点 */
		$str = preg_replace ( '/>\\s+</i', '><', $str );

		$str = preg_replace ( '/>([^<]+)/i', '><__TEXT__>\\1</__TEXT__>', $str );

		$str = trim ( $str );

		$arrTag = array (); //存储标签
		$arrNode = array (); //存储节点


		$i = 0;
		$char = $str [$i];
		while ( @ord ( $str [$i] ) != 0 ) {
			$tStr = "";

			/* <...> 节点开始 */
			if ($str [$i] == "<" && $str [$i + 1] != "/") {
				$i ++;
				while ( $str [$i] != ">" ) {
					$tStr .= $str [$i];
					$i ++;
				}
				/* 节点名 */
				$tStr = trim ( $tStr );
				preg_match ( '/^([a-z0-9_]+)/i', $tStr, $match );
				$nodeName = $match [1];

				array_push ( $arrTag, $nodeName );
				$node = new Node ();
				$node->_nodeName = $nodeName;

				/* 节点属性 */
				$tStr = preg_replace ( '/^([a-z0-9_]+)/i', '', $tStr );
				preg_match_all ( '/([a-z0-9_]+) *= *"([^"]*)"/i', $tStr, $match );
				while ( list ( $key, ) = each ( $match [1] ) ) {
					$node->SetAttribute ( $match [1] [$key], $match [2] [$key] );
				}

				/* 取节点值 _nodeValue */
				$i ++;
				$tStr = "";
				while ( $str [$i] != "<" ) {
					$tStr .= $str [$i];
					$i ++;
				}
				$node->_nodeValue = $tStr;

				array_push ( $arrNode, $node );
				continue;
			} /* <..> end */

			/* </..> 节点结束 */
			if ($str [$i] == "<" && $str [$i + 1] == "/") {
				$i += 2;
				$tStr = "";
				while ( $str [$i] != ">" ) {
					$tStr .= $str [$i];
					$i ++;
				}
				$tStr = trim ( $tStr );
				$topTag = array_pop ( $arrTag );
				if ($tStr != $topTag)
					die ( "<br /><b>The xml is invalid. Tag is not closed</b><br />\n" . $topTag . " => " . $tStr . "<br />\n" );
				$topNode = array_pop ( $arrNode );
				if (count ( $arrNode ) == 0) {
					$this->_MClone ( $topNode );
					$this->Serial ();
					return true;
				}
				$pNode = array_pop ( $arrNode );
				$pNode->_childNodes [] = $topNode;

				array_push ( $arrNode, $pNode );
				continue;
			} /* </..> end */
			$i ++;
		}
	}

	function Parse($depth = 0) {
		$str = "";
		$tab = "";
		for($i = 0; $i < $depth; $i ++)
			$tab .= "\t";
		$str .= $tab . "<" . $this->_nodeName;
		while ( list ( $key, $val ) = each ( $this->_attr ) ) {
			$str .= " $key=\"$val\"";
		}
		reset ( $this->_attr );

		$str .= ">\n";
		if ($this->_nodeValue != "")
			$str .= $tab . "\t" . $this->_nodeValue . "\n";
		$cnt = count ( $this->_childNodes );
		$depth ++;
		for($i = 0; $i < $cnt; $i ++) {
			$str .= $this->_childNodes [$i]->Parse ( $depth );
		}
		$str .= $tab . "</" . $this->_nodeName . ">\n";
		return $str;
	}
}

function Trace($obj, $depth = 0) {
	if ($depth == 0)
		echo "<br />===============<br />\n";
	for($i = 0; $i < $depth * 4; $i ++)
		echo "&nbsp;";
	echo "NODE_ID => <font color='red'>" . $obj->__NODE_ID__ . "</font><br />\n";
	for($i = 0; $i < $depth * 4; $i ++)
		echo "&nbsp;";
	echo "nodeName => <font color='red'>" . $obj->_nodeName . "</font><br />\n";
	$attr = $obj->Attribute ();
	while ( list ( $key, $val ) = each ( $attr ) ) {
		for($i = 0; $i < $depth * 4; $i ++)
			echo "&nbsp;";
		echo $key . " => <font color='blue'>" . $val . "</font><br />\n";
	}
	echo "<br />\n";
	$depth ++;
	$child = $obj->ChildNodes ();
	for($i = 0; $i < count ( $child ); $i ++)
		Trace ( $child [$i], $depth );
}

/*
require ("XmlDom.php");

$xml = new Node ();

/*    如果加载的xml文件本身就是utf-8编码，则只需要 $xml->LoadXml("1.xml");即可
$xml->LoadXml ("1.xml");

$node = new Node ();
$node->SetAttribute ("id", "newNode");
$node->SetNodeName ("date");
$node->SetValue (date("Y-m-d H:i:s"));

$xml->AppendChild ($node);

$xml->SaveXml ("2.xml");

$node = $xml->GetElementById("3");
$xml->RemoveChild ($node);
$xml->SaveXml ("3.xml");

$nodes = $xml->GetElementsByTagName ("id");
$nodes[0]->SetAttribute("Got", "Got it");
$xml->SaveXml ("4.xml");

unset ($node);
$node = new Node ();
$node->SetAttribute ("id", "insert");
$node->SetNodeName ("insert");
$node->SetValue ("This is a node insert before...");
$xml->InsertBefore ($node, $nodes[0]);
$node->SetValue ("This is a node insert after...");
$nodes = $xml->GetElementsByTagName ("id");
$nodes0 = &$nodes[0];
$xml->InsertAfter ($node, $nodes0);
$xml->SaveXml ("5.xml");

$refNode = &$xml->GetElementById("insert");


$refNode->SetAttribute("id", "New Insert");
$xml->SaveXml ("6.xml");
/* open url */
//$urlXml = new Node ();
//$urlXml->LoadXml ("http://lilybbs.net/temp/top10.xml", "GBK");
//$urlXml->SaveXml ("lily.xml");
?>