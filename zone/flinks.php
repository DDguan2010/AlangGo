<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("good.php");
$do=$_GET["do"];
$time=time();
if($do="add")
{
     $un=$_POST["un"];
     $pw=$_POST["pw"];
     $url=$_POST["url"];
     $sname=$_POST["sname"];
     $lname=$_POST["lname"];
    if($_POST["B1"]!=null)
    {
       
        if(empty($un) or empty($pw) or empty($url) or empty($sname) ){
		$p="带*项不能为空不能为空<br/>";
		}elseif(mysql_fetch_array(mysql_query("SELECT * FROM flinks  WHERE un='$un'"))){
		$p="<font color='red'>此用户名已被注册</font><br/>";
            $un="";
		}elseif(mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE url= '$url'"))){
		$p="<font color='red'>此网站已被注册，请等待站长审核或联系站长！</font><br/>";
		}
        elseif(!preg_match('/^http://.+/usi',$url)){
		$p="<font color='red'>请检查网站链接！</font><br/>" ;
		}
		elseif(!preg_match("/^[0-9a-zA-Z]{2,15}$/",$pw)){
		$p="<font color='red'>密码不能包含特殊符号！</font><br/>";
            $pw="";
		}elseif(mb_strlen($lname,'utf-8')>60){
		$p="<font color='red'>简介字符长度应在60个以内！</font><br/>";
            $sname="";
		}
        elseif(mb_strlen($sname,'utf-8')>4 || mb_strlen($sname,'utf-8')<2){
		$p="<font color='red'>网站简称应为2到4个字！</font><br/>";
            $sname="";
		}
        else{
            $lid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='lid'"));
			$lid=$lid["num"]+1;
            mysql_query("INSERT INTO finks(lid,nu,pw,time,url,sname.lname) VALUES ($lid, '$nu', '$pw', $time,'$url', '$sname','$lname')" );
			mysql_query("UPDATE z_jsq SET num='$lid' WHERE name='lid' ");
            $p="<font color='green'>添加完成，请将http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?blik=${lid}加入你的网站！</font><br/>"; 
        }
        
    }
    else
    {
        $add_form="<form method=\"POST\" action=\"\">
        *管理用户名:<br/><input type=\"text\" name=\"un\" value=\"${un}\"//>(推荐命名中不包含中文)<br/>
        *管理密码: <br/><input type=\"text\" name=\"pw\" value=\"${pw}\"/><br/>
        *网址:（以http://开头） <br/><input type=\"text\" name=\"url\" value=\"http://\"/><br/>
    	 简介: <br/><input type=\"text\" name=\"lname\" value=\"${lname}\"/><br/>
		*简称: （2——4个字符）br/><input type=\"text\" name=\"sname\" value=\"${sname}\"/><br/>
    	<input type=\"submit\" value=\" 添 加 \" name=\"B1\"/></form>";
    }
}