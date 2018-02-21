<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>积分兑换</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="">
        <meta name="description" content="">
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
    </head><body id="nofast">
        <link href="/Resource/credit/css/style.css?201612" type="text/css" rel="stylesheet"/>
        <div class="ticket-detail">
            <div class="inner">
                <div class="img"><img src="<?=isset($data['list_pic'])?$data['list_pic']:''?>"></div>
                <div class="text">
                    <ul>
                        <li class="ti"><?=isset($data['goods_name'])?$data['goods_name']:''?></li>
                        <li>所需积分：<span class="c-red"><span class="big"><?=isset($data['credit'])?$data['credit']:''?></span>积分</span></li>
                        <li>剩余数量：<?=isset($data['store'])?$data['store']:''?></li>
                        <li>兑换数量</li>
                        <li>
                            <div class="number" data-rel="cart-item" data-amount="95" data-step="1">
                                <a href="javascript:;" class="minus">-</a>
                                <input type="tel" class="ipt-number" value="1">
                                <a href="javascript:;" class="plus">+</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php if($user_id>0){ ?>
            <div class="btns">
                <a href="javascript:rewards.buy()" class="ui-btn btn-submit">立即兑换</a>
            </div>
            <?php }else{ ?>
            <div class="btns">
                <a href="<?=Root?>login/index/" class="ui-btn btn-submit" disabled="disabled">请登录后操作</a>
            </div>
            <?php } ?>
        </div>
        <div class="ticket-detail-rule">
            <div class="hd">礼品详情：</div>
            <div class="bd">
                <?=isset($data['content'])?$data['content']:''?>		
            </div>
        </div>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1608151650"></script>
        <script src="/Resource/credit/js/rewards.js?2323"></script>
        <script>
            rewards.gid = '<?=isset($data['id'])?$data['id']:0?>';
            rewards.rand = '<?=isset($salt)?$salt:0?>';
        </script>
        <a href="/goods/index/" class="btn-home"><i></i></a>	

        <script>
            var isPageHide = false;
            window.addEventListener('pageshow', function() {
                if (isPageHide) {
                    window.location.reload();
                }
            });
            window.addEventListener('pagehide', function() {
                isPageHide = true;
            });
        </script>
    </body>
</html>