<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 模型工厂
 +------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class ModelFactory
{
	public  $com      = null;     //组件对象容器
	public  $path     = null;     //模型类存放根路径
	public  $models   = null;     //把视图对模型的引用关系
	public  $input    = null;     //用户输入
	private $oldPath  = null;     //模型类初始存放根路径
	private $objTable = array();  //模型对象缓存

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
	 * 类的析构方法(负责资源的清理工作)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */	
	public function __destruct()
	{
		 foreach($this->objTable as $k=>$v)  $this->objTable[$k] = null;
		 $this->com      = null;
		 $this->path     = null;
		 $this->objTable = null;
		 $this->models   = null;
		 $this->input    = null;
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
		if(!is_array($this->models) || $this->models == null) return null;
		if(isset($this->models[$name]))
		{
			return $this->getObject($name);
		}
		else
		{
			return null;
		}
	}

	private function getObject($name)
	{
		if(isset($this->objTable[$name]) && is_object($this->objTable[$name]))
		{
			return $this->objTable[$name];
		}
		else
		{
			if(is_array($this->models[$name]))
			{
				if(!isset($this->models[$name]['source']) || !isset($this->models[$name]['ref'])) 
				{
					RunException::throwException("控制器依赖的模型配置出错!");
				}
				if(!is_array($this->models[$name]['ref']))
				{
					RunException::throwException($this->models[$name]['source']."依赖的模型配置出错!");
				}
				$model  = $this->models[$name]['source'].'Model';
				$object =  $this->create($model);
				foreach($this->models[$name]['ref'] as $key=>$val)
				{
					$val .= 'Model';
					$object->$key =  $this->create($val);
				}
			}
			else
			{
				//记录模型类初始存放根路径
				if ( !strpos($this->path, '/') === FALSE )
				{
					$this->oldPath = $this->path;
				}

				if ( !(strpos($this->models[$name], '/') === FALSE) )
				{
					$bool       = ltrim($this->models[$name], '/') == $this->models[$name] ? true : false;
					$temp       = explode('/', $this->models[$name]);
					$model      = $temp[count($temp)-1].'Model';
					unset($temp[count($temp)-1]);
					$this->path = implode('/', $temp);
					$object     = $this->create($model);
				}
				else
				{
					$this->path = $this->oldPath;
					$model      = $this->models[$name].'Model';
					$object     = $this->create($model);
				}
			}
			$this->objTable[$name] = $object;

			return $this->objTable[$name];
		}		
	}

	/**
	 +----------------------------------------------------------
	 * 构造模型对象并初始化
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $model  模型对象类名  
	 +----------------------------------------------------------
	 */
	private function create($model)
	{
		$modelFile = substr(strtolower($model), 0, strlen($model)-5).'.model';
		$reqFile   = $this->findFile($modelFile);
                //if($reqFile == null){return null;} //如果需要不建立model文件就可以访问数据库就需要做这一步，现在先不做处理 jimmy
		if($reqFile == null) RunException::throwException("找不到模型类文件 $modelFile.php");
		require_once($reqFile);
		if(!class_exists($model)) RunException::throwException('找不到Model类 '.$model);
		$objModel = new $model();
		//把组件容器传递给模型
		$objModel->com = $this->com;
		//把模型工厂传递给模型对象
		$objModel->mf = $this;
		//把用户输入传递给模型
		$objModel->input = $this->input;
		
		return $objModel;
	}

	/**
	 +----------------------------------------------------------
	 * 遍历模型层目录,查找请求的目标文件
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param string $model      模型类名  
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	private function findFile($model)
	{
		$reqFile = $this->path.'/'.$model.'.php';
		if(!file_exists($reqFile)) 
		    $reqFile = str_replace('/www/api/', '../api/', $reqFile);
		//在模型层根目录查找请求的目标文件
		if ( file_exists($reqFile) ) 
		{
			$this->path = $this->oldPath;

			return $reqFile;
		}

		if(!file_exists($this->path)) RunException::throwException('找不到Model目录 '.$this->path);

		//遍历模型层目录下的所有子目录,查找请求的目标文件
		if(false !== ($dh = opendir($this->path)))
		{
			while(false !== ($file = readdir($dh))) 
			{
				$path = $this->path.'/'.$file;
				if (is_dir($path)) 
				{
					$reqFile = $path.'/'.$model.'.php';
					if(file_exists($reqFile)) return $reqFile;
				} 
			}
		}
		return null;
	}
}
?>