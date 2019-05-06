//@author song 2015/10/21
var form = BB.form;
var forgetPasswdJs = {
    stepOne: function() {
        if (form.checkMobile() == false || form.checkSmsCode() == false || form.checkImgCode() == false) {
            return false
        }
        if (this.phoneExsit($('input[name="telephone"]').val()) == false) {
            return false;
        }

        //手机号码
        var telephone = $("#telephone").val();
        //验证码
        var yzm = $("#yzm").val();
        //验证验证码
        var bool = true;
        //异步验证密码的正确性
        $.ajax({
            type: "post",
            url: "/login/validyzm/",
            async: false,
            dataType: 'json',
            data: 'yzm=' + yzm + '&telephone=' + telephone,
            success: function(data) {
                if (data.err == 'fail') {
                    BB.popup.alert(data.msg);
                    bool = false;
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                BB.popup.loading.hide();
                this.isSend = false;
                BB.popup.alert('网络异常,请稍后重试');
            }
        });

        return bool;
    },
    step2: function() {
        if (form.checkPassword() == false || form.confirmPassword() == false) {
            return false
        }
        var telephone = $("#telephone").val();
        var password = $("#password").val();
        var password2 = $("#password2").val();
        var rand = $("#rand").val();
        var bool = true;
        //异步验证密码的正确性
        BB.popup.loading.show();
        $.ajax({
            type: "post",
            url: "/login/forget/",
            dataType: 'json',
            data: 'isPost=1&step=2&password=' + password + '&password2=' + password + '&telephone=' + telephone + '&rand=' + rand,
            success: function(data) {
                BB.popup.loading.hide();
                if (data.err == 'success') {
                    bool = true;
                    BB.popup.alert(data.msg, data.url);
                } else {
                    BB.popup.alert(data.msg);
                    bool = false;
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                BB.popup.loading.hide();
                this.isSend = false;
                BB.popup.alert('网络异常,请稍后重试');
            }
        });
        return bool;
    },
    //检验手机是否存在
    phoneExsit: function(phone) {
        var bool = true;
        $.ajax({
            type: "GET",
            url: "/login/ajaxphoneExsit/",
            async: false,
            dataType: 'json',
            data: 'telephone=' + phone + '&t=' + Math.random(),
            success: function(data) {
                if (data.err == 'fail') {
                    var msg = '';
                    if (data.msg == '1') {
                        msg = '该手机号不存在';
                    }
                    form.tips.error($('input[name="telephone"]'), msg);
                    bool = false;
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                BB.popup.loading.hide();
                this.isSend = false;
                BB.popup.alert('网络异常,请稍后重试');
            }
        });

        return bool;
    }
}

//发送语音短信
function SendMsgYuYin() {
    verify = $('input[name="img_code"]').val();
    form.checkMobile() && form.sendSmsCode($("button.btn-get-code"), 2, verify, 2);//edit by gonyl 17-01-05
}

//加载事件
$(function() {
    form.checkInputs("input[name='telephone'],input[name='yzm'],input[name='img_code']", "#psw_form_4");
    form.checkInputs("input[name='password'],input[name='confirm_password']", "#psw_form_5");
    //忘记密码验证码
    var button = $("button.btn-get-code");
    button.on("click", function() {
        verify = $('input[name="img_code"]').val();
        form.checkMobile() && form.sendSmsCode(button, 1, verify, 2);//edit by gonyl 17-01-05
    });
    $('#confirmPassword').click(function() {
        forgetPasswdJs.step2();
    });
})