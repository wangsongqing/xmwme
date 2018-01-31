<?php
class Hook
{
    //过滤列表
    private $isFilter = array("login", "api","logout",);

    /**
     * 验证用户是否登录
     * @access public
     * @return mixd
     */
    public function work()
    {
        $mod     = getModule();
        $action  = getAction();

        //检查是否登录
        if (!loginCheck() && !in_array(strtolower($mod), $this->isFilter)) {
	    $this->redirect('',Root.'login/index/');
        }

        $admin = getAuth('all');
    }

    function Html(){
        $this->com->msg->display("地址错误");
    }
    
    public function redirect($desc, $url, $scripts = array())
    {
	    $this->com->msg->show($desc, $url, $scripts);
    }
}
?>