/**|*****************************************************|
***|**********Ajax删除 这里的删除操作都需要加入权限 *****| 
***|*****************************************************/
//这里是公用的js 单选多选
$('.js-check').on('click', function () {
        var off = $(this).prop('checked');
        if (off) {
            $(this).parents('tr').addClass('active');
        } else {
            $(this).parents('tr').removeClass('active');
        }
})
$('.js-all').on('click', function () {
        var off = $(this).prop('checked');

        if (off) {
            $('.js-check').prop('checked', true);
            $('.js-check').parents('tr').addClass('active');
        } else {
            $('.js-check').prop('checked', false);
            $('.js-check').parents('tr').removeClass('active');
        }
})

//公用ajax单体删除  模板页面需定义url路径
$('.del').click(function () {

    var _this = $(this);
    var id = _this.attr('wow');
    var delurl = _this.attr('delurl');
    layer.confirm('确定删除该条信息吗？', {
        btn: ['删除','取消'] ,
        skin: 'layui-layer-lan'   //主题皮肤 可选 参考官网
    }, function(index){
        layer.close(index);
        $.post(delurl, { id: id }, function (data) {

            if (data == 1) {

                $('.del').each(function () {
                    var vel = $(this).attr('wow');
                    vel = parseInt(vel);
                    if (vel == id) {
                        $(this).parent().parent().remove();
                        layer.msg('已删除',{time:3000,icon:1});
                    }

                    // if ($('.del').length < 7) {
                    //     location.href = location.href;
                    // }
                });

            }else{
                layer.msg('未删除',{time:3000,icon:2});
            }
        });

    }, function(){
        // layer.msg('也可以这样', {
        //   time: 20000, //20s后自动关闭
        //   btn: ['明白了', '知道了']
        // });
    });

});

//公用的多选删除  需定义form的class为delform  
$('#alldel').click(function(){
    var str = '';
    var _this = $(this);
    var url = _this.attr('wow');
    $("input[name='del[]']:checked").each(function(){
        str=str+','+$(this).val();
    });
    delid=str.replace(/^,*/g,"");
    if(delid==''||delid==null){
        layer.msg('请勾选删除项',{time:3000,icon:2});
    }else{
        layer.confirm('删除后将无法恢复，请谨慎操作', {
        btn: ['删除','取消'] ,
        skin: 'layui-layer-lan'   //主题皮肤 可选 参考官网
        },function(index){   //确定回掉
            layer.close(index);
            $('.delform').attr('action',url);
            $('.delform').submit();
        },function(index){
      　　　layer.close(index);
        });
    }
});




//特殊或者公用不了的删除操作
//请在下面单独去添加

