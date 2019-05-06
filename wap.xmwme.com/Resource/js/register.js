var form = BB.form;
var registerJs = {
    aregister: false, //防重复提交
    sumbmitRegister: function() {
        if (form.checkImgCode() == false || form.checkSmsCode() == false || form.checkPassword() == false) {
            return false
        }

        var telephone = $("#telephone").val()
        var password = encryptionJs.xmwme_encryption($("input[name='password']").val());
        var yzm = $("input[name='yzm']").val();
        var invite_code = $("#invite_code").val();
        if (registerJs.aregister)
            return false;

//        BB.popup.loading.show();
        //防重复提交
        registerJs.aregister = true;
        $.ajax({
            type: "post",
            url: "/login/ajaxregister/",
            dataType: 'json',
            data: {ajax: 1, isPost: 1, step: 2, invite_code: invite_code, telephone: telephone, password: password, yzm: yzm},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                BB.popup.loading.hide();
                BB.popup.alert('网络异常,请稍后重试');
                form.isSend = false;
                registerJs.aregister = false;
            },
            success: function(data) {
//                BB.popup.loading.hide();
                if (data.err == '0') {
                    $("button.btn-submit").prop('disabled', true).addClass('disabled');
                    BB.popup.alert(data.msg, data.url);
                } else
                {
                    registerJs.aregister = false;
                    BB.popup.alert(data.msg);
                    form.isSend = false;
                }
            }

        });

        return false;
    }
}

//发送语音短信
function SendMsgYuYin() {
    verify = $('input[name="img_code"]').val();
    form.checkMobile() && form.sendSmsCode($("button.btn-get-code"), 2, verify, 1);//edit by gonyl 17-01-05
}

$(function() {
    form.checkInputs("input[name='img_code'],input[name='yzm'],input[name='password']", "#reg_form_2");
    $("button.btn-get-code").on("click", function() {
        verify = $('input[name="img_code"]').val();
        form.checkMobile() && form.sendSmsCode($(this), 1, verify, 1);//edit by gonyl 17-01-05
    });
    $('#reg_form_2').submit(function() {
        return registerJs.sumbmitRegister();
    });
});