<?php
/**
  +------------------------------------------------------------------------------
 * Run Framework 通用数据库访问接口
  +------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
  +------------------------------------------------------------------------------
 */
class RunDbPdo implements IDataSource {

    //数据库类型
    public $dbType = null;
    //分页对象
    public $page = null;
    //连接数据库配置文件
    public $configFile = null;
    //当前连接ID
    private $connectId = null;
    //操作所影响的行数
    private $affectedRows = 0;
    //查询结果对象
    private $PDOStatement = null;
    //当前数据库id
    public $dbId = null; 

    /**
      +----------------------------------------------------------
     * 类的构造子
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */

    public function __construct() {
        if (!class_exists('PDO')) {
            RunException::throwException('Not Support : PDO');
        }
    }

    /**
      +----------------------------------------------------------
     * 类的析构方法(负责资源的清理工作)
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    public function __destruct() {
        $this->close();
        $this->dbType = null;
        $this->configFile = null;
        $this->connectId = null;
        $this->PDOStatement = null;
        $this->dbId = null;
    }

    /**
      +----------------------------------------------------------
     * 打开数据库连接
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    private function connect() {
        if($this->connectId == null){
            if (!file_exists($this->configFile)){
                RunException::throwException("数据库配置文件：" . $this->configFile . "不存在!");
            }
            require($this->configFile);
            $this->connectId = new PDO("mysql:host={$host};dbname={$db}", $user, $password);
            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
            if ($this->dbType == 'mysql'){
                $this->connectId->exec("set names $encode");
            }
            $dsn = $username = $password = $encode = null;
            if ($this->connectId == null) {
                RunException::throwException("PDO CONNECT ERROR");
            }
        }
    }

    /**
      +----------------------------------------------------------
     * 关闭数据库连接
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    public function close() {
        $this->connectId = null;
    }

    /**
      +----------------------------------------------------------
     * 释放查询结果
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    private function free() {
        $this->PDOStatement = null;
    }

    /**
      +----------------------------------------------------------
     * 执行语句 针对 INSERT, UPDATE 以及DELETE
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     * @param string $sql  sql指令
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public function query($sql) {
        if($this->connectId == null){
            $this->connect();
        }
        $this->affectedRows = $this->connectId->exec($sql);
        return $this->affectedRows >= 0 ? true : false;
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
    public function getAffected() {
        if ($this->connectId == null){
            return 0;
        }
        return $this->affectedRows;
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
    public function getRow($sql) {
        if($this->connectId == null){
            $this->connect();
        }
        $result = array();   //返回数据集
        $this->PDOStatement = $this->connectId->prepare($sql);
        $this->PDOStatement->execute();

        if (empty($this->PDOStatement)) {
            $this->error($sql);
            return $result;
        }

        $result = $this->PDOStatement->fetch(constant('PDO::FETCH_ASSOC'));
        $this->free();

        return $result;
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
    public function getRows($sql) {
        if($this->connectId == null){
            $this->connect();
        }
        $result = array();   //返回数据集
        $this->PDOStatement = $this->connectId->prepare($sql);
        $this->PDOStatement->execute();

        if (empty($this->PDOStatement)) {
            $this->error($sql);
            return $result;
        }

        $result = $this->PDOStatement->fetchAll(constant('PDO::FETCH_ASSOC'));
        $this->free();

        return $result;
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
    public function getPageRows($query, $pageRows = 20) {
        if (!is_object($this->page))
            return array();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        if ((int) $pageRows > 0)
            $this->page->pageRows = $pageRows;
        $sqlCount = preg_replace("|SELECT.*?FROM([\s])|i", "SELECT COUNT(*) as total FROM$1", $query, 1);
        $row = $this->getRow($sqlCount);
        $total = isset($row['total']) ? $row['total'] : 0;

        // group count
        if (preg_match('!(GROUP[[:space:]]+BY|HAVING|SELECT[[:space:]]+DISTINCT)[[:space:]]+!is', $sqlCount)) {
            $sqlCount = preg_replace('!(order[[:space:]]+BY)[[:space:]]+.*!is', '', $query, 1);
            $sqlCount = preg_replace("|SELECT.*?FROM([\s])|i", "SELECT COUNT(*) as total FROM$1", $sqlCount, 1);
            $rows = $this->getRows($sqlCount);
            $total = empty($rows) ? 0 : count($rows);
        }

        //计算分页的偏移量
        $pageId = isset($page) ? $page : 1;
        $offset = ($pageId - 1) * $pageRows;
        $offset = ($offset < 0) ? 0 : $offset;
        $query .= ' LIMIT ' . $offset . ',' . $this->page->pageRows;
        $data['pageBar'] = $this->page->get($total, $page);
        $data['record'] = $this->getRows($query);
        $data['total'] = $total;
        return $data;
    }

    /**
      +----------------------------------------------------------
     * 获得最后一次插入的id
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return int
      +----------------------------------------------------------
     */
    public function getLastInsertId() {
        if ($this->connectId != null) {
            return $this->connectId->lastInsertId();
        }
        return 0;
    }

    /**
      +----------------------------------------------------------
     * 添加数据(辅助方法)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string  $table  表名
      +----------------------------------------------------------
     * @param array   $arr    插入的数据(键值对)
      +----------------------------------------------------------
     * @return mixed
      +----------------------------------------------------------
     */
    public function insert($table, $arr = array()) {
        $field = $value = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                $v = preg_replace("/'/", "\\'", $v);
                $field .= "$k,";
                $value .= "'$v',";
            }
            $field = preg_replace("/,$/", "", $field);
            $value = preg_replace("/,$/", "", $value);
            $sql = "insert into $table($field) values($value)";
            return $this->query($sql);
        }
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
    public function getLastInsId() {
        if ($this->connectId != null) {
            return $this->connectId->lastInsertId();
        }
        return 0;
    }

    /**
      +----------------------------------------------------------
     * 更新数据(辅助方法)
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string  $table  表名
      +----------------------------------------------------------
     * @param array   $arr    更新的数据(键值对)
      +----------------------------------------------------------
     * @param mixed   $where  条件
      +----------------------------------------------------------
     * @return mixed
      +----------------------------------------------------------
     */
    public function update($table, $arr = array(), $where = '') {
        $field = "";
        $loop = 1;
        $len = count($arr);
        $sql = "UPDATE {$table} SET ";
        foreach ($arr as $k => $v) {
            $v = preg_replace("/'/", "\\'", $v);
            $field .= $k . "='" . $v . "',";
        }
        $sql .= trim($field, ',');

        if (!empty($where)) {
            if (is_array($where)) {
                $sql .= " WHERE ";
                foreach ($where as $wFiled => $wValue)
                    $sql .= $wFiled . " = " . "'$wValue'" . " AND ";
                $sql = trim($sql, " AND ");
            } else {
                $sql .= " WHERE $where";
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
     * @param string  $table  表名
      +----------------------------------------------------------
     * @param mixed   $where  条件
      +----------------------------------------------------------
     * @return mixed
      +----------------------------------------------------------
     */
    public function delete($table, $where = '') {
        $sql = "delete from {$table} ";
        if (!empty($where)) {
            if (is_array($where)) {
                $sql .= " where ";
                foreach ($where as $wFiled => $wValue)
                    $sql .= $wFiled . " = " . $wValue . " AND ";
                $sql = trim($sql, " AND ");
            } else {
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
    public function startTrans() {
        $result = $this->commit();
        if (!$result) {
            $this->error("开启事务失败！");
            return false;
        }
        $this->query('SET AUTOCOMMIT=0');
        $this->query('START TRANSACTION');                                    //开启事务
        return true;
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
        return;
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
        if (!$result) {
            return false;
        }
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
        if (!$result)
            return false;
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
    private function error($sql) {
        $error = $this->PDOStatement->errorInfo();
        $str = $error[2];
        if ($sql != '')
            $str .= "\n [ SQL语句 ] : " . $sql;
        RunException::throwException($str);
    }

}

?>