<?php
	use sinacloud\sae\Storage as Storage;
	include "./conn.php";
	if (isset($_POST['content']) && isset($_POST['openid']) && isset($_POST['title']) && isset($_POST['chk'])){
		$content = $_POST['content'];
		$openid = $_POST['openid'];
		$title = $_POST['title'];
		$chk = $_POST['chk'];
		$strtime = time();
		
		$blogId = $openid.$strtime;
		$time = date("Y-m-d H:i:s", $strtime);
		switch($chk){
			case "0":
				$chk = "A";
				break;
			case "1":
				$chk = "Q";
				break;
			case "2":
				$chk = "E";
				break;
			case "3":
				$chk = "S";
				break;
		}
		
		if (mysql_query("insert into blog(blogid, userid, time, title, mark) values('$blogId', '$openid', '$time', '$title', '$chk')", $db) == false){
			echo mysql_errno() . ": " . mysql_error(). "\n";
			exit;
		}
		
		$s = new Storage();  
		$s->putObject($content, "blog", "$blogId.txt", array(), array('Content-Type' => 'text/plain'));
		echo "1";
	}
	else{
		echo '2';
	}
?>