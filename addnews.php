<?php
namespace LaneWeChat;
require ("./core/sendnewstomany.lib.php");
use LaneWeChat\Core\SendNewsToMany;
use LaneWeChat\Core\ResponseInitiative;
// 当用户点击submit提交上传的文件时
if(isset($_POST["submit"])){
	// 创建SAE storage存储
	$storage= new \SaeStorage();// 创建SAE storage存储对象
	$domain = 'newspicture';// 这里的$domain对应得名字就是自己起的名字
	$fileType = $_FILES["file"]["type"]; //被上传文件的类型
	$title = $_POST["title"];
	$content = $_POST["content"];
	$desc = $_POST["description"];
	if ($_POST["active"] == "on"){
		$active = true;
	}
	else{
		$active = false;
	}
	$time = time();
	$md_title = md5($title);
	if (strlen($md_title) >= 10){
		$id = (string)$time.(substr($md_title,-10));
	}
	else{
		$id = (string)$time;
	}
	#连接sae时，系统提供默认参数，本地的话要自己填写主机、端口、用户、密码
	include("conn.php");
	if (mysql_query("insert into news(id, stringtime, title, content, description) values('".$id."','".(string)$time."','".$title."','".$content."','".$desc."')", $db) == false){
		echo mysql_errno() . ": " . mysql_error(). "\n";
	}else{
		echo "<p style='background:#7CBD55;border-radius: 0.3em;padding:5px;color:#fff;'>新闻上传成功！</p>";
	}

	if(($fileType=="image/gif") || ($fileType=="image/jpeg")||($fileType=="image/jpg")||($fileType=="image/png")){
		$filename = $id."_".substr(md5($_FILES["file"]["name"]), -8).".png";
		if($storage->fileExists($domain,$filename) == true) {// 判断文件是否已经存在
			echo "<p style='background:#FCC9C4;border-radius: 0.3em;padding:5px;color:#fff;''>图片已存在,请重新上传!</p>";
			}
		else{
			//$storage->putObjectFile($_FILES['uploaded']['tmp_name'], "test", "1.txt");
			$storage->upload( $domain,$filename,$_FILES['file']['tmp_name']); 
			echo "<p style='background:#7CBD55;border-radius: 0.3em;padding:5px;color:#fff;'>图片上传成功！</p>";
			//echo "<script> window.location='showImage.php';</script>";
			   
			echo '<span style="white-space:pre">	</span>';}
    }else{
    	echo "<p style='background:#FCC9C4;border-radius: 0.3em;padding:5px;color:#fff;''>图片格数不正确,上传失败！</p>";
    }
	
	$url = "http://xyhit-newspicture.stor.sinaapp.com/".$filename;
	if (mysql_query("insert into picture(id, name, url) values('".$id."','".$filename."','".$url."')", $db) == false){
		echo mysql_errno() . ": " . mysql_error(). "\n";
	}
	if ($active == true){
		//return $responseInitiative->text('ocC_xvqBiYxo-z-3yHZ_UCNw_v9A', "hello world");
		$newArr = array(
					array(
						"title"=>urlencode($title),
						"description"=>urlencode($desc),
						"picurl"=>$url,
						"url"=>"http://1.xyhit.applinzi.com/news.php?id=".(string)$id
					)
				);
		$itemArray = array();
		foreach ($newArr as $item){
			$itemArray[] = ResponseInitiative::newsItem($item["title"], $item["description"], $item["picurl"], $item["url"]);
		}
		
		$touser = array();
		$sql = mysql_query("select id from user", $db);
		if ($sql){
			while ($query_result = mysql_fetch_array($sql))
				$touser[] = $query_result['id'];
		}
		else{
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		
		if (count($touser) > 0){
			foreach ($touser as $useritem){
				SendNewsToMany::send($useritem, $itemArray);
			}
		}
	}
}

?>

<!DOCTYPE HTML>
<html>
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!--<meta name="viewport" content="width=device-width,initial-scale=1">
	<link href="./css/style.css" rel="stylesheet" type="text/css"  media="all" />-->
</head>
<body>

<form method="POST" enctype="multipart/form-data">
<h3>标题编辑：</h3>
	<input type="text" style="font-size:20px; width:380px; height:30px;" name="title">
<br/>
<h3>新闻描述： </h3>
<div>
	<textarea style="font-size:20px; width:380px; height:50px;" size="20px" name="description"></textarea>
</div>
<br/>
<h3>内容编辑： </h3>
<div>
	<textarea style="font-size:20px; width:380px; height:300px;" size="20px" name="content" ></textarea>
</div>
<h3>图片编辑：</h3>
	<input type="file" name="file" id="file" />
	<input type="checkbox" name="active" >是否主动发送</input>
	<input type="submit" value="Submit" name="submit"/>	
</form>

</body>
</html>