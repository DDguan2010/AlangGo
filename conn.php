<?php
date_default_timezone_set("Asia/Shanghai");
//include_once("zone/good.php");
$datetime=date("Y.m.d H:i");
$mysql_host="localhost";
$mysql_user="root";
$mysql_password="root";
$mysql_database="vst93";
$conn=mysql_connect("$mysql_host","$mysql_user","$mysql_password");
if(!$conn){ die("连接数据库失败：" . mysql_error());}
mysql_select_db("$mysql_database",$conn);
mysql_query("set character set 'utf-8'");
mysql_query("set names 'utf-8'");
?>