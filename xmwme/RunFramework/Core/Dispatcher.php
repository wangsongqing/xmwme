<?php

/**
  +---------------------------------------------------------------------------------------------------------------
 * Run Framework 调度器
  +---------------------------------------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
  +---------------------------------------------------------------------------------------------------------------
 */
class Dispatcher implements IDispatcher {

    public $mapPath = null;                  //重新影射新的地址
    public $rootPath = '/';                   //应用程序根路径
    public $param = array();               //输入参数
    public $module = 'IndexAction';         //控制器模块类名
    public $action = 'index';               //控制器缺省方法
    public $isRewrite = false;                 //是否url重写
    public $configFile = null;      //自定义路由规则配置文件
    public $exceptionModule = null;                  //发生异常时的控制器
    private $moduleFile = null;                  //控制器文件
    private $actionPath = 'Action';              //控制器所在目录

    /**
      +----------------------------------------------------------
     * 类的构造子
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */

    public function __construct() {
        
    }

    /**
      +----------------------------------------------------------
     * 类的析构方法(负责资源的清理工作)
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    public function __destruct() {
        $this->mapPath = null;
        $this->rootPath = null;
        $this->module = null;
        $this->action = null;
        $this->isRewrite = null;
        $this->isCached = null;
    }

    /**
      +----------------------------------------------------------
     * 属性访问器(写)
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    public function __set($name, $value) {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    /**
      +----------------------------------------------------------
     * 解析URL参数
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     * @return void 
      +----------------------------------------------------------
     */
    public function parseReq() {
        if ($this->isRewrite) {
            $fullUrl = $_SERVER['REQUEST_URI'];
            $filename = $_SERVER['SCRIPT_NAME'];
            $getArgs = $_SERVER['QUERY_STRING'];
            $searchStr = array($filename, '?' . $getArgs, 'index.php', $this->module . '.php');
            $url = str_replace($searchStr, '', $fullUrl);
            $url = explode('/', $url);

            $urltemp = array();
            foreach ($url as $k => $v)
                if ($v != null)
                    $urltemp[] = $v;

            if ($this->mapPath == null) {
                $key = $this->rootPath == '/' ? 0 : count(explode('/', $this->rootPath)) - 2;
                $mod = isset($urltemp[$key]) ? $urltemp[$key] : $this->module;
                if (isset($urltemp[$key + 1]) && strpos($urltemp[$key + 1], '?') === false)
                    $this->action = $urltemp[$key + 1];
            }
            else {
                $key = $this->rootPath == '/' ? 1 : count(explode('/', $this->rootPath)) - 1;
                $mod = isset($urltemp[$key]) ? $urltemp[$key] : $this->module;
                if (isset($urltemp[$key + 1]) && strpos($urltemp[$key + 1], '?') === false)
                    $this->action = $urltemp[$key + 1];
            }

            //重新定义url规则
            $req = $this->rewrite($mod, $this->action, $this->param);
            $mod = $req['mod'];
            $this->action = $req['action'];
            $this->param = $req['param'];
        }
        else {
            $mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : $this->module;
            if (isset($_REQUEST['action']))
                $this->action = $_REQUEST['action'];
        }

        if ($mod != $this->module)
            $this->module = ucfirst($mod) . 'Action';  //限制action类名

            
//新增(2009.12.08)
        if ($mod == $this->module) {
            $mod = substr($mod, 0, strlen($mod) - 6);
            $file = $this->actionPath . '/' . strtolower($mod) . '.action' . '.php';
        } else {
            $file = $this->actionPath . '/' . strtolower($mod) . '.action' . '.php';
        }


        //在控制器目录查找请求的目标文件
        if (file_exists($file)) {
            $this->moduleFile = $file;
            $file = null;
        } else {
            if ($mod != 'Exception' && $mod != 'favicon.ico') {
                RunException::writeLog("控制器 $mod 未找到!", date('Y-m-d').'_'.'error.log');
            }
            $mod = substr($this->exceptionModule, 0, strlen($this->exceptionModule) - 6);
            $file = $this->actionPath . '/' . strtolower($mod) . '.action' . '.php';
            if (file_exists($file)) {
                $this->moduleFile = $file;
                $this->module = $this->exceptionModule;
                $this->action = 'index';
                $file = null;
                $this->exceptionModule = null;
            } else {
                RunException::throwException("控制器 $mod 未找到!");
            }
        }
    }

    /**
      +----------------------------------------------------------
     * 重新定义框架重写规则
      +----------------------------------------------------------
     * @access	public 
      +----------------------------------------------------------
     * @param	string	$mod	控制器名
      +----------------------------------------------------------
     * @param	string	$action	请求的操作
      +----------------------------------------------------------
     * @param	array	$param	待构造的参数
      +----------------------------------------------------------
     * @return	array
      +----------------------------------------------------------
     */
    private function rewrite($mod, $action, $param) {
        if (!file_exists($this->configFile)) {
            return array(
                'mod' => $mod,
                'action' => $action,
                'param' => $param,
            );
        }

        require($this->configFile);
        if (!isset($rules) || empty($rules)) {
            return array(
                'mod' => $mod,
                'action' => $action,
                'param' => $param,
            );
        }

        $url = $_SERVER['REQUEST_URI'];
        foreach ($rules as $rule) {
            $bool = false;
            if (preg_match($rule[0], $url)) {
                $bool = true;
                $mod = isset($rule[1]['mod']) ? $rule[1]['mod'] : $mod;
                $action = isset($rule[1]['action']) ? $rule[1]['action'] : $action;
            }
            $hasAgr = isset($rule[2]) && is_array($rule[2]) && !empty($rule[2]) ? true : false;
            if ($bool && $hasAgr) {
                foreach ($rule[2] as $key => $value) {
                    if (!(strpos($value, '#') === FALSE)) {
                        preg_match("$value", $url, $result);
                        $param[$key] = isset($result[1]) ? $result[1] : '';
                    } else {
                        $param[$key] = $value;
                    }
                }
            }
        }

        return array(
            'mod' => $mod,
            'action' => $action,
            'param' => $param,
        );
    }

    /**
      +----------------------------------------------------------
     * 获取控制器文件名
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public function getFile() {
        return $this->moduleFile;
    }

    /**
      +----------------------------------------------------------
     * 获取控制器类名
      +----------------------------------------------------------
     * @access public 	
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public function getModule() {
        return $this->module;
    }

    /**
      +----------------------------------------------------------
     * 获取请求的操作
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public function getAction() {
        return $this->action;
    }

    /**
      +----------------------------------------------------------
     * 获取输入参数
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     * @return array
      +----------------------------------------------------------
     */
    public function getInput() {
        return $this->param;
    }

}

?>