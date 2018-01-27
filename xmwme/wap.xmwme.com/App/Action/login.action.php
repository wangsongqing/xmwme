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
        $this->display("login/login.html");
    }

    /**
     * 注册页面
     */
    public function regiter($phone) {
        $this->display('login/regiter.php',array(
            'telephone' => $phone
        ));
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
        
        $markArr = array('1'=>'register_confirm', '2'=>'forget_confirm','3'=>'forget_pay_pwd','5'=>'new_telephone_confirm');
        $mark    = isset($markArr[$mark]) ? $markArr[$mark] : -1;
        if ($mark == -1) die(json_encode(array('err'=>1,'msg'=>'业务参数错误！')));
        
        //配置要发送的短信类型
        $sendConfig = array(
            'register_confirm'=> array(
                'key'=>'register_confirm_code'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
                'tpl'=>'验证码{code}，请在10分钟内完成输入，欢迎注册宝宝钱包!',//短信模版
            ),
            'forget_confirm'=> array(
                'key'=>'forget_confirm_code'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
                'tpl'=>'验证码{code}，您正在找回宝宝钱包登录密码，需要进行验证。请勿泄露您的验证码！如非本人操作，请联系客服。',//短信模版
            ),
            'forget_pay_pwd'=> array(
                'key'=>'forget_pay_pwd'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
                'tpl'=>'验证码为：{code}，您正在宝宝钱包向恒丰银行存管平台发起交易密码找回, 该验证码10分钟内有效，为了您的资金安全，请妥善保管您的验证码。',//短信模版
            ),
            'new_telephone_confirm'=>array(//修改手机号向新手机发送短信
                'key'=>'new_telephone_confirm'.$telephone,//存储验证码key
                'num'=>5,//指定时间内发送次数限制
                'timeout'=>600,//验证码有效期
                'tpl'=>'验证码{code}，您正在修改注册手机号码，为确保账户安全，打死都不能告诉别人哦！如非本人操作，请忽略。',//短信模版
            )
        );
        
        //随机生成6位数字验证码,并保存memcache
        //$code = rand(111111,999999);
        $code = '123456';
        //短信模版
        $msg = str_replace('{code}', $code, $sendConfig[$mark]['tpl']);
        
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
        if ($type == 1){
            if($mark == 'withdraw_confirm' || $mark == 'forget_pay_pwd'){
                //$send_result = BB::getApi('sms')->sendMsg( $telephone, $mark, array('code'=>$code), $this->login_user['user_id']);
            }else{
                //$send_result = BB::getApi('sms')->sendText($telephone, $mark, $msg);       //发送短信验证码
            }
        }elseif($type == 2){
            //$send_result = BB::getApi('sms')->sendText($telephone, 'yuying', $code);       //发送语音验证码
        }
        $send_result = 1;
        if($send_result == 1)
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