<?php
include("check.php");
include("../user.php");
header("Content-Type: text/html; charset=utf-8");
$nsn=$_POST["nsn"];
$nkw=$_POST["nkw"];
$nun=$_POST["nun"];
$npw=$_POST["npw"];
$nsmtp=$_POST["nsmtp"];
$nsmhead=$_POST["nsmhead"];
$nsmtxt=$_POST["nsmtxt"];
if($_POST['ok']!=null)
{
    mysql_query("UPDATE z_set SET val='$nsn' WHERE name='sname'");
    mysql_query("UPDATE z_set SET val='$nkw' WHERE name='kword'");
    mysql_query("UPDATE z_set SET val='$nun' WHERE name='set_mail_un'");
    mysql_query("UPDATE z_set SET val='$npw' WHERE name='set_mail_pw'");
    mysql_query("UPDATE z_set SET val='$nsmtp' WHERE name='set_mail_smtp'");
    mysql_query("UPDATE z_set SET val='$nsmhead' WHERE name='set_mail_head'");
    mysql_query("UPDATE z_set SET val='$nsmtxt' WHERE name='set_mail_txt'");
    
    $out="<div><font color='green'>更新成功。</font></div>";
}
    
$sname=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='sname' "));
$sname=$sname['val'];
$kword=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='kword' "));
$kword=$kword['val'];
$un=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_un' "));
$un=$un['val'];
$pw=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_pw' "));
$pw=$pw['val'];
$smtp=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_smtp' "));
$smtp=$smtp['val'];
$smhead=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_head' "));
$smhead=$smhead['val'];
$smtxt=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='set_mail_txt' "));
$smtxt=$smtxt['val'];


$content="<div><form method='POST' action=''>
网站名称:<br/><input type='text' name='nsn' value='${sname}'/><br/>
关键词:(多个用半角符逗号隔开)<br/><input type='text' name='nkw' value='${kword}'/><br/>
找回密码——邮件设置（用于给用户发送邮件）<br/>
邮箱:<input type='text' name='nun' value='${un}'/><br/>
密码:<input type='text' name='npw' value='${pw}'/><br/>
SMTP:<input type='text' name='nsmtp' value='${smtp}'/><br/>
比如qq邮箱是smtp.qq.com，新浪邮箱是smtp.sina.com
<br/>
邮件标题:<input type='text' name='nsmhead' value='${smhead}'/><br/>
邮件类容:<input type='text' name='nsmtxt' value='${smtxt}'/>(可用\\n进行换行)<br/>
<input type='submit' value='更 新' name='ok'/>
</form></div>";
$title='网站基本信息设置';
echo "</head><body><h1>版块管理</h1><hr/>$out<div>${content}</div><hr/><a href='index.php'>后台列表</a>.<a href='../index.php'>社区首页</a></body></html>";