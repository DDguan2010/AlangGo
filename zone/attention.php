<?php
header  (  'Content-Type:text/html;charset=utf-8 ' );
include("../conn.php");
include("user.php");
include("good.php");
$uid=$_COOKIE["uid"];
$page=$_GET["p"];
is_login(0);
$att1=mysql_query("SELECT * FROM z_att WHERE frid=${uid}");
//$att_num=mysql_num_rows($list_att);


if(@!mysql_fetch_array($att1))
{
   $content="你暂时还没有关注任何人哦！";
}
else
{

    $all_num=mysql_num_rows($att1);
    $max_page=ceil($all_num/15);
    if($page==NULL || $page<=0 || $page>$max_page)
    {
        $page=1;
    }
    $num1=($page-1)*15;
    $num2=15;
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


        $ys="<p><form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'>
        <input type='hidden' name='do' value='${do}' /><input type='hidden' name='bid' value='${bid}' /><input type='submit' value='跳' /></form></p>";
    }

    $att_sql="SELECT * FROM z_att  WHERE frid=${uid}  ORDER BY time DESC LIMIT $num1,$num2";
    $att_list=mysql_query($att_sql);
    $xh=$all_num-($page-1)*15;
    while($list=mysql_fetch_array($att_list))  
    {
         $uname=uinfo($list["beid"]);
        $date=date("Y.m.d",$list[time]);
        $content=$content."${xh})<a href=\"user/info.php?uid=${list[beid]}\"> ${uname[name]}</a> (${date})<br/>";

           $xh-=1;
    }
    
}



$title="我关注的人";
include("html.php");
echo "</head><body>${head}<div class='module-title'>我关注的人</div><p>".$content."</p><p class=\"border-btm\"></p>".$ys.${foot};
?>
