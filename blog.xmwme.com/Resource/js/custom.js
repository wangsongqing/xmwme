	jQuery(function($){	
		//highlight
		hljs.initHighlightingOnLoad();
		
		//to-top
		$(window).scroll(function(){
			if ($(this).scrollTop() >= 30) {
				if (!$(".to-top").hasClass("topbtnfadein"))
					$(".to-top").removeClass("topbtnfadeout topbtnhide").addClass("topbtnfadein topbtnshow").removeClass("topbtnfadein");
				//$(".to-top").stop().animate({bottom: 30, opacity: 100});
			} else {
				if (!$(".to-top").hasClass("topbtnfadeout"))
					$(".to-top").removeClass("topbtnfadein topbtnshow").addClass("topbtnfadeout topbtnhide").removeClass("topbtnfadeout");
			}
		})
		
		$(".to-top").click(function(){
			$("body, html").stop().animate({scrollTop:0});
		});
		
		//poshytip
		$('.widget a').poshytip({
			className: 'tip-twitter',
			showTimeout: 1,
			alignTo: 'target',
			alignX: 'center',
			alignY: 'bottom',
			offsetY: 5,
			allowTipHover: false,
		});
		
		//menu
		var idxY = null;
		$(".nav ul li").each(function(){
			var navY = $(this).position().top;
			if ($(this).hasClass("current-menu-item") || $(this).hasClass('current-category-ancestor') || $(this).hasClass("current-post-ancestor")) {
				idxY = navY;
			} else {
				idxY = -15;
			}
			$("#nav-current").css({top: idxY+15});
			$(this).mouseenter(function(){
				$("#nav-current").stop().animate({top: navY+15}, 300);
			});
		});
		$(".nav ul").mouseleave(function(){
			$("#nav-current").stop().animate({top: idxY+15}, 500);
		});
		
		//Switch of screen width controller
		$(".wider-switch").click(function(){
			if ($(this).find('i').hasClass('fa-expand')) {
				toWider();
			} else {
				toNarrow();
			}
		}); 

		var listHideLeft = 110;
		var showLeft = 320;
		var weiboHideLeft = 60;
		var toWider = function(){
			listHideLeft = -150;
			weiboHideLeft = -200;
			showLeft = 60;
			$(".left").css('left', '-260px').addClass('iswider');
			$(".container").css('margin-left', '80px');
			$('.list').css('left', listHideLeft);
			$('.weibo-show').css('left', weiboHideLeft);
			$(".wider-switch").find('i').removeClass('fa-expand').addClass('fa-compress');
			setCookie('widerCookie',"true", 86400);
		}
		var toNarrow = function(){
			listHideLeft = 110;
			weiboHideLeft = 60;
			showLeft = 320;
			$(".wider-switch").find('i').removeClass('fa-compress').addClass('fa-expand');
			$(".left").css('left', '0').removeClass('iswider');
			$(".container").css('margin-left', '340px');
			$('.list').css('left', listHideLeft);
			$('.weibo-show').css('left', weiboHideLeft);
			setCookie('widerCookie',"false", 86400);
		}
		if (getCookie('widerCookie')=='true') {
			toWider();
		} else {
			toNarrow();
		}
		
		//side icon
		$(".icon-category").click(function(){
			if ($(this).hasClass("list-open")) {
				$(this).removeClass('list-open').children('span').html('展开分类目录');
				$(".list").stop().animate({left: listHideLeft}, 500);
			}
			else {
				$(this).addClass('list-open').children('span').html('关闭分类目录');
				if ($('.sns-weibo').hasClass('sns-weibo-open'))
				{
					$('.sns-weibo').click();
				}
				$(".list").stop().animate({left: showLeft}, 500);
			}
		});
		$(".sns-weibo").click(function(){
			if ($(this).hasClass("sns-weibo-open")) {
				$(this).removeClass('sns-weibo-open').children('span').html('展开微博窗口');
				$(".weibo-show").stop().animate({left: weiboHideLeft}, 500);
			}
			else {
				$(this).addClass('sns-weibo-open').children('span').html('关闭微博窗口');
				if ($('.icon-category').hasClass('list-open'))
				{
					$('.icon-category').click();
				}
				$(".weibo-show").stop().animate({left: showLeft}, 500);
			}
		});
		
		
		document.addEventListener("touchstart", function(){}, true);
		
		//Sidebox Close and Open 
		$(".sidebox > h2").click(function(){
			if ($(this).parent().height()>50) {
				$(this).parent().height(50);
			} else {
				$(this).parent().height('');
			}
		});
		
		//#categories
		$("#categories").find('select').change(function(){
			window.location.href=$(this).val();
		});
		
		//loading 
		$('.loading').animate({'width':'95%'},9000);
		$(window).load(function()
		{
			$('.circle-loading').fadeOut(300);
			$('.loading').stop().animate({'width':'100%'},300,function()
			{
				$(this).fadeOut(300);
			});
		});
		
	});
	
//取得cookie    
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';'); //把cookie分割成组    
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i]; //取得字符串    
		while (c.charAt(0) == ' ') { //判断一下字符串有没有前导空格    
			c = c.substring(1, c.length); //有的话，从第二位开始取    
		}
		if (c.indexOf(nameEQ) == 0) { //如果含有我们要的name    
			return unescape(c.substring(nameEQ.length, c.length)); //解码并截取我们要值    
		}
	}
	return false;
}

//清除cookie    
function clearCookie(name) {
	setCookie(name, "", -1);
}

//设置cookie    
function setCookie(name, value, seconds) {
	seconds = seconds || 0; //seconds有值就直接赋值，没有为0，这个跟php不一样。    
	var expires = "";
	if (seconds != 0) { //设置cookie生存时间    
		var date = new Date();
		date.setTime(date.getTime() + (seconds * 1000));
		expires = "; expires=" + date.toGMTString();
	}
	document.cookie = name + "=" + escape(value) + expires + "; path=/"; //转码并赋值    
}  