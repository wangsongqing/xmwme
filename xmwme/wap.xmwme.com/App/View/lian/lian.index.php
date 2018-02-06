<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>连连看</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link href="<?= Resource ?>css/common.css" type="text/css" rel="stylesheet">
        <link href="<?= Resource ?>activity/lianliankan/css/style.css?DO47TUtf" type="text/css" rel="stylesheet"/>
        <script>(function(c) {
                var f = window, e = document, a = e.documentElement, b = "orientationchange" in window ? "orientationchange" : "resize", d = function() {
                    var h = a.clientWidth;
                    if (!h) {
                        return
                    }
                    var g = 20 * (h / 320);
                    a.style.fontSize = (g > 40 ? 40 : g) + "px"
                };
                if (!e.addEventListener) {
                    return
                }
                f.addEventListener(b, d, false);
                d()
            })();</script>
    </head>
    <body>
        <div class="game-w">
            <div class="bg"></div>
            <div class="game-start">
                <div class="head">
                    <a href="javascript:;" class="link-rule">活动规则</a>
                    <a href="<?= Root ?>lian/myscore/" class="link-score">我的成绩</a>
                </div>
                <div class="pic-name"></div>
                <div class="panel-gift">
                    <div class="tit">
                        <i class="bg1"></i>
                        <i class="bg2"></i>
                    </div>
                    <i class="icon-tree"></i>
                    <div class="outer">
                        <div class="inner">
                            <?php if (!empty($data)) { ?>
                                <div class="more">
                                    <a href="<?= Root ?>lian/numscoreorder/">更多排行</a>
                                </div>
                                <ul class="gift-list">
                                    <?php
                                    $i = 1;
                                    foreach ($data as $value) {
                                        ?>
                                        <li>
                                            <div class="col col-1"><span class="num num-<?= $i ?>"><?= $i ?></span><?= isset($value['nick']) && !empty($value['nick']) ? $value['nick'] : '小宝' ?></div>
                                            <div class="col col-2"><?= isset($value['telephone']) ? $value['telephone'] : '' ?></div>
                                            <div class="col col-3"><?= isset($value['total_score']) ? $value['total_score'] : '0' ?>分</div>
                                        </li>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </ul>
                            <?php } else { ?>
                                <p class="empty">亲，榜上有名，就差你哦！</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="btns">
                    <a href="javascript:;" class="btn" id="btn_start"></a>
                    <p>本活动权归run所有</p>
                </div>
            </div>
            <div class="game-loading" id="loading">
                <div class="box">
                    <i class="baby"></i>
                    <div class="progress" id="progress"><i></i></div>
                    <p>加载中，请稍候...</p>
                </div>
            </div>
            <div id="countdown"><div class="box"><i></i></div></div>
            <div class="game-scene">
                <div class="game-main">
                    <div class="head">
                        <div class="time"><p><span id="time"></span>秒</p></div>
                        <div class="score"><p id="score"></p></div>
                    </div>
                    <div class="box" >
                        <table id="icons_box"></table>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="btn-reset" data-status="2"></a>
                        <a href="javascript:;" class="btn-voice" data-status="on"></a>
                    </div>
                </div>
                <div class="game-over"></div>
            </div>
        </div>
        <input type='hidden' value='<?= $goods_id ?>' id='goods_id'><!--商城购买连连参数-->
        <script type="text/template" id="popup_rule">
            <div class="popup-rule">
            <h2>活动规则</h2>
            <ul>
            <li><span>1.</span><p>点击相同图案的两个图标，如果图标能用3根及以下的直线连起来，就成功消除；</p></li>
            <li><span>2.</span><p>每次游戏<?= $game_time / 1000 ?>秒，每消除1对图标，得<?= $game_score ?>分；成功消除所有图标，赠送<?= $game_floop ?>倍得分；</p></li>
            <li><span>3.</span><p>游戏结束后根据得分奖励现金红包、投资红包或宝贝豆。</p></li>
            <li><span>4.</span><p>每日游戏次数：免费赠送1次；当日投资宝贝计划增加1次；当日邀请好友注册关注增加1次。每日限3次。</p></li>
            </ul>
            </div>
        </script>
        <script src="<?= Resource ?>js/zepto.min.js"></script>
        <script src="<?= Resource ?>js/base.js"></script>
        <script src="<?= Resource ?>js/layer/layer.m.js"></script>
        <script src="<?= Resource ?>activity/lianliankan/js/jWebAudio.min.js"></script>
        <script src="<?= Resource ?>activity/lianliankan/js/main.js?DO47TUtf"></script>
        <script>
            LLKGame.init({
                time:<?= $game_time ?>,
                price:<?= $game_score ?>,
                redEnvelopesNum: 2,
                beansNum: 2
            })
        </script>
    </body>
</html>