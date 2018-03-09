<?php
/**
 * 提现model
 */
class WithdrawModel extends modelMiddleware
{
    /**
     * 数据表key
     */
    public $tableKey = 'withdraw';
    public $cached  = false;
    
    /**
     * 数据表主键Id名称
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
     * 提现方法
     * @param int $user_id
     * @param int $money
     */
    public function add_withdraw($user_id,$money){
       $err_arr = array('err'=>'','msg'=>'');
        try{
            $withdraw = self::_model();
            $withdraw->startTrans();//开启事务
            $redBag = M('redbag')->find($user_id);
            if(empty($redBag)) throw new Exception('该用户红包账户不存在','-1001');
            if($money>$redBag['red_bag']) throw new Exception ('您的可提现金额为'.$redBag['red_bag'].'元','-1002');
            if($money<'10') throw new Exception ('提现金额必须大于10元','-1000');
            $_rule['exact']['user_id'] = $user_id;
            $_rule['other'] = "status in ('1','2')";
            $isCan = self::_model()->findOne($_rule);
            if(!empty($isCan)) throw new Exception ('你有一笔提现未到账，请到账后再提现！','-1000');
            //操作redbag表的数据
            $time = time();
            $redbag_sql = "UPDATE `xm_redbag` SET red_bag=red_bag-{$money},updated={$time} WHERE user_id={$user_id}";
            $resu_redbag = $withdraw->execate($redbag_sql);
            if(!$resu_redbag) throw new Exception ('更新红包表失败','-1002');
            if($resu_redbag){
                M('redbag')->credit_revision($user_id);//刷新缓存
            }
            
            //添加 redbag_log 表的数据
            $result_log = M('redbag_log')->add_data($user_id,$money,2);
            if(!$result_log) throw new Exception('记录提现日志失败','-1003');
            
            //数据入 提现表
            $orders = $time.$user_id.rand(0, 9);
            $_data = array(
                'user_id'=>$user_id,
                'money'=>$money,
                'status'=>1,
                'orders'=>$orders,
                'created'=>$time,
                'updated'=>$time,
                'start_time'=>0,
                'content'=>''
            );
            $exe_withdraw = $withdraw->add($_data);
            if(!$exe_withdraw) throw new Exception('提现数据入库失败','-1004');
            $exe_commit = $withdraw->commit();//提交事务
            if(!$exe_commit) throw new Exception('事务提交数据失败','-1005');
            $err_arr['err'] = 1;
            $err_arr['msg'] = 'suss';
            $err_arr['orders'] = $orders;
            
        } catch (Exception $e) {
            $rollback = self::_model()->rollback();
            if(!$rollback) throw new Exception('事务回滚失败');
            $msg = $e->getMessage();
            $code = $e->getCode();
            $err_arr['err'] = $code;
            $err_arr['msg'] = $msg;
            writeLog('提现失败：用户id:'.$user_id.',失败异常原因:'.$msg, 'withdraw.log');
        }
        return $err_arr;
    }
    
    /**
     * 获取用户累计提现金额
     * @param int $user_id
     * @param int $status
     */
    public function get_user_money($user_id,$status=4){
        $rule['exact']['user_id'] = $user_id;
        $rule['exact']['status'] = $status;
        $model = self::_model()->findOne($rule,'SUM(money) as money_num');
        if(!empty($model)){
            return $model['money_num'];
        }
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
                "{status:1}",
                "{status:0}",
                "{orders:$orders}",
                "{user_id:$user_id}{status:1}",
                "{user_id:$user_id}{status:2}",
                "{user_id:$user_id}{status:3}",
                "{user_id:$user_id}{status:4}"
	    );
	}
	 $this->revision();
    }
}

