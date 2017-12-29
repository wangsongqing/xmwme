<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>宝贝游戏</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="宝宝理财,儿童理财,亲子理财,家庭理财">
        <meta name="description" content="宝宝钱包是一款针对育儿用户的智能投顾工具，通过这个工具用户能方便快捷的进行投资，实现育儿资金的增值保值。宝宝钱包由获得软银中国资本注资的上海易贷网金融信息服务有限公司运营。">
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
                    <li>
                        <div class="inner">
                            <a><img src="http://img.bbqb.cn/goods/201701/93e587abe403242dd3a86ecb02743d46.jpg"></a>
                            <dl>
                                <dt>
                                <h2>打地鼠</h2>
                                积分兑换打地鼠游戏机会								</dt>
                                <dd>
                                    <span class="l"></span>所需宝贝豆：<span class="c-red">30</span>
                                    <a href="javascript:exchange(1)" class="ui-btn btn-submit">立即兑换</a>
                                </dd>
                            </dl>
                        </div>
                    </li>
                    <li>
                        <div class="inner">
                            <a><img src="http://img.bbqb.cn/goods/201701/8d09c294028fe0c5099a6ba0ddd2073e.jpg"></a>
                            <dl>
                                <dt>
                                <h2>接鸡蛋</h2>
                                游戏结束后扣除积分								</dt>
                                <dd>
                                    <span class="l"></span>所需宝贝豆：<span class="c-red">30</span>
                                    <a href="javascript:exchange(2)" class="ui-btn btn-submit">立即兑换</a>
                                </dd>
                            </dl>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar">
            <div class="inner">
                <ul>
                    <li class="">
                        <a href="/index/index/">
                            <span class="icon-w">
                                <i class="icon icon-1"></i>
                            </span>
                            首页
                        </a>
                    </li>
                    <li class="active">
                        <a href="/index/game/">
                            <span class="icon-w">
                                <i class="icon icon-2"></i>
                            </span>
                            投资
                        </a>
                    </li>
                    <li class="">
                        <a href="/index/shop/">
                            <span class="icon-w">
                                <i class="icon icon-3"></i>
                            </span>
                            商城
                        </a>
                    </li>
                    <li class="">
                        <a href="/user/index/">
                            <span class="icon-w">
                                <i class="icon icon-4"></i>
                            </span>
                            我的
                        </a>
                    </li>
                </ul>
            </div>
        </div>
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