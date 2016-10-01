<?php
	namespace LaneWeChat\News_Module;
	include_once "./config.php";
	require (ROOT_DIR."/core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
	use LaneWeChat\Core\SqlQuery;
	require (ROOT_DIR."/core/sqlquery.lib.php");
	
	$ItemsNumInOnePage = 10;         //定义每页新闻条数
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>往期新闻</title>
    <link rel="stylesheet" href="<?= ROOT_DIR ?>/static/weui/dist/style/weui.css">
    <link rel="stylesheet" href="<?= ROOT_DIR ?>/static/weui/dist/example/example.css">
</head>
<body>
		<?php
			$query_array = array(
				"id",
				"title",
				"time",
				"description"
			);
			$condition_array = array();
			$result = SqlQuery::query("news", $query_array, $condition_array, "time");    //新闻，按time字段排序，默认为降序
			if ($result[0] == 0 && count($result[1]) == 0){
				echo "<h2 align='center'>暂无新闻</h2></body></html>";
				exit;
			}
			else if ($result[0] == -1){
				echo "数据库查询出错！";
				exit;
			}
			$newsNum = count($result[1]);
			$pageobj = new Mypage($newsNum, $ItemsNumInOnePage);
			
			$current_cursor = ((int)$pageobj->page - 1) * $ItemsNumInOnePage;
			$stringtime = (int)strtotime($result[1][$current_cursor]['time']);
			$saved_time = $stringtime;

			$count = 0;
			while (true){
				echo "<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_hd'>".date("Y-m-d", strtotime($result[1][$current_cursor]['time']))."</div>
						<div class='weui_panel_bd'>";
				do {
					$query_array = array(
						"id"
					);
					$condition_array = array(
						"news_id"=>$result[1][$current_cursor]['id']
					);
					$picresult = SqlQuery::query("newspicture", $query_array, $condition_array);  //新闻图片
					if ($picresult[0] == 0 && count($picresult[1]) == 0){
						$picture = "default.png";                        //如果新闻没有图片，给默认图片
					}
					else if ($picresult[0] == -1){
						echo "数据库查询出错！";
						exit;
					}
					else{
						$picture = $picresult[1][0]['id'].".png";
					}

					echo "<a href='".WECHAT_URL."/".CURRENT_DIR."/news.php?id=".$result[1][$current_cursor]['id']."'; class='weui_media_box weui_media_appmsg'>
							<div class='weui_media_hd'>
								<img class='weui_media_appmsg_thumb' src='".PICTURE_SAVED_PATH."/".$picture."' alt=''></img>
							</div>
							<div class='weui_media_bd'>
								<h4 class='weui_media_title'>".$result[1][$current_cursor]['title']."</h4>
								<p class='weui_media_desc'>".$result[1][$current_cursor]['description']."</p>
							</div>
						</a>";
					$count ++;
					if ($count >= $ItemsNumInOnePage){
						break 2;
					}
					if ($current_cursor < count($result[1]) - 1){
						$current_cursor ++;
						if (strtotime($result[1][$current_cursor]['time']) >= strtotime(date('Y-m-d',$saved_time))){
							continue;
						}
						else{
							$saved_time = (int)strtotime($result[1][$current_cursor]['time']);
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