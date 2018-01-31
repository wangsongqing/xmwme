var encryptionJs = {
    //公钥串
    public_key :    'A0B6C69752FF22AD559C0BF6CEEE81232CF967B8823BCEB095E0588725C3F2AC33A13C15B5F2B9E6793845891DBB48970228105A87686D4666BF69CFB26AB7AAC632F17DC0811AB1E944524714D32494B5D52ADEF9FA7183D1E3065792A56EA02F2F7CB1176D0EF8DC42B52CACE30FF36CB1B9BF190CB00D273ABE67BAA6690D',
    //公钥长度
    public_length : "10001",
	
	
   /**
    * 加密串
    * str 加密变量
    * @return bool
    */
    run_encryption:function(str){
      var rsa = new RSAKey();
      rsa.setPublic(encryptionJs.public_key, encryptionJs.public_length);
      var res = rsa.encrypt(str);
      return res;
    },
    /**
     * 加密
     */
    form_encryption:function(formDataStr){
    	datas=formDataStr.split("&"); 
    	var postArr = new Array();
    	$.each(datas,function(i,v){
    		var vv=v.split("="); 
    		postArr.push(vv[0]+'='+(vv[1]?encryptionJs.edai_encryption(vv[1]):''));
    	});
    	postStr = postArr.join('&');
    	return postStr;
    }
	
}