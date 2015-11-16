<?php
/**
* 使用示例
*
* @copyright Copyright (c) 2007-2008 (http://www.tblog.com.cn)
* @author Akon(番茄红了) <aultoale@gmail.com>
* @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
*/

/************************************************************
    Discuz 模板引擎类
    1、去掉了 Discuz 语言包的功能
    2、移植 Discuz 模板中所有的功能
    3、添加了自动更新缓存及生命周期功能
************************************************************/

require_once ('classes/template.class.php');

$options = array(
    'template_dir' => 'templates/', //指定模板文件存放目录
    'cache_dir' => 'templates/cache', //指定缓存文件存放目录
    'auto_update' => true, //当模板文件有改动时重新生成缓存 [关闭该项会快一些]
    'cache_lifetime' => 1, //缓存生命周期(分钟)，为 0 表示永久 [设置为 0 会快一些]
);
$template = Template::getInstance(); //使用单件模式实例化模板类
$template->setOptions($options); //设置模板参数

/*
    // 可以使用以下三种方法设置参数
    $template->setOptions(array('template_dir' => 'templates/default/')); //用于批量设置时使用
    $template->set('template_dir', 'templates/default/');
    $template->template_dir = 'templates/default/');
*/

$testArr = array('testa' => 'a', 'testb' => 'b');
$sys = 'chenxiang';
include($template->getfile('test.htm'));