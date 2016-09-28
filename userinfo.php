<?php
	session_start();
	include ("./conn.php");
	if (isset($_GET['code'])){
		include_once ("./oauth_base.php");
		//$access_token['openid']
		if (isset($access_token['openid']))
			$sql = mysql_query("select count(*) as c1 from user where id='".$access_token['openid']."'", $db);
		else{
			echo "<html><head><title>找不到页面啦</title></head><body><h2>该页面不存在，请重新获取</h2></body></html>";
			exit;
		}
		if ($sql){
			$result = mysql_fetch_array($sql);
			if ($result['c1'] == 0 && isset($_GET['id'])){
				$_SESSION['mark_id'] = $_GET['id'];
				//'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri='.urlencode("http://1.xyhit.applinzi.com/oauth2.php").'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'
				header("Location: ".'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri='.urlencode("http://1.xyhit.applinzi.com/oauth2.php").'&response_type=code&scope=snsapi_userinfo&state=125#wechat_redirect');
				exit;
			}
		}
		
		if (isset($_GET['id'])){
			$id = $_GET['id'];
			$sql = mysql_query("select id, gender, province, city, date_format(entime, '%Y-%c') as date1, date_format(grtime, '%Y-%c') as date2, degree, teacher, name, nickname, country, company, position, major from user where id='$id'", $db);
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
			echo "NO-USERID-ERROR";
			exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>
		<?php
		echo $userinfo['name']."的个人信息";
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
		<br/>
		<div class="weui_cells_title">
		<?php
			if ($userinfo['gender'] == "M"){
				echo "谁标记了认识他";
			}
			else{
				echo "谁标记了认识她";
			}
		?>
		</div>
			<div class="weui_cells weui_cells_access">
				<?php echo "<a href='./marklist.php?userid=$id' class='weui_cell'>";
				?>
					<div class="weui_cell_bd weui_cell_primary">
						<p>用户标记</p>
					</div>
					<?php
						$sql = mysql_query("select markerid from mark where userid='$id' order by time asc", $db);
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
					<div class="weui_cell_ft_new" id="mark_number">
					<?php
						echo mysql_num_rows($sql);
					?></div>
				</a>
			</div>
			<br/>
			<?php
				$sql = mysql_query("select markerid from mark where userid='$id' and markerid='".$access_token['openid']."'", $db);
				if ($sql){
					if (mysql_num_rows($sql) <= 0){
						echo "<a class='weui_btn weui_btn_primary' id='ensure' onclick='mark()'>";
						if ($userinfo['gender'] == "M"){
							echo "我也认识他";
						}
						else{
							echo "我也认识她";
						}
					}
					else{
						echo "<a class='weui_btn weui_btn_default' id='ensure' onclick='mark()'>取消标记";
					}
				}
			?>
			</a>
			<br/>
	</body>
	<script type="text/javascript">
	function mark(){		
		if (document.getElementById(id="ensure").getAttribute("class") == "weui_btn weui_btn_primary"){
			
			var mark_number = document.getElementById(id="mark_number").innerHTML;
			var new_mark_number = parseInt(mark_number) + 1;
			<?php 
				echo "var userid = '$id';";
				echo "var markerid = '".$access_token['openid']."';";
			?>
			var postStr = "mark_number=" + new_mark_number.toString() + "&userid=" + userid + "&markerid=" + markerid + "&markflag=1";	
			var xmlhttp;
			if (window.XMLHttpRequest){
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else {
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("post", "http://1.xyhit.applinzi.com/altermarknumber.php", true);
			xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xmlhttp.send(postStr);
			
			
			document.getElementById(id="mark_number").innerHTML = new_mark_number.toString();
			
			document.getElementById(id="ensure").setAttribute("class", "weui_btn weui_btn_default");
			document.getElementById(id="ensure").innerHTML = "取消标记";
			
			<?php echo "insert_html = \"<div class='weui_cell_hd'><img name='markimg' src='"."http://xyhit-headimg.stor.sinaapp.com/".$access_token['openid'].".png"."' alt='' style='width:20px;margin-right:5px;display:block'></img></div>\"";?>;
			document.getElementById(id="mark_number").insertAdjacentHTML("beforeBegin", insert_html);
		}
		else{
			
			var mark_number = document.getElementById(id="mark_number").innerHTML;
			var new_mark_number = parseInt(mark_number) - 1;
			<?php 
				echo "var userid = '$id';";
				echo "var markerid = '".$access_token['openid']."';";
				echo "\n";
			?>
			var postStr = "mark_number=" + new_mark_number.toString() + "&userid=" + userid + "&markerid=" + markerid + "&markflag=0";	
			var xmlhttp;
			if (window.XMLHttpRequest){
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else {
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("post", "http://1.xyhit.applinzi.com/altermarknumber.php", true);
			xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xmlhttp.send(postStr);

			document.getElementById(id="mark_number").innerHTML = new_mark_number.toString();
			document.getElementById(id="ensure").setAttribute("class", "weui_btn weui_btn_primary");
			
			<?php echo "var marker = '".$access_token['openid']."';";
			?>
			
			var markImgList = document.getElementsByName("markimg");
			for (var i=0; i<markImgList.length; i++){
				var temp = markImgList[i].getAttribute("src").split("/").pop();
				if (temp == marker + ".png"){
					markImgList[i].parentNode.removeChild(markImgList[i]);;
					break;
				}
			}
			
			<?php
				if ($userinfo['gender'] == "M"){
					echo "document.getElementById(id='ensure').innerHTML = '"."我也认识他"."';";
				}
				else{
					echo "document.getElementById(id='ensure').innerHTML = '"."我也认识她"."';";
				}
			?>
			
		}
	}
	</script>
</html>