<?php
class LogoutAction extends actionMiddleware
{

    /**
     * 退出
     * @access public
     * @return void
     */
    public function index()
    {
        //echo 'hello world';
        $user = getAuth('all');
        $r = isset($info['url_code']) ? $info['url_code'] : "";
        clearCookie();
        $this->redirect('您已成功退出登录...', Root);
    }



}