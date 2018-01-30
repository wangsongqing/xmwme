<?php
define('Debug', 0);                                    //调试信息(0、1、2)
define('IsRewrite', true);                             //是否url重写
define('IsCached', true);                              //是否缓存对象资源
define('Lib', '../RunFramework');                   //框架路径
define('Root', '/');                            //当前应用程序运行路径
define('DataRoot', 'Resource');                        //数据存放根目录 
define('Resource', Root.DataRoot.'/');                 //js、css、图片、等资源路径
define('CacheDir', DataRoot.'/Cache');                 //缓存对象资源目录
define('LogDir', 'log');                               //日志目录
define('App', 'App');                                  //应用代码存放路径
define('Model', App.'/Model');                         //业务逻辑(模型组件存放路径)
define('View', App.'/View');                           //视图层存放路径
define('Action', App.'/Action');                       //控制器存放路径
define('Config', 'Config');                            //配置文件所在路径
define('Module', 'IndexAction');                       //指定默认控制器    
define('ExceptionModule', 'ExceptionAction');          //指定发生异常时的控制器 
require_once(Config.'/app.config.php');                //载入应用全局配置
require_once(Config.'/webservice.config.php');      //秘钥相关的配置      
?>