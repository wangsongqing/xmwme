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
$configRoot = realpath('../Config');
$configDir = $configRoot.'/dbconfig/';


/*base库开始*/
$tbl['banner'] = array(
    'name'       => $prefix.'banner',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['manage_log'] = array(
    'name'       => $prefix.'manage_log',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['address'] = array(
    'name'       => $prefix.'address',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['areas'] = array(
    'name'       => $prefix.'areas',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['come_wx_num'] = array(
    'name'       => $prefix.'come_wx_num',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['manage_user'] = array(
    'name'       => $prefix.'manage_user',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
$tbl['blog'] = array(
    'name'       => $prefix.'blog',
    'dbId'       => 'xm_base',
    'configFile' => $configDir.'xm_base.php',
);
/*base库结束*/




/*core库开始*/
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
$tbl['withdraw'] = array(
    'name'       => $prefix.'withdraw',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['user_info'] = array(
    'name'       => $prefix.'user_info',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['redbag'] = array(
    'name'       => $prefix.'redbag',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
$tbl['redbag_log'] = array(
    'name'       => $prefix.'redbag_log',
    'dbId'       => 'xm_core',
    'configFile' => $configDir.'xm_core.php',
);
/*core库结束*/




/*xm_activity 库开始*/
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
/*xm_activity 库结束*/

