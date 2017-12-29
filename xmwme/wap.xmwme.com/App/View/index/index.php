
<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>宝宝钱包-首页</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="我的钱包">
        <meta name="description" content="我的钱包">
        <link href="<?php echo Resource;?>css/common.css?xEl2SF0q" type="text/css" rel="stylesheet">
        <link href="<?php echo Resource;?>css/pages.css?xEl2SF0q" type="text/css" rel="stylesheet">
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
        <link href="<?php echo Resource;?>css/home.css?a7SSHtWr" type="text/css" rel="stylesheet">
        <link href="<?php echo Resource;?>css/swiper.min.css" type="text/css" rel="stylesheet" />
        <div class="container-fluid">
            <div class="banner swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><a href="/staticpage/special/?key=activity1226_2"><img src="http://img.bbqb.cn/banner/201712/banner151418066811921.jpg"></a></div>
                    <div class="swiper-slide"><a href="/lian/index/"><img src="http://img.bbqb.cn/banner/201711/banner151174905248885.jpg"></a></div>
                    <div class="swiper-slide"><a href="/sign/index/"><img src="http://img.bbqb.cn/banner/201709/banner150517913365646.jpg"></a></div>
                </div>
            </div>
            <div class="panel">
                <!--第一组-->
                <ul>
                    <li><a href="/staticpage/vip_upgrade/"><img src="http://img.bbqb.cn/banner/201707/banner150045330170342.jpg"><p>会员体系</p></a></li>
                    <li><a href="/share/invite/"><img src="http://img.bbqb.cn/banner/201707/banner150045334128994.jpg"><p>邀请好友</p></a></li>
                    <li><a href="/staticpage/insurance/"><img src="http://img.bbqb.cn/banner/201707/banner150045338951886.jpg"><p>安全保障</p></a></li>
                </ul>
                <!--第二组-->
                <ul>
                </ul>
                <!--第三组-->
                <ul>
                </ul>
            </div>
            <div class="product">
                <i class="tag"></i>
                <div class="text">
                    <a href="/fixed/fcapital_income/?type=1">
                        <p class="p1">百日宝<span><i></i>到期退出或续投</span></p>

                        <p class="p2">历史年化收益率</p>
                        <p class="p3">8.00<span>%</span></p>
                        <ul>
                            <li>持有满90天转让</li>
                            <li>1000元起投</li>
                            <li>每日限额开放</li>
                        </ul>
                    </a>
                    <div class="btn-w">
                        <a href="/buy/index/?ptype=1" class="ui-btn btn-submit">马上投资</a>
                    </div>
                </div>
            </div>
            <div class="dynamics">
                <i class="icon icon-speaker"></i>
                <div class="rt">
                    <a href="/account/capital_log/?type=withdraw">
                        <div class="meta">
                            <p>我的变现</p>
                            <time>2017-11-28 15:47:01</time> 
                        </div>
                        <div class="text">已成功变现5105元</div>
                    </a>
                    <!--到期退出-->

                </div>
            </div>
        </div>
        <div class="navbar">
            <div class="inner">
                <ul>
                    <li class="active">
                        <a href="/index/index/">
                            <span class="icon-w">
                                <i class="icon icon-1"></i>
                            </span>
                            首页
                        </a>
                    </li>
                    <li class="">
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
                        <a href="/index/my/">
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
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1701161354"></script>
        <script src="/Resource/js/swiper.min.js"></script>
        <script src="/Resource/js/rsa/rsa.js"></script>
        <script src="/Resource/js/rsa/edai_encryption.js"></script>
        <script src="/Resource/js/account/cg.js?1704171734"></script>
        <script>
            if ($(".swiper-slide").length > 1) {
                var swiper = new Swiper('.swiper-container', {
                    autoplay: 5000,
                    loop: true,
                    autoplayDisableOnInteraction: false
                });
            }
        </script>
    </body>
</html>