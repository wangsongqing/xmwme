<?php
//钩子对象配置信息(如控制登录)
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


//mysql数据库访问接口
$objects[] = array(
'id'         => 'mysql',
'enable'     => true,
'source'     => Lib.'/Db/RunDbPdo.php',
'className'  => 'RunDbPdo',
'import'     => array(Lib.'/Db/IDataSource.php'),
'property'   => array(
	'objRef'      => array('hook'=>'dbHook', 'page' => 'pager'),
	'configFile'  => 'Config/mysql.config.php'
));


//普通文件上传
$objects[] = array(
'id'         => 'upload',
'enable'     => true,
'source'     => Lib.'/Util/UploadFile.php',
'className'  => 'UploadFile',
'property'   => array(
	'maxSize' => 1073741824,
	'path'    => '/upload',
	'upType'  => 'docx|rar|zip|txt|xls|xlsx|jpg|gif|png',
));

//数据分页
$objects[] = array(
'id'        => 'pager',
'enable'    => true,
'source'    => Lib.'/Util/Page.php',
'className' => 'Page'
);

//参数验证
$objects[] = array(
'id'        => 'para',
'enable'    => true,
'source'    => Lib.'/Util/Parameter.php',
'className' => 'Parameter'
);

//验证码
$objects[] = array(
'id'        => 'vi',
'enable'    => true,
'source'    => Lib.'/Util/VerifyImg.php',
'className' => 'VerifyImg'
);

//表管理器
$objects[] = array(
'id'        => 'dt',
'enable'    => true,
'source'    => Lib.'/Util/TableManager.php',
'className' => 'TableManager',
'initMethod' => 'load',
'property'   => array(
	'configFile' => 'Config/datatable.config.php'
));

//Memcache
$objects[] = array(
    'id'        => 'mem',
    'enable'    => true,
    'source'    => Lib.'/Util/MmCache.php',
    'className' => 'MmCache',
    'import'    => array(Lib.'/Util/ICache.php'),
    'property'  => array(
        'expire'     => 1800,
        'compressed' => true,
        'configFile' =>'Config/memcache.config.php'
    ));


//Memcache  session
$objects[] = array(
    'id'        => 'sess',
    'enable'    => true,
    'source'    => Lib.'/Util/MmCache.php',
    'className' => 'MmCache',
    'import'    => array(Lib.'/Util/ICache.php'),
    'property'  => array(
        'expire'     => 360000,
        'compressed' => true,
        'configFile' => 'memcache.config.php'
    ));

//Redis队列接口
$objects[] = array(
    'id'        => 'queue',
    'enable'    => true,
    'source'    => Lib.'/Util/RedisQ.php',
    'className' => 'RedisQ',
    'import'    => array(Lib.'/Util/IQueue.php'),
    'property'  => array(
        'configFile' => 'redis.config.php'
    ));
?>