<?php
	namespace LaneWeChat;
	session_start();
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>搜索结果</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
	<?php
		if (isset($_GET['search_input'])){
			include ("./conn.php");
			if (!isset($_SESSION['userid']) && mysql_num_rows(mysql_query("select id from user where id='".$_SESSION['userid']."'", $db)) <= 0){
				echo "NO SESSION ERROR!";
				exit;
			}
			
			$name = $_GET['search_input'];
			$sql = mysql_query("select id, name, company, position from user where name='$name'", $db);
			if ($sql){
				$num = mysql_num_rows($sql);
				if ($num <= 0){
					echo "<br/><p align='center'>该用户不存在</p>";
					echo "<br/><a class='weui_btn weui_btn_primary' onclick='javascript:history.back(-1);'>重新搜索</a>
					</body>
					</html>";
					exit;
				}
				else{
					$pageobj = new Mypage($num, 10);
					$startrow = mysql_data_seek($sql, ((int)$pageobj->page - 1) * 10);
					$counter = 0;
					echo "<div class='weui_panel_hd'>用户列表</div>
						<div class='weui_panel weui_panel_access'>
							<div class='weui_panel_bd'>";
					while ($counter < 10 && $result = mysql_fetch_array($sql)){
						$picurl = "http://xyhit-headimg.stor.sinaapp.com/".$result['id'].".png";
						echo "<a href='"."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$result['id'])."&response_type=code&scope=snsapi_base&state=124#wechat_redirect"."'; class='weui_media_box weui_media_appmsg'>
								<div class='weui_media_hd'>
									<img class='weui_media_appmsg_thumb' src='".$picurl."' alt=''></img>
								</div>
								<div class='weui_media_bd'>
									<h4 class='weui_media_title'>".$result['name']."</h4>
									<p class='weui_media_desc'>".$result['company']." ".$result['position']."</p>
								</div>
								<span class='weui_cell_ft'></span>
							</a>";
						$counter ++;
					}
					echo "</div>
						</div>";
					echo "<div class='weui_panel weui_panel_access'>
							<div class='weui_panel_hd' alige = 'center'>";
					echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
					echo "</div>
						</div>";
					exit;
				}
			}
			else{
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
		}
		else{
			echo "<p align='center'>参数错误，请重新搜索</p>";
		}
	?>
</body>
</html>