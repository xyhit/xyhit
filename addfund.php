<?php
	if (isset($_GET['id']) && isset($_GET['time'])){
		$id = $_GET['id'];
		$strtime = $_GET['time'];
		$time = date("Y-m-d H:i:s", $strtime);
		include "./conn.php";
		$sql = mysql_query("select * from fund where id='$id' and time='$time'", $db);
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
		<title>添加一条捐款信息</title>
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
					<input class="weui_input" type="text" id="project" placeholder='款项名称，如 “***奖学金”'></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">用户ID</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					if (isset($id)){
						echo $id;
					}
					else{
						echo "<input class='weui_input' type='text' id='userid' placeholder='捐款人用户ID'></input>";
					}
				?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">捐款人</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					if (isset($result['name'])){
						echo "<input class='weui_input' type='text' id='name' placeholder='捐款人姓名' value='".$result['name']."'></input>";
					}
					else{
						echo "<input class='weui_input' type='text' id='name' placeholder='捐款人姓名'></input>";
					}
				?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">捐款金额</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="text" id="money" placeholder="捐款金额(元)"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">时间</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
					<input class="weui_input" type="date" id="time" placeholder="奖学金设立时间"></input>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">联系电话</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					if (isset($result['phone'])){
						echo "<input class='weui_input' type='text' id='phone' placeholder='捐款人联系电话' value='".$result['phone']."'></input>";
					}
					else{
						echo "<input class='weui_input' type='text' id='phone' placeholder='捐款人联系电话'></input>";
					}
				?>
				</div>
			</div>
			<div class="weui_cell">
				<div class="weui_cell_hd">
					<label class="weui_label">邮箱地址</label>
				</div>
				<div class="weui_cell_bd weui_cell_primary">
				<?php
					if (isset($result['email'])){
						echo "<input class='weui_input' type='text' id='email' placeholder='捐款人Email' value='".$result['email']."'></input>";
					}
					else{
						echo "<input class='weui_input' type='text' id='email' placeholder='捐款人Email'></input>";
					}
				?>
				</div>
			</div>
		</div>
		<br/>
		<div class="weui_cells_title">奖励条件</div>
		<div class="weui_cells" id='prize'>
			<div class="weui_cell">
				<div class="weui_cell_bd weui_cell_primary">
					<p>奖励条件</p>
				</div>
				<div class="weui_cell_ft"><a onclick="showDialog()" class="weui_btn weui_btn_mini weui_btn_primary">添加</a></div>
			</div>
		</div>
		<div class="weui_btn_area">
			<a href="javascript:;" class="weui_btn weui_btn_primary" id="showDialog2" onclick="showDialog2()">保存</a>
		</div>
		<!--BEGIN dialog1-->
		<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
			<div class="weui_mask"></div>
			<div class="weui_dialog">
				<div class="weui_dialog_hd"><strong class="weui_dialog_title">填写奖励条件</strong></div>
				<div class="weui_dialog_bd">
				<form name='form1'>
					<div class="weui_cell">
						<div class="weui_cell_bd weui_cell_primary">
							<textarea class="weui_textarea" placeholder="请输入内容" rows="3" id="condition"></textarea>
							<div class="weui_textarea_counter"><span id="count">0</span>/200</div>
						</div>
					</div>
				</div>
				<div class="weui_dialog_ft">
					<a class="weui_btn_dialog default" onclick="hideDialog()">取消</a>
					<a class="weui_btn_dialog primary" onclick='addcondition()'>确定</a>
				</div>
			</div>
		</div>
		<!--END dialog1-->
		<!--BEGIN dialog2-->
			<div class="weui_dialog_confirm" id="dialog2" style="display: none;">
				<div class="weui_mask"></div>
				<div class="weui_dialog">
					<div class="weui_dialog_hd"><strong class="weui_dialog_title">信息确认</strong></div>
					<div class="weui_dialog_bd">
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">项目</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertproject"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">用户ID</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertuserid"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">捐款人</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertname"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">捐款金额</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertmoney"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">时间</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alerttime"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">电话</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertphone"></div>
						</div>
						<div class="weui_cell">
							<div class="weui_cell_hd">
								<label class="weui_label">邮箱</label>
							</div>
							<div class="weui_cell_bd weui_cell_primary" id="alertemail"></div>
						</div>
					</div>
					<div class="weui_dialog_ft">
						<a class="weui_btn_dialog default" onclick="hideDialog2()">取消</a>
						<a class="weui_btn_dialog primary" onclick='submitForm()'>确定</a>
					</div>
				</div>
			</div>
			<!--END dialog2-->
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
	<script type="text/javascript">
	
	$(function(){
	  var max = 200;
	  $('#condition').on('input', function(){
		 var text = $(this).val();
		 var len = text.length;
		
		 $('#count').text(len);
		
		 if(len > max){
		   $(this).closest('.weui_cell').addClass('weui_cell_warn');
		 }
		 else{
		   $(this).closest('.weui_cell').removeClass('weui_cell_warn');
		 }
		 
	  });
	})
	
	function showDialog(){
		$("#dialog1").removeAttr("style");
	}
	function hideDialog(){
		$("#dialog1").attr("style","display: none;");
	}
	function showDialog2(){
		<?php
			if (isset($id)){
				echo "var id = '$id';";
			}
			else{
				echo "var id = document.getElementById('userid').value;";
			}
		?>
		var project = document.getElementById("project").value;
		var name = document.getElementById("name").value;
		var money = document.getElementById("money").value;
		var projecttime = document.getElementById("time").value;
		var phone = document.getElementById("phone").value;
		var email = document.getElementById("email").value;
		document.getElementById("alertuserid").innerHTML = id;
		document.getElementById("alertproject").innerHTML = project;
		document.getElementById("alertname").innerHTML = name;
		document.getElementById("alertmoney").innerHTML = money;
		document.getElementById("alerttime").innerHTML = projecttime;
		document.getElementById("alertphone").innerHTML = phone;
		document.getElementById("alertemail").innerHTML = email;
		
		$("#dialog2").removeAttr("style");
	}
	function hideDialog2(){
		$("#dialog2").attr("style","display: none;");
	}
	function deletehtml(currenthtml){
		var html = currenthtml.parentNode.parentNode;
		html.remove();
	}
	function addcondition(){
		var content = document.getElementById("condition").value;
		if (content.length > 200){
			alert("文字数量太大！");
			return;
		}
		content=content.replace(/\n/ig,"\n<br/>");
		if (content != ''){
			var insert_html = "<div class='weui_cell'><div class='weui_cell_bd weui_cell_primary' contenteditable='false' name='conditioncontent' style='outline:none; min-height:30px; color:#999999; font-size:13px;'>" + content + "</div>";
			
			insert_html += "<div class='weui_cell_ft'><a onclick='deletehtml(this)' class='weui_btn weui_btn_mini weui_btn_default'>删除</a></div></div>";
			document.getElementById(id="prize").insertAdjacentHTML("beforeEnd", insert_html);
			document.getElementById("condition").value="";
		}
		hideDialog();
	}
	function submitForm(){
		<?php
			if (isset($id)){
				echo "var id = '$id';";
			}
			else{
				echo "var id = document.getElementById('userid').value;";
			}
		?>
		var project = document.getElementById("project").value;
		var name = document.getElementById("name").value;
		var money = document.getElementById("money").value;
		var projecttime = document.getElementById("time").value;
		var phone = document.getElementById("phone").value;
		var email = document.getElementById("email").value;
		var conditionlist = document.getElementsByName("conditioncontent");
		var temp = '';
		for (var i = 0; i < conditionlist.length; i ++){
			tempContent = conditionlist[i].textContent;
			tempContent = tempContent.replace(/\+/g, "%2B");          //正文转义“+”
			tempContent = tempContent.replace(/\&/g, "%26");          //正文转义“&”
			temp += tempContent;
			if (i + 1 != conditionlist.length){
				temp += "\n";
			}
		}
		
		var postStr = "id=" + id + "&project=" + project + "&name=" + name + "&phone=" + phone + "&email=" + email + "&money=" + money + "&date=" + projecttime + "&conditionlist=" + temp;	
		var xmlhttp;
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("post", "http://1.xyhit.applinzi.com/processFundRecord.php", true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send(postStr);
		
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				$("#toast").removeAttr("style");
				var recv = xmlhttp.responseText;
				setTimeout("document.getElementById('toast').setAttribute('style','display: none;')", 1000);
				setTimeout("window.location.href='http://1.xyhit.applinzi.com/receivedMsg.php'", 1200);
			}
		}
	}
	</script>
</html>