<?php
/**
 * @author jimmy
 * @date 2018-01-25
 * 游戏模块
 */
class GameAction extends actionMiddleware
{   
    public function index(){
        $this->display('game/game.index.php');
    }
}

