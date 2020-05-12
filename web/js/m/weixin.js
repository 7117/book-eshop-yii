$(document).ready(function(){

    var weixin = {
        init:function(){
            this.initJSconfig();
        },
        initJSconfig:function(){
            var that = this;
            $.ajax({
                url:'/weixin/jssdk/index?url='+encodeURIComponent(location.href.split('#')[0]),
                type:'GET',
                dataType:'json',
                success:function( res ){

                    if( res.code != 200 ){
                        return ;
                    }

                    var data = res.data;
                    wx.config({
                        debug: false,
                        appId: data['appId'],
                        timestamp: data['timestamp'],
                        nonceStr: data['nonceStr'],
                        signature: data['signature'],
                        jsApiList: [ 'onMenuShareTimeline','onMenuShareAppMessage','wx.onMenuShareQQ' ]
                    });

                    wx.ready(function(){

                        var share_info =  eval( '(' + $("#share_info").val() + ")" );
                        var title = share_info.title;
                        var link = encodeURIComponent(  location.href.split('#')[0] );
                        var desc = share_info.desc;
                        var img_url = share_info.img_url;

                        wx.onMenuShareTimeline({
                            title: title,
                            link: link,
                            imgUrl: img_url,
                            success: function () {
                                that.sharedSuccess();
                            },
                            cancel: function () {
                            }
                        });
                        wx.onMenuShareAppMessage({
                            title: title,
                            desc: desc,
                            link: link,
                            imgUrl: img_url,
                            type: 'link',
                            success: function () {
                                that.sharedSuccess();
                            },
                            cancel: function () {
                            }
                        });
                        wx.onMenuShareQQ({
                            title: title,
                            desc: desc,
                            link: link,
                            imgUrl: img_url,
                            success: function () {
                                that.sharedSuccess();
                            },
                            cancel: function () {
                            }
                        });
                    });

                    wx.error(function(res){
                    });
                }
            });
        },
        wxPay:function(json_data){
            wx.ready(function(){
                wx.chooseWXPay(json_data);
            });
        },
        sharedSuccess:function(){
            $.ajax({
                url:common_ops.buildMUrl("/default/shared"),
                type:'POST',
                dataType:'json',
                data:{
                    url:window.location.href
                },
                success:function(){
                    alert("success");
                },
                error:function(){
                    alert("failed");
                }
            });
        }
    };

    weixin.init();

});
