$(document).ready(function(){
    $("#save").click(function(){

        var button = $(this);

        if(button.hasClass("disabled")){
            common_ops.alert("请勿重复点击");
            return false;
        }
        button.addClass("disabled");

        var old_password = $("#old_password").val();
        var new_password = $("#new_password").val();

        if (old_password < 1) {
           common_ops.tip("请输入原密码",$("#old_password"));
           return false;
        }

        if(new_password < 1) {
            common_ops.tip("请输入新密码",$("#new_password"));
            return false;
        }

        $.ajax({
            url:common_ops.buildWebUrl('/user/reset-pwd'),
            type:'POST',
            data:{
                old_password:old_password,
                new_password:new_password
            },
            dataType:'json',
            success:function(res){
                callback = null;
                if (res.code == 200) {
                    callback = function(){
                        window.location.reload();
                    }
                }
                common_ops.alert(res.msg,callback);
                button.removeClass("disabled");
            }
        })

    })
})