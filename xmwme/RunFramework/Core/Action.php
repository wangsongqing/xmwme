<?php
/**
 +---------------------------------------------------------------------------------------------------------------
 * Run Framework 视图基类
 +---------------------------------------------------------------------------------------------------------------
 * @date    2017-05
 * @author  Jimmy <jimmywsq@163.com>
 * @version 1.0
 +---------------------------------------------------------------------------------------------------------------
 */
abstract class Action extends Object
{
	public $caches = array();//缓存要执行的操作
	public $action = 'index';//当前被执行的操作
	public $debug  = false;//sql调试开关


	/**
	 +----------------------------------------------------------
	 * 装载模板文件、解析变量并显示
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $file   文件名
	 +----------------------------------------------------------
	 */
	public final function display($file = null,$arr=array())
	{	    
		if (in_array($this->action, $this->caches) && !$this->input['isPost'])
		{
			$data  = $this->com('view')->fetch($file);
			$cache = $this->getCacheObject();
			$key   = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$cache->set(md5($key), $data);

			ob_start('ob_gzip');
			print $data;
			ob_end_flush();
		}
		else
		{	
			$this->com('view')->display($file,$arr);
		}
	}


	/**
	 +----------------------------------------------------------
	 * 装载模板文件、解析变量,返回解析后的html
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $file   文件名
	 +----------------------------------------------------------
	 */
	public final function fetch($file = null)
	{
		return $this->com('view')->fetch($file);
	}


	/**
	 +----------------------------------------------------------
	 * 呼叫控制器执行操作
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $action   方法名
	 +----------------------------------------------------------
	 */
	public final function call($action)
	{
		$this->action = $action;

		//前置操作、操作、后置操作
		if (!method_exists($this, $action)) RunException::throwException("未定义的操作: $action");
		if (method_exists($this,'before'))  $this->before();
		if (in_array($this->action, $this->caches) && !$this->input['isPost'])
		{
			$cache = $this->getCacheObject();
			$key   = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$data  = $cache->get(md5($key));
			if (empty($data))
			{
				$this->$action();
			}
			else
			{
				print $data;
			}
		}
		else
		{
			$this->$action();
		}
		if ($this->debug && isset($this->com('dbHook')->sqlLog))
		{
			print $this->com('dbHook')->sqlLog;
			print $this->com('dbHook')->getTime();
		}

		if(method_exists($this,'after')) $this->after();
	}


	/** 
	 +----------------------------------------------------------
	 * 数据验证
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  array   $data   待验证的数据(键值对)
	 * @param  array   $fields 要验证的字段
	 *
	 * @return array           返回错误信息、验证后的数据
	 +----------------------------------------------------------
	 */
	public function validate($data, $fields)
	{
		return $this->com('para')->validate($data, $fields);
	}


	/**
	 +----------------------------------------------------------
	 * 显示消息提示框(带提示信息+跳转)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $desc 消息文本
	 +----------------------------------------------------------
	 * @param  string  $url  跳转地址
	 +----------------------------------------------------------
	 */
	public final function redirect($desc, $url, $scripts = array())
	{
		$this->com('msg')->show($desc, $url, $scripts);
	}
}
?>