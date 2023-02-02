<?php
header ('Content-Type:text/html;charset=utf-8');
session_start();
include("../conn.php");
include("user.php");
include("good.php");
$txt=trim($_POST["txt"]);
$head=trim($_POST["head"]);
$uid=$_COOKIE["uid"];
if($uid==null)
{
    header("location:login.php");
}
$user=uinfo($uid);
$time=time();
$zid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='zid'"));
$zid=$zid["num"]+1;
if(isset($_POST["head"]))
{
is_login(0);

if(empty($head)  )

{
$out=" <p><font color='red'> 忘了写标题哦！</font></p>";}
elseif(mb_strlen($head,'utf-8')>30)
{
$out=" <p><font color='red'> 标题长度应不大于30字哦！</font></p>";
}
else
{ 
    if( $_SESSION['th']!=$head )
    {
$_SESSION['th']="${head}";
          
        
        //附件上传
        for($fn=1;$fn<6;$fn++)
        {      
          if($_FILES["file".$fn]["size"] < 50000000 && $_FILES["file".$fn]["name"]!=null )
            {
              $ooo=$ooo."｛${fn}｝";
                $pics = explode('.',$_FILES["file".$fn]["name"]);
                $pnum = count($pics);
              if($pnum>1)
              {
                  $ftype=".".$pics[$pnum-1];
              }else
              {
                  $ftype="";
              }
           if((($_FILES["file".$fn]["type"] == "image/gif")|| ($_FILES["file".$fn]["type"] == "image/jpeg")||($_FILES["file".$fn]["type"] == "image/png")
                 || ($_FILES["file".$fn]["type"] == "image/pjpeg")) && ($_FILES["file".$fn]["size"] < 10000000))
           {
               $ft=3;
           }
           else
           {
               $ft=2;
           }
           $fname=rand(1000,9999).time().rand(1000,9999).$ftype;
              $s2 = new SaeStorage();
              $s2-> upload('dir',$fname,$_FILES["file".$fn]["tmp_name"]);
			 
			  $ftext=trim($_POST["ftext".$fn]);
              mysql_query("INSERT INTO z_up(zid,uid,name,time,type,file) VALUES ($zid,$uid,'$fname',$time,$ft,'$ftext')" );      //上传记录
			  $ftext="";
            }
        }
      

        
$txt=toa($txt,$zid); 
$txt=htmlspecialchars($txt, ENT_QUOTES);
//$txt=addslashes($txt);
$head=htmlspecialchars($head, ENT_QUOTES);
    /**
    z_txt表单结构:
    zid 帖子id   
    uid    作者id
    head  标题
    txt  正文
    txt2  摘要（暂时无用）
    lb  属性 1——帖子  2——说说
    time  时间戳
    zt 状态 （暂时无用）
    tip  置顶 1——是  其他——否
    y1 帖子已查看次数
    y2 被赞数量
    y3  板块id
    **/ 
mysql_query("INSERT INTO z_txt(zid,uid,head,txt,txt2,lb,time,zt,tip,y1,y2,y3) VALUES ($zid,$uid,'$head', '$txt','',1,$time,0,0,'0','0','$_POST[bid]')" );
mysql_query("UPDATE z_jsq SET num='$zid' WHERE name='zid'");
$out=" <p><font color='green'> 帖子发布成功。</font></p><p><a href=\"read.php?zid=${zid}\">去看看刚发的帖子</a></p>";

    }
}
}
$title="发 帖";
include("html.php");
include("../conn.php");
echo "

<script>
var fn=1;
var isout;
function addf()
{
 if(fn<5)
 {
 fn=fn+1;
 var a=document.getElementById(\"vfile\");
 document.getElementById(\"vfile\").innerHTML=a.innerHTML+\"<br/>\"+fn+\")<input type='file' name='file\"+fn+\"' id='file1' /><br/>说明:<input type='text' name='ftext\"+fn+\"' id='file1' /> \";
 }
 else
 {
 
  if(isout!=1)
  {
  var a=document.getElementById(\"vfile\");
  document.getElementById(\"vfile\").innerHTML=a.innerHTML+\"<br/><font color='red'>单次最多上传5个文件！</font>\";
  isout=1;
  } 
 }
}
</script>

</head><body>
${head}${out}<span class='txt-fade'>『 发 布 帖 子 』</span><br/>";
?>
    <div id="face_show"></div>
    <div class='form-box margin-t-5 form-box'>
        <form method='post' action='' enctype="multipart/form-data" >
    类别：<?php echo settle_lt(0,0);  ?><br/>
    标题：<input type="text" name="head" maxlength="30"><br/>
    <textarea name="txt" cols='20' rows='10' id="s" class="s"></textarea><br/>
    <div id="vfile">1)<input type="file" name="file1" id="file1" />
	<br/>说明:<input type="text" name="ftext1" id="file1" /></div>
    <input class='btn-s' type="submit" value="完 成" /> 
    <a href='ubb.html'>[UBB]</a>
    <span id="doingface" onclick="showFace(this.id,'s');">+表情</span> <span id="doingface" onclick="addf()">+附件</span>
    </form></div><div class='spacing-5 border-btm bg-alter'></div>
<?php echo $foot; 
mysql_close($conn);
?>