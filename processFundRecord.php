<?php
	if (isset($_POST['project']) && isset($_POST['name']) && isset($_POST['id']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['money']) && isset($_POST['date']) && isset($_POST['conditionlist'])){
		$id = $_POST['id'];
		$project = $_POST['project'];
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$money = $_POST['money'];
		$date = $_POST['date'];
		$conditionlist = $_POST['conditionlist'];
		
		$money = (int)$money;
		$donationid = md5($id.$date).rand(100000, 999999);
		include "./conn.php";
		if (mysql_query("insert into donation(DonationId, DonaId, DonaName, DonaMoney, DonaEmail, DonaPhone, projecttime, project, conditionlist) values('$donationid', '$id', '$name', $money, '$email', '$phone', '$date', '$project', '$conditionlist')", $db) == true){
			echo '1';
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
		}
	}
	else{
		echo '2';
	}
?>