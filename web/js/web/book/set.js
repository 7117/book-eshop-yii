$(document).ready(function(){

    upload = {
        error:function(msg){
            $.alert(msg);
        },
        success:function(file_key,type){
            if(!file_key){
                return;
            }
            var html = '<img src="'+common_ops.buildPicUrl("book",file_key)+'"/>'
                +'<span class="fa fa-times-circle del del_image" data="'+file_key+'"></span>';

            if( $(".upload_pic_wrap .pic-each").size() > 0 ){
                $(".upload_pic_wrap .pic-each").html( html );
            }else{
                $(".upload_pic_wrap").append('<span class="pic-each">'+ html + '</span>');
            }
            $(".wrap_book_set .del_image").unbind().click(function(){
                $(this).parent().remove();
            });

        }
    };

    var ue = UE.getEditor('editor',{
        toolbars: [
            [ 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall',  '|','rowspacingtop', 'rowspacingbottom', 'lineheight'],
            [ 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink'],
            [ 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'horizontal', 'spechars','|','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols' ]

        ],
        enableAutoSave:true,
        saveInterval:60000,
        elementPathEnabled:false,
        zIndex:4
    });

    $("input[name=pic]").change(function(){
        $(".upload_pic_wrap").submit();
    });

    $("input[name=tags]").tagsInput({
        width:'auto',
        height:40,
        onAddTag:function(tag){
        },
        onRemoveTag:function(tag){
        }
    });

    $("select[name=cat_id]").select2({
        language: "zh-CN",
        width:'100%'
    });

    $(".save").click( function(){
        var button = $(this);

        if( button.hasClass("disabled") ){
            common_ops.alert("请不要重复提交");
            return;
        }

        var cat_id_target = $("select[name=cat_id]");
        var cat_id = cat_id_target.val();

        var name_target = $("input[name=name]");
        var name = name_target.val();

        var price_target = $("input[name=price]");
        var price = price_target.val();

        var summary  = $.trim( ue.getContent() );

        var stock_target = $("input[name=stock]");
        var stock = stock_target.val();

        var tags_target = $("input[name=tags]");
        var tags = $.trim( tags_target.val() );

        if( parseInt( cat_id ) < 1 ){
            common_ops.tip( "请输入图书分类" ,cat_id_target );
            return;
        }

        if( name.length < 1 ){
            common_ops.alert( "请输入符合规范的图书名称" );
            return;
        }

        if( parseFloat( price ) <= 0 ){
            common_ops.tip( "请输入符合规范的图书售卖价格" ,price_target );
            return;
        }

        if( $(".wrap_book_set .pic-each").size() < 1 ){
            common_ops.alert( "请上传封面图"  );
            return;
        }

        if( summary.length < 10 ){
            common_ops.tip( "请输入图书描述，并不能少于10个字符" ,price_target );
            return;
        }

        if( parseInt( stock ) < 1 ){
            common_ops.tip( "请输入符合规范的库存量" ,stock_target );
            return;
        }

        if( tags.length < 1 ){
            common_ops.alert( "请输入图书标签，便于搜索" );
            return;
        }

        button.addClass("disabled");

        var data = {
            cat_id:cat_id,
            name:name,
            price:price,
            summary:summary,
            stock:stock,
            tags:tags,
            main_image:$(".del_image").attr("data"),
            id:$("input[name=id]").val()
        };

        $.ajax({
            url:common_ops.buildWebUrl("/book/set") ,
            type:'POST',
            data:data,
            dataType:'json',
            success:function(res){
                button.removeClass("disabled");
                var callback = null;
                if( res.code == 200 ){
                    callback = function(){
                        window.location.href = common_ops.buildWebUrl("/book/index");
                    }
                }
                common_ops.alert( res.msg,callback );
            }
        });
    });
})