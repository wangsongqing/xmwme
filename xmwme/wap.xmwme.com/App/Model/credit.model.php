<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 用户积分表Model
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
    public $pK = 'user_id';//主键
    
    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model(){
	$model = M('credit');
	return $model;
    }
    
    /**
     * 刷新mem缓存
     * @param  $user_id
     * @access public
     * @return void
     */
    public function credit_revision($user_id)
    {
        $sql    = sprintf("select * from %s where `user_id` = '$user_id'", $this->getTable('credit',0) );
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

    /**
     * 用户积分记录 获得积分处理
     * @param type $num_credit
     * @param type $user_id
     * @return type
     * @throws type
     */
    public function change_user_credit($num_credit,$user_id,$activity_id){
        $flag = 0;
        $msg = '';
        try{
            $startTrans = self::_model()->startTransTable();
            if(!$startTrans) throw new Exception('开启事务失败！');
            $time = time();
            $_sql = "UPDATE `xm_credit` SET credit=credit+{$num_credit},all_credit=all_credit+{$num_credit} WHERE user_id={$user_id}";
            $re = self::_model()->execate($_sql);
            if(!$re){throw Exception('更新积分表失败！');}
            $change_credit_data = array(
                'user_id'=>$user_id,
                'credit'=>$num_credit,
                'type'=>1,
                'created'=>$time,
                'updated'=>$time,
                'activity_id'=>$activity_id,
            );
            $credit_log_insert = M('credit_log')->add($change_credit_data);
            if(!$credit_log_insert) throw new Exception('积分日志表记录失败！');
            M('credit_log')->credit_log_revision($credit_log_insert);//刷新缓存
            $commit = self::_model()->commitTransTable();
            if(!$commit) throw Exception('事务提交失败！');
            $flag = 1;
        }  catch (Exception $e) {
            $rollback = self::_model()->rollbackTransTable();
            if(!$rollback) throw new Exception('事务回滚失败！');
            $msg = $e->getMessage();
             writeLog('用户:'.$user_id.'，连连看积分数据写入数据失败，表:credit，错误信息:'.$msg, 'lian.log');
        }
        return $flag;
    }
 }
?>