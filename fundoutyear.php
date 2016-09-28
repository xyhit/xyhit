<!DOCTYPE html>
<html lang="zh-Hans">
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>资金流向</title>
        <link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
    </head>
    <body>
<?php
	if (isset($_GET['id'])){
		$donationid = $_GET['id'];
		include "./conn.php";
		$sql = mysql_query("select date_format(dtime, '%Y') as year from donationout where donationid='$donationid' group by(date_format(dtime, '%Y'))", $db);
		if ($sql){
			if (mysql_num_rows($sql) <= 0){
				$LastPage =$_SERVER['HTTP_REFERER'];
				echo "<div class='weui_msg'>
						<div class='weui_icon_area'><i class='weui_icon_msg weui_icon_waiting'></i></div>
						
						<div class='weui_text_area'>
							<h2 class='weui_msg_title'>暂无流向信息</h2>
						</div>
						<div class='weui_opr_area'>
							<p class='weui_btn_area'>
								<a href='$LastPage' class='weui_btn weui_btn_primary'>确定</a>
							</p>
						</div>
					</div>
					</body>
					</html>";
				exit;
			}
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
			exit;
		}
	}
	else{
		echo "NO-GET-CODE-ERROR\n";
		exit;
	}
?>
		<div class="weui_cells_title">按照发放年份查看</div>
		<?php
			while ($result = mysql_fetch_array($sql)){
				echo "<div class='weui_cells weui_cells_access'>
						<a class='weui_cell' href='./receiver_fund.php?year=".$result['year']."&donationid=".$donationid."'>
							<div class='weui_cell_bd weui_cell_primary'>
								<p>".$result['year']." 年</p>
							</div>
							<div class='weui_cell_ft'>查看详情</div>
						</a>
					</div>";
			}
		?>
	</body>
</html>