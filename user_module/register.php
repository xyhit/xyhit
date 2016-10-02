<?php
	namespace LaneWeChat\User_Module;
	session_start();
	include_once ("./config.php");
	require (ROOT_DIR."/core/accesstoken.lib.php");
	require (ROOT_DIR."/core/popularize.lib.php");
	require (ROOT_DIR."/core/accessjstoken.lib.php");
	use LaneWeChat\Core\Popularize;
	use LaneWeChat\Core\AccessJsToken;
	
	$openid=$_POST['openid'];
	$nickname=$_POST['nickname'];
	if ($_POST['sex'] == 1)
		$gender = "M";
	else
		$gender = "F";
	$headimgurl=$_POST['headimgurl'];
	$country=$_POST['country'];
	$province=$_POST['province'];
	$city=$_POST['city'];
	$name=$_POST['name'];
	
	$major=$_POST['major'];
	$entime=$_POST['entime'];
	$grtime=$_POST['grtime'];
	$degree=$_POST['degree'];
	$teacher=$_POST['teacher'];
	
	$company=$_POST['company'];
	$pos=$_POST['pos'];
	
	/*if (mysql_query("insert into user(id, gender, province, city, entime, grtime, degree, teacher, name, nickname, country, company, position, major) value('$openid', '$gender', '$province', '$city', '$entime', '$grtime', '$degree', '$teacher', '$name', '$nickname', '$country', '$company', '$pos', '$major')", $db) == false){
		echo mysql_errno().": ".mysql_error()."\n";
	}*/
	mysql_query("insert into user(id, gender, province, city, entime, grtime, degree, teacher, name, nickname, country, company, position, major, regtime) value('$openid', '$gender', '$province', '$city', '$entime', '$grtime', '$degree', '$teacher', '$name', '$nickname', '$country', '$company', '$pos', '$major', '".(string)time()."')", $db);
	$s = new \SaeStorage();  
	$img = file_get_contents($headimgurl);  //括号中的为远程图片地址  
	$s->write ( 'headimg' ,  $openid.'.png' , $img);
?>
<!DOCTYPE html>
<html lang="zh-Hans">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>系统消息</title>
	<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
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
<div class="container" id="container"><div class="msg">
	<div class="weui_msg">
		<div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">操作成功</h2>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<?php
					if (isset($_SESSION['mark_id'])){
						echo "<a class='weui_btn weui_btn_primary' onclick='jumptomark()'>确定</a>";
						//header("Location: "."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$_SESSION['mark_id'])."&response_type=code&scope=snsapi_base&state=124#wechat_redirect");
						//unset($_SESSION['mark_id']);
						//exit;
					}
					else{
						echo "<a class='weui_btn weui_btn_primary' id='share_btn'>分享名片到朋友圈</a>";
					}
				?>
				<a class="weui_btn weui_btn_default" onclick="closeWindow()">取消</a>
			</p>
		</div>

		<div class="weui_extra_area">
			<div id="qrcode" align="center"></div>
			<br/>
			<?php 
				$reArray = Popularize::createTicket(2, 1800, $openid);
				$ticket = $reArray['ticket'];			
				echo "<div><img id='qrcodeimg' align='center' width='100px' height='100px' src='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket)."'/></div><br/>";
				echo "<a href='./qrcode.php?id=".$openid."'>为您生成了二维码</a>"; 
			?>
		</div>
	</div>
	</div>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<script src="./static/jquery.min.js"></script>
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
<script type='text/javascript'>
	<?php
		$jsapi_ticket=AccessJsToken::getAccessJsToken();
		$noncestr=AccessJsToken::getRandChar(16);
		$timestamp=time();
		$url="http://1.xyhit.applinzi.com/register.php";
		
		$str = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".(string)$timestamp."&url=".$url;
		$signature = sha1($str);
		echo "var openid = '$openid';\n";
	?>
	var imgsrc = document.getElementById("qrcodeimg").src;
	wx.config({
		debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
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
			title: '我在校友平台注册了，认识我的朋友快来标记我吧！', // 分享标题
			link: "http://1.xyhit.applinzi.com/qrcode.php?id=" + openid, // 分享链接
			imgUrl: imgsrc, // 分享图标
			success: function () { 
				closeWindow();// 用户确认分享后执行的回调函数
			}
		});
	});
</script>
<script type='text/javascript'>
window.onload=function(){
	var postStr = "imgsrc=" + imgsrc + "&openid=" + openid;	
	var xmlhttp;
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("post", "http://1.xyhit.applinzi.com/qrcodeajax.php", true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send(postStr);
};
</script>
<script type='text/javascript'>
function jumptomark(){
	<?php
		if (isset($_SESSION['mark_id'])){
			echo "window.location.href='"."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$_SESSION['mark_id'])."&response_type=code&scope=snsapi_base&state=124#wechat_redirect"."';";
		}
	?>
}
</script>
</html>