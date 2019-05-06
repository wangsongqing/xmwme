<?php
/**
 * 提现审核
 */
class WithdrawAction extends actionMiddleware
{   
    public $status = array(
        '1'=>'审核中',
        '2'=>'审核通过',
        '3'=>'审核不通过',
        '4'=>'已经到账'
    );
    public function index(){
        extract($this->input);
        $rule = array();
        $_data = M('withdraw')->findAll($rule);
        $this->display('withdraw/withdraw.index.php',array(
            'result'=>$_data,
            'status'=>$this->status,
        ));
    }
    
    /**
     * 审核操作
     */
    public function audit(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $status = isset($status)?$status:0;
        if($isPost){
            if($id==0 || $status==0) $this->redirect ('参数错误', '/withdraw/index/');
            $time = time();
            $data = array(
                'status'=>$status,
                'updated'=>$time,
                'admin_id'=>$this->login_user['admin_id'],
                'sd_created'=>$time,
                'content'=>isset($content)?$content:'',
            );
            if($status==4){
                $data['start_time'] = $time;
            }
            $rule['exact']['id'] = $id;
            $re = M('withdraw')->edit($data,$rule);
            if($re){
                M('withdraw')->withdraw_revision($id);//刷新缓存
                $this->redirect('审核成功', '/withdraw/index/');
            }
        }
        $data = M('withdraw')->find($id);
        $this->display('withdraw/withdraw.audit.php',array(
            'data'=>$data,
            'status'=>$this->status,
            'id'=>$id,
        ));
    }
}

