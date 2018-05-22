<?php
/**
 * actionMiddleware  action中间件用于公共事务及数据处理；
 * @author Jimmy Wang <jimmywsq@163.com>
 * @version      	V1.0
 * @time        	17-06
 */
class actionMiddleware extends Action {

    public $result = null; //错误信息
    public $login_user = array(); //用户登录信息
    //菜单列表
    public $menuArr = array(
        '用户管理' => array('index','log'),
        '数据管理' => array('banner','activity','goods','orders'),
    );

    /**
     * 控制器所依赖模型 -- 定义后台所有使用到的Model引用
     * @access 	public
     */
    public $models = array();

    public function __construct() {
        require_once '../api/OB/run.class.php';
        $this->models = actionModels();
        Run::set('OB', $this);
    }

    /**
     * 获取当前操作Model
     */
    protected final function getModel() {
        $default = 'index';
        if (defined('IsRewrite') && IsRewrite) { //是否开启地址重写
            $url = explode('/', $_SERVER['REQUEST_URI']);
            $ext = substr($url[1], 0, 1);
            if (empty($url[0]) && $ext == '?') {
                $mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : $default;
            } else {
                $temp = array();
                foreach ($url as $k => $v)
                    if ($v != null)
                        $temp[] = strtolower(trim($v));
                $key = Root == '/' ? 0 : count(explode('/', Root)) - 2;
                $mod = isset($temp[$key]) ? $temp[$key] : $default;
            }
        }
        else {
            $mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : $default;
        }
        return $mod;
    }

    /**
     * 获取当前操作Action
     * @access public
     */
    protected final function getAction() {
        $default = 'index';
        if (defined('IsRewrite') && IsRewrite) {
            $url = explode('/', $_SERVER['REQUEST_URI']);
            $temp = array();
            foreach ($url as $k => $v)
                if ($v != null)
                    $temp[] = strtolower(trim($v));
            $key = Root == '/' ? 0 : count(explode('/', Root)) - 2;
            $action = isset($temp[$key + 1]) ? $temp[$key + 1] : $default;
        }
        else {
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : $default;
        }
        return $action;
    }

    /**
     * 初始化相关参数，预先检查，用于公共检测，在继承中不能重写，在加载action之前做得工作可以放到这里
     * @access public
     */
    protected final function before() {

        extract($this->input);

        $mod = getModule();
        $action = getAction();

        //获取用户登录信息
        if (loginCheck()) {
            $this->get_admin_menu();
            $this->login_user = getAuth('all');
        } elseif (!isset($this->nologin[$mod]) || (isset($this->nologin[$mod]) && !in_array($action, $this->nologin[$mod]))) {//登录操作
        }
    }

    /**
     * 重载 Object 里面的 import
     * @param mixed $name
     */
    function import($name = null) {
        static $DBS = array();
        $ob = parent::import($name); //执行  Object   import 
        //如果返回空，说明 没有框架里面的model映射和 model文件 
        if ($ob == null) {
            if (isset($DBS[$name])) {
                return $DBS[$name];
            } else {
                $dbMapping = $this->com('dt')->tbl;
                if (isset($dbMapping[$name])) {
                    //$tableKey  $pK     $cached $expire
                    $DB = new modelMiddleware();
                    $DB->tableKey = $name;
                    $DB->com = $this->com;
                    $DB->mf = $this;
                    $DB->input = $this->input;
                    $DBS[$name] = $DB;
                    return $DB;
                }
            }
            return null;
        }
        return $ob;
    }

    /**
     * @param number $err
     * @param unknown $msg
     * @param string $url
     * @param string $data
     */
    protected function praseJson($err = 0, $msg, $url = '', $data = '') {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $msg = array('err' => $err, 'msg' => $msg, 'url' => $url, 'data' => $data);
            die(json_encode($msg));
        } else {
            if ($url) {
                header('Location:' . $url);
                exit;
            }
            header('Location:' . Root);
            exit;
        }
    }

    protected function get_admin_menu() {
        $userInfo = $this->login_user;
        $grouFile = CacheDir . '/groups.php';
        if (!file_exists($grouFile)) {
            showMsg("权限组管理数据错误，请联系管理员!", Root);
            exit;
        }
        require($grouFile);                  //取得权限组配置
        //存储菜单项
        $htmlmenu = array();
        $subnav = array();
        $abc = '';
        $menupms = $groups;
        unset($groups);
        foreach ($this->menuArr as $key => $value) {
            $htmlstring = '';
            foreach ($value as $val) {
                //检查菜单栏目是否存在
                if (array_key_exists($val, $menupms)) {
                    //多级菜单列 - 只支持2级菜单列表，不支持无限级菜单；
                    foreach ($menupms[$val]['permition'] as $_key => $_val) {
                        $className = '';
                        if (getModule() == $menupms[$val]['convert'] && getAction() == strtolower($_key)) {
                            $className = " class='current' ";
                            $abc = $key;
                        } elseif (getModule() != $menupms[$val]['convert'] && $val == getModule()) {
                            //$className = " class='current' ";
                            $abc = $key;
                        }
                        //如果有地址映射，则采用地址映射；
                        if (isset($urlmenu[$val])) {
                            //是否选中的菜单栏
                            $htmlstring .= "<dd " . $className . "><a href='" . Root . $urlmenu[$val] . "' >" . $_val['convert'] . "</a></dd>";
                        } else {  //如果没有设置转换地址，则地址由Model+Action组合构成
                            $htmlstring .= "<dd " . $className . "><a href='" . Root . $val . "/" . $_key . "/' >" . $_val['convert'] . "</a></dd>";
                        }
                    }
                    $kk = array_keys($menupms[$val]['permition']);
                    $headermenu[$key] = !empty($headermenu[$key]) ? $headermenu[$key] : Root . $menupms[$val]['convert'] . "/" . $kk[0] . "/";
                }
            }
            if (in_array(getModule(), $value)) {
                $abc = $key;
            }
            !empty($htmlstring) ? $htmlmenu[$key] = $htmlstring : '';
            $abc == $key ? $subnav[$key] = $htmlstring : '';
        }
        $mc = getCookie('mc');
        $mca = !empty($mc) ? explode('|', $mc) : array();
        //当前操作菜单栏目
        setFileVar("mca", $mc);
        setFileVar("htmlmenu", $htmlmenu);
        setFileVar("subnav", $subnav); 
        setFileVar("headermenu", $headermenu);
    }

}

?>