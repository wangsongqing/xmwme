<?php
/**
 * @author wangsongqing <jimmywsq@163.com>
 * @time 2018-01
 +------------------------------------------
 * 简单的增删改操作
 * 如果你的数据是从缓存里面读取出来的，那个你在增、删、改的时候要去刷新缓存。
 * 这块可能一下不好熟悉，大家多看一下源码就好。
 +------------------------------------------
 * 执行方式  例如：E:\phpstudy\WWW\runframework\run\run_task>php index.php test vip
 * 如果是用法的phpstudy,进入cmd的时候从phpstudy设置里面进入
 * 如果想传入参数 $GLOBALS["argv"]["3"];
 * linux系统执行方法  /usr/local/php/bin/php /home/wwwroot/xmwme/xmw_task/index.php test changeip
 */
class TestAction extends actionMiddleware
{
    public function changeip(){
        $_rule['exact']['ip'] = '';
        $model = M('manage_log');
        $data = $model->findTop($_rule);
        foreach($data as $v){
            $eRule['exact']['id'] = $v['id'];
            $_data = array(
                'ip'=>'127.0.0.2'
            );
            $model->edit($_data,$eRule);
        }
        echo 'suss'."\r\n";
    }
}

?>