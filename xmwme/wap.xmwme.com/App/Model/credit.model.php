<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 菜单数据操作
 +---------------------------------------------------------------------------------------------------------------
 */
class creditModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'credit';
    public  $cached  = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'user_id';
    
    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model(){
	$model = M('user_info');
	return $model;
    }
    
    /**
     * 在本model里面引用别的model的试列
     * @return array
     */
    public function selectOne(){
	$model = M('credit');
	$data = $model->findOne(1);
	return $data;
    }
    
    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function credit_revision($admin_id)
    {
        $sql    = sprintf("select * from %s where `user_id` = '$admin_id'", $this->getTable('credit',0,0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
		"{user_id:$user_id}",
	    );
	}
	 $this->revision();
    }
 }
?>