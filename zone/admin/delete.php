<?php
include("check.php");
include("../user.php");
header("Content-Type: text/html; charset=utf-8");
$guid=$_GET["uid"];
$do=$_GET["do"];
$page=$_GET["p"];

if($do=="delete" && $_GET["ok"]!=1 && $_GET["uid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= $guid ")) )
{
$uinfo=uinfo($guid);
$out="确定删除用户 <a href='../user/info.php?uid=${guid}'>{$uinfo['name']}(${guid}) ?<br/><a href='?do='>算了</a>|<a href='?do=delete&uid=${guid}&ok=1'>确定</a>";
}
elseif(
    $do=="delete" && $_GET["ok"]==1 && $_GET["uid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= {$_GET['uid']} ")) )
{
if(mysql_query("DELETE FROM v_user WHERE id={$_GET['uid']}"))
{
$out="<font color='green'>该用户删除成功。</font>";
}
}
$un=mysql_query("SELECT * FROM v_user");
$all_n=mysql_num_rows($un);
$content="目前共有{$all_n}位用户";


$max_page=ceil($all_n/20);
if($page==NULL || $page<=0 ||$page>$max_page)
{$page=1;}
$num1=($page-1)*20;
$num2=20;
if($max_page>1)
{
    if($page>1)
    {
        $p2=$page-1;
        $sy="<a href='?p=${p2}'>上页</a>";
    }
    if($page<$max_page)
    {
        $p3=$page+1;
        $xy="<a href='?p=${p3}'>下页</a>";
    }
}
$ys="<form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='hidden' name='do' value='${do}' /><input type='hidden' name='bid' value='${bid}' /><input type='submit' value='跳' /></form>";

     
$asql="SELECT * FROM v_user ORDER BY id DESC LIMIT $num1,$num2";
$list1=mysql_query($asql); 
while($list2=mysql_fetch_array($list1))
{
    $uncon=$uncon."<a href=\"../user/info.php?uid=${list2[id]}\">${list2['name']}</a>[${list2[id]}] <a href='?do=delete&uid=${list2[id]}'>X</a><br/>";
}

$form="<div><form method='get' action=''><input type='hidden' name='do' value='delete' ><input type='text' name='uid'><input type='submit' value='删除'/></form></div>";
$title='删除用户';
include("../html.php");
    echo "</head><body><h1>删除用户</h1><hr/>$out<p>${content}</p><div>删除用户（填写用户ID）<br/>${form}
    <hr/>${uncon}${ys}</div><hr/><a href='index.php'>后台列表</a>.<a href='../index.php'>社区首页</a></body></html>";