<?php

/**
  +---------------------------------------------------------------------------------------------------------------
 * 菜单数据操作
  +---------------------------------------------------------------------------------------------------------------
 */
class manage_logModel extends modelMiddleware {

    /**
     * 数据表key
     */
    public $tableKey = 'manage_log';
    public $cached = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'id';

    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model() {
        $model = M('manage_log');
        return $model;
    }

    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function manage_log_revision($id) {
        $sql = sprintf("select * from %s where `id` = '$id'", $this->getTable($this->tableKey, 0));
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

?>