<?php
error_reporting(E_ALL ^ E_DEPRECATED);
$db = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS) or die("数据库服务器连接错误".mysql_error());
if ($db) {
	mysql_select_db(SAE_MYSQL_DB, $db) or die("数据库访问错误".mysql_error());
}
?>