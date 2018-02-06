<?php
/**
 * modelMiddleware  数据库公共方法处理中间件
 * @author       	Jimmy Wang 
 * @version      	V1.0 
 * @time         	2017-5 
 */
class modelMiddleware extends Model {

    public $cached = false; //是否开启缓存
    public $expire = 3600; //缓存时间(单位: 秒)	
    public $tableKey = 'admin'; //数据表key -- 默认指定用户表，可随意指定
    public $pK = 'id'; //数据表主键Id名称
    public $cachePreKey = '';

    /**
     * 得到指定ID的信息,只能是以id来查询
     * =============================================================
     * @param  int  $id                                            
     * @param  int  $slice  分表分库的时候需要填写		        
     * @param  int  $isReadOnly  是否访问只读数据库 1:true 0:false	
     * @param  int  $isLock  是否锁表 1:true 0:false		
     * @param  int  $iscached  是否读取缓存 1:true 0:false		
     * ==============================================================
     * @access public
     * @return array
     */
    public function find($id, $iscached = 1, $slice = 0, $isLock = 0) {
        $this->cached = $iscached;
        $table = $this->setServerReadWrite($slice);
        //主键名
        $pk = $this->pK;
        if ($isLock == 1) {
            $sql = "select * from $table where $pk='$id' FOR UPDATE";
        } else {
            $sql = "select * from $table where $pk='$id'";
        }
        return $this->getRow($sql, $this->cachePreKey . "{{$pk}:$id}");
    }

    /**
     * 根据条件查询单条记录
      +-------------------------------------
     * @param  array $rule  数据查询规则
      +-------------------------------------
     * @access public
      +-------------------------------------
     * @return array
      +-------------------------------------
     */
    public function findOne($rule, $field = '*', $iscached = 1) {
        $this->cached = $iscached;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->setServerReadWrite($slice);
        $where = where($rule);
        $sql = "select $field from $table $where limit 1";
        if (!empty($rule['exact'])) {
            $key = revisionKey($rule['exact']);
            $key = $this->cachePreKey . $key;
        } else {
            $key = $this->cachePreKey . '{all:all}';
        }
        return $this->getRow($sql, $key);
    }

    /**
     * 按条件获取数据(前N条)
     * @param  array  $rule  数据查询规则
     * @access public
     * @return array
     */
    public function findTop($rule, $field = '*', $iscached = 1) {
        $this->cached = $iscached;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->setServerReadWrite($slice);

        $where = where($rule);
        $limit = isset($rule['limit']) ? $rule['limit'] : 5;
        $sql = "select $field from $table $where limit $limit";
        if (!empty($rule['exact'])) {
            $key = revisionKey($rule['exact']);
            $key = $this->cachePreKey . $key;
        } else {
            $key = $this->cachePreKey . '{all:all}';
        }
        $rows = $this->getRows($sql, $key);
        return $rows;
    }

    /**
     * 列表(搜索、多字段组合查询)
     * @param  array  $rule  数据查询规则
     * @access public
     * @return array
     */
    public function findAll($rule = array(), $field = '*', $iscached = 0) {
        $this->cached = $iscached;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->setServerReadWrite($slice);
        $where = where($rule);
        $limit = isset($rule['limit']) ? $rule['limit'] : 20;
        $sql = "select $field from $table $where";
        if (!empty($rule['exact'])) {
            $key = revisionKey($rule['exact']);
            $key = $this->cachePreKey . $key;
        } else {
            $key = $this->cachePreKey . '{all:all}';
        }

        $rows = $this->getPageRows($sql, $limit, $key);
        return $rows;
    }

    /**
     * 数据库读写设置及分表
     * @param  array  $slice  分库
     * @access public
     * @return $table
     */
    private function setServerReadWrite($slice) {
        $table = $this->getTable($this->tableKey, $slice);
        return $table;
    }

    /**
     * 插入记录
     * @param array $arr 数组
     * @access public
     * @return boolean
     */
    public function add($arr, $slice = 0) {
        $table = $this->getTable($this->tableKey, $slice);
        $result = $this->insert($table, $arr);
        $lastInsId = $this->getLastInsId();                                           //插入成功，返回记录
        return $result ? $lastInsId : false;                                          //确认插入成功后返回成功，否则提交失败
    }

    /**
     * 修改记录
     * @param  array  	$rule  数据查询规则
     * @param  array	$arr  修改字段
     */
    function edit($arr, $rule) { //return false;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->getTable($this->tableKey, $slice);
        $condition = where($rule, 1);
        $result = $this->update($table, $arr, $condition);
        return $result ? $result : false;
    }

    /**
     * 删除记录
     * @access public
     * @param  mixed $condition 条件
     */
    public function del($rule) {
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->getTable($this->tableKey, $slice);
        $condition = where($rule, 1);
        $result = $this->delete($table, $condition);
        return $result ? true : false;
    }

    /**
     * 开始事务，链接指定数据库
     * @access public
     * @param  int $slice 链接数据库ID
     */
    public function startTransTable($slice = 0) {
        $table = $this->getTable($this->tableKey, $slice);
        return $this->startTrans();
    }
    
    /**
	 * 提交事务
	 * @access public
	 * @return boolean
	 */
	public function commitTransTable($slice = 0) {
	    $table = $this->getTable($this->tableKey, $slice);
	    return $this->commit();
	}
	
	/**
	 * 事务回滚
	 * @access public
	 * @return boolean
	 */
	public function rollbackTransTable($slice = 0) {
	    $table = $this->getTable($this->tableKey, $slice);
	    return $this->rollback();
	}

    /**
     * 插入记录
     * @param array $arr 数组 - 只针对单表数据批量插入使用，或者事务中进行使用
     * @access public
     */
    public function execate($sql) {
        return $this->db->query($sql);
    }

    /**
     * 执行原生sql，查询单条,只是限于在单个数据库，不能执行跨库的sql
     * @param string $sql
     * @return array
     */
    public function queryOne($sql = '') {
        if (empty($sql)) {
            return false;
        }
        $this->getTable($this->tableKey);
        $data = $this->db->getRow($sql);
        return $data;
    }

    /**
     * 执行原生sql，查询多条，只是限于在单个数据库，不能执行跨库的sql
     * @param string $sql
     * @return array
     */
    public function queryAll($sql = '') {
        if (empty($sql)) {
            return false;
        }
        $this->getTable($this->tableKey);
        $data = $this->db->getRows($sql);
        return $data;
    }

    /**
     * 按条件统计数据
     * @param  array  $rule  数据查询规则
     */
    public function getCount($rule, $fields = "*") {
        $this->cached = 0;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->getTable($this->tableKey, $slice);
        $where = where($rule);
        $sql = "select count($fields) as total from $table $where";
        $row = $this->getRow($sql);
        return $row['total'];
    }

    /**
     * 按条件获取数据(前N条)事物锁表
     * @param unknown $rule 查询条件
     * @param string $field 查询数组
     * @return multitype:
     */
    public function getTopTrans($rule, $field = '*') {
        $this->cached = 0;
        $slice = isset($rule["slice"]) ? $rule["slice"] : 0;
        $table = $this->getTable($this->tableKey, $slice);
        $where = where($rule);
        $limit = isset($rule['limit']) ? $rule['limit'] : 10000;
        $sql = "select $field from $table $where limit $limit for update";
        $rows = $this->getRows($sql);
        return $rows;
    }

}

?>