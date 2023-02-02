<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("cuttxt.php");
include("good.php");
$zid=$_GET["zid"];
$do=$_GET["do"];
$asql="SELECT * FROM z_txt WHERE zid='${zid}' ";
$list1=mysql_query($asql);

if($list2=mysql_fetch_array($list1))
{
}
else
{
    die("内容不存在！");
}

if( is_admin($list2[uid])==0)
{
    die("error");
}

if($do==1)
{
    mysql_query("delete from z_txt where zid=${zid} ");
    mysql_query("delete from z_hf where zid=${zid} ");
      $s2 = new SaeStorage();
    @$upt=mysql_query("SELECT * FROM z_up WHERE zid='{$zid}'");
    while( $pic_up=mysql_fetch_array($upt))
    {
        if($pic_up["type"]==1)
        { 
            
            $s2-> delete( "pic","$pic_up[name]");
        } 
        elseif($pic_up["type"]==2 || $pic_up["type"]==3)      
        {          
              
            $s2-> delete( "dir","$pic_up[name]");
        }
    }

    mysql_query("delete from z_up where zid=${zid} ");
    mysql_query("delete from z_txt where zid=${zid}");
    mysql_query("delete from z_hf where zid=${zid} ");
     $out="<p><font color='green'> 删除成功。</font></p>";
       
}
      
else    
{       
    $out="<p>确定删除它？<br/><a href='read.php?zid=${zid}'>算了，再看看</a><br/><a href='?zid=${zid}&do=1'>确定，以及肯定</a></p>";    
}
$title="动态删除";
include("html.php");
echo "</head><body>${head}<div class='module-title'>${title}</div><p>${out}</p>${foot}";