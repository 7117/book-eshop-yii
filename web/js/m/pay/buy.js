$(document).ready( function(){

    var pay = {

        init:function(){
            this.eventBind();
        },

        eventBind:function(){
            $(".do_pay").click( function() {
                var button = $(this);
                if( button.hasClass("disabled") ){
                    alert( "正在提交，请不要重复提交" );
                    return ;
                }
                button.addClass("disabled");
                $.ajax({
                    url:common_ops.buildMUrl("/pay/prepare"),
                    type:'POST',
                    data:{
                        pay_order_id:$(".hide_wrap input[name=pay_order_id]").val()
                    },
                    dataType:'json',
                    success:function( res ){

                        if( res.code == 200 ){
                            var data = res.data;
                            var json_data = {
                                timestamp: data.timeStamp,
                                nonceStr: data.nonceStr,
                                package: data.package,
                                signType: data.signType,
                                paySign: data.paySign,
                                success: function () {
                                    window.location.href = common_ops.buildMUrl("/user/index");
                                },
                                cancel: function(){
                                    alert("取消了支付");
                                    button.removeClass("disabled");
                                },
                                fail: function(){
                                    alert("支付失败");
                                    button.removeClass("disabled");
                                }
                            };
                            weixin.wxPay(json_data);
                        }else{
                            button.removeClass("disabled");
                            alert(res.msg);
                        }
                    }
                })
            })
        }
    }

    pay.init();
})