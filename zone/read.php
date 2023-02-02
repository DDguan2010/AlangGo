<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("cuttxt.php");
include("good.php");

$zid=$_GET["zid"];
$hftxt=trim($_POST["hftxt"]);
$uid=$_COOKIE["uid"];
$user=uinfo($uid);
$time=time();
$good=$time-$_GET["z"];

//相关赞的操作
if($good<600){
good($uid,$zid);
header("location:read.php?zid=${zid}");}

$asql="SELECT * FROM z_txt WHERE zid='${zid}' ";
$list1=mysql_query($asql);
if($list2=mysql_fetch_array($list1)){}else{die("内容不存在！");}
$time0=date("Y.m.d H:i:s",$list2["time"]);
$txt=$list2["txt"];
$uname=uinfo($list2["uid"]);
$uname=$uname["name"];


$hf_form="<div><form method='POST' action='?zid=${zid}'>
<div id=\"face_show\"></div><div id=\"div1\"></div>
<textarea name='hftxt'  rows='2' id=\"s\"></textarea><br/>
<input type='submit' class='btn-s' value='确定回复 ' /> 
<span id=\"doingface\" onclick=\"showa(3)\">+@</span> 
<span id=\"doingface\" onclick=\"showFace(this.id,'s');\">+表情</span></form>
</div>";
$hid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='hid'"));
$hid=$hid["num"]+1;

if(!empty($hftxt) )
{
    is_login(0);
    if(mb_strlen($hftxt,'utf-8')>5000)
    {
        $out="回复内容长度应小于5000字!";
    }
    else
    {
        if($ask==1){$hftxt=$hftxt."@${list2[uid]};";}
        $hftxt=toa($hftxt,$zid);
        //$hftxt=addslashes($hftxt);
        $hftxt=htmlspecialchars($hftxt, ENT_QUOTES);
       
        mysql_query("INSERT INTO z_hf(hid,zid,uid,txt,time) VALUES ($hid,$zid,$uid, '$hftxt',$time)" );
        mysql_query("UPDATE z_jsq SET num='$hid' WHERE name='hid'");
        $out="<div><font color=\"green\">回复成功。</font></div>";
    }

}
 
     
if(@mysql_fetch_array(mysql_query("SELECT * FROM z_good WHERE zid=${zid} and uid=$uid ")) )   
{   
    $znum="<font color=\"red\">${list2['y1']}</font>";    
}
   
else
{      
    $znum=$list2['y1'];            
}

$tool="[ <a href='?zid=${zid}&z=${time}'><img src='pic/good.png' alt='赞' /></a>${znum} ]";

if($list2["lb"]==0)
{
   
    //读取说说关联图片 并附加在后面显示
    if($pic_up=mysql_fetch_array(mysql_query("SELECT * FROM z_up WHERE zid='{$list2{zid}}'") ))
     {
        $s3= new SaeStorage();
        $gurl=$s3->getUrl("pic",$pic_up[name]);
            $txt=$txt."<br/>[img=${gurl}][/img]"; 
     } 
    $txt = preg_replace ('!\[url=(.*)\](.*)\[/url\]!siuU','<a href="\\1">\\2</a>' ,$txt );
	$txt = preg_replace ('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>' ,$txt );
    $txt= preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<div id="tt"><a href="\\1"><img src="http://what.sinaapp.com/t.php?h=320&w=240&purl=\\1" alt="\\2"/></a><br/>
    <span class="txt-fade">点击图片可查看原图</span></div>',$txt);
    $txt= preg_replace('!\[img\](.*)\[/img\]!siuU','<div id="tt"><a href="\\1"><img src="http://what.sinaapp.com/t.php?h=320&w=240&purl=\\1" alt=""  /></a><br/>
    <span class="txt-fade">点击图片可查看原图</span></div>',$txt);
    //$txt= preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<a href="\\1"><img src="\\1" alt="\\2" onload="if(this.width>500)this.width=500"/></a>',$txt);
    $txt = preg_replace('!@(\d{1,9})\;!iuU','<a href="user/info.php?uid=\\1">@\\1;</a>',$txt);
    $txt= preg_replace('!@(.{2,30})\s{1}!iuU','<a href="user/info.php?uname=\\1">@\\1&nbsp;</a>',$txt);
    $txt=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$txt);
    $txt=ctxt($txt);
    if(is_admin($list2["uid"])!=0){$del_cha="<span class='css_admin'><a href='delete.php?zid=${zid}'>删除</a></span>";}
    $time0=ctime($list2["time"]);
    $content=$content."<span class='txt-fade'>${uname}的说说</span><p class='tabs-1 margin-t-5'>
    <a href=\"user/info.php?uid=${list2['uid']}\"   id=\"aa0\" a=\"${uname}\">${uname}</a> : ${txt} <br/><span class='txt-fade'>( ${time0} )</span></p>${tool}${del_cha}<div class='module-title'>回复列表</div>";
    $title="查看说说";
}
elseif($list2["lb"]==1)  //如果lb是1 则它为帖子 
{
    zadd($zid); //访问次数加1

    if(is_admin($list2["uid"])==1){$del_cha="<span class='css_admin'><a href='delete.php?zid=${zid}'>删除</a>|<a href='change.php?zid=${zid}'>修改</a>|<a href='cha_bk.php?zid=${zid}'>移动</a></span>"; $f_del=1;}
    elseif(is_admin($list2["uid"])==(2||3) ){$del_cha="<span class='css_admin'><a href='delete.php?zid=${zid}'>删除</a>|<a href='change.php?zid=${zid}'>修改</a>|<a href='cha_bk.php?zid=${zid}'>移动</a>|<a href='ztop.php?zid=${zid}'>置顶</a>|<a href='zgreat.php?zid=${zid}'>加精</a></span>";
                                         if(is_admin($list2["uid"])==3 ){ $f_del=1;}
                                        }
	
    $head=ctxt($list2["head"]);
     //判断是否为精华帖
    if($list2["zt"]==1)
    {
        $is_great="<span class=\"z_great\">[精]</span>";
    }
    $time0=date("Y-m-d H:i:s",$list2["time"]);
    $n0=$list2['y2']+1;

    if($_GET["all"]==1)
    {
        $txt=$list2["txt"];
    }
    else
    {
        $txt_size=500;
        $all_txt_num=mb_strlen($list2['txt'],"utf-8");
        $max_txt_page=ceil($all_txt_num/$txt_size);
        if($_GET["p"]==NULL || $_GET["p"]<=0 || $_GET["p"]>$max_txt_page){$p=1;}else{$p=$_GET["p"];}
        $txt=mb_substr($list2['txt'],$txt_size*($p-1),$txt_size,"utf-8");
    }
    $txt=ctxt($txt);
    
    
    //附件列表
    $sf = new SaeStorage();
    $file_n=1;
    @$upt=mysql_query("SELECT * FROM z_up WHERE zid='{$zid}'");
    while( $file_up=mysql_fetch_array($upt))
    {
        $fxx=$sf->getAttr("dir",$file_up["name"]);
        $fsize=size2($fxx[length]);
        
        
        $gurl=$sf->getUrl("dir",$file_up[name]);
        
        $ds=mysql_query("SELECT * FROM z_down WHERE name='{$file_up[name]}'");
        if(@$dnum_a=mysql_fetch_array($ds))
        {
        $dnum=$dnum_a["num"];
        }
        else
        {
        $dnum=0;
        }
		if($file_up[file]!=null)
		{
		 $ftext="<div class=\"ftext\">{$file_up[file]}</div>";
		}
        
        if($f_del==1)
        {
            $fdt="|<a href='fdel.php?d=dir&n={$file_up[name]}&zid=${zid}'>删除</a>";
        }
        
        $fil_con=$fil_con."[${file_n}]<a href=\"down.php?d=dir&n={$file_up[name]}\">{$file_up[name]}</a><span class=\"txt-fade\">[{$fsize}|{$dnum}次${fdt}]</span>
        <br/>${ftext}";
        $fdt=null;
		$ftext=null;
        if($file_up["type"]==3)
        {
             $txt= preg_replace('!\[t'.$file_n.'\]!siuU','<div id="tt"><a href="http://what-dir.stor.sinaapp.com/'.$file_up[name].'">
           <img src="http://what.sinaapp.com/t.php?h=320&w=240&purl=http://what-dir.stor.sinaapp.com/'.$file_up[name].'" alt="[PIC]"/></a><br/>
    <span class="txt-fade">点击图片可查看原图</span></div>',$txt);
        }
        $file_n++;
    }
    if($fil_con!=null)
    {
        $fil_con="<div class=\"file_con\"><div style=\"background:#52CDFD;
font-size:16px;margin:-5px -8px 3px -8px;padding: 3px 8px;\"><img src=\"pic/ico/floppy.png\" />附件列表：</div>${fil_con}</div>";
    }
    
    
    $txt=ubb2html($txt);
    if($max_txt_page>1)
    {
        if($p>1)
        {
            $p2=$p-1;
            $sy="<a href='read.php?zid=${list2[zid]}&p=${p2}'>上页</a>";}

        if($p<$max_txt_page){$p3=$p+1;

                             $xy="<a href='read.php?zid=${list2[zid]}&p=${p3}'>下页</a>";}

        $ys="<form method='get' action=''><input type='hidden' value='${list2[zid]}' name='zid' />".$xy."[${p}/${max_txt_page}]".$sy." <input type='text' name='p' maxlength='7'  size='2' value='1'>
        <input type='submit' value='跳' /><a href='read.php?zid=${list2[zid]}&all=1'>全文</a></form>";

    }

 
    //所属板块
    $asql20 ="SELECT * FROM z_lt where bid= ${list2[y3]}";
    $list20= mysql_query ( $asql20 );
    @$list20 = mysql_fetch_array ( $list20);
    $list20=$list20["name"];
    $content=$content." <div class='module-title title'>${head}${is_great}</div><div class='spacing-5 border-btm bg-alter'>
    <p class='tabs-1'><div class='txt-fade'>(<a href=\"user/info.php?uid=${list2['uid']}\" id=\"aa0\" a=\"${uname}\">${uname}</a> | ${time0} | N:${n0} | <a href=\"list_s.php?bid=${list2[y3]}\">$list20</a> )</div>
    <div style=\"border-bottom: 1px solid #E3E6EB;margin: 5px 0;\"></div><p> ${txt}</p>
    ${fil_con}
    </div></div>${ys}<p>${tool}${del_cha}${cha_bk}</p><div class='module-title'>回帖列表</div>";
    $title=$head;
}

//回复列表  读取最新的三条回复内容
$asql="SELECT * FROM z_hf WHERE zid='${zid}' ORDER BY time DESC";
$list1=mysql_query($asql);
$hfnum=mysql_num_rows($list1);
for($n=1;$n<=3;$n++)
{
    if($list2=mysql_fetch_array($list1))
    {
        $txt=$list2["txt"];
        if( mb_strlen ( $txt , 'utf-8' )>150 )
        {
            $txt = mb_substr ( $txt , 0 ,150, "utf-8" );
            $txt = $txt . "<a href='read_hf.php?hid=${list2[hid]}'>……</a>" ;
        } 
        $time0=ctime($list2["time"]);
        $txt=ctxt($txt);
        $txt=ubb2html($txt);
        $uname=uinfo($list2["uid"]);
        $uname=$uname["name"];
        $content=$content."<div class='spacing-5 border-btm bg-alter'>
        <p class='tabs-1'><a href=\"user/info.php?uid=${list2['uid']}\" id=\"aa${n}\" a=\"${uname}\">${uname}</a> : ${txt} <span class='txt-fade'>( ${time0} )</span></p></div>";
    }
}

if($hfnum==0)
{
    $allhf="暂时没有人回复Ta哦！";
}
else{
    $allhf=" <a href='all_hf.php?zid=${zid}'>查看全部回复( ${hfnum}条 )</a> ";
}


include("html.php");
echo "
</head><body>${head}${out}".$content."<div class='module-title'>${allhf}</div>${hf_form}
${foot}";
?>