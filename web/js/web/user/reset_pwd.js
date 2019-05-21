$(document).ready(function(){
    $("#save").click(function(){

        var button = $(this);

        if(button.hasClass("disabled")){
            alert("请勿重复点击");
            return false;
        }
        button.addClass("disabled");

        var old_password = $("#old_password").val();
        var new_password = $("#new_password").val();

        if (old_password < 1) {
           alert("请输入原密码");
           return false;
        }

        if(new_password < 1) {
            alert("请输入新密码");
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
                alert(res.msg);
                button.removeClass("disabled");
            }
        })

    })
})