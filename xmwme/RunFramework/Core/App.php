<?php
class App
{
	public  $path            = null;   //框架路径
	public  $mapPath         = null;   //影射的新路径
	public  $rootPath        = null;   //应用程序根路径
	public  $isCached        = false;  //是否缓存对象资源
	public  $cacheDir        = null;   //对象资源缓存目录
	public  $module          = null;   //默认控制器
	public  $exceptionModule = null;   //发生异常时的控制器
	private $com             = null;   //组件容器
	private $factory         = null;   //服务工厂
	private $dispatcher      = null;   //调度器


	//+------------------------------------------------------------------------------------------------------
	  //Desc:类的构造子
	public function __construct()
	{
	}

	//+------------------------------------------------------------------------------------------------------
	  //Desc:类的析构方法(负责资源的清理工作)
	public function __destruct()
	{
		$this->path       = null;
		$this->mapPath    = null;
		$this->rootPath   = null;
		$this->isCached   = null;
		$this->cacheDir   = null;
		$this->com        = null;
		$this->factory    = null;
		$this->dispatcher = null;
	}

	//+------------------------------------------------------------------------------------------------------
	  //Desc:加载框架核心库并初始化
	public function init()
	{
		set_error_handler(array(&$this,"appError"));                               //设定错误处理
		set_exception_handler(array(&$this,"appException"));                       //设定异常处理
		require($this->path.'/Core/RunException.php');                          //载入系统异常类
		require($this->path.'/Core/ServiceFactory.php');                           //载入服务工厂
		$this->factory             = new ServiceFactory();                         //构造服务工厂对象
		$this->factory->isCached   = $this->isCached;                              //是否缓存对象资源
		$this->factory->cacheDir   = $this->cacheDir;                              //对象资源缓存目录
		$this->factory->configFile = $this->path.'/Config/Ioc/map.config.php';     //指定级联配置文件
		$this->factory->getObject('loader')->load();                               //加载框架核心文件	
		$this->com = $this->factory->getObject('com');                             //构造组件容器
		$this->mf  = $this->factory->getObject('mf');                              //构造模型工厂
		$this->mf->com = $this->com;                                               //把组件容器传递给模型工厂
		$this->dispatcher = $this->factory->getObject('dispatcher');               //构造调度器对象
		$this->dispatcher->mapPath         = $this->mapPath;                       //影射的新路径
		$this->dispatcher->rootPath        = $this->rootPath;                      //应用程序根路径
		$this->dispatcher->module          = $this->module;                        //指定默认控制器
		$this->dispatcher->exceptionModule = $this->exceptionModule;               //指定发生异常时的控制器(如找不到请求的控制器)  
	}

	//+------------------------------------------------------------------------------------------------------
	  //Desc:执行应用程序
	public function execute()
	{
		//解析URL参数,获取请求信息
		$this->dispatcher->param = $this->com->in->dataInput;
		$this->dispatcher->parseReq();
		$moduleFile = $this->dispatcher->getFile();
		$module     = $this->dispatcher->getModule();
		$action     = $this->dispatcher->getAction(); 
		$input      = $this->dispatcher->getInput(); 

		//构造控制器实例并初始化
		require_once($moduleFile);                                                            
		if(!class_exists($module)) RunException::throwException("控制器 $module 未找到!");                   
		$instance = new $module();
		$instance->com = $this->com;
		$instance->input = $input;
		if(isset($instance->models)) $this->mf->models = $instance->models;
		$this->mf->input = $input;
		$instance->mf = $this->mf;

		//呼叫控制器执行操作
		$instance->call($action);      
		$moduleFile = $module = $action = $instance = null;                                       
	}

	//+------------------------------------------------------------------------------------------------------
	  //Desc:外部调用的快捷入口
	public function run()
	{
		$this->init();
		$this->execute();
	}

	//+------------------------------------------------------------------------------------------------------
	  //Desc:显示应用程序中出现的错误
	public function appError($errno, $errstr, $errfile, $errline)
	{
		$error = "错误号: $errno <br>";
		$error .="描  述: $errstr <br>";
		$error .= "所在文件: ".basename($errfile)."<br>";
		$error .= "所在行数: 第 $errline 行<br>";
		RunException::throwException($error);
	}
	
	//+------------------------------------------------------------------------------------------------------
	  //Desc:显示应用程序中出现的异常
	public function appException($e)
	{
		RunException::throwException($e->__toString());
	}
}
?>