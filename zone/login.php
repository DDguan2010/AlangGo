<?php
include("../conn.php");
include_once("good.php");
$title="用户登录";
$uid=$_COOKIE['uid'];
$name=$_POST['name'];
$password=$_POST['password'];
if($name!=NULL ||$password!=NULL)
{

    if(mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE (name='${name}' AND password='${password}')")))

    {

        $uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE (name='${name}' AND password='${password}')"));
        
        $code_a=mysql_fetch_array(mysql_query("SELECT * FROM user_c WHERE id= $uinfo[id] "));
        if($code_a==null)
        {
            mysql_query("INSERT INTO user_c(id) VALUES ( $uinfo[id] )" );
        }
        else
        {
            $code=$code_a["i_code"];
        }
        
       
        if($code==null || $_POST['c_code']==1)
        {
            $code=time().rand(10000000,99999999);
            mysql_query("UPDATE user_c SET i_code='$code' WHERE id='$uinfo[id]' ");
        }

        setcookie("uid",$uinfo[id],time()+24*3600*30);
        setcookie("u_code",$code,time()+24*3600*30);


        $p="<div class='main4'>${uinfo['name']},欢迎回来!</div>";
         $uid=$_COOKIE['uid'];

        $ok=1;
    }


    else
    {
        $p="<div class='main4'><font color=\"red\">用户名或密码错误!</font></div>
        <form method='POST' action='login.php'>
        <div style=\"margin:5px 2px\">用户名:<input type='text' name='name' value='${name}' /></div>
        <div style=\"margin:5px 2px\">密 码: <input type='text' name='password' /></div><input type='submit' value=' 登 录'  /> 
        <input type=\"checkbox\" name=\"c_code\" value=\"1\" />更新安全码
        </form>";  
    }
}

else
{
    $p="<form method='POST' action=''>
    <div style=\"margin:5px 2px\">用户名:<input type='text' name='name' /></div>
    <div style=\"margin:5px 2px\">密 码: <input type='text' name='password' /></div><input type='submit' value=' 登 录 ' />
    <input type=\"checkbox\" name=\"c_code\" value=\"1\" />更新安全码</form>";
}

if($ok!=1)
{
    $l2='<br/>1.没有账号?赶紧<a href="reg.php">注册</a>一个吧<br/>2.你也可以<a href="pushuser.php">找回账号与密码</a>';
}

if($uid!=NULL && $ok!=1)
{
    $l="<div style=\"color:red\">您已有用户登录，要重新登录？</div>";
}
$title="用户登录";
include("html.php");
echo "</head><body>${head}<span class='txt-fade'>用户登录</span><br/>${l}${p}${l2}${foot}";
?>