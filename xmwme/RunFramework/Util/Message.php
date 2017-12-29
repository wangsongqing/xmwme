<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 消息提示
 +------------------------------------------------------------------------------
 * @date    17-06
 * @author Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class Message
{
	/**
	 +----------------------------------------------------------
	 * 显示消息提示框(带提示信息+跳转)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $desc 消息文本
	 +----------------------------------------------------------
	 * @param  string  $url  跳转地址
	 +----------------------------------------------------------
	 */
	public function show($desc, $url, $scripts = array(), $seconds=2)
	{
		if (empty($desc)) 
		{
			if( is_array($scripts) && !empty($scripts) ) print implode('', $scripts);
			$this->noMsgBox($url);
		}

		if (file_exists(View.'/custom/msg.html'))
		{
			$msgFile = View.'/custom/msg.html';
			$path    = defined('Resource') ? Resource : (defined('Root') ? Root : '');
		}
		else
		{
			$msgFile = Lib.'/UI/msg.html';
			$path    = defined('Root') ? Root : (defined('Resource') ? Resource : '');
		}

		$js = '';
		foreach($scripts as $script) $js .= $script;

		if(!file_exists($msgFile)) 
		{
			print("<br><br>$desc<br><br>");
			print("请稍后,系统正在自动跳转........"); 
			foreach($scripts as $script) print $script;
			die("<meta http-equiv='Refresh' content='$seconds; url=$url'>");
		}
		$tipInfo  = "$desc <p>请稍后,系统正在自动跳转........</p>";
		$gotoUrl  = "<meta http-equiv='Refresh' content='$seconds; url=$url'>";
		$strHtml = file_get_contents($msgFile);
		$strHtml = str_replace('$url', "$url", $strHtml);
		$strHtml = str_replace('$root', $path, $strHtml);
		$strHtml = str_replace('$tipInfo', "$tipInfo", $strHtml);
		$strHtml = str_replace('$gotoUrl', "$gotoUrl", $strHtml);
		$strHtml = str_replace('$js', "$js", $strHtml);
		print $strHtml;
		die();
	}


	/**
	 +----------------------------------------------------------
	 * 显示消息提示框
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $desc 消息文本
	 +----------------------------------------------------------
	 */
	public function display($desc)
	{
		if (file_exists(View.'/custom/msg.display.html'))
		{
			$msgFile = View.'/custom/msg.display.html';
			$path    = defined('Resource') ? Resource : (defined('Root') ? Root : '');
		}
		else
		{
			$msgFile = Lib.'/UI/msg.display.html';
			$path    = defined('Root') ? Root : (defined('Resource') ? Resource : '');
		}

		if(!file_exists($msgFile)) 
		{
			die($desc);
		}
		$strHtml = file_get_contents($msgFile);
		$strHtml = str_replace('$desc', "$desc", $strHtml);
		$strHtml = str_replace('$root', $path, $strHtml);
		die($strHtml);
	}


	/**
	 +----------------------------------------------------------
	 * 显示消息提示框(JS提示)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $msg 消息文本
	 +----------------------------------------------------------
	 * @param  string  $url 跳转地址
	 +----------------------------------------------------------
	 */
	public function msgBox($msg, $url)
	{
		print "<script language='javascript'>";
		print "alert('$msg');location.href='$url';";
		print "</script>";
		die();
	}

	/**
	 +----------------------------------------------------------
	 * 地址跳转(JS不带提示信息)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $url 跳转地址
	 +----------------------------------------------------------
	 */
	public function noMsgBox($url)
	{
		print "<script language='javascript'>";
		print "location.href='$url';";
		print "</script>";
		die();
	}
}
?>
