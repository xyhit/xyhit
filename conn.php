<?php
error_reporting(E_ALL ^ E_DEPRECATED);
$db = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS) or die("���ݿ���������Ӵ���".mysql_error());
if ($db) {
	mysql_select_db(SAE_MYSQL_DB, $db) or die("���ݿ���ʴ���".mysql_error());
}
?>