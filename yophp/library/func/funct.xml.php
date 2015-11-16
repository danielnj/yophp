<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name xml.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */


function xml_file($filename, $keyid = 'errorentry')
{
	$string = implode('', file($filename));
	return xml_str($string, $keyid);
}

function xml_str($string, $keyid = 'errorentry')
{
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $string, $values, $tags);
	xml_parser_free($parser);
	$tdb = array();
	foreach ($tags as $key=>$val)
	{
		if($key != $keyid) continue;
		$molranges = $val;
		for ($i=0; $i < count($molranges); $i+=2)
		{
			$offset = $molranges[$i] + 1;
			$len = $molranges[$i + 1] - $offset;
			$tdb[] = xml_arr(array_slice($values, $offset, $len));
		}
	}
	return $tdb;
}

function xml_arr($mvalues)
{
	$arr = array();
	for($i=0; $i < count($mvalues); $i++)
	{
		$arr[$mvalues[$i]['tag']] = $mvalues[$i]['value'];
	}
	return $arr;
}
?>