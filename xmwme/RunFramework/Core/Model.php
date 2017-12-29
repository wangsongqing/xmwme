<?php
/**
 +--------------------------------------------------------------------------------------------
 * Run Framework 所有model的基类
 +--------------------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +--------------------------------------------------------------------------------------------
 */
abstract class Model extends Object
{
	public $db          = null;
	public $dbType      = 'mysql';
	public $table       = '';
	public $cached      = false;
	/**
	 * 查询数据的版本key
	 */
	public $revisionKey = '';


	/**
	 +----------------------------------------------------------
	 * 创建一个数据库对象
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return object
	 +----------------------------------------------------------
	 */	
	public final function createDbObject()
	{
		$dbType   = $this->dbType ? $this->dbType : 'mysql';
		$this->db = $this->com($dbType);
	}


	/**
	 +----------------------------------------------------------
	 * 根据key向表管理器获取表名、切换当前连接的数据库
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $key   表 key
	 +----------------------------------------------------------
	 * @param  int    $slice 子表数
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	public function getTable($key, $slice = 0, $readonly = 0)
	{
		$this->createDbObject();
		$tbl = $this->com('dt')->tbl;
		if (!isset($tbl[$key])) 
		{
			RunException::throwException("key: $key 对应的数据表不存在!");
		}
		/*if ( $slice > 0) { //此处是为数据库集群设计的，当你的数据量小时就不需要关心这块，当你的数据量大的时候可以关注一下，到时候可以邮箱和我探讨
		    $databaseId	  =  $slice%200;
		}*/
		$this->db->configFile = $tbl[$key]['configFile'];
		$this->table = $tbl[$key]['name'];
		return $this->table;
		
		RunException::throwException("key: $key 对应的数据表 ".$tbl[$key]['name'].$slice."不存在!");
	}


	/**
	 +----------------------------------------------------------
	 * 更新查询数据的版本号
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public final function revision()
	{ 
		if ( !empty($this->revisionKey) && is_array($this->revisionKey) )
		{
			$cache = $this->getCacheObject();
			foreach ($this->revisionKey as $val)
			{
				$cache->set($this->table.$val, time(), 360000);
			}
		}
		$this->revisionKey = null;
	}


	/**
	 +----------------------------------------------------------
	 * 构造缓存key
	 +----------------------------------------------------------
	 * @access private 
	 +----------------------------------------------------------
	 * @param  string  $sql          SQL指令
	 +----------------------------------------------------------
	 * @param  string  $revisionKey  SQL查询的版本key
	 +----------------------------------------------------------
	 * @param  string  $cache        cache
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */	
	private function createKey($sql, $revisionKey, $cache)
	{
		// 获取当前查询的版本号
		$revision = empty($revisionKey) ? '' : $cache->get($this->table.$revisionKey);
		$sql      = strtolower(trim($sql));
		
		//构建版本号
		if ( empty($revision) && !empty($revisionKey) )
		{
			$revision = time();
			$cache->set($this->table.$revisionKey, $revision, 360000);
		}
		$dataKey = empty($revisionKey) ? $sql : $sql.$revision;

		return $dataKey;
	}


	/**
	 +----------------------------------------------------------
	 * 过滤sql(为缓存key服务)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $sql     sql指令
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */	
	public final function filter($sql)
	{
		$sql = str_replace('"', "'", trim($sql));
		$sql = preg_replace("/([\s]+)?=([\s]+)?/", "=", $sql);
		$sql = preg_replace("/[\s]{2,}/", " ", $sql);
		$sql = preg_replace("/=(\d+)/", "='$1'", $sql);
		
		return $sql;
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
	public final function query($sql)
	{
		$this->createDbObject();

		return $this->db->query($sql);
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
	public final function getAffected()
	{
		$this->createDbObject();

		return $this->db->getAffected();
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
	public final function getLastInsId()
	{
		$this->createDbObject();
		 
		return $this->db->getLastInsId();
	}
	
	/**
	 +----------------------------------------------------------
	 * 获得一条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string  $sql          SQL指令
	 +----------------------------------------------------------
	 * @param string  $revisionKey  SQL查询的版本key
	 +----------------------------------------------------------
	 * @param  int    $expire       过期时间
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */	
	public final function getRow($sql, $revisionKey = '', $expire = 0)
	{
		if ($this->cached)
		{
			$sql     = $this->filter($sql);
			$cache   = $this->getCacheObject();
			$dataKey = $this->createKey($sql, $revisionKey, $cache);
			$data    = $cache->get($dataKey);
			if (!empty($data))
			{
				return $data;
			}
			else
			{
				$this->createDbObject();
				$data = $this->db->getRow($sql);
				$cache->set($dataKey, $data, $expire);
				return $data;
			}
		}
		else
		{
			$this->createDbObject();

			return $this->db->getRow($sql);
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 获得多条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param string  $sql          SQL指令
	 +----------------------------------------------------------
	 * @param string  $revisionKey  SQL查询的版本key
	 +----------------------------------------------------------
	 * @param  int    $expire       过期时间
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public final function getRows($sql, $revisionKey = '', $expire = 0)
	{
		if ($this->cached)
		{
			$sql     = $this->filter($sql);
			$cache   = $this->getCacheObject();
			$dataKey = $this->createKey($sql, $revisionKey, $cache);
			$data    = $cache->get($dataKey);

			if (!empty($data))
			{
				return $data;
			}
			else
			{
				$this->createDbObject();
				$data = $this->db->getRows($sql);
				$cache->set($dataKey, $data, $expire);
				return $data;
			}
		}
		else
		{
			$this->createDbObject();

			return $this->db->getRows($sql);
		}
	}

	/**
	 +----------------------------------------------------------
	 * 获得多条查询数据(带分页条)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string $sql      SQL指令
	 +----------------------------------------------------------
	 * @param  int    $pageRows 每页显示的记录条数
	 +----------------------------------------------------------
	 * @param  int    $expire   过期时间
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public final function getPageRows($sql, $pageRows = 20, $revisionKey = '', $expire = 0)
	{
		if ($this->cached)
		{
			$sql       = $this->filter($sql);
			$pageId    = isset($_GET['page']) ? $_GET['page'] : 1;
			$offset    = ($pageId-1)* $pageRows;
			$offset    = ($offset < 0) ? 0 : $offset; 
			$sqlLimit  = ' LIMIT '.$offset.','. $pageRows;
			

			$cache   = $this->getCacheObject();
			
			$dataKey = $this->createKey($sql.$sqlLimit, $revisionKey, $cache);
			$data    = $cache->get($dataKey);
			if (!empty($data))
			{
				return $data;
			}
			else
			{
				$this->createDbObject();
				$this->com('pager')->input = $this->com('dispatcher')->getInput();
				$this->db->page = $this->com('pager');
				$data = $this->db->getPageRows($sql, $pageRows);
				//$sql  = $data['query'];
				
				if ( !empty($data['record']) ) $cache->set($dataKey, $data, $expire);
				return $data;
			}
		}
		else
		{
			$this->createDbObject();
			$this->com('pager')->input = $this->com('dispatcher')->getInput();
			$this->db->page = $this->com('pager');
			
			return $this->db->getPageRows($sql, $pageRows);
		}
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
	public final function insert($table, $arr = array())
	{
		$this->revision();
		$this->createDbObject();

		return $this->db->insert($table, $arr);
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
	public final function update($table, $arr = array(), $where = '', $add_self = array())
	{
		$this->revision();
		$this->createDbObject();

		return $this->db->update($table, $arr, $where, $add_self);
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
	public final function delete($table, $where = '')
	{
		$this->revision();
		$this->createDbObject();

		return $this->db->delete($table, $where);
	}
	
	
	/**
	 * 启动事务
	 * @access public
	 * @return void
	 */
	public function startTrans($isXA=0) {
	    $this->createDbObject();
	    $this->commit();
	    $this->db->startTrans();
	    return ;
	}
	
	/**
	 * 提交事务
	 * @access public
	 * @return boolean
	 */
	public function commit() {
	    $this->createDbObject();
	    return $this->db->commit();
	}
	
	/**
	 * 事务回滚
	 * @access public
	 * @return boolean
	 */
	public function rollback() {
	    $this->createDbObject();
	    return $this->db->rollback();
	}
}
?>