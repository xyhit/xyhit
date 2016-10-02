<?php
	//想要注册的用户如果已经注册过了，跳转到该页面
	
?>
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
			<h2 class="weui_msg_title">您已经注册过了，请勿重复注册</h2>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a class="weui_btn weui_btn_primary" onclick="closeWindow()">确定</a>
			</p>
		</div>
		<div class="weui_extra_area">
			<a href="">查看详情</a>
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