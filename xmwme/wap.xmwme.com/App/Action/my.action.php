<?php
/**
 * @author jimmy
 * @date 2018-01-25
 * 个人中心
 */
class MyAction extends actionMiddleware
{   
    public function index(){
        $this->display('my/my.index.php');
    }
}

