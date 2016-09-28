<?php
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$msginfourl = "./msginfo.php?id=".$id;
		$userinfourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$id)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
	}
	else{
		echo "no-get-code-error!\n";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-Hans">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
		<title>选项</title>
		<link rel="stylesheet" href="./weui/dist/style/weui.min.css"/>
	</head>
	<body>
	<div class="weui_cells weui_cells_access">
        <?php echo "<a class='weui_cell' href='$userinfourl'>";?>
            <div class="weui_cell_bd weui_cell_primary">
                <p>用户详情</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
		<?php echo "<a class='weui_cell' href='$msginfourl'>";?>
            <div class="weui_cell_bd weui_cell_primary">
                <p>往来消息</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
    </div>
	</body>
</html>