//================上传文件JS函数开始，需和jquery.form.js一起使用===============
//单个文件上传
function SingleUpload(repath, uppath, action,url) {
    var submitUrl=""+url+"?ReFilePath="+repath+"&UpFilePath="+uppath+"&action="+action;
    //开始提交
    $("#form1").ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options){
            //隐藏上传按钮
            //$("#"+repath).parent().nextAll(".col").eq(0).hide();
            //显示LOADING图片
            //$("#"+repath).parent().nextAll(".col").eq(1).show();
        },
        success: function(data, textStatus) {
            if (data.msg == 1) {
                $("#"+repath).val(data.msbox);
                //更改img标签的 src值
                $("#"+repath).next().attr("src",ROOT+"/uploadfiles/core/"+data.msbox);
                // $.post(CONT+"/ajaxcore",{logo:data.msbox},function(){});
            } else {
                alert(data.msbox);
            }
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        error: function(data, status, e) {
            alert("上传失败，错误信息：" + e);
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        url: submitUrl,
        type: "post",
        dataType: "json",
        timeout: 600000
    });
};


//系统头像上传
function SingleUpload_logo(repath, uppath, action,url) {
    var submitUrl=""+url+"?ReFilePath="+repath+"&UpFilePath="+uppath+"&action="+action;
    //开始提交
    $("#form1").ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options){
            //隐藏上传按钮
            //$("#"+repath).parent().nextAll(".col").eq(0).hide();
            //显示LOADING图片
            //$("#"+repath).parent().nextAll(".col").eq(1).show();
        },
        success: function(data, textStatus) {
            if (data.msg == 1) {
                $("#"+repath).val(data.msbox);
                //更改img标签的 src值
                $("#"+repath).next().attr("src",ROOT+"/uploadfiles/syslogo/"+data.msbox);
                // $.post(CONT+"/ajaxcore",{logo:data.msbox},function(){});
            } else {
                alert(data.msbox);
            }
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        error: function(data, status, e) {
            alert("上传失败，错误信息：" + e);
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        url: submitUrl,
        type: "post",
        dataType: "json",
        timeout: 600000
    });
};

//报名选手上传图片
function SingleUploadsi(repath, uppath, action,url) {
    var submitUrl=""+url+"?ReFilePath="+repath+"&UpFilePath="+uppath+"&action="+action;
    //开始提交
    $("#form1").ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options){
            //隐藏上传按钮
            //$("#"+repath).parent().nextAll(".col").eq(0).hide();
            //显示LOADING图片
            //$("#"+repath).parent().nextAll(".col").eq(1).show();
        },
        success: function(data, textStatus) {
            if (data.msg == 1) {
                $("#"+repath).val(data.msbox);
                //更改img标签的 src值
                $("#"+uppath).next().attr("src",ROOT+"/uploadfiles/signup/"+data.msbox);
                // $.post(CONT+"/ajaxcore",{logo:data.msbox},function(){});
            } else {
                alert(data.msbox);
            }
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        error: function(data, status, e) {
            alert("上传失败，错误信息：" + e);
            //$("#"+repath).parent().nextAll(".col").eq(0).show();
            //$("#"+repath).parent().nextAll(".col").eq(1).hide();
        },
        url: submitUrl,
        type: "post",
        dataType: "json",
        timeout: 600000
    });
};