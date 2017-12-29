var form = BB.form;
var cgJs = {
	aregister:false,//防重复提交
	//验证原交易密码
    validatePayPasswd : function()
    {
    	if(form.checkPayPassword() == false){
    		BB.popup.alert('密码格式错误');
    		return false;
    	}
    	if(cgJs.aregister == true) return false;
    	password = encryptionJs.edai_encryption($('#password').val());
    	agreement = $("input[name='agreement']").is(':checked');
    	BB.popup.loading.show();
    	cgJs.aregister = true;
        //异步验证密码的正确性
        $.ajax({
            type: "post",
            url : "/account/authorization/",
            dataType:'json',
            data:{password:password,agreement:agreement},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            	BB.popup.loading.hide();
            	cgJs.aregister = false;
            	BB.popup.alert('网络异常,请稍后重试');
            },
            success: function(data){
            	BB.popup.loading.hide();
                if(data.err == '0') {
                	hideAuthorizePopup();
                	BB.popup.alert(data.msg);
                }else
                {
                	cgJs.aregister = false;
            		BB.popup.alert(data.msg);
                }
            }
        });
        return false;
    },
    //关闭弹窗
    closeDialog : function(msg_id,type,position)
    {
    	if(position=='undefined') position = 1;
        //你已经关闭了弹框
        $.ajax({
            type: "post",
            url : "/account/closeDialog/",
            dataType:'json',
            data:{msg_id:msg_id,position:position},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            	BB.popup.loading.hide();
            	BB.popup.alert('网络异常,请稍后重试');
            },
            success: function(data){
            	BB.popup.loading.hide();
                if(data.err == '0') {
                	if(type==1) {
                		hideVipUpgradePopup();
                	}else if(type==2){
                		hideCGPopup();
                	}else{
                		hideAuthorizePopup();
                	}
                }else
                {
            		//BB.popup.alert(data.msg);
                }
            }
        });
        return false;
    }
}

$(function(){
    ;(function(){ //验证初始化
        form.checkInputs("input[name='password'],input[name='agreement']","#auth_form");
    })();
});
