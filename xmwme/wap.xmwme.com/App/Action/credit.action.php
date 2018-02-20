<?php
/**
 * @author wangsognqing<jimmywsq@163.com>
 * 积分Action
 * @date 2018-02-19
 */
class creditAction extends actionMiddleware
{   
    /**
     * 获取积分记录
     */
    public function index(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $credit = M('credit')->find($user_id);
        $_rule['exact']['user_id'] = $user_id;
        $_rule['exact']['type'] = 1;
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = 10;
        $credit_log = M('credit_log')->findAll($_rule);
        foreach($credit_log['record'] as &$v){
            $activity = M('activity')->find($v['activity_id']);
            if(!empty($activity)){
                $v['activity_name'] = $activity['activity_name'];
            }
        }
        if(isset($ajax) && $ajax==1){//滚动加载数据
            $data = $this->fetch('credit/credit.grow.php',array('credit_log'=>$credit_log));
            $this->praseJson('0', '', '',$data);exit;
        }
        $this->display('credit/credit.index.php',array(
            'credit'=>$credit,
            'credit_log'=>$credit_log
        ));
    }
    
    /**
     * 消费积分记录
     */
    public function exchange(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $credit = M('credit')->find($user_id);
        $_rule['exact']['user_id'] = $user_id;
        $_rule['exact']['type'] = 2;
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = 10;
        $credit_log = M('credit_log')->findAll($_rule);
        foreach ($credit_log['record'] as &$v){
            $goods = M('goods')->find($v['goods_id']);
            if(!empty($goods)){
                $v['goods_name'] = $goods['goods_name'];
            }
        }
        if(isset($ajax) && $ajax==1){//滚动加载数据
            $data = $this->fetch('credit/credit.grow_ex.php',array('credit_log'=>$credit_log));
            $this->praseJson('0', '', '',$data);exit;
        }
        $this->display('credit/credit.exchange.php',array(
            'credit'=>$credit,
            'credit_log'=>$credit_log
        ));
    }
}

