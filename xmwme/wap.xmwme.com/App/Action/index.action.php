<?php
/**
 * 新萌网
 * @author wangsongqing <jimmywsq@163.com>
 * @time 2017-12-29
 +------------------------------------------
 */
class IndexAction extends actionMiddleware
{   
    /**
     * 首页
     */
    public function index()
    {   
        $model = M('banner');
        $rule['exact']['status'] = 1;
        $rule['limit'] = 4;
        $data = $model->findTop($rule);//banner操作
        
        //积分动态
        $credit_model = M('credit_log');
        $login_user_id = isset($this->login_user['user_id'])?$this->login_user['user_id']:0;
        $_rule['exact']['user_id'] = $login_user_id;
        $_rule['order']['id'] = 'desc';
        $credit_data = $credit_model->findOne($_rule);
        if(isset($credit_data['activity_id'])){
            $activity = M('activity')->find($credit_data['activity_id']);
            if(!empty($activity)){
                $credit_data['activity_name'] = $activity['activity_name'];
            }
        }
	$this->display('index/index.php',array(
            'data'=>$data,
            'credit_data'=>$credit_data,
            'login_user_id'=>$login_user_id
        ));
    }
    
    /**
     * 关于我们
     */
    public function about(){
        $this->display('index/about.php');
    }
    
    /**
     * 联系我们
     */
    public function tell(){
        $this->display('index/tell.php');
    }

}
?>