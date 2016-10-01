<?php
	namespace LaneWeChat\News_Module;
	use LaneWeChat\Core\SqlQuery;
	include_once './config.php';
	require (ROOT_DIR."/core/sqlquery.lib.php");
	
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$query_array = array(
			"title",
			"date_format(time, '%Y-%m-%d %H:%i') as time", 
			"description",
			"content"
		);
		$condition_array = array(
			"id"=>$id
		);
		$result = SqlQuery::query("news", $query_array, $condition_array);
		if ($result[0] == 0 && count($result[1]) == 0){
			echo "不存在该条新闻！";
			exit;
		}
		else if ($result[0] == -1){
			echo "数据库查询出错！";
			exit;
		}
	}
	else{
		echo "NO-ID-ERROR";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>新闻阅读</title>
    <link rel="stylesheet" href="<?= ROOT_DIR?>/static/weui/dist/style/weui.css"/>
    <link rel="stylesheet" href="<?= ROOT_DIR?>/static/weui/dist/example/example.css"/>
</head>
<body ontouchstart>
<div class="container" id="container"><div class="article">
<div class="hd">
    <h1 class="page_title">
	<?php
		echo $result[1][0]["title"];
	?>
	</h1>
</div>
<div class="bd">
    <article class="weui_article">
        <p>
		<?php
			echo "时间：".$result[1][0]["time"];
		?>
		</p>
        <section>
            <p class="title">
			<?php
				echo "描述：".$result[1][0]["description"];
			?>
			</p>
            <section>
                <p>
				<?php
					echo str_replace("  ", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", str_replace("\n","<br/>", $result[1][0]["content"]));
				?>
				</p>
                <p>
				<?php
					$query_array = array(
						"id"
					);
					$condition_array = array(
						"news_id"=>$id
					);
					$result = SqlQuery::query("newspicture", $query_array, $condition_array);
					if ($result[0] == 0 && count($result[1]) == 0){
						echo "不存在图片！";
						exit;
					}
					else if ($result[0] == -1){
						echo "数据库newspicture表查询出错！".$result[1];
						exit;
					}
					$i = 0;
					while ($i < count($result[1])){
						echo "<img src='".PICTURE_SAVED_PATH."/".$result[1][$i]['id'].".png' alt=''>";
						$i ++;
					}
				?>
                </p>
            </section>
        </section>
    </article>
</div>
</div></div>
</body>
</html>