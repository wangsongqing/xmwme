<?php
class LoginAction extends actionMiddleware
{
    /**
     * 显示登陆界面
     * @access public
     * @return void
     */
    public function index()
    {
        extract($this->input);
        $this->display("login/login.html");
    }

    public function ajaxLogin()
    {
        $this->input = $this->getEncryLogin($this->input);//解密提交过来的数据,因为前端用js加密的，为了安全考虑
        extract($this->input);
        //验证密码
        $re = check_password(isset($password)?$password:'');
        if( isset($re[0]) && !empty($re[0]) ){
	    echo json_encode(array('code'=>3,'msg'=>$re[0]));exit;
        }
	
	$userModel = M('manage_user');
        //登录
        $response = $userModel->login(isset($account)?$account:'', isset($password)?$password:'');
	
        if (!isset($response) || empty($response)) {
	    echo json_encode(array('code'=>3,'msg'=>'用户名称或密码错误'));exit;
        }else {
            $time = date('Y-m-d H:i:s', time());
	    $ip = getIp();
            $arr = array(
                'admin_id'    => $response['admin_id'],
                'admin_name'  => $response['admin_name'],
                'loginIp'   => $ip,
                'mobile'=> $response['mobile'],
                'group_id'=> $response['group_id'],
                'group_name'=> $response['group_name'],
                'password' => $response['password'],
                'url_code' => 'wwwwww',
                'useragent' => $_SERVER['HTTP_USER_AGENT'],
                'loginTime' => $time,
            );
            $userModel->setLoginStatus($arr);
	    M('manage_log')->logs($arr);
	    echo json_encode(array('code'=>1,'msg'=>'欢迎您回来'));exit;
        }

    }
    
    /**
     * 解密提交过来的数据
     * @author wangsongqing <jimmywsq@163.com>
     * @param array $data
     * @return array
     * @time 2017-05
     */
    private function getEncryLogin($data)
    {
        $return = array();
        foreach($data as $k=>$li)
	{
            $en = run_encryption(trim($li));
            if( !empty($li) && !empty($en) ){
                $return[$k] = $en;
            }else{
                $return[$k] = $li;
            }
        }

        return $return;
    }



}
?>