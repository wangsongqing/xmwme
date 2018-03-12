
<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>食物百科网</title>
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
                <?php foreach($data as $value){ ?>
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><a href="<?=$value['url']?>"><img src="<?=$value['img_url']?>"></a></div>
                </div>
                <?php } ?>
            </div>
            <div class="panel">
                <!--第一组-->
                <ul>
                    <li><a href="/index/about/"><img src="<?php echo Resource; ?>images/banner/banner150045330170342.jpg"><p>关于我们</p></a></li>
                    <li><a href="/my/share/"><img src="<?php echo Resource; ?>images/banner/banner150045334128994.jpg"><p>邀请好友</p></a></li>
                    <li><a href="/story/index/"><img src="<?php echo Resource; ?>images/banner/banner150045338951886.jpg"><p>食物故事</p></a></li>
                </ul>
            </div>
            <div class="product">
                <i class="tag"></i>
                <div class="text">
                    <a href="/lian/index/">
                        <p class="p1">连连看</p>
                        <ul>
                            <li>考考你的眼力</li>
                            <li>快快挑战吧</li>
                            <li>每日限玩一次</li>
                        </ul>
                    </a>
                    <div class="btn-w">
                        <a href="/lian/index/" class="ui-btn btn-submit">马上开始</a>
                    </div>
                </div>
            </div>
            <div class="dynamics">
                <?php if($login_user_id!=0){ ?>
                <i class="icon icon-speaker"></i>
                <div class="rt">
                    <a href="/redbag/index/">
                        <div class="meta">
                            <p>积分最新动态</p>
                            <time><?=isset($credit_data['created'])?date('Y-m-d H:i:s',$credit_data['created']):''?></time> 
                        </div>
                        <?php if($credit_data['type']==1){ ?>
                        <div class="text">已成功通过<?=isset($credit_data['activity_name'])?$credit_data['activity_name']:''?>获取<?=isset($credit_data['redbag'])?$credit_data['redbag']:''?>元红包</div>
                        <?php }else if($credit_data['type']==2){ ?>
                        <span>成功兑换<?=isset($credit_data['goods_name'])?$credit_data['goods_name']:''?>,消耗<?=isset($credit_data['credit'])?$credit_data['credit']:''?>积分</span>
                        <?php }else{ ?>
                        <span>你好！你还没有积分动态！</span>
                        <?php } ?>
                    </a>                    
                </div>
                <?php }else{ ?>
                <div class="meta">
                        <p>你还没有登录哦！！</p>
                    </div>      
                <?php } ?>
            </div>
        </div>
        
       <?php require(View.'/layout/footer.html'); ?>

        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1701161354"></script>
        <script src="/Resource/js/swiper.min.js"></script>
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