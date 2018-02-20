<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 用户积分表Model
 +---------------------------------------------------------------------------------------------------------------
 */
class credit_logModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'credit_log';
    public  $cached  = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'id';//主键
    
    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model(){
	$model = M('credit_log');
	return $model;
    }
    
    /**
     * 做数据入库
     * @param type $user_id
     * @param type $credit
     * @param type $type
     * @param type $activity_id
     * @param type $goods_id
     */
    public function add_data($user_id,$credit=0,$type=1,$activity_id,$goods_id){
        $_data = array(
            'user_id'=>$user_id,
            'credit'=>$credit,
            'type'=>$type,
            'activity_id'=>$activity_id,
            'goods_id'=>$goods_id,
            'created'=>time(),
            'updated'=>time(),
        );
        $insert_id = self::_model()->add($_data);
        if($insert_id>0){
            self::_model()->credit_log_revision($insert_id);//刷新缓存
        }
        return $insert_id;
    }
    
    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function credit_log_revision($id)
    {
        $sql    = sprintf("select * from %s where `id` = '$id'", $this->getTable($this->tableKey,0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
		"{id:$id}",
                "{user_id:$user_id}"
	    );
	}
	 $this->revision();
    }
 }
?>