<?php
/**
 * @author jimmy
 * @date 2018-01-25
 * 游戏模块
 */
class GameAction extends actionMiddleware
{   
    public function index(){
        $model = M('activity');
        $rule['exact']['status'] = 1;
        $rule['limit'] = 4;
        $data = $model->findTop($rule);
        $this->display('game/game.index.php',array('data'=>$data));
    }
}

