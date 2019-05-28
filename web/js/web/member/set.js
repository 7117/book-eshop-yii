$(document).ready(function(){
    $(".save").click(function(){
        var button = $(this);

        if (button.hasClass("disabled")){
            common_ops.alert("请勿重复点击");
            return;
        }

        var id = $("input[name=id]").val();
        var nickname = $("input[name=nickname]").val();
        var mobile = $("input[name=mobile]").val();

        if (nickname.length <1 ) {
            common_ops.tip("姓名不对",$("input[name=nickname]"));
            return;
        }

        if (mobile.length < 1 ) {
            common_ops.tip("号码不对",$("input[name=mobile]"));
        }

        var data ={
            id:id,
            nickname:nickname,
            mobile:mobile
        }

        $.ajax({
            url:common_ops.buildWebUrl("/member/set"),
            type:"POST",
            data:data,
            dataType:'json',
            success:function(res){
                button.removeClass("disables");
                callback = null;
                if (res.code == 200 ){
                    callback = function(){
                        window.location.href = common_ops.buildWebUrl("/member/index")
                    }
                }

                common_ops.alert(res.msg,callback);
            }
        })

    })



})