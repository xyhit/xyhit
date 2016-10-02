<?php
//https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID
namespace LaneWeChat\User_Module;
session_start();
use LaneWeChat\Core\SqlQuery;
include_once "./config.php";
require (ROOT_DIR."/core/sqlquery.lib.php");

if (isset($_GET['code']) || (isset($_SESSION['AUTH']) && $_SESSION['AUTH'])){
	if (isset($_SESSION['AUTH']) && $_SESSION['AUTH']){
		$userinfo = array();
		$userinfo['openid'] = $_SESSION['openid'];
		$userinfo['headimgurl'] = $_SESSION['headimgurl'];
		$userinfo['nickname'] = $_SESSION['nickname'];
		$userinfo['sex'] = $_SESSION['sex'];
		$userinfo['country'] = $_SESSION['country'];
		$userinfo['province'] = $_SESSION['province'];
		$userinfo['city'] = $_SESSION['city'];
	}
	else{
		session_destroy();
		session_start();
		include (ROOT_DIR."/oauth_detail.php");
		$_SESSION['openid'] = $userinfo['openid'];
		$_SESSION['headimgurl'] = $userinfo['headimgurl'];
		$_SESSION['nickname'] = $userinfo['nickname'];
		$_SESSION['sex'] = $userinfo['sex'];
		$_SESSION['country'] = $userinfo['country'];
		$_SESSION['province'] = $userinfo['province'];
		$_SESSION['city'] = $userinfo['city'];
		$_SESSION['AUTH'] = true;
	}
	
	$headimg = $userinfo['headimgurl'];
	for ($i = strlen($headimg) - 1; $headimg[$i] != "/" && $i >= 0; $i--);
	$headimg = substr($headimg, 0, $i + 1)."96";
	
	//判断是否已经注册过了
	$result = SqlQuery::query("user", array("openid"), array("openid"=>$userinfo['openid']));
	if($result[0] == -1){
		echo 'SQL Error: '.$result[1];
		exit;
	}
	else if (count($result[1]) > 0){
		header("Location: ./haveid.php");
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
		<link rel="stylesheet" href="<?= ROOT_DIR ?>/static/weui/dist/style/weui.css"/>
	</head>
	<body>
		<br/>
		<p align='center'><img src="<?php echo $headimg?>"></img></p>
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
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">真实姓名</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" name="name" placeholder="填写您的真实姓名"></input>
				</div>
			</div>
			<form name="form1" method="post" action="./register.php" onSubmit="return checkinput(this)">
			<?php
				echo "<input type='hidden' name='openid' value='".$userinfo['openid']."'></input>";
				echo "<input type='hidden' name='nickname' value='".$userinfo['nickname']."'></input>";
				echo "<input type='hidden' name='sex' value='".$userinfo['sex']."'></input>";
				echo "<input type='hidden' name='headimgurl' value='".$headimg."'></input>";
				if (!$userinfo['city'] || !$userinfo['province'] || !$userinfo['country']){
					echo 
					"<div class='weui_cell weui_cell_select'>
						<div class='weui_cell_hd'>
							<label for='' class='weui_label' style='padding-left: 15px;'>国家</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<select class='weui_select' name='select2'>
								<option value='1'>中国</option>
								<option value='2'>美国</option>
								<option value='3'>英国</option>
							</select>
						</div>
					</div>
					<div class='weui_cell weui_cell_select'>
						<div class='weui_cell_hd'>
							<label for='' class='weui_label' style='padding-left: 15px;'>省份</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<select class='weui_select' name='select2'>
								<option value='1'>中国</option>
								<option value='2'>美国</option>
								<option value='3'>英国</option>
							</select>
						</div>
					</div>
					<div class='weui_cell weui_cell_select'>
						<div class='weui_cell_hd'>
							<label for='' class='weui_label' style='padding-left: 15px;'>城市</label>
						</div>
						<div class='weui_cell_bd weui_cell_primary'>
							<select class='weui_select' name='select2'>
								<option value='1'>中国</option>
								<option value='2'>美国</option>
								<option value='3'>英国</option>
							</select>
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
		</div>
		<div class="weui_cells_tips">默认信息跟随微信系统，如不正确，请从微信修改</div>
		<br/>
		<div class="weui_cells_title">教育经历</div>
		<div class="weui_cells weui_cells_form">
			<div class='weui_cell weui_cell_select'>
				<div class='weui_cell_hd'>
					<label for='' class='weui_label' style='padding-left: 15px;'>最高学位</label>
				</div>
				<div class='weui_cell_bd weui_cell_primary'>
					<select class='weui_select' name='select2'>
						<option value='1'>本科</option>
						<option value='2'>硕士</option>
						<option value='3'>博士</option>
						<option value='3'>博士后</option>
					</select>
				</div>
			</div>
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
<script type="text/javascript">
function ajax_request(){
	var checkcode = document.getElementById(id="mycheckcode").value;
	if (checkcode.length < 4){
		document.getElementById(id="errorpic").setAttribute("class", "non");
		return;
	}
	var xmlhttp;
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	<?php
		echo "var wechat_url = '".WECHAT_URL."';";
	?>
	xmlhttp.open("get", wechat_url+"/user_module/checkcodeajax.php?checkcode="+checkcode, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var recv = xmlhttp.responseText;
			if (recv != ""){
				document.getElementById(id="errorpic").setAttribute("class", "weui_icon_success");
			}
			else{
				document.getElementById(id="errorpic").setAttribute("class", "");
			}
		}
	}
}
</script>
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