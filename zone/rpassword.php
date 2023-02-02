<?php
header("Content-Type: text/html; charset=utf-8");
include("../conn.php");
include("good.php");
include("user.php");
$uid=$_COOKIE['uid'];
$rpassword=$_POST['rpassword'];
$rpassword2=$_POST['rpassword2'];
$password=$_POST['password'];
$title="修改密码";
is_login(0);$uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id='${uid}'"));
if($password==NULL){}
elseif($rpassword!=$rpassword2){
$p="<font color='red'>新密码前后不一致</font><br/>";
}elseif(!preg_match("/^[0-9a-zA-Z]+$/",$rpassword)){
$p="<font color='red'>密码不能包含特殊符号！</font><br/>";
}elseif( ($uinfo[password]!=$password && $password!=NULL) || ($rpassword!=NULL &&$password==NULL) ){
$p="<font color='red'>原密码错误</font><br/>";
}
elseif($password!=NULL && $rpassword!=NULL)
{mysql_query("UPDATE v_user SET password='$rpassword' WHERE id='$uid'");
$p="<font color='green'>密码修改成功!</font><br/>";
$do=1;}
include("html.php");
echo "</head><body>${head}<p><span class='txt-fade'>『 修改密码 』</span></p>${p}<form method='POST'action=''>
原密码: <br/><input type='text' name='password' /><br/>
新密码: <br/><input type='password' name='rpassword'/><br/>确认新密码:<br/><input type='password' name='rpassword2' /><br/>
<input type='submit' value=' 完 成 ' /> </form>${foot}";
?>