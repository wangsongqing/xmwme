var rewards = {
    gid: 0,
    aregister: false,
    rand: '',
    number: function() { //购物车数量控制
        $("[data-rel='cart-item']").each(function() {
            var $ipt = $(this).find(".ipt-number"),
                    $plus = $(this).find(".plus"),
                    $minus = $(this).find(".minus"),
                    step = Number($(this).attr("data-step")),
                    amount = Number($(this).attr("data-amount")),
                    timer1, timer2, timer3;
            $plus.on("click", function() {
                var val = Number($ipt.val());
                if (val < amount) {
                    if (val % step > 0) {
                        $ipt.val(val - val % step)
                    }
                    if (val + step > amount) {
                        $ipt.val(amount)
                    } else {
                        $ipt.val(Number($ipt.val()) + step)
                    }
                } else {
                    return false
                }
                ;
            });
            $minus.on("click", function() {
                var val = Number($ipt.val());
                if (val > step) {
                    if (val % step > 0) {
                        $ipt.val(val - val % step)
                    } else {
                        $ipt.val(val - step)
                    }
                } else {
                    return false
                }
                ;
            })
            $ipt.on("keyup", function() {
                var val = Number($(this).val()),
                        $this = $(this);
                clearTimeout(timer3);
                timer3 = setTimeout(function() {
                    if (val % step > 0) {
                        $this.val(val - val % step + step)
                    }
                }, 500)
            })
        })
    },
    init: function() {
        this.number()
    },
    buy: function() {//兑换投资

        layer.open({
            content: "您是否确认使用积分兑换该商品？", //文字
            btn: ['确认', '取消'], //按钮
            yes: function() { //确定
                if (rewards.aregister == true)
                    return false;
                rewards.aregister = true;
                BB.popup.loading.show();
                $.ajax({
                    type: "post",
                    url: '/goods/buy/',
                    dataType: 'json',
                    data: {'gid': rewards.gid, 'num': $('.ipt-number').val(), 'rand': rewards.rand},
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        BB.popup.loading.hide();
                        rewards.aregister = false;
                        BB.popup.alert('网络异常,请稍后重试');
                    },
                    success: function(data) {
                        BB.popup.loading.hide();
                        if (data.err < 0 && data.err == '-1003') {
                            //确定按钮跳转
                            layer.open({
                                content: data.msg, //文字
                                btn: ['赚取积分', '关闭'], //按钮
                                yes: function() {
                                    window.location.href = '/goods/index/';
                                },
                                no: function() {
                                    window.location.reload();
                                }
                            })
                        } else if (data.err < 0) {
                            rewards.aregister = false;
                            BB.popup.alert(data.msg);
                        } else if (data.err == 1) {
                            //确定按钮跳转
                            layer.open({
                                content: data.msg, //文字
                                btn: ['确定', '取消'], //按钮
                                yes: function() {
                                    window.location.href = data.url;
                                },
                                no: function() {
                                    window.location.reload();
                                }
                            })
                        }else {
                            BB.popup.alert(data.msg, data.url);
                        }
                    }
                })
            }
        });
    }


}
$(function() {
    rewards.init();
})
