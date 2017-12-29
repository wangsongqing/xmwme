<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 服务工厂对象资源管理器(框架核心)
 +------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class Resource
{
	private $isCached   = false;             //是否缓存对象配置信息
	private $cacheDir   = 'Cache/Resource';  //对象资源缓存目录
	private $configFile = null;              //级联配置文件

	/**
	 +----------------------------------------------------------
	 * 类的构造子
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __construct()
	{
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
		}
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
		 $this->isCached   = null;
		 $this->cacheDir   = null;
		 $this->configFile = null;
	}

	/**
	 +----------------------------------------------------------
	 * 通过对象标识ID得到对象配置信息
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string $objId  
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getResource($objId)
	{
		$object = $this->isCached && $this->cacheDir != null && file_exists($this->cacheDir) ? $this->getCache($objId) : $this->find($objId);

		if(isset($object['ignore']) && $object['ignore'] && !file_exists($object['source'])) return false;

		if(!file_exists($object['source'])) 
		{
			RunException::throwException("$object[className] 对象所在类文件 $object[source] 不存在!");
		}

		if(isset($object['import']))
		{
			foreach($object['import'] as $file)
			{
				if(!file_exists($file))
				{
					RunException::throwException("$object[className] 对象所依赖的文件 $file 不存在!");
				}
			}
		}
		return $object;
	}

	/**
	 +----------------------------------------------------------
	 * 通过对象标识ID得到缓存对象配置信息(缓存不存在则创建之)
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $objId  对象标识ID
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	private function getCache($objId)
	{
		if(!file_exists($this->cacheDir.'/'.$objId.'.php'))
		{
			$object = $this->find($objId);
			$this->create($object); //构造对象标识ID的对象配置信息缓存
		}
		else
		{
			require($this->cacheDir.'/'.$objId.'.php');
		}
		return $object;
	}

	/**
	 +----------------------------------------------------------
	 * 通过对象标识ID扫描所有级联对象配置文件获取对象配置信息
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $objId  对象标识ID
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	private function find($objId)
	{
		if(!file_exists($this->configFile)) 
		{
			RunException::throwException("核心配置文件: $this->configFile 不存在!");
		}
		require($this->configFile);
		$scanedFile = null;
		foreach($source as $objFile)
		{
			if(empty($objFile['source']) || !file_exists($objFile['source'])) continue;
			require($objFile['source']);
			foreach($objects as $object)
			{
				if(isset($object['enable']) && !$object['enable'])
				{
					continue;
				}
				if($object['id'] == $objId)
				{
					return $object;
				}
			}
			$scanedFile = $scanedFile."\t".$objFile['source'];
		}
		$this->remove();
		RunException::throwException("对象配置参数: $objId 在配置文件 $scanedFile 中没有找到!");
	}

	/**
	 +----------------------------------------------------------
	 * 构造对象标识ID的对象配置信息缓存
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param array $object
	 +----------------------------------------------------------
	 */
	private function create($object)
	{
		$str = "<?php\r\n";
		foreach($object as $key=>$val)
		{
			if(is_array($object[$key]))
			{
				if($key == 'import')
				{
					foreach($object[$key] as $iKey=>$iVal)
					{
						$str .= "\$object['import']['$iKey'] = \"$iVal\";\r\n";
					}
				}
				
				if($key == 'property')
				{
					foreach($object[$key] as $pKey=>$pVal)
					{
						if($pKey == 'objRef' || $pKey == 'hookList')
						{
							foreach($pVal as $k=>$v) 
							{
								$str .= "\$object['property']['$pKey']['$k'] = \"$v\";\r\n";
							}
							continue;
						}
						if(is_array($pVal))
						{
							foreach($pVal as $k=>$v) 
							{
								$str .= "\$object['property']['$pKey']['$k'] = \"$v\";\r\n";
							}
						}
						else
						{
							$str .= "\$object['property']['$pKey'] = \"$pVal\";\r\n";
						}
					}
				}				
				$str .= "\r\n";
			}
			else
			{
				$str .= "\$object['$key'] = \"$val\";\r\n";
			}
		}
		$str .= "?>\r\n";
		$file = $this->cacheDir.'/'.$object['id'].'.php';
		file_put_contents($file,$str);
	}

	/**
	 +----------------------------------------------------------
	 * 清空对象配置信息缓存
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 */
	private function remove()
	{
		if(file_exists($this->cacheDir))
		{
			$handle = opendir($this->cacheDir);
			while($file = readdir($handle))
			{
				$file = $this->cacheDir . DIRECTORY_SEPARATOR . $file;
				if(!is_dir($file) && $file != '.' && $file != '..') @unlink($file);
			}
			closedir($handle);
		}
	}
}
?>