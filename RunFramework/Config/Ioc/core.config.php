<?php
/*
对象配置信息:运行系统的基础对象
当 'enable'  => true 表示开启该功能，
当 'enable'  => false 表示禁用该功能,
真真实现了针对接口编程的思想(php中表现为隐私接口)
让设计者在一侧修改而对另一侧不会产生不良影响
*/

//组件对象构造器
$objects[] = array(
'id'         => 'com',
'enable'     => true,
'source'     => Lib.'/Core/ApplicationContext.php',
'className'  => 'ApplicationContext',
);

//模型工厂
$objects[] = array(
'id'         => 'mf',
'enable'     => true,
'source'     => Lib.'/Core/ModelFactory.php',
'className'  => 'ModelFactory',
'property'   => array(
    'path'   => Model
));

//文件加载器
$objects[] = array(
'id'         => 'loader',
'enable'     => true,
'source'     => Lib.'/Core/Loader.php',
'className'  => 'Loader',
'property'   => array(
    'fileList' => array(Lib.'/Core/Object.php', Lib.'/Core/Action.php', Lib.'/Core/Model.php', App.'/Util/functions.php'),
));


//调度器
$objects[] = array(
'id'         => 'dispatcher',
'enable'     => true,
'source'     => defined('OverRide') && OverRide ? App.'/Util/Dispatcher.php' : Lib.'/Core/Dispatcher.php',
'className'  => 'Dispatcher',
'import'     => array(Lib.'/Core/IDispatcher.php'),
'property'   => array(
	'hookList'  => array('hook'=>'appHook'),
	'configFile'=> Config.'/route.config.php',  //自定义路由配置文件
	'isRewrite' => IsRewrite,
	'module'    => Module,      //缺省控制器
	'actionPath'=> Action
));
?>