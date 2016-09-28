<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;
	
	if (isset($_GET['donationid']) && isset($_GET['year'])){
		$donationid =  $_GET['donationid'];
		$year = $_GET['year'];
		include "./conn.php";
		$sql = mysql_query("select * from donationout where donationid='$donationid' and date_format(dtime, '%Y')='$year' order by(dtime) desc", $db);
	}
	else{
		echo "no-get-code-error!\n";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>捐款流向</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
		<link rel="stylesheet" href="./weui/dist/example/example.css">
		<link rel="stylesheet" href="./weui/dist/style/CSSTableGenetor.css">
	</head>
	<body>
	<div class='CSSTableGenerator'>
	<table>
	<tr><td>受赠方姓名</td><td>项目</td><td>金额</td><td>受赠日期</td><td>受赠方详情</td><tr>
				<?php
					if ($sql){
						$num = mysql_num_rows($sql);
						$pageobj = new Mypage($num, 20);
						mysql_data_seek($sql, ((int)$pageobj->page - 1) * 20);
						$counter=0;
						while(($result = mysql_fetch_array($sql)) && $counter<20){
				?>
				<tr>
					<td><?php echo $result['doneename'] ?></td>
					<td><?php echo "".$result['project'] ?></td>
					<td><?= $result['money'] ?></td>
					<td><?= $result['dtime'] ?></td>
					<td> 
					<?php
						$doneeid = $result['doneeid'];
						$doneeidsql = mysql_query("select id from user where id='$doneeid'", $db);
						if (mysql_num_rows($doneeidsql) <= 0){
							echo "<a href='javascript:void(0);' id='".$doneeid."' onclick='viewsimpleinfo(this)'>查看</a>";
						}
						else{
							$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$doneeid)."&response_type=code&scope=snsapi_base&state=130#wechat_redirect";
							echo "<a href='".$url."'>查看</a>";
						}
					?>

					</td>
					</tr>
				<?php
							$counter ++;
						}
					}
					else{
						echo mysql_errno() . ": " . mysql_error(). "\n";
						exit;
					}
				?>
				
		</table>
		</div>
		
		<?php
			echo "<div class='weui_panel weui_panel_access'>
					<div class='weui_panel_hd' alige = 'center'>";
			echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
			echo "</div>
				</div>";
		?>
	</body>
	<script type='text/javascript'>
	function viewsimpleinfo(currenthtml){
		alert("用户未注册！");
	}
	</script>
</html>