<?php
//构造对象配置信息(如控制登录)
$objects[] = array(
'id'         => 'appHook',
'enable'     => true,
'ignore'     => true,
'source'     => App.'/Hook/app.hook.php',
'className'  => 'Hook',
'property'   => array(
	'objRef' => array('com'=>'com')
));

//数据库钩子对象配置信息(记录SQL)
$objects[] = array(
'id'         => 'dbHook',
'enable'     => true,
'ignore'     => true,
'source'     => App.'/Hook/db.hook.php',
'className'  => 'DbHook',
'property'   => array(
	'objRef' => array('com'=>'com')
));
?>