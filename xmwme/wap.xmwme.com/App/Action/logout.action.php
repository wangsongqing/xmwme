<?php
/**
 * 注销并推出登录
 * @author wangsongqing
 * @date 17-12-28
 */
class LogoutAction extends actionMiddleware {

    /**
     * 退出
     * @access public
     * @return void
     */
    public function index() {
        $user = getAuth('all');
        clearCookie();
        header('location:' . $url);
    }

}
