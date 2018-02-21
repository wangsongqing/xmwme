<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>新萌</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="宝宝理财,儿童理财,亲子理财,家庭理财">
        <meta name="description" content="宝宝钱包是一款针对育儿用户的智能投顾工具，通过这个工具用户能方便快捷的进行投资，实现育儿资金的增值保值。宝宝钱包由获得软银中国资本注资的上海易贷网金融信息服务有限公司运营。">
        <link href="/Resource/css/common.css?X0uvJJXk" type="text/css" rel="stylesheet">
        <link href="/Resource/css/pages.css?X0uvJJXk" type="text/css" rel="stylesheet">
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
    </head><script src="/Resource/js/fixed/treatment.js?201707201038"></script>
    <body class="pg-asset-y" id="nofast">
        
        <div class="text">
            我的订单
        </div>
        <div class="lists">
            <div class="hd">
                <ul></ul>
            </div>
            <div class="bd">
                <div class="list-plan" id="statement_page">
                    <?php foreach($orders['record'] as $v){ ?>
                    <dl>
                        <dt>
                        <img src="<?=isset($v['goods_img'])?$v['goods_img']:''?>"><br>
                        </dt>
                        <dd>
                            <div class="tit"><span class="big"></span>商品名称：<span class="sub c-999"><?=isset($v['goods_name'])?$v['goods_name']:''?></span>
                            </div>
                            <ul>
                                <li><span class="n">下单时间：</span><span><?=isset($v['created'])?date('Y-m-d H:i:s',$v['created']):'0'?></span></li>
                                <li><span class="n">订单号：</span><span class="data"><?=isset($v['orders_num'])?$v['orders_num']:''?></span></li>
                                <li><span class="n">商品个数(个)：</span><span class="data"><?=isset($v['goods_num'])?$v['goods_num']:''?></span></li>
                                <li><span class="n">消费积分：</span><span class="data"><?=isset($v['credit'])?$v['credit']:''?></span></li>
                                <li><span class="n">订单状态：</span><span class="data"><?=isset($v['is_get'])&&$v['is_get']==1?'出货':'未出货'?></span></li>
                                <!--<li><span class="n">快递单号：</span><span class="data">4546565555756756767</span></li>-->
                            </ul>
                        </dd>
                    </dl>
                    <?php } ?>
                </div>
            </div>
        </div>
        <a href="/user/index/" class="btn-home"><i></i></a>  <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
    </body>
</html>