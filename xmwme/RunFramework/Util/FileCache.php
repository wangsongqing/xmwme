<?php 

class FileCache implements ICache{
	const DIRECTORY_SEPARATOR = '/';
	const DIR_WRITE_MODE = 0777;
	const FOPEN_WRITE_CREATE = 'ab';
 	/**
     * 缓存路径
     *
     * @access private
     * @var string
     */
 	private $_cache_path;
 
 	/**
     * 解析函数，设置缓存过期实践和存储路径
     * 
     * @access public
     * @return void
     */
	 public function __construct($cache_path='./fileCache/')
	 {
	  	$this->_cache_path = $cache_path;
	 }
 
 	/**
     * 缓存文件名
     * 
     * @access public
     * @param  string $key
     * @return void
     */
 	private function _file($key)
	{ 	
		/*检查目录是否存在，不存在就创建*/
		if (! file_exists ($this->_cache_path)) {
        	mkdir ($this->_cache_path, self::DIR_WRITE_MODE, true );
		}
		return $this->_cache_path . md5($key);
	}
 
 	/**
     * 设置缓存
     * 
     * @access public
     * @param  string $key 缓存的唯一键
     * @param  string $data 缓存的内容
     * @param  int    $time 缓存保存时间
     * @return bool
     */
 	public function set($key, $data,$time=3600)
 	{	
 		$_arr = array('data'=>$data,'_time'=>$time);
	  	$value = serialize($_arr);

	  	$file = $this->_file($key);
	    return $this->write_file($file, $value);
 	}
 
 	/**
     * 获取缓存
     * 
     * @access public
     * @param  string $key 缓存的唯一键
     * @return mixed
     */
 	public function get($key)
 	{
  		$file = $this->_file($key);
 	 	/** 文件不存在或目录不可写 */
  		if (!file_exists($file) || !$this->is_really_writable($file))
  		{
   			return false;
  		}else{
  			/*否则就读取文件里面的内容*/
  			$data = $this->read_file($file);
  			if($data===false){ return false; }
  			$unData = unserialize($data);
  		}
  
  		/** 缓存没有过期，仍然可用 */
  		if ( time() < (filemtime($file) + $unData['_time']) ) 
  		{
 			if(false !== $data)
   			{
    			return $unData['data'];
   			}
   				return false;
  		}
  
  		//缓存过期，删除之 
  		@unlink($file);
  		return false;
  	}
  
	function read_file($file){
		if (!file_exists($file)){
			return false;
		}
		if (function_exists('file_get_contents')){
			return file_get_contents($file);  
		}
		if (!$fp = @fopen($file, FOPEN_READ)){
			return false;
		}

		flock($fp, LOCK_SH);//读取之前加上共享锁

		$data = '';
		if (filesize($file) > 0)
		{
		$data =& fread($fp, filesize($file));
		}
		flock($fp, LOCK_UN);//释放锁
		fclose($fp);
		return $data;
 	}
 
 	function write_file($path, $data){
		if ( ! $fp = @fopen($path, 'w')){
			return false;
		}
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp); 
		return true;
	}


	function is_really_writable($file){ 
		//判断各平台判断文件是否有写入权限 linux服务器
		if (self::DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == false){
			return is_writable($file);
		}

		if (is_dir($file)){
			$file = rtrim($file, '/').'/'.md5(rand(1,100));
			if (($fp = @fopen($file, self::FOPEN_WRITE_CREATE)) === false){
				return false;
			}
			fclose($fp);
			@chmod($file, self::DIR_WRITE_MODE);
			@unlink($file);
			return true;
		}elseif (($fp = @fopen($file, self::FOPEN_WRITE_CREATE)) === false){
			return false;
		}

		fclose($fp);
		return true;
	}
	
	/**
	 *删除缓存数据
	 */
	public function remove($key){
	    if(empty($key)){
		return false;
	    }
	    $fileName = $this->_cache_path.md5($key);
	    if(file_exists($fileName)){
		    if(unlink($fileName)){
			    return true;
		    }
	    }
	    return false;
	}

	/**
	 *清除所有缓存数据
	 */
	public function clear(){
		$dirName = $this->_cache_path;
		if(file_exists($dirName) && $handle=opendir($dirName)){
	        while(false!==($item = readdir($handle))){
	            if($item!= "." && $item != ".."){
	                if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
	                    clearData($dirName.'/'.$item);
	                }else{
	                    if(unlink($dirName.'/'.$item)){
	                        return true;
	                    }
	                }
	            }
	        }
	        closedir( $handle);
    	}
    	return '文件夹不存在';
	}
}

/*
* 文件缓存类的使用方法
* set方法如果不写时间默认是 3600s
* $cache->clearData();是清空所有数据
* new FileCache(); 如果在new对象的时候不写文件保存地址则默认保存在当前目录下的 fileCache 文件夹下面
* 当然你可以根据你的需求来下保存路径 例如：new FileCache('../resorce/cache/');

$cache = new FileCache();

if($cache->get('addr')){
	echo $cache->get('addr').' is cache data';
}else{
	$cache->set('addr','beijing',60);
	echo $cache->get('addr').' is first data';
}
*/