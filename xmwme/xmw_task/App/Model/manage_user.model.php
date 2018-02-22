<?php

/**
  +---------------------------------------------------------------------------------------------------------------
 * 菜单数据操作
  +---------------------------------------------------------------------------------------------------------------
 */
class manage_userModel extends modelMiddleware {

    /**
     * 数据表key
     */
    public $tableKey = 'manage_user';
    public $cached = false;

    /**
     * 数据表主键Id名称
     *
     */
    public $pK = 'admin_id';

    /**
     * @param $account 用户名
     * @param $password 密码
     * @param $type 后台类型  1管理后台，2运营后台，3财务后台
     * @param string $field
     * @return array
     */
    public function login($account, $password, $field = '*') {
        $table = $this->getTable($this->tableKey);
        $where = 'where ( admin_name="' . $account . '" AND password=MD5(CONCAT(MD5("' . $password . '"),salt))) ';
        $sql = "select $field from $table $where limit 1";
        return $this->getRow($sql);
    }

    /**
     * 设置登录状态
     */
    public function setLoginStatus($user) {
        setAuth($user, 0);
        setVar('manage_' . $user['admin_name'], $user['password'], 360000);
    }

    /**
     * 尽可能的在model里面做一切相关的数据处理
     * @return object
     */
    public static function _model() {
        $model = M('manage_user');
        return $model;
    }

    /**
     * 在本model里面引用别的model的试列
     * @return array
     */
    public function selectOne() {
        $model = M('manage_log');
        $data = $model->findOne(1);
        return $data;
    }

    /**
     * 数据验证
     */
    public function validateMolde() {
        $fields = array(
                    array('admin_name', 'require', '请填写登录名'),
                    array('mobile', 'require', '请填写手机号'),
                    array('realname', 'require', '请填写真实名字'),
                    array('mobile', 'phone', '请填写合格的手机号'),
                    array('password', 'require', '请填写登陆密码'),
        );
        return $fields;
    }

    /**
     * 刷新mem缓存
     * @param  $admin_id
     * @access public
     * @return void
     */
    public function manage_user_revision($admin_id) {
        $sql = sprintf("select * from %s where `admin_id` = '$admin_id'", $this->getTable('manage_user', 0, 0));
        $member = $this->getRow($sql);
        if (empty($member)) {
            $this->revisionKey = array("{all:all}");
        } else {
            extract($member);
            //为数据查询key
            $this->revisionKey = array(
                "{all:all}",
                "{admin_id:$admin_id}",
            );
        }
        $this->revision();
    }

}

?>