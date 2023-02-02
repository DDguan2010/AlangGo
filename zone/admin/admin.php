<?php
include("check.php");
include("../user.php");
header("Content-Type: text/html; charset=utf-8");
$do=$_GET["do"];
if($do=="add" && $_POST["auid"]!=NULL )
{
$auid=$_POST["auid"];
if(@mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= $auid ")) && !mysql_fetch_array(mysql_query("SELECT * FROM z_admin WHERE uid= $auid ")) )
{$uinfo=uinfo($auid);
if(mysql_query("INSERT INTO z_admin(uid)VALUES($auid)"))
        {
        $out="<font color='green'>${auid}(${uinfo['name']})添加成功。</font>";
        }
}
elseif(@mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= $auid ")) && @mysql_fetch_array(mysql_query("SELECT * FROM z_admin WHERE uid= $auid ")) )
{$uinfo=uinfo($auid);
$out="<font color='red'>${auid}(${uinfo['name']})已是管理员！</font>";        
}
else{
$out="<font color='red'>无效的用户ID！</font>";        
}
}
$guid=$_GET["uid"];
if($do=="delete" && $_GET["ok"]!=1 && $_GET["uid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_admin WHERE uid= $guid ")) )
{
$uinfo=uinfo($guid);
$out="确定去除 {$uinfo['name']}(${guid}) 的管理权限?<br/><a href='?do='>算了</a>|<a href='?do=delete&uid=${guid}&ok=1'>确定</a>";
}
elseif($do=="delete" && $_GET["ok"]==1 && $_GET["uid"]!=NULL && mysql_fetch_array(mysql_query("SELECT * FROM z_admin WHERE uid= {$_GET['uid']} ")) )
{
if(mysql_query("DELETE FROM z_admin WHERE uid={$_GET['uid']}"))
{
$out="<font color='green'>删除成功。</font>";
}
}
$list1=mysql_query("SELECT * FROM z_admin");
if(!mysql_fetch_array($list1)){$content1="无"; }
else
{
$list1=mysql_query("SELECT * FROM z_admin");
while($list2=mysql_fetch_array($list1) )
{
$uinfo=uinfo($list2["uid"]);
$content1=$content1."${uinfo['name']}(${list2['uid']}) (<a href='?do=delete&uid=${list2['uid']}'>删除</a>)<br/>";
}
}
$add_form="<div><form method='POST' action='?do=add'><input type='text' name='auid'><input type='submit' value='添加' /></div>";
$title='社区管理员';
include("../html.php");
echo "</head><body><h1>社区管理员</h1><hr/>$out<div>${content1}</div><hr/><div>添加管理员（填写用户ID）<br/>$add_form</div><hr/><a href='index.php'>后台列表</a>.<a href='../index.php'>社区首页</a></body></html>";