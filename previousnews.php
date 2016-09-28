<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>往期新闻</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
		<?php
			include ("conn.php");
			$sql = mysql_query("select id, title, stringtime, description from news order by(stringtime) desc", $db);
			if ($sql){
				$newsNum = mysql_num_rows($sql);
				$pageobj = new Mypage($newsNum, 10);
				if ($newsNum >= 1){
					$startrow = mysql_data_seek($sql, ((int)$pageobj->page - 1) * 10);
					$result = mysql_fetch_array($sql);
					$saved_time = (int)$result['stringtime'];
				}
				else{
					echo "<h2 align='center'>暂无新闻</h2></body></html>";
					exit;
				}
			}
			else{
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
			$count = 0;
			while (true){
				echo "<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_hd'>".date("Y-m-d", $result['stringtime'])."</div>
						<div class='weui_panel_bd'>";
				do {
					$pic = mysql_query("select url from picture where id='".$result['id']."'", $db);
					$picresult = mysql_fetch_array($pic);
					echo "<a href='"."http://1.xyhit.applinzi.com/news.php?id=".$result['id']."'; class='weui_media_box weui_media_appmsg'>
							<div class='weui_media_hd'>
								<img class='weui_media_appmsg_thumb' src='".$picresult['url']."' alt=''></img>
							</div>
							<div class='weui_media_bd'>
								<h4 class='weui_media_title'>".$result['title']."</h4>
								<p class='weui_media_desc'>".$result['description']."</p>
							</div>
						</a>";
					$count ++;
					if ($count >= 10){
						break 2;
					}
					if ($result = mysql_fetch_array($sql)){
						if ($result['stringtime'] >= strtotime(date('Y-m-d',$saved_time))){
							continue;
						}
						else{
							$saved_time = (int)$result['stringtime'];
							break;
						}
					}
					else {
						break 2;
					}
				}while (true);
				echo "</div>
					</div>";
			}
			echo "</div>
				</div>";
			echo "<div class='weui_panel weui_panel_access'>
				<div class='weui_panel_hd' alige = 'center'>";
					echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
				echo "</div>
			</div>";
		?>
</body>
</html>