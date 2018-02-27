<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>邀请好友</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link href="/Resource/css/common.css" type="text/css" rel="stylesheet">
        <link href="/Resource/css/170726.css?1708211603" type="text/css" rel="stylesheet">
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

    <body class="pg-share">
        <div class="head"></div>
        <div class="items">
            <div class="item-1">
                <div class="qr-code">
                    <img src="/my/shareimg/" id="qrcode">
                </div>
                <p>长按图片保存专属二维码并发送好友</p>
            </div>
            <div class="item-2">
                <p class="p1">您的邀请码为<br><span><?= isset($user_info) ? $user_info['form_code'] : 0 ?></span></p>
                <p>好友注册时填写邀请码</p>
            </div>
            <div class="item-2">
                <p>您的邀请连接为<br><span><?=isset($link)?$link:''?></span></p><br>
                <p>复制此连接发送给朋友或朋友圈</p>
            </div>
        </div>
        <dl class="ex-text">
            <dt>奖励规则：</dt>
            <dd>
                <ul>
                    <li><span>1、</span><p>邀请好友成功注册并关注，您将获得现金红宝1元，邀请红包大于十元可以取现</p></li>
                </ul>
            </dd>
        </dl>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1604151430"></script>
        <script src="/Resource/js/base.js?d=170105"></script>
        <script src="/Resource/js/echarts/echarts.common.min.js"></script>
    </body>
</html>