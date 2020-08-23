//搜索
function  search(){
	if (document.sform.kwords.value.match(/^\s*$/)){
                alert ("输入您想要了解的内容");
                document.sform.kwords.focus();
                return false;
             }    
}

//排序选择
function sort(xv,id,url){
	 $.post(url,{id:id,isort:xv},function(data){
             if(data==1){
                 layer.msg('已修改',{time:2000,icon:1
                 ,end:function () {
                         location.href=location.href;
                     }
                 });

             }else{
                 layer.msg('未修改',{time:3000,icon:2});
             }
        });
}


//转移数据
function movedata(id,url){
   var mod='';
   $("input:checkbox[name='del[]']:checked").each(function() { // 遍历name=del的多选框

      mod+=$(this).val()+',';  // 每一个被选中项的值
    
   });

   var pid=$('#gsbm').val(); //获取要移动栏目id
    
   if(mod=='' || mod==null){
        new $.zui.Messager('请选择要操作的数据', {
                       type: 'danger' // 定义颜色主题
        }).show();  

        return false;
   }else if(id==pid){
        //数据不能移动到当前栏目
        new $.zui.Messager('请选择非当前所属栏目', {
                       type: 'danger' // 定义颜色主题
        }).show();  
        return false;
   }else{
        $.post(url,{pid:pid,del:mod},function(data){
             if(data==1){
                 new $.zui.Messager('移动成功', {
                        type: 'success' // 定义颜色主题
                 }).show();
                 location.href=location.href;
             }else{
                new $.zui.Messager('移动失败', {
                       type: 'danger' // 定义颜色主题
                }).show(); 
             }
        });
   } 


}


//复制数据
function copydata(id,url){
   var mod='';
   $("input:checkbox[name='del[]']:checked").each(function() { // 遍历name=del的多选框

      mod+=$(this).val()+',';  // 每一个被选中项的值
    
   });

   var pid=$('#gsbm').val(); //获取要移动栏目id
    
   if(mod=='' || mod==null){
        new $.zui.Messager('请选择要操作的数据', {
                       type: 'danger' // 定义颜色主题
        }).show();  

        return false;
   }else if(id==pid){
        //数据不能移动到当前栏目
        new $.zui.Messager('请选择非当前所属栏目', {
                       type: 'danger' // 定义颜色主题
        }).show();  
        return false;
   }else{
        $.post(url,{pid:pid,del:mod},function(data){
             if(data==1){
                 new $.zui.Messager('复制成功', {
                        type: 'success' // 定义颜色主题
                 }).show();
                 location.href=location.href;
             }else{
                new $.zui.Messager('复制失败', {
                       type: 'danger' // 定义颜色主题
                }).show(); 
             }
        });
   } 
}