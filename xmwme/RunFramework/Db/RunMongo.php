<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework mongo数据库访问接口
 +------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class RunMongo
{
	//当前数据库对象
	public  $db         = null;

	//当前连接对象
	public  $connect    = null;

	//连接数据库配置文件
	public  $configFile = null;

	//获取的列
	public  $col		= array();

	//增删改操作选项
	public $options     = array(
		'safe'     => true,
		'fsync'    => true,
		'multiple' => true,
		);

	//分页对象
	public $page        = null;

	
	/**
	 +----------------------------------------------------------
	 * 类的构造方法
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
		$this->db         = null;
		$this->page       = null;
		$this->options    = null;
		$this->configFile = null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 打开数据库连接
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */
	public function connect()
	{
		if ($this->connect == null)
		{
			if ( !file_exists($this->configFile) ) 
			{
				RunException::throwException("数据库配置文件：".$this->configFile."不存在!");
			}
			require($this->configFile);
			
			$server = $user && $password ? "mongodb://{$user}:{$password}@{$host}" : "mongodb://{$host}";
			$this->connect   = new Mongo($server);
			$nodes  = $this->connect->listDBs();
			$found  = false;
			foreach ($nodes['databases'] as $node) if ($node['name'] == $db) $found = true;
			if (!$found) RunException::throwException("数据库 $db 不存在!");
			$this->db = $this->connect->$db;  
		}		
	}
	
	/**
	 +----------------------------------------------------------
	 * 关闭数据库连接
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 */
	public function close()
	{
		if ($this->connect) 
		{
			$this->connect->close();
		}
		$this->connect = null;
	}


	/**
	 +----------------------------------------------------------
	 * 构造条件
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  array  $where 过滤字段
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */	
	public function where($where)
	{
		$wh = array();
		if ( isset($where['exact']) && is_array($where['exact']) && !empty($where['exact']) ) {
			foreach ($where['exact'] as $key => $value) {
				$wh[$key] = $value;
			}
		}

		if ( isset($where['in']) && is_array($where['in']) && !empty($where['in']) ) {
			foreach ($where['in'] as $key => $value) {
				if ( is_array($value) ) {
					$wh[$key] = array('$in' => $value);
				}
			}
		}

		if ( isset($where['scope']) && is_array($where['scope']) && !empty($where['scope']) ) {
			foreach ($where['scope'] as $key => $value) {
				if ( is_array($value) && count($value) == 2  && $value[0] < $value[1]) {
					$wh[$key] = array('$gte' => $value[0], '$lte' => $value[1]);
				}
			}
		}

		if ( isset($where['like']) && is_array($where['like']) && !empty($where['like']) ) {
			foreach ($where['like'] as $key => $value) {
				if (!empty($value)) {
					$wh[$key] = new MongoRegex("/*$value*/i");
				}
			}
		}
		return $wh;
	}


	/**
	 +----------------------------------------------------------
	 * 构造排序规则
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  array  $order 排序字段
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */	
	public function order($order)
	{
		$orderBy = array();
		foreach($order as $key => $value)
		{
			$orderBy[$key] = strtolower($value) == 'desc' ? -1 : 1;
		}
		return $orderBy;
	}

	
	/**
	 +----------------------------------------------------------
	 * 获得一条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $table  表名
	 +----------------------------------------------------------
	 * @param  array   $rule   查询规则
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */	
	public function getRow($table, $rule = array() )
	{
		$this->connect();
		$where = $this->where($rule);
		$row   = $this->db->$table->findOne($where, $this->col);
		 
		return $row;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获得多条查询记录
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $table   表名
	 +----------------------------------------------------------
	 * @param  array   $where   查询规则
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getRows($table, $rule)
	{
		$this->connect();
		$rows   = array();
		$where  = $this->where($rule);
		$order  = isset($rule['order']) ? $this->order($rule['order']) : array();
		$limit  = isset($rule['limit']) ? $rule['limit'] : 20;
		$cursor = $this->db->$table->find($where, $this->col)->sort($order)->limit($limit);
		foreach ($cursor as $row)
		{
			$rows[] = $row;
		}
		return $rows;
	}

	/**
	 +----------------------------------------------------------
	 * 获得多条查询数据(带分页条)
	 +----------------------------------------------------------
	 * @access public 
	 +----------------------------------------------------------
	 * @param  string  $table   表名
	 +----------------------------------------------------------
	 * @param  array   $rule    查询规则
	 +----------------------------------------------------------
	 * @return array
	 +----------------------------------------------------------
	 */
	public function getPageRows($table, $rule)
	{
		$this->connect();
		$page  = isset($_GET['page']) ? intval($_GET['page']) : 0;
		$where = $this->where($rule);
		$order = isset($rule['order']) ? $this->order($rule['order']) : array();
		$limit = isset($rule['limit']) ? $rule['limit'] : 20;
		$total = $this->db->$table->find($where, $this->col)->count();

		//计算分页的偏移量
		$offset = ($page-1)* $limit;
		$offset = ($offset < 0) ? 0 : $offset; 
		
		$rows   = array();
		$cursor = $this->db->$table->find($where, $this->col)->sort($order)->skip($offset)->limit($limit);
		foreach ($cursor as $row)
		{
			$rows[] = $row;
		}
		
		$data['pageBar'] = is_object($this->page) ? $this->page->get($total, $limit, 10, 'on') : '';
		$data['record']	 = $rows;
		return $data;
	}


	/**
	 +----------------------------------------------------------
	 * 添加数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table   表名
	 +----------------------------------------------------------
	 * @param  array   $record  插入的数据(键值对)
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function insert($table, $record)
	{
		if ( !is_array($record) ) return false;
		
		$this->connect();
		
		return $this->db->$table->insert($record, $this->options);
	}

	/**
	 +----------------------------------------------------------
	 * 更新数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table   表名
	 +----------------------------------------------------------
	 * @param  array   $record  更新的数据(键值对)
	 +----------------------------------------------------------
	 * @param  array   $where   条件(键值对)
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function update($table, $record = array(), $where = array() )
	{
		if ( !is_array($record) || !is_array($where) ) return false;

		$this->connect();
		$where = $this->where($where);
		
		return $this->db->$table->update($where, $record, $this->options);
	}


	/**
	 +----------------------------------------------------------
	 * 删除数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param  string  $table  表名
	 +----------------------------------------------------------
	 * @param  array   $where  条件
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function delete($table, $where = array() )
	{
		if ( !is_array($where) ) return false;

		$this->connect();
		$where = $this->where($where);
		
		return $this->db->$table->remove($where, $this->options);
	}
}
?>