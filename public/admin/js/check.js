// JavaScript Document
function check(mo_captcha_reg){
	var in1=document.getElementById('in1');
	var in2=document.getElementById('in2');

	if (document.loginform.username.value.match(/^\s*$/)){
        in1.setAttribute('class','row has-warning');  
		document.loginform.username.focus();
		return false;
	}else{
		in1.setAttribute('class','row');
	}
	
	
	if (document.loginform.password.value.match(/^\s*$/)){
		in2.setAttribute('class','row has-warning');
		document.loginform.password.focus();
		return false;
	}else{
		in2.setAttribute('class','row');
	}


	if(mo_captcha_reg == 1){
		var in3=document.getElementById('in3');
		if (document.loginform.vdcode.value.match(/^\s*$/)){
			in3.setAttribute('class','row login-yzmrow has-warning');
			document.loginform.vdcode.focus();
			return false;
		}else{
			in3.setAttribute('class','row login-yzmrow');
		}
	}
	 return true;
	
}

function  loginajax(url,uyl,mo_captcha_reg){

   var in1=document.getElementById('in1');
   var in2=document.getElementById('in2');
	if(mo_captcha_reg == 1){
		var in3=document.getElementById('in3');
	}

   if(check(mo_captcha_reg)==false){
        return false;
   }else{
       var username=document.loginform.username.value;
       var password=document.loginform.password.value;

	   if(mo_captcha_reg == 1){
		   var vdcode=document.loginform.vdcode.value;
		   var sendata = {username:username,password:password,vdcode:vdcode,checks:checks};
	   }else{
		   var sendata = {username:username,password:password,checks:checks};

	   }
       if(document.loginform.checks.checked){
          var checks=document.loginform.checks.value;
       }
   	   $.post(url,sendata,function(data){
   	   	    if(data.msg==1){
				code1(this,'/spzgadmin.php/captcha.html');
				layer.msg(data.msbox,{time:2000,icon:2});
               in3.setAttribute('class','row login-yzmrow has-warning');
   	   	    }else if(data.msg==2){
   	   	    	code1(this,'/spzgadmin.php/captcha.html');
				layer.msg(data.msbox,{time:2000,icon:2});
               in1.setAttribute('class','row has-warning');
               in2.setAttribute('class','row has-warning');
   	   	    }else if(data.msg==3){
				code1(this,'/spzgadmin.php/captcha.html');
				layer.msg(data.msbox,{time:2000,icon:2});
               in1.setAttribute('class','row has-warning');
               in2.setAttribute('class','row has-warning');
               in3.setAttribute('class','row login-yzmrow has-warning');
   	   	    }else if(data.msg==4){
               location.href=uyl;
   	   	    }else if(data.msg==5){
				layer.msg(data.msbox,{time:2000,icon:2});
   	   	    }else if(data.msg==6){
				layer.msg(data.msbox,{time:2000,icon:2});
			}else if(data.msg==7){
   	   	    	layer.msg(data.msbox,{time:2000,icon:2});
			}else if(data.msg==8){
				layer.msg(data.msbox,{time:2000,icon:2});
			}else if(data.msg==9){
				layer.msg(data.msbox,{time:2000,icon:2});

			}
   	   });
   }
   
   return false;
}




