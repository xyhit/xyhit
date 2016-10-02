<?php
	include_once __DIR__.'/config.php';
	$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET.'&code='.$_GET['code'].'&grant_type=authorization_code';  
	$access_token = file_get_contents($url);
	$access_token = json_decode($access_token, true);
	/*access_token
	{
	   "access_token":"ACCESS_TOKEN",
	   "expires_in":7200,
	   "refresh_token":"REFRESH_TOKEN",
	   "openid":"OPENID",
	   "scope":"SCOPE"
	}*/
?>