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
$prefix    = 'xm_';//数据表前缀
$configRoot = realpath('./Config');
$configDir = $configRoot.'/dbconfig/';
/*manage开始*/
$tbl['user_info'] = array(
    'name'       => $prefix.'user_info',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['credit'] = array(
    'name'       => $prefix.'credit',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['credit_log'] = array(
    'name'       => $prefix.'credit_log',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['banner'] = array(
    'name'       => $prefix.'banner',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['goods'] = array(
    'name'       => $prefix.'goods',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['orders'] = array(
    'name'       => $prefix.'orders',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['manage_log'] = array(
    'name'       => $prefix.'manage_log',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
/*xm_activity 库 start*/
$tbl['activity'] = array(
    'name'       => $prefix.'activity',
    'dbId'       => 'xm_activity',
    'configFile' => $configDir.'xm_activity.php',
);
$tbl['activity_log'] = array(
    'name'       => $prefix.'activity_log',
    'dbId'       => 'xm_activity',
    'configFile' => $configDir.'xm_activity.php',
);
$tbl['lian'] = array(
    'name'       => $prefix.'lian',
    'dbId'       => 'xm_activity',
    'configFile' => $configDir.'xm_activity.php',
);