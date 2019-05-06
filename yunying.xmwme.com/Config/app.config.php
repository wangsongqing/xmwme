<?php
$item   = explode(".", $_SERVER['HTTP_HOST']);
$length = count($item);
$domain = $item[$length-2].'.'.$item[$length-1];

define('Hook', true);                                                                 //开启hook自定义配置
define('WebUrl', 'http://'.$_SERVER['SERVER_NAME'].'/');
define('UploadImgPath', WebUrl.'Resource/');
define('AuthKey', md5('a72686uCLwATXU2O'.$_SERVER['HTTP_USER_AGENT']));               //密钥
define('CookiePath', '/');                                                            //cookie存放路径
define('CookieDomain', ".$domain");                                                           //cookie 域名
define('CookiePrefix', '6ePf_'.substr(md5(CookiePath.'|'.CookieDomain), 0, 4).'_');   //cookie前缀       
?>