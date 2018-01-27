<?php
/**
 +---------------------------------------------------------------------------------------------------------------
 * Run Framework 系统异常类
 +---------------------------------------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +---------------------------------------------------------------------------------------------------------------
 */
class RunException
{
	/**
	 +----------------------------------------------------------
	 * 变量输出(供调试使用)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string $msg   异常信息
	 +----------------------------------------------------------
	 */
	public static function dump($var, $echo=true,$label=null, $strict=true)
	{
		$label = ($label===null) ? '' : rtrim($label) . ' ';
		if(!$strict) {
			if (ini_get('html_errors')) {
				$output = print_r($var, true);
				$output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
			} else {
				$output = $label . " : " . print_r($var, true);
			}
		} else {
			ob_start();
			var_dump($var);
			$output = ob_get_clean();
			if(!extension_loaded('xdebug')) {
				$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
				$output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
			}
		}
		if ($echo) {
			echo($output);
			return null;
		}else {
			return $output;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 抛出框架异常信息
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string $msg   异常信息
	 +----------------------------------------------------------
	 */
	public static function throwException($msg)
	{       
                if($msg!='控制器 Exception 未找到!'){
                    RunException::writeLog($msg,  date('Y-m-d').'_'.'error.log'); //记录日志信息(便于调试)
                }
		if (!defined('Debug')) exit();
		if (defined('Debug') && Debug==0) exit();
		if (defined('Debug') && Debug==1) RunException::displayAppError();
		if (Debug==2)
		{

			if (file_exists(View.'/custom/errorMsg.html'))
			{
				$msgFile = View.'/custom/errorMsg.html';
				$path    = defined('Resource') ? Resource : (defined('Root') ? Root : '');
			}
			else
			{
				$msgFile = Lib.'/UI/errorMsg.html';
				$path    = defined('Root') ? Root : (defined('Resource') ? Resource : '');
			}

			if(!file_exists($msgFile)) 
			{
				die("<br><br><br><br><div align=center>异常信息: $msg <a href='#' onClick='history.go(-1);'>返回</a></div>");
			}
			$strHtml = file_get_contents($msgFile);
			$strHtml = str_replace('$root', $path, $strHtml);
			$strHtml = str_replace('$tipInfo', $msg, $strHtml);			
			die($strHtml);
		}
		if (Debug == 3)
		{
			$response = array(
				'desc'   => $msg,
				'status' => '-9999',
				);
			print json_encode($response);
		}
		
		if (Debug == 4) {
			$host  = $_SERVER['HTTP_HOST'];
			header("Location: http://".$host);
		}
		exit();
	}

	/**
	 +----------------------------------------------------------
	 * 日志记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $content 日志内容
	 +----------------------------------------------------------
	 * @param  string  $file    日志文件名
	 +----------------------------------------------------------
	 */
	public static function writeLog($content, $file='log.txt')
	{
		if(preg_match('/php/i',$file) || is_array($content)) return ;
		$logDir  = "log";
		if (!file_exists($logDir)) 
		{
			@mkdir($logDir);
			@chmod($logDir, 0777);
		}
		$reqUrl  = '请求的地址: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$content = "【".date("Y-m-d H:i:s", time())."】\t\t".$content."\t\t".$reqUrl."\r\n";
		file_put_contents($logDir.'/'.$file, $content, FILE_APPEND);
	}

	/**
	 +----------------------------------------------------------
	 * 显示具体应用的异常信息(显示友好界面：界面定制)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */
	public static function displayAppError()
	{
		if (file_exists(View.'/custom/appError.html'))
		{
			$msgFile = View.'/custom/appError.html';
			$path    = defined('Resource') ? Resource : (defined('Root') ? Root : '');
		}
		else
		{
			$msgFile = Lib.'/UI/appError.html';
			$path    = defined('Root') ? Root : (defined('Resource') ? Resource : '');
		}

		if(!file_exists($msgFile)) 
		{
			die("<br><br><br><br><div align=center>出错啦<a href='#' onClick='history.go(-1);'>返回</a></div>");
		}
		
		if(defined('IsRewrite') && IsRewrite)
		{
			$root = $path;
			$strHtml = file_get_contents($msgFile);
			$strHtml = str_replace("\"","\\\"",$strHtml);
			eval("\$strHtml=\"$strHtml\";");
			die($strHtml);
		}
		die(file_get_contents($msgFile));
	}
}
?>