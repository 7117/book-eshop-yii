$(document).ready(function () {
    // upload = {
    //     error : function(msg){
    //         common_ops.alert( msg );
    //     },
    //     success : function (image_key) {
    //         var html =
    //             '<img src="'+common_ops.buildPicUrl('brand',image_key)+'">' +
    //             '<span class="fa fa-times-circle del del_image" data="'+image_key+'">' +
    //                  '<i></i>' +
    //             '</span>';
    //
    //         if ($(".upload_pic_wrap .pic-each").size() > 0 ){
    //             $(".upload_pic_wrap .pic-each").html(html);
    //         } else {
    //             $(".upload_pic_wrap ").append( '<span class="pic-each">'+ html + '</span>');
    //
    //         }
    //     }
    // }

    $(".save").click(function () {

        var button = $(this);

        if (button.hasClass("disabled")) {
            common_ops.alert("请勿重复点击");
            return ;
        }

        var name = $('input[name="name"]').val();
        var mobile =  $('input[name="mobile"]').val();
        var address =  $('input[name="address"]').val();
        var description =  $('textarea[name="description"]').val();
        var image_key =  $('.del_image').attr("data");

        if (name.length < 1 ){
            common_ops.tip("名字错误",$('input[name="name"]'));
            return;
        }

        if (mobile.length < 1 ){
            common_ops.tip("号码错误",$('input[name="mobile"]'));
            return;
        }

        if (address.length < 1 ){
            common_ops.tip("地址错误",$('input[name="address"]'));
            return;
        }

        if (description.length < 1 ){
            common_ops.tip("描述错误",$('textarea[name="description"]'));
            return;
        }

        if (image_key.length < 1 ){
            common_ops.alert("请上传LOGO");
            return;
        }

        button.addClass("disabled");

        var data = {
          name:name,
          image_key:image_key,
          mobile:mobile,
          address:address,
          description:description
        }

        $.ajax({
            url:common_ops.buildWebUrl("/brand/set/"),
            type:'POST',
            data:data,
            dataType:'json',
            success:function(res){
                button.removeClass("disabled");
                callback = null;
                if (res.code == 200 ){
                    callback = function(){
                        window.location.reload();
                    }
                }
                common_ops.alert(res.msg,callback);
            }
        })

    })

    // 当输入域发生变化时改变其颜色：
    $(".upload_pic_wrap input[name=pic]").change(function(){
        $(".upload_pic_wrap").submit();
    })

    $(".del_image").unbind().click( function(){
        $(this).parent().remove()
    } )
});