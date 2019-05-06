<?php
/**
 * @author Jimmy Wang <jimmywsq@163.com>
 * 用户登陆日志
 */
class LogAction extends actionMiddleware
{
    public function index()
    {	
	extract($this->input);
	$isSearch = isset($isSearch)?$isSearch:'';
	$model = M('manage_log');
	$rule = array();
	if($isSearch){
	    isset($beginTime) && isset($endTime) && $rule['other'] = 'created>='.strtotime($beginTime).' AND created<'.strtotime($endTime);
	}
	$data = $model->findAll($rule,'*',0,0);
	$this->display('log/index.php',array('result'=>$data));
    }



}