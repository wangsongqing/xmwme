<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>我的</title>
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
    </head><body id="nofast">
        <link href="/Resource/css/user.css?xeigAjZ5" type="text/css" rel="stylesheet">
        <link href="/Resource/css/swiper.min.css" type="text/css" rel="stylesheet" />
        
<!--        <div class="widget-activity">
            <a href="/staticpage/special/?key=activity1226_2"><img src="http://img.bbqb.cn/banner/201712/banner151434083669746.jpg"></a>
        </div>
        右下角悬浮窗-->
       
        
        
        <div class="container-fluid">
            <header class="head">
                <!--<a href="/sign/" class="sign"><i id="sign_gif"></i><br>马上签到</a>-->
                <div class="avatar-w">
                    <!--<a href="/user/setting/" class="setting">-->
                        <img src="/Resource/images/user/headers.jpg" class="g-avatar">
                    <!--</a>-->
                    <div class="rt">
                        <p><span class="name">小果</span>&nbsp;&nbsp;</p>
                        <a href="/vipuser/index/" class="vip vip-0"></a>
                    </div>
                </div>
                <div class="datas">
                    可提现红包(元)
                    <p><?=isset($credit['red_bag'])?$credit['red_bag']:0?></p>
                </div>
            </header>
            <div class="gains-data">
                <ul>
                    <li>累计获得红包(元)
                        <p><?=isset($credit['all_red_bag'])?$credit['all_red_bag']:0?></p>
                    </li>
                    <li>累计到账红包(元)
                        <p><?=isset($withdraw)?$withdraw:0?></p>
                    </li>
                </ul>
            </div>
            <div class="btns">
                <ul>
                    <li>
                        <a href="/game/index/" class="btn btn-cash">领红包</a>
                    </li>
                    <li>
                        <a href="/withdraw/index/" class="btn btn-buy">提现</a>
                    </li>
                </ul>
            </div>
            <div class="items-list">
                <ul>
                    <li>
                        <a href="/withdraw/withdraw_order/">
                            <i class="icon icon-1"></i>
                            <p><span>我的提现记录</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="/redbag/index/">
                            <i class="icon icon-2"></i>
                            <p><span>我的红包记录</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="/my/share/">
                            <i class="icon icon-6"></i>
                            <p><span>我的邀请</span></p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="g-menu">
                <ul>
                    <li class="item">
                        <ol>
                            <li>
                                <a href="/my/share/">
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-friend"></i></span>邀请好友</span>
                                        <span class="r"><span class="c-888"><?=isset($user_info['form_code'])?$user_info['form_code']:0?></span><i class="icon icon-arrow"></i></span>
                                    </p>
                                </a>
                            </li>
                        </ol>
                    </li>
                    <li class="item">
                        <ol>
<!--                            <li>
                                <a href="/benevolence/index/">
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-zj"></i></span>收货地址</span>
                                        <span class="r"><i class="icon icon-arrow"></i></span>
                                    </p>
                                </a>
                            </li>-->
                            <li>
                                <a href="/user/my_debts/">
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-zq"></i></span>修改密码</span>
                                        <span class="r"><i class="icon icon-arrow"></i></span>
                                    </p>
                                </a>
                            </li>
                        </ol>
                    </li>
                    <li class="item">
                        <ol>
                            <li>
                                <!--<a href="/vipuser/index/">-->
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-user"></i></span>当前用户</span>
                                        <span class="r"><span class="c-888"><?=isset($user_info['telephone'])?$user_info['telephone']:0?></span><i class="icon icon-arrow"></i></span>
                                    </p>
                                <!--</a>-->
                            </li>
                        </ol>
                    </li>
                </ul>
            </div>
        </div>
        <?php require(View.'/layout/footer.html'); ?>

        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1711221436"></script>


<script src="/Resource/js/swiper.min.js"></script>
<script src="/Resource/js/rsa/rsa.js"></script>
<script src="/Resource/js/rsa/xmwme_encryption.js"></script>
<script src="/Resource/js/account/cg.js?1704171734"></script>

</body>

</html>


