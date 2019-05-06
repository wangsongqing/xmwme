<?php
//视图输出
$objects[] = array(
'id'         => 'view',
'enable'     => true,
'source'     => Lib.'/Util/View.php',
'className'  => 'View',
'property'   => array(
    'tplDir' => View
));


//接收用户输入(带过滤功能)
$objects[] = array(
'id'         => 'in',
'enable'     => true,
'source'     => Lib.'/Util/Input.php',
'className'  => 'Input',
'initMethod' => 'parse',
);


//消息提示
$objects[] = array(
'id'         => 'msg',
'enable'     => true,
'source'     => Lib.'/Util/Message.php',
'className'  => 'Message'
);

//php文件缓存
$objects[] = array(
'id'        => 'fc',
'enable'    => true,
'source'    => Lib.'/Util/CacheFile.php',
'className' => 'CacheFile',
'import'    => array(Lib.'/Util/ICache.php'),
'property'  => array(
	'path'   => CacheDir,
	'expire' => 300
));


//sphinx对象
$objects[] = array(
'id'        => 'q',
'enable'    => true,
'source'    => Lib.'/Util/sphinxapi.php',
'className' => 'SphinxClient',
);

//redis队列
$objects[] = array(
'id'        => 'rd',
'enable'    => true,
'source'    => Lib.'/Util/RedisQ.php',
'className' => 'RedisQ',    
);
?>