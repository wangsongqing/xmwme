<?php
//获取redis对象
function R($key=''){
    static $import = true;
    static $cache  = null;
    if ($import)
    {
        require_once(Lib.'/Db/RunRedis.php');
	require_once(Lib.'/Util/RedisCrud.php');
    }
    $import = false;
    if ($cache == null)
    {
        $cache = new RedisCrud($key);
        $cache->configFile = 'Config/redis.config.php';
    }
    return $cache;
}

/**
 * 在名称为key的list头添加一个值为value的 元素
 * list类型的的操作
 * @param String $key
 * @param type $value
 * @return boolean
 */
function lpushVal($key,$value){
    if(!empty($key) && !empty($value)){
	$redis = R();
	$bool = $redis->lpush($key,$value);
	return $bool;
    }
    return false;
}

/**
 * 返回并删除名称为key的list中的尾元素
 * list类型的操作
 * @param type $key
 * @return boolean
 */
function getPushVal($key){
    if(!empty($key)){
	$redis = R();
	return $redis->rpop($key);
    }
    return false;
}

/**
 * 返回list 为key的值
 * @param type $key
 * @return boolean
 */
function getListVal($key){
    if(!empty($key)){
	$redis = R();
	return $redis->getlist($key);
    }
    return false;
}
