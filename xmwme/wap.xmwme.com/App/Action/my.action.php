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
    
    /**
     * 我的邀请页面
     */
    public function share(){
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $user_info = M('user_info')->find($user_id);
        $link = $this->linkShare($user_info, $user_id);
        $this->display('my/my.share.php',array(
            'user_info'=>$user_info,
            'link'=>$link
        ));
    }
    
    public function shareimg(){
        require(App.'/Util/phpqrcode.php');
        $user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $user_info = M('user_info')->find($user_id);
        //生成二维码图片,最后一个参数是为二维码打logo
       $str = $this->linkShare($user_info, $user_id);
        QRcode::png($str, false, 'L', 5, 2,false);
    }
    
    private function linkShare($user_info,$user_id){
        $form_code = base64_encode($user_info['form_code']);
        $user_id = base64_encode($user_id);
        $str = "http://wap.xmwme.com/login/index/?invite_code={$form_code}&fuid={$user_id}";//地址需要加http
        return $str;
    }
}

