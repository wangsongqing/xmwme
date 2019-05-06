<?php

/**
  +---------------------------------------------------------------------------------------------------------------
 * Run Framework 文件加载器
  +---------------------------------------------------------------------------------------------------------------
 * @date    2017-7
 * @author  Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
  +---------------------------------------------------------------------------------------------------------------
 */
class Loader {

    public $fileList = null;

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
        $this->fileList = null;
    }

    /**
      +----------------------------------------------------------
     * 动态加载文件
      +----------------------------------------------------------
     * @access public 
      +----------------------------------------------------------
     */
    public function load() {
        //动态加载文件列表
        if ($this->fileList != null && is_array($this->fileList)) {
            foreach ($this->fileList as $file) {
                if (file_exists($file)){
                    require_once($file);
                }
            }
        }
    }

}

?>