<?php
header("Content-Type: text/html; charset=utf-8");
include("../conn.php");
include("rand_func.php");
include_once("good.php");
$title="用户注册";
$id=mysql_fetch_array(mysql_query("SELECT * FROM v_jsq WHERE name='id'"));
$id=$id["number"]+1;
$name=$_POST[name];
$email=$_POST[email];
$password=$_POST[password];
$password1=$_POST[password1];
$num2=$_POST[num2];
if(empty($name) or empty($email) or empty($password) or empty($password1) or empty($num1) or empty($num2)){
$p="●任何项都不能为空<br/>";
}elseif(!stristr($num1,$num2)){
$p="<font color='red'>验证码输入不正确</font><br/>";
}elseif($password!=$password1){
$p="<font color='red'>密码前后不一致</font><br/>";
}elseif(mysql_fetch_array(mysql_query("SELECT * FROM v_user  WHERE name='$name'  "))){
$p="<font color='red'>此用户名已被注册</font><br/>";

}elseif(mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE email= '$email' "))){
$p="<font color='red'>此邮箱已被使用</font><br/>";
}
elseif(!preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/us',$name)){
$p="<font color='red'>用户名可由数字、小写字母和下划线组成！</font><br/>" ;
}
elseif(!preg_match("/^[0-9a-z]+@([0-9a-z]*[0-9a-z].)[a-z]{2,3}$/",$email)){$p="<font color='red'>邮箱格式不正确</font><br/>";
}elseif(!preg_match("/^[0-9a-zA-Z]+$/",$password)){
$p="<font color='red'>密码不能包含特殊符号！</font><br/>";
}elseif(mb_strlen($name,'utf-8') < 2 or mb_strlen($name,'utf-8')> 15){
$p="<font color='red'>字符长度应在2~15个之间</font><br/>";
}
else{
mysql_query("INSERT INTO v_user(id,name,email,password,reg_time) VALUES ($id, '$name', '$email', '$password', '$datetime')" );
mysql_query("UPDATE v_jsq SET number='$id' WHERE name='id' ");
setcookie("uid",$id,time()+3600*24*30);
$p="恭喜你注册成功<br/>
你的用户名为:$name<br/>
ID:$id <br/>
密码:$password <br/>";
$v=1;

}
include("html.php");
echo "</head><body>${head}<div class='txt-fade'>新用户注册</div><p>${p}</p>";
if($v!=1){
echo <<<htm
<form method="POST" action="">
用户名:<input type="text" name="name" size="12"/><br/>
（可以为中文 长度2~15）<br/>
邮箱:<input type="text" name="email" size="12" /><br/>
（用于密码的找回等服务！）<br/>
密码:<input type="password" name="password" size="12" /><br/>
确认密码:<input type="password" name="password1" size="12" /> <br/>验证码:<input type="text" name="num2" size="8" />
<input type="hidden"  name="num1" value="
htm;
echo  $_SESSION['code'];
echo '"/>'.$_SESSION['code'];
echo <<<html
<br/><input type="submit" value="注 册" name="B1"/> <input type="reset" value="清空" name="B2"/>
</form><br/>
html;
}
echo $foot;
?>
