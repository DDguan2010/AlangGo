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
//验证操作的合法性
if($list2=mysql_fetch_array($list1)){}else{die("内容不存在！");}
//验证管理员身份
if( is_admin($list2[uid])==0){die("error");}
//判定操作
if($do==1)
{
    if($list2["tip"]!=1)
    {     
            if(mysql_query("UPDATE z_txt SET  tip=1 WHERE  zid=$zid "))
            {
             $out="<font color=\"greent\">帖子置顶 成功。</font><br/><a href='read.php?zid=${zid}'>返回查看帖子</a>";
            }
            else
            {
             $out="<font color=\"red\">帖子置顶 失败。</font><br/><a href='read.php?zid=${zid}'>返回查看帖子</a>";
            }     
    }
    else
    {
       if(mysql_query("UPDATE z_txt SET  tip=0 WHERE  zid=$zid "))
            {
             $out="<font color=\"greent\">帖子<font color=\"red\">取消置顶</font>成功。</font><br/><a href='read.php?zid=${zid}'>返回查看帖子</a>";
            }
            else
            {
             $out="<font color=\"red\">帖子<font color=\"red\">取消置顶</font>失败。</font><br/><a href='read.php?zid=${zid}'>返回查看帖子</a>";
            }     
        
        
        
        
    }
}
else
{
     if($list2["tip"]!=1)
     {
     $out="<a href='read.php?zid=${zid}'>返回帖子</a><br/>确定要置顶它？<br/><a href='read.php?zid=${zid}'>算了，再看看</a><br/><a href='?zid=${zid}&do=1'>确定，以及肯定</a>";
     }
     else
     {
     
     $out="<a href='read.php?zid=${zid}'>返回帖子</a><br/>确定要<font color=\"red\">取消置顶</font>？<br/><a href='read.php?zid=${zid}'>算了，再看看</a><br/><a href='?zid=${zid}&do=1'>确定，以及肯定</a>";
     
     }
}
//显示页面
$title="帖子置顶";
include("html.php");
echo "</head><body>${head}<div class='module-title'>${title}</div><p>${out}</p>${foot}";