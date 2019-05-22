$(document).ready(function()
{
    $(".save").click(function() {

        var button = $(this);

        if (button.hasClass("disabled")) {
            common_ops.alert("请勿重复点击");
            return false;
        }

        button.addClass("disabled");

        var nickname = $('.user_edit_wrap [name="nickname"]').val();
        var email = $('.user_edit_wrap [name="email"]').val();

        if(nickname.length < 1){
            common_ops.tip("请输入合法姓名",$('.user_edit_wrap [name="nickname"]'));
            return false;
        }

        if(email.length < 1){
            common_ops.tip("请输入合法邮箱",$('.user_edit_wrap [name="email"]'));
            return false;
        }

        $.ajax({
            url:common_ops.buildWebUrl('/user/edit'),
            type:'POST',
            data:{
                nickname:nickname,
                email:email,
            },
            dataType:'json',
            success:function (res) {
                callback = null;
                button.removeClass("disabled");
                if(res.code == 200){
                    callback = function () {
                        window.location.reload();
                    }
                }
                common_ops.alert(res.msg,callback);
            },
        });
    });
});