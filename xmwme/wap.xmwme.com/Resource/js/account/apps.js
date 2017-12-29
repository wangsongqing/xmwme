$(function(){
    ;(function(){ //按钮默认不可提交
        $("button.btn-submit").not("[data-check='off']").prop('disabled',true).addClass('disabled');
        $("input").on("focus",function() {
            if ($(this).closest('.item').hasClass('error')) BB.form.tips.hide($(this));
        })
    })();
    ;(function(){ //验证初始化
        var form = BB.form;
        form.checkInputs("input[name='sms_code'],input[name='password'],input[name='confirm_password']","#reg_form_2");
        $("#reg_form_1").submit(function() {
            return form.checkMobile();
        });
        $("#reg_form_2").submit(function() {
            if(form.checkSmsCode() == false || form.checkPassword() == false || form.confirmPassword() == false){
                return false
            }
        }); 
    })();
    ;(function(){ //获取验证码
        obj = $("button.btn-get-code");
        obj.on("click",function(){
            BB.form.sendSmsCode(obj,60)
        });
        if($("#reg_form_2").length){
            BB.form.sendSmsCode(obj,60)
        };
    })();
});