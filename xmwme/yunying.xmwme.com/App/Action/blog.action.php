<?php
/**
 * 博客系统管理
 * @author wangsongqing <1105235512@qq.com>
 */
class BlogAction extends actionMiddleware
{   
    
    public $calss = array(
                '1'=>'编程技术',
                '2'=>'生活感悟',
            );
    public function index(){
        $data = Run::Model('blog')->findAll();
        $this->display('blog/blog.index.php',array(
            'data'=>$data,
            '_class'=>$this->calss,
        ));
    }
    
    public function add(){
        extract($this->input);
        if($isPost){
            $_arr = array(
                'title'=>isset($title)?$title:'',
                'class'=>isset($class)?$class:'',
                'content'=>isset($_POST['content'])?$_POST['content']:'',
                'scontent'=>isset($_POST['content'])?mb_substr($_POST['content'],0,1000,'utf-8'):'',
                'created'=>time(),
                'updated'=>time(),
            );
            $id = Run::Model('blog')->add($_arr);//添加
            if($id){
                $this->redirect('添加成功',Root.'blog/index/');
            }
        }
        $this->display('blog/blog.add.php');
    }
    
    public function edit(){
        extract($this->input);
        $id = isset($id) ? $id : 0;
        $data = Run::Model('blog')->find($id);
        if($isPost){
            $_arr = array(
                'title'=>$title,
                'class'=>$class,
                'content'=>$_POST['content'],
                'scontent'=>$_POST['scontent'],
                'updated'=>time()
            );
            $_rule['exact']['id'] = $id;
            $result = Run::Model('blog')->edit($_arr,$_rule);
            if($result){
                Run::Model('blog')->blog_revision($id);//刷新缓存
                $this->redirect('编辑成功',Root.'blog/index/');
            }
        }
        $this->display('blog/blog.edit.php',array(
            'data'=>$data,
            'id'=>$id,
        ));
    }
    
    public function delete(){
        $id = isset($id)?$id:0;
        $_rule['exact']['id'] = $id;
        $result = Run::Model('blog')->del($_rule);
        if($result){
            $this->redirect('删除成功', Root.'blog/index/');
        }
    }
}

