<?php
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		include "./conn.php";
		//查询捐款的详细信息
		$sql = mysql_query("select * from donation where DonationId='$id'", $db);
		if ($sql){
			$result = mysql_fetch_array($sql);
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
			exit;
		}
		
		//查询捐款人是否是注册用户
		$usersql = mysql_query("select id from user where id='".$result['DonaId']."'", $db);
		if ($usersql){
			if (mysql_num_rows($usersql) == 1){
				$userexist = true;
			}
			else{
				$userexist = false;
			}
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
			exit;
		}
	}
?>

<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>捐款项目详情</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
	</head>
	<body>
		<div class="weui_cells_title">基本信息</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">项目名称</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $result['project']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">用户ID</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php echo $result['DonaId'];	?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">捐款人</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php echo $result['DonaName'];	?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">捐款金额</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php
						echo $result['DonaMoney'];
					?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">时间</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php
						echo $result['projecttime'];
					?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">联系电话</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					echo $result['DonaPhone'];
				?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">邮箱地址</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					echo $result['DonaEmail'];
				?>
				</div>
			</div>
		</div>
		<div class="weui_cells_title">奖励条件</div>
		<div class="weui_cells" id='prize'>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">奖励条件</label>
				</div>
				<div class='weui_cell_bd weui_cell_primary' contenteditable='false' name='conditioncontent' style='outline:none; min-height:15px; color:#999999; font-size:15px;'>
				<?php
					echo str_replace("\n", "<br/>", $result['conditionlist']);
				?>
				</div>
			</div>
		</div>
			<?php
				if ($userexist == true){
					echo "<div class='weui_cells_title'>捐款人是注册用户</div>
							<div class='weui_cells weui_cells_access'>";
					echo "<a class='weui_cell' href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$result['DonaId'])."&response_type=code&scope=snsapi_base&state=124#wechat_redirect'>";
					echo "<div class='weui_cell_bd weui_cell_primary'>".$result['DonaName']."</div>";
					echo "<div class='weui_cell_hd'><img name='markimg' src='http://xyhit-headimg.stor.sinaapp.com/".$result['DonaId'].".png' alt='' style='width:20px;display:block'></img></div>
							<div class='weui_cell_ft_new'>查看</div>
							</a>
					</div>";
				}
			?>
		<br/>
	</body>
</html>