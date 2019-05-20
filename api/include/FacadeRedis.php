<?php

/**
 * Redis 门面操作类
 * $bool = FacadeRedis::hSet('databases', 'table',['1name'=>'wmd']);
 * $data = FacadeRedis::hGet('databases','table');
 */
class FacadeRedis {

    //获取redis对象
    private static function connect($key = '') {
        static $import = true;
        static $cache = null;
        if ($import) {
            require_once(Lib . '/Db/RunRedis.php');
        }
        $import = false;
        if ($cache == null) {
            require_once '../Config/redis.config.php';
            $config = ['host' => $host, 'port' => $port, 'auth' => $auth];
            $cache = new RunRedis($config);
        }
        return $cache;
    }

    public static function __callStatic($name, $arguments) {
        $obj_redis = self::connect();
        $count = count($arguments);
        switch ($count) {
            case 1:
                return $obj_redis->$name($arguments[0]);
                break;
            case 2:
                return $obj_redis->$name($arguments[0], $arguments[1]);
                break;
            case 3:
                return $obj_redis->$name($arguments[0], $arguments[1], $arguments[2]);
                break;
            case 4:
                return $obj_redis->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                break;
            default:
                break;
        }
    }

}
