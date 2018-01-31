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
}

