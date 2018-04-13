<?php

/**
 * @author jimmy Wang <jimmywsq@163.com>
 * activity model
 * @date 2018-02-05
 */
class Activity_logModel extends modelMiddleware {

    public $tableKey = 'activity_log'; //数据表key
    public $cached = true; //是否读取缓存
    public $pK = 'id'; //数据表主键Id名称

    /**
     * 数据操作模型
     * @return object
     */

    public static function _model() {
        $model = M('activity_log');
        return $model;
    }

    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function activity_log_revision($id) {
        $sql = sprintf("select * from %s where `id` = '$id'", $this->getTable('activity_log', 0));
        $member = $this->getRow($sql);
        if (empty($member)) {
            $this->revisionKey = array("{all:all}");
        } else {
            extract($member);
            //为数据查询key
            $this->revisionKey = array(
                "{all:all}",
                "{id:$id}",
            );
        }
        $this->revision();
    }
    
    /**
     * add data in activity_log
     * @param int $user_id
     * @param int $activity_id
     */
    public function add_activity_log($user_id,$activity_id){
        $time = time();
        $_data = array(
            'user_id'=>$user_id,
            'activity_id'=>$activity_id,
            'created'=>$time,
            'updated'=>$time,
        );
        $insert_id = self::_model()->add($_data);
        return $insert_id;
    }

}
