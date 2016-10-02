<?php
	if (isset($_POST['mark_number']) && isset($_POST['userid']) && isset($_POST['markerid']) && isset($_POST['markflag'])){
		echo "hello";
		$mark_number = $_POST['mark_number'];
		$userid = $_POST['userid'];
		$markerid = $_POST['markerid'];
		$markflag = $_POST['markflag'];
		include ("./conn.php");
		if ($markflag == 1){
			mysql_query("insert into mark(userid, markerid, time) values('$userid', '$markerid', '".(string)time()."')", $db);
		}
		else{
			$sql = mysql_query("delete from mark where userid='$userid' and markerid='$markerid'", $db);
		}
	}
?>