<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework mysql数据库访问接口 -- 添加事物和分布式事务的操作
 +------------------------------------------------------------------------------
 * @date    17-04
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class RunMysql implements IDataSource
{
	private $connectId  = null;//当前连接ID
	private $transConnectId = null;//内部事务连接ID
	private $XATransConnectId = null;//分布式事务连接ID
	private $queryId    = null;//操作资源id
	public  $dbId       = null;//当前数据库id
	public  $configFile = null;//连接数据库配置文件
	public  $persistent = 0;//是否持续连接，默认否
	public  $page = null;//分页对象
	public  $hook = null;//钩子
	public  $offset = null;//偏移值
	
	/**
	 +----------------------------------------------------------
	 * 类的构造函数
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
		 $this->close();
		 $this->page       = null;
		 $this->hook       = null;
		 $this->dbId       = null;
		 $this->queryId    = null;
		 $this->persistent = null;
		 $this->configFile = null;
		 $this->offset	   = null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 打开数据库连接
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 */
	private function connect()
	{
		if($this->connectId == null)
		{
			$this->connectId = $this->_connect();
		}
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 建立连接
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 */
	private function _connect()
	{	
        if(!file_exists($this->configFile)) RunException::throwException("数据库配置文件：".$this->configFile."不存在!");
        require($this->configFile);
        $connectId = ($this->persistent == 0) ? mysql_connect($host,$user,$password) : mysql_pconnect($host,$user,$password);
        if($connectId == null) RunException::throwException("数据库连接失败");
        if(!mysql_select_db($db)) RunException::throwException("不能选择指定的数据库: $db");
        if(isset($encode)) {
            mysql_query("set names $encode");
        }
		//设置偏移值
		if ($this->offset) {
			mysql_query("set SESSION auto_increment_offset = ".$this->offset);
		}
        $host = $user = $password = $db = $encode = null;
        return $connectId;
	}
	
	/**
	 +----------------------------------------------------------
	 * 关闭数据库连接
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 */
	public function close()
	{ 
		if($this->connectId != null)
		{
			if($this->persistent == 0) mysql_close($this->connectId);
			$this->connectId = null;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 释放操作资源
	 +----------------------------------------------------------
	 * @access private
	 +----------------------------------------------------------
	 */		
	private function free()
	{
		if($this->queryId != null)
		{
			mysql_free_result($this->queryId);
			$this->queryId = null;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 执行SQL语句
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $sql  sql指令
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function query($sql)
	{//echo '<br>'.$this->configFile;
		if(empty($sql)) return false;
		if (is_object($this->hook) && method_exists($this->hook, "work")) 
		{
			$this->hook->sql = $sql;
			$this->hook->work();
		}
		//echo '----';var_dump($this->connectId);echo '<br>';
		if($this->connectId == null || !isset($this->connectId)) $this->connect();
		$this->queryId = mysql_query($sql,$this->connectId);
		if(!$this->queryId) $this->error("SQL语法错误:".$sql);
		
		return $this->queryId;
	}

	/**
	 +----------------------------------------------------------
	 * 返回操作所影响的行数(INSERT、UPDATE 或 DELETE)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return integer
	 +----------------------------------------------------------
	 */	
	public function getAffected()
	{
		 if($this->connectId == null) return 0;

		 return mysql_affected_rows($this->connectId);
	}

	/**
	 +----------------------------------------------------------
	 * 返回最后一次使用 INSERT 指令的 ID
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return integer
	 +----------------------------------------------------------
	 */	
	public function getLastInsId()
	{
		if($this->connectId != null)
		{
			return mysql_insert_id($this->connectId);
		}
		return 0;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获得一条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string  $sql  SQL指令
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */	
	public function getRow($sql)
	{
		 $this->queryId = $this->query($sql);
		 $row = mysql_fetch_array($this->queryId, MYSQL_ASSOC);
		 $this->free();

		 return $row;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获得多条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string  $sql  SQL指令
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getRows($sql)
	{
		$dataRow = array();
		$this->queryId = $this->query($sql);
		while($data = mysql_fetch_array($this->queryId,MYSQL_ASSOC))
		{
			$dataRow[] = $data;
		}
		$this->free();
		
		return $dataRow;
	}

	/**
	 +----------------------------------------------------------
	 * 获得多条查询数据(带分页条)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $query    SQL指令
	 +----------------------------------------------------------
	 * @param  int    $pageRows 每页显示的记录条数
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getPageRows($query, $pageRows =20, $point = 10, $style = 'on')
	{
		if(!is_object($this->page)) return array();
		$page     = isset($_GET['page']) ? intval($_GET['page']) : 0;
		$page     = $page > 1000000 ? 1 : $page;
		$pageRows = $pageRows > 0 ? $pageRows : 20;
		$sqlCount = preg_replace("|SELECT.*?FROM([\s])|i","SELECT COUNT(*) as total FROM$1", $query, 1);
		$row      = $this->getRow($sqlCount);
		$total    = isset($row['total']) ? $row['total'] : 0 ;

		// group count
		if(preg_match('!(GROUP[[:space:]]+BY|HAVING|SELECT[[:space:]]+DISTINCT)[[:space:]]+!is', $sqlCount))
		{
			$sqlCount = preg_replace('!(order[[:space:]]+BY)[[:space:]]+.*!is','', $query, 1);
			$sqlCount = preg_replace("|SELECT.*?FROM([\s])|i","SELECT COUNT(*) as total FROM$1", $sqlCount, 1);
			$rows     = $this->getRows($sqlCount);
			$total    = empty($rows) ? 0 : count($rows);
		}

		//计算分页的偏移量
		$pageId = $page;
		$offset = ($pageId-1)* $pageRows;
		$offset = ($offset < 0) ? 0 : $offset; 
		$query .= ' LIMIT '.$offset.','. $pageRows;
		$data['pageBar'] = $this->page->get($row['total'], $pageRows, $point, $style);
		$data['record']	 = $this->getRows($query);
		$data['query']   = $query;
		return $data;
	}


	/**
	 +----------------------------------------------------------
	 * 添加数据(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table  表名
	 +----------------------------------------------------------
	 * @param  array   $arr    插入的数据(键值对)
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function insert($table, $arr = array() )
	{
		$field = $value = "";
		if ( !empty($arr) && is_array($arr) )
		{
			foreach($arr as $key => $val)
			{
				$val      = addslashes($val);
				$fields[] = $key;
				$values[] = "'$val'";
			}
			$field  = implode(',', $fields);
			$value  = implode(',', $values);
			$sql    = "insert into $table($field) values($value)";
			return $this->query($sql);
		}
		return false;
	}

	/**
	 +----------------------------------------------------------
	 * 更新数据(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table  表名
	 +----------------------------------------------------------
	 * @param  array   $arr    更新的数据(键值对)
	 +----------------------------------------------------------
	 * @param  mixed   $where  条件
	 +----------------------------------------------------------
	 * @param  array   $add_self  字段加减
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function update($table, $arr = array(), $filter = '', $add_self = array() )
	{		

		if ( !empty($arr) && is_array($arr) )
		{
			foreach($arr as $key => $val)
			{
				$val      = addslashes($val);
				if ( array_key_exists($key, $add_self) ) {
					if ($add_self[$key] == 'add') {
						$fields[] = "$key=$key + $val";
					} else if ($add_self[$key] == 'minus') {
						$fields[] = "$key=$key - $val";
					} else {
						return false;	
					}
				} else {
					$fields[] = "$key='$val'";
				}
			}
			$field = implode(',', $fields);
			$sql   = "UPDATE $table SET $field";
		}
		if ( !empty($filter) && is_array($filter) )
		{ 
			foreach($filter as $key => $val)
			{
				$whereArr[] = "$key='$val'";
			}
			$sql .= ' where '. implode(' AND ', $whereArr);
		}
		else
		{   
			if (empty($filter)) {
				return false;	
			} else {
				$sql .=  ' where '.$filter;
			}
		}
		return $this->query($sql);
	}


	/**
	 +----------------------------------------------------------
	 * 删除数据(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table  表名
	 +----------------------------------------------------------
	 * @param  mixed   $where  条件
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function delete($table,$where='')
	{
		$sql = "delete from {$table} ";
		if(!empty($where))
		{
			if(is_array($where))
			{
				$sql .= " where ";
				foreach($where as $wFiled => $wValue) $sql .= $wFiled . " = " . $wValue." AND ";
				$sql = trim($sql," AND ");
			}
			else
			{
				$sql .= " where $where";
			}
			return $this->query($sql);
		}
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 开启事物(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  int  $isXA  是否开启分布式事务
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function startTrans()
	{
	    $result = $this->commit();
	    if(!$result) {
	        $this->error("开启事务失败！");
	        return false;
	    }
	    $this->query('SET AUTOCOMMIT=0');                                    
	    $this->query('START TRANSACTION');                                    //开启事务
        return ;
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 分布式事物准备(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function prepare($XID) {
	    $connectId = $this->XATransConnectId;
        mysql_query("XA END '$XID'", $connectId);                                        //结束事务
        mysql_query("XA PREPARE '$XID'", $connectId);                                    //消息提示
	    return ;
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 事物提交(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function commit() {
	    $result = $this->query('COMMIT');                                         //提交事务
        if(!$result){return false; }
        $this->query('SET AUTOCOMMIT=1');
	    return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 事物回滚(辅助方法)
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function rollback() {
        $result = $this->query('ROLLBACK');                                         //回滚
        if(!$result) return false; 
        $this->query('SET AUTOCOMMIT=1');
	    return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 数据库错误信息
	 * 并显示当前的SQL语句
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 */	
	private function error($msg)
	{
		 $content = "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
		 $content .= "<b>Mysql error description</b>: ".mysql_error($this->connectId)."\n<br>";
		 $content .= "<b>Mysql error number</b>: ".mysql_errno($this->connectId)."\n<br>";
		 $content .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br>";
		 $content .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br>";
		 $content .= "<b>Referer</b>: ".getenv("HTTP_REFERER")."\n<br><br>";
		 RunException::throwException($content);		
	}
}
?>