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
        <form class="common-form" autocomplete="off" id="psw_form_2" method="post">
            <div class="item">
                <ul>
                    <li class="input-box">
                        <div class="inner">
                            <div class="label">登录密码</div>
                            <div class="rcon">
                                <input type="hidden"  name="telephone" value="<?=isset($phone)&&!empty($phone)?$phone:''?>" id="telephone" />
                                <input type="password" name="password" class="ui-input" placeholder="请输入登录密码" maxlength="16">
                            </div>
                        </div>
                    </li>
                </ul>
                 <div id='error_show' class="tips-text" style='display: none;'>密码输入有误，请重新输入</div>
            </div>
            <div class="r-text"><a href="/login/forget/">忘记登录密码&nbsp;&gt;</a></div>
            <div class="g-btns">
                <ul>
                    <li><button id='login_password' type='submit' class="ui-btn btn-submit">登&nbsp;录</button></li>
                </ul>
            </div>
        </form>
        <script src="/Resource/js/jquery-2.1.4.min.js"></script>
        <script src="/Resource/js/rsa/xmwme_encryption.js"></script>
    </body>
</html>
<script>
$("#login_password").click(function(){
    login_submit();
    return false;
});
function login_submit(){
    var phone = encryptionJs.xmwme_encryption($("input[name=telephone]").val());
    var pwd = encryptionJs.xmwme_encryption($("input[name=password]").val());
    _bool = true;
    $.post('/login/ajaxLogin/',{password:pwd,phone:phone},function(data){
       if(data.code==1){
           location.href = '/index/';
       }else{
            $("#error_show").html(data.msg);
            $("#error_show").css('display','block');
       }
    },'json');
    return _bool;
}
</script>