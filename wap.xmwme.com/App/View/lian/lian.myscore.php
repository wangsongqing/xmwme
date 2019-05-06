<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>连连看</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link href="<?=Resource?>css/common.css" type="text/css" rel="stylesheet">
	<link href="<?=Resource?>activity/lianliankan/css/style.css?O9VTOdj2" type="text/css" rel="stylesheet" />
	<script>(function(c){var f=window,e=document,a=e.documentElement,b="orientationchange" in window?"orientationchange":"resize",d=function(){var h=a.clientWidth;if(!h){return}var g=20*(h/320);a.style.fontSize=(g>40?40:g)+"px"};if(!e.addEventListener){return}f.addEventListener(b,d,false);d()})();</script>
</head>

<body>
	<div class="pg-my">
		<div class="bg"></div>
		<div class="panel-gift my-score">
			<div class="tit">
				<i class="bg1"></i>
				<i class="bg2"></i>
			</div>
			<i class="icon-tree"></i>
			<div class="outer">
				<div class="inner">
					<ul>
						<li>
							总分数(分)<p><?=isset($alldata['total_score'])?$alldata['total_score']:''?></p>
						</li>
						<li>
							总排名(名)<p><?=isset($alldata['pm'])?$alldata['pm']:''?></p>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="panel-gift my-list">
			<i class="icon-tree"></i>
			<div class="outer">
				<div class="inner">
					<div class="hd"><span>我的单次成绩</span></div>
					<ul class="gift-list" id='main_gift_list'>
						<li>
							<div class="col col-1"><strong>分数</strong></div>
							<div class="col col-2"><strong>参与时间</strong></div>
						</li>
                                                <?php foreach($data['record'] as $value){ ?>
						<li>
							<div class="col col-1"><?=$value['score']?></div>
							<div class="col col-2"><?=date('Y-m-d H:i:s',$value['created'])?></div>
						</li>
						<?php } ?>
						
					</ul>
				</div>
			</div>
			<div class="block"></div>
		</div>
	</div>
</body>

</html>
<?require(View.'/public/js.html');?> 
<script>                  
var Page = 2;
var canRequest = true;
$(function(){
    var range = 10; //距下边界长度/单位px
    var totalheight = 0;
    var main =  $("#main_gift_list"); //主体元素
    var str = '<div class="loading-more"><i></i>正在加载…</div>';
    main.scroll(function(){
        var srollPos = main.scrollTop(); //滚动条距顶部距离(页面超出窗口的高度)
        totalheight = parseFloat(main.height()) + parseFloat(srollPos);
        if((main.height()-range) <= totalheight && canRequest) {
          $('.loading-more').remove();
          main.append(str);
          canRequest = false;
            $.ajax({
                type: "get",
                url : "/lian/myscore/",
                async:false,
                dataType:'json',
                data: 'ajax=1&page=' + Page,
                success: function(result){
                  $('.loading-more').remove();
                    canRequest = true;
                    if(result.err == '0' && result.data !=" ") {//查询结果有数据
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
