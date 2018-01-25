<?php
/**
 * @author jimmy
 * @date 2018-01-25
 * 商场模块
 */
class ShopAction extends actionMiddleware
{   
    public function index(){
        $this->display('shop/shop.index.php');
    }
}

