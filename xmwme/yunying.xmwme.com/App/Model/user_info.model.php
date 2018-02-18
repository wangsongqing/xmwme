<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 订单表表Model
 +---------------------------------------------------------------------------------------------------------------
 */
class User_infoModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'user_info';
    public  $cached  = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'user_id';//主键
    
    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model(){
	$model = M('user_info');
	return $model;
    }

    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function orders_revision($id)
    {
        $sql    = sprintf("select * from %s where `user_id` = '$id'", $this->getTable($this->tableKey,0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
		"{user_id:$id}",
	    );
	}
	 $this->revision();
    }
    
 }
?>