<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>连连看</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link href="<?=Resource?>css/common.css" type="text/css" rel="stylesheet">
	<link href="<?=Resource?>activity/lianliankan/css/style.css" type="text/css" rel="stylesheet" />
	<script>(function(c){var f=window,e=document,a=e.documentElement,b="orientationchange" in window?"orientationchange":"resize",d=function(){var h=a.clientWidth;if(!h){return}var g=20*(h/320);a.style.fontSize=(g>40?40:g)+"px"};if(!e.addEventListener){return}f.addEventListener(b,d,false);d()})();</script>
</head>

<body>
	<div class="pg-my">
		<div class="bg"></div>
		<div class="panel-gift gift-top-list">
			<div class="tit">
				<i class="bg1"></i>
				<i class="bg2"></i>
			</div>
			<i class="icon-tree"></i>
			<div class="outer">
				<div class="inner">
					<ul class="gift-list">
                                                <?php $i=1; foreach($data as $value){ ?>
						<li>
							<div class="col col-1"><span class="num num-<?=$i?>"><?=$i?></span><?=isset($value['nick'])&&!empty($value['nick'])?$value['nick']:'小宝'?></div>
							<div class="col col-2"><?=$value['telephone']?></div>
							<div class="col col-3"><?=$value['total_score']?>分</div>
						</li>
                                                <?php $i++; } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="block"></div>
	</div>
</body>

</html>
