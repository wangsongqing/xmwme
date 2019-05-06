<?php
/**
 * 提现模块
 */
class WithdrawAction extends actionMiddleware
{
    private $status = array(
        '1'=>'审核中',
        '2'=>'审核通过',
        '3'=>'审核不通过',
        '4'=>'已到账',
    );
    
    public function index(){
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $redbag = M('redbag')->find($user_id);
        $this->display('withdraw/withdraw.index.php',array(
            'redbag'=>$redbag
        ));
    }
    
    public function withdraw_send(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $money = isset($money)?$money:0;
        $data = M('withdraw')->add_withdraw($user_id,$money);
        if($data['err']!=1){
            $this->praseJson($data['err'], $data['msg'],$url);
        }
        $url = '/withdraw/withdraw_suss/?orders='.$data['orders'];
        $this->praseJson('1', '提现成功',$url);
    }
    
    public function withdraw_suss(){
        extract($this->input);
        $orders = isset($orders)?$orders:0;
        $this->display('withdraw/withdraw.suss.php',array(
            'orders'=>$orders
        ));
    }
    
    /**
     * 我的提现记录（所有提现记录）
     */
    public function withdraw_order(){
        extract($this->input);
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $_rule['exact']['user_id'] = $user_id;
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = '10';
        $orders = M('withdraw')->findAll($_rule);
       if(isset($ajax) && $ajax==1){
           $html = $this->fetch('withdraw/withdraw.fetch.php',array(
            'orders'=>$orders,
            'status'=>$this->status,
           ));
           $this->praseJson(0, '', '',$html);
       }
        $this->display('withdraw/withdraw.orders.php',array(
            'orders'=>$orders,
            'status'=>$this->status,
        ));
    }
}

