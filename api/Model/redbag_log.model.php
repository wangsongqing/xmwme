<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 用户积分表Model
 +---------------------------------------------------------------------------------------------------------------
 */
class redbag_logModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'redbag_log';
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
	$model = M('redbag_log');
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
    public function add_data($user_id,$redbag=0,$type=1,$activity_id=0,$goods_id=0){
        $_data = array(
            'user_id'=>$user_id,
            'redbag'=>$redbag,
            'type'=>$type,
            'activity_id'=>isset($activity_id)?$activity_id:0,
            'goods_id'=>isset($goods_id)?$goods_id:0,
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