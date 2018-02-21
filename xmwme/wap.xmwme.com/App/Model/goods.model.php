<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 商品表Model
 +---------------------------------------------------------------------------------------------------------------
 */
class GoodsModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'goods';
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
	$model = M('goods');
	return $model;
    }
    
    /**
     * 兑换商品
     * @param type $gid
     * @param type $num
     * @param type $user_id
     * @throws Exception
     */
    public function buy_credit_goods($gid,$num,$user_id){
        $code = '1';
        try{
            $model_goods = self::_model();
            $model_goods->startTrans();//开启事务
            $goods = $model_goods->find($gid);
            $need_credit = $num * $goods['credit']; //获取此次需要的积分
            $credit_model = M('credit'); //获取用户可用积分
            $credit = $credit_model->find($user_id);           
            if($need_credit>$credit['credit']){
                throw new Exception('你的积分不够了','-1003');
            }
            $credit_arr = array(
                'credit'=>$credit['credit'] - $need_credit,
                'use_credit'=>$credit['use_credit'] + $need_credit,
            );
            $cRule['exact']['user_id'] = $user_id;
            $cre = $credit_model->edit($credit_arr,$cRule);//积分表数据变化
            if(!$cre){throw new Exception('积分处理失败','-1004');}
            $credit_model->credit_revision($user_id);//刷新缓存
            
            //添加积分变动记录表
            $credit_log = M('credit_log')->add_data($user_id,$need_credit,'2',0,$gid);
            if(!$credit_log) throw new Exception('积分变动日志记录失败','-1006');
            
            //商品库存变化
            $arr_goods = array(
                'store'=>$goods['store'] - $num,
            );
            $gRule['exact']['id'] = $gid;
            $gre = $model_goods->edit($arr_goods,$gRule);
            if(!$gre){throw new Exception('减库存处理失败','-1005');}
            $model_goods->goods_revision($gid);//刷新缓存
            
            //生成订单
            $oreder = M('orders')->get_orders($user_id,$gid,$goods['goods_type'],$num,$need_credit);
            if(!$oreder) throw new Exception('订单生成失败','-1007');
            $model_goods->commit();//事务提交  
            
        } catch (Exception $e) {
            $model_goods->rollback();//事务回滚
            $code = $e->getCode();
            $msg = $e->getMessage();
        }
       return $code;
    }


    /**
     * 刷新mem缓存
     * @param  $id
     * @access public
     * @return void
     */
    public function goods_revision($id)
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