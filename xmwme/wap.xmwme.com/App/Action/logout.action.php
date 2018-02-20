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
        $url = Root.'index/';
        header('location:' . $url);
    }
    
    /**
     * 清除所有缓存
     */
    public function clear(){
       $telephone = isset($this->login_user['telephone'])?$this->login_user['telephone']:0;
       if($telephone=='18201197923'){
           $mem = clearMem();
            if($mem){
                $url = Root.'index/';
                 header('location:' . $url);
            }
       }else{
           die('非法操作！！！');
       }
       
    }

}
