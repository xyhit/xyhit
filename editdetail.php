<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>
		<?php
		echo "修改信息";
		?>
		</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
	</head>
	<body>
	<form action="./edituserinfosql.php" method="post">
		<div class="weui_cells_title">修改信息</div>
	<?php
		if (isset($_GET['type']) && isset($_GET['id'])){
			$type = $_GET['type'];
			$id = $_GET['id'];
			echo "<input type='hidden' name='userid' value='$id'></input>";
			switch($type){
				case "name":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='name'>真实姓名</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='name' placeholder='填写您的真实姓名'></input>
								</div>
							</div>
						</div>";
					break;
				case "gender":
					include "./conn.php";
					$sql = mysql_query("select gender from user where id = '$id'", $db);
					if ($sql){
						$result = mysql_fetch_array($sql);
						$gender = $result['gender'];
					}
					else{
						echo "connect database error\n";
						exit;
					}
					echo "<div class='weui_cells weui_cells_radio'>
							<label class='weui_cell weui_check_label' for='x11'>
								<div class='weui_cell_bd weui_cell_primary'>
									<p>男</p>
								</div>
								<div class='weui_cell_ft'>";
								if ($gender == 'M'){
									echo "
									<input type='radio' class='weui_check' name='gender' id='x11' value='1' checked='checked'></input>";
								}
								else{
									echo "
									<input type='radio' class='weui_check' name='gender' id='x11' value='1'></input>";
								}
								echo "
									<span class='weui_icon_checked'></span>
								</div>
							</label>
							<label class='weui_cell weui_check_label' for='x12'>
								<div class='weui_cell_bd weui_cell_primary'>
									<p>女</p>
								</div>
								<div class='weui_cell_ft'>";
								if ($gender == 'M'){
									echo "
									<input type='radio' class='weui_check' name='gender' id='x12' value='2'></input>";
								}
								else{
									echo "
									<input type='radio' class='weui_check' name='gender' id='x12' value='2' checked='checked'></input>";
								}
								echo "
									<span class='weui_icon_checked'></span>
								</div>
							</label>
						</div>";
					break;
				case "nickname":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='nickname'>昵称</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='nickname' placeholder='填写您的昵称'></input>
								</div>
							</div>
						</div>";
					break;
				case "area":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='country'>国家</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='country' placeholder='填写您所在的国家'></input>
								</div>
							</div>";
					echo "	<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='province'>所在省份</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='province' placeholder='填写您所在的省份'></input>
								</div>
							</div>";
					echo "	<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='city'>所在城市</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='city' placeholder='填写您所在的城市'></input>
								</div>
							</div>
						</div>";
					break;
				case "company":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='company'>单位</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='company' placeholder='填写您所在的单位'></input>
								</div>
							</div>
						</div>";
					break;
				case "position":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='position'>所在职位</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='position' placeholder='填写您所在的职位'></input>
								</div>
							</div>
						</div>";
					break;
				case "degree":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='degree'>取得学位</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='degree' placeholder='填写您取得的学位'></input>
								</div>
							</div>
						</div>";
					break;
				case "major":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='major'>专业</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='major' placeholder='填写您所学的专业'></input>
								</div>
							</div>
						</div>";
					break;
				case "teacher":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='teacher'>导师姓名</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='text' name='teacher' placeholder='填写您导师的姓名'></input>
								</div>
							</div>
						</div>";
					break;
				case "entime":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='entime'>入学时间</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='date' name='entime'></input>
								</div>
							</div>
						</div>";
					break;
				case "grtime":
					echo "<div class='weui_cells weui_cells_form'>
							<div class='weui_cell'>
								<div class='weui_cell_hd'><label class='weui_label' id='grtime'>毕业时间</label></div>
								<div class='weui_cell_bd weui_cell_primary'>
									<input class='weui_input' type='date' name='grtime'></input>
								</div>
							</div>
						</div>";
					break;
			}
		}
		else{
			echo "GET PARA ERROR";
			exit;
		}
	?>
	</div>
	<div class="weui_btn_area">
        <input class="weui_btn weui_btn_primary" type="submit" id="showTooltips"></input>
    </div>
	</form>
</body>
</html>