<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name config.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

return array(
	'mode' => 'debug', // 应用程序模式，默认为调试模式

	'auto_load_controller' => array(), // 控制器自动加载的扩展类名
	'auto_load_model' => array(), // 模型自动加载的扩展类名

	'inst_class' => array(), // 已实例化的类名称
	'import_file' => array(), // 已经载入的文件
	'view_registered_functions' => array(), // 视图内挂靠的函数记录

	'default_controller' => 'do', // 默认的控制器名称
	'default_action' => 'php',  // 默认的动作名称

	'url_controller' => 'c',  // 请求时使用的控制器变量标识
	'url_action' => 'm',  // 请求时使用的动作变量标识

	'auto_session' => TRUE, // 是否自动开启SESSION支持
	'dispatcher_error' => "路由错误，请检查控制器目录下是否存在该控制器/动作。;", // 定义处理路由错误的函数

	'yo_temp' => 'tmp', // 框架临时文件夹目录
	'controller_path' => 'controller', // 用户控制器程序的路径定义
	'model_path' => 'model', // 用户模型程序的路径定义

	'url' => array( // URL设置
		'url_path_info' => TRUE, // 是否使用path_info方式的URL
		'url_path_base' => '', // URL的根目录访问地址，默认为空则是入口文件index.php
	),

	'db' => array(  // 数据库连接配置
		'driver' => 'mysql',   // 驱动类型
		'host' => 'localhost', // 数据库地址
		'port' => 3306,        // 端口
		'login' => 'root',     // 用户名
		'password' => '',      // 密码
		'database' => '',      // 库名称
		'prefix' => '',           // 表前缀
		'persistent' => FALSE,    // 是否使用长链接
	),
	'db_driver_path' => '', // 自定义数据库驱动文件地址
	'db_spdb_full_tblname' => TRUE, // spDB是否使用表全名

	'view' => array( // 视图配置
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/template', // 模板目录
			'compile_dir' => APP_PATH.'/complie', // 编译目录
			'cache_dir' => APP_PATH.'/cache', // 缓存目录
			'left_delimiter' => '<{',  // smarty左限定符
			'right_delimiter' => '}>', // smarty右限定符
		),
		'debugging' => FALSE, // 是否开启视图调试功能，在部署模式下无法开启视图调试功能
		'engine_name' => 'Smarty', // 模板引擎的类名称，默认为Smarty
		'auto_ob_start' => TRUE, // 是否自动开启缓存输出控制
		'auto_display' => TRUE, // 是否使用自动输出模板功能
		'auto_display_sep' => '/', // 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
		'auto_display_suffix' => '.html', // 自动输出模板的后缀名
	),

	'lang' => 'english', // 多语言设置，键是每种语言的名称，而值可以是default（默认语言），语言文件地址或者是翻译函数
					// 同时请注意，在使用语言文件并且文件中存在中文等时，请将文件设置成UTF8编码
	'ext' => array(), // 扩展使用的配置根目录

);
