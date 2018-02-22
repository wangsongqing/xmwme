<?php
$object['id'] = "mysql";
$object['enable'] = "1";
$object['source'] = "../RunFramework/Db/RunDbPdo.php";
$object['className'] = "RunDbPdo";
$object['import']['0'] = "../RunFramework/Db/IDataSource.php";

$object['property']['objRef']['hook'] = "dbHook";
$object['property']['objRef']['page'] = "pager";
$object['property']['configFile'] = "Config/mysql.config.php";

?>
