<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 订单表表Model
 +---------------------------------------------------------------------------------------------------------------
 */
class OrdersModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'orders';
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
	$model = M('orders');
	return $model;
    }
    
    /**
     * 生成订单并入库
     * @param type $user_id
     * @param type $goods_id
     */
    public function get_orders($user_id,$goods_id,$goods_type=1,$goods_num=0,$credit=0){
        $oreder = get_order_sn($user_id);
        $_data = array(
            'user_id'=>$user_id,
            'orders_num'=>$oreder,
            'goods_id'=>$goods_id,
            'goods_type'=>$goods_type,
            'goods_num'=>$goods_num,
            'credit'=>$credit,
            'created'=>time(),
            'updated'=>time(),
        );
        return self::_model()->add($_data);
    }

    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function orders_revision($id)
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
	    );
	}
	 $this->revision();
    }
    
 }
?>