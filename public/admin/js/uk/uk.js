var K1mTokenMgr = new mTokenMgr("mTokenPlugin");
var g_keyUID = "";

function bangding(url,id){
	K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
		var kid=ukid();
        var url=url;
        var id=id;
        $.post(url,{ukid:kid,id:id},function(data){
        	if(data==1){
                new $.zui.Messager('该UK已经绑定其他管理员了', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('绑定成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#bid'+id).css('display','none');
		        $('#jid'+id).css('display','');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
	}
}


function jiebang(url,id){
    K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
        var kid=ukid();
        var url=url;
        var id=id;
        $.post(url,{ukid:kid,id:id},function(data){
        	if(data==1){
                new $.zui.Messager('该UK绑定的不是该管理员', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('解绑成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#bid'+id).css('display','');
		        $('#jid'+id).css('display','none');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
    
    }		
}


function ukid(){
	var keyUID = "";
	keyUID = K1mTokenMgr.K1Mgr_mTokenGetUID(1);
	if(keyUID == null || keyUID == "")
	{
			new $.zui.Messager('请先插入Uk', {
		            type: 'danger' // 定义颜色主题
		    }).show();	
		    return  false;
	}

	g_keyUID = keyUID;

	return keyUID;
}


//单篇的审核通过
function danshen(url,id){
	K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
		var kid=ukid();
        var url=url;
        var id=id;
        $.post(url,{ukid:kid,id:id},function(data){
        	if(data==1){
                new $.zui.Messager('该UK和管理员不匹配', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('操作成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#queshen').css('display','none');
		        $('#qushen').css('display','');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
	}
}


//单篇的取消审核
function danqushen(url,id){
    K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
        var kid=ukid();
        var url=url;
        var id=id;
        $.post(url,{ukid:kid,id:id},function(data){
        	if(data==1){
                new $.zui.Messager('该UK和管理员不匹配', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('操作成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#queshen').css('display','');
		        $('#qushen').css('display','none');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
    
    }		
}


//多篇的审核通过
function duoshen(url,id,cid){
	K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
		var kid=ukid();
        var url=url;
        var id=id;
        var cid=cid;
        $.post(url,{ukid:kid,id:id,cid:cid},function(data){
        	if(data==1){
                new $.zui.Messager('该UK和管理员不匹配', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('操作成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#bid'+id).css('display','none');
		        $('#jid'+id).css('display','');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
	}
}


//多篇的取消审核
function duoqushen(url,id,cid){
	K1mTokenMgr.LoadLibrary();
	var keyNumber = 0;
	keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
	//判断是否插入了uk
	if(keyNumber < 1)
	{ 
		new $.zui.Messager('请插入uk', {
            type: 'danger' // 定义颜色主题
        }).show();	
		return  false;
	}else{
		var kid=ukid();
        var url=url;
        var id=id;
        var cid=cid;
        $.post(url,{ukid:kid,id:id,cid:cid},function(data){
        	if(data==1){
                new $.zui.Messager('该UK和管理员不匹配', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}else if(data==2){
        		new $.zui.Messager('操作成功', {
		            type: 'success' // 定义颜色主题
		        }).show();	
		        $('#bid'+id).css('display','');
		        $('#jid'+id).css('display','none');
				return  false;
        	}else{
                new $.zui.Messager('操作失败', {
		            type: 'danger' // 定义颜色主题
		        }).show();	
				return  false;
        	}
        });

        return  false;
	}
}

