<?php
class LoginAction extends actionMiddleware {

    /**
     * 显示登陆界面
     * @access public
     * @return void
     */
    public function index() {
        extract($this->input);
        if ($isPost) {
            $phone = isset($phone) ? $phone : '';
            $_rule['exact']['telephone'] = $phone;
            $model = M('user_info')->findOne($_rule);
            if(!empty($model)){
                $this->password($phone);exit;
            }
        }
        $this->display("login/login.html");
    }

    /**
     * 注册页面
     */
    public function regiter($phone) {
        var_dump($phone);
    }

    public function password($phone) {
        extract($this->input);
        $this->display("login/password.php",array('phone'=>$phone));
    }

    public function regeze() {
        extract($this->input);
        $this->display("login/regeze.php");
    }

    public function ajaxLogin() {
        //$this->input = $this->getEncryLogin($this->input); //解密提交过来的数据,因为前端用js加密的，为了安全考虑
        extract($this->input);
        //验证密码
        $re = check_password(isset($password) ? $password : '');
        if (isset($re[0]) && !empty($re[0])) {
            echo json_encode(array('code' => 3, 'msg' => $re[0]));
            exit;
        }

        $userModel = M('user_info');
        //登录
        $response = $userModel->login(isset($phone) ? $phone : '', isset($password) ? $password : '');
        if (!isset($response) || empty($response)) {
            echo json_encode(array('code' => 3, 'msg' => '用户名称或密码错误'));
            exit;
        } else {
            $time = date('Y-m-d H:i:s',time());
            $ip = getIp();
            $arr = array(
                'user_id' => $response['user_id'],
                'nick' => $response['nick'],
                'loginIp' => $ip,
                'telephone' => $response['telephone'],
                'password' => $response['password'],
                'useragent' => $_SERVER['HTTP_USER_AGENT'],
                'loginTime' => $time,
            );
            $userModel->setLoginStatus($arr);
            echo json_encode(array('code' => 1, 'msg' => '欢迎您回来'));
            exit;
        }
    }

    /**
     * 解密提交过来的数据
     * @author wangsongqing <jimmywsq@163.com>
     * @param array $data
     * @return array
     * @time 2017-05
     */
    private function getEncryLogin($data) {
        $return = array();
        foreach ($data as $k => $li) {
            $en = run_encryption(trim($li));
            if (!empty($li) && !empty($en)) {
                $return[$k] = $en;
            } else {
                $return[$k] = $li;
            }
        }

        return $return;
    }
    
    public function loginout(){
        clearCookie();
        $jump_url = Root . 'index/index/?' . time();
        echo '<script>location.href="' . $jump_url . '"</script>';
    }

}

?>