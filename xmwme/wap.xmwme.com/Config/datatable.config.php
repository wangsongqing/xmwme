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
$prefix    = 'xm_';
$configDir = '../'.PROJECT_NAME.'/Config/dbconfig/';
/*manage开始*/
$tbl['user_info'] = array(
    'name'       => $prefix.'user_info',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);