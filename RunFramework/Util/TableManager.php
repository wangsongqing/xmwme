<?php
/**
 +--------------------------------------------------------------------------------------------
 * Run Framework 表管理器，方便连接多数据库，在数据库之间进行切换
 +--------------------------------------------------------------------------------------------
 * @date    2017-6
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +--------------------------------------------------------------------------------------------
 */
class TableManager
{
	public $tbl        = array();
	public $configFile = null;


	/**
	 +----------------------------------------------------------
	 * 装载表配置文件
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public function load()
	{
		if ($this->configFile && file_exists($this->configFile))
		{
			require($this->configFile);
			
			$this->tbl = $tbl;
		}
	}
}
?>