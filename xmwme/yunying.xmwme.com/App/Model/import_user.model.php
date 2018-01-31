<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 用户登陆记录模型
 +---------------------------------------------------------------------------------------------------------------
 */
class import_userModel extends modelMiddleware{

    public $tableKey = 'import_user'; //数据表key
    public  $cached  = false;//是否读取缓存
    public $pK = 'id'; //数据表主键Id名称
    
    /**
     * 数据操作模型
     * @return object
     */
    public static function _model(){
	$model = M('manage_log');
	return $model;
    }
    
 }
?>