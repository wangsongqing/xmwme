<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 提现审核
 +---------------------------------------------------------------------------------------------------------------
 */
class WithdrawModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'withdraw';
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
	$model = M('withdraw');
	return $model;
    }

    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function withdraw_revision($id)
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
                "{user_id:$user_id}",
	    );
	}
	 $this->revision();
    }
    
 }
?>