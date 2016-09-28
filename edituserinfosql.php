<?php
	include "./conn.php";
	if (isset($_POST['userid'])){
		$userid = $_POST['userid'];
		if (isset($_POST['name'])){
			$name = $_POST['name'];
			mysql_query("update user set name='$name' where id='$userid'", $db);
		}
		else if (isset($_POST['gender'])){
			$gender = $_POST['gender'];
			if ($gender == '1'){
				mysql_query("update user set gender='M' where id='$userid'", $db);
			}
			else{
				mysql_query("update user set gender='F' where id='$userid'", $db);
			}
		}
		else if (isset($_POST['nickname'])){
			$nickname = $_POST['nickname'];
			mysql_query("update user set nickname='$nickname' where id='$userid'", $db);
		}
		else if (isset($_POST['country']) && isset($_POST['province']) && isset($_POST['city'])){
			$country = $_POST['country'];
			$province = $_POST['province'];
			$city = $_POST['city'];
			mysql_query("update user set country='$country' and province='$province' and city='$city' where id='$userid'", $db);
		}
		else if (isset($_POST['company'])){
			$company = $_POST['company'];
			mysql_query("update user set company='$company' where id='$userid'", $db);
		}
		else if (isset($_POST['position'])){
			$position = $_POST['position'];
			mysql_query("update user set position='$position' where id='$userid'", $db);
		}
		else if (isset($_POST['degree'])){
			$degree = $_POST['degree'];
			mysql_query("update user set degree='$degree' where id='$userid'", $db);
		}
		else if (isset($_POST['major'])){
			$nickname = $_POST['nickname'];
			mysql_query("update user set nickname='$nickname' where id='$userid'", $db);
		}
		else if (isset($_POST['entime'])){
			$entime = $_POST['entime'];
			mysql_query("update user set entime='$entime' where id='$userid'", $db);
		}
		else if (isset($_POST['grtime'])){
			$grtime = $_POST['grtime'];
			mysql_query("update user set grtime='$grtime' where id='$userid'", $db);
		}
		else{
			echo "NO-POST-CODE-ERROR";
		}
	}
	else{
		echo "no-post-userid";
		exit;
	}
	
	header("Location:".'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri='.urlencode("http://1.xyhit.applinzi.com/edituserinfo.php").'&response_type=code&scope=snsapi_base&state=122#wechat_redirect');
?>