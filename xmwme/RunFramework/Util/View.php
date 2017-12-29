<?php
class View
{
	public  $tplDir  = null;        //模板根目录
	private $tplVar  = null;        //模板变量数组 

	//Desc:类的构造函数
	public function __construct()
	{
	}

	//Desc:设置模板变量
	public function set($key,$val)
	{
		$this->tplVar[$key] = $val;
	}

	//Desc:装载模板文件并显示
	public function display($file = null,$arr=array())
	{
		if($file)
		{
			$file = $this->tplDir .'/'.$file;
			if(!file_exists($file)) RunException::throwException("模板文件: $file 不存在!");
			if(isset($arr) && !empty($arr)){
			    foreach($arr as $key=>$val){
				$this->tplVar[$key] = $val;
			    }
			    extract($this->tplVar);
			}
			require_once($file);
		}
	}

	//Desc:装载模板文件并返回解析后的html
	public function fetch($file = null)
	{
		if($file)
		{
			$file = $this->tplDir .'/'.$file;
			if(!file_exists($file)) RunException::throwException("模板文件: $file 不存在!");
			if(is_array($this->tplVar) && !empty($this->tplVar)) extract($this->tplVar);
			ob_start();
			require_once($file);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
		return '';
	}

	//Desc:类的析构方法(负责资源的清理工作)
	public function __destruct()
	{
		$this->tplVar  = null;
		$this->tplDir  = null;
	}
}

function ob_gzip($content)
{
	$bool = isset($_SERVER["HTTP_ACCEPT_ENCODING"]) && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip") 
		    ? true
		    : false;
	if (!headers_sent() && extension_loaded("zlib") && $bool)
    {
        $content = gzencode($content,9);
       
        header("Content-Encoding: gzip");
        header("Vary: Accept-Encoding");
        header("Content-Length: ".strlen($content));
    }
    return $content;
}
?>