/**********************************************************
 *
 * 使用此JS脚本之前请先仔细阅读K1帮助文档!
 * 
 * @author		Longmai
 * @version		1.0
 * @date		2014/12/24
 * @explanation	操作系统支持: Windows, Linux, Mac OS
 *				浏览器支持: IE, Firefox, Chrome, Safari, 以及目前市面上使用这些浏览器核心的其它浏览器;
 *              如猎豹，360，QQ, 遨游等等。
 *
**********************************************************/

	var K1mTokenMgr = new mTokenMgr("mTokenPlugin");
	var g_keyUID = "";
	/*******************************************************
	*
	* 函数名称：K1mTokenMgrFindDevice
	* 功    能：查找K1 mToken设备
	* 输    入：
	* 返 回 值：查找到设备个数，返回值大于1成功，否则失败可调用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：此方法是使用K1第一个调用的函数，返回值是查找到设备个数。
	*
	**********************************************************/	
	function K1mTokenMgrFindDevice()
	{
		K1mTokenMgr.LoadLibrary();
		var keyNumber = 0;
		keyNumber =  K1mTokenMgr.K1Mgr_mTokenFindDevice();
		if(keyNumber < 1)
		{
			alert("查找K1失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			document.getElementById("txt_FindDevice").value = "";	
			return ;
		}

		document.getElementById("txt_FindDevice").value = keyNumber;	
	};
	

	/*******************************************************
	*
	* 函数名称：K1mTokenMgrGetVersion
	* 功    能：获取插件版本号
	* 输    入：
	* 返 回 值：版本号
	* 说	明：若版本有升级，可通过此版本号获取当前插件版本区别
	*
	**********************************************************/
	function K1mTokenMgrGetVersion()
	{
		var version = 0;
		version =  K1mTokenMgr.K1Mgr_mTokenGetVersion();
		if(version != null || version != "")
		{
			alert("获取版本失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return ;
		}

		return version;
	};
	
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenGetLastError
	* 功    能：获取最后一次调用接口错误码
	* 输    入：
	* 返 回 值：错误码
	* 说	明：当调用接口返回值错误，可用此函数获取错误码，通过错误码可分析出错原因
	*
	**********************************************************/
	function K1mTokenMgrGetLastError()
	{
		var errorCode = 0;
		errorCode =  K1mTokenMgr.K1Mgr_GetLastError();
		
		return errorCode;
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenGetUID(int keyIndex)
	* 功    能：查找K1 mToken设备
	* 输    入：keyIndex 从1开始，小于等于查找到设备的个数
	* 返 回 值：0 成功，非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：获取设备唯一硬件ID
	*
	**********************************************************/	
	function K1mTokenMgrGetUID()
	{
		var keyUID = "";
		keyUID = K1mTokenMgr.K1Mgr_mTokenGetUID(1);
		if(keyUID == null || keyUID == "")
		{
			alert("获取设备唯一硬件ID失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return ;
		}

		g_keyUID = keyUID;	
		document.getElementById("txt_keyUID").value = keyUID;
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenOpen(string keyUID, string keyPassword)
	* 功    能：打开K1 mToken设备管理权限
	* 输    入：keyUID，设备唯一硬件ID
	*           keyPassword mToken用户密码   
	* 返 回 值：0 成功，非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：根据唯一硬件ID获取设备管理权限
	*
	**********************************************************/
	function K1mTokenMgrOpen()
	{
		var keyPassword = document.getElementById("txt_AdminPwd").value;			
		var nRet = K1mTokenMgr.K1Mgr_mTokenOpen(g_keyUID, keyPassword);
		if(nRet != 0)
		{
			alert("验证用户密码权限失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
		}		
	};
		
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenClose
	* 功    能：关闭设备
	* 输    入：
	* 返 回 值：0 成功，非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：关闭当前打开的设备，与K1Mgr_mTokenOpen成对使用
	*
	**********************************************************/
	function K1mTokenMgrClose()
	{
		return K1mTokenMgr.mTokenClose();
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenChangePwd(string keyUID, string oldPassword, string newPassword)
	* 功    能：修改密码
	* 输    入：keyUID,设备唯一硬件ID
	*			oldPassword,原密码
	*			newPassword,新密码
	* 返 回 值：0 成功，非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：修改管理密码
	*
	**********************************************************/
	function K1mTokenMgrChangePwd()
	{
		var oldPassword = document.getElementById("txt_oldPwd").value;	
		var newPassword = document.getElementById("txt_newPwd").value;	
	
		var nRet = K1mTokenMgr.K1Mgr_mTokenChangePwd(g_keyUID, oldPassword, newPassword);
		if(nRet != 0)
		{
			alert("修改用户密码失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
		}
	};
	
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenReadSecureStorage（string keyUID, int offset, int readLength)
	* 功    能：获取设备安全存储区数据
	* 输    入：keyUID，设备唯一硬件ID
	*           offset, 读取安全存储区数据开始地址
	*           readLength，需要读取数据的长度
	* 返 回 值：非空值成功，正确安全存储区数据，此数据为密文（经过BASE64编码）
	*           空值失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：获取设备安全存储区设备，获取的数据是密文且经过BASE64编码
	*
	**********************************************************/
	function K1MgrmTokenReadSecureStorageByAdmin()
	{
		var readSecureData = "";
		var offset = 0;
		var dataLength = 128;
		readSecureData = K1mTokenMgr.K1Mgr_mTokenReadSecureStorageByAdmin(g_keyUID, offset, dataLength);
		if(readSecureData == null || readSecureData == "")
		{
			alert("获取安全存储区数据失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
		document.getElementById("txt_readSafeData").value = readSecureData;	
		return 
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenWriteSecureStorage（string keyUID, int offset, string writeData)
	* 功    能：获取设备安全存储区数据
	* 输    入：keyUID,设备唯一硬件ID
	*           offset，安全存储区写开始地址
	*           writeData,此数据必须是密文，加密数据的秘钥必须与锁内一致才能正确写入到设备存储区（BASE64编码）
	* 返 回 值：0 成功，数据成功写入设备
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：写入到设备的数据必须为密文且经过BASE64编码，加密数据的秘钥必须与设备内秘钥一致。
	*
	**********************************************************/
	function K1MgrmTokenWriteSecureStorageByAdmin()
	{
		var getTxtData = document.getElementById("txt_writeSafeData").value;
	
		var nRet = K1mTokenMgr.K1Mgr_mTokenWriteSecureStorageByAdmin(g_keyUID, 0, getTxtData);
		if(nRet != 0)
		{
			alert("写安全存储区数据失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
		}
		
	};
	
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenReadUserStorage（string keyUID, int offset, int DataLength)
	* 功    能：获取设备用户存储区数据
	* 输    入：keyUID,设备唯一硬件ID
	*           offset，读取数据开始地址
	*           DataLength,需要读取的数据长度     
	* 返 回 值：非空值成功，正确获取用户存储区数据（BASE64编码）
	*           空置失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：获取到用户存储区的数据经过BASE64编码，查看正确的用户存储区设备数据请还原BASE64编码
	*
	**********************************************************/
	function K1mTokenMgrReadUserStorage()
	{
		var userData = "";
		var offset = 0;
		var dataLength = 128;
		userData = K1mTokenMgr.K1Mgr_mTokenReadUserStorage(g_keyUID, offset, dataLength);
		if(userData == null || userData == "")
		{
			document.getElementById("txt_readUserData").value = "";	
			alert("读取用户存储区数据失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
		document.getElementById("txt_readUserData").value = userData;	
		
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenWriteUserStorage（string keyUID, int offset, string writeData)
	* 功    能：获取设备用户存储区数据
	* 输    入：keyUID,设备唯一硬件ID
	*           offset,写用户存储区开始地址
	*           writeData,写入到用户存储区的数据（BASE64编码）
	* 返 回 值：0 成功，正确把数据存储到设备用户存储区
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：写入到设备用户存储区的设备需把原数据经过BASE64处理
	*
	**********************************************************/	
	function K1mTokenMgrWriteUserStorage()
	{
		var WriteBuffer = document.getElementById("txt_writeUserData").value
		var Base64WriteData = _Base64encode(WriteBuffer);
	
		var offset = 0;
		
		var nRet = K1mTokenMgr.K1Mgr_mTokenWriteUserStorage(g_keyUID, offset, Base64WriteData);
		if(nRet != 0)
		{	
			document.getElementById("txt_writeUserData").value = "";
			alert("写用户存储区数据失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
		}
		
	};
	
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenSetSeedKey（string keyUID, string seedKey)
	* 功    能：设置SHA1计算摘要种子码
	* 输    入：keyUID,设备唯一硬件ID
	*           seedKey,参加SHA1计算的字符串（BASE64编码）
	* 返 回 值：0 成功，正确保存SHA1种子码
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：SHA1计算摘要种子码，参与SHA1摘要生成
	*
	**********************************************************/
	function K1MgrmTokenSetSeedKey()
	{
		var sha1SeedKey = document.getElementById("txt_sha1Seed").value;	
		var sha1SeedKeyBase64 = _Base64encode(sha1SeedKey);
		
		var nRet = K1mTokenMgr.K1Mgr_mTokenSetSeedKey(g_keyUID, sha1SeedKeyBase64);
		if(nRet != 0)
		{
			alert("设置SHA1种子码失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenSetMainKey（string keyUID, string mainKey)
	* 功    能：设置3DES秘钥
	* 输    入：keyUID,设备唯一硬件ID
	*           mainKey,用户参与加密解密的3DES秘钥（BASE64编码）
	* 返 回 值：0 成功，正确保存3DES秘钥
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：3DES秘钥主要用于参与安全存储区读取与写入数据的加/解密运算
	*
	**********************************************************/
	function K1MgrmTokenSetMainKey()
	{
		var triDesKey = document.getElementById("txt_triDESKey").value;	
		var triDesKeyBase64 = _Base64encode(triDesKey);
		var nRet = K1mTokenMgr.K1Mgr_mTokenSetMainKey(g_keyUID, triDesKeyBase64);
		if(nRet != 0)
		{
			alert("设置SHA1种子码失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
	};
	
	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenGenResetpwdResponse（string keyUID, string mainKey, string clientRequest, string adminPwd, string userPwd)
	* 功    能：设置3DES秘钥
	* 输    入：keyUID,设备唯一硬件ID
	*           mainKey,申请设备中设置3DES秘钥（BASE64）
	*           clientRequest，由设备请求的密码重置申请的字符串
	*           adminPwd，设备管理密码
	*           userPwd，新的用户密码
	* 返 回 值：0 成功，正确生成重置密码响应码
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：生产重置密码响应码
	*
	**********************************************************/
	function K1MgrmTokenGenResetpwdResponse()
	{
		K1mTokenMgr.LoadLibrary();
		
		var resetPwdRequest = document.getElementById("txt_resetPwdRequest").value;	
		var adminPwd= document.getElementById("txt_AdminPwd").value;	
		
		var triDesKey = document.getElementById("txt_triDESKey").value;	
		var triDesKeyBase64 = _Base64encode(triDesKey);
		
		var newUserPwd = document.getElementById("txt_newUserPwd").value;	
						
		var pwdResponse = K1mTokenMgr.K1Mgr_mTokenGenResetpwdResponse(triDesKeyBase64, resetPwdRequest, adminPwd, newUserPwd);
		if(pwdResponse == null || pwdResponse == "")
		{
			document.getElementById("txt_resetPwdResponse").value = "";
			
			alert("生成密码重置请求失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
		//var pwdResponseBase64 = _Base64encode(pwdResponse);
		
		document.getElementById("txt_resetPwdResponse").value = pwdResponse;
	};

	/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenSetUserInfo（string keyUID, int openType,
	*	                                 string label, string url, string companyName, 
	*                                    string remarks, string tip)
	* 功    能：设置用户配置区信息
	* 输    入：keyUID,设备唯一硬件ID
	*           openType, 客户端浏览器打开类型
	*           label, 设备别名
	*           url,网址
	*           companyName, 公司名称
	*           remarks,备注信息
	*           tip, 气泡信息
	* 返 回 值：0 成功，正确设置用户配置区基本信息
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：设置用户配置区相关信息
	*
	**********************************************************/
	function K1MgrmTokenSetUserInfo()
	{
		var label = document.getElementById("txt_label").value;	
		var url = document.getElementById("txt_url").value;	
		var companyName = document.getElementById("txt_companyName").value;	
		var remarks = document.getElementById("txt_remarks").value;	
		var tip = document.getElementById("txt_tip").value;	
		var openBowseType = document.getElementById("txt_openBrowseType").value;	
		
		if(label.length > 31)
		{
			alert("别名最大长度31字符");
			return ;
		}
		
		var nRet = K1mTokenMgr.K1Mgr_mTokenSetUserInfo(g_keyUID, openBowseType, label, url, companyName, remarks, tip);
		if(nRet != 0)
		{
			alert("设置配置区信息失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
	};
		/*******************************************************
	*
	* 函数名称：K1Mgr_mTokenSetUserInfo（string keyUID, string adminPwd, string userPwd)
	* 功    能：设置用密码
	* 输    入：keyUID,设备唯一硬件ID
	*           adminPwd, 设备管理密码
	*           userPwd, 新设置的设备用户权限密码

	* 返 回 值：0 成功，用户权限密码设置成功
	*           非0 失败，使用K1Mgr_mTokenGetLastError()函数获取错误码。
	* 说	明：设置用户密码
	*
	**********************************************************/
	function K1MgrmTokenUnlockPwd()
	{
		var adminPwd = document.getElementById("txt_AdminPwd").value;	
		var unlockPwd = document.getElementById("txt_unlockPwd").value;	
		var nRet = K1mTokenMgr.K1Mgr_mTokenUnlockPwd(g_keyUID, adminPwd, unlockPwd);
		if(nRet != 0)
		{
			alert("解锁用户密码失败,错误码:" + K1mTokenMgr.K1Mgr_mTokenGetLastError());
			return;
		}
		
	};
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
