$(document).ready(function(){
    $(".save").click(function(){
        var button = $(this);

        if (button.hasClass("disabled")){
            common_ops.alert("请勿重复提交");
            return;
        }
        var id = $('input[name="id"]').val();
        var nickname = $('input[name="nickname"]').val();
        var mobile = $('input[name="mobile"]').val();
        var email = $('input[name="email"]').val();
        var login_name = $('input[name="login_name"]').val();
        var login_pwd = $('input[name="login_pwd"]').val();

        if (nickname.length < 1 ){
            common_ops.tip("姓名不对",$('input[name="nickname"]'));
            return;
        }

        if (mobile.length < 1 ){
            common_ops.tip("手机不对",$('input[name="mobile"]'));
            return;
        }

        if (email.length < 1 ){
            common_ops.tip("邮箱不对",$('input[name="email"]'));
            return;
        }

        if (login_name.length < 1 ){
            common_ops.tip("登录名不对",$('input[name="login_name"]'));
            return;
        }

        if (login_pwd.length < 1 ){
            common_ops.tip("密码不对",$('input[name="login_pwd"]'));
            return;
        }

        button.addClass("disabled");

        $.ajax({
            url:common_ops.buildWebUrl("/account/set"),
            type:'POST',
            data:{
                id:id,
                nickname:nickname,
                mobile:mobile,
                email:email,
                login_name:login_name,
                login_pwd:login_pwd
            },
            dataType:'json',
            success:function(res){
                callback = null;
                if (res.code == 200 ){
                    callback = function () {
                        window.location.reload();
                    }
                    common_ops.alert(res.msg,callback);
                }else{
                    callback = function(){
                        window.location.reload();
                    }
                    common_ops.alert("填写错误",callback);
                }
            }
        });

    })
});