<?php
	namespace LaneWeChat;
	session_start();
	require ("./core/accesstoken.lib.php");
	require ("./core/popularize.lib.php");
	require ("./core/accessjstoken.lib.php");
	use LaneWeChat\Core\Popularize;
	use LaneWeChat\Core\AccessJsToken;
	
	function object_array($array){
		if(is_object($array)){
			$array = (array)$array;
		}
		if(is_array($array)){
			foreach($array as $key=>$value){
				$array[$key] = object_array($value);
			}
		}
		return $array;
	} 

	use sinacloud\sae\Storage as Storage;
	if (isset($_GET['id'])){
		$s = new Storage;
		$id = $_GET['id'];
		$openid = $id;
		$array = object_array($s->getObject("qrcode", $id.".txt"));
		//echo $array["body"];
		$qrcodesrc = $array["body"];
		
		$fromself = false;
		if (isset($_GET['fromself']) && $_GET['fromself'] == '1'){
			$fromself = true;
		}
	}
	else{
		echo "NO-GET-CODE-ERROR";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>二维码</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css"/>
    <link rel="stylesheet" href="./weui/dist/example/example.css"/>
	<style>
		#shareit {
		  -webkit-user-select: none;
		  display: none;
		  position: absolute;
		  width: 100%;
		  height: 100%;
		  background: rgba(0,0,0,0.85);
		  text-align: center;
		  top: 0;
		  left: 0;
		  z-index: 105;
		}
		#shareit img {
		  max-width: 100%;
		}
		.arrow {
		  position: absolute;
		  right: 10%;
		  top: 5%;
		}
		#share-text {
		  margin-top: 400px;
		}
	</style>
</head>
<body>
	<div id="shareit">
	  <img class="arrow" src="./static/img/share-it.png">
	</div>
	<?php echo "<div style='text-align: center;margin-top: 120px;'>";
		  if ($fromself == false){
			  echo "<h2>长按选择识别二维码</h2>";
		  }
		  echo "<img width='200px' height='200px' alt='二维码' id='qrcodeimg' src='".urldecode($qrcodesrc)."'></img>
				</div>";
	?>
	<div class='weui_btn_area'>
		<a class='weui_btn weui_btn_primary' id='share_btn'>分享名片到朋友圈</a>
		<a class='weui_btn weui_btn_default' onclick='javascript:history.back(-1);'>取消</a>
	</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<script src="./static/jquery.min.js"></script>
<script type='text/javascript'>
	<?php
		$jsapi_ticket=AccessJsToken::getAccessJsToken();
		$noncestr=AccessJsToken::getRandChar(16);
		$timestamp=time();
		
		if (isset($fromself) && $fromself == '1'){
			$url="http://1.xyhit.applinzi.com/qrcode.php?id=$id&fromself=1";
		}
		else{
			$url="http://1.xyhit.applinzi.com/qrcode.php?id=".$id."&from=timeline&isappinstalled=0";
		}
		
		$str = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".(string)$timestamp."&url=".$url;
		$signature = sha1($str);
		echo "var openid = '$openid';\n";
	?>
	var imgsrc = document.getElementById("qrcodeimg").src;
	wx.config({
		debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		appId: <?php echo "'".WECHAT_APPID."'";?>, // 必填，公众号的唯一标识
		timestamp: <?php echo $timestamp;?>, // 必填，生成签名的时间戳
		nonceStr: <?php echo "'".$noncestr."'";?>, // 必填，生成签名的随机串
		signature: <?php echo "'".$signature."'";?>,// 必填，签名，见附录1
		jsApiList: ['onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	wx.ready(function () {
        wx.checkJsApi({
            jsApiList: [
                'onMenuShareTimeline',
            ]
        });
		wx.onMenuShareTimeline({
			title: 
			<?php
				if (isset($fromself) && $fromself == '1'){
					echo "'我在校友平台注册了，认识我的朋友快来标记我吧！'";
				}
				else{
					echo "'这是我的朋友，你也认识 Ta 吗?'";
				}
			?>, // 分享标题
			link: "http://1.xyhit.applinzi.com/qrcode.php?id=" + openid, // 分享链接
			imgUrl: imgsrc, // 分享图标
			success: function () { 
				closeWindow();// 用户确认分享后执行的回调函数
			}
		});
	});
</script>
<script type="text/javascript">
$("#share_btn").on("click", function() {
	$("#shareit").show();
});
$("#shareit").on("click", function(){
	$("#shareit").hide(); 
})
function closeWindow(){
	wx.closeWindow();
}
</script>
</html>