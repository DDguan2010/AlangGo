<?php
header ( 'Content-Type:text/html;charset=utf-8 ' );
$vdir="../";
include("../../conn.php");
include("../user.php");
include("../good.php");
$myid=$_COOKIE["uid"];
$uid=$_GET["uid"];
$user=uinfo($uid);
$uname0=$user["name"];
$do=$_GET["do"];
$yurl="ulist.php?uid=${uid}&do=${do}&";
if($do==1){$sql0=" AND lb=1";$l="帖子";}else{$sql0=" AND lb=0";$l="说说";}
$page=$_GET["p"];
$time=time();

$asql0="SELECT * FROM z_txt where uid=${uid} ${sql0}";

$list0=mysql_query($asql0);

if(!mysql_fetch_array($list0)){$content="无"; }

else{$all_num=mysql_num_rows($list0);

$max_page=ceil($all_num/15);

if($page==NULL || $page<=0 ||$page>$max_page)
{$page=1;}

$num1=($page-1)*15;
$num2=15;

if($max_page>1)

{

    if($page>1)
    {
        $p2=$page-1;
        $sy="<a href='${yurl}p=${p2}'>上页</a>";
    }
    
    if($page<$max_page)
    {
        $p3=$page+1;
        $xy="<a href='${yurl}p=${p3}'>下页</a>";
    }
$ys="<form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='hidden' name='do' value='${do}' /><input type='hidden' name='bid' value='${bid}' /><input type='submit' value='跳' /></form>";
}
     
$asql="SELECT * FROM z_txt  WHERE uid=${uid} ${sql0} ORDER BY time DESC LIMIT $num1,$num2";

$list1=mysql_query($asql);

$xh=$all_num-($page-1)*15;

$nnn=15;
     
     
while($list2=mysql_fetch_array($list1) )
{
    if(($nnn%2)==1)
        {$bg=" bg-1 ";}
    
$time0=ctime($list2["time"]);
$txt=ctxt($list2["txt"]);
$uname=uinfo($list2["uid"]);
$uname=$uname["name"];
$asql="SELECT * FROM z_hf WHERE zid='${list2[zid]}' ";
$list3=mysql_query($asql);
$hfnum=mysql_num_rows($list3);
    if($list2['y1']==NULL)
    {
        $znum=0;
    }
    else
    {
        $znum=$list2['y1'];
    }
if($list2['lb']==1)
{
$head=ctxt($list2["head"]);
   //判断是否为精华帖
            if($list2["zt"]==1)
            {
                $is_great="<span class=\"z_great\">[精]</span>";
            }
    $content=$content."<div class='spacing-5 border-btm ${bg}'>
<p class='tabs-1'>
<span class='txt-fade'>${xh})</span>${is_great}<a href='../read.php?zid=${list2[zid]}'>${head}</a></p><p class='tabs-1'>
<span class='txt-fade'>[${time0}]</span><a href='../read.php?zid=${list2[zid]}'>评论(${hfnum})</a></p></div>";
    unset($is_great);
}
else{
    
    //读取说说关联图片 并附加在后面显示
    if($pic_up=mysql_fetch_array(mysql_query("SELECT * FROM z_up WHERE zid='{$list2{zid}}'") ))
     {
        $s3= new SaeStorage();
        $gurl=$s3->getUrl("pic",$pic_up[name]);
         $txt=$txt."<br/>[img=${gurl}][/img]";
    
     }
    
$txt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!isuU' ,'<a href="\\1">\\2</a>' ,$txt );
$txt = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$txt);
$txt = preg_replace('!\[img=upload/(.*)\](.*)\[/img\]!siuU','<img src="../upload/\\1" alt="\\2" onload="if(this.height>160)this.height=160" />',$txt);
$txt = preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="\\2" /></a>',
$txt);
           $txt = preg_replace('!\[img\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt=""  /></a>',$txt);
$txt = preg_replace('!@(\d{1,9})\;!suU','<a href="../user/info.php?uid=\\1">@\\1;</a>',$txt);
$txt= preg_replace('!@(.{2,30})\s{1}!suU','<a href="../user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
    $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="../pic/face/\\1.gif"></img>',$txt);
$content=$content."<div class='spacing-5 border-btm ${bg}'>
<p class='tabs-1'><span class='txt-fade'>${xh})</span>${txt}</p><pclass='tabs-1'><span class='txt-fade'>[${time0}]</span><a href='../read.php?zid=${list2[zid]}'>评论(${hfnum})</a></p></div>";}

    unset($bg);
    $nnn--;
    $xh-=1;
}
}
$title="${uname0}的${l}列表";
$mu0="../";
include("../html.php");
echo "</head><body>${head}<div class='module-title'><a href=\"../user/info.php?uid=${uid}\">${uname0}</a>的${l}</div>".$content.$ys.${foot};