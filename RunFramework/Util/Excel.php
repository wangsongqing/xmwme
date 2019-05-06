<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework excel操作类
 +------------------------------------------------------------------------------
 * @date    17-06
 * @author Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class Excel
{
	private $space     = 2;
	private $tab       = 3; //制表符个数
	private $title     = '报表信息标题';
	private $fileName  = 'test.xls';   //输出的报表文件名
	private $hasHeader = true;
	private $initEncode = 'utf-8';     //初始编码
	private $destEncode = 'GB2312//IGNORE';    //转化的目标编码

	/**
	 +----------------------------------------------------------
	 * 构造函数
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
	 * 输出报表
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 */
	public function export($dataRows)
	{
		header("Content-type: text/csv");
		header("Content-Disposition:filename=$this->fileName");
		if($this->hasHeader) print $this->createHeader();
		print $this->createRow($dataRows);
	}

	/**
	 +----------------------------------------------------------
	 * 创建报表标题
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	private function createHeader()
	{
		$header = str_repeat(str_repeat(",",$this->space),$this->tab).$this->title."\r\n";
		return $header;
	}

	/**
	 +----------------------------------------------------------
	 * 创建报表数据行
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 */
	private function createRow($dataRows)
	{
		$row = "";
		foreach($dataRows as $rowKey=>$rowVal)
		{
			if(is_array($dataRows[$rowKey]))
			{
				foreach($dataRows[$rowKey] as $val)
				{
					$val = str_replace(",", "，", $val);
					$val = iconv($this->initEncode, $this->destEncode,$val);
					$row .= $val . str_repeat(",", $this->space);
				}
				$row .= "\r\n";
			}
			else
			{
				foreach($dataRows as $key=>$val)
				{
					$val = str_replace(",", "，", $val);
					$val = iconv($this->initEncode, $this->destEncode, $val);
					$row .= $val . str_repeat(",", $this->space);
				}
				$row .= "\r\n";
				break ;
			}
		}
		return $row;
	}

	public function replaceData($str)
	{
	    $str = str_replace("\"", "\"\"", $str);
	    $str = "\"" . $str . "\"";

	    return $str;
	}

	/**
	 +----------------------------------------------------------
	 * 显示报表信息
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 */
	public function displayExcelInfo()
	{
		if(!file_exists($this->fileName)) die("目标文件: $this->fileName 不存在!");

		$data = file($this->fileName);
		foreach($data as $v)
		{
			print $v."<br>";
		}
	}
}
?>