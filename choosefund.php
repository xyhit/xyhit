<?php
	namespace LaneWeChat;
	require ("./core/pagesplit.lib.php");
	use LaneWeChat\Core\Mypage;

	include "./conn.php";
	$sql = mysql_query("select donationid, project from donation order by(projecttime) desc", $db);
	if ($sql){
		$result = mysql_fetch_array($sql);
	}
	else{
		echo mysql_errno() . ": " . mysql_error(). "\n";
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>选择一个款项</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
		<link rel="stylesheet" href="./weui/dist/style/CSSTableGenetor.css">
	</head>
	<body>
	<div class="weui_cells_title">选择一个捐款来源</div>
	<div class="weui_cells weui_cells_radio">
		<?php
			$num = mysql_num_rows($sql);
			if ($num <= 0){
				echo "<h2 align='center'>暂无捐款</h2></body></html>";
				exit;
			}
			$pageobj = new Mypage($num, 20);
			mysql_data_seek($sql, ((int)$pageobj->page - 1) * 20);
			$counter = 0;
			echo "<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_bd'>
						<hr style='height:3px;border:none;border-top:3px solid #62b900;' />";
			while(($result = mysql_fetch_array($sql)) && $counter < 20){
				echo "<label class='weui_cell weui_check_label' for='".$result['donationid']."'>
					<div class='weui_cell_bd weui_cell_primary'>
						<p>".$result['project']."</p>
					</div>
					<div class='weui_cell_ft'>
						<input type='radio' class='weui_check' name='radio1' id='".$result['donationid']."'>
						<span class='weui_icon_checked'></span>
					</div>
				</label>
				<a class='weui_panel_ft' href='./view_fund.php?id=".$result['donationid']."'>查看详情</a>
				<hr style='height:3px;border:none;border-top:3px solid #62b900;' />";
				
				$counter ++;
			}
			echo "</div></div>";
			echo "<div class='weui_panel weui_panel_access'>
					<div class='weui_panel_hd' alige = 'center'>";
			echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
			echo "</div>
				</div>";
		?>
		<div class="weui_btn_area">
			<a class='weui_btn weui_btn_primary' onclick="submitChoice()">确定</a>
		</div>
		<br/>
	</div>
	</body>
	<script type="text/javascript">
		var checkbox = document.getElementsByName("radio1");
		if (checkbox.length >= 1){
			checkbox[0].checked = "checked";
		}
	</script>
	<script type="text/javascript">
		function submitChoice(){
			var checkbox = document.getElementsByName("radio1");
			var donationid = "";
			for (var i = 0; i < checkbox.length; i ++){
				if (checkbox[i].checked == true){
					donationid = checkbox[i].id;
					break;
				}
			}
			if (donationid == ""){
				alert("请选择一个款项！");
			}
			else{
				window.location.href = "./addfundout.php?donationid=" + donationid;
			}
		}
	</script>
</html>