<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>积分明细</title>
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
    </head><body class="pg-asset-y">
        <div class="head" style="padding:0 0 1.25rem;">
            <div class="tabs">
                <ul>
                    <li class="on"><a href="/credit/index/">获取积分</a></li>
                    <li><a href="/credit/exchange/">消费积分</a></li>
                </ul>
            </div>
            <div class="text">
                累计消费积分<br>
                <p class="num"><?=isset($credit)?$credit['use_credit']:0?></p>
            </div>	
        </div>
        <div class="profit-list">

            <ul id= 'grow_page'>
                <?php foreach($credit_log['record'] as $v){ ?>
                <li>
                    <span class="l"><i class="icon-time"></i><?=date('Y-m-d H:i:s',$v['created'])?></span>
                    <?php if(isset($v['goods_name']) && !empty($v['goods_name'])){ ?>
                    <span class="l">，兑换商品<?=$v['goods_name']?>消费</span>
                    <?php } ?>
                    <span class="c-red r">-<?=$v['credit']?>积分</span>
                </li>
                <?php } ?>
            </ul>
        </div>
        <a href="/user/index/" class="btn-home"><i></i></a>	
        <script src="/Resource/js/zepto.min.js"></script>
        <script src="/Resource/js/plugins.js"></script>
        <script src="/Resource/js/layer/layer.m.js?d=1608151650"></script>
        <script src="/Resource/js/base.js?ZR6rDry1?d=180117"></script>
        <script>
      var Page = 2;
      var canRequest = true;
      $(document).ready(function() {
          var range = 50; //距下边界长度/单位px
          var totalheight = 0;
          var main = $("#grow_page"); //主体元素
          var str = '<div class="loading-more"><i></i>正在加载…</div>';
          $(window).scroll(function() {
              var srollPos = $(window).scrollTop(); //滚动条距顶部距离(页面超出窗口的高度)
              totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
              if (($(document).height() - range) <= totalheight && canRequest) {
                  $('.loading-more').remove();
                  main.append(str);
                  canRequest = false;
                  $.ajax({
                      type: "get",
                      url: "/credit/exchange/",
                      async: false,
                      dataType: 'json',
                      data: 'ajax=1&page=' + Page,
                      success: function(result) {
                          $('.loading-more').remove();
                          canRequest = true;
                          if (result.err == '0' && result.data != "") {//查询结果有数据
                              main.append(result.data);
                              Page++;
                          } else if (result.err == '0' && result.data == "") {//查询结果无数据
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