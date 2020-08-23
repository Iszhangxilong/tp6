
function isIe()
{
	return ("ActiveXObject" in window);
}

function mTokenMgr(obj){
	this.obj = obj;	
	
	var g_mTokenPlugin = null;

	this.LoadLibrary = function()
	{
		//浏览器判断
	
		g_mTokenPlugin = new K1AdminPlugin();//新
		
		
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return 0;
	};

	this.K1Mgr_mTokenGetVersion = function()
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenGetVersion();
	};

	this.K1Mgr_mTokenFindDevice = function()
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenFindDevice();
	};

	this.K1Mgr_mTokenGetLastError = function()
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenGetLastError();
	};

	this.K1Mgr_mTokenGetUID = function(keyIndex)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenGetUID(keyIndex);
	};

	this.K1Mgr_mTokenOpen = function(keyUID, keyPassword)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenOpen(keyUID, keyPassword, 0);
	};

	this.K1Mgr_mTokenClose = function()
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenClose();
	};

	this.K1Mgr_mTokenChangePwd = function(keyUID,oldPassword, newPassword)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenChangePwd(keyUID, 0, oldPassword, newPassword);
	};

	this.K1Mgr_mTokenSetSeedKey = function(keyUID, seedKey)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenSetSeedKey(keyUID, seedKey);
	};

	this.K1Mgr_mTokenSetMainKey = function(keyUID, mainKey)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenSetMainKey(keyUID, mainKey);
	};
	
	this.K1Mgr_mTokenGenResetpwdResponse = function(mainKey, clientRequest, adminPwd, newUserPwd)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenGenResetpwdResponse(mainKey, clientRequest, adminPwd, newUserPwd);
	};

	this.K1Mgr_mTokenSetUserInfo = function(keyUID, openType, label, url, companyName, remarks, Tip)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenSetUserInfo(keyUID, openType, label, url, companyName, remarks, Tip);
	};
	
	this.K1Mgr_mTokenUnlockPwd = function(keyUID, adminPwd, userPwd)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenUnlockPwd(keyUID, adminPwd, userPwd);
	};
	
	this.K1Mgr_mTokenReadUserStorage = function(keyUID, offset, dataLength)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenReadUserStorage(keyUID, offset, dataLength);
	};

	this.K1Mgr_mTokenWriteUserStorage = function(keyUID, offset, writeData)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenWriteUserStorage(keyUID, offset, writeData);
	};

	
	this.K1Mgr_mTokenReadSecureStorageByAdmin = function(keyUID, offset, readLength)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenReadSecureStorageByAdmin(keyUID, offset, readLength);
	};
	
	this.K1Mgr_mTokenWriteSecureStorageByAdmin = function(keyUID, offset, writeData)
	{
		if(g_mTokenPlugin == null)
		{
			return -1;
		}
		
		return g_mTokenPlugin.mTokenWriteSecureStorageByAdmin(keyUID, offset, writeData);
	};

}




function K1AdminPlugin()
{
	
	var url = "http://127.0.0.1:51111/K1_Admin";
	
	var xmlhttp ;
	function AjaxIO(json) {
		if(xmlhttp == null) {
			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		if("https:" == document.location.protocol)
		{
			url = "https://127.0.0.1:51121/K1_Client";
		}
		xmlhttp.open("POST", url, false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("json=" + json);
	}

	this.mTokenGetVersion = function()
	{
		var json = '{"function":"mTokenGetVersion"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};

	this.mTokenFindDevice = function()
	{
		var json = '{"function":"mTokenFindDevice"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.devCount;
		}else{
			return -2;
		}
	};

	this.mTokenGetLastError = function()
	{
		var json = '{"function":"mTokenGetLastError"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.errorCode;
		}else{
			return -2;
		}
	};

	this.mTokenGetUID = function(keyIndex)
	{
		var json = '{"function":"mTokenGetUID", "keyIndex":' + keyIndex + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};

	this.mTokenOpen = function(keyUID, keyPassword, type)
	{
		var json = '{"function":"mTokenOpen", "keyUID":"' + keyUID + '", "passWd":"' + keyPassword + '", "passWdType":' + type + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

	this.mTokenClose = function()
	{
		var json = '{"function":"mTokenClose"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

	this.mTokenChangePwd = function(keyUID, type, oldPassword, newPassword)
	{
		var json = '{"function":"mTokenChangePwd", "keyUID":"' + keyUID + '", "oldUpin":"' + oldPassword + '", "newUpin":"' + newPassword + '", "passWdType":' + type + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

	this.mTokenSetSeedKey = function(keyUID, seedKey)
	{
		var json = '{"function":"mTokenSetSeedKey", "keyUID":"' + keyUID + '", "seedKey":"' + seedKey + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

	this.mTokenSetMainKey = function(keyUID, mainKey)
	{
		var json = '{"function":"mTokenSetMainKey", "keyUID":"' + keyUID + '", "mainKey":"' + mainKey + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};
	
	this.mTokenGenResetpwdResponse = function(mainKey, clientRequest, adminPwd, newUserPwd)
	{
		var json = '{"function":"mTokenGenResetpwdResponse", "mainKey":"' + mainKey + '", "request":"' + clientRequest + '", "SuperPin":"' + adminPwd + '", "newUpin":"' + newUserPwd + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};

	this.mTokenSetUserInfo = function(keyUID, openType, label, url, companyName, remarks, Tip)
	{
		var json = '{"function":"mTokenSetUserInfo", "keyUID":"' + keyUID + '", "openType":' + openType + ', "label":"' + label + '", "Url":"' + url + '", "companyName":"' + companyName + '", "remarks":"' + remarks + '", "Tip":"' + Tip + '"}'; 
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return -1;
		}
	};
	
	this.mTokenUnlockPwd = function(keyUID, adminPwd, userPwd)
	{
		var json = '{"function":"mTokenUnlockPwd", "keyUID":"' + keyUID + '", "UserPin":"' + userPwd + '", "SuperPin":"' + adminPwd + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return -1;
		}
	};
	
	this.mTokenReadSecureStorage = function(keyUID, offset, dataLength)
	{
		var json = '{"function":"mTokenReadSecureStorage", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inDataLen":' + dataLength + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};

	this.mTokenWriteSecureStorage = function(keyUID, offset, writeData)
	{
		var json = '{"function":"mTokenWriteSecureStorage", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inData":"' + writeData + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

	this.mTokenReadUserStorage = function(keyUID, offset, dataLength)
	{
		var json = '{"function":"mTokenReadUserStorage", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inDataLen":' + dataLength + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};

	this.mTokenWriteUserStorage = function(keyUID, offset, writeData)
	{
		var json = '{"function":"mTokenWriteUserStorage", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inData":"' + writeData + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};
	
	this.mTokenReadSecureStorageByAdmin = function(keyUID, offset, readLength)
	{
		var json = '{"function":"mTokenReadSecureStorageByAdmin", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inDataLen":' + readLength + '}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.outData;
		}else{
			return "";
		}
	};
	
	this.mTokenWriteSecureStorageByAdmin = function(keyUID, offset, writeData)
	{
		var json = '{"function":"mTokenWriteSecureStorageByAdmin", "keyUID":"' + keyUID + '", "offset":' + offset + ', "inData":"' + writeData + '"}';
		AjaxIO(json);
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var obj = eval("(" + xmlhttp.responseText + ")");
			return obj.rtn;
		}else{
			return 1;
		}
	};

}




