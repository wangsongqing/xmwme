<?php
/**
 * 新萌网
 * @author wangsongqing <jimmywsq@163.com>
 * @time 2017-12-29
 +------------------------------------------
 */
class IndexAction extends actionMiddleware
{   
    /**
     * 首页
     */
    public function index()
    {	
	$this->display('index/index.php');
    }
    /**
     * 游戏列表
     */
    public function game(){
        $this->display('index/game.php');
    }
    /**
     * 商城兑换列表
     */
    public function shop(){
        $this->display('index/shop.php');
    }
    
    /**
     * 我的个人中心
     */
    public function my(){
        $this->display('index/my.php');
    }

}
?>