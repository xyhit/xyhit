<?php
//https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID
if (isset($_GET['code'])){
	include ("./oauth_detail.php");
	$headimg = $userinfo['headimgurl'];
	for ($i = strlen($headimg); $headimg[$i] != "/" && $i >= 0; $i--);
	$headimg = substr($img, 0, $i + 1)."0";
	
	//判断是否已经注册过了
	include("conn.php");
	$tempid = $userinfo['openid'];
	$sql = mysql_query("select id from user where id='$tempid'", $db);
	if ($sql){
		if (mysql_num_rows($sql) > 0){
			header("Location: ./haveid.php");
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
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>用户注册</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
	</head>
	<body>
		<br/>
		<p align='center'><img height="100" width="100" src="<?php echo $userinfo['headimgurl']?>"></img></p>
		<div class="weui_cells_title">基本信息</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="nickname">昵称</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<label class="weui_label"><?php echo $userinfo['nickname'] ?></label>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd"><label class="weui_label" id="gender">性别</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<?php 
					if ($userinfo['sex'] == 1){
						echo "男";
					}
					else{
						echo "女";
					}
					?>
				</div>
			</div>
			<form name="form1" method="post" action="/register.php" onSubmit="return checkinput(this)">
			<?php
				echo "<input type='hidden' name='openid' value='".$userinfo['openid']."'></input>";
				echo "<input type='hidden' name='nickname' value='".$userinfo['nickname']."'></input>";
				echo "<input type='hidden' name='sex' value='".$userinfo['sex']."'></input>";
				echo "<input type='hidden' name='headimgurl' value='".$userinfo['headimgurl']."'></input>";
				if (!$userinfo['city'] || !$userinfo['province'] || !$userinfo['country']){
					echo 
					"<div class='weui_cell'>
						<div class='weui_cell_hd'>
							<label class='weui_label'>国家</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<input class='weui_input' type='text' name='country' placeholder='填写您所在的国家'></input>
						</div>
					</div>
					<div class='weui_cell'>
						<div class='weui_cell_hd'>
							<label class='weui_label'>省份</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<input class='weui_input' type='text' name='province' placeholder='填写您所在的省份'></input>
						</div>
					</div>
					<div class='weui_cell'>
						<div class='weui_cell_hd'>
							<label class='weui_label'>城市</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<input class='weui_input' type='text' name='city' placeholder='填写您所在的城市'></input>
						</div>
					</div>";
				}
				else{
					echo "<div class='weui_cell'>
				<div class='weui_cell_hd'><label class='weui_label' id='district'>所在地区</label></div>
					<div class='weui_cell_bd weui_cell_primary'>".$userinfo['country'].' '.$userinfo['province'].' '.$userinfo['city'].
					"</div>
				</div>";
					echo "<input type='hidden' name='country' value='".$userinfo['country']."'></input>";
					echo "<input type='hidden' name='province' value='".$userinfo['province']."'></input>";
					echo "<input type='hidden' name='city' value='".$userinfo['city']."'></input>";
				}
			?>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">真实姓名</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="name" placeholder="填写您的真实姓名"></input>
				</div>
			</div>
		</div>
		<div class="weui_cells_tips">默认信息跟随微信系统，如不正确，请从微信修改</div>
		<br/>
		<div class="weui_cells_title">教育经历</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">专业</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="major" placeholder="填写您的专业"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">入学时间</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="date" name="entime"></input>
					</label>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">毕业时间</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="date" name="grtime"></input>
					</label>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">取得学位</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="degree" placeholder="填写您所取得的学位"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">导师姓名</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="teacher" placeholder="填写您的导师姓名"></input>
				</div>
			</div>
		</div>
		
		<br/>
		<div class="weui_cells_title">工作经历</div>
		<div class="weui_cells weui_cells_form">
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">所在单位</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="company" placeholder="填写您所在的单位"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">职位</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="pos" placeholder="填写您的职位"></input>
				</div>
			</div>
		</div>
		
		<br/>
		<div class="weui_cells_title">填写验证码</div>
		<div class="weui_cells weui_cells_form">
			<div id="checkcodediv" class="weui_cell weui_vcode">
				<div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" id="mycheckcode" name="checkcode" placeholder="请输入验证码"/>
				</div>
				<div class="weui_cell_ft">
					<i id="errorpic"></i>
					<img id="checkpic" title="点击刷新" src="./checkcode.php" onclick="changing();">
				</div>
			</div>
		</div>
			
			<br/>
			<input class="weui_btn weui_btn_primary" type="submit" onclick="checkinput()"></input>
			</form>
			<br/>
	</body>
<script type="text/javaScript">
function checkinput(form){
	if (form.country.value=="")
	{
		alert("请输入所在国家!");
		form.country.focus();
		return(false);
	}
	if (form.province.value=="")
	{
		alert("请输入所在省份!");
		form.province.focus();
		return(false);
	}
	if (form.city.value=="")
	{
		alert("请输入所在城市!");
		form.city.focus();
		return(false);
	}
	if(form.name.value=="")
	{
	 alert("请输入真实姓名!");
	 form.name.focus();
	 return(false);
	}
	if(form.major.value=="")
	{
	 alert("请输入您的专业!");
	 form.major.focus();
	 return(false);
	}
	if(form.entime.value=="")
	{
	 alert("请输入您的入学时间!");
	 form.entime.focus();
	 return(false);
	}
	if(form.grtime.value=="")
	{
	 alert("请输入您的毕业时间!");
	 form.grtime.focus();
	 return(false);
	}
	if(form.degree.value=="")
	{
	 alert("请输入您取得的学位!");
	 form.degree.focus();
	 return(false);
	}
	if(form.teacher.value=="")
	{
	 alert("请输入您导师的姓名!");
	 form.teacher.focus();
	 return(false);
	}
	if(form.company.value=="")
	{
	 alert("请输入您所在的单位!");
	 form.company.focus();
	 return(false);
	}
	if(form.pos.value=="")
	{
	 alert("请输入您的职位!");
	 form.pos.focus();
	 return(false);
	}
	var checkcodeclass = document.getElementById(id="errorpic").getAttribute("class");
	if (checkcodeclass == ""){
		alert("验证码有误，请重新输入!");
		form.checkcode.focus();
		return(false);
	}
	return (true);
}
function changing(){
    document.getElementById('checkpic').src="./checkcode.php?"+Math.random();
}
</script>
<script type="text/javascript" src="./static/ajax_request.js"></script>
<script type="text/javascript">
var testinput = document.createElement('input');
var object = document.getElementById(id="mycheckcode");      
if('oninput' in testinput){  
    object.addEventListener("input",ajax_request,false);  
}else{  
    object.onpropertychange = ajax_request;  
} 
</script>
</html>