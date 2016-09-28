<?php
	if (isset($_POST['donationid']) && isset($_POST['doneename']) && isset($_POST['project']) && isset($_POST['money']) && isset($_POST['time']) && isset($_POST['phone']) && isset($_POST['email'])){
				
		$donationid = $_POST['donationid'];
		$doneename = $_POST['doneename'];
		$project = $_POST['project'];
		$money = (int)$_POST['money'];
		$time = $_POST['time'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		
		$doneeid = md5($donationid.$phone.$time);
		
		if (isset($_POST['ifregister']) && $_POST['ifregister'] == 'on'){
			$ifregister = 'Y';
			if (isset($_POST['doneeid'])){
				$doneeid = $_POST['doneeid'];
			}
		}
		else{
			$ifregister = 'N';
		}
		include "./conn.php";
		if (mysql_query("insert into donationout(donationid, doneeid, doneename, money, dtime, project, doneephone, doneeemail, ifregister) values('$donationid', '$doneeid', '$doneename', $money, '$time', '$project', '$phone', '$email', '$ifregister')", $db) == false){
			echo mysql_errno() . ": " . mysql_error(). "\n";
		}
	}
	else{
		echo "NO-POST-CODE-ERROR";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
		<title>系统消息</title>
	</head>
	<body>
	<!--START toast-->
	<div id="toast" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content">保存成功</p>
		</div>
	</div>
	<!--END toast-->
	</body>
	<script src="./static/jquery.min.js"></script>
	<script type='text/javascript'>
		$("#toast").removeAttr("style");
		setTimeout("document.getElementById('toast').setAttribute('style','display: none;')", 1000);
		setTimeout("window.location.href='http://1.xyhit.applinzi.com/receivedMsg.php'", 1200);
	</script>
</html>