<?php
include("css.php");
//include _once("good.php");
$da=date("Y-m-d H:i");
$mu=dirname("http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]").'/'.$vdir;

$hsql="SELECT * FROM z_chat  ORDER BY time DESC LIMIT 0,2";
$hlist1=mysql_query($hsql);
$hlist2=mysql_fetch_array($hlist1);
$htime0=ctime($hlist2["time"]);
$htxt=$hlist2["txt"];
if(strlen($htxt)>120)
{
    $htxt=mb_substr($htxt,0,40,"utf-8");
    $sl="......";
}
$htxt=ctxt($htxt);
        $huname=uinfo($hlist2["uid"]);
        $huname=$huname["name"];
        $htxt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU' ,'\\2',$htxt );
        $htxt = preg_replace('!\[url\](.*)\[/url\]!siuU','\\1',$htxt);
        $htxt= preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','',$htxt);
        $htxt = preg_replace('!@(\d{1,9})\;!iuU','@\\1;',$htxt);
        $htxt= preg_replace('!@(\w{2,30})\s{1}!iuU','@\\1&nbsp;',$htxt);
        $htxt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="'.$vdir.'pic/face/\\1.gif"></img>',$htxt);
        $ct="<div class='chat1'>
        <a href='{$vdir}chat.php'>——聊天室——</a> <span class='txt-fade'>[${htime0}]</span>
        <p ><a href=\"${vdir}user/info.php?uid=${hlist2['uid']}\">${huname}</a>:
        ${htxt}${sl}</p>
        </div>";

$head="<a name=\"top\"></a><div id='main-nav-host'><p><a href='${mu}all_list.php'>大厅</a> <a href='{$mu}user/info.php?uid=$_COOKIE[uid]'>主页</a> <a href='{$mu}attention.php'>关注</a> <a href='{$mu}search_z.php'>搜帖</a> <a href='{$mu}chat.php'>聊天</a> </p></div>";
if($_COOKIE["uid"]!=NULL)
{
$user=is_login(1);
$foot="<p><a href='${mu}user/info.php?uid=$_COOKIE[uid]'>${user}</a> - <a href='{$mu}ulogin.php'>退出</a> - <a href='{$mu}rpassword.php'>改密</a> - <a href=\"#top\">[回顶]</a></p>";
}
else
{$foot="<p><a href='{$mu}login.php'>登录</a> - <a href='{$mu}reg.php'>注册</a> - <a href='{$mu}pushuser.php'>找回密码</a> - <a href=\"#top\">[回顶]</a></p>";
}
//读取foot
@$foot_txt=mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='foot'")); 
@$foot_txt=htmlspecialchars_decode($foot_txt["txt"]);


$foot="<div id='footer'>
${foot}
${ct}
<div id='main-footer'>
<a href=''>刷新</a> <a href='{$mu}list_s.php?do=1'>全部</a> <a href='{$mu}list_s.php?do=2'>说说</a> <a href='{$mu}list_s.php'>帖子</a>
</div>[ 手机访问|<a href='{$mu}../../web/' />电脑访问</a> ]
<br/>
<span class=\"txt-fade\" style=\"font-size: 20px;\">
Time:${da}
</span>
${foot_txt}
</div>
</body></html>";

//读取设置
$sname_txt=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='sname'")); 
$sname_txt=$sname_txt["val"];
$kword_txt=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='kword'"));
$kword_txt=$kword_txt["val"];


//mysql_close($conn);
echo "<!DOCTYPE html PUBLIC'-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta name='viewport' content='width=device-width' />
<meta http-equiv='content-type' content='text/html;charset=UTF-8'>
<meta name='keywords' content='${kword_txt}'>
<script type=\"text/javascript\" src=\"${vdir}../js.js\"></script>
<link rel=\"shortcut icon\" href=\"/favicon.ico\" />
<style type='text/css'>
${css}
</style>
<title>
${title}${sname_txt}
</title>" ;