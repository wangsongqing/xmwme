<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>设置登录密码</title>
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
    </head><body>
        <form class="common-form" autocomplete="off" id="psw_form_5" action="/login/forget/" method="post" onsubmit="return false;">
            <input type="hidden" name="step" value="2">
            <div class="item">
                <ul>
                    <li class="input-box">
                        <div class="inner">
                            <div class="label">登录密码</div>
                            <div class="rcon">
                                <input type="password" name="password" id="password" class="ui-input" placeholder="6-16位英文、数字、特殊字符" maxlength="16">
                            </div>
                        </div>
                    </li>
                    <li class="input-box">
                        <div class="inner">
                            <div class="label">再次输入</div>
                            <div class="rcon">
                                <input type="password" name="password2" id="password2" class="ui-input" placeholder="请再次输入新密码" maxlength="16">
                            </div>
                        </div>
                    </li>
                </ul>
                <input type="hidden" name="telephone" id="telephone" value="<?=isset($telephone)?$telephone:0?>">
                <input type="hidden" name="rand" id="rand" value="SrO0kIUkwWRxUOPR">
            </div>
            <div class="g-btns">
                <ul>
                    <li><button class="ui-btn btn-submit" id="confirmPassword">确认修改并登录</button></li>
                </ul>
            </div>
        </form>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
        <script src="/Resource/js/user/forget.password.js?t=1701161447"></script>

        <span style="display:none">
            <img src="http://c.cnzz.com/wapstat.php?siteid=1256636065&r=http%3A%2F%2Flowap.bbqb.cn%2Flogin%2Fforget%2F&rnd=1255614215" width="0" height="0"/><script src="https://s95.cnzz.com/z_stat.php?id=1256636065&web_id=1256636065" language="JavaScript"></script>
        </span>

    </body>
</html>