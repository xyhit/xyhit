<?php
namespace LaneWeChat;
use LaneWeChat\Core\SendNewsToMany;
use LaneWeChat\Core\ResponseInitiative;
use LaneWeChat\Core\SqlQuery;
require ("./core/sqlquery.lib.php");
require ("./core/sendnewstomany.lib.php");

include_once __DIR__.'/config.php';

// 当用户点击submit提交上传的文件时
if(isset($_POST["submit"])){
	$title = $_POST["title"];            //新闻标题title，内容content，描述desc，是否主动群发active
	$content = $_POST["content"];
	$desc = $_POST["description"];
	if (isset($_POST["active"]) && $_POST["active"] == "on"){
		$active = true;
	}
	else{
		$active = false;
	}
	$time = date('Y-m-d H:i:s', time());
	
	$insert_array = array(
		'title'=>$title,
		'time'=>$time,
		'description'=>$desc,
		'content'=>$content
	);
	$re = SqlQuery::insert('news', $insert_array);        //将一条新闻插入数据库
	print_r($re);
	if ($re[0] == 0 && $re[1] >= 1){
		echo "<p style='background:#7CBD55;border-radius: 0.3em;padding:5px;color:#fff;'>新闻上传成功！</p>";
	}
	else if (DEBUG){
		echo "Error: ".$re[0].", ".$re[1];
		exit;
	}
	else{
		echo "<p style='background:#7CBD55;border-radius: 0.3em;padding:5px;color:#fff;'>新闻上传失败！</p>";
		exit;
	}
	
	#处理上传的图片文件
	$insert_array = array(
		"news_id"=>$re[2]            //获取新闻id
	);
	$re = SqlQuery::insert('newspicture', $insert_array);
	if ($re[0] == 0 && $re[1] >= 1){
		echo "<p style='background:#7CBD55;border-radius: 0.3em;padding:5px;color:#fff;'>图片上传成功！</p>";
	}
	else if (DEBUG){
		echo "Error: ".$re[0].", ".$re[1];
		exit;
	}
	else{
		echo "上传图片失败！";
		exit;
	}	
	
	$filename = $re[2];   //上传图片的名称，即id
	$fileFolder = "newspicture/";   //图片保存在服务器上的路径
	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 2000000))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
			if (file_exists($fileFolder	. $filename))
			{
				echo $filename . " already exists. ";
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"], $fileFolder . $filename);
			}
		}
	}
	else
	{
		echo "Invalid file";
	}	
	
	//如果主动发送按钮为开，则将新闻发送给注册用户
	/*if ($active == true){
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
	}*/
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
	<input type="file" name="file" id="file"></input>
	<input type="checkbox" name="active" >是否主动发送</input>
	<input type="submit" value="Submit" name="submit"></input>	
</form>

</body>
</html>