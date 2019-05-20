<?php

/**
 * memcache缓存映射类
 * 使用方法
 * $bool = FacadeMem::set('names', 'wwssqq', 60);
 * $data = FacadeMem::get('names');
 */
class FacadeMem {

    public static $expire = 86400;

    public static function getCacheObj() {
        static $import = true;
        static $cache = null;
        if ($import) {
            require_once(Lib . '/Util/ICache.php');
            require_once(Lib . '/Util/MmCache.php');
        }
        $import = false;
        if ($cache == null) {
            $cache = new MmCache();
            $cache->prefix = 'run_';
            $cache->expire = 18000;
            $cache->compressed = true;
            $cache->configFile = '../Config/memcache.config.php';
        }
        return $cache;
    }

    /**
      +----------------------------------------------------------
     * 写入缓存
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @param string  $key     缓存键值
     * @param mixed   $value   被缓存的数据
     * @param mixed   $expire  缓存时间
      +----------------------------------------------------------
     * @return boolean
      +----------------------------------------------------------
     */
    public static function set($key, $value, $expire = 0) {
        $cache = self::getCacheObj();
        return $cache->set($key, $value, $expire);
    }

    public static function get($key) {
        $cache = self::getCacheObj();
        return $cache->get($key);
    }

}
