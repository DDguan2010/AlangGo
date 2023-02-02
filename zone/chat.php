<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
session_start();
include("../conn.php");
include("user.php");
include("good.php");
$ct=$_POST["ct"];
$uid=$_COOKIE["uid"];
$page=$_GET["p"];
$fcid=$_GET["cid"];
$user=is_login(1); 
$time=time();    //时间戳
$title="聊天室";
if($_COOKIE["uid"]!=NULL)
{
       //发言表单
    $form="<div class='form-box margin-t-5 form-box'>
<form method='post' action='' enctype=\"multipart/form-data\">
<div id=\"face_show\"></div>
<textarea name='ct' cols='20' rows='3' id=\"s\"></textarea><br/>
<input type='submit' class='btn-s' value=' 发 言 ' />
<span id=\"doingface\" onclick=\"showFace(this.id,'s');\">+表情</span> <span id=\"doingface\" onclick=\"showa()\">+@</span><div id=\"div1\"></div></form></div>
";

    if(!empty($ct) && $_SESSION['ch']!=$ct)
    {    
        $_SESSION['ch']=$ct;
        $cid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='cid'"));  //读取已有的最大聊天id
        $cid=$cid["num"]+1;
        $ct=trim($ct);
        $ct=substr($ct,0,900);
        $ct=ctoa($ct,$cid);
        $ct==htmlspecialchars($ct, ENT_QUOTES);
        mysql_query("INSERT INTO z_chat(cid,uid,txt,time) VALUES ($cid,$uid,'$ct',$time)" );
        mysql_query("UPDATE z_jsq SET num='$cid' WHERE name='cid'");  //更新计数器
        
    }
    
}

//检查是否没有人发言
$asql0="SELECT * FROM z_chat";
$list0=mysql_query($asql0);
if(!mysql_fetch_array($list0))
{
    $content="无"; 
}
else  //否则
{
    $all_num=mysql_num_rows($list0);
    $max_page=ceil($all_num/10);
    if($page==NULL || $page<=0 ||$page>$max_page)
    {
        if($fcid!==null && $fcid<=$all_num)
        {
            $page=$max_page+1-ceil($fcid/10);
        }
        else
        {
        
            $page=1;
        }
    }
    
   
    $num1=($page-1)*10;
    $num2=10;
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
        $ys="<form method='get' action=''>".$xy."[${page}/${max_page}]".$sy." <input type='text' name='p' maxlength='7' size='2' value='1'><input type='hidden' name='do' value='${do}' />
        <input type='submit' value='跳' /></form>";
}
     
$asql="SELECT * FROM z_chat  ORDER BY time DESC LIMIT $num1,$num2";
$list1=mysql_query($asql);
$xh=$all_num-($page-1)*10;
    $an=0;
    while($list2=mysql_fetch_array($list1) )

    {
        $time0=ctime($list2["time"]);
        $txt=ctxt($list2["txt"]);
        $uname=uinfo($list2["uid"]);
        $uname=$uname["name"];
        $txt= preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU' ,'<a href="\\1">\\2</a>',$txt );
        $txt = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$txt);  
        $txt= preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<a href="\\1"><img src="http://what.sinaapp.com/t.php?purl=\\1" alt="\\2"/></a>',$txt);
        $txt = preg_replace('!@(\d{1,9})\;!iuU','<a href="user/info.php?uid=\\1">@\\1;</a>',$txt);
        $txt= preg_replace('!@(.{2,30})\s{1}!iuU','<a href="user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
        $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt);
        $content=$content."<div class='chat1'>
        <p class='tabs-1'><a href=\"user/info.php?uid=${list2['uid']}\" id=\"aa${an}\">${uname}</a> <span class='txt-fade'>[T:${time0}|N:${xh}]</span></p>
        <p>${txt}</p>
        </div>";

        $xh--;
        $an++;


    }



}

include("html.php");
echo "</head><body>${head}${form}".$content.$ys."
<script>
jsgo(10);
</script>".${foot};
        
function ctoa($txt,$cid)
{
    $txt0=mb_substr($txt,0,30,"utf-8");
    $txt=htmlspecialchars($txt, ENT_QUOTES);
    $txt=addslashes($txt);
    $time=time();
    $aid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='aid'"));
    $aid=$aid["num"]+1;
    $fromid=$_COOKIE["uid"];
    preg_match_all ( '!\@(.{2,30})\s{1}!uU' ,$txt, $list);
    $list=array_unique($list[1]);
    foreach($list as $name)
    {
        if($uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE name='${name}'")) )
        {
            $toid=$uinfo["id"];
            mysql_query("INSERT INTO z_a(fromid,toid,aid,uid,txt,time,y1,y2) VALUES ($fromid,$toid,$aid, 0,'$txt0',$time,'$cid','c')" );
            mysql_query("UPDATE z_jsq SET num='$aid' WHERE name='aid'");
            //$txt = preg_replace("!\@(".$name.") !siuU","[ton=\\1] ",$txt);
            $aid++;
        }
    }
    preg_match_all ( '!@(\d{1,9})\;!uU' ,$txt,$list);
    $list=array_unique($list[1]);
    foreach($list as $toid)
    {
        if(mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= ${toid} ")) )
        {
            mysql_query("INSERT INTO z_a(fromid,toid,aid,uid,txt,time,y1,y2) VALUES ($fromid,$toid,$aid, 0,'$txt0',$time,'$cid','c')" );
            mysql_query("UPDATE z_jsq SET num='$aid' WHERE name='aid'");
            //$txt = preg_replace("!@(".$toid.")\;!siuU","[toi=\\1]",$txt);
            $aid++;
        }
    }
    return $txt;
}
    
?>