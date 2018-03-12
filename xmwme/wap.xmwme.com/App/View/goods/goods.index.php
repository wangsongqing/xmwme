<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>商城</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link href="/Resource/css/common.css?xEl2SF0q" type="text/css" rel="stylesheet">
        <link href="/Resource/css/pages.css?xEl2SF0q" type="text/css" rel="stylesheet">
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
    </head><body>
        <link href="/Resource/css/shop.css?3MjKZ6Ft" type="text/css" rel="stylesheet">
        <div class="container-fluid">
            <header class="head">
                <div class="inner">
                    <p class="name">红包(元)</p>
                    <div class="text"><a href="/redbag/index/"><i class="icon-bean"></i><span class="amount"><?=isset($credit['red_bag'])?$credit['red_bag']:0?></span><i class="icon-arrow"></i></a>
                    </div>
                </div>
            </header>
            <div class="category">
                <ul>
                    <!--<li><a href="/game/index/"><img src="/Resource/images/shop/voucher.png?3MjKZ6Ft"><p>红包游戏</p></a></li>-->
                    <!--<li><a href="/goods/lists/"><img src="/Resource/images/shop/gift.png?3MjKZ6Ft"><p>礼品兑换</p></a></li>-->
                    <li><a href="/game/index/"><img src="/Resource/images/shop/game.png?3MjKZ6Ft"><p>红包游戏</p></a></li>
                </ul>
            </div>
            <div class="item item-recommend">
                <div class="hd">
                    <h3><i class="icon-gift"></i>热门礼品</h3>
                    <!--<a href="/goods/lists/" class="more">更多&gt;</a>-->
                </div>
                <div class="bd">
                    <div class="shop-list">
                        <ul>
                            <?php foreach($goods as $value){ ?>
                            <li>
                                <div class="inner">
                                    <a href="/goods/detail/?id=<?=$value['id']?>">
                                        <img src="<?=$value['list_pic']?>">
                                        <p><?=$value['goods_name']?></p>
                                        <span class="c-red"><?=$value['credit']?></span>元
                                    </a>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
           <?php require(View.'/layout/footer.html'); ?>

            <script src="/Resource/js/zepto.min.js"></script>
            <script src="/Resource/js/plugins.js"></script>
            <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
            <script src="/Resource/js/base.js?d=1711221436"></script>

            <span style="display:none">
                <img src="https://c.cnzz.com/wapstat.php?siteid=1256636065&r=https%3A%2F%2Fwap.bbqb.cn%2Faccount%2Findex%2F&rnd=2132136520" width="0" height="0"/><script src="https://s95.cnzz.com/z_stat.php?id=1256636065&web_id=1256636065" language="JavaScript"></script>
            </span>

            <script src="/Resource/js/swiper.min.js"></script>
            <script>
            var aregister = false;
            function exchange(gid) {//兑换投资
                if (aregister == true)
                    return false;
                layer.open({
                    content: "您是否确认使用宝贝豆兑换游戏次数？", //文字
                    btn: ['确认', '取消'], //按钮
                    yes: function() { //确定
                        BB.popup.loading.show();
                        $.ajax({
                            type: "post",
                            url: '/goods/detail/',
                            dataType: 'json',
                            data: {'gid': gid, 'num': 1, 'rand': Math.random()},
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                BB.popup.loading.hide();
                                aregister = false;
                                BB.popup.alert('网络异常,请稍后重试');
                            },
                            success: function(data) {
                                BB.popup.loading.hide();
                                aregister = false;
                                if (data.err < 0) {
                                    BB.popup.alert(data.msg);
                                } else {
                                    location.href = data.url;
                                }
                            }
                        })
                    }
                });
            }
            </script>
    </body>

</html>


