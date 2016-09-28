<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;

	include ('./conn.php');
	$sql = mysql_query("select * from fund order by time desc", $db);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>有捐款意向的用户</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
		<?php
			if ($sql){
				$num = mysql_num_rows($sql);
				if ($num <= 0){
					echo "<h2 align='center'>暂无消息</h2></body></html>";
					exit;
				}
				$pageobj = new Mypage($num, 10);
				mysql_data_seek($sql, ((int)$pageobj->page - 1) * 10);
				$counter = 0;
				while(($result = mysql_fetch_array($sql)) && $counter < 10){
					$registered_user = mysql_query("select id from user where id='".$result['id']."'", $db);
					//如果该用户是注册用户
					if ($registered_user && mysql_num_rows($registered_user) > 0){
						$imgsrc = "http://xyhit-headimg.stor.sinaapp.com/".$result['id'].".png";
					}
					else{
						$imgsrc = "./static/img/defaultuser.jpg";
					}
					
					$userinfourl = "./funduserinfo.php?id=".$result['id']."&time=".strtotime($result['time']);
					echo "<div class='weui_panel weui_panel_access'>
							<div class='weui_panel_bd'>";
					echo "<a href='".$userinfourl."'; class='weui_media_box weui_media_appmsg'>
						<div class='weui_media_hd'>
							<img class='weui_media_appmsg_thumb' src='$imgsrc' alt=''></img>
						</div>
						<div class='weui_media_bd'>
							<h4 class='weui_media_title'>".$result['name']."</h4>
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
		?>
</body>
</html>