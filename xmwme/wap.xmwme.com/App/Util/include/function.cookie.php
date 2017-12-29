<?php
/**
 +----------------------------------------------------------
 * 数据加密、解密
 +----------------------------------------------------------
 * @param string   $string     加密、解密字符串
 * @param string   $operation  加密、解密操作符(ENCODE加密、DECODE解密)
 * @param string   $key        密钥
 * @param string   $expiry     过期时间
 +----------------------------------------------------------
 * @return string
 +---------------------------------------------------------- 
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
	$ckey_length = 4;
	$key         = md5($key != '' ? $key : AuthKey);
	$keya        = md5(substr($key, 0, 16));
	$keyb        = md5(substr($key, 16, 16));
	$keyc        = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey    = $keya.md5($keya.$keyc);
	$key_length  = strlen($cryptkey);
	$string = $operation == 'DECODE' ? 
		base64_decode(substr($string, $ckey_length)) 
		: sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		}else{
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}


/**
 +----------------------------------------------------------
 * 写cookie
 +----------------------------------------------------------
 * @param string   $key    键
 * @param string   $value  数据
 * @param int      $life   生命周期
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------    
 */
function addCookie($key, $value = '', $life = 0, $httponly = false)
{
	if (empty($key)) return ;
	$timestamp = time();
	$key       = CookiePrefix.$key;
	if ($value == '' || $life < 0)
	{
		$value = '';
		$life  = -1;
	}
	
	$value  = $value == '' ? $value : authcode($value, 'ENCODE');
	$life   = $life > 0 ? $timestamp + $life : ($life < 0 ? $timestamp - 31536000 : 0);
	$path   = $httponly && PHP_VERSION < '5.2.0' ? CookiePath.'; HttpOnly' : CookiePath;
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if (PHP_VERSION < '5.2.0')
	{
		setcookie($key, $value, $life, $path, CookieDomain, $secure);
	}
	else
	{
		setcookie($key, $value, $life, $path, CookieDomain, $secure, $httponly);
	}
}

/**
 +----------------------------------------------------------
 * 读取cookie
 +----------------------------------------------------------
 * @param  string $key 键
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function getCookie($key)
{
	$key = CookiePrefix.$key;
	if(isset($_COOKIE[$key]) && $_COOKIE[$key])
	{
		return authcode($_COOKIE[$key], 'DECODE');
	}
	else
	{
		return '';
	}
}


/**
 +----------------------------------------------------------
 * 清除全部cookie
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------
 */
function clearCookie()
{
	$keys = array();
	$pos  = !CookiePrefix ?  0 : strlen(CookiePrefix);
	foreach($_COOKIE as $key=>$val)
	{
		$keys[] = substr($key, $pos, strlen($key));
	}
	
	foreach($keys as $key)
	{
		addCookie($key);
	}
}

/**
 +----------------------------------------------------------
 * 删除cookie
 * @param  string $key 键
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------
 */
function removeCookie($key)
{
	addCookie($key);
}
?>