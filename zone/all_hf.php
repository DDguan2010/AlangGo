<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("good.php");
include("user.php");
$zid=$_GET["zid"];
$asql="SELECT * FROM z_txt WHERE zid='${zid}' ";

$list1=mysql_query($asql);

if($list2=mysql_fetch_array($list1)){}else{die("内容不存在！");}
$hfnum=mysql_num_rows(mysql_query("SELECT * FROM z_hf WHERE zid='${zid}' "));
if($hfnum==0){$content="<p>暂时没有人回复Ta哦！</p>";}
else{
$all_num=$hfnum;
$page=$_GET["p"];
$max_page=ceil($all_num/10);
if($page==NULL || $page<=0 ||$page>$max_page){$page=1;}
$num1=($page-1)*10;
$num2=10;
if($max_page>1)
{
if($page>1){$p2=$page-1;
$sy="<a href='?zid=${zid}&p=${p2}'>上页</a>";}
if($page<$max_page){$p3=$page+1;
$xy="<a href='?zid=${zid}&p=${p3}'>下页</a>";}
$ys="<form method='get' action=' '>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='hidden' name='zid' value='${zid}' /><input type='submit' value='跳' /></form>";
}
$asql="SELECT * FROM z_hf WHERE zid='${zid}' ORDER BY time DESC  LIMIT $num1,$num2";
$list1=mysql_query($asql);
$n0=$all_num-($page-1)*10;
while($list2=mysql_fetch_array($list1))
{
$txt=$list2["txt"];
if( mb_strlen ( $txt , 'utf-8' )> 100 )
{
$txt = mb_substr ( $txt , 0 , 100,"utf-8" );
$txt = $txt ."[url=read_hf.php?hid=${list2[hid]}]……[/url]" ;}
$time0=ctime($list2["time"]);
$txt=ctxt($txt);
$txt=ubb2html($txt);
$uname=uinfo($list2["uid"]);
$uname=$uname["name"];
$content=$content."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'><span class='txt-fade'>${n0})</span> <a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> : ${txt} <span class='txt-fade'>(${time0})</span></p></div>";
$n0--;
}
}
$title="全部回复";
include("html.php");
echo "</head><body>${head}<div class='module-title'>全部回复内容-<a href='read.php?zid=${zid}'>返回</a></div>".$content.$ys.$foot;