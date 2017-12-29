<?php
/**
 +---------------------------------------------------------------------------------------------------------------
 * Run Framework 控制器、模型的基类
 +---------------------------------------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +---------------------------------------------------------------------------------------------------------------
 */
class Object
{
	protected $cacheId    = 'mem';
	protected $expire     = 300;              //过期时间(5分钟)
	protected $cachePath  = 'Resource/Cache'; //php文件缓存默认路径

	/**
	 +----------------------------------------------------------
	 * 加载model对象
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param objId  $objId  对象标识(标签)
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */	
	public function import($objId = null)
	{
		return $objId ? $this->mf->$objId : null;
	}


	/**
	 +----------------------------------------------------------
	 * 获取组件对象
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  objId   $objId  对象标识(标签)
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */	
	public final function com($objId = null)
	{
		return $objId ? $this->com->$objId : null;
	}


	/**
	 +----------------------------------------------------------
	 * 创建一个缓存对象
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */	
	public final function getCacheObject()
	{
		$objId     = $this->cacheId ? $this->cacheId : 'fc';
		$cache     = $this->com->$objId;
		$className = get_class($cache);
		$interface = class_implements($className);

		if ( in_array('ICache', $interface) )
		{
			if ($objId == 'fc') $cache->path = $this->cachePath;
			$cache->expire = $this->expire;
			return $cache;
		}
		else
		{
			RunException::throwException("类 $className 未实现 ICache 接口!");
		}
	}
}
?>