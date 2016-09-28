<?php
	use sinacloud\sae\Storage as Storage;
	if (isset($_POST['imgsrc']) && isset($_POST['openid'])){
		$imgsrc = $_POST['imgsrc'];
		$openid = $_POST['openid'];
		$s = new Storage();  
		$s->putObject($imgsrc, "qrcode", $openid.".txt", array(), array('Content-Type' => 'text/plain'));
	}
	else{
		echo '2';
	}
?>