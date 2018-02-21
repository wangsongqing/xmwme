<?php
/**
 * @author jimmy
 * @date 2018-01-25
 * 个人中心
 */
class MyAction extends actionMiddleware
{   
    public function index(){
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $credit = M('credit')->find($user_id);
        $user_info = M('user_info')->find($user_id);
        $this->display('my/my.index.php',array(
            'credit'=>$credit,
            'user_info'=>$user_info,
        ));
    }
    
    /**
     * 我的订单详情
     */
    public function myorders(){
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $_rule['exact']['user_id'] = $user_id;
        $_rule['order']['id'] = 'desc';
        $orders = M('orders')->findAll($_rule);
        foreach($orders['record'] as &$v){
            $goods = M('goods')->find($v['goods_id']);
            if(!empty($goods)){
                $v['goods_name'] = $goods['goods_name'];
                $v['goods_img'] = $goods['detail_pic'];
            }
        }
        $this->display('my/my.orders.php',array(
            'orders'=>$orders
        ));
    }
}

