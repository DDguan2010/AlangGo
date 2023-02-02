<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("cuttxt.php");
include("good.php");
$zid=$_GET["zid"];
$bid=$_POST["bid"];
$do=$_GET["do"];
$asql="SELECT * FROM z_txt WHERE zid='${zid}' ";
$list1=mysql_query($asql);
if($list2=mysql_fetch_array($list1)){}else{die("内容不存在！");}
if( is_admin($list2[uid])==0){die("error");}
if($do==1)
{
if(!mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='$bid' "))){die("error");}
mysql_query("UPDATE z_txt SET  y3= '$bid' WHERE  zid=$zid ");$out="版块移动成功。<br/><a href='read.php?zid=${zid}'>返回查看帖子</a>";
}
else
{
$zinfo=mysql_fetch_array(mysql_query("SELECT * FROM z_txt WHERE zid='$zid' "));
$out="<a href='read.php?zid=${zid}'>返回帖子</a><br/><form method='post' action='?zid=${zid}&do=1'>".settle_lt($zinfo["y3"],0)."<br/><input type='submit' value='移 动'></form>";
}
$title="帖子移动";
include("html.php");
echo "</head><body>${head}<div class='module-title'>${title}</div><p>${out}</p>${foot}";