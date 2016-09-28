<?php
	include "./conn.php";
	function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));   
        $uuid  = substr($str,0,8) . '-';   
        $uuid .= substr($str,8,4) . '-';   
        $uuid .= substr($str,12,4) . '-';   
        $uuid .= substr($str,16,4) . '-';   
        $uuid .= substr($str,20,12);   
        return $prefix . $uuid;
    }
    $commentId = create_uuid(); //用uuid为评论设计主键；
	
	if (isset($_POST['openid']) && isset($_POST['comment']) && isset($_POST['blogid']) && isset($_POST['blogPublisherId'])){
		$openid = $_POST['openid'];
		$comment = $_POST['comment'];
		$blogId = $_POST['blogid'];
		$blogPublisherId = $_POST['blogPublisherId'];
		$strtime = time();
		$time = date("Y-m-d H:i:s", $strtime);
		if (mysql_query("insert into blogcomment(blogid, commentid, userid, commenterid, time, content) values('$blogId', '$commentId', '$blogPublisherId', '$openid', '$time', '$comment')", $db) == false){
			echo mysql_errno() . ": " . mysql_error(). "\n";
		}
		echo "1";
	}
?>