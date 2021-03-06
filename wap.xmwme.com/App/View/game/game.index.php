<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>玩游戏喽！</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="玩游戏喽">
        <meta name="description" content="玩游戏喽是一个趣味小游戏平台，你可以玩游戏领红包哦！">
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
        <link href="/Resource/css/shop.css?3MjKZ6Ft" type="text/css" rel="stylesheet"/>

        <div class="game-w">
            <div class="game-list">
                <ul>
                    <?php foreach($data as $value){ ?>
                    <li>
                        <div class="inner">
                            <a><img src="<?=$value['img_url']?>"></a>
                            <dl>
                                <dt>
                                <h2><?=$value['activity_name']?></h2>
                                玩连连看游戏攒积分了								
                                </dt>
                                <dd>
                                    <a href="<?=$value['url']?>" class="ui-btn btn-submit">立即开始</a>
                                </dd>
                            </dl>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php require(View.'/layout/footer.html'); ?>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1608151650"></script>
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
                            data: {'gid': gid, 'num': 1, 'rand': 'Dv2xTOE7ul'},
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

        <script>
            var Page = 2;
            var canRequest = true;
            $(document).ready(function() {
                var range = 50; //距下边界长度/单位px
                var totalheight = 0;
                var main = $(".game-list ul"); //主体元素
                var str = '<div class="loading-more"><i></i>正在加载…</div>';
                $(window).scroll(function() {
                    var srollPos = $(window).scrollTop(); //滚动条距顶部距离(页面超出窗口的高度)
                    totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
                    if (($(document).height() - range) <= totalheight && canRequest) {
                        $('.loading-more').remove();
                        main.append(str);
                        canRequest = false;
                        $.ajax({
                            type: "get",
                            url: "/goods/lists/",
                            async: false,
                            dataType: 'json',
                            data: 'ajax=1&page=' + Page + '&type=4',
                            success: function(result) {
                                $('.loading-more').remove();
                                canRequest = true;
                                if (result.err == '0' && result.data != "") {//查询结果有数据
                                    main.append(result.data);
                                    Page++;
                                } else if (result.err == '0' && result.data == "") {//查询结果无数据
                                    canRequest = false;
                                }
                            }
                        });
                    }
                });
            });
        </script>
        <a href="/goods/index/" class="btn-home"><i></i></a>	
        <span style="display:none">
            <img src="https://c.cnzz.com/wapstat.php?siteid=1256636065&r=https%3A%2F%2Fwap.bbqb.cn%2Fgoods%2Findex%2F&rnd=976088000" width="0" height="0"/><script src="https://s95.cnzz.com/z_stat.php?id=1256636065&web_id=1256636065" language="JavaScript"></script>
        </span>

    </body>
</html>