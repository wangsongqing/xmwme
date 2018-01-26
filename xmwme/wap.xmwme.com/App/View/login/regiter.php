<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>注册验证</title>
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
    </head><body class="pg-reg">
        <form class="common-form" autocomplete="off" id="reg_form_2" method='post' onsubmit="return false;">
            <div class="item item-img">
                <div class="input-box">
                    <div class="inner">
                        <div class="label">图形验证</div>
                        <div class="rcon">
                            <input type="text" name="img_code" class="ui-input" maxlength="4" placeholder="请输入右图字符">
                            <img src="/login/verify/" class="img-code" onclick='BB.form.changeVerify()'>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item item-code">
                <div class="input-box">
                    <div class="inner">
                        <div class="label">短信验证</div>
                        <div class="rcon">
                            <input type="tel" name="yzm" class="ui-input" maxlength="6" placeholder="请输入验证码">
                            <input name="telephone" id="telephone" value="<?=isset($telephone)?$telephone:''?>" type="hidden">
                            <button type="button" class="btn-get-code">获取验证码</button>
                        </div>
                    </div>
                </div>
                <div class="tips-text"></div>
            </div>
            <div class="item item-lists">
                <div class="input-box">
                    <div class="inner">
                        <div class="label">登录密码</div>
                        <div class="rcon rel">
                            <input type="password" name="password" id="password" class="ui-input" placeholder="请输入数字、字母，不少于6位" maxlength="16">
                            <i class="eye" id="psw_swich"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="input-box">
                    <div class="inner">
                        <div class="label">邀请码</div>
                        <div class="rcon">
                            <input type="tel" name="invite_code" id="invite_code" class="ui-input" placeholder="请输入好友邀请码（选填）" maxlength="9">
                        </div>
                    </div>
                </div>
            </div>


            <div class="g-btns">
                <ul>
                    <li><button class="ui-btn btn-submit">确&nbsp;认</button></li>
                </ul>
                <p class="tx">点击“确认”即表示您同意<a href="/staticpage/regprotocol/">《用户服务协议》</a></p>
            </div>
        </form>
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?d=17112122"></script>
        <script src="/Resource/js/rsa/rsa.js"></script>
        <script src="/Resource/js/rsa/xmwme_encryption.js"></script>
        <script src="/Resource/js/register.js?t=170116221"></script>
        <script>
        </script>
    </body>
</html>

