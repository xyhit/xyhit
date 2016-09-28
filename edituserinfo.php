<?php
	if (isset($_GET['code'])){
		include ("./oauth_base.php");
		include ("./conn.php");
		$sql = mysql_query("select id, gender, province, city, date_format(entime, '%Y-%m-%d') as entime, date_format(grtime, '%Y-%m-%d') as grtime, degree, teacher, name, nickname, country, company, position, major from user where id='".$access_token['openid']."'", $db);
		if ($sql){
			if (mysql_num_rows($sql) == 0){
				header("Location: ".'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri='.urlencode("http://1.xyhit.applinzi.com/oauth2.php").'&response_type=code&scope=snsapi_userinfo&state=125#wechat_redirect');
				exit;
			}
			$userinfo = mysql_fetch_array($sql);
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
	else{
		echo "NO-CODE-ERROR";
	}
?>

<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>
		<?php
		echo "个人信息";
		?>
		</title>
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
		<div class="weui_cells weui_cells_access">
			<?php echo "<a class='weui_cell' href='./qrcode.php?id=".$userinfo['id']."&fromself=1'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>我的二维码</p>
				</div>
				<div class="weui_cell_ft">点击查看
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=name&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>姓名</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['name'];?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=gender&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>性别</p>
				</div>
				<div class="weui_cell_ft">
				<?php
					if ($userinfo['gender'] == "M"){
						echo "男";
					}
					else{
						echo "女";
					}
				?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=nickname&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>微信昵称</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['nickname'];?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=area&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>所在地区</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['country']." ".$userinfo['province']." ".$userinfo['city']; ?>
				</div>
			</a>
		</div>
		<br/>
		<div class="weui_cells_title">职业信息</div>
		<div class="weui_cells weui_cells_access">
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=company&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>所在单位</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['company']; ?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=position&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>职位</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['position']; ?>
				</div>
			</a>
		</div>
		<br/>
		<div class="weui_cells_title">受教育信息</div>
		<div class="weui_cells weui_cells_access">
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=major&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>专业</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['major']; ?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=degree&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>学位</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['degree']; ?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=teacher&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>导师</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['teacher']; ?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=entime&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>入学时间</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['entime']; ?>
				</div>
			</a>
			<?php echo "<a class='weui_cell' href='./editdetail.php?type=grtime&id=".$userinfo['id']."'>"; ?>
				<div class="weui_cell_bd weui_cell_primary">
					<p>毕业时间</p>
				</div>
				<div class="weui_cell_ft"><?php echo $userinfo['grtime']; ?>
				</div>
			</a>
		</div>
		<br/>
		<div class="weui_cells_title">标记信息</div>
			<div class="weui_cells weui_cells_access">
				<?php echo "<a href='./marklist.php?userid=".$userinfo['id']."' class='weui_cell'>";
				?>
					<div class="weui_cell_bd weui_cell_primary">
						<p>认识我的人</p>
					</div>
					<?php
						$sql = mysql_query("select markerid from mark where userid='".$userinfo['id']."' order by time asc", $db);
						if ($sql){
							$counter = 0;
							while (($re = mysql_fetch_array($sql)) && $counter < 2){
								$marker = $re['markerid'];
								echo "<div class='weui_cell_hd'><img name='markimg' src='http://xyhit-headimg.stor.sinaapp.com/$marker.png' alt='' style='width:20px;margin-right:5px;display:block'></img>
									</div>";
								$counter ++;
							}
						}
					?>
					<div class="weui_cell_ft_new">
					<?php
						echo mysql_num_rows($sql);
					?></div>
				</a>
				<?php echo "<a href='./mymark.php?userid=".$userinfo['id']."' class='weui_cell'>";
				?>
					<div class="weui_cell_bd weui_cell_primary">
						<p>我认识的人</p>
					</div>
					<?php
						$sql = mysql_query("select userid from mark where markerid='".$userinfo['id']."' order by time asc", $db);
						if ($sql){
							$counter = 0;
							while (($re = mysql_fetch_array($sql)) && $counter < 2){
								$markeduser = $re['userid'];
								echo "<div class='weui_cell_hd'><img name='markimg' src='http://xyhit-headimg.stor.sinaapp.com/$markeduser.png' alt='' style='width:20px;margin-right:5px;display:block'></img>
									</div>";
								$counter ++;
							}
						}
					?>
					<div class="weui_cell_ft_new">
					<?php
						echo mysql_num_rows($sql);
					?></div>
				</a>
			</div>
			<br/>
	</body>
</html>