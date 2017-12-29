var BB = BB || {};
BB.regx = {
	idcard: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/, //身份证
	zh:/^[\\u4e00-\\u9fa5]+$"/, //中文
	mobile:/^1[3|4|5|7|8]\d{9}$/ //手机号
};
BB.responsive = function(options){ //响应式
	var defaults = {
		fontSize: 20,
		maxFontSize:40
	};
	var opts = $.extend({},defaults, options),
	win = window,
	doc = document,
	docElem = doc.documentElement,
    Events = 'orientationchange' in window ? 'orientationchange' : 'resize',
    func = function () {
        var clientWidth = docElem.clientWidth;
        if (!clientWidth) return;
        var size = opts.fontSize * (clientWidth / 320);
        docElem.style.fontSize = (size > opts.maxFontSize ? opts.maxFontSize : size) + "px";
    };
    if (!doc.addEventListener) return;
    win.addEventListener(Events, func, false);
    doc.addEventListener('DOMContentLoaded', func, false);
};
BB.responsive();
BB.form = {
    isSend:false,
    trim:function(str) {
        return str.replace(/[ ]/g,"");
    },
    tips:{ //表单验证提示
        show:function(elem,text,time){
            var $item = elem.closest(".item"),
            that = this;
            $item.find(".tips-text").length ? $item.find(".tips-text").html(text) : elem.closest(".item").append('<div class="tips-text">' + text + '</div>');
            $item.find(".tips-text").show();
        },
        error:function(elem,text){
            this.show(elem,text);
            elem.closest(".item").addClass("error");
        },
        right:function(elem){
            elem.closest(".item").removeClass("error");
        },
        hide:function(elem){
            var $item = elem.closest(".item");
            if($(".tips-text",$item).length > 0){
                $(".tips-text",$item).hide();
                $item.removeClass("error")
            }
            $item.removeClass("error");
        }
    },
    simSelect:function(){ //选择
        $(".sim-sel li").on("tap",function(){
            var $parent = $(".sim-sel");
            $("input[type='hidden']",$parent).val($(this).attr("data-val"));
            $(this).addClass('active').siblings().removeClass('active');
        })
    },
    checkVal:function(elem,text){ //判断是否为空
        var $elem = $(elem);
        if(this.trim($elem.val()).length == 0){
            this.tips.error($elem,text);
            return false;
        }
        this.tips.hide($elem);
        this.tips.right($elem);
        return true;
    },
    checkMobile:function(elem){
        var $elem = typeof elem == 'undefined' ? $("input[name='mobile']") : $(elem),
        that = this,
        regx = BB.regx,
        val = that.trim($elem.val());
        if (!that.checkVal($elem,"请输入手机号")) {
            return false
        }
        if (!regx.mobile.test(val)) {
            that.tips.error($elem,"手机号格式有误，请重新输入");
            return false
        }
        this.tips.hide($elem);
        this.tips.right($elem);
        return true
    },
    checkInputs:function(elems,parent){ //按钮可提交状态
        var flag = true,$btn = $(".btn-submit",$(parent));
        $(elems,$(parent)).on('blur keyup',function(){
         $(elems,$(parent)).each(function(i){
            if($(this).val() == ""){
                flag = false;
                return false;
            }
            else{
                flag = true;
            }
        });
         flag ? $btn.prop('disabled',false).removeClass('disabled') : $btn.prop('disabled',true).addClass('disabled');
     });   
    },
    checkPassword:function(text) {
        var obj = $("input[name='password']"),
        that = this,
        text = text != undefined ? text : "";
        var val = obj.val();
        if(val.indexOf(" ")>-1){
            that.tips.error(obj,"密码中不能含有空格");
            return false
        }
        if (val.length == 0) {
            that.tips.error(obj,"请输入"+text+"密码");
            return false
        }
        if(val.length <6 || val.length > 16){
            that.tips.error(obj,"密码长度6-16位");
            return false
        }
        if(that.getPasswordScore(val) < 2){
            that.tips.error(obj,"至少包含数字、字母（区分大小写）、符号中的2种");
            return false;
        }
        that.tips.hide(obj);
        return true

    },
    confirmPassword:function(text) {
        var obj1 = $("input[name=password]"),
        obj2 = $("input[name=confirm_password]"),
        psw1 = obj1.val(),
        psw2 = obj2.val();
        if (psw2.length == 0) {
            this.tips.error(obj2,"请输入确认新密码");
            return false
        }
        if(psw1 != psw2){
            this.tips.error(obj2,"两次密码不一致，请重新输入");
            return false
        }
        this.tips.hide(obj2);
        return true
    },
    getPasswordScore:function(str) { //判断密码级别
        var score = 0;
        if (str.length < 6) return 0;
        if (str.match(/[a-zA-Z]/ig)) score++;
        if (str.match(/[0-9]/ig)) score++;
        if (str.match(/[^a-zA-Z0-9]/ig)) score++;
        return score
    },
    checkSmsCode:function(){ //检测短信验证码
        var obj = $("input[name='sms_code']"),
        that = this,
        val = that.trim(obj.val());
        if (!that.checkVal(obj,"请输入验证码")) {
            return false
        };
        if(val.length < 6){
            that.tips.error(obj,"验证码错误");
            return false
        }
        that.tips.hide(obj);
        return true;
    },
    sendSmsCode:function (obj,time) { //发送短信验证码
        var that = this;
        if (that.isSend) return;
        var result = 1; //ajax返回值
        if (result) {
            if(time == 60){
                that.tips.show(obj,"验证码已发送");
            }
            that.isSend = true;
            that.countdown(time, obj, function () {
                that.isSend = false;
            });
        }
    },
    countdown:function (time,obj,callback) { //短信验证码倒计时
        obj.attr('disabled',true).addClass("disabled");
        var timer;
        obj.text(time + "秒")
        timer = setInterval(function () {
            if (time > 1) {
                time--;
                obj.text(time + "秒")
            }
            else {
                clearInterval(timer);
                obj.removeAttr('disabled').removeClass("disabled").text("重新获取");
                callback && callback.call();
            }
        }, 1000)
    }
};
$(function(){
    BB.form.simSelect();
})