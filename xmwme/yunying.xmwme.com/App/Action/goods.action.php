<?php

class GoodsAction extends actionMiddleware
{
    public function index(){
        extract($this->input);
        
        $rule = array();
        $goods = M('goods');
        $rule['order']['id'] = 'desc';
        $data = $goods->findAll($rule,'*',0);
        $this->display('goods/goods.index.php',array(
            'result'=>$data,
        ));
    }
    
    public function add(){
        extract($this->input);
        if($isPost){
            
            $data = array(
                'goods_name'=>$goods_name,
                'store'=>$store,
                'status'=>$status,
                'credit'=>$credit,
                'content'=>$content,
                'goods_type'=>$goods_type,
                'created'=>time(),
                'updated'=>time(),
            );
            $url = $this->upload();
            if(!empty($url)){
                $data['list_pic'] = $url;
                $data['detail_pic'] = $url;
            }
            $re = M('goods')->add($data);
            if($re){
                $this->redirect('添加商品成功', Root.'goods/index/');
            }
        }
        $this->display('goods/goods.add.php');
    }
    
    public function edit(){
        extract($this->input);
        $id = isset($id)?$id:0;
        $model = M('goods');
        if($isPost){
            $data = array(
                'goods_name'=>isset($goods_name)?$goods_name:'',
                'store'=>isset($store)?$store:'',
                'status'=>isset($status)?$status:0,
                'credit'=>isset($credit)?$credit:0,
                'content'=>isset($content)?$content:'',
                'goods_type'=>isset($goods_type)?$goods_type:1,
                'updated'=>time(),
            );
            if (!empty($filename['tmp_name'])) {
                $img_url = $this->upload();//上传图片
                $data['list_pic'] = $img_url;
                $data['detail_pic'] = $img_url;
            }
            $rule['exact']['id'] = $id;
            $re = $model->edit($data,$rule);
            if($re){
                $model->goods_revision($id);//刷新缓存
                $this->redirect('编辑成功', Root.'goods/index/');
            }
        }
        $data = $model->find($id);
        $this->display('goods/goods.edit.php',array(
            'data'=>$data,
            'id'=>$id
        ));
    }
    
    public function delete(){
        extract($this->input);
        $id = isset($id)?$id:'';
        if($id){
            $model = M('goods');
            $data = $model->find($id);
            $rule['exact']['id'] = $id;
            $re = $model->del($rule);
            if($re){
                $model->goods_revision($id);//刷新缓存
                if(!empty($data)){//删除的时候删除相关图片，防止图片占用过多的存储
                    $img_name = pathinfo($data['list_pic']);
                    $img_path = './Resource/uplodes/'.$img_name['basename'];
                    if(file_exists($img_path)){
                        unlink($img_path);
                    }
                }
                $this->redirect('删除成功!', Root . "goods/index/");
            }else{
                $this->redirect('删除失败!', Root . "goods/index/");
            }
        }
    }
    
    /**
     * 上传操作
     */
    private function upload() {
        extract($this->input);
        require_once Lib . '/Util/FileUpload.php';
        $cdirs = '.' . Resource . "uplodes";
        $up = new FileUpload(array('isRandName' => true, 'allowType' => array('txt', 'doc', 'php', 'gif', 'jpg', 'rar', 'png', 'xls'), 'FilePath' => $cdirs, 'MAXSIZE' => 2000000));
        $up->mkdirs($cdirs); //建立目录
        $fname = '';
        if ($up->uploadFile('filename')) {
            $fi = $up->getNewFileName();
            $fname = $cdirs . '/' . $fi;
        }
        if(!empty($fname)){
            $img_url = Resource . "uplodes" .'/'.$fi;
            return  WebUrl.$img_url;
        }
        return '';
    }
}

