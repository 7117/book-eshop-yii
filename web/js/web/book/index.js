$(document).ready(function(){
    $(".search").click(function(){
        $(".wrap_search").submit();
    });

    $(".remove").click(function(){
        var id = $(this).attr("data");

        var callback = {
            'ok':function(){
                $.ajax({
                    url:common_ops.buildWebUrl("/book/book_remove"),
                    type:"POST",
                    data:{
                        id:id,
                    },
                    dataType:"json",
                    success:function(res){
                        var callback = null;
                        if (res.code == 200 ){
                            window.location.reload();
                        }
                        common_ops.alert(res.msg,callback);
                    }
                })
            },
            'cancel':null,
        }

        common_ops.confirm("确定删除吗",callback);
    })

    $(".recover").click(function(){
        var id = $(this).attr("data");

        var callback = {
            'ok':function(){
                $.ajax({
                    url:common_ops.buildWebUrl("/book/book_recover"),
                    type:"POST",
                    dataType:'json',
                    data:{
                        id:id,
                    },
                    success:function (res) {
                        var callback = null;
                        if (res.code == 200 ){
                            callback = function () {
                                window.location.reload();
                            }
                        }
                        common_ops.alert(res.msg,callback);
                    }

                })
            },
            'cancel':null,
        };

        common_ops.confirm("确定恢复吗",callback);
    })

})