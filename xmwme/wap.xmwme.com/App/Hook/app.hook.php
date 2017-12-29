<?php
class Hook
{
    /**
     * 验证用户是否登录
     * @access public
     * @return mixd
     */
    public function work()
    {
       
    }
    
    public function redirect($desc, $url, $scripts = array())
    {
	    $this->com->msg->show($desc, $url, $scripts);
    }
}
?>