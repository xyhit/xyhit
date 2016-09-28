<!DOCTYPE html>
<html lang="zh-Hans">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>系统消息</title>
	<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
</head>
<body>
<div class="container" id="container"><div class="msg">
	<div class="weui_msg">
		<div class="weui_icon_area"><i class="weui_icon_warn weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">搜索被禁止</h2>
			<p class="weui_msg_desc">您还没有注册，请点击页面底部的链接进行注册！</p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a class="weui_btn weui_btn_primary" onclick="closeWindow()">确定</a>
			</p>
		</div>
		<div class="weui_extra_area">
		<?php
			$redirect_uri = urlencode("http://1.xyhit.applinzi.com/oauth2.php");
			echo "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=124#wechat_redirect'>现在注册</a>"
		?>
		</div>
	</div>
	</div>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<script type="text/javascript">
function closeWindow(){
	wx.closeWindow();
}
</script>
</html>