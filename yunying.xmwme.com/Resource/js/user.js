var user = {
	mode     : 1,
	webPath  : '',
	errorCode : true,
	init  : function(path)
	{
		user.webPath = path;
	},
	
	authLogin : function(form)
	{   
            var account = $('#account').val();
            var password= $('#password').val();
            if(account=='' || account=='请输入您的账号')
            {
                    alert("请输入登录帐号!");
                    return false;
            }

            if(password=='')
            {
                    alert("请输入登录密码!");
                    return false;
            }
        
        account = encryptionJs.run_encryption(account);//对数据进行加密处理
        password = encryptionJs.run_encryption(password);
        
        $.post('/login/ajaxLogin/',{account:account,password:password},function(msg){
            if(msg.code==3){
                alert(msg.msg);return false;
            }
            if(msg.code==1){
                alert(msg.msg);
                window.location.href = '/index/index';
            }
        },'json');
            return false;
	},
    
};

