<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
	if (isset($_GET['code'])){
		include ("./oauth_base.php");
		//echo $access_token['openid'];
		$id = $access_token['openid'];
		include ("./conn.php");
		$sql = mysql_query("select name from user where id='$id'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				$userinfo = mysql_fetch_array($sql);
				$user_exist = true;
				$name = $userinfo['name'];
			}
			else{
				$user_exist = false;
			}
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-CODE-TYPE ERROR";
		exit;
	}
?>

<!DOCTYPE html>
<html lang="zh-Hans">
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
		<meta name="apple-mobile-web-app-capable" content="yes">
        <title>捐赠信息</title>
        <link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
		<link rel="stylesheet" href="./weui/dist/style/CSSTableGenetor.css">
		
    </head>
    <body>
		<div class='CSSTableGenerator'>
		<table>
				<?php
					$sql = mysql_query("select DonaName, DonaMoney, DonationID, project, projecttime from donation order by DonaMoney desc", $db);
					if (!$sql){
						die('Error: ' . mysql_error());
					}
					
					$num = mysql_num_rows($sql);
					$pageobj = new Mypage($num, 20);
					if ($num <= 0){
						echo "<h3 align='center'>暂无条目</h3>";
					}
					else{
						mysql_data_seek($sql, ((int)$pageobj->page - 1) * 20);
						$order = ((int)$pageobj->page - 1) * 20 + 1;
						$counter=0;
						
						echo "<tr><td>序号</td><td>捐赠人</td><td>项目名称</td><td>金额</td><td>查看</td><td>流向</td></tr>";
						while(($result = mysql_fetch_array($sql)) && $counter<20){
					?>
							<tr>
								<td><?php echo $order ?></td>
								<td><?php echo $result['DonaName'] ?></td>
								<td><?= $result['project'] ?></td>
								<td><?= $result['DonaMoney'] ?></td>
								<td> <a href="./<?= "view_fund.php?id=".$result['DonationID'] ?>">详情</a></td>
								<td> <a href="./<?= "fundoutyear.php?id=".$result['DonationID'] ?>">详情</a></td>
							</tr>
					<?php
						$counter ++;
						$order ++;
						}
					}
				?>
				
		</table>
			<!--</form>-->
		</div>
		<?php
			echo "<div class='weui_panel weui_panel_access'>
					<div class='weui_panel_hd' alige = 'center'>";
			echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
			echo "</div>
				</div>";
		?>
		<br/>
			<div class="weui_btn_area">
				<a href="javascript:;" class="weui_btn weui_btn_primary" id="showDialog1" onclick="showDialog()">我要捐款</a>
			</div>
			<div class="dialog">
			<!--BEGIN dialog1-->
			<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
				<div class="weui_mask"></div>
				<div class="weui_dialog">
					<div class="weui_dialog_hd"><strong class="weui_dialog_title">信息确认</strong></div>
					<div class="weui_dialog_bd">请确认您的信息，方便我们与您联系：
					<br/>
					<form name='form1'>
					姓名：<?php
								if ($user_exist == true)
									echo "<input type='text' name='name' value='$name'></input>";
								else{
									echo "<input type='text' name='name' value=''></input>";
								}
							?>
					<br/>
					电话：<input type="text" name='phone' value=''></input>
					<br/>
					邮箱：<input type="text" name='email' value=''></input>
					</div>
					<div class="weui_dialog_ft">
						<a class="weui_btn_dialog default" onclick="hideDialog()">取消</a>
						<a class="weui_btn_dialog primary" onclick='submitForm()'>确定</a>
					</div>
				</div>
			</div>
			<!--END dialog1-->
			</div>
		</body>
	<script src="./static/jquery.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
	<script type="text/javascript">
	function showDialog(){
		$("#dialog1").removeAttr("style");
	}
	function hideDialog(){
		$("#dialog1").attr("style","display: none;");
	}
	function submitForm(){
		var form = document.form1;
		if (form.name.value=="")
		{
			alert("请输入您的姓名!");
			form.name.focus();
			return(false);
		}
		if (form.phone.value=="")
		{
			alert("请输入您的电话!");
			form.phone.focus();
			return(false);
		}
		if (form.email.value=="")
		{
			alert("请输入您的Email!");
			form.email.focus();
			return(false);
		}
		<?php
			echo "var openid = '$id';";
		?>
		var name = form.name.value;
		var phone = form.phone.value;
		var email = form.email.value;
		var postStr = "openid=" + openid + "&name=" + name + "&phone=" + phone + "&email=" + email;	
		var xmlhttp;
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("post", "http://1.xyhit.applinzi.com/processFund.php", true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send(postStr);
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var recv = xmlhttp.responseText;
				wx.closeWindow();
			}
		}
	}
	</script>
</html>