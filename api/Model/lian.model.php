<?php

/**
  +---------------------------------------------------------------------------------------------------------------
 * 连连看model模型
  +---------------------------------------------------------------------------------------------------------------
 */
class LianModel extends modelMiddleware {

    public $tableKey = 'lian'; //数据表key
    public $cached = true; //是否读取缓存
    public $pK = 'id'; //数据表主键Id名称

    /**
     * 数据操作模型
     * @return object
     */

    public static function _model() {
        $model = M('lian');
        return $model;
    }

    /**
     * 获取总榜排名前N的用户
     *
     * @param  unknown $start_time  活动开始时间
     * @param  unknown $end_time    活动结束时间
     * @param  int     $order_type  排序类型  0=>总分数 1=>总次数
     * @param  int     $limit       查询条数
     * @access public
     * @return array
     */
    public function get_lian_top($start_time, $end_time, $order_type = 0, $limit = 10) {
        $table = $this->getTable($this->tableKey);
        $key = '{lian:lian}';
        if ($order_type == 1) {
            $orderType = 'total_times desc';
            $where = '';
            $orderType2 = 'count(id) as total_times';
        } else {
            $orderType = 'total_score desc';
            $where = 'AND score > 0';
            $orderType2 = 'sum(score) as total_score';
        }
        $sql = "SELECT
                	nick,
                	telephone,
                	FROM_UNIXTIME(MAX(created)) as ma,
                	{$orderType2}
                FROM
                	{$table}
                WHERE
                	created >= {$start_time} and created <= {$end_time} {$where}
                GROUP BY
                	user_id
                ORDER BY
                	{$orderType} , ma asc
                limit {$limit}";
        return $this->db->getRows($sql, $key);
    }
    
    /**
     * 统计我的总成绩（总分数,总分数排名,总次数,总次数排名）
     *
     * @param  int     $user_id     用户ID
     * @param  unknown $start_time  活动开始时间
     * @param  unknown $end_time    活动结束时间
     * @param  int     $order_type  排序类型  0=>总分数 1=>总次数
     * @access public
     * @return array
     */
    public function get_my_score($user_id, $start_time, $end_time, $order_type = 0) {
	$table = $this->getTable($this->tableKey);
	$key = '{lian:lian}';
	$orderType = $order_type == 1 ? 'total_times DESC,created ASC' : 'total_score DESC,created ASC';

	$sql = "SELECT a.* FROM (
	           SELECT obj.*,@rownum := @rownum + 1 AS pm FROM ( SELECT user_id,telephone,nick,SUM(score) as total_score,count(user_id) as total_times,MAX(created) as created FROM {$table} where ( created >= $start_time AND created <= $end_time)
	           GROUP BY user_id ORDER BY {$orderType} ) AS obj ,(SELECT @rownum := 0) r  )  as a where user_id  = $user_id ";
	return $this->db->getRow($sql, $key);
    }
    
    /**
     * 参与记录
     * @param type $user_info
     * @param type $num
     * @param type $join_type
     */
    public function add_result($user_info,$num,$join_type=0){
        $flag = 0;
        $msg = '';
        try {
            $startTrans = self::_model()->startTrans();
            if(!$startTrans) throw new Exception('开启事务失败！');
            $activity_info = M('activity')->getActivity('lian');
            $score = $num * 5;
            $credit = format_money($score * 0.00075);
            $_data = array(
                'user_id'=>$user_info['user_id'],
                'telephone'=>$user_info['telephone'],
                'join_type'=>$join_type,
                'lian_num'=>$num,
                'score'=>$score,
                'red_bag'=>$credit,
                'created'=>time(),
                'updated'=>time(),
            );
            $re_lian = self::_model()->add($_data);
            if(!$re_lian) throw new Exception('活动数据参入失败！');
            $re_activity_log = M('activity_log')->add_activity_log($user_info['user_id'],$activity_info['id']);
            if(!$re_activity_log) throw new Exception('活动日志数据插入失败！');
            $commitTrans = self::_model()->commit();
            if(!$commitTrans) throw new Exception('提交事务失败！');
            $flag = 1;
        } catch (Exception $e) {
            $rollbackTrans = self::_model()->rollback();
            if(!$rollbackTrans) throw new Exception('事务回滚失败！');
            $msg = $e->getMessage();
            writeLog('用户:'.$user_info['user_id'].'连连看数据写入数据失败', 'lian.log');
        }
        return $flag;
    }
    
    /**
     * 检测用户今日可玩游戏否
     * @param type $user_id
     * @param type $play_type
     */
    public function user_can_play($user_id){
        $time = strtotime(date('Y-m-d'));//今天时间戳
        $ttime = strtotime("+1 day",strtotime(date('Y-m-d')));//明天时间戳
        $rule['exact']['user_id'] = $user_id;
        $rule['exact']['join_type'] = 0;
        $rule['other'] = "created>={$time} AND created<$ttime";
        $is_data = M('lian')->findOne($rule,'*',0);
        
        $rule['exact']['join_type'] = 1;
        $is_data_invet = M('lian')->findOne($rule,'*',0);//邀请好友的次数已经用完
        if(!empty($is_data)){
            if(!empty($is_data_invet)){
                return 2;//次数已经用完了
            }
            //判断用户有没有邀请好友
            $user_rule['exact']['from_user_id'] = $user_id;
            $user_rule['other'] = "created>={$time} AND created<$ttime";
            $invite = M('user_info')->findOne($user_rule,'*',0);
            if(!empty($invite)){
                return 3;
            }
           return 1;
        }

        
    }
}

?>