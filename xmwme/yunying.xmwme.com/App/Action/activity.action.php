<?php

/**
 * @author Jimmy Wang <jimmywsq@163.com>
 * activity管理
 * @date 2018-02-03
 */
class ActivityAction extends actionMiddleware {

    public function index() {
        extract($this->input);
        $rule = array();
        if ($isPost && isset($isSearch)) {
            isset($activity_name) && $rule['exact']['activity_name'] = $activity_name;
        }
        $model = M('activity');
        $data = $model->findAll($rule);
        $this->display('activity/activity.index.php', array('data' => $data));
    }
    
    public function add(){
        extract($this->input);
        if($isPost){
            if (empty($activity_name))
                $this->redirect('活动名称不能为空!', Root . "activity/add/");
            if (empty($start_time))
                $this->redirect('开始时间不能为空!', Root . "activity/add/");
            if (empty($end_time))
                $this->redirect('结束时间不能为空!', Root . "activity/add/");
            if (empty($url))
                $this->redirect('活动链接不能为空!', Root . "activity/add/");
            $_data = array(
                'key'=>$key,
                'activity_name' => $activity_name,
                'start_time' => strtotime($start_time),
                'end_time' => strtotime($end_time),
                'status' => $status,
                'url' => $url,
                'created'=>time(),
                'updated'=>time(),
            );
             if (!empty($filename)) {
                $img_url = $this->upload(); //上传图片
                $_data['img_url'] = $img_url;
            }
            $model = M('activity');
            $re = $model->add($_data);
            if($re){
                $model->activity_revision($re);
                $this->redirect('添加成功!', Root . "activity/index/");
            }
        }
        $this->display('activity/activity.add.php');
    }

    public function edit() {
        extract($this->input);
        $id = isset($id) ? $id : '';
        if (empty($id))
            $this->redirect('参数错误', Root . 'activity/index/');
        $model = M('activity');
        $data = $model->find($id);
        if ($isPost) {
            if (empty($activity_name))
                $this->redirect('活动名称不能为空!', Root . "activity/edit/?id={$id}");
            if (empty($start_time))
                $this->redirect('开始时间不能为空!', Root . "activity/edit/?id={$id}");
            if (empty($end_time))
                $this->redirect('结束时间不能为空!', Root . "activity/edit/?id={$id}");
            if (empty($url))
                $this->redirect('活动链接不能为空!', Root . "activity/edit/?id={$id}");
            $_data = array(
                'activity_name' => $activity_name,
                'start_time' => strtotime($start_time),
                'end_time' => strtotime($end_time),
                'status' => $status,
                'url' => $url,
                'updated'=>time(),
            );
            if (!empty($filename['tmp_name'])) {
                $img_url = $this->upload(); //上传图片
                $_data['img_url'] = $img_url;
            }
            $_rule['exact']['id'] = $id;
            $re = $model->edit($_data,$_rule);
            if($re){
                $model->activity_revision($id);
                $this->redirect('编辑成功!', Root . "activity/index/");
            }
        }
        $this->display('activity/activity.edit.php', array(
            'data' => $data,
            'id' => $id,
        ));
    }
    
    public function delete(){
        extract($this->input);
        $id = isset($id)?$id:'';
        if($id){
            $rule['exact']['id'] = $id;
            $model = M('activity')->del($rule);
            if($model){
                $model->banner_revision($id);//刷新缓存
                $this->redirect('删除成功!', Root . "activity/index/");
            }else{
                $this->redirect('删除失败!', Root . "activity/index/");
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
        if (!empty($fname)) {
            $img_url = Resource . "uplodes" . '/' . $fi;
            return WebUrl . $img_url;
        }
        return '';
    }

}
