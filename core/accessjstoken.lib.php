<?php
	namespace LaneWeChat\Core;
	include_once __DIR__.'/../config.php';
	
	class AccessJsToken{
		public static function getRandChar($length){//生成随机字符串
			$str = null;
			$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
			$max = strlen($strPol)-1;

			for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
			}
			return $str;
		}
		
		public static function getAccessJsToken(){
			//在获取token的过程中先判断环境
			if(Environment::isSae($_SERVER['HTTP_APPNAME'],$_SERVER['HTTP_ACCESSKEY']))
				return self::_getSaeJs();
		}
			//此处写不是在SAE环境下的getAccessJsToken的代码
			// ..............
			// ..............
		
		/**
		*@descrpition 在SAE平台上获取access_token
		* @return string
		*/
		private static function _getSaeJs(){
			//从memcache中获取access_token
			$accessJsToken = self::_getFromMemcacheJs();
			return $accessJsToken;
		}
		/**
		*@descrpition 从memcache中获取access_token
		* @return string
		*/
		private static function _getFromMemcacheJs(){
			//初始化memcache,前提是已经开启memcache服务
			if(function_exists('memcache_init') && function_exists('memcache_get') && function_exists('memcache_set')){
				$mmc=memcache_init();
				//从memcache之中取值
				$accessJsToken = memcache_get($mmc,'jskey');
				//看memcache之中是否的值是否过期/存在,true直接返回
				if(!empty($accessJsToken)){
					return $accessJsToken;
				}else{
					$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.AccessToken::getAccessToken().'&type=jsapi';
					$accessJsToken = Curl::callWebServer($url, '', 'GET');
					if(!isset($accessJsToken['ticket'])){
						return Msg::returnErrMsg(MsgConstant::ERROR_GET_ACCESS_JS_TOKEN, '获取ACCESS_JS_TOKEN失败');
					}
					//将access_token的值存入memcache并且设置其过期时间7000秒,微信平台默认是7200秒,此处设置的值比7200小就可以
					$val=memcache_set($mmc,'jskey',$accessJsToken['ticket'],0,7000);
					return $accessJsToken['ticket'];
				}
			}else{
				exit('SAE环境下不支持写文件.并且您尚未开启memcache.请在lanewechat/core/accesstoken.lib.php的getAccessToken()方法为入口自行编写存取access_token的方式');
			}
		}
	}
?>