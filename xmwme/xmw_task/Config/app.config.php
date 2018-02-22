<?php
define('Hook', true);                                                                 //开启hook自定义配置         
define('EditorImagePath', '../../../../Resource/attached/');                          //编辑器图片上传路径
define('CookiePath', '/');                                                            //cookie存放路径
define('CookieDomain', '');                                                           //cookie 域名
define('CookiePrefix', '6ePf_'.substr(md5(CookiePath.'|'.CookieDomain), 0, 4).'_');   //cookie前缀       
?>