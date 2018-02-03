<?php

/**
 * @author jimmy Wang <jimmywsq@163.com>
 * activity model
 * @date 2018-02-03
 */
class ActivityModel extends modelMiddleware {

    public $tableKey = 'activity'; //数据表key
    public $cached = true; //是否读取缓存
    public $pK = 'id'; //数据表主键Id名称

    /**
     * 数据操作模型
     * @return object
     */

    public static function _model() {
        $model = M('activity');
        return $model;
    }

    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function activity_revision($id) {
        $sql = sprintf("select * from %s where `id` = '$id'", $this->getTable('activity', 0));
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

}
