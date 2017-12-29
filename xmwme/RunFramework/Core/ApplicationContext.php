<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 组件容器
 +------------------------------------------------------------------------------
 * @date    2017-05
 * @author  Jimmy <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class ApplicationContext
{
	private $objTable = null;
	private $factory  = null;

	/**
	 +----------------------------------------------------------
	 * 类的构造方法
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __construct($factory)
	{
		 $this->factory = $factory;
	}

	/**
	 +----------------------------------------------------------
	 * 类的析构方法(负责资源的清理工作)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __destruct()
	{
		 $this->objTable = null;
		 $this->factory  = null;
	}

	/**
	 +----------------------------------------------------------
	 * 属性访问器(读)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param string $name   类标签 
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */
	public function __get($name)
	{
		return $name ? $this->getObject($name) : null;
	}

	private function getObject($name)
	{
		if(isset($this->objTable[$name]) && is_object($this->objTable[$name]))
		{
			return $this->objTable[$name];
		}
		else
		{
			$this->objTable[$name] = $this->factory->getObject($name);
			
			return $this->objTable[$name];
		}
	}
}
?>