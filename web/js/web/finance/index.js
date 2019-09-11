$(document).ready( function(){

    var finance_index_ops = {
        init:function(){
            this.eventBind();
        },
        eventBind:function(){
            var that = this;
            $(".wrap_search select[name=status]").change( function(){
                $(".wrap_search").submit();
            });
        }
    };

    finance_index_ops.init();
});