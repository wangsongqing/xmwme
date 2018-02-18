<?php
/**
 * 订单管理
 * @author wangsongqing
 */
class OrdersAction extends actionMiddleware
{   
    public function index(){
        extract($this->input);
        $model = M('orders');
        $rule = array();
        if(isset($isSearch)){
            isset($orders_num) && $rule['exact']['orders_num'] = $orders_num;
        }
        $data = $model->findAll($rule);
        $_arr = array();
        $goodsModel = M('goods');
        foreach ($data['record'] as $value) {
            $goods = $goodsModel->find($value['goods_id']);
            $value['goods_name'] = $goods['goods_name'];
            array_push($_arr, $value);
        }
        $data['record'] = $_arr;
        $this->display('orders/orders.index.php',array(
            'result'=>$data,
        ));
    }
    
    public function edit(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $model = M('orders');
        if($isPost){
            $_data = array(
                'is_get'=>isset($is_get)?$is_get:0,
                'number'=>isset($number)?$number:0,
                'content'=>isset($content)?$content:''
            );
            $_rule['exact']['id'] = $id;
            $re = $model->edit($_data,$_rule);
            if($re){
                $model->orders_revision($id);
                $this->redirect('编辑成功', Root.'orders/index/');
            }
        }
        $data = $model->find($id);
        $user_info = M('user_info')->find($data['user_id']);
        $data['phone'] = $user_info['telephone'];
        $goods =  M('goods')->find($data['goods_id']);
        $data['goods_name'] = $goods['goods_name'];
        $this->display('orders/orders.edit.php',array(
            'id'=>$id,
            'data'=>$data,
        ));
    }
}

