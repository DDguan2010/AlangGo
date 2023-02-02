<?php
header('Content-Type:text/html;charset=utf-8');
include("../conn.php");
include("user.php");
include("good.php");
$uid=$_COOKIE["uid"];
$user=uinfo($uid);
$do=$_GET["do"];
$bid=$_GET["bid"];
$page=$_GET["p"];
$px=$_GET["px"]; //帖子排序条件
$time=time();
$title="友友动态";

$sql_px=" ORDER BY time DESC ";
if($do==1)
{
    $url="?do=1&p=${page}";
    $yurl="?do=1&";
    $sql_lb="";
    $main="<span style=\"padding:8px;background:#CACACA;margin:0 0 0 -4px;\">全部</span> <a href='?do=2'>说说</a> <a href='?bid=0'>帖子</a>
    <a href='zwrite.php'><span style=\"font-size:18px;\">发帖</span></a>"; 
}
elseif($do==2)
{
    $main="<a href='?do=1'>全部</a> <span style=\"padding:8px;background:#CACACA;\">说说</span> <a href='?bid=0'>帖子</a> 
    <a href='zwrite.php'><span style=\"font-size:18px;\">发帖</span></a>";
    $yurl="?do=2&";
    $url="?do=2&p=${page}";
    $sql_lb="where lb=0";
}
else
{
    $lb=settle_lt($bid,1);                                           
    $main="<div class=\"lists\"><form method='get' action=''><a href='?do=1'>全部</a> <a href='?do=2'>说说</a>
   <span style=\"padding:8px;background:#CACACA;\"> ${lb}<input type='submit' value='帖子' /> 
   </span> <a href='zwrite.php'><span style=\"font-size:18px;\">发帖</span></a>
    </form></div>";
    $url="?bid=${bid}&p=${page}";
    $yurl="?bid=${bid}&"; 
    $sql_lb="where lb=1"; 

    if($bid>0)
    {
        $sql_bk="and y3=${bid}"; 
        
    }
    
    //px 为1时按访问量排序  为2时显示精华帖 为3时显示置顶帖
    if($px==1)
    {
        $sql_px=" ORDER BY y2 DESC ";
        $yurl.="px=1&";
        $pxout="<a href='?bid=${bid}'>默认</a> 热门 <a href='?bid=${bid}&px=2'>精华</a> <a href='?bid=${bid}&px=3'>置顶</a>";
        
    }
    elseif($px==2)
    {
        $sql_px=" and zt=1 ORDER BY time DESC ";
        $yurl.="px=2&";
        $pxout="<a href='?bid=${bid}'>默认</a> <a href='?bid=${bid}&px=1'>热门</a> 精华 <a href='?bid=${bid}&px=3'>置顶</a>";
    }
    elseif($px==3)
    {
        $sql_px=" and tip=1 ORDER BY time DESC ";
        $yurl.="px=3&";
        $pxout="<a href='?bid=${bid}'>默认</a> <a href='?bid=${bid}&px=1'>热门</a> <a href='?bid=${bid}&px=2'>精华</a> 置顶";
    }
    else
    {
        $pxout="默认 <a href='?bid=${bid}&px=1'>热门</a> <a href='?bid=${bid}&px=2'>精华</a> <a href='?bid=${bid}&px=3'>置顶</a>";
    }
    $main="${main}<div style=\"padding:8px;background:#CACACA;\">排序条件:${pxout}</div>";
    
}





//赞操作
$good=$time-$_GET["z"];
if($good<600 && $_GET["zid"]!=NULL)
{
    good($uid,$_GET["zid"]);
    header("location:list_s.php${url}");
}


$asql0="SELECT * FROM z_txt ${sql_lb} ${sql_bk}  ${sql_px} ";
$list0=mysql_query($asql0);

if(@!mysql_fetch_array($list0))
{
    $content="<p>还没有人发布内容哦！</p>";
}
else
{

    $all_num=mysql_num_rows($list0);
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
            $sy="<a href='${yurl}p=${p2}'>上页</a>";
        }
        
        if($page<$max_page)
        {
            $p3=$page+1;
            $xy="<a href='${yurl}p=${p3}'>下页</a>";
        }


        $ys="<form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'>
        <input type='hidden' name='do' value='${do}' /><input type='hidden' name='bid' value='${bid}' /><input type='hidden' name='px' value='${px}' />
        <input type='submit' value='跳' /></form>";
    }

    $asql="SELECT * FROM z_txt ${sql_lb} ${sql_bk} ${sql_px} LIMIT $num1,$num2";
    $list1=mysql_query($asql);
    $xh=$all_num-($page-1)*15;
    $nnn=15;
    while($list2=mysql_fetch_array($list1))
    {
        if(($nnn%2)==1)
        {$bg=" bg-1 ";}

        //$time0=date("Y.m.d H:i",$list2["time"]);
        $time0=ctime($list2["time"]);
        $txt=ctxt($list2["txt"]);
        $uname=uinfo($list2["uid"]);
        $uname=$uname["name"];
        $asql="SELECT * FROM z_hf WHERE zid='${list2[zid]}' ";
        $list3=mysql_query($asql);
        $hfnum=mysql_num_rows($list3);
        

        //判断是否已赞

        if(@mysql_fetch_array(mysql_query("SELECT * FROM z_good WHERE zid=${list2[zid]} and uid=$uid ")) )
        {
            $is_good="已赞";
        }
        else
        {
            $is_good="赞";
        }


        //赞 的数量

        if($list2['y1']==NULL)
        {
            $znum=0;
        }
        else
        {
            $znum=$list2['y1'];                    
                        
        }

        
        //lb为1时表示它是帖子
        if($list2['lb']==1)
        {

            $head=ctxt($list2["head"]);
                   
            //判断是否为精华帖
            if($list2["zt"]==1)
            {
                $is_great="<span class=\"z_great\">[精]</span>";
            }
        
            if(mb_strlen($txt,'utf-8')>60)
            {
                $txt=mb_substr($txt,0,60,"utf-8");             
                $txt=$txt."……";
            }
           
            $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt);
            
            if($list2["zt"]==2)
            {
                $out_zt="更新了";
            } 
            else
            {
  
                $out_zt="发帖";  
   
            }

            $content=$content."<div class='spacing-5 border-btm ${bg}'>
            <p class='tabs-1'><span class='txt-fade'>${xh})</span>${is_great}<a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> ${out_zt}《
            <a href='read.php?zid=${list2[zid]}'>${head}</a>》<br/>${txt}</p><p class='tabs-1'><span class='txt-fade'>[${time0}]</span>
            <a href='${url}&zid=${list2[zid]}&z=${time}'>${is_good}(${znum})
            </a>.<a href='read.php?zid=${list2[zid]}'>评论(${hfnum})</a></p></div>";
             unset($is_great);
        }

        else
        {
    
            //读取说说关联图片 并附加在后面显示
            if($pic_up=mysql_fetch_array(mysql_query("SELECT * FROM z_up WHERE zid='{$list2{zid}}'") ))
            {
                $s3= new SaeStorage();
                $gurl=$s3->getUrl("pic",$pic_up[name]);
                $txt=$txt."<br/>[img=${gurl}][/img]";
    
            }

          
            $txt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU' ,'<a href="\\1">\\2</a>' , $txt );
            $txt = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$txt);
            $txt = preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="\\2"/></a>',$txt);
           $txt = preg_replace('!\[img\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="" /></a>',$txt);
            $txt = preg_replace('!@(\d{1,9})\;!iuU','<a href="user/info.php?uid=\\1">@\\1;</a>',$txt);
            $txt= preg_replace('!@(.{2,30})\s{1}}!isuU','<a href="user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
            $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt);
            $content=$content."<div class='spacing-5 border-btm ${bg}'>
            <p class='tabs-1'><span class='txt-fade'>${xh})</span><a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> : ${txt}</p><p class='tabs-1'>
            <span class='txt-fade'>[${time0}]</span><a href='${url}&zid=${list2[zid]}&z=${time}'>${is_good}(${znum})</a>.<a href='read.php?zid=${list2[zid]}'>评论(${hfnum})</a></p></div>";}

        /**$content=$content."<p>${xh})<a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> : ${txt}<br/>[${time0}]<a href='?zid=${list2[zid]}&z=${time}&p=${page}'>赞(${znum})
        </a>.<a href='read.php?zid=${list2[zid]}'>评论(${hfnum})</a></p>-------------------";**/

        unset($bg);
        $nnn--;
        $xh-=1;
    }
}
$title="列表";
include("html.php");
echo "</head><body>${head}<div class='module-title'><div style=\"padding:7px 0px;\">${main}</div></div>".$content.$ys.${foot};
?>