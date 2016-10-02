<?php
	include "./conn.php";
	if (isset($_GET['donationid'])){
		$donationid = $_GET['donationid'];
		$donationsql = mysql_query("select donationid, donaname, project, projecttime from donation where donationid='$donationid'", $db);
		if ($donationsql){
			$donationresult = mysql_fetch_array($donationsql);
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
		}
	}
	else{
		echo "未选择款项";
		exit;
	}
	if (isset($_GET['id'])){
		$doneeid = $_GET['id'];
		$sql = mysql_query("select id, name from user where id='$doneeid'", $db);
		if ($sql){
			$result = mysql_fetch_array($sql);
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
		}
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>捐款流向</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
	</head>
	<body>
	<div class="weui_panel">
        <div class="weui_panel_hd">款项信息</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_text">
				<?php
					echo "<h4 class='weui_media_title'>".$donationresult['project']."</h4>
							<ul class='weui_media_info'>
								<li class='weui_media_info_meta'>".$donationresult['donaname']."</li>
								<li class='weui_media_info_meta weui_media_info_meta_extra'>".$donationresult['projecttime']."</li>
							</ul>";
				?>
            </div>
        </div>
    </div>
	<div class="weui_cells_title">受奖励信息</div>
	<form name='form1' action="./fundoutpost.php" method="post">
		<?php
			if (isset($donationresult['donationid'])){
				echo "<input type='hidden' id='donationid' name='donationid' value='".$donationresult['donationid']."'></input>";
			}
			if (isset($result['id'])){
				echo "<input type='hidden' id='doneeid' name='doneeid' value='".$result['id']."'></input>";
			}
		?>
		<div class='weui_cells weui_cells_access'>
			<div class="weui_cell weui_cell_switch" id='ifcheck'>
				<div class="weui_cell_hd weui_cell_primary">受奖励者是否已注册</div>
				<div class="weui_cell_ft_new123">
					<input class="weui_switch" type="checkbox" id='checkboxid' name='ifregister' onchange='checkboxchange()'></input>
				</div>
			</div>
			<div class="weui_cell" id='doneename'>
				<div class="weui_cell_hd">
					<label class="weui_label">姓名</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php
						if (isset($doneeid)){
							echo "<input class='weui_input' type='text' name='doneename' value='".$result['name']."' placeholder='受奖励人姓名'></input>";
						}
						else{
							echo "<input class='weui_input' type='text' name='doneename' placeholder='受奖励人姓名'></input>";
						}
					?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">电话</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="phone" placeholder="受奖励人电话"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">邮箱</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="email" placeholder="受奖励人邮箱"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">项目名称</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="project" placeholder="如 “****年度***奖学金”"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">发放金额</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="money" placeholder="发放金额(元)"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">发放时间</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="date" name="time"></input>
					</label>
				</div>
			</div>
		</div>
	</form>
	<div class="weui_btn_area">
		<a class='weui_btn weui_btn_primary' onclick='submitForm()'>保存</a>
	</div>
	<!--START toast-->
	<div id="toast" style="display: none;">
		<div class="weui_mask_transparent"></div>
		<div class="weui_toast">
			<i class="weui_icon_toast"></i>
			<p class="weui_toast_content">已完成</p>
		</div>
	</div>
	<!--END toast-->
	</body>
	<script src="./static/jquery.min.js"></script>
	<script type='text/javascript'>
	function submitForm(){
		document.form1.submit();
	}
	
	function checkboxchange(){
		if(document.getElementById("checkboxid").checked){
			<?php
				echo "var donationid = '$donationid';";
			?>
			var insert_html = "<a class='weui_cell' id='choose' href='./chooseuser.php?donationid="+ donationid +"'><div class='weui_cell_bd weui_cell_primary'><p>选择用户</p></div><div class='weui_cell_ft'></div></a>";
			document.getElementById(id="ifcheck").insertAdjacentHTML("afterEnd", insert_html);
		}
		else{
			document.getElementById(id="choose").remove();
		}
	}
	</script>
	<script type='text/javascript'>
		<?php
			if (isset($doneeid)){
				echo "var idexist = '1';";
			}
			else{
				echo "var idexist = '0';";
			}
		?>
		if (idexist == '1'){
			document.getElementById("checkboxid").setAttribute("checked","checked");
			//checkboxchange();			
		}
	</script>
</html>