<?php
	
	/*******************************************
	这个文件中的58行form请求，应该是post, 但是微信会因为我们的测试号还没认证所以会弄丢post值，所以我们暂用get
	认证公众号后，我们要把58行get改成post, searchafter.php中的$_GET改成$_POST.
	***************************/
	
	
	session_start();
	//https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID
	if (isset($_GET['code'])){
		include ("./oauth_base.php");
		//echo $access_token['openid'];
		$id = $access_token['openid'];
		if ($id == '' && isset($_SESSION['userid'])){
			$id = $_SESSION['userid'];
		}
		include ("./conn.php");
		$sql = mysql_query("select id from user where id='$id'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				$userinfo = mysql_fetch_array($sql);
				$_SESSION['userid'] = $userinfo['id'];
			}
			else{
				header("Location: ./nothisid.php");
				exit;
			}
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-CODE-TYPE ERROR";
		exit;
	}
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>搜索</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css"/>
    <link rel="stylesheet" href="./weui/dist/example/example.css"/>
</head>
<body ontouchstart>
    <div class="container" id="container"></div>
    <script type="text/html" id="tpl_searchbar">
	<div class="hd">
		<h1 class="page_title">搜索用户</h1>
	</div>
		<div class="bd">
			<!--<a href="javascript:;" class="weui_btn weui_btn_primary">点击展现searchBar</a>-->
			<div class="weui_search_bar" id="search_bar">
				<form class="weui_search_outer" method="get" action="./searchafter.php">
					<div class="weui_search_inner">
						<i class="weui_icon_search"></i>
						<input type="search" class="weui_search_input" id="search_input" name="search_input" placeholder="搜索" required/>
						<a href="javascript:" class="weui_icon_clear" id="search_clear"></a>
					</div>
					<label for="search_input" class="weui_search_text" id="search_text">
						<i class="weui_icon_search"></i>
						<span>搜索</span>
					</label>
				</form>
				<a href="javascript:" class="weui_search_cancel" id="search_cancel">取消</a>
			</div>
			<!--<div class="weui_cells weui_cells_access search_show" id="search_show">
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<p>实时搜索文本</p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<p>实时搜索文本</p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<p>实时搜索文本</p>
					</div>
				</div>
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary">
						<p>实时搜索文本</p>
					</div>
				</div>
			</div>-->
		</div>
</script>
    <script src="./weui/dist/example/zepto.min.js"></script>
    <script src="./weui/dist/example/router.min.js"></script>
    <script src="./weui/dist/example/searchbar.js"></script>

</body>
</html>
