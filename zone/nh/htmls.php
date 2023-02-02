<?php
header("Content-Type: text/html; charset=utf-8");
include("../../conn.php");
date_default_timezone_set("Asia/Shanghai");
$n=$_GET["n"];
if(!empty($n))
{
    //读取htmls中css内容
    $css_txt=mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='css'"));
    $css_txt=htmlspecialchars_decode($css_txt["txt"],ENT_QUOTES);
    
$h0="<!DOCTYPE html PUBLIC'-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='content-type' content='text/html;charset=UTF-8'>
<style type='text/css'>
${css_txt}
</style>
";
$m1 = mysql_query("SELECT * FROM htmls WHERE name='${n}'");
$m2=mysql_fetch_array($m1);
echo $h0.htmlspecialchars_decode($m2["txt"],ENT_QUOTES);
}
?>