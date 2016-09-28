<?php
	if (isset($_GET['id'])){
		include("conn.php");
		$id = $_GET['id'];
		$sql = mysql_query("select title, stringtime, description, content from news where id='".$id."'", $db);
		$result = mysql_fetch_array($sql);
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
    <link rel="stylesheet" href="./weui/dist/style/weui.css"/>
    <link rel="stylesheet" href="./weui/dist/example/example.css"/>
</head>
<body ontouchstart>
<div class="container" id="container"><div class="article">
<div class="hd">
    <h1 class="page_title">
	<?php
		echo $result["title"];
	?>
	</h1>
</div>
<div class="bd">
    <article class="weui_article">
        <p>
		<?php
			echo "时间：".date("Y-m-d H:i", ($result["stringtime"]));
		?>
		</p>
        <section>
            <p class="title">
			<?php
				echo "描述：".$result["description"];
			?>
			</p>
            <section>
                <p>
				<?php
					echo str_replace("  ", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", str_replace("\n","<br/>", $result["content"]));
				?>
				</p>
                <p>
				<?php
					$sql = mysql_query("select url from picture where id='".$id."'", $db);
					while ($result = mysql_fetch_array($sql)){
						echo "<img src='".$result['url']."' alt=''>";
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