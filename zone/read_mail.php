<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("good.php");
include("user.php");
$uid=$_COOKIE["uid"];
$mid=$_GET["mid"];
$do=$_GET["do"];
is_login(0);
if($mid==NULL)

{
if($_GET["do"]==1)
{

$list1=mysql_query("SELECT * FROM z_mail WHERE toid='${uid}' ");
}
elseif($_GET["do"]==2)
{

$list1=mysql_query("SELECT * FROM z_mail WHERE fromid='${uid}' ");
} 
else
{
$list1=mysql_query("SELECT * FROM z_mail WHERE toid=${uid} AND uid=0 ");
}
$anum=mysql_num_rows($list1);

if($anum==0)
{$content="<p>『 无 』</p>";
if($_GET["do"]==1)
{
$zh="<a href='?do=0'>未查看</a> 〖 全部 〗<a href='?do=2'>发送的</a>";
}
elseif($_GET["do"]==2)
{
$zh="<a href='?do=0'>未查看</a> <a href='?do=1'>全部</a> 〖发送的〗";
}
else
{
$zh="〖 未查看 〗 <a href='?do=1'>全部</a> <a href='?do=2'>发送的</a>";}

}

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
$zh="<a href='?do=0'>未查看</a> 〖 全部 〗<a href='?do=2'>发送的</a>";
$list1=mysql_query("SELECT * FROM z_mail WHERE toid='${uid}' ORDER BY time DESC LIMIT $num1,$num2");
}
elseif($_GET["do"]==2)
{
$zh="<a href='?do=0'>未查看</a> <a href='?do=1'>全部</a> 〖发送的〗";
$list1=mysql_query("SELECT * FROM z_mail WHERE fromid='${uid}' ORDER BY time DESC LIMIT $num1,$num2");
}
else
{
$zh="〖 未查看 〗 <a href='?do=1'>全部</a> <a href='?do=2'>发送的</a>";
$list1=mysql_query("SELECT * FROM z_mail WHERE toid=${uid} AND uid=0 ORDER BY time DESC LIMIT $num1,$num2");
}

while($list2=mysql_fetch_array($list1))

{

$txt=ctxt($list2["txt"]);
    $txt=ubb2html($txt);
$time=ctime($list2["time"]);
$fromid=$list2["fromid"];
$toid=$list2["toid"];
$u=$list2["uid"];
$zid=$list2["y1"];
$frominfo=uinfo($fromid);
$fromname=$frominfo["name"];
if($u==1){$is_read="";}else{$is_read="<font color='red'>[未读]</font>";}
if($_GET["do"]!=2)
{
$content=$content."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'><span class='txt-fade'>${n0}) </span>${is_read} <a href='user/info.php?uid=${fromid}'>${fromname}</a>发给你的<a href='?mid=${list2[mid]}'>内信</a>:<p><span class='txt-fade'>${txt}</span>(${time})</p></p></div>";
}
else
{
$toname=uinfo($toid);
$toname=$toname["name"];
$content=$content."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'><span class='txt-fade'>${n0}) </span>${is_read} 发送给<a href='user/info.php?uid=${toid}'>${toname}</a>的<a href='?mid=${list2[mid]}'>内信</a>:<p><span class='txt-fade'>${txt}</span>(${time})</p></p></div>";
}

$n0--; 
}
}
$title="我的内信";
include("html.php");
echo "</head><body>${head}<p><span class='txt-fade'>我 的 内 信 </span><p><div class='module-title'>${zh}</div>".$content.$ys.$foot;
}
else
{
if($minfo=mysql_fetch_array (mysql_query ( "SELECT * FROM z_mail WHERE mid= ${mid} " )))
{

if($minfo["toid"]==$uid || $minfo["fromid"]==$uid)
{
if($minfo["toid"]==$uid)
{
mysql_query ( "UPDATE z_mail SET uid=1 WHERE mid='$mid'" );
}
$txt=ctxt($minfo["txt"]);
$time=ctime($minfo["time"]);
$fromid=$minfo["fromid"];
$frominfo=uinfo($fromid);
$fromname=$frominfo["name"];
$toid=$_GET["toid"];
$mailtxt=$_POST["mailtxt"];
$myid=$_COOKIE["uid"];
$ntime=time();
if(!empty($mailtxt) )
{
is_login(0);
$nmid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='mid'"));
$nmid=$nmid["num"]+1;
if(mb_strlen($mailtxt,'utf-8')>5000)
{
$out="<p><font color='red'>内信长度应小于5000字!</font></p>";
}
elseif(mysql_query("INSERT INTO z_mail(fromid,toid,mid,uid,txt,time) VALUES ($myid,$toid,$nmid,0, '$mailtxt',$ntime)" ) && mysql_query("UPDATE z_jsq SET num='$nmid' WHERE name='mid'") )
{ $out="<p><font color='green'>回复内信成功。</font></p>";
}
}
$mail_form="<div><form method='POST' action='?mid=${mid}&toid=${fromid}'><textarea name='mailtxt' rows='2'></textarea><br/>
<input type='submit' class='btn-s' value='确定回复' /> </div>";

$title="来自${fromname}的内信";
$mtime=$time;
include("html.php");
echo "</head><body>${head}${out}<p><span class='txt-fade'>来自${fromname}的内信 </span>[<a href='?mid='>返回</a>]</p><p>${txt}<span class='txt-fade'>(${mtime})</span></p><div class='module-title'>回复Ta</div><div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'>${mail_form}</p></div>${foot}";

}else{die("出错了。");}

}
else{die("出错了。。。");}
}