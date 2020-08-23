$(function () {
    //时间实例化
    $(".form-date").datetimepicker(
        {
            language:  "zh-CN",
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format: "yyyy-mm-dd"
        });
});
function  add(){
    var pnames=document.getElementById('pnames');
    if (document.addma.title.value.match(/^\s*$/)){
        pnames.setAttribute('class','row has-warning');
        document.addma.title.focus();
        layer.msg('标题不能为空',{time:3000,icon:2,offset:"400px"});
        return false;
    }
}