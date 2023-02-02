<?php
include("../good.php");
include("../../conn.php");
header ( 'Content-Type:text/html;charset=utf-8 ');
if($_COOKIE["uid"]!=1)
{
die("Not Allow!");
}

?>