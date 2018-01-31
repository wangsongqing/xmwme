<?php
$object['id'] = "dispatcher";
$object['enable'] = "1";
$object['source'] = "../RunFramework/Core/Dispatcher.php";
$object['className'] = "Dispatcher";
$object['import']['0'] = "../RunFramework/Core/IDispatcher.php";

$object['property']['hookList']['hook'] = "appHook";
$object['property']['configFile'] = "Config/route.config.php";
$object['property']['isRewrite'] = "1";
$object['property']['module'] = "IndexAction";
$object['property']['actionPath'] = "App/Action";

?>
