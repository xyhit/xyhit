<?php
	if (isset($_GET['code'])){
		include ("./oauth_base.php");
		$openid = $access_token['openid'];
		include ("./conn.php");
		$sql = mysql_query("select name from user where id='$openid'", $db);
		if ($sql){
			if (mysql_num_rows($sql) == 0){
				$publisher = "用户".$openid;
				$ifregister = false;
			}
			else{
				$userinfo = mysql_fetch_array($sql);
				$publisher = $userinfo['name'];
				$ifregister = true;
			}
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-CODE-ERROR";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>编辑动态</title>
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览 -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui">
	<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
	<link rel="stylesheet" href="./artEditor/css/style.css">
</head>
<body>
	<div style="width:320px;margin: 0 auto;">
		<div class="publish-article-title">
			<div class="title-tips">标题</div>
			<input type="text" id="title" class="w100" placeholder="文章标题"></input>
		</div>
		<div class="publish-article-title">
			<div class="title-tips" id="publisher">
			<?php
				if ($ifregister == true){
					echo "作者  ".$publisher;
				}
				else{
					echo "作者  "."<a href='"."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/oauth2.php")."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>未注册用户</a>"; 
				}
			?>
			</div>
		</div>
		<div class="publish-article-title">
			<div class="title-tips">标签</div>
			<form>
			<div class="weui_cells weui_cells_radio">
				<label class="weui_cell weui_check_label" for="x11">
					<div class="weui_cell_bd weui_cell_primary">
						<p>个人动态</p>
					</div>
					<div class="weui_cell_ft">
						<input type="radio" class="weui_check" name="mark1" id="x11" checked="checked">
						<span class="weui_icon_checked"></span>
					</div>
				</label>
				<label class="weui_cell weui_check_label" for="x12">

					<div class="weui_cell_bd weui_cell_primary">
						<p>疑难问题</p>
					</div>
					<div class="weui_cell_ft">
						<input type="radio" name="mark1" class="weui_check" id="x12">
						<span class="weui_icon_checked"></span>
					</div>
				</label>
				<label class="weui_cell weui_check_label" for="x13">

					<div class="weui_cell_bd weui_cell_primary">
						<p>招聘信息</p>
					</div>
					<div class="weui_cell_ft">
						<input type="radio" name="mark1" class="weui_check" id="x13">
						<span class="weui_icon_checked"></span>
					</div>
				</label>
				<label class="weui_cell weui_check_label" for="x14">

					<div class="weui_cell_bd weui_cell_primary">
						<p>招生信息</p>
					</div>
					<div class="weui_cell_ft">
						<input type="radio" name="mark1" class="weui_check" id="x14">
						<span class="weui_icon_checked"></span>
					</div>
				</label>
			</div>
			<!--<input type="radio" name="mark1" value="mark1" class="w100" checked="checked">个人动态</input>
			<input type="radio" name="mark1" value="mark2" class="w100">疑难问题</input>
			<input type="radio" name="mark1" value="mark3" class="w100">招聘信息</input>
			<input type="radio" name="mark1" value="mark4" class="w100">招生信息</input>-->
			</form>
		</div>
		<div class="publish-article-content">
			<div class="title-tips">正文</div>
			<input type="hidden" id="target"></input>
			<div class="article-content" id="content">
			</div>
			<div class="footer-btn g-image-upload-box">
				<div class="upload-button">
					<span class="upload"><i class="upload-img"></i>插入图片</span>
					<input class="input-file" id="imageUpload" type="file" name="fileInput" capture="camera" accept="image/*" style="position:absolute;left:0;opacity:0;width:100%;"></input>
				</div>
			</div>
		</div>
	</div>
	<div class="weui_btn_area">
		<a href="javascript:;" class="weui_btn weui_btn_primary" onclick="publish()">发 表</a>
	</div>
	
	<div id="toast" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content">发表成功</p>
		</div>
	</div>
	<div id="loadingToast" class="weui_loading_toast" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<div class="weui_loading">
				<div class="weui_loading_leaf weui_loading_leaf_0"></div>
				<div class="weui_loading_leaf weui_loading_leaf_1"></div>
				<div class="weui_loading_leaf weui_loading_leaf_2"></div>
				<div class="weui_loading_leaf weui_loading_leaf_3"></div>
				<div class="weui_loading_leaf weui_loading_leaf_4"></div>
				<div class="weui_loading_leaf weui_loading_leaf_5"></div>
				<div class="weui_loading_leaf weui_loading_leaf_6"></div>
				<div class="weui_loading_leaf weui_loading_leaf_7"></div>
				<div class="weui_loading_leaf weui_loading_leaf_8"></div>
				<div class="weui_loading_leaf weui_loading_leaf_9"></div>
				<div class="weui_loading_leaf weui_loading_leaf_10"></div>
				<div class="weui_loading_leaf weui_loading_leaf_11"></div>
			</div>
			<p class="weui_toast_content">数据保存中</p>
		</div>
	</div>
	
	<script src="./artEditor/js/jquery-1.11.2.js"></script>
	<script src="./static/artEditor.js"></script>
	<script src="./artEditor/js/index.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
	<script type="text/javascript">
	function publish(){
		var content = $('#content').getValue();     //正文
		var title = document.getElementById("title").value;
		<?php echo "var openid = '$openid';\n"; ?>  //发布者
		if (title == "")         //判断标题是否被填写
		{
			alert("请输入标题!");
			document.getElementById("title").focus();
			return(false);
		}
		if (content == "<p>请输入文章正文内容</p>")         //判断正文是否被填写
		{
			alert("请输入文章正文内容");
			document.getElementById("content").focus();
			return(false);
		}
		
		var chk = '0';
		var chkObjs = document.getElementsByName("mark1");
		for(var i = 0; i < chkObjs.length; i++){
			if(chkObjs[i].checked){
				chk = i.toString();                       //单选框选中内容
				break;
			}
		}
		
		$("#loadingToast").removeAttr("style");
		content = content.replace(/\+/g, "%2B");          //正文转义“+”
		content = content.replace(/\&/g, "%26");          //正文转义“&”
		title = title.replace(/\+/g, "%2B");          //标题转义“+”
		title = title.replace(/\&/g, "%26");          //标题转义“&”
		
		var postStr = "content=" + content + "&openid=" + openid + "&title=" + title + "&chk=" + chk;
		var xmlhttp;                                      //ajax异步提交表单
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("post", "http://1.xyhit.applinzi.com/blogAjaxProcess.php", true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8");
		xmlhttp.send(postStr);
		
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var recv = xmlhttp.responseText;
				if (recv == '1'){  //正确保存
					$("#loadingToast").attr("style","display: none;");
					$("#toast").removeAttr("style");
					setTimeout("document.getElementById('toast').setAttribute('style','display: none;')", 1000);
					setTimeout("wx.closeWindow()", 1200);
				}
				else{              //错误
					$("#loadingToast").attr("style","display: none;");
					alert("发表失败，请检查网络后重新提交。");
				}
			}
		}
	}
	</script>

</body>
</html>
