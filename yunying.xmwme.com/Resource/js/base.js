var BB = BB || {};
//响应式加载
BB.responsive = function(options) {
  var defaults = {
    fontSize: 20,
    maxFontSize: 40
  };
  var opts = $.extend({}, defaults, options),
    win = window,
    doc = document,
    docElem = doc.documentElement,
    Events = 'orientationchange' in window ? 'orientationchange' : 'resize',
    func = function() {
      var clientWidth = docElem.clientWidth;
      if (!clientWidth) return;
      var size = opts.fontSize * (clientWidth / 320);
      docElem.style.fontSize = (size > opts.maxFontSize ? opts.maxFontSize : size) + "px";
    };
  if (!doc.addEventListener) return;
  win.addEventListener(Events, func, false);
  doc.addEventListener('DOMContentLoaded', func, false);
};
//BB.responsive();
//正则表达式
BB.regx = {
  //身份证
  idcard: /^([1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3})|([1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X))$/,
  zh: /^[\u4E00-\u9FA5\uf900-\ufa2d·s]{2,13}$/, //中文
  mobile: /^1[3|4|5|7|8]\d{9}$/, //手机号
  number: /^\d+$/, //正整数
  bank: /^\d{16,19}$/, //银行卡号
  isNumber: /^[0-9]+$/, //验证是否是数字
  isString: /^[a-zA-Z]+$/, //验证是否是字符串
  isFloatTwo: /^\d+\.?\d{0,2}$/, //验证是否是两位小数
  isRNS: /(\r|\n|\s)/, //验证空格、换行、回车
  isSpecial: /^[^a-zA-Z0-9]+$/, //特殊字符
  isZw: /[\u4E00-\u9FA5]/,
  isAllZw: /^[\u4E00-\u9FA5]+$/, //中文
  birthday: /^(^(\d{4}|\d{2})(\-|\/|\.)\d{1,2}\3\d{1,2}$)|(^\d{4}年\d{1,2}月\d{1,2}日$)$/, //生日验证
  yzm: /^\d{6}$/, //验证码
  picyzm: /^\d{4}$/
};
//消息加载和弹窗框
BB.popup = {
  alert: function(text, url) {
    layer.open({
      content: "<p class='c-text'>" + text + "</p>",
      style: 'background:rgba(0,0,0,.5);color:#FFF;max-width:12rem;min-width:8rem;width:auto',
      time: 3,
      shade: false,
      success: function() {
        url !== undefined ? setTimeout(function() { window.location = url }, 2000) : !0;
      }
    });
  },
  cover: {
    show: function() {
      if (!$(".cover-layer").length) {
        $("body").append('<div class="cover-layer"></div>');
      }
      $(".cover-layer").show();
      setTimeout(function() {
        $(".cover-layer").addClass('toggle')
      }, 50)
    },
    hide: function() {
      $(".cover-layer").removeClass('toggle');
      setTimeout(function() {
        $(".cover-layer").hide()
      }, 300)
    }
  },
  loading: {
    show: function() {
      layer.open({
        className: "popup-loading",
        content: '<p class="c-text"><i class="loading"></i><br>努力加载中，稍等哟<p>',
        shade: true,
        shadeClose: false
      });
    },
    hide: function() {
      layer.closeAll();
    }
  },
  zoom: {
    show: function() {
      if ($(".popup-zoom").length) {
        $(".popup-zoom").show()
      } else {
        $("body").append('<div class="popup-zoom"></div>')
      }
    },
    hide: function() {
      $(".popup-zoom").hide()
    }
  }
};
BB.imgZoom = function() {
  $("[data-rel='zoom']").on("click", function() {
    BB.popup.zoom.show();
    $(".popup-zoom").html("<img src=" + $(this).attr("data-src") + ">")
  });
  $(document).delegate('.popup-zoom', 'click', function() {
    BB.popup.zoom.hide();
  });
};

//tab切换
BB.tabSwith = function(options) {
  var defaults = {
    elem: ".ui-tab",
    menu: ".ui-tab-nav",
    item: ".ui-tab-item",
    event: "mouseover",
    active: "active"
  };
  var opts = $.extend({},
    defaults, options);
  $(document).delegate(opts.menu + " li", opts.event, function() {
    var $icon = $(this).closest(opts.menu).find(".icon-cur");
    if ($icon.length) {
      var left = $(this).position().left + $(this).outerWidth() / 2 - 17;
      $icon.stop(true, false).animate({
        left: left
      })
    };
    $(this).addClass(opts.active).siblings('li').removeClass(opts.active);
    $(this).closest(opts.elem).find(opts.item).eq($(this).closest(opts.menu).find('li').index(this)).show().siblings(opts.item).hide();
  })
};
BB.checkBox = function() {
  $('input[type="checkbox"]').each(function(i) {
    $(this).after('<span class="ipt-checkbox"></span>');
    $(this).addClass("real-checkbox");
    if ($(this).prop("checked") == true) {
      $(this).next(".ipt-checkbox").addClass("checked");
    }
  });
  $(document).delegate(".real-checkbox", "change", function() {
    var $elem = $(this).parent().find(".ipt-checkbox");
    if ($elem.hasClass("checked")) {
      $elem.prev('input[type="checkbox"]').prop("checked", false);
      $elem.removeClass("checked");
    } else {
      $elem.prev('input[type="checkbox"]').prop("checked", true);
      $elem.addClass("checked");
    }
  })
};
BB.actionSheet = function() {
  $('[data-rel="ActionSheet"]').on("click", function() {
    var id = $(this).attr('data-as-id');
    BB.popup.cover.show();
    $("#" + id).addClass('toggle');
  });
  $(".actionsheet-menu .close").on("click", function() {
    $(this).parent().parent().removeClass('toggle');
    BB.popup.cover.hide();
  });
  $(".actionsheet-menu li").on("click", function() {
    var val = $(this).attr("data-val"),
      ch = $(this).attr('data-c'),
      id = $(this).closest('.actionsheet-menu').attr("id");
    phone = $(this).attr('data-p');
    if (!$(this).hasClass('add')) {
      $(this).addClass('active').siblings().removeClass('active');
      !$("i", this).length ? $(this).append('<i class="icon-sel"></i>') : !0;
      $(this).siblings(".active").find("i").remove();
      $('[data-as-id= "' + id + '"]').find("input[type='hidden']").val(val);
      ch != null && $('#quick').attr('href', '/buy/agree/?c=' + ch);
      $('[data-as-id= "' + id + '"]').find(".text").html($(this).html());
      $('#phone').html(phone);
      BB.popup.cover.hide();
      $(this).closest('.actionsheet-menu').removeClass('toggle');
    }
  });
  $(".actionsheet-menu li.active").append('<i class="icon-sel"></i>');
};
//银行卡号格式化
BB.formatBankNo = function(BankNo) {
  if (BankNo.value == "") return;
  var account = new String(BankNo.value);
  account = account.substring(0, 22);
  if (account.match(".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null) {
    if (account.match(".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}|" + ".[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{7}") == null) {
      var accountNumeric = accountChar = "",
        i;
      for (i = 0; i < account.length; i++) {
        accountChar = account.substr(i, 1);
        if (!isNaN(accountChar) && (accountChar != " ")) accountNumeric = accountNumeric + accountChar;
      }
      account = "";
      for (i = 0; i < accountNumeric.length; i++) {
        if (i == 4) account = account + " ";
        if (i == 8) account = account + " ";
        if (i == 12) account = account + " ";
        account = account + accountNumeric.substr(i, 1)
      }
    }
  } else {
    account = " " + account.substring(1, 5) + " " + account.substring(6, 10) + " " + account.substring(14, 18) + "-" + account.substring(18, 25);
  }
  if (account != BankNo.value) BankNo.value = account;
};
//银行选择
BB.selectBank = function() {
  if ($(".select-bank").attr("data-tap") != "false") {
    $(".select-bank").on('click', function($event) {
      $event.preventDefault();
      $("#pg_bank").removeClass("right2").addClass("right");
      $("#sel_bank_list").removeClass("left").addClass("right2");
    });
    $("#sel_bank_list li").on('click', function($event) {
      $event.preventDefault();
      if (!$(this).hasClass('add')) {
        $("#sel_bank_list li").removeClass('active');
        $(this).addClass('active');
        $("#sel_bank_list").removeClass("right2").addClass("left");
        $("#pg_bank").removeClass("right").addClass("right2");
        $("input[name='bank_id']").val($(this).attr("data-val"));
        $("input[name='bank_mark']").val($(this).attr("data-val"));
        $(".select-bank").addClass('on').text($(this).text());
        $('.text').text($(this).attr('phone'));
        $('.agreements a').attr('href', '/buy/agree/?c=' + $(this).attr("pay_aisle")).parent('.agreements').show();
      }
    });
  }
};

BB.toggleTrans = function() {
  $("[data-rel='more'] dt").on("click", function($event) {
    $event.stopPropagation();
    var $parent = $(this).closest("dl"),
      $dd = $parent.find("dd");
    $parent.hasClass('active') ? $parent.removeClass('active') : $parent.addClass('active');
    $dd.height($dd.height() == 0 ? $dd.prop("scrollHeight") : 0)
  })
};

//文本域自适应高度
BB.autoTextarea = function(options) {
  var defaults = {
    elem: null,
    minHeight: null,
    maxHeight: null
  };
  var opts = $.extend({}, defaults, options);
  return $(opts.elem).each(function() {
    $(this).bind("paste cut keydown keyup focus blur",
      function() {
        var height, style = this.style;
        this.style.height = opts.minHeight + 'rem';
        if (this.scrollHeight / 40 > opts.minHeight) {
          if (opts.maxHeight && this.scrollHeight / 40 > opts.maxHeight) {
            height = opts.maxHeight;
            style.overflowY = 'scroll';
          } else {
            height = this.scrollHeight;
            style.overflowY = 'hidden';
          }
          style.height = height / 40 + 'rem';
        }
      });
  });
};
/**
 * 表单验证 BB.form
 * 
 * 日期：2015-10-20
 * @editor: Harray<gong86627@qq.com>
 * 
 * 基本功能
 *    去除字符串中的空格       trim
 *    tips                表单消息错误提示 
 *    simSelect           选择 
 *    default             按钮默认不可提交
 *    checkInputs         按钮可提交状态
 *    checkVal            判断是否为空
 *    checkMobile         检查手机
 *    checkRealName           验证姓名
 *    checkIDNumber           验证身份证
 *    checkBankNumber         验证银行卡
 *    checkMobile         验证银行卡手机号
 *    checkPassword           验证密码
 *    confirmPassword             验证确认密码
 *    getPasswordScore            判断密码级别
 *    checkAmount         验证金额
 *    checkTextareaLength         验证可输入的长度
 *    checkSmsCode        检测短信验证码
 *    sendSmsCode         发送短信验证码
 *    clearNoNum          清除非数字
 */
BB.form = {
  isSend: false,
  trim: function(str) {
    return str.replace(/[ ]/g, "");
  },
  tips: { //表单验证提示
    show: function(elem, text, time) {
      var $item = elem.closest(".item"),
        that = this;
      $item.find(".tips-text").length ? $item.find(".tips-text").html(text) : elem.closest(".item").append('<div class="tips-text">' + text + '</div>');
      $item.find(".tips-text").show();
    },
    error: function(elem, text) {
      this.show(elem, text);
      elem.closest(".item").addClass("error");
    },
    right: function(elem) {
      elem.closest(".item").removeClass("error");
    },
    hide: function(elem) {
      var $item = elem.closest(".item");
      if ($(".tips-text", $item).length > 0) {
        $(".tips-text", $item).hide();
        $item.removeClass("error")
      }
      $item.removeClass("error");
    }
  },
  simSelect: function() { //选择
    $(".sim-sel li").on("click", function() {
      var $parent = $(".sim-sel");
      $("input[type='hidden']", $parent).val($(this).attr("data-val"));
      $(this).addClass('active').siblings().removeClass('active');
      $("#text_result").html($(this).attr("data-text"));
      $("input[name='regard']").val($(this).attr('data-val'));
    })
  },
  default: function() { //按钮默认不可提交
    if ($("button.btn-submit").attr("data-check") != "off") $("button.btn-submit").prop('disabled', true).addClass('disabled');
    $("input").on("focus", function() {
      if ($(this).closest('.item').hasClass('error')) BB.form.tips.hide($(this));
    })
  },
  checkInputs: function(elems, parent) { //按钮可提交状态
    var flag = true,
      $btn = $(".btn-submit", $(parent));
    $(elems, $(parent)).on('blur keyup click change', function() {
      $(elems, $(parent)).each(function(i) {
        if ($(this).prop("type") == "checkbox" || $(this).prop("type") == "radio") {
          if ($(this).prop("checked") == false) {
            flag = false;
            return false;
          } else {
            flag = true;
          }
        } else {
          if ($(this).val() == "") {
            flag = false;
            return false;
          } else {
            flag = true;
          }
        }

      });
      flag ? $btn.prop('disabled', false).removeClass('disabled') : $btn.prop('disabled', true).addClass('disabled');
    });
  },
  checkVal: function(elem, text) { //判断是否为空
    var $elem = $(elem);
    if (this.trim($elem.val()).length == 0) {
      this.tips.error($elem, text);
      return false;
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true;
  },
  checkMobile: function(elem) { //判断手机号
    var $elem = typeof elem == 'undefined' ? $("input[name='telephone']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim($elem.val());
    if (!that.checkVal($elem, "请输入手机号")) {
      return false
    }
    if (!regx.mobile.test(val)) {
      that.tips.error($elem, "手机号格式有误，请重新输入");
      return false
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true
  },
  checkRealName: function(elem,text) { //判断真实姓名
    var $elem = typeof elem == 'undefined' ? $("input[name='real_name']") : $(elem),
        $text = typeof text == 'undefined' ? "真实姓名" : text,
      that = this,
      regx = BB.regx,
      val = that.trim($elem.val());
    if (!that.checkVal($elem, "请输入" + $text)) {
      return false
    }
    if (!regx.zh.test(val)) {
      that.tips.error($elem, $text + "为2-13位中文");
      return false
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true
  },
  checkIDNumber: function(elem) { //判断身份证号
    var $elem = typeof elem == 'undefined' ? $("input[name='ID']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim($elem.val());
    if (!that.checkVal($elem, "请输入身份证号")) {
      return false
    }
    if (!regx.idcard.test(val)) {
      that.tips.error($elem, "身份证号码格式错误");
      return false
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true
  },
  check_bank_mark: function() {
    var $elem = typeof elem == 'undefined' ? $("input[name='bank_mark']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim($elem.val());
    if (!that.checkVal($elem, "请选择银行")) {
      return false
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true
  },
  checkBankNumber: function(elem) { //判断银行卡号
    var $elem = typeof elem == 'undefined' ? $("input[name='cardno']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim($elem.val());
    if (!that.checkVal($elem, "请输入银行卡号")) {
      return false
    }
    if (!regx.bank.test(val)) {
      that.tips.error($elem, "银行卡号为16-19位数字");
      return false
    }
    this.tips.hide($elem);
    this.tips.right($elem);
    return true
  },
  checkPassword: function(text) {
    var obj = $("input[name='password']"),
      that = this,
      regx = BB.regx,
      text = text != undefined ? text : "";
    var val = obj.val();
    if (val.indexOf(" ") > -1) {
      that.tips.error(obj, "密码中不能含有空格");
      return false
    }
    if (val.length == 0) {
      that.tips.error(obj, "请输入" + text + "密码");
      return false
    }
    if (val.length < 6 || val.length > 16) {
      that.tips.error(obj, "密码长度6-16位");
      return false
    }
    if (regx.isZw.test(val)) {
      that.tips.error(obj, "密码不能有中文，请重新输入");
      return false;
    }
    that.tips.hide(obj);
    return true
  },
  checkPayPassword: function(text) {
    var obj = $("input[name='password']"),
      that = this,
      regx = BB.regx,
      text = text != undefined ? text : "";
    var val = obj.val();
    if (val.indexOf(" ") > -1) {
      that.tips.error(obj, "密码中不能含有空格");
      return false
    }
    if (val.length == 0) {
      that.tips.error(obj, "请输入" + text + "密码");
      return false
    }
    if (val.length < 6 || val.length > 16) {
      that.tips.error(obj, "密码长度6-16位");
      return false
    }
    if (regx.isZw.test(val)) {
      that.tips.error(obj, "密码不能有中文，请重新输入");
      return false;
    }
    if (that.getPasswordScore(val) < 2) {
      that.tips.error(obj, "至少包含数字、字母（区分大小写）、符号中的2种");
      return false;
    }
    that.tips.hide(obj);
    return true
  },
  confirmPassword: function(text) {
    var obj1 = $("input[name=password]"),
      obj2 = $("input[name=password2]"),
      psw1 = obj1.val(),
      psw2 = obj2.val();
    if (psw2.length == 0) {
      this.tips.error(obj2, "请输入确认新密码");
      return false
    }
    if (psw1 != psw2) {
      this.tips.error(obj2, "两次密码不一致，请重新输入");
      return false
    }
    this.tips.hide(obj2);
    return true
  },
  getPasswordScore: function(str) { //获取密码级别
    var score = 0;
    if (str.length < 6) return 0;
    if (str.match(/[a-zA-Z]/ig)) score++;
    if (str.match(/[0-9]/ig)) score++;
    if (str.match(/[^a-zA-Z0-9]/ig)) score++;
    return score
  },
  checkAmount: function(elem, min_num, max_num) { //判断金额
    var $elem = $(elem);
    if (!BB.regx.isFloatTwo.test($elem.val())) {
      this.tips.error($elem, "请输入正确的金额");
      return false
    };
    if (Number(this.trim($elem.val())) < min_num) {
      this.tips.error($elem, "金额不能低于" + min_num + "元");
      return false
    }
    if (max_num) {
      if (Number(this.trim($elem.val())) > max_num) {
        this.tips.error($elem, "金额不能高于" + max_num + "元");
        return false
      }
    }
    this.tips.hide($elem);
    return true
  },
  checkday: function(elem, min, max) { //判断天数
    var $elem = $(elem);

    var value = $elem.val();

    if (!BB.regx.number.test(value)) {
      this.tips.error($elem, "请输入正确格式的计划期限");
      return false
    }


    if (value < min) {
      this.tips.error($elem, "计划期限不能小于" + min + "天");
      return false
    }
    if (value > max) {
      this.tips.error($elem, "计划期限不能大于" + max + "天");
      return false
    }
    this.tips.hide($elem);

    return true
  },
  checkTextareaLength: function(elem, len) {
    if ($(elem).val().length <= len) {
      $("#tip_text").html('还能输入<span class="c-red">' + (len - $(elem).val().length) + '</span>字');

      return true
    } else {
      $("#tip_text").html('超出了<span class="c-red">' + ($(elem).val().length - len) + '</span>字');
      return false
    }
  },
  checkSmsCode: function(elem) { //检测短信验证码
    var obj = typeof elem == 'undefined' ? $("input[name='yzm']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim(obj.val());
    if (!that.checkVal(obj, "请输入短信验证码")) {
      return false
    };
    if (val.length < 6 || !regx.yzm.test(val)) {
      that.tips.error(obj, "短信验证码错误");
      return false
    }
    that.tips.hide(obj);
    return true;
  },
  checkImgCode: function(elem) { //检测图形验证码
    var obj = typeof elem == 'undefined' ? $("input[name='img_code']") : $(elem),
      that = this,
      regx = BB.regx,
      val = that.trim(obj.val());
    if (!that.checkVal(obj, "请输入图形验证码")) {
      return false
    };
    if (val.length < 4 || !regx.picyzm.test(val)) {
      that.tips.error(obj, "图形验证码错误");
      return false
    }
    that.tips.hide(obj);
    return true;
  },
  sendSmsCode: function(obj, type, verify, mark) { //发送短信验证码
    var that = this;
    if (that.isSend) return;
    var telephone = $.trim($("#telephone").val());
    if (!that.checkMobile($("input[name='telephone']")) || !that.checkImgCode()) {
      return false;
    }
    $.ajax({
      type: "GET",
      url: "/index/sendmsg/",
      dataType: 'json',
      data: 'type=' + type + '&telephone=' + telephone + '&verify=' + verify + '&mark=' + mark + '&t=' + Math.random(),
      success: function(data) {
        if (data.err == '0') {
          that.tips.show(obj, data.msg);
          that.isSend = true;
          that.countdown(60, obj, function() {
            that.isSend = false;
          });
        } else if (data.err == '1') {
          that.isSend = false;
          that.tips.show(obj, data.msg);
          $('.img-code').attr('src', '/verify/index/' + '?rand=' + Math.random());
          obj.removeAttr('disabled').removeClass("disabled").text("重新获取");
        } else if (data.err == '2') {
          that.isSend = false;
          BB.popup.alert(data.msg);
          $('.img-code').attr('src', '/verify/index/' + '?rand=' + Math.random());
          obj.removeAttr('disabled').removeClass("disabled").text("重新获取");
        }
      }
    });
  },
  changeVerify: function(elem) {
    var obj = typeof elem == 'undefined' ? $(".img-code") : $(elem);
    obj.attr('src', '/verify/index/' + '?rand=' + Math.random());
  },
  countdown: function(time, obj, callback) { //短信验证码倒计时
    obj.attr('disabled', true).addClass("disabled");
    var timer,
        that = this;
    obj.text(time + "秒")
    timer = setInterval(function() {
      if (time > 1) {
        time--;
        obj.text(time + "秒")
      } else {
        clearInterval(timer);
        obj.removeAttr('disabled').removeClass("disabled").text("重新获取");
        that.tips.show(obj, '<span class="c-999">如未收到短信验证码，您可以点击' + '<a href="javascript:SendMsgYuYin();" class="btn-get-voice">获取语音验证码</a></span>');
        callback && callback.call();
      }
    }, 1000)
  },
  clearNoNum: function(obj, isFloat) {
    var value = $(obj).val();
    var reg = /[^\d\.]/g;
    valid = reg.test(value); //清除"数字"和"."以外的字符
    if (valid) {
      $(obj).val('');
    }

    if (isFloat == 0) {
      value = value.replace(/^(\d{1,11}).*$/, "$1"); //只能输入两个小数
    } else if (isFloat == 2) {
      value = value.replace(/^(\d{1,11})\.(\d\d).*$/, "$1.$2"); //只能输入两个小数
    }
    $(obj).val((value + '').substring(0, 11));
  },
  passwordSwich: function() {
    $("#psw_swich").on("click", function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $("#password").prop("type", "password");
      } else {
        $(this).addClass('active');
        $("#password").prop("type", "text");
      }
    });
  }
};
$(function() {
  BB.toggleTrans();
  BB.selectBank();
  BB.imgZoom();
  BB.form.default();
  BB.form.passwordSwich();
  BB.form.simSelect();
  BB.actionSheet();
  BB.checkBox();
})
