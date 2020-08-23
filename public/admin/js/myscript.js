/**
 * Created by Administrator on 2017/2/5.
 */
$(function () {
    $('.header .nav li').on('click', function () {
        $(this).addClass('active').siblings().removeClass('active');
    });
    $(window).resize(function () {
        reheight();
    });
    reheight();
    function reheight() {
        $('.iframe-container').height($(window).height() - 87);
        $('.iframe-minheight').css('minHeight', $(window).height());
    }

    if($('.chosen-select').size()>0){
    $('select.chosen-select').chosen({
        no_results_text: '没有找到',    // 当检索时没有找到匹配项时显示的提示文本
        disable_search_threshold: 8, // 10 个以下的选择项则不显示检索框
        search_contains: true,         // 从任意位置开始检索
        width:'100%'
    });
    }

});
(function () {
    $('.js-check').on('click',function () {
        $(this).parent('tr').addClass('active')
    })
    //菜单操作
    var check = {
        init: function () {
            this.jscheck();
        },
        jscheck: function () {
            $('.js-check').on('change',function () {
                $(this).parents('tr').addClass('active')
            })
        }
    }
    return check.init();
}());
    

$(function () {  
        $(document).keydown(function (event) {  
            if (event.keyCode == 116) {  
                window.top.frames['main'].document.location.reload();
                return false;                     
            }  
        });  
}); 

