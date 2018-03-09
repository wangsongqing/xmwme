<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>提现</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link href="/Resource/css/common.css?X0uvJJXk" type="text/css" rel="stylesheet">
  <link href="/Resource/css/pages.css?X0uvJJXk" type="text/css" rel="stylesheet">
  <script>(function(c){var f=window,e=document,a=e.documentElement,b="orientationchange" in window?"orientationchange":"resize",d=function(){var h=a.clientWidth;if(!h){return}var g=20*(h/320);a.style.fontSize=(g>40?40:g)+"px"};if(!e.addEventListener){return}f.addEventListener(b,d,false);d()})();</script>
</head><body class="pg-cash">
<div class="head">
    
</div>
<form autocomplete="off" class="cash-form cls" id="cash_form_1" onsubmit="return false;">
    <div class="item input-box">
        <div class="label">变现金额</div>
        <div class="rt">元</div>
        <div class="rcon">
        <input type="text" name="case_amount" class="ui-input" placeholder="可变现金额<?=isset($redbag['red_bag'])?$redbag['red_bag']:'0.00'?>" id="money" onKeyUp="withdrawJs.amount(this)" onKeyDown="withdrawJs.amount(this)">
        </div>
        <div class="tips-text"></div>
    </div>
    <div class="item">
        <button id="button_submit" type="button" class="ui-btn btn-submit" onclick="withdrawJs.withdraw();">下一步</button>
    </div>
</form>
<div class="ex-text">
    <span class="c-333">温馨提示：</span>
    <br>1、提现金额必须大于等于10元
    <br>2、变现处理时间为1-3个工作日
    <br>3、前一笔变现资金到账后，方可发起下一笔
</div>
<script src="/Resource/js/jquery-2.1.4.min.js"></script>
<script src="<?=Resource?>js/layer/layer.m.js?d=1608151650"></script>
<script type="text/javascript"> 
    
    var withdrawJs = {
        withdraw:function(){
            var money = $("#money").val();
            var can_money = '<?=isset($redbag['red_bag'])?$redbag['red_bag']:'0.00'?>';
            if(money>can_money){
                $(".tips-text").html('您当前可取现金额为'+can_money+'元');
                return false;
            }
            if(money<10){
                $(".tips-text").html('金额不能低于10元');
                return false;
            }
            
            $.post('/withdraw/withdraw_send/',{money:money},function(data){
                if(data.err==1){
                    layer.open({
                        content: data.msg,  //文字
                        btn: ['确定'], //按钮
                        yes: function(){ //确定
                            location.href = data.url;
                        }
                    });
                }else{
                    layer.open({
                        content: data.msg,  //文字
                        btn: ['确定'] //按钮
                    });
                }
            },'json');
        },
        amount:function(th){
            //实时动态限制用户录入金额
             var regStrs = [
                ['^(0|\\.)$', ''], //禁止以0或者小数点开头
                ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
                ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
                ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
                ];
            for(i=0; i<regStrs.length; i++){
                var reg = new RegExp(regStrs[i][0]);
                th.value = th.value.replace(reg, regStrs[i][1]);
            }
        }
    }
</script>

</body>
</html>