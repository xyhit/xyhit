<?php
	$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx47b09b3c8dd8305e&secret=2961d88b0b1dc7b50cfc4281aca3ea52&code='.$_GET['code'].'&grant_type=authorization_code';  
	$access_token = file_get_contents($url);		
	$access_token = json_decode($access_token, true);
	/*access_token
	{
	   "access_token":"ACCESS_TOKEN",
	   "expires_in":7200,
	   "refresh_token":"REFRESH_TOKEN",
	   "openid":"OPENID",
	   "scope":"SCOPE"
	}
	*/
	
	//获取用户信息
	$seturl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token['access_token'].'&openid='.$access_token['openid'].'&lang=zh_CN';
	$userinfo = file_get_contents($seturl);
	$userinfo = json_decode($userinfo, true);
	/*
	{
	   "openid":" OPENID",
	   " nickname": NICKNAME,
	   "sex":"1",
	   "province":"PROVINCE"
	   "city":"CITY",
	   "country":"COUNTRY",
		"headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
		"privilege":[
		"PRIVILEGE1"
		"PRIVILEGE2"
		],
		"unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
	}
	*/
?>