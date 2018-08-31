<?php
/**
 * 博客系统管理
 * @author wangsongqing <1105235512@qq.com>
 */
class BlogAction extends actionMiddleware
{   
    
    public $calss = array(
                '1'=>'PHP',
                '2'=>'Python',
                '3'=>'Mysql',
                '4'=>'Java',
                '5'=>'生活感悟',
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
                'scontent'=>isset($_POST['scontent'])?$_POST['scontent']:'',
                'created'=>time(),
                'updated'=>time(),
            );
            $id = Run::Model('blog')->add($_arr);//添加
            if($id){
                $this->redirect('添加成功',Root.'blog/index/');
            }
        }
        $this->display('blog/blog.add.php',array(
            'type'=>$this->calss
        ));
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
            'type'=>$this->calss
        ));
    }
    
    public function delete(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $_rule['exact']['id'] = $id;
        $result = Run::Model('blog')->del($_rule);
        if($result){
            $this->redirect('删除成功', Root.'blog/index/');
        }
    }
    
    //编辑器上传功能
    public function uploadimage(){
        require './'.Resource.'UEditor/php/Uploader.class.php';
        $js_config_json = './'.Resource.'UEditor/php/config.json';
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($js_config_json)), true);
        $action = $_GET['action'];
        
        $base64 = "upload";
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                 $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                $up = new Uploader($fieldName, $config, $base64); //生成上传实例对象并完成上传 
                $result = json_encode($up->getFileInfo());//返回数据
                break;
            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
            }
            echo $result;/* 输出结果 */
        }
}

