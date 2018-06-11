<?php
/**
 * @author wangsongqing <jimmywsq@163.com>
 * @time 2017-05
 +------------------------------------------
 * 简单的增删改操作
 * 如果你的数据是从缓存里面读取出来的，那个你在增、删、改的时候要去刷新缓存。
 * 这块可能一下不好熟悉，大家多看一下源码就好。
 +------------------------------------------
 */
class IndexAction extends actionMiddleware
{   
    public $calss = array(
                '1'=>'编程技术',
                '2'=>'生活感悟',
            );
    /**
     * 博客列表
     */
    public function index()
    {	
	extract($this->input);
	$isSearch = isset($isSearch)?$isSearch:'';
        $ajax = isset($ajax) ? intval($ajax) : 0;
        $_rule['order']['id'] = 'desc';
        $_rule['limit'] = '5';
        $data = Run::Model('blog')->findAll($_rule);
        if($ajax==1){
            $html = $this->fetch('index/ajax.page.php', array('data'=>$data,'class'=>$this->calss));
            $this->praseJson('1','suss','',$html);
        }
	$this->display('index/index.php',array('data'=>$data,'class'=>$this->calss));
    }
    
    public function detail(){
        extract($this->input);
        $id = isset($id)?$id:'';
        $data = Run::Model('blog')->find($id);
        $_rule['exact']['blog_id'] = $id;
        $_rule['limit'] = 30;
        $_rule['order']['id'] = 'desc';
        $commit_data = Run::Model('commit')->findTop($_rule,'*',0);
        $data['commit'] = $commit_data;
        $this->display('index/detail.php',array(
            'data'=>$data,
            'class'=>$this->calss
        ));
    }
    
    
    public function blogDate(){
        extract($this->input);
        $date = isset($date)?$date:'';
        $ajax = isset($ajax)?$ajax:'';
        $firstday = date('Y-m-01', strtotime($date));//当前月的第一天的时间戳
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));//当前月的最后一天的时间戳
        $firstday = strtotime($firstday);
        $lastday = strtotime($lastday) + 86400;
        $_rule['other'] = "created>{$firstday} AND created<{$lastday}";
        $_rule['limit'] = 5;
        $_rule['order']['id'] = 'desc';
        $data = Run::Model('blog')->findAll($_rule,'*',0);
        if($ajax==1){
            $html = $this->fetch('index/ajax.page.php', array('data'=>$data,'class'=>$this->calss));
            $this->praseJson('1','suss','',$html);
        }
        $this->display('index/blogdate.php',array(
            'data'=>$data,
            'class'=>$this->calss,
            '_date'=>$date
        ));
    }
    
    /**
     * 评论action
     */
    public function commit(){
        extract($this->input);
        $username = isset($username)?$username:'';
        $mail = isset($mail)?$mail:'';
        $id = isset($id)?$id:'';//博客id
        $_arr = array(
            'username'=>$username,
            'mail'=>$mail,
            'blog_id'=>$id,
            'content'=>$comment,
            'created'=>time(),
        );
        $insert_id = Run::Model('commit')->add($_arr);
        if($insert_id){
            Run::Model('commit')->commit_revision($insert_id);//刷新缓存
            $data = Run::Model('commit')->find($insert_id);
            $this->display('index/commit.php', array('data'=>$data));
        }
    }
}
?>