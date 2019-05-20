$(document).ready(function()
{
    $(".save").click(function() {

        var button = $(this);

        if (button.hasClass("disabled")) {
            alert("请勿重复点击");
            return false;
        }

        button.addClass("disabled");

        var nickname = $('.user_edit_wrap [name="nickname"]').val();
        var email = $('.user_edit_wrap [name="email"]').val();

        if(nickname.length < 1){
            alert("请输入合法姓名");
            return false;
        }

        if(email.length < 1){
            alert("请输入合法邮箱");
            return false;
        }


        $.ajax({
            url:'/web/user/edit',
            type:'POST',
            data:{
                nickname:nickname,
                email:email,
            },
            dataType:'json',
            success:function (res) {
                button.removeClass("disabled");
                if(res.code == 200){
                    alert(res.msg);
                }
            },
        });
    });
});