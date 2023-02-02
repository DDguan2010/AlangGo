<?php
error_reporting(0);
$user_i=$_COOKIE["uid"];
if($user_i!=NULL)
{
    @$u_code_a=mysql_fetch_array(mysql_query("SELECT * FROM user_c WHERE id= ${user_i} "));
    $u_code=$u_code_a["i_code"];
    if( $_COOKIE["u_code"]!=$u_code || $u_code==null)
    {
       setcookie("uid","",time()-1000);
    }
}
?>