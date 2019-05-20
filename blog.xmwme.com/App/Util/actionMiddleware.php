<?php
/**
 * actionMiddleware  action中间件用于公共事务及数据处理；
 * @author Jimmy Wang <1105235512@qq.com>
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
    
    //博客分类
    public $calss = array('1'=>'PHP','2'=>'Python','3'=>'Mysql','4'=>'Java','5'=>'生活感悟');
    
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
            //$this->login_user = getAuth('all');
        } elseif (!isset($this->nologin[$mod]) || (isset($this->nologin[$mod]) && !in_array($action, $this->nologin[$mod]))) {//登录操作
        }
        $this->get_blog_date();
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
    
    /**
     * 博客归档日期
     */
    private final function get_blog_date(){
        $sql = "SELECT id,FROM_UNIXTIME(a.created,'%Y-%m') AS NF,COUNT(*) AS num FROM `xm_blog` a GROUP BY NF";
        $data = Run::Model('blog')->queryAll($sql);
        $this->set('blog_date',$data);
        $this->set('blog_type',$this->calss);
    }

}

?>