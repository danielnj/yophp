<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name discuz.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

function transamp($template) {
    $template = str_replace('&', '&amp;', $template);
    $template = str_replace('&amp;amp;', '&amp;', $template);
    $template = str_replace('\"', '"', $template);
    return $template;
}

function stripvtags($expr, $statement) {
    $expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
    $statement = str_replace("\\\"", "\"", $statement);
    return $expr . $statement;
}

function addquote($var) {
    return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function stripscriptamp($s) {
    $s = str_replace('&amp;', '&', $s);
    return "<script src=\"$s\" type=\"text/javascript\"></script>";
}

function stripblock($var, $s) {
    $s = str_replace('\\"', '"', $s);
    $s = preg_replace("/<\?=\\\$(.+?)\?>/", "{\$\\1}", $s);
    preg_match_all("/<\?=(.+?)\?>/e", $s, $constary);
    $constadd = '';
    $constary[1] = array_unique($constary[1]);
    foreach($constary[1] as $const) {
        $constadd .= '$__' . $const  .' = ' . $const . ';';
    }
    $s = preg_replace("/<\?=(.+?)\?>/", "{\$__\\1}", $s);
    $s = str_replace('?>', "\n\$$var .= <<<EOF\n", $s);
    $s = str_replace('<?', "\nEOF;\n", $s);
    return "<?\n$constadd\$$var = <<<EOF\n" . $s . "\nEOF;\n?>";
}