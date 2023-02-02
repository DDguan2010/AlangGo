<?php
/**
初始源码由手机编写，故代码很有些繁杂，还望谅解...
**/
header ( 'Content-Type:text/html;charset=utf-8 ');
session_start();
include("../conn.php");
include("user.php");
include("good.php");
$txt=trim($_POST["txt"]);

$uid=$_COOKIE["uid"];
$user=is_login(1);
$time=time();//时间戳
$title="动态大厅";
//关于赞的时间验证
$good=$time-$_GET["z"];

if($good<600 && $_GET["zid"]!=NULL && $uid!=NULL){good($uid,$_GET["zid"]);header("location:all_list.php");}


$date_jt=date("d M Y");
$time_jt=strtotime($date_jt);
$zid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='zid'"));
$zid=$zid["num"]+1;

if(!empty($txt) )
{
   
    is_login(0);    //验证是否登录 good.php中的自定义函数
    //验证要发布的说说
    if(mb_strlen($txt,'utf-8')>350)
    {
$out="说说长度应小于350字!";
    } 
    else
    {
        if( $_SESSION['ss']!=$txt )  //防止重复提交
        {
            $_SESSION['ss']="${txt}";
            $txt=toa($txt,$zid);  // 完成所发说说中的@消息  toa()自定义函数
            //说说的图片附件
            if((($_FILES["file"]["type"] == "image/gif")|| ($_FILES["file"]["type"] == "image/jpeg")||($_FILES["file"]["type"] == "image/png")
                 || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 10000000))
            {
                $pics = explode('.',$_FILES["file"]["name"]);
                $pnum = count($pics);
                $ftype=$pics[$pnum-1];
                $ifilename=time().rand(100,999).".".$ftype;  //随机产生图片文件名
                $s2 = new SaeStorage();
                //move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" .$ifilename);
                //$txt=$txt."<br/>[img=upload/${ifilename}][/img]";
                $s2-> upload('pic',$ifilename,$_FILES['file']['tmp_name']);
                mysql_query("INSERT INTO z_up(zid,uid,name,time,type) VALUES ($zid,$uid,'$ifilename',$time,1)" );      //图片上传记录
                // $txt=$txt."<br/>[img=".$s2->getUrl("pic",$ifilename)."][/img]";
            }
            $txt=htmlspecialchars($txt, ENT_QUOTES);
            $txt=addslashes($txt);
            mysql_query("INSERT INTO z_txt(zid,uid,head,txt,txt2,lb,time,zt,tip,y1) VALUES ($zid,$uid,'', '$txt','', 0,$time,0,0,'0')" );
            mysql_query("UPDATE z_jsq SET num='$zid' WHERE name='zid'");
            $out="说说发布成功。";
        }
    }
}

//当天动态统计
if($today_all_num=mysql_num_rows(mysql_query("SELECT * FROM z_txt WHERE time>${time_jt}")))
{$tan="<font color='red'>(${today_all_num})</font>";}
if($today_ss_num=mysql_num_rows(mysql_query("SELECT * FROM z_txt WHERE time>${time_jt} AND lb=0")) ){$tsn="<font color='red'>(${today_ss_num})</font>";}
if($today_tz_num=mysql_num_rows(mysql_query("SELECT * FROM z_txt WHERE time>${time_jt} AND lb=1")) ){$ttn="<font color='red'>(${today_tz_num})</font>";}

//@消息与內信数统计
if($user !=NULL)
{
    if($anum=mysql_num_rows( (mysql_query("SELECT * FROM z_a WHERE toid='$uid' and uid=0 ")) ))
    {
        $anum="<font color='green'>(+${anum})</font>";
    }
    else{
        $anum=NULL;
    }
    if( $mailnum = mysql_num_rows ( ( mysql_query ( "SELECT * FROM z_mail WHERE toid='$uid ' and uid=0 " ))))
    { 
        $mailnum = "<font color='green'>(+${mailnum})</font>" ;
    }
    else{
        $mailnum = NULL ;
    }
    
    $da=date("Y-m-d H:i:s");
    
    //读取htmls中defined内容
    @$defined_txt=mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='defined'"));
    @$defined_txt=htmlspecialchars_decode($defined_txt["txt"],ENT_QUOTES);
    
    //发说说表单
     $form="<div class='form-box margin-t-5 form-box'>
<form method='post' action='' enctype=\"multipart/form-data\">
<div id=\"face_show\"></div>
<textarea name='txt' cols='20' rows='3' id=\"s\"></textarea>
<div style=\"margin:2px 2px 4px 2px\">
<label for=\"file\"><span style=\"padding: 3px 5px;background-color:#41D6EB;\">+图片:</span></label>
<input type=\"file\" name=\"file\" id=\"file\" /></div>
<input type='submit' class='btn-s' value='发表说说' />
<span id=\"doingface\" onclick=\"showFace(this.id,'s');\">+表情</span> <a href='ubb.html'>[UBB]</a><br/>
<div class=\"txt-fade\"><img src=\"pic/ico/help.png\" />+表情功能无法正常使用？<a href=\"http://what.sinaapp.com/wap/zone/read.php?zid=1221&all=1\">请点我</a></div>
</form></div>
<div class='spacing-5'>
${defined_txt}
<p class=\"tabs-1\">
<a href='read_a.php'><span  class=\"bi\"><span class=\"ico\" style=\"background-position: -25px -149px;\"></span>我的</span>${anum}</a> <a href='read_mail.php'>
<span  class=\"bi\"><span class=\"ico\" style=\"background-position: -25px -223px;\"></span>内信</span>${mailnum}</a> <a href='zwrite.php'><span class=\"bi bi2\"><span class=\"ico\" style=\"background-position: -250px -225px;\"></span>发帖</span></a></p>
</div>";
}
else
{
     $form="<div style=\"max-width: 340px;height:34px;font-size:25px;padding: 0px 0px;margin:0px 0px 8px 0px;color:#fff;\">
    <a href='login.php'><span  style=\"float:left; width: 65%;background:#25CBFF; padding: 5px 0;text-align:center\">登录</span></a>
    <a href='reg.php'><span style=\"float:right; width: 34%;background:#25CBFF;  padding: 5px 0;text-align:center\">注册</span></a></div>";
}
      
  
//置顶贴
$asql="SELECT * FROM z_txt WHERE tip=1 ORDER BY time DESC LIMIT 0,3";
$list_t=mysql_query($asql);
while( $list_t2=mysql_fetch_array($list_t))
{
 
    $t_head=ctxt($list_t2["head"]);
    $out_tip=$out_tip."<div class='spacing-5  bg-alter'><p class='tabs-1'>
      <font color=\"#F32334\">[顶]</font><a href='read.php?zid=${list_t2[zid]}' id=\"a_tip\">${t_head}</a></p></div>";
}


$page=1;  //页面数
$num1=($page-1)*15;
$num2=15;  //每页条数
//数据库读取
$asql="SELECT * FROM z_txt  ORDER BY time DESC LIMIT $num1,$num2";
$list1=mysql_query($asql);
//进入循环读取

$nnn=15;
while($list2=mysql_fetch_array($list1))
{
    if(($nnn%2)==1)
        {$bg=" bg-1 ";}
//$time0=date("Y.m.d H:i",$list2["time"]);
$time0=ctime($list2["time"]);
$txt=$list2["txt"];
$uname=uinfo($list2["uid"]);
$uname=$uname["name"];
$asql="SELECT * FROM z_hf WHERE zid='${list2[zid]}' ";
$list3=mysql_query($asql);
$hfnum=mysql_num_rows($list3);
   
    //是否赞过
    if(@mysql_fetch_array(mysql_query("SELECT * FROM z_good WHERE zid=${list2[zid]} and uid=$uid ")) )
    {
        $is_good="已赞";
    }
    else
    {
        $is_good="赞";
    }
    

    //判断赞的数量
    if($list2['y1']==NULL){$znum=0;}else{$znum=$list2['y1'];}
    
    //判别是否为帖子
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
        $txt=ctxt($txt);
        $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt); 
        if($list2["zt"]==2)
        {
            $out_zt="更新了";
        } 
        else{
            $out_zt="发帖";  
        }
        $content=$content."<div class='spacing-5 border-btm ${bg}'>
<p class='tabs-1'>${is_great}<a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> ${out_zt}《<a href='read.php?zid=${list2[zid]}'>${head}</a>》<br/>${txt}</p>
<p class='tabs-1'><span class='txt-fade'>[${time0}]</span><a href='?zid=${list2[zid]}&z=${time}'>${is_good}(${znum})</a>.
<a href='read.php?zid=${list2[zid]}'>评论(${hfnum})</a><br/></p></div>";
        
        unset($is_great);
    }
    //否则为说说
    else{
    
        //读取说说关联图片 并附加在后面显示
        if($pic_up=mysql_fetch_array(mysql_query("SELECT * FROM z_up WHERE zid='{$list2{zid}}'") ))
        
        {
        $s3= new SaeStorage();
        $gurl=$s3->getUrl("pic",$pic_up[name]);
            $txt=$txt."<br/>[img=${gurl}][/img]";
        }
    

        //说说的ubb代换
        $txt=ctxt($txt);  //good.php 中
        $txt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU' ,'<a href="\\1">\\2</a>',$txt );
        $txt = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$txt);
        $txt= preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="\\2"/></a>',$txt);
         $txt= preg_replace('!\[img\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="" /></a>',$txt);
        $txt = preg_replace('!@(\d{1,9})\;!uU','<a href="user/info.php?uid=\\1">@\\1;</a>',$txt);
        $txt= preg_replace('!@(.{2,30})\s{1}!suU','<a href="user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
        $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt);

        $content=$content."<div class='spacing-5 border-btm ${bg}'>
<p class='tabs-1'><a href=\"user/info.php?uid=${list2['uid']}\">${uname}</a> : ${txt}</p>
<p class='tabs-1'><span class='txt-fade'>[${time0}]</span><a href='?zid=${list2[zid]}&z=${time}'>${is_good}(${znum})</a>.<a href='read.php?zid=${list2[zid]}'>评论(${hfnum})</a><br/></p></div>";
    }
    unset($bg);
    $nnn--;
}
include("html.php");
echo "</head><body>${head}${form}<div class='module'>
<div class='module-title'><a href='list_s.php?do=1'>全部</a>$tan <a href='list_s.php?do=2'>说说</a>$tsn <a href='list_s.php'>帖子</a>$ttn </div><div class='module-content'>
<div class='spacing-5 border-btm bg-alter'>$out_tip</div> ${content}
<a href='list_s.php?do=1&p=2' style=\"color:#F75E08; margin:2px 3px;padding:2px 5px; font-size: 18px;\">更 多 动 态 >>></a></div></div>${foot}";