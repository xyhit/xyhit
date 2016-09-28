<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>消息列表</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
<?php
	include "./conn.php";
	$sql = mysql_query("select formalmsg.id as id, time, recv_send, content from formalmsg, latestmsg where formalmsg.id=latestmsg.id and formalmsg.time=latestmsg.latesttime order by(formalmsg.time) desc", $db);
	if ($sql){
		$num = mysql_num_rows($sql);
		if ($num > 0){
			$pageobj = new Mypage($num, 10);
			mysql_data_seek($sql, ((int)$pageobj->page - 1) * 10);
			$counter = 0;
			while(($result = mysql_fetch_array($sql)) && $counter < 10){
				$registered_user = mysql_query("select id, name from user where id='".$result['id']."'", $db);
				//如果该用户是注册用户
				if ($registered_user && mysql_num_rows($registered_user) > 0){
					$registered = true;
					$imgsrc = "http://xyhit-headimg.stor.sinaapp.com/".$result['id'].".png";
				}
				else{
					$registered = false;
					$imgsrc = "./static/img/defaultuser.jpg";
				}
				
				$msginfourl = "./msginfo.php?id=".$result['id'];
				echo "<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_bd'>";
				echo "<a href='".$msginfourl."'; class='weui_media_box weui_media_appmsg'>
					<div class='weui_media_hd'>
						<img class='weui_media_appmsg_thumb' src='$imgsrc' alt=''></img>
					</div>
					<div class='weui_media_bd'>";
				if ($registered == true){
					$rUser = mysql_fetch_array($registered_user);
					echo "<h4 class='weui_media_title'>".$rUser['name']."</h4>";
				}
				else{
					echo "<h4 class='weui_media_title'>用户".$result['id']."</h4>";
				}
				echo "
						<p class='weui_media_desc'>".$result['content']."</p>
						<p class='weui_media_desc'>".$result['time']."</p>
					</div>
				</a>";
				
				$counter ++;
			}
			echo "</div></div>";
			
			echo "<div class='weui_panel weui_panel_access'>
					<div class='weui_panel_hd' alige = 'center'>";
			echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
			echo "</div>
				</div>";
		}
		else{
			echo "<h1 align='center'>暂无消息</h1>";
			exit;
		}
	}
	else{
		echo "连接数据库失败\n";
		exit;
	}
?>
</body>
</html>