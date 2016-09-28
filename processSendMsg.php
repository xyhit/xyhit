<?php
	namespace LaneWeChat;
	require ("./core/responseinitiative.lib.php");
	use LaneWeChat\Core\ResponseInitiative;
	
	if (isset($_POST['touser']) && isset($_POST['content'])){
		$touser = $_POST['touser'];
		$content = $_POST['content'];
		include "./conn.php";
		$time = date("Y-m-d H:i:s", time());
		mysql_query("insert into formalmsg(id, time, recv_send, content) values('$touser', '$time', 'S', '$content')", $db);
		$sql = mysql_query("select id from latestmsg where id='$touser'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				mysql_query("update latestmsg set latesttime='$time'", $db);
			}
			else{
				mysql_query("insert into latestmsg(id, latesttime) values('$touser', '$time')");
			}
		}
		else{
			$reply = "数据库连接发生错误，请重新发送消息。";
		}
		ResponseInitiative::text($touser, $content);
		echo true;
	}
	else{
		echo "no post code error!";
		exit;
	}
?>