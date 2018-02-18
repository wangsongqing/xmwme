<?php

/**
 * @author jimmy
 * @date 2018-01-25
 * 商场模块
 */
class GoodsAction extends actionMiddleware {
    
    public $err = array(
        '1'=>'兑换成功,请去我的查看详细',
        '-1001'=>'请勿重复提交',
        '-1002'=>'参数错误',
        '-1003'=>'你的积分不够了',
        '-1004'=>'积分处理失败',
        '-1005'=>'减库存处理失败',
        '-1006'=>'积分变动日志记录失败',
        '-1006'=>'订单生成失败'
    );
    public function index() {
        $is_login = isset($this->login_user['user_id']) ? $this->login_user['user_id'] : 0;
        if ($is_login > 0) {
            $credit = M('credit')->find($this->login_user['user_id']);
        }
        $rule['limit'] = 16;
        $rule['order']['id'] = 'desc';
        $goods = M('goods')->findTop($rule);
        $this->display('goods/goods.index.php', array(
            'credit' => isset($credit) ? $credit : 0,
            'goods' => $goods,
        ));
    }

    public function detail() {
        extract($this->input);
        $id = isset($id) ? $id : '';
        $user_id = isset($this->login_user['user_id']) ? $this->login_user['user_id'] : 0;
        $model = M('goods');
        $data = $model->find($id);
        if ($user_id > 0) {
            $salt = saltRound(8); //生成随机salt,放重复提交需要
            $key = 'saltcredit' . $this->login_user['user_id'];
            setVar($key, $salt);
        }

        $this->display('goods/goods.detail.php', array(
            'data' => $data,
            'salt' => isset($salt) ? $salt : 0,
            'user_id' => $user_id,
        ));
    }

    public function buy() {
        extract($this->input);
        $gid = isset($gid) ? $gid : 0;
        $num = isset($num) ? $num : 0;
        $user_id = isset($this->login_user['user_id']) ? $this->login_user['user_id'] : 0;
        
        $key = 'saltcredit' . $this->login_user['user_id'];//防重复提交
        $salt = getVar($key);
        if ($rand != $salt) {
            $this->praseJson('-1001', $this->err['-1001']);
        }else{
            delVar($key);
        }
        if ($gid == 0 || $num == 0) {
            $this->praseJson('-1002', $this->err['-1002']);
        }
        $reGoods = M('goods')->buy_credit_goods($gid,$num,$user_id);//执行兑换操作   
       if($reGoods<0){
           $this->praseJson($reGoods, $this->err[$reGoods]);
       }
       $this->praseJson($reGoods, $this->err[$reGoods],Root.'my/');     
        
    }

}
