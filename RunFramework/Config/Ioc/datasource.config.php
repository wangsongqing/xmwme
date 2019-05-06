<?php
//mssql数据库访问接口
$objects[] = array(
'id'         => 'db',
'enable'     => false,
'source'     => Lib.'/Db/RunDbAdo.php',
'className'  => 'RunDbAdo',
'import'     => array(Lib.'/Db/IDataSource.php'),
'property'   => array(
	'objRef'      => array('page'=>'pager'),
	'configFile'  => Config.'/mssql.config.php'
));

//mssql数据库访问接口
$objects[] = array(
'id'         => 'db',
'enable'     => false,
'source'     => Lib.'/Db/RunMssql.php',
'className'  => 'RunMssql',
'import'     => array(Lib.'/Db/IDataSource.php'),
'property'   => array(
	'objRef'      => array('page'=>'pager'),
	'configFile'  => Config.'/mssql.config.php'
));

//mysql数据库访问接口
$objects[] = array(
'id'         => 'db',
'enable'     => true,
'source'     => Lib.'/Db/RunMysql.php',
'className'  => 'RunMysql',
'import'     => array(Lib.'/Db/IDataSource.php'),
'property'   => array(
	'objRef'      => array('hook'=>'dbHook', 'page'=>'pager'),
	'configFile'  => Config.'/mysql.config.php'
));

//通用数据库访问接口
$objects[] = array(
'id'         => 'db',
'enable'     => false,
'source'     => Lib.'/Db/RunDbPdo.php',
'className'  => 'RunDbPdo',
'import'     => array(Lib.'/Db/IDataSource.php'),
'property'   => array(
	'objRef'      => array('page'=>'pager'),
	'configFile'  => Config.'/pdo.mysql.config.php',
	'dbType'      => 'mysql'
));
?>