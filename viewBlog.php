<?php
	namespace LaneWeChat;
	require ("./core/pagesplitWithAnchors.lib.php");
	use LaneWeChat\Core\MypageWithAnchors;
	
	include "./conn.php";
	function object_array($array){
		if(is_object($array)){
			$array = (array)$array;
		}
		if(is_array($array)){
			foreach($array as $key=>$value){
				$array[$key] = object_array($value);
			}
		}
		return $array;
	} 
	session_start();
	use sinacloud\sae\Storage as Storage;
	if (isset($_GET['id'])){
		$s = new Storage;
		$blogId = $_GET['id'];
		$array = object_array($s->getObject("blog", $blogId.".txt"));
		//echo $array["body"];
		$blogContent = $array["body"];        //从SAE加载正文
		
		$sql = mysql_query("select blogid, userid, mark, title, date_format(time, '%Y-%m-%d') as publishdate from blog where blogid='$blogId'", $db);
		if ($sql){
			$result = mysql_fetch_array($sql);
			$userid = $result['userid'];
			if (isset($userid)){
				$pubishersql = mysql_query("select id, name from user where id='$userid'", $db);
				if ($pubishersql){
					if (mysql_num_rows($pubishersql) > 0){
						$publisherResult = mysql_fetch_array($pubishersql);
						$ifregister = true;
						$publiser = $publisherResult['name'];
					}
					else{
						$ifregister = false;
					}
				}
				else{
					echo mysql_errno() . ": " . mysql_error(). "\n";
					exit;
				}
			}
			else{
				echo "NOT-SET-USERID-ERROR;\n";
				exit;
			}
		}
		else{
			echo mysql_errno() . ": " . mysql_error(). "\n";
			exit;
		}
	}
	else{
		echo "NO-GET-CODE-ERROR";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
	<title>动态浏览</title>
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览 -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui">
	<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="./weui/dist/style/weuiShortLabel.css"/>
	<link rel="stylesheet" href="./artEditor/css/viewblog.css">
</head>
<body>
<div style="width:320px;margin: 0 auto;">
	<div class="publish-article-title">
		<div class="title-tips" align="center"><?php echo $result['title'];?></div>
		<ul class="weui_media_info_new">
			<li class="weui_media_info_meta_new"><?php 
			if ($ifregister == true){
				$userinfourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$userid)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
				echo "<a href='".$userinfourl."'>$publiser</a>";
			}
			else{
				echo "用户".$userid;
			}
			?></li>
			<li class="weui_media_info_meta_new weui_media_info_meta_extra_new"><?php
			switch ($result['mark']){
				case "A":
					echo "个人动态";
					break;
				case "Q":
					echo "疑难问题";
					break;
				case "S":
					echo "招生信息";
					break;
				case "E":
					echo "招聘信息";
					break;
				default:
					echo "未分类";
			}
			?></li>
			<li class="weui_media_info_meta_new weui_media_info_meta_extra_new"><?php echo $result['publishdate'];?></li>
		</ul>
	</div>
	<div class="publish-article-title">
		<div class="article-content" id="content">
			<?php
				echo $blogContent;
			?>
		</div>
	</div>
	<div class="publish-article-content">
		<div class="footer-btn g-image-upload-box">
			<div class="upload-button" onclick="onclickcomment()">
				<span class="upload"><i class="comment-img"></i>写评论</span>
			</div>
		</div>
	</div>
	<div class="publish-article-title">
		<div class="title-tips" id="commentarea">评论区</div>
		<div class="article-content" id="content">
			<div class="weui_cells" id="addCommentPostion">
			<!--评论 start -->
			<?php
				$commentsql = mysql_query("select commenterid, date_format(time, '%Y-%m-%d %H:%i') as time, content from blogcomment where blogid='$blogId' order by(time) desc", $db);
				if ($commentsql){
					$commentNum = mysql_num_rows($commentsql);
					$pageobj = new MypageWithAnchors($commentNum, 15);
					if ($commentNum > 15)
						mysql_data_seek($commentsql, ((int)$pageobj->page - 1) * 15);
					$counter=0;
					while (($commentResult = mysql_fetch_array($commentsql)) && $counter < 15){
						$imgsrc = "http://xyhit-headimg.stor.sinaapp.com/".$commentResult['commenterid'].".png";
						$commentersql = mysql_query("select name from user where id='".$commentResult['commenterid']."'", $db);
						if ($commentersql){
							$commenterResult = mysql_fetch_array($commentersql);
							$commenterName = $commenterResult['name'];
						}
						else{
							echo mysql_errno() . ": " . mysql_error(). "\n";
							exit;
						}
						echo "<div class='weui_cell'>
								<div class='weui_cell_hd'  align='top'>
									<label class='weui_label'>
										<img src='".$imgsrc."' style='width:24px;height:24px' align='top'></img>
									</label>
								</div>
								<div class='weui_cell_bd weui_cell_primary' contenteditable='false' name='conditioncontent' style='outline:none; min-height:15px; color:#999999; font-size:15px;'>
								".$commentResult['content']."
									<ul class='weui_media_info_new'>
										<li class='weui_media_info_meta_new'>".$commenterName."</li><li class='weui_media_info_meta_new weui_media_info_meta_extra_new'>".$commentResult['time']."</li>
									</ul>
								</div>
							</div>";
						$counter ++;
					}
				}
				else{
					echo mysql_errno() . ": " . mysql_error(). "\n";
					exit;
				}
				
				echo "</div>
					</div>
					</div>
					<div class='publish-article-title'>
					<div class='weui_panel weui_panel_access'>
						<div class='weui_panel_hd' alige = 'center'>";
				echo "<h3 align='center'>".(string)($pageobj->showpage())."</h3>";
				echo "</div>
					</div>
					</div>";
			?>
			<!--评论 end-->
</div>
<!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
	<div class="weui_mask"></div>
	<div class="weui_dialog">
		<div class="weui_dialog_hd"><strong class="weui_dialog_title">写评论</strong></div>
		<div class="weui_dialog_bd">
		<form name='form1'>
			<div class="weui_cell">
				<div class="weui_cell_bd weui_cell_primary">
					<textarea class="weui_textarea" placeholder="请输入内容" rows="3" id="comment"></textarea>
					<div class="weui_textarea_counter"><span id="count">0</span>/200</div>
				</div>
			</div>
		</div>
		<div class="weui_dialog_ft">
			<a class="weui_btn_dialog default" onclick="hideDialog()">取消</a>
			<a class="weui_btn_dialog primary" onclick='addcomment()'>确定</a>
		</div>
	</div>
</div>
<!--END dialog1-->
<!-- Loading Toast-->
<div id="loadingToast" class="weui_loading_toast" style="display: none;">
	<div class="weui_mask_transparent"></div>
	<div class="weui_toast">
		<div class="weui_loading">
			<div class="weui_loading_leaf weui_loading_leaf_0"></div>
			<div class="weui_loading_leaf weui_loading_leaf_1"></div>
			<div class="weui_loading_leaf weui_loading_leaf_2"></div>
			<div class="weui_loading_leaf weui_loading_leaf_3"></div>
			<div class="weui_loading_leaf weui_loading_leaf_4"></div>
			<div class="weui_loading_leaf weui_loading_leaf_5"></div>
			<div class="weui_loading_leaf weui_loading_leaf_6"></div>
			<div class="weui_loading_leaf weui_loading_leaf_7"></div>
			<div class="weui_loading_leaf weui_loading_leaf_8"></div>
			<div class="weui_loading_leaf weui_loading_leaf_9"></div>
			<div class="weui_loading_leaf weui_loading_leaf_10"></div>
			<div class="weui_loading_leaf weui_loading_leaf_11"></div>
		</div>
		<p class="weui_toast_content">数据保存中</p>
	</div>
</div>
<!-- Loading Toast End-->
<script src="./static/jquery.min.js"></script>
<script type="text/javascript">
	var x = document.getElementsByClassName("imgFromJavaScript");
	for (var i = 0; i < x.length; i ++){
		x[i].style.width = "100%";
	}
</script>
<script type="text/javascript">
	$(function(){
	  var max = 200;
	  $('#comment').on('input', function(){
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
	function onclickcomment(){ 
		$("#dialog1").removeAttr("style");
	}
	function hideDialog(){
		$("#dialog1").attr("style","display: none;");
	}
</script>
<script type="text/javascript">
function getNowFormatDate() {
	var date = new Date();
	var seperator1 = "-";
	var seperator2 = ":";
	var month = date.getMonth() + 1;
	var strDate = date.getDate();
	if (month >= 1 && month <= 9) {
		month = "0" + month;
	}
	if (strDate >= 0 && strDate <= 9) {
		strDate = "0" + strDate;
	}
	var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
			+ " " + date.getHours() + seperator2 + date.getMinutes();
	return currentdate;
}
function addcomment(){
	var comment = document.getElementById("comment").value;
	if (comment.length > 200){
		alert("文字数量太大！");
		return;
	}
	else if (comment.length == 0){
		alert("未填写内容");
		return;
	}
	transComment = comment;
	transComment=transComment.replace(/\n/ig,"\n<br/>");
	transComment = transComment.replace(/\+/g, "%2B");          //正文转义“+”
	transComment = transComment.replace(/\&/g, "%26");          //正文转义“&”
	
	$("#loadingToast").removeAttr("style");             //动画效果
	
	<?php 
		echo "var blogId = '".$result['blogid']."';";
		echo "var blogPublisherId = '".$publisherResult['id']."';";
		if (isset($_SESSION['authentication'])){
			$openid = $_SESSION['openid'];
			echo "var openid = '$openid';";
			$visitorsql = mysql_query("select name from user where id='$openid'", $db);
			if ($visitorsql){
				if (mysql_num_rows($visitorsql) > 0){
					$visitorResult = mysql_fetch_array($visitorsql);
					echo "var visitor = '".$visitorResult['name']."';";
					echo "var imgsrc = 'http://xyhit-headimg.stor.sinaapp.com/$openid.png';";
				}
				else{
					echo "alert('您还未注册，暂不能发送评论');return;";
					echo "var imgsrc = './static/img/defaultuser.jpg';";
				}
			}
			else{
				echo mysql_errno() . ": " . mysql_error(). "\n";
			}
		}
		else{
			echo "alert('未获取到您的用户session，暂不能发送评论');return;";
		}
	?>
	var time = getNowFormatDate();
	var postStr = "comment=" + transComment + "&openid=" + openid + "&blogid=" + blogId + "&blogPublisherId=" + blogPublisherId;
	var xmlhttp;                                      //ajax异步提交表单
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("post", "http://1.xyhit.applinzi.com/blogCommentAjaxProcess.php", true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8");
	xmlhttp.send(postStr);
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var recv = xmlhttp.responseText;
			if (recv == '1'){  //正确保存, 返回发布时间，保存在变量recv中
				var insert_html = "<div class='weui_cell'><div class='weui_cell_hd'  align='top'><label class='weui_label'><img src='" + imgsrc + "' style='width:24px;height:24px' align='top'></img></label></div><div class='weui_cell_bd weui_cell_primary' contenteditable='false' name='conditioncontent' style='outline:none; min-height:15px; color:#999999; font-size:15px;'>" + comment + "<ul class='weui_media_info_new'> <li class='weui_media_info_meta_new'>" + visitor + "</li><li class='weui_media_info_meta_new weui_media_info_meta_extra_new'>" + time + "</li></ul></div></div>";

				document.getElementById(id="addCommentPostion").insertAdjacentHTML("afterBegin", insert_html);
				document.getElementById("comment").value="";
				hideDialog();
				$("#loadingToast").attr("style","display: none;");
			}
			else{              //错误
				$("#loadingToast").attr("style","display: none;");
				alert("发表失败，请检查网络后重新提交。");
			}
		}
	}
}
</script>
</body>
</html>