/**
 * mybank
 * @auther sumic
 */
var BB = BB || {};
BB.regx = {
  idcard: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/, //身份证
  b_name: /^[\\u4e00-\\u9fa5]+$"/, //中文
  mobile: /^1[3|4|5|7|8]\d{9}$/ //手机号
};

$(function() {
  $(".del").click(function() {
    var cardid = $(this).attr('rel');

    layer.open({
      content: '您确定删除这张卡吗？', //文字
      btn: ['确定', '取消'], //按钮
      yes: function() { //确定
        BB.popup.loading.show();
        $.ajax({
          type: "post",
          url: "/mybank/dropcard/",
          dataType: 'json',
          data: { id: cardid },
          success: function(data) {
            layer.close();
            BB.popup.loading.hide();
            BB.popup.alert(data.msg,data.url);
            if (data.err > 0) {
              $("#card" + cardid).remove();
            }
          }
        })
      }
    })
  });

  $(".apply_safe").click(function() {
        BB.popup.loading.show();
        $.ajax({
          type: "GET",
          url: "/mybank/safe_replace/",
          dataType: 'json',
          success: function(data) {
            layer.close();
            BB.popup.loading.hide();
            if(data.err == '-1003'){
              layer.open({
                content: data.msg,  //文字
                btn: ['确定', '取消'], //按钮
                yes: function(){ //确定
                  location.href= data.url;
                }
              });
            }else if(data.err<0){
              layer.open({
                content: data.msg,  //文字
                btn: ['确定'], //按钮

              });
            }else {
              location.href= data.url;
            }
          }
    })
  });

  $("#submit_edit").click(function() {
    BB.popup.loading.show();
    var p_id = $("#p_id").val();
    var c_id = $("#c_id").val()
    var b_name = $("#b_name").val()
    var card_id = $("#card_id").val()
    $.ajax({
      type: "post",
      url: "/mybank/editcard/",
      dataType: 'json',
      data: { id: card_id, branch_name: b_name, province: p_id, city: c_id },
      success: function(data) {
        BB.popup.loading.hide();
        BB.popup.alert(data.msg);
        if (data.err == 2) {
          location.href = data.url;
        }
      }
    })
  });

  $("#auditing").click(function() {
    BB.popup.loading.show();
    var cardid    = $("#bank_val").val();
    var scsfz  = $("#scsfz").val();
    var scyhk     = $("#scyhk").val();
    $.ajax({
      type: "post",
      url: "/mybank/safe_replace/",
      dataType: 'json',
      data: { card_id: cardid, scsfz: scsfz, scyhk: scyhk},
      success: function(data) {
        BB.popup.loading.hide();
        location.href = data.url;
        if (data.err < 0) {
          BB.popup.alert(data.msg);
          location.href = data.url;
        }
      }
    })
  });
  var form = BB.form;
  form.checkInputs("input[name='branch_name'],input[name='province'],input[name='city'],input[name='sfzscsfz'],input[name='scyhk'],input[name='cardid']", "#cash_form_5");
})