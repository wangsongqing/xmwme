<?php
//数据表配置管理
/**
 * @author wangsongiqng <jjimmywsq@163.com>
 * @time 2017-05-15
 * 当你每建立一个数据表的时候   必须在此添加表管理器
 * 此处的好处是当你一个项目有多个数据库的时候可以方便的管理，也方便数据库集群的管理，
 * 例如：
 * $tbl['user'] = array(
    'name'       => $prefix.'manage_user', 表名
    'dbId'       => 'manage_user',//数据库名称
    'configFile' => $configDir.'run_user.php',表所在的数据库配置文件
);
 */
$prefix    = 'run_';
$configDir = '../'.PROJECT_NAME.'/Config/dbconfig/';
/*manage开始*/
$tbl['manage_user'] = array(
    'name'       => $prefix.'manage_user',
    'dbId'       => 'manage_user',
    'configFile' => $configDir.'run_user.php',
);

$tbl['manage_log'] = array(
    'name'       => $prefix.'manage_log',
    'dbId'       => 'manage_log',
    'configFile' => $configDir.'run_user.php',
);

$tbl['import_user'] = array(
    'name'       => $prefix.'import_user',
    'dbId'       => 'import_user',
    'configFile' => $configDir.'run_import.php',
);
$tbl['lian'] = array(
    'name'       => $prefix.'lian',
    'dbId'       => 'lian',
    'configFile' => $configDir.'run_user.php',
);
$tbl['play_hamster'] = array(
    'name'       => $prefix.'play_hamster',
    'dbId'       => 'play_hamster',
    'configFile' => $configDir.'run_user.php',
);
$tbl['catch_egg'] = array(
    'name'       => $prefix.'catch_egg',
    'dbId'       => 'catch_egg',
    'configFile' => $configDir.'run_user.php',
);
$tbl['answer_attend'] = array(
    'name'       => $prefix.'answer_attend',
    'dbId'       => 'answer_attend',
    'configFile' => $configDir.'run_user.php',
);
$tbl['answer_questions'] = array(
    'name'       => $prefix.'answer_questions',
    'dbId'       => 'answer_questions',
    'configFile' => $configDir.'run_user.php',
);