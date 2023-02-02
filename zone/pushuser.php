<?php
header("Content-Type: text/html; charset=utf-8");
include("../conn.php");
include("good.php");
$title="找回账号密码";
$email=$_POST[email];
$uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE email='${email}'"));
if($email!=NULL && !preg_match("/^[0-9a-z]+@([0-9a-z]*[0-9a-z].)[a-z]{2,3}$/",$email)){echo "邮箱格式不正确！返回<a href=''>重新填写</a>";}
elseif($email!=NULL && $uinfo==NULL){echo "邮箱不正确或未注册！返回<a href=''>重新填写</a>";}
elseif($email!=NULL && $uinfo!=NULL)
{
    //读取设置
$txt=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_txt'")); 
$txt=$txt["val"];

$t=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_head'"));
$t=$t["val"];

$un=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_un'")); 
$un=$un["val"];
$pw=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_pw'"));
$pw=$pw["val"];
$smtp=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_smtp'"));
$smtp=$smtp["val"];

//$txt = "来自易助理(http://vwap.tk)的账号密码找回£££账号:$uinfo[name]£££ID:$uinfo[id]£££密码:$uinfo[password]£££易助理——您的网络助理。";
 // 如果一行大于 70 个字符，请使用$txt = wordwrap($txt,70); 
// 发送邮件 

//mail($email,"账号密码找回——易账号密码找回——易助理(http://vwap.tk)",$txt); 
    $txt = "${txt}\n账号:$uinfo[name]\nID:$uinfo[id]\n密码:$uinfo[password]\n创 意 改 变 世 界 。";

    //$t="账号密码找回—易社区";
//file_get_contents("email.php?e=${email}&t=${t}&m=${txt}")

$t=urlencode($t);
$txt=urlencode($txt);

//$email=urlencode($email);
$smtp=urlencode($smtp);

$mail = new SaeMail();
    if($mail->quickSend($email,$t,$txt,$un,$pw,$smtp,25))
    {
        $ba="<font color='green'>后台正在将您的账号信息发送至您的邮箱，请注意查收！</font>";
    }
    else
    {
        $ba="<font color='red'>系统出错.</font>";
    }
//mail($email,$t,$txt,"From:为易小站");
include("html.php");
    echo "</head><body>${head}<p>${ba}</p>";
}
else
{
include("html.php");
echo "</head><body>${head}";
echo <<<html
<p><span class='txt-fade'>密码与账号找回</span></p><div>请输入你注册时填写的邮箱，系统会将您的账号与密码等信息发至您的邮箱！</div><br/><form method="POST" action="">
<input type="text" name="email" size="12" />
<input type="submit" value="我要找回" name="B1"/>
html;
}
echo "${foot}";
?>