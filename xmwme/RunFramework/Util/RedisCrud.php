<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework RedisCRUD操作类
 +------------------------------------------------------------------------------
 * @date    17-07
 * @author Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
class RedisCrud extends RedisQ{
    
    public function __construct($key=''){
	$this->key = $key;
    }
    
    /**
     * 查询所有数据
     */
    public function findAll(){
	    if($this->get($this->key)){
		for( $i = 1; $i <= $this->get($this->key); $i++ ){
		    $data[] = $this->hgetall($this->key.':'.$i);
		    $data = array_filter($data);//过滤数组中的空元素
		}
		 return $data;
	    }
	   return false;
    }
    
	/**
	 * 添加数据
	 * @param array $data array
	 */
	public function add($data){
	     $id = $this->incr($this->key);
	     $data['id'] = $id;
	     $this->hmset($this->key.':'.$id, $data);
	}
	
	
	public function findOne($id){
	    return $this->hgetall($this->key.':'.$id);
	}
	
	public function edit($id,$data){
	     $this->hmset($this->key.':'.$id, $data);
	}
	
	public function delete($id){
	    $this->del($this->key.':'.$id);
	}
}

