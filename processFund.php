<?php
	namespace LaneWeChat;
	require ("./core/responseinitiative.lib.php");
	use LaneWeChat\Core\ResponseInitiative;
	if (isset($_POST['name']) && isset($_POST['openid']) && isset($_POST['phone']) && isset($_POST['email'])){
		$name = $_POST['name'];
		$openid = $_POST['openid'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		include "./conn.php";
		$sql = mysql_query("insert into fund(id, name, phone, email) values('$openid', '$name', '$phone', '$email')", $db);
		if ($sql == true){
			$content = "感谢您为学院出的一份力，我们将尽快与您联系并处理相关事宜。";
			ResponseInitiative::text($openid, $content);
			echo "1";
		}
		else{
			echo "2";
		}
	}
?>