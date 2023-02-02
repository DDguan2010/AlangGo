<?php
include("check.php");
header("Content-Type: text/html; charset=utf-8");
$do=$_GET["do"];
if($do=="add" && $_POST["nbn"]!=NULL)
{
$nname=$_POST["nbn"];
$obid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='bid' ") );
$nbid=$obid["num"]+1;
if(mysql_query("INSERT INTO z_lt(bid,name)VALUES($nbid,'$nname')"))
        {
mysql_query("UPDATE z_jsq SET num='${nbid}'  WHERE name='bid' ");
$out="<font color='green'>版块 $nname 新建成功。</font>";
        }
}
if($do=="delete" && $_GET["ok"]!=1 && $_GET["bid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='{$_GET['bid']}' ")) )
{
$gbid=$_GET["bid"];
$bidn=mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='$gbid' "));
$out="确定删除 {$bidn['name']} 版块<font color='red'>及版块下所有帖子</font>?<br/><a href='?do='>算了</a>|<a href='?do=delete&bid=${gbid}&ok=1'>确定</a>";
}
elseif($do=="delete" && $_GET["ok"]==1 && $_GET["bid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='{$_GET['bid']}' ")) )
{
        if(mysql_query("DELETE FROM z_lt WHERE bid={$_GET['bid']}"))
        {
mysql_query("DELETE FROM z_txt WHERE y3='{$_GET['bid']}' ");
$out="<font color='green'>删除成功。</font>";
        }
}
$ton=$_POST["ton"];
if($do=="change" && $ton==NULL && $_GET["bid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='{$_GET['bid']}' ")) )
{
$gbid=$_GET["bid"];
$bidn=mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='$gbid' "));
$out="<p><form method='POST' action='?do=change&bid=${gbid}'>将 {$bidn['name']} 改名为<input type='text' name='ton'><input type='submit' value='确定' /></form></p>";
}
elseif($do=="change" && $ton!=NULL && $_GET["bid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_lt WHERE bid='{$_GET['bid']}' ")) )
{
        if(mysql_query("UPDATE  z_lt SET name='${ton}' WHERE bid={$_GET['bid']}"))
        {
$out="<font color='green'>修改成功。</font>";
        }
}

$list1=mysql_query("SELECT * FROM z_lt");
if(!mysql_fetch_array($list1)){$content1="无"; }
else
{
$list1=mysql_query("SELECT * FROM z_lt");
        while($list2=mysql_fetch_array($list1) )

        {
$content1=$content1."${list2[1]} ( <a href='?do=delete&bid=${list2[0]}'>删除</a> | <a href='?do=change&bid=${list2[0]}'>修改</a> )<br/>";
        }
}
$add_form="<div><form method='POST' action='?do=add&bid'><input type='text' name='nbn'><input type='submit' value='新建' /></form></div>";
$title='板块管理';
include("../html.php");
echo "</head><body><h1>版块管理</h1><hr/>$out<div>${content1}</div><hr/><div>新建版块<br/>$add_form</div><hr/><a href='index.php'>后台列表</a>.<a href='../index.php'>社区首页</a></body></html>";