$(document).ready(function(){
    $(".search").click(function(){
        $(".wrap_search").submit();
    });

    $(".remove").click(function(){
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
                      callback = null;
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
        common_ops.confirm((act == "remove")?"确定删除吗？":"确定恢复吗？",callback);
    }
})