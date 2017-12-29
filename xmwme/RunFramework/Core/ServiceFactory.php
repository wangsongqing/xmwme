<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 服务工厂(框架核心)
 +------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class ServiceFactory
{
	public  $table      = array();  //对象表(把构造的对象注册到对象表)
	private $resource   = null;     //资源加载器
	private $isCached   = false;    //是否缓存对象配置信息
	private $cacheDir   = null;     //对象资源缓存目录
	private $configFile = null;     //级联配置文件

	/**
	 +----------------------------------------------------------
	 * 类的构造子
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __construct()
	{
		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Resource.php');
		$this->resource = new Resource();
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
		 foreach($this->table as $k=>$v) $this->table[$k] = null;
		 $this->table      = null;
		 $this->resource   = null;
		 $this->isCached   = null;
		 $this->cacheDir   = null;
		 $this->configFile = null;
	}

	/**
	 +----------------------------------------------------------
	 * 属性访问器(写)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */
	public function __set($name,$value)
	{
		if(property_exists($this,$name))
		{
			$this->$name = $value;
			$this->resource->$name = $value;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 得到一个对象
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string $objId  
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */
	public function getObject($objId = null)
	{
		if($objId == null) 
		{
			RunException::throwException("对象构造失败,缺少配置参数!");
		}
		$object = $this->dispatch($objId);
		if(!is_object($object))
		{
			RunException::throwException("配置参数: $objId 构造对象失败!");
		}
		
		return $object;
	}

	/**
	 +----------------------------------------------------------
	 * 通过对象标识分派对象
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $objId  
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */
	private function dispatch($objId)
	{
		if(!is_array($objId) && isset($this->table[$objId])) 
		{
			return $this->table[$objId];  //如果对象表中存在所需对象
		}
		else
		{
			$object = $this->resource->getResource($objId);
		}

		if($object === false) 
		{
			return new stdClass();
		}
		else
		{
			return $this->create($object);
		}
	}

	/**
	 +----------------------------------------------------------
	 * 通过对象配置信息构造对象
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param array $config  
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */
	private function create($config)
	{
		if(!file_exists($config['source']))
		{
			RunException::throwException($config['source']."文件不存在,对象构造失败!");
		}
		
		//对接口,抽象类的支持
		if(isset($config['import']) && is_array($config['import']))
		{
			foreach($config['import'] as $file)
			{
				if(file_exists($file)) require_once($file);
			}
		}
		require_once($config['source']);
		if(!class_exists($config['className'])) RunException::throwException("未定义的类 $config[className]");
		$object = new $config['className']($this);
		
		//对属性赋值
		if(isset($config['property']))
		{
			foreach($config['property'] as $propertyName => $propertyVal)
			{
				if($propertyName != 'objRef') $object->$propertyName = $propertyVal;
			}
		}
		
		//执行初始化方法
		if(isset($config['initMethod']) && method_exists($object,$config['initMethod'])) $object->$config['initMethod']();
		
		//构造依赖对象
		if(isset($config['property']['objRef']))
		{
			foreach($config['property']['objRef'] as $key=>$val)
			{
				$object->$key = $this->getObject($val);
				$this->register($val,$object->$key);
			}
		}

		//动态植入代码(钩子链)
		if(isset($config['property']['hookList']))
		{
			foreach($config['property']['hookList'] as $key=>$val)
			{
				$hook = $this->getObject($val);
				$this->register($val, $hook);
				if(is_object($hook) && method_exists($hook, 'work')) $hook->work(); //执行钩子代码
				$hook = null;
			}
		}
		$this->register($config['id'],$object);
		
		return $object; 
	}

	/**
	 +----------------------------------------------------------
	 * 注册对象
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $objId、object $object
	 +----------------------------------------------------------
	 */
	private function register($objId,$object)
	{
		$this->table[$objId] = $object;
	}
}
?>