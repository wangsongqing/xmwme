<?php
/**
 * @author wangsognqing<jimmywsq@163.com>
 * 积分Action
 * @date 2018-02-19
 */
class redbagAction extends actionMiddleware
{   
    /**
     * 获取红包记录
     */
    public function index(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $credit = M('redbag')->find($user_id);
        $_rule['exact']['user_id'] = $user_id;
        $_rule['exact']['type'] = 1;
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = 10;
        $credit_log = M('redbag_log')->findAll($_rule);
        foreach($credit_log['record'] as &$v){
            $activity = M('activity')->find($v['activity_id']);
            if(!empty($activity)){
                $v['activity_name'] = $activity['activity_name'];
            }
        }
        if(isset($ajax) && $ajax==1){//滚动加载数据
            $data = $this->fetch('redbag/redbag.grow.php',array('credit_log'=>$credit_log));
            $this->praseJson('0', '', '',$data);exit;
        }
        $this->display('redbag/redbag.index.php',array(
            'credit'=>$credit,
            'credit_log'=>$credit_log
        ));
    }
    
    /**
     * 红包提现记录(已经到账的)
     */
    public function exchange(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $credit = M('redbag')->find($user_id);
        $_rule['exact']['user_id'] = $user_id;
        $_rule['exact']['status'] = '4';
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = 10;
        $credit_log = M('withdraw')->findAll($_rule);
        $num_w = 0;
        foreach ($credit_log['record'] as $v){
           $num_w += $v['money'];
        }
        if(isset($ajax) && $ajax==1){//滚动加载数据
            $data = $this->fetch('redbag/redbag.grow_ex.php',array('credit_log'=>$credit_log));
            $this->praseJson('0', '', '',$data);exit;
        }
        $this->display('redbag/redbag.exchange.php',array(
            'credit'=>$credit,
            'credit_log'=>$credit_log,
            'num_w'=>$num_w
        ));
    }
}

