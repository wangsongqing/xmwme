<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>输入登录密码</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link href="/Resource/css/common.css?nrGv5stS" type="text/css" rel="stylesheet">
        <link href="/Resource/css/pages.css?QwEv1hiy" type="text/css" rel="stylesheet">
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
        <form class="common-form" autocomplete="off" id="psw_form_2" method="post" onSubmit="return loginJs.sumbmitLogin();">
            <div class="item">
                <ul>
                    <li class="input-box">
                        <div class="inner">
                            <div class="label">登录密码</div>
                            <div class="rcon">
                                <input type="hidden"  name="telephone" value="18201197923" id="telephone" />
                                <input type="password" name="password" class="ui-input" placeholder="请输入登录密码" maxlength="16">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="r-text"><a href="/login/forget/">忘记登录密码&nbsp;&gt;</a></div>
            <div class="g-btns">
                <ul>
                    <li><button type='submit' class="ui-btn btn-submit">登&nbsp;录</button></li>
                </ul>
            </div>
        </form>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=1711221436"></script>
        <script src="/Resource/js/user/login.js?t=20170217"></script>
        <script src="/Resource/js/rsa/rsa.js"></script>
        <script src="/Resource/js/rsa/edai_encryption.js"></script>
    </body>
</html>