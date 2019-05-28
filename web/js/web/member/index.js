$(document).ready(function(){
    $(".remove").click(function(){

        var id = $(this).attr("data");

        var data = {
            id:id,
        }

        $.ajax({
            url:common_ops.buildWebUrl("/member/remove"),
            type:'POST',
            data:data,
            dataType:'json',
            success:function( res ){
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

    $(".recover").click(function(){

        var id = $(this).attr("data");

        var data = {
            id:id,
        }

        $.ajax({
            url:common_ops.buildWebUrl("/member/recover"),
            type:'POST',
            data:data,
            dataType:'json',
            success:function( res ){
                var callback = null;
                if (res.code == 200 ){
                    callback = function(){
                        window.location.reload();
                    }
                }
                common_ops.alert(res.msg,callback);
            }
        })

    })

    $(".search").click(function(){
        $(".wrap_search").submit();
    })


})