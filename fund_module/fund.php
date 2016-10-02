<?php
	namespace LaneWeChat\FundModule;
    use LaneWeChat\Core\SqlQuery;
    
	require ("../core/pagesplit.lib.php");
    require (ROOT_DIR."/core/sqlquery.lib.php");
	use LaneWeChat\Core\Mypage;
	if (isset($_GET['code'])){
		include (ROOT_DIR."/oauth_base.php");
		//echo $access_token['openid'];
		$id = $access_token['openid'];
		include (ROOT_DIR"/conn.php");
        
        $result = SqlQuery::query(
            'user', 
            array('name'), 
            array(
                'id'=>"$id"
                )
            );
        if($result[0] == -1){
            echo 'SQL Error: '.result[1];
            exit;
        }
        else{
            if(count($result[1]) < 1){
                $user_exist = false;
            } 
            else{
                $user_exist = true;
                $name = $result[1][0]['name'];
            }
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
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
		<meta name="apple-mobile-web-app-capable" content="yes">
        <title>捐赠信息</title>
        <link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
		<link rel="stylesheet" href="./weui/dist/style/CSSTableGenetor.css">
		
    </head>
    <body>
		<div class='CSSTableGenerator'>
		<table>
				<?php
                    $results = SqlQuery::query(
                        'donation',
                        array('name', 'money', 'id', 'projectname', 'time'),
                        array(),
                        'money',
                        1
                        );
                    if($results[0] == -1){
                        echo 'SQL Error'.$results[1];
                        exit;
                    }
                    $num = count($results[1]);
                    $pageobj = new Mypage($num, 20);
                    if ($num <= 0){
						echo "<h3 align='center'>暂无条目</h3>";
					}
                    else{
                        $order = ((int)$pageobj->page - 1) * 20;
						$counter=0;
                        echo "<tr><td>序号</td><td>捐赠人</td><td>项目名称</td><td>金额</td><td>查看</td><td>流向</td></tr>";
                        while(counter < 20){
                            try{
                                $index = $order + $counter;
                                $name = $results[1][$index]['name'];
                                $projectname = $results[1][$index]['projectname'];
                                $money = $results[1][$index]['money'];
                                $fund_id = $results[1][$index]['id'];
                                echo "
                                <tr>
                                    <td>$index</td>
                                    <td>$name</td>
                                    <td>$projectname</td>
                                    <td>$money</td>
                                    <td> <a href=\"./view_fund.php?id=$fund_id\">详情</a></td>
                                    <td> <a href=\"./fundoutyear.php?id=$fund_id\">详情</a></td>
                                </tr>
                                ";
                            }catch(Exception $e){
                                break;
                            }
                            counter++;
                        }
                    }
		</table>
			<!--</form>-->
		</div>
		<?php
			echo "<div class='weui_panel weui_panel_access'>
					<div class='weui_panel_hd' alige = 'center'>";
			echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
			echo "</div>
				</div>";
		?>
		<br/>
			<div class="weui_btn_area">
				<a href="javascript:;" class="weui_btn weui_btn_primary" id="showDialog1" onclick="showDialog()">我要捐款</a>
			</div>
			<div class="dialog">
			<!--BEGIN dialog1-->
			<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
				<div class="weui_mask"></div>
				<div class="weui_dialog">
					<div class="weui_dialog_hd"><strong class="weui_dialog_title">信息确认</strong></div>
					<div class="weui_dialog_bd">请确认您的信息，方便我们与您联系：
					<br/>
					<form name='form1'>
					姓名：<?php
								if ($user_exist == true)
									echo "<input type='text' name='name' value='$name'></input>";
								else{
									echo "<input type='text' name='name' value=''></input>";
								}
							?>
					<br/>
					电话：<input type="text" name='phone' value=''></input>
					<br/>
					邮箱：<input type="text" name='email' value=''></input>
					</div>
					<div class="weui_dialog_ft">
						<a class="weui_btn_dialog default" onclick="hideDialog()">取消</a>
						<a class="weui_btn_dialog primary" onclick='submitForm()'>确定</a>
					</div>
				</div>
			</div>
			<!--END dialog1-->
			</div>
		</body>
	<script src="./static/jquery.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
	<script type="text/javascript">
	function showDialog(){
		$("#dialog1").removeAttr("style");
	}
	function hideDialog(){
		$("#dialog1").attr("style","display: none;");
	}
	function submitForm(){
		var form = document.form1;
		if (form.name.value=="")
		{
			alert("请输入您的姓名!");
			form.name.focus();
			return(false);
		}
		if (form.phone.value=="")
		{
			alert("请输入您的电话!");
			form.phone.focus();
			return(false);
		}
		if (form.email.value=="")
		{
			alert("请输入您的Email!");
			form.email.focus();
			return(false);
		}
		<?php
			echo "var openid = '$id';";
		?>
		var name = form.name.value;
		var phone = form.phone.value;
		var email = form.email.value;
		var postStr = "openid=" + openid + "&name=" + name + "&phone=" + phone + "&email=" + email;	
		var xmlhttp;
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.open("post", "http://1.xyhit.applinzi.com/processFund.php", true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send(postStr);
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var recv = xmlhttp.responseText;
				wx.closeWindow();
			}
		}
	}
	</script>
</html>