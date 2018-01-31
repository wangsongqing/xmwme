<?php
$object['id'] = "mem";
$object['enable'] = "1";
$object['source'] = "../RunFramework/Util/MmCache.php";
$object['className'] = "MmCache";
$object['import']['0'] = "../RunFramework/Util/ICache.php";

$object['property']['expire'] = "1800";
$object['property']['compressed'] = "1";
$object['property']['configFile'] = "Config/memcache.config.php";

?>
