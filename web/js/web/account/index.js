$(document).ready(function(){
    $(".search").click(function(){
        $(".wrap_search").submit();
    });

    $(".remove").click(function(){
        //通过获取属性值  attr
        var uid = $(this).attr("data");
        ops("remove",uid);
    });

    $(".recover").click(function(){
        var uid = $(this).attr("data");
        ops("recover",uid);
    });

    function ops(act,uid) {
        callback = {
          "ok":function(){
              $.ajax({
                  url:common_ops.buildWebUrl("/account/ops"),
                  type:'POST',
                  data:{
                      act:act,
                      uid:uid,
                  },
                  dataType:'json',
                  success:function(res){
                      allback = null;
                      if(res.code == 200) {
                          callback =function () {
                              window.location.reload();
                          }
                      }
                      common_ops.alert(res.msg,callback);
                  }
              });
          },
          "cancel":function(){

          }
        };
        //记住callback是一个回调函数
        //回调函数是作为一个参数在函数中
        //然后在函数内部让他运行
        common_ops.confirm((act == "remove")?"确定删除吗？":"确定恢复吗？",callback);
    }
})