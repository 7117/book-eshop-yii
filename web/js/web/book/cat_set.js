$(document).ready(function(){

    $(".save").click(function(){
        button = $(this);

        if (button.hasClass("disabled")) {
            common_ops.alert("请勿重复点击");
            return ;
        }

        var id = $("input[name=id]").val();
        var name = $("input[name=name]").val();
        var weight = $("input[name=weight]").val();

        if (name.length < 1 ){
            common_ops.tip("名字不对",$("input[name=name]"));
        }

        button.addClass("disabled");

        var data = {
            id:id,
            name:name,
            weight:weight
        }

        $.ajax({
            url:common_ops.buildWebUrl("/book/cat_set"),
            data:data,
            type:"POST",
            dataType:"json",
            success:function (res) {
                button.removeClass("disabled");
                callback = null;
                if (res.code == 200 ){
                    callback = function(){
                        window.location.href = common_ops.buildWebUrl("/book/cat");
                    }
                }
                common_ops.alert(res.msg,callback);
            }
        })

    })

})