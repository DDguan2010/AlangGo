<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("good.php");
include("user.php");
$uid=$_COOKIE["uid"];
$aid=$_GET["aid"];
$do=$_GET["do"];
is_login(0);
if($aid==NULL)
{
if($_GET["do"]==1)
{

$list1=mysql_query("SELECT * FROM z_a WHERE toid='${uid}' ");
}
else
{
$list1=mysql_query("SELECT * FROM z_a WHERE toid=${uid} AND uid=0 ");
}
$anum=mysql_num_rows($list1);

if($anum==0)
{$content="<p>『 无 』</p>";}

else
{
$all_num=$anum;

$page=$_GET["p"];

$max_page=ceil($all_num/10);

if($page==NULL || $page<=0||$page>$max_page)
{$page=1;}

$num1=($page-1)*10;

$num2=10;

if($max_page>1)

{

if($page>1){$p2=$page-1;
$sy="<a href='?zid=${zid}&do=${do}&p=${p2}'>上页</a>";}

if($page<$max_page){$p3=$page+1;
$xy="<a href='?zid=${zid}&do=${do}&p=${p3}'>下页</a>";}

$ys="<form method='get' action=' '>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='hidden' name='zid' value='${zid}' /><input type='submit' value='跳' /></form>";
}
$n0=$all_num-($page-1)*10;

if($_GET["do"]==1)
{
$list1=mysql_query("SELECT * FROM z_a WHERE toid='${uid}' ORDER BY time DESC LIMIT $num1,$num2");
}
else
{
$list1=mysql_query("SELECT * FROM z_a WHERE toid=${uid} AND uid=0 ORDER BY time DESC LIMIT $num1,$num2");
}

while($list2=mysql_fetch_array($list1))

{

$txt=ctxt($list2["txt"]);
$time=ctime($list2["time"]);
$fromid=$list2["fromid"];
$u=$list2["uid"];
$zid=$list2["y1"];
$frominfo=uinfo($fromid);
$fromname=$frominfo["name"];
    
    if($list2["y2"]=='c')
    {
        $zai="聊天室";
        $isc="&isc=1";
    }    
    else
    {
        $zinfo=mysql_fetch_array (mysql_query ( "SELECT * FROM z_txt WHERE zid= ${list2[y1]} " ));
        if($zinfo["lb"]==1)
        {
            $zai="帖子";
        }
        else
        {
            $zai="说说";
        }
    }
    
    
if($u==1){$is_read="";}else{$is_read="<font color='red'>[新]</font>";}
    
    
$content=$content."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'><span class='txt-fade'>${n0}) </span>${is_read} <a href='user/info.php?uid=${fromid}'>${fromname}</a>在<a href='?aid=${list2[aid]}${isc}'>${zai}</a>中@你:<p><span class='txt-fade'>${txt}</span>(${time})</p></p></div>";
$n0--; 
}
}
if($_GET["do"]==1)
{
$zh="<a href='?do=0'>未查看</a> - 〖 全部 〗";
}
else
{
$zh="〖 未查看 〗 - <a href='?do=1'>全部</a>";
}
$title="我的@消息";
include("html.php");
echo "</head><body>${head}<p><span class='txt-fade'>我 的 @ 消 息 </span><p><div class='module-title'>${zh}</div>".$content.$ys.$foot;
}
else
{
if($ainfo=mysql_fetch_array (mysql_query ( "SELECT * FROM z_a WHERE aid= ${aid} " )))
{

if($ainfo["toid"]==$uid)
{
    mysql_query ( "UPDATE z_a SET uid=1 WHERE aid='$aid'" );
    
    if($ainfo["y2"]=="c")
    {
        //$pp=ceil($ainfo["y1"]/10);
        header("location:chat.php?cid=${ainfo[y1]}");
        break;
    }
    
    header("location:read.php?zid=${ainfo[y1]}");
}
    else
    {
        die("出错了。");
    }

}
else{die("出错了。。。");}
}