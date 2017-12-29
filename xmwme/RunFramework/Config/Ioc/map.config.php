<?php
//对象配置信息:级联配置文件
$source[]['source'] = Lib.'/Config/Ioc/core.config.php';                                                                                     //框架核心组件对象配置
$source[]['source'] = file_exists(Config.'/hook.config.php') ? Config.'/hook.config.php' : Lib.'/Config/Ioc/hook.config.php';                //为应用扩展钩子配置文件
$source[]['source'] = file_exists(Config.'/extension.config.php') ? Config.'/extension.config.php' : Lib.'/Config/Ioc/extension.config.php'; //为应用扩展框架配置文件
$source[]['source'] = Lib.'/Config/Ioc/datasource.config.php';                                                                               //数据源组件对象配置
$source[]['source'] = Lib.'/Config/Ioc/component.config.php';                                                                                //常用组件对象配置
?>