$(document).ready(function () {

    upload = {
        error : function(msg){
            common_ops.alert( msg );
        },
        success : function (image_key) {
            var html =
                '<img src="'+common_ops.buildPicUrl('brand',image_key)+'">' +
                '<span class="fa fa-times-circle del del_image" data="'+image_key+'">' +
                '<i></i>' +
                '</span>';

            if ($(".upload_pic_wrap .pic-each").size() > 0 ){
                $(".upload_pic_wrap .pic-each").html(html);
            } else {
                $(".upload_pic_wrap ").append( '<span class="pic-each">'+ html + '</span>');

            }
        }
    };

    $(".set_pic").click(function(){
      $('#brand_image_wrap').modal('show');
    });

    $("input[name=pic]").change(function(){
      $(".upload_pic_wrap").submit();
    });

    $(".save").click(function(){
        var button = $(this);
        if (button.hasClass("disabled")){
            common_ops.alert("请勿重复提交");
            return;
        }


        if ($(".pic-each").size() < 1 ) {
            common_ops.alert("请上传图片");
            return;
        }

         button.addClass("disabled");

        $.ajax({
            url:common_ops.buildWebUrl("/brand/set-image"),
            type:'POST',
            data:{
                image_key : $(".del_image").attr("data")
            },
            dataType:'json',
            success:function( res ){
                button.removeClass('disabled');
                callback = null;
                if (res.code == 200){
                    callback = function(){
                        window.location.reload();
                    }
                }
                common_ops.alert(res.msg,callback);
            }
        })
    });

    $(".remove").click(function(){

        var id = $(this).attr("data");
        var callback = {
            'ok':function(){
                $.ajax({
                    url:common_ops.buildWebUrl("/brand/image-ops"),
                    type:'POST',
                    data:{
                      id:id
                    },
                    dataType:'json',
                    success:function(res){
                        var callback = null;
                        if ( res.code == 200 ){
                            callback = function(){
                                window.location.reload();
                            }
                        }
                        common_ops.alert(res.msg,callback);
                    }
                })
            },
            'cancel':null,
        };
        common_ops.confirm( "确定删除？",callback );
    })
})