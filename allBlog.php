<?php
	namespace LaneWeChat;
	session_start();
	require ("./core/pagesplitTEMP.lib.php");
	use LaneWeChat\Core\MypageTEMP;
	include ("./conn.php");
	function object_array($array){
		if(is_object($array)){
			$array = (array)$array;
		}
		if(is_array($array)){
			foreach($array as $key=>$value){
				$array[$key] = object_array($value);
			}
		}
		return $array;
	} 

	use sinacloud\sae\Storage as Storage;
	if (isset($_GET['code']) || isset($_SESSION['authentication'])){
		if (isset($_SESSION['authentication'])){
			$openid = $_SESSION['openid'];
		}
		else{
			include ("./oauth_base.php");
			$openid = $access_token['openid'];
			$sql = mysql_query("select id from user where id='$openid'", $db);
			if ($sql){
				$_SESSION['openid'] = $openid;
				if (mysql_num_rows($sql) == 0){
					$_SESSION['authentication'] = false;
					$ifregister = false;
				}
				else{
					$_SESSION['authentication'] = true;
					$ifregister = true;
				}
			}
			else{
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
		}
		
		$type = '0';
		if (isset($_GET['type'])){
			$type = $_GET['type'];
		}
		$mark = "A";
		switch ($type){
			case "1":
				$mark = "Q";
				break;
			case "2":
				$mark = "E";
				break;
			case "3":
				$mark = "S";
				break;
			
		}
		
		$blogsql = mysql_query("select blogid, userid, date_format(time, '%Y-%m-%d %H:%i') as time, title from blog where mark='$mark' order by(time) desc", $db);
		if (!$blogsql){
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-CODE-ERROR";
	}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
	<title>动态浏览</title>
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览 -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui">
	<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
	<link rel="stylesheet" href="./artEditor/css/viewblog.css">
</head>
<body>
<div class="navbar">
<div class="bd" style="height: 100%;">
    <div class="weui_tab">
        <div class="weui_navbar">
            <div class="weui_navbar_item" id="item0" onclick="turnOnItem(this)">
                个人动态
            </div>
            <div class="weui_navbar_item" id="item1" onclick="turnOnItem(this)">
                疑难问题
            </div>
            <div class="weui_navbar_item" id="item2" onclick="turnOnItem(this)">
                招聘信息
            </div>
			<div class="weui_navbar_item" id="item3" onclick="turnOnItem(this)">
                招生信息
            </div>
        </div>
		<!-- content start-->
	<div style="width:320px;margin: 0 auto;">
		<div class="weui_tab_bd" id="pos"></div>
        <div class="weui_tab_bd" id="content">
			<!-- 文章展示 start-->
			<?php
				$num = mysql_num_rows($blogsql);
				$pageobj = new MypageTEMP($num, 15);
				if ($num <= 0){
					echo "<h3 align='center'>暂无条目</h3>";
				}
				else{
					mysql_data_seek($blogsql, ((int)$pageobj->page - 1) * 15);
					$counter=0;
					while(($blogresult = mysql_fetch_array($blogsql)) && $counter<15){
						$publishersql = mysql_query("select name from user where id='".$blogresult['userid']."'", $db);
						if ($publishersql){
							if (mysql_num_rows($publishersql) == 0){
								$publisherRegister = false;
							}
							else{
								$publisherRegister = true;
								$publisherResult = mysql_fetch_array($publishersql);
								$publisher = $publisherResult['name'];
							}
						}
						else{
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						
						$s = new Storage;
						$blogId = $blogresult['blogid'];
						$array = object_array($s->getObject("blog", $blogId.".txt"));
						//echo $array["body"];
						$blogContent = $array["body"];        //从SAE加载正文
						$preview = strstr($blogContent, "<img", true);
						
						echo "<div class='publish-article-title'>
							<div class='article'>
							<div class='hd'>
								<h2 class='page_title' align='center'>".$blogresult['title']."</h2>
							</div>
							<div class='bd'>
								<article class='weui_article'>
									<h1>";
						if ($publisherRegister == true){
							echo $publisher." | ".$blogresult['time'];
						}	
						else{
							echo "匿名 | ".$blogresult['time'];
						}
						echo "		</h1>
									<section>";
						if (isset($preview) && $preview != false){
							echo $preview;
						}
						else{
							echo $blogContent;
						}
						echo "		</section>
								</article>
							</div>
							</div>
							<div class='weui_cells weui_cells_access'>
							<a class='weui_cell' href='./viewBlog.php?id=$blogId'>
								<div class='weui_cell_bd weui_cell_primary'>";
						if (isset($preview) && $preview != false){
							echo "<p style='color:blue;'>有图哦</p>";
						}
						else{
							echo "<p>查看评论</p>";
						}
						echo "		</div>
								<div class='weui_cell_ft'>
								</div>
							</a>
							</div>
						</div>";
						$counter ++;
					}
				}
			?>

			<!-- 文章展示 end-->
			<div class='publish-article-title'>
			<?php
				echo "<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_hd' alige = 'center'>";
				echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
				echo "</div>
					</div>";
			?>
			</div>
		</div>
		<!-- content end -->
    </div>
</div>
</div>
</body>
<script type="text/javascript">
<?php
	if ($type != '0' && $type != '1' && $type != '2' && $type != '3'){
		echo "ERROR-GET-TYPE!\n";
		exit;
	}
	echo "document.getElementById('item' + $type).setAttribute('class', 'weui_navbar_item weui_bar_item_on')";
?>
</script>
<script type="text/javascript">
function turnOnItem(para){
	if (para.className == "weui_navbar_item weui_bar_item_on")
		return;
	var items = document.getElementsByClassName("weui_navbar_item");
	for(var i = 0; i < items.length; i ++){
		if (items[i].getAttribute('class') == "weui_navbar_item weui_bar_item_on"){
			items[i].setAttribute("class", "weui_navbar_item");
			break;
		}
	}
	para.setAttribute("class", "weui_navbar_item weui_bar_item_on");
	switch (para.id){
		case "item0":
			window.location.href='./allBlog.php?type=0';
			break;
		case "item1":
			window.location.href='./allBlog.php?type=1';
			break;
		case "item2":
			window.location.href='./allBlog.php?type=2';
			break;
		case "item3":
			window.location.href='./allBlog.php?type=3';
			break;
		default:
			window.location.href='./allBlog.php';
	}
	
}
</script>
</html>
