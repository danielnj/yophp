<?php
define ( 'G_ROOT', dirname ( __FILE__ ) . DIRECTORY_SEPARATOR );
// 定义框架路径
define('CORE_PATH', '../yophp');
//定义项目名称和路径
define('APP_NAME', 'Hp');
define('APP_PATH', G_ROOT);
// 加载框架公共入口文件
require(CORE_PATH."/yo.php");
//实例化
$apps = new Application();	//实现化一个项目
$apps->run();	//执行
?>