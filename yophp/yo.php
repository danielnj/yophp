<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name yo.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

error_reporting ( E_ALL );
//区域定义
date_default_timezone_set ( 'PRC' );

//版本号
define('YO_VERSION',	'1.0.0');

//常量
define ( 'S_ROOT', dirname ( __FILE__ ) . DIRECTORY_SEPARATOR );
define ( 'S_LIB', S_ROOT.'library' );
define ( 'S_CLASS', S_LIB.'/class' );
define ( 'S_DB', S_LIB.'/db' );
define ( 'S_DRIVER', S_LIB.'/driver' );
define ( 'S_VIEW', S_LIB.'/view' );
define ( 'S_FUNC', S_LIB.'/func' );
define ( 'S_CONFIG', S_ROOT.'config' );
define ( 'S_LANG', S_ROOT.'lang' );
define ( 'S_3RD', S_ROOT.'3rd' );


@set_magic_quotes_runtime(0);

//加载全局文件
require_once(S_ROOT.'global.php');

//加载核心类
import('base', S_ROOT);
//基础应用类
import('application', S_ROOT);
//基础视图类
import('view', S_ROOT);
//基础模型类
import('model', S_ROOT);

?>