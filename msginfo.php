<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
	if (isset($_GET['id'])){
		$userid = $_GET['id'];
		include './conn.php';
		$usersql = mysql_query("select id, name from user where id='$userid'", $db);
		if ($usersql){
			if (mysql_num_rows($usersql) > 0){
				$registered = true;
				$userresult = mysql_fetch_array($usersql);
			}
			else{
				$registered = false;
			}
		}
		else{
			echo "connot connect database!\n";
			exit;
		}
	}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>消息详情</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
	<?php
		if ($registered == true){
			$userinfourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$userid)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
			$imgsrc = "http://xyhit-headimg.stor.sinaapp.com/".$userid.".png";
			echo "
				<div class='weui_cells_title'>该用户是注册用户</div>
				<div class='weui_cells weui_cells_access'>
					<a class='weui_cell' href='".$userinfourl."'>
						<div class='weui_cell_hd'><img src='".$imgsrc."' alt='' style='width:20px;margin-right:5px;display:block'></div>
						<div class='weui_cell_bd weui_cell_primary'>
						".$userresult['name']."
						</div>
						<div class='weui_cell_ft'>用户详情</div>
					</a>
				</div>
			";
		}
	?>
	
	<div class="weui_cells_title">回复区</div>
	<div class="weui_cells weui_cells_form">
		<div class="weui_cell">
			<div class="weui_cell_bd weui_cell_primary">
				<textarea class="weui_textarea" placeholder="请输入回复内容" rows="3" id="textarea"></textarea>
				<div class="weui_textarea_counter"><span id="count">0</span>/200</div>
			</div>
		</div>
	</div>
	<div class="weui_btn_area">
		<a class="weui_btn weui_btn_primary" onclick="submitForm()">回复</a>
	</div>
	<div class="weui_cells_title">消息区</div>
	<div class="weui_panel">
		<div class="weui_panel_bd">
			<?php
				$sql = mysql_query("select * from formalmsg where id='$userid' order by(time) desc", $db);
				if ($sql){
					$num = mysql_num_rows($sql);
					if ($num <= 0){
						echo "<h2 align='center'>暂无消息</h2></body></html>";
						exit;
					}
					$pageobj = new Mypage($num, 10);
					mysql_data_seek($sql, ((int)$pageobj->page - 1) * 10);
					$counter = 0;
					while(($result = mysql_fetch_array($sql)) && $counter < 10){
						echo "<div class='weui_media_box weui_media_text'>
								<div contenteditable='false' style='outline:none; min-height:30px; color:#999999; font-size:13px;'>".$result['content']."</div>
								<ul class='weui_media_info'>
									<li class='weui_media_info_meta'>";
						if ($result['recv_send'] == 'R'){
							echo "<font color='red'>来自用户</font>";
						}
						else{
							echo "<font color='green'>系统回复</font>";
						}
						echo "</li>
									<li class='weui_media_info_meta weui_media_info_meta_extra'>".$result['time']."</li>
								</ul>
							</div>
						";						
						$counter ++;
					}
				}
				else{
					echo "cannot connect database!";
				}
			?>
		</div>
	</div>
</body>
<script src="./static/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
  var max = 200;
  $('#textarea').on('input', function(){
     var text = $(this).val();
     var len = text.length;
    
     $('#count').text(len);
    
     if(len > max){
       $(this).closest('.weui_cell').addClass('weui_cell_warn');
     }
     else{
       $(this).closest('.weui_cell').removeClass('weui_cell_warn');
     }
     
  });
})
</script>
<script type="text/javascript">
function submitForm(){
	var content = document.getElementById("textarea").value;
	if (content.length > 200){
		alert("文字数量超出限制！");
		return;
	}
	<?php
		echo "var touser = '$userid';";
	?>
	var postStr = "touser=" + touser + "&content=" + content;	
	var xmlhttp;
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("post", "http://1.xyhit.applinzi.com/processSendMsg.php", true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send(postStr);
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			window.location.reload();
		}
	}
}
</script>
</html>