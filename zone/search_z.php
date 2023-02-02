<?php
header  (  'Content-Type:text/html;charset=utf-8 ' );
include("../conn.php");
include("user.php");
include("good.php");
$uid=$_COOKIE["uid"];
$page=$_GET["p"];
$st=$_GET["t"];
$st=trim($st);
$time=time();
$time2=$time-3600;
if(!empty($st))
{
    
    
$list1=mysql_query("SELECT * FROM z_search WHERE t='${st}'");
$all_num=mysql_num_rows($list1);
    $out2="<div style=\"color:green\">共找到与“${st}”相关的帖子${all_num}个。</div>";
if($all_num==0)
{
    if(!empty($st))
    {
        $st_arr=array_unique(explode(" ",$st));
        foreach ($st_arr as $stf) 
        {
            $stf = preg_replace('![\W_]!siuU','',$stf);
        //$stf = preg_replace('![\s]+!siu',' ',$stf);
        //$sql_s=$sql_s."  txt LIKE '%{$stf}%' or head LIKE '%{$stf}%' or";
        
            if(!empty($stf) && $stf!=" ")
            {
                $sql_s=$sql_s."  txt LIKE '%{$stf}%'or head LIKE '%{$stf}%' or";
            }
        }
        $sql2="SELECT * FROM z_txt WHERE  (${sql_s} time=1) and lb=1";
        $list2=mysql_query($sql2);
        $sn=mysql_num_rows($list2);
   
        while($list3=mysql_fetch_array($list2))
        {
            mysql_query("INSERT INTO z_search(t,zid,time,ztime) VALUES ('$st',$list3[zid],$time,$list3[time])" ); 
        }
      
    }
}

$list1=mysql_query("SELECT * FROM z_search WHERE t='${st}'");
$all_num=mysql_num_rows($list1);
if($all_num==0)
{
    $content="无";
}
else
{
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
            $sy="<a href='?t=${st}&p=${p2}'>上页</a>";
        }
        
        if($page<$max_page)
        {
            $p3=$page+1;
            $xy="<a href='?t=${st}&p=${p3}'>下页</a>";
        }


        $ys="<form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'>
        <input type='hidden' name='t' value='${st}' /><input type='submit' value='跳' /></form>";
    }

    $asql="SELECT * FROM z_search WHERE t='${st}' ORDER BY ztime DESC LIMIT $num1,$num2";
    $list1=mysql_query($asql);
    $xh=$all_num-($page-1)*15;
    $nnn=15;
    while($list2=mysql_fetch_array($list1))
    {
        if(($nnn%2)==1)
        {$bg=" bg-1 ";}
        
        if($list2=mysql_fetch_array(mysql_query("SELECT * FROM z_txt WHERE zid={$list2[zid]}")))
        {
            $time0=ctime($list2["time"]);
            $uname=uinfo($list2["uid"]);
            $uname=$uname["name"];
            $head=ctxt($list2["head"]);
            $content=$content."<div class='spacing-5 border-btm ${bg}'>
            <p class='tabs-1'><span class='txt-fade'>${xh})</span><a href='read.php?zid=${list2[zid]}'>${head}</a></p>
            <p class='tabs-1'><span class='txt-fade'>[ <a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> | ${time0}]</span></p></div>";
        $nnn--;
        }
        unset($bg);
       
        $xh--;
    }
}

    
}
mysql_query("delete from z_search where time <= ${time2}");

$main="<div style=\"padding: 8px 3px;\">
<form method='get' action=''>
想找些什么呢？(多个关键字用空格隔开)<br/>
<input type=\"text\" name=\"t\" maxlength=\"30\" value=\"${st}\">
<input class='btn-s' type=\"submit\" value=\"完 成\" /> 
</form></div>${out2}";

$title="搜索";
include("html.php");
echo "</head><body>${head}<div class='module-title'><p>${main}</p></div>".$content.$ys.${foot};
?>
