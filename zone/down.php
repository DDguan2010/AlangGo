<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("good.php");
$sf = new SaeStorage();
$d=$_GET["d"];
$n=$_GET["n"];
if($sf->fileExists($d,$n))
{
$gurl=$sf->getUrl($d,$n);
if(@$did=mysql_fetch_array(mysql_query("SELECT * FROM z_down WHERE name='$n'")))
{
$did=$did["num"];
$did+=1;
mysql_query("UPDATE z_down SET num='$did' WHERE name='$n'");
}
{
mysql_query("INSERT INTO z_down(num,name) VALUES (1,'$n')" );
}
header("location:${gurl}");
}
else
{
die("Not Found!");
}