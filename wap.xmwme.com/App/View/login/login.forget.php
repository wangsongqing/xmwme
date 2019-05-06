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
  <script>(function(c){var f=window,e=document,a=e.documentElement,b="orientationchange" in window?"orientationchange":"resize",d=function(){var h=a.clientWidth;if(!h){return}var g=20*(h/320);a.style.fontSize=(g>40?40:g)+"px"};if(!e.addEventListener){return}f.addEventListener(b,d,false);d()})();</script>
</head><body class="pg-cash">
	<div class="top-tip">重置账号登录密码前，请完成信息验证</div>
	<form class="common-form" autocomplete="off" id="psw_form_4" action="/login/forget/" method="post" onSubmit="return forgetPasswdJs.stepOne();">
		<input type="hidden" name="step" value="1">
		<div class="item">
			<div class="input-box">
				<div class="inner">
					<div class="label">手机号</div>
					<div class="rcon">
						<input type="tel" id="telephone" name="telephone" value="" class="ui-input" maxlength="11" placeholder="请输入注册的手机号">
					</div>
				</div>
			</div>
         </div>
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
						<input type="tel" name="yzm" id="yzm" class="ui-input" maxlength="6" placeholder="请输入短信验证码">
                        <button type="button" class="btn-get-code" id="send">获取验证码</button>
					</div>
				</div>
			</div>
		</div>
		<div class="g-btns">
			<ul>
				<li><button class="ui-btn btn-submit">下一步</button></li>
			</ul>
		</div>
	</form>
<script src="/Resource/js/zepto.min.js"></script>
<script src="/Resource/js/plugins.js"></script>
<script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
<script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
<script src="/Resource/js/account/forget.password.js?t=1701061446"></script>

</body>
</html>