<?php
 /**
 +---------------------------------------------------------------------------------------------------------------
 * 菜单数据操作
 +---------------------------------------------------------------------------------------------------------------
 */
class user_infoModel extends modelMiddleware{

/**
     * 数据表key
     */
    public $tableKey = 'user_info';
    public  $cached  = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'user_id';

    /**
     * @param $account 用户名
     * @param $password 密码
     * @param $type 后台类型  1管理后台，2运营后台，3财务后台
     * @param string $field
     * @return array
     */
    public function login($account,$password,$field='*')
    {
        $table = $this->getTable($this->tableKey);
        $where = 'where ( telephone="' .$account . '" AND password=MD5(CONCAT(MD5("'.$password.'"),salt))) ';
        $sql   = "select $field from $table $where limit 1";
        return $this->getRow($sql);
    }

    /**
     * 设置登录状态
     */
    public function setLoginStatus($user)
    {
        setAuth($user, 0);
        setVar('manage_'.$user['user_id'], $user['password'], 360000);
    }
    
    /**
     * 增加注册用户
     * @author jimmy
     * @param int $telephone
     * @param string $password
     * @param int $invite_code
     */
    public function register($telephone, $password, $invite_code){
        $err_arr = array();
        try{
            $start_trans = self::_model()->startTransTable();
            if(!empty($invite_code)){
                $rule['exact']['form_code'] = $invite_code;
                $form_user = self::_model()->findOne($rule);
                if(empty($form_user)){
                    throw new Exception('邀请码有误');
                }
            }
        
            $salt = saltRound();//生成随机salt
            $password = md5(md5($password).$salt);
            $data = array(
                'telephone' => $telephone,
                'form_code' => substr($telephone, 3),
                'from_user_id' => isset($form_user['user_id'])?$form_user['user_id']:0,
                'type' => isset($form_user)&&!empty($form_user)?1:0,
                'last_login_time' => time(),
                'regiest_ip' => getIp(),
                'locked' => 0,
                'password' => $password,
                'salt' => $salt,
                'created' => time(),
                'updated' => time()
            );
            $last_insert_id = self::_model()->add($data);
            if(!$last_insert_id){
                throw Exception('注册失败');
            }
            //初始化积分表
            $credit_data = array('user_id' => $last_insert_id,'credit' => 0,'use_credit' => 0,'all_credit' => 0,'created' => time(),'updated' => time(),);
            $last_credit_id = M('credit')->add($credit_data);
            if(!$last_credit_id){
                throw Exception('初始化积分表失败');
            }
            //给邀请人增加累计邀请数
            if(isset($form_user) && !empty($form_user)){
                $_sql = "UPDATE xm_user_info SET to_user_count=to_user_count+1 WHERE user_id='".$form_user['user_id']."'";
                $re_up = self::_model()->execate($_sql);
                if(!$re_up){
                    throw Exception('给邀请人增加累计邀请数');
                }
            }
            $arr = array('user_id' => $last_insert_id,'nick' => '','loginIp' => $data['regiest_ip'],'telephone' => $data['telephone'],'password' => $data['password'],'useragent' => $_SERVER['HTTP_USER_AGENT'],'loginTime' => time());
            $this->setLoginStatus($arr);//注册成功后设置登录状态
            $commit_trans = self::_model()->commit();
            $err_arr = array('err'=>'1','msg'=>'注册成功');
        } catch (Exception $e){
            $msg = $e->getMessage();
            $err_arr = array('err'=>'-1','msg'=>$msg);
            $rollback_trans = self::_model()->rollback();
        }
        return $err_arr;
    }
    
    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model(){
	$model = M('user_info');
	return $model;
    }
    
    /**
     * 在本model里面引用别的model的试列
     * @return array
     */
    public function selectOne(){
	$model = M('user_info');
	$data = $model->findOne(1);
	return $data;
    }

    /**
     * 数据验证
     */
    public function validateMolde(){
	$fields = 
	    array(
	    array('admin_name', 'require', '请填写登录名'),
	    array('mobile','require','请填写手机号'),
	    array('realname','require','请填写真实名字'),
	    array('mobile','phone','请填写合格的手机号'),
	    array('password','require','请填写登陆密码'),
	 );
	return $fields;
    }
    
    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function user_info_revision($user_id)
    {
        $sql    = sprintf("select * from %s where `user_id` = '$user_id'", $this->getTable('user_id',0,0) );
        $member = $this->getRow($sql);
	if (empty($member)){
	    $this->revisionKey = array("{all:all}");
	}else{
	    extract($member);
	    //为数据查询key
	    $this->revisionKey = array(
		"{all:all}",
                "{user_id:$user_id}",
		"{telephone:$telephone}",
	    );
	}
	 $this->revision();
    }
 }
?>