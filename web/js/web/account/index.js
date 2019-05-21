$(document).ready(function(){
    $(".search").click(function(){
        $(".wrap_search").submit();
    });

    $(".remove").click(function(){
        if(!confirm("确定删除吗？")){
          return ;
        };
        var uid = $(this).attr("data");
        ops("remove",uid);
    });

    $(".recover").click(function(){
        if(!confirm("确定恢复吗？")){
            return ;
        };
        var uid = $(this).attr("data");
        ops("recover",uid);
    });

    function ops(act,uid) {
        $.ajax({
            url:common_ops.buildWebUrl("/account/ops"),
            type:'POST',
            data:{
                act:act,
                uid:uid,
            },
            dataType:'json',
            success:function(res){
                alert(res.msg);
                window.location.reload();
            }
        });
    }
})