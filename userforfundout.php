<?php
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		include "./conn.php";
		$sql = mysql_query("select id, gender, province, city, date_format(entime, '%Y-%c-%d') as date1, date_format(grtime, '%Y-%c-%d') as date2, degree, teacher, name, nickname, country, company, position, major from user where id='$id'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				$userinfo = mysql_fetch_array($sql);
			}
			else{
				echo "User not exist";
				exit;
			}
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-GET-CODE-ERROR";
		exit;
	}
	
	if (isset($_GET['donationid'])){
		$donationid = $_GET['donationid'];
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>选择受奖励人</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
	</head>
	<body>
		<br/>
		<p align='center'>
		<?php
			echo "<img height='100' width='100' src='http://xyhit-headimg.stor.sinaapp.com/".$userinfo['id'].".png'></img>";
		?>
		</p>
		<div class="weui_cells_title">基本信息</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">姓名</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['name']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="gender">性别</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php 
					if ($userinfo['gender'] == "M"){
						echo "男";
					}
					else{
						echo "女";
					}
					?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">微信昵称</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['nickname']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">所在地区</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['country']." ".$userinfo['province']." ".$userinfo['city'] ?>
				</div>
			</div>
		</div>
		<br/>
		<div class="weui_cells_title">职业信息</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">所在单位</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['company']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">职位</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['position']; ?>
				</div>
			</div>
		</div>
		<br/>
		<div class="weui_cells_title">受教育信息</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">专业</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['major']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">学位</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['degree']; ?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">导师</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php echo $userinfo['teacher'];?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">入学/毕业</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php 
						echo $userinfo['date1']." ~ ".$userinfo['date2'];
					?>
				</div>
			</div>
		</div>
		<div class="weui_btn_area">
			<?php
				if (isset($donationid)){
					echo "<a class='weui_btn weui_btn_primary' href='./addfundout.php?donationid=".$donationid."&id=".$userinfo['id']."'>确定</a>";
				}
				else{
					echo "<a class='weui_btn weui_btn_primary' href='./addfundout.php?id=".$userinfo['id']."'>确定</a>";
				}
			?>
			<a href="./addfundout.php?" class="weui_btn weui_btn_default">取消</a>
		</div>
		<br/>
	</body>
</html>