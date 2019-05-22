$(document).ready(function () {
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

        button.addClass("disabled");

        var data = {
          name:name,
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
})