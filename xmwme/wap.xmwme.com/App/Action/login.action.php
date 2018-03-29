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
            }else{
                $this->regiter($phone);exit;
            }
        }
        if(isset($invite_code) && $invite_code!=''){
            addCookie('invite_code_form', base64_decode($invite_code));
        }
        $this->display("login/login.html");
    }

    /**
     * 注册页面
     */
    public function regiter($phone) {
        $form_key = getCookie('invite_code_form');
        $this->display('login/regiter.php',array(
            'telephone' => $phone,
            'invite_code'=>$form_key
        ));
    }
    
    /**
     * 忘记密码
     */
    public function forget(){
        extract($this->input);
        if($isPost){
           if(isset($step) && $step==1){
               $telephone = isset($telephone)?$telephone:0;
               $this->display('login/login.forget_pwd.php',array(
                   'telephone'=>$telephone
               ));//修改密码页面
           }
           
           if(isset($step) && $step==2){
               $password = isset($password)?$password:0;
               $telephone = isset($telephone)?$telephone:0;
               if(!$password){$this->praseJson('-1002', '密码不能为空');}
               if(!$telephone){$this->praseJson('-1003', '手机号不能为空');}
               $rule['exact']['telephone'] = $telephone;
               $user = M('user_info')->findOne($rule);
               if(!$user){$this->praseJson('-1004', '用户不存在，请先注册');}
               $salt = saltRound();//生成随机salt
               $password = md5(md5($password).$salt);
               $_data = array(
                   'password'=>$password,
                   'salt'=>$salt,
               );
               $user_re = M('user_info')->edit($_data,$rule);
               if($user_re){
                   M('user_info')->user_info_revision($user['user_id']);//刷新缓存
                   $this->praseJson('success', '修改成功,请重新登录','/login/');
               }else{
                   $this->praseJson('-1006', '修改密码失败');
               }
               
           }
        }
        $this->display('login/login.forget.php');
    }
    
    /**
     * 验证短信验证吗是否正确
     */
    public function validyzm(){
        extract($this->input);
        if($isPost){
            $yzm = isset($yzm)?$yzm:'-1';
            $telephone = isset($telephone)?$telephone:'';
            $get_m_yzm = getVar('forget_confirm_code'.$telephone);//忘记密码找回密码的验证码
            if($yzm!=$get_m_yzm){
                $this->praseJson('fail', '短信验证码错误');
            }
        }
    }
    
    /**
     * 验证手机号是否存在
     */
    public function ajaxphoneExsit($telephone=0){
        $rule['exact']['telephone'] = $telephone;
        $user_re = M('user_info')->findOne($rule);
        if(empty($user_re)){
            return '-1';
        }
    }

    public function password($phone) {
        extract($this->input);
        $this->display("login/password.php",array('phone'=>$phone));
    }

    public function ajaxregister() {
        extract($this->input);
        if($isPost){
            //rsa解密
            $password = run_encryption($password);
            $fields = array(
                array('telephone', 'require', '手机号码不能为空'),
                array('telephone', '/^1[3|4|5|7|8]\d{9}$/', '手机号码格式不正确'),
                array('yzm', 'require', '验证码不能为空'),
                array('yzm', '/^\d{6}$/', '验证码格式不正确'),
                array('password', 'require', '密码不能为空'),
            );
            $validate = $this->validate($this->input, $fields);
            if ( !empty($validate['error']) ) {
		$error = explode('@@@', $validate['error']);
		$this->praseJson(1,$error[0]);
            }
            if(preg_match("/[\x80-\xff]/",$password)===1){
		$this->praseJson('1','密码不能有中文');
            }
            if($this->checkPasswordLevel($password) < 2){
                $this->praseJson('1','密码6-16位数字、字母、字符的2种组合');
            }
            
            //验证验证码是否正确
            $old_yzm = getVar('register_confirm_code'.$telephone);
            if(!$old_yzm||($old_yzm!= $yzm)){
                $this->praseJson('1','短信验证码错误');exit;
            }
            $userModel = M('user_info');
            $reg_info = $userModel->register($telephone,$password,$invite_code);
            if($reg_info['err']==-1){
                $this->praseJson('1',$reg_info['msg']);exit;
            }else if($reg_info['err']==1){
                removeCookie('invite_code_form');
                $this->praseJson('0',$reg_info['msg'],Root.'my/index');exit;
            }
            
        }
    }

    public function ajaxLogin() {
        $this->input = $this->getEncryLogin($this->input); //解密提交过来的数据,因为前端用js加密的，为了安全考虑
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
    
    /**
     * 生成验证码图片验证码
     */
    public function verify(){
            require_once App . '/Util/captcha.class.php';//验证码类
            $verify = new captcha();
            $code = $verify->randString(4,1);
            addCookie('verify',$code);
            $verify->code();
    }
    
    /**
     * 发送短信接口
     * @param  $type 0变现  1注册 2找回密码
     * @author song
     */
    public function sendmsg()
    {
        extract($this->input);
        $fields = array(
            array('type', 'require', '短信发送类型不正确'),
            array('type', '/^(1|2)$/', '短信类型不在指定范围'),
            array('verify','require', '图形验证码不能为空'),
            array('verify','/^\d{4}$/','图形验证码长度不够'),
            array('mark', 'require', '业务参数错误'),
            array('telephone', 'require', '手机号码不能为空'),
            array('telephone', '/^1(4|3|5|8|7)\d{9}$/', '手机号码格式不正确'),
        );
        $validate = $this->validate($this->input, $fields);
        if ( !empty($validate['error']) ) {
        	$error = explode('@@@', $validate['error']);
            die(json_encode(array('err'=>1,'msg'=>$error[0])));
        }
        
        //判断图片验证码是否正确
        $sysCode = getCookie('verify');
        if($sysCode != $verify){
            $this->praseJson('2', '图片验证码不正确');
        }
        
        $ip = getIp();
        
        $markArr = array('1'=>'register_confirm', '2'=>'forget_confirm');
        $mark    = isset($markArr[$mark]) ? $markArr[$mark] : -1;
        if ($mark == -1) die(json_encode(array('err'=>1,'msg'=>'业务参数错误！')));
        if ($type==1 && $mark=='forget_confirm') {
            $is_exe = $this->ajaxphoneExsit($telephone);
            if($is_exe=='-1') die(json_encode(array('err'=>1,'msg'=>'该手机号码不存在！请先注册！')));
        }
        //配置要发送的短信类型
        $sendConfig = array(
            'register_confirm'=> array(
                'key'=>'register_confirm_code'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
            ),
            'forget_confirm'=> array(
                'key'=>'forget_confirm_code'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
            )
        );
        
        //60秒内不允许重复发送
        $last_time = getVar('last_time_'.$mark.'_'.$telephone);
        $ip_count  = getVar('send_limit_'.$ip) ? intval(getVar('send_limit_'.$ip)) : 0;	
        if($last_time){
            die(json_encode(array('err'=>1,'msg'=>'60秒请不要重复发送')));
        }
        if($ip_count >= 20){
            //单个ip限制单类一天20次，
            die(json_encode(array('err'=>1,'msg'=>'单个ip每天最多可获取20次验证码')));
        }
        //指定时间内的次数限制
        $send_count = getVar('send_count_'.$mark.'_'.$telephone);       //获取今日已发送的次
        if($send_count >= $sendConfig[$mark]['num']){
            die(json_encode(array('err'=>1,'msg'=>'验证码超出次数限制，请明天再试')));
        }
        

        //发送验证码
        $code = rand(111111,999999);
        if ($type==1 && $mark=='forget_confirm') {
            $smsType = 'SMS_127160413';//忘记密码
        }else{
            $smsType = 'SMS_127160332';//注册短信
        }
        
        $send_result = SmsSendText($telephone, $code, $smsType);       //发送短信验证码
        if($send_result->Code == 'OK')
        {
            //设置验证码
            setVar($sendConfig[$mark]['key'], $code, $sendConfig[$mark]['timeout']);
            //刷新找回密码中的统计数
            setVar('last_time_'.$mark.'_'.$telephone, 1, 60);//区别60秒内不能发送第二次
            $send_count++;
            $ip_count++;
            $dayEndTime = mktime(23,59,59,date('n'),date('j'),date('Y')) ;
            $timeOut = $dayEndTime-time();
            setVar('send_count_'.$mark.'_'.$telephone, $send_count, $timeOut);//一天只能发送指定的次数
            setVar('send_limit_'.$ip, $ip_count, $timeOut);
            die(json_encode(array('err'=>0,'msg'=>'<span class="c-999">验证码已发送至'.hiddenPhoneMiddle($telephone).',有效期为10分钟</span>')));
        }
        die(json_encode(array('err'=>1,'msg'=>'验证码发送失败')));
    }
    
    
    //检查密码的级别是否是二种组合以上
    private function checkPasswordLevel($password)
    {
        $level = 0;
        if(preg_match('/[a-zA-Z]/', $password)) $level++;
        if(preg_match('/[0-9]/', $password)) $level++;
        if(preg_match('/[^a-zA-Z0-9]/', $password)) $level++;
        return $level;
    }
}

?>