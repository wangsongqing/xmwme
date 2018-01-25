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
     * 关于我们
     */
    public function about(){
        $this->display('index/about.php');
    }
    
    /**
     * 联系我们
     */
    public function tell(){
        $this->display('index/tell.php');
    }

}
?>