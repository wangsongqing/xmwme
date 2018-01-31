<?php

/**
 * @author Jimmy Wang <jimmywsq@163.com>
 * banner管理
 * @date 2018-01-31
 */
class BannerAction extends actionMiddleware {

    public function index() {
        extract($this->input);
        $isSearch = isset($isSearch) ? $isSearch : '';
        $model = M('banner');
        $rule = array();
        if ($isSearch) {
            isset($beginTime) && isset($endTime) && $rule['other'] = 'created>=' . strtotime($beginTime) . ' AND created<' . strtotime($endTime);
        }
        $data = $model->findAll($rule, '*', 0, 0);
        $this->display('banner/banner.index.php', array('result' => $data));
    }

    public function add() {
        extract($this->input);
        $activity_id = isset($activity_id) ? $activity_id : '';
        $status = isset($status) ? $status : '';
        $banner_name = isset($banner_name) ? $banner_name : '';
        $url = isset($url) ? $url : '';
        $filename = isset($filename) ? $filename : '';
        if ($isPost) {
            if (empty($activity_id)) {
                $this->redirect('请选择活动!', Root . "banner/add/");
            }
            if (empty($status)) {
                $this->redirect('请活动状态!', Root . "banner/add/");
            }
            if (empty($banner_name)) {
                $this->redirect('请填写banner名称!', Root . "banner/add/");
            }
            if (empty($url)) {
                $this->redirect('请填写跳转url!', Root . "banner/add/");
            }
            if (empty($filename)) {
                $this->redirect('请上传图片!', Root . "banner/add/");
            }
            $img_url = $this->upload();//上传图片
            $data = array(
                'activity_id' => $activity_id,
                'banner_name' => $banner_name,
                'url' => $url,
                'img_url' => $img_url,
                'status' => $status,
                'created' => time(),
                'updated' => time(),
            );
            $insert_id = M('banner')->add($data);
            if($insert_id){
                $this->redirect('添加成功!', Root . "banner/index/");
            }else{
                $this->redirect('添加失败!', Root . "banner/index/");
            }
        }
        //获取活动
        $this->display('banner/banner.add.php');
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
            return  $img_url;
        }
        return '';
    }

}
