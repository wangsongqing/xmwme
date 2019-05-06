<?php

class come_wx_numModel extends modelMiddleware
{
    public $tableKey = 'come_wx_num';
    public $pK = 'id';
    public $cache = true;
    
    public function changeNum(){
        $arr = array(
            'num'=>1,
            'ip'=>getIp(),
            'created'=>date('Y-m-d H:i:s'),
        );
        M('come_wx_num')->add($arr);
    }
}

