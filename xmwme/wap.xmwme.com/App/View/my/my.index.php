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
        <div class="widget-activity">
            <a href="/staticpage/special/?key=activity1226_2"><img src="http://img.bbqb.cn/banner/201712/banner151434083669746.jpg"></a>
        </div>
       
        
        
        <div class="container-fluid">
            <header class="head">
                <a href="/sign/" class="sign"><i id="sign_gif"></i><br>马上签到</a>
                <div class="avatar-w">
                    <a href="/user/setting/" class="setting"><img src="http://img.bbqb.cn/avatar/001/28/49/90avatar_150_150.jpeg?t=1514535802" class="g-avatar"></a>
                    <div class="rt">
                        <p><span class="name">小萌</span>&nbsp;&nbsp;</p>
                        <a href="/vipuser/index/" class="vip vip-0"></a>
                    </div>
                </div>
                <div class="datas">
                    可用积分(个)
                    <p><?=isset($credit['credit'])?$credit['credit']:0?></p>
                </div>
            </header>
            <div class="gains-data">
                <ul>
                    <li>累计获得积分(个)
                        <p><?=isset($credit['all_credit'])?$credit['all_credit']:0?></p>
                    </li>
                    <li>累计消费积分(个)
                        <p><?=isset($credit['use_credit'])?$credit['use_credit']:0?></p>
                    </li>
                </ul>
            </div>
            <div class="btns">
                <ul>
                    <li>
                        <a href="/game/index/" class="btn btn-cash">玩游戏了</a>
                    </li>
                    <li>
                        <a href="/goods/index/" class="btn btn-buy">去消费了</a>
                    </li>
                </ul>
            </div>
            <div class="items-list">
                <ul>
                    <li>
                        <a href="/my/myorders/">
                            <i class="icon icon-1"></i>
                            <p><span>我的订单</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="/credit/index/">
                            <i class="icon icon-2"></i>
                            <p><span>我的积分</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="/invite/index/">
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
                                <a href="/invite/share/">
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
                            <li>
                                <a href="/benevolence/index/">
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-zj"></i></span>收货地址</span>
                                        <span class="r"><i class="icon icon-arrow"></i></span>
                                    </p>
                                </a>
                            </li>
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
                                <a href="/vipuser/index/">
                                    <p>
                                        <span class="l"><span class="icon-outer"><i class="icon icon-user"></i></span>我的详情</span>
                                        <span class="r"><span class="c-888">萌萌哒</span><i class="icon icon-arrow"></i></span>
                                    </p>
                                </a>
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

        <span style="display:none">
            <img src="https://c.cnzz.com/wapstat.php?siteid=1256636065&r=https%3A%2F%2Fwap.bbqb.cn%2Fgoods%2Findex%2F&rnd=269723696" width="0" height="0"/><script src="https://s95.cnzz.com/z_stat.php?id=1256636065&web_id=1256636065" language="JavaScript"></script>
        </span>

<script src="/Resource/js/swiper.min.js"></script>
<script src="/Resource/js/rsa/rsa.js"></script>
<script src="/Resource/js/rsa/xmwme_encryption.js"></script>
<script src="/Resource/js/account/cg.js?1704171734"></script>
<script type="text/javascript">
    var startX, startY, endX, endY, eLeft, eTop;
    $(".widget-activity").on("touchstart", function(event) {
        var touch = event.touches[0];
        startY = touch.pageY;
        startX = touch.pageX;
        eLeft = $(this).offset().left;
        eTop = $(this).offset().top;
    });
    $(".widget-activity").on("touchmove", function touchMove(event) {
        event.preventDefault();
        var touch = event.touches[0];
        moveX = event.touches[0].pageX;
        moveY = event.touches[0].pageY;
        var left = parseFloat(moveX) - parseFloat(startX) + parseFloat(eLeft),
                top = parseFloat(moveY) - parseFloat(startY) + parseFloat(eTop),
                right = $(window).width() - $(this).width(),
                bottom = $(window).height() - $(this).height() - $(".navbar").height();
        left = left < 0 ? 0 : left > right ? right : left;
        top = top < 0 ? 0 : top > bottom ? bottom : top;
        $(this).css({"bottom": "auto", "right": "auto", "left": left + "px", "top": top + "px"});
    });
    var is_show = "0";
    var msg_id = "0";
    $(document).ready(function() {
        sign_gif();
        if (is_show == 1) {
            showCGPopup(1); //开通/激活存管
        } else if (is_show == 2) {
            showAuthorizePopup(1); //授权
        } else if (is_show == 3) {
            showVipUpgradePopup(1); //VIP升级
        }
        $(".close-dialog").click(function() {
            var msg_id = $(this).attr('dialog-id');
            var type = $(this).attr('dialog-type');
            var position = $(this).attr('dialog-position');
            cgJs.closeDialog(msg_id, type, position); //close dialog
        })
    })
    function sign_gif() {
        var index = 0;
        setInterval(function() {
            if (index < 3) {
                index++;
            }
            else {
                index = 0;
            }
            $("#sign_gif").attr("data-f", index);
        }, 350)
    }
    function showVipUpgradePopup(position) {
        $(".close").attr("dialog-position", position);
        BB.popup.cover.show();
        $(".popup-vip-upgrade").show();
        setTimeout(function() {
            $(".popup-vip-upgrade .fireworks").addClass('show');
            $(".popup-vip-upgrade .vip").addClass('show');
        }, 500)
    }
    ;

    function hideVipUpgradePopup() {
        BB.popup.cover.hide();
        $(".popup-vip-upgrade").hide();
    }
    ;
    $(".popup-vip-upgrade .close").on("click", function() {
        hideVipUpgradePopup();
    });

    function showNovicePopup() {
        BB.popup.cover.show();
        $('.wallet').removeClass("show");
        $('.wallet').removeClass("shake");
        setTimeout(function() {
            $(".popup-novice").addClass('show');
        }, 500)
    }
    ;

    function hideNovicePopup() {
        $(".popup-novice").removeClass('show');
        $('.wallet').addClass("show");
        setTimeout(function() {
            BB.popup.cover.hide();
            $('.wallet').addClass("shake");
        }, 500)
    }
    ;

    function showCGPopup(position) {
        $(".close").attr("dialog-position", position);
        BB.popup.cover.show();
        $(".popup-cg").show();
    }
    ;

    function hideCGPopup() {
        BB.popup.cover.hide();
        $(".popup-cg").hide();
    }
    ;

    function showAuthorizePopup(position) {
        if (position == 1) {
            $(".close").attr("dialog-position", position);
            BB.popup.cover.show();
            $(".popup-authorize").show();
        } else {
            $.ajax({
                type: "post",
                url: "/account/getAuthDialog/",
                dataType: 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    BB.popup.loading.hide();
                    BB.popup.alert('网络异常,请稍后重试');
                },
                success: function(data) {
                    if (data.is_show == '1') {
                        $(".close").attr("dialog-position", position);
                        BB.popup.cover.show();
                        $(".popup-authorize").show();
                        $(".close-dialog").attr('dialog-id', data.msg_id);
                    } else {
                        window.location.href = "/withdraw/index/";
                    }
                }
            });
        }
        ;
    }

    function hideAuthorizePopup() {
        BB.popup.cover.hide();
        $(".popup-authorize").hide();
    }
    ;
    $(".popup-cg .close").on("click", function() {
        hideCGPopup();
    });
    $(".popup-novice .close a").on("click", function() {
        hideNovicePopup();
    });
    $(".wallet").on("click", function() {
        showNovicePopup()
    });
    if ($(".swiper-slide").length > 1) {
        var swiper = new Swiper('.swiper-container', {
            autoplay: 5000,
            loop: true,
            autoplayDisableOnInteraction: false
        });
    }

    function goto_buy() {
        layer.open({
            content: "您还未投资过，是否要存一笔？", //文字
            btn: ['确定', '取消'], //按钮
            yes: function() { //确定
                location.href = "/buy/index/";
            }
        });
    }
</script>
        <span style="display:none">
            <img src="http://c.cnzz.com/wapstat.php?siteid=1256636065&r=&rnd=659446399" width="0" height="0"/>
        </span>
</body>

</html>


