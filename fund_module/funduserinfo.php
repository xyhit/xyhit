<?php
	if (isset($_GET['id']) && isset($_GET['time'])){
		$userid = $_GET['id'];
		$strtime = $_GET['time'];
		$time = date("Y-m-d H:i:s", $strtime);
		
		include "./conn.php";
		$sql = mysql_query("select * from user where id='$userid'", $db);
		$fundsql = mysql_query("select * from fund where id='$userid' and time='$time'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				$result = mysql_fetch_array($sql);
				$registered = true;
			}
			else{
				$registered = false;
			}			
		}
		else{
			echo "cannot connect database!\n";
		}
		if ($fundsql){
			//echo mysql_num_rows($fundsql);
			$fundUser = mysql_fetch_array($fundsql);
		}
		else{
			echo "cannot connect database!\n";
		}
	}
	else{
		echo "NO-GET-CODE-ERROR";
		exit;
	}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>捐款意向</title>
    <link rel="stylesheet" href="./weui/dist/style/weui.css">
    <link rel="stylesheet" href="./weui/dist/example/example.css">
</head>
<body>
	<div class="weui_cells_title">消息详情</div>
	<div class="weui_cells">
		<div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>用户ID</p>
            </div>
            <div class="weui_cell_ft"><?php echo $fundUser['id'];?></div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>姓名</p>
            </div>
            <div class="weui_cell_ft"><?php echo $fundUser['name'];?></div>
        </div>
		<div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>消息时间</p>
            </div>
            <div class="weui_cell_ft"><?php echo $fundUser['time'];?></div>
        </div>
		<div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>联系电话</p>
            </div>
            <div class="weui_cell_ft"><?php echo $fundUser['phone'];?></div>
        </div>
		<div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>邮箱地址</p>
            </div>
            <div class="weui_cell_ft"><?php echo $fundUser['email'];?></div>
        </div>
    </div>
	<?php
		if ($registered == true){
			$userinfourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$userid)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
			$imgsrc = "http://xyhit-headimg.stor.sinaapp.com/".$userid.".png";
			echo "
				<div class='weui_cells_title'>该用户是注册用户</div>
				<div class='weui_cells weui_cells_access'>
					<a class='weui_cell' href='".$userinfourl."'>
						<div class='weui_cell_hd'><img src='".$imgsrc."' alt='' style='width:20px;margin-right:5px;display:block'></div>
						<div class='weui_cell_bd weui_cell_primary'>
						</div>
						<div class='weui_cell_ft'>用户详情</div>
					</a>
				</div>
			";
		}
	?>
	<br/>
	<div class='weui_cells_title'>如果捐款协议已达成，请点击这里去添加捐款条目</div>
	<div class="weui_btn_area">
        <?php echo "<a class='weui_btn weui_btn_primary' href='./addfund.php?id=".$fundUser['id']."&time=".$strtime."' id='showTooltips'>";
		?>
		去添加</a>
    </div>
</body>
</html>