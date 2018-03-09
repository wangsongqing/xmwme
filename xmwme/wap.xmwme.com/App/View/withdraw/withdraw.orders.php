
<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>资金流水</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link href="/Resource/css/common.css?X0uvJJXk" type="text/css" rel="stylesheet">
  <link href="/Resource/css/pages.css?X0uvJJXk" type="text/css" rel="stylesheet">
  <script>(function(c){var f=window,e=document,a=e.documentElement,b="orientationchange" in window?"orientationchange":"resize",d=function(){var h=a.clientWidth;if(!h){return}var g=20*(h/320);a.style.fontSize=(g>40?40:g)+"px"};if(!e.addEventListener){return}f.addEventListener(b,d,false);d()})();</script>
</head><body class="pg-my">
  <div class="tabs">
      <li class="active"><a href="/account/capital_log/?type=withdraw">变现记录</a></li>
  </div>
  <dl class="fund-list">
    <dd>
        <ul id = 'statement_page'>
            <?php foreach($orders['record'] as $v){ ?>
            <li>
                <p>
                  <span class="l">
                    变现时间 <br><time><?=date('Y-m-d H:i:s',$v['created'])?> 提现订单号:<?=$v['orders']?></time>
                  </span>
                    <span class="c-red r">
                    <?=$v['money']?> <br/>
                    <time class="r"><?=$status[$v['status']]?></time>
                    </span>
<!--                    <span class="">
                        提现订单号:<?=$v['orders']?>
                    </span>-->
                </p>
            </li>
            <?php } ?>
        </ul>
    </dd>
<a href="/user/index/" class="btn-home"><i></i></a>    
<script src="/Resource/js/zepto.min.js"></script>
<script src="/Resource/js/plugins.js"></script>
<script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
<script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
<script>
    var Page = 2;
    var canRequest = true;
    $(document).ready(function(){
        var range = 50; //距下边界长度/单位px
        var totalheight = 0;
        var main =  $("#statement_page"); //主体元素
        var str = '<div class="loading-more"><i></i>正在加载…</div>';
        $(window).scroll(function(){
            var srollPos = $(window).scrollTop(); //滚动条距顶部距离(页面超出窗口的高度)
            totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
            if(($(document).height()-range) <= totalheight && canRequest) {
            	$('.loading-more').remove();
            	main.append(str);
            	canRequest = false;
                $.ajax({
                    type: "get",
                    url : "/withdraw/withdraw_order/",
                    async:false,
                    dataType:'json',
                    data: 'ajax=1&page=' + Page,
                    success: function(result){
                    	$('.loading-more').remove();
                        canRequest = true;
                        if(result.err == '0' && result.data !="") {//查询结果有数据
                            main.append(result.data);
                            Page++;
                        }else if(result.err == '0' && result.data ==""){//查询结果无数据
                            canRequest = false;
			}
                    }
                });
            }
        });
    });
</script>

</body>

</html>
