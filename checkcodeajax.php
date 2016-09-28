<?php
	session_start();
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 
		// ajax 请求的处理方式 
		$lcheckcode = $_GET['checkcode'];
		if (md5((string)$lcheckcode) == $_SESSION["verification"]){
			echo true;
		}
		else{
			echo false;
		}
	}
?>