<?php
$vdir="../";
header ( 'Content-Type:text/html;charset=utf-8');
include("../good.php");
include("../../conn.php");
//include("include.php");
include("../user.php");
$time=time();
$uid=$_GET["uid"];
$myid=$_COOKIE["uid"];
 if($myid==1 && ($_GET["uid"]==1|| empty($_GET["uid"]) ))           

           {
  
               $t_admin="<div style=\"\"><a href=\"../admin/\"><font color=\"green\">网站后台管理</font></a></div>";

           }

if($_GET["uname"]!=null)
     {
	 $uid=@mysql_fetch_array(mysql_query("SELECT id FROM v_user WHERE name='$_GET[uname]'"));
	 @$uid=$uid["id"];
	}
if($uid==NULL)
{
    $uid=$myid;
}

if(!mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id='$uid'")))
{header("location:../login.php");}
$uinfo=uinfo($uid);
$uname=$uinfo["name"];

//发內信
$mailtxt=htmlspecialchars($_POST["mailtxt"], ENT_QUOTES);
$mailtxt=addslashes($mailtxt);
$mid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='mid'"));
$mid=$mid["num"]+1;

if(!empty($mailtxt) )
{
    is_login(0);
    if(mb_strlen($mailtxt,'utf-8')>1000)
    {
        $out="<p><font color='red'>内信长度应小于1000字!</font></p>";
    }
       elseif(mysql_query("INSERT INTO z_mail(fromid,toid,mid,uid,txt,time) VALUES ($myid,$uid,$mid,0,'$mailtxt',$time)" ) && mysql_query("UPDATE z_jsq SET num='$mid' WHERE name='mid'") )
       { 
    
           $out="<p><font color='green'>内信发送成功。</font></p>";
       }
 }


       //关注
       if(!empty($myid) && $uid!=$myid)
       {
           
           
            //加关注操作
           if($_GET["aa"]==1)
           {
            

               if(!mysql_fetch_array(mysql_query("SELECT * FROM z_att WHERE beid=${uid} and frid=${myid}")))
               {
                   $time=time();
                   mysql_query("INSERT INTO z_att(frid,beid,time) VALUES ($myid,$uid,$time)" );
                   $out="<p><font color='green'>加关注成功。</font></p>";
               }
           }                                 

           
           //取消关注 
           if($_GET["aa"]==2)
           {            
               if(!mysql_fetch_array(mysql_query("SELECT * FROM z_att WHERE beid=${uid} and frid=${myid}")))  
               {
               } 
                else
               {
                  
                   mysql_query("DELETE FROM z_att WHERE beid=${uid} and frid=${myid}" );
                   $out="<p><font color='green'>已取消关注。</font></p>";
               }
           }  

           
           //判断是否已关注
           if(@mysql_fetch_array(mysql_query("SELECT * FROM z_att WHERE frid=${myid} and beid=$uid")))
           {
               $is_att="<span class=\"txt-fade\">[<a href=\"?uid=${uid}&uname=${_GET[uname]}&aa=2\">取消关注</a>]</span>";             
           }
           else
           {
               $is_att="<span class=\"txt-fade\">[<a href=\"?uid=${uid}&uname=${_GET[uname]}&aa=1\">关注Ta</a>]</span>";           
           }       
          
       }


//被关注的条数
$att_sql="SELECT * FROM z_att WHERE beid=${uid}";
$list_att=mysql_query($att_sql);

if($att_num=mysql_num_rows($list_att))
{}
else{
    $att_num=0;
}
    


$list1=mysql_query("SELECT * FROM z_txt WHERE uid='${uid}' AND lb=0 ORDER BY time DESC LIMIT 0,3");

while($list2=mysql_fetch_array($list1))
{
    
    
$hfnum=mysql_num_rows(mysql_query("SELECT * FROM z_hf WHERE zid='${list2[zid]}' "));
$txt=ctxt($list2["txt"]);
    
    //读取说说关联图片 并附加在后面显示
    if($pic_up=mysql_fetch_array(mysql_query("SELECT * FROM z_up WHERE zid='{$list2{zid}}'") ))
     {
        $s3= new SaeStorage();
        $gurl=$s3->getUrl("pic",$pic_up[name]);
        $txt=$txt."<br/>[img=${gurl}][/img]";
    
     }
    
$txt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU' ,'<a href="\\1">\\2</a>' ,$txt );
$txt = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$txt);
$txt = preg_replace('!\[img=upload/(.*)\](.*)\[/img\]!siuU','<img src="../upload/\\1" alt="\\2" onload="if(this.height>160)this.height=160" />',$txt);
$txt = preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<img src="http://what.sinaapp.com/t.php?purl=\\1" alt="\\2" />',$txt);
           $txt = preg_replace('!\[img\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt=""  /></a>',$txt);
$txt = preg_replace('!@(\d{1,9})\;!uU','<a href="../user/info.php?uid=\\1">@\\1;</a>',$txt);
$txt= preg_replace('!@(.{2,30})\s{1}!uU','<a href="../user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
    $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="../pic/face/\\1.gif"></img>',$txt);
$time0=ctime($list2["time"]);
$ss=$ss."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'>${txt}</p><p class='tabs-1'><span class='txt-fade'>[${time0}]</span><a href='../read.php?zid=${list2[zid]}'>评论(${hfnum})</a><br/></p></div>";
}




//帖子
$list1=mysql_query("SELECT * FROM z_txt WHERE uid='${uid}' AND lb=1 ORDER BY time DESC LIMIT 0,3");

while($list2=mysql_fetch_array($list1))
{
$hfnum=mysql_num_rows(mysql_query("SELECT * FROM z_hf WHERE zid='${list2[zid]}' "));
$head=ctxt($list2["head"]);
    //判断是否为精华帖
            if($list2["zt"]==1)
            {
                $is_great="<span class=\"z_great\">[精]</span>";
            }
$time0=ctime($list2["time"]);
$tz=$tz."<div class='spacing-5 border-btm bg-alter'>
<p class='tabs-1'>${is_great}<a href='../read.php?zid=${list2[zid]}'>${head}</a></p><pclass='tabs-1'><span class='txt-fade'>[${time0}]</span><a href='../read.php?zid=${list2[zid]}'>评论(${hfnum})</a><br/></p></div>";
unset($is_great);

}
if($uid==$_COOKIE["uid"])
{$title="我的主页";}
else
{$title="${uname}的主页";}
$mail_form="<div><form method='POST' action='?uid=${uid}'><textarea name='mailtxt' rows='2'></textarea><br/>
<input type='submit' class='btn-s' value='确定发送 ' /> </div>";
$mu0="../";
include("../html.php");
echo"<head><body>${head}${out}<p><span class='txt-fade'>${title}</span></p><p class='tabs-1'>呢称：${uname}${is_att}<br/>
ID：${uid}<br/>Email：${uinfo[email]}<br/>注册时间：${uinfo[reg_time]}<br/>关注Ta的人数：${att_num}</p>
${t_admin}
<div class='module-title'>说说</div>${ss}<a href='ulist.php?uid=${uid}'>查看更多</a>
<div class='module-title'>帖子</div>${tz}<a href='ulist.php?uid=${uid}&do=1'>查看更多</a>
<div class='module-title'>发送内信</div>
<div class='spacing-5 border-btm bg-alter '>${mail_form}</div>
${foot}";