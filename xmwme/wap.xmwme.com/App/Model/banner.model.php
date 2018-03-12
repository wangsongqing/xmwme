<?php
/**
 * banner Model
 */
class bannerModel extends modelMiddleware
{
    public $tableKey = 'banner'; //数据表key
    public  $cached  = true;//是否读取缓存
    public $pK = 'id'; //数据表主键Id名称
    
     /**
     * 数据操作模型
     * @return object
     */
    public static function _model(){
	$model = M('banner');
	return $model;
    }
    
    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function banner_revision($id)
    {
        $sql    = sprintf("select * from %s where `id` = '$id'", $this->getTable('banner',0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
		"{id:$id}",
                "{status:1}",
                "{status:0}",
	    );
	}
	 $this->revision();
    }
}

