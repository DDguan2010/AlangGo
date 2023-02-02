<?php
header  (  'Content-Type:text/html;charset=utf-8 ' );
include("../conn.php");
include("good.php");
include("user.php");
include("../conn.php");
$asql = "SELECT * FROM z_hf WHERE hid=' $_GET[hid]  ' " ;
$list1 = mysql_query ( $asql );
if( $list2 = mysql_fetch_array ( $list1 )){}else{die( "内容不存在！" );}
$txt_size=500;
$all_txt_num=mb_strlen($list2['txt'],"utf-8");
$max_txt_page=ceil($all_txt_num/$txt_size);

if($_GET["p"]==NULL || $_GET["p"]<=0 ||$_GET["p"]>$max_txt_page){$p=1;}
else{$p=$_GET["p"];}
$txt=mb_substr($list2['txt'],$txt_size*($p-1),$txt_size,"utf-8");

$txt=ctxt($txt);

$txt=ubb2html($txt);

if($max_txt_page>1)
{
if($p>1){$p2=$p-1;
$sy="<a href='read_hf.php?hid=${list2[hid]}&p=${p2}'>上页</a>";}
if($p<$max_txt_page){$p3=$p+1;
$xy="<a href='read_hf.php?hid=${list2[hid]}&p=${p3}'>下页</a>";}
$ys="<form method='get' action=''><input type='hidden' value='${list2[hid]}' name='hid' />".$xy."[${p}/${max_txt_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='submit' value='跳' /></form>";
}
$uinfo=uinfo($list2["uid"]);
$uname=$uinfo["name"];
$title="查看回复全文";
include("html.php");
echo "</head><body>${head}<div class='module-title'><a href='read.php?zid=${list2[zid]}'>【 返 回 】</a></div><div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'>[ 该条回复来自<a href='user/info.php?uid=${list2[uid]}'>${uname}</a> ]</p></div><p>".$txt."</p>".$ys.$foot;