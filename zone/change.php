<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
//include("cuttxt.php");
include("good.php");
$ntime=time();  //现在的时间戳
$zid=$_GET["zid"];
$do=$_GET["do"];
$uid=$_COOKIE["uid"];
$rhead=trim($_POST["head"]);
$rhead=htmlspecialchars($rhead, ENT_QUOTES);
$rtxt=trim($_POST["txt"]);
//$rtxt=addslashes($rtxt);
$rtxt=htmlspecialchars($rtxt, ENT_QUOTES);
$rtxt=htmlspecialchars($rtxt, ENT_QUOTES);

//错误提示
$asql="SELECT * FROM z_txt WHERE zid='${zid}' ";
$list1=mysql_query($asql);
if($list2=mysql_fetch_array($list1)){
}
else{
    die("内容不存在！");
}
if($list2["lb"]!=1 || is_admin($list2[uid])==0){
    die("error");
}

if($do==1)
{
    if(empty($rhead) )
    {
        $out="<font color='red'>忘了写标题哦！</font>";}
    elseif(mb_strlen($rhead,'utf-8')>30)
    {
        $out="<font color='red'>标题长度应不大于30字哦！</font>";
    }
    else
    {
        //更新帖子
        
         //附件上传
        for($fn=1;$fn<6;$fn++)
        {      
          if($_FILES["file".$fn]["size"] < 50000000 && $_FILES["file".$fn]["name"]!=null )
            {
             
                $pics = explode('.',$_FILES["file".$fn]["name"]);
                $pnum = count($pics);
              if($pnum>1)
              {
                  $ftype=".".$pics[$pnum-1];
              }
              else
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
               mysql_query("INSERT INTO z_up(zid,uid,name,time,type,file) VALUES ($zid,$uid,'$fname',$ntime,$ft,'$ftext')" ); 
			  $ftext="";
            }
        }
        
        
        
        mysql_query ( "UPDATE z_txt SET head='$rhead',txt='$rtxt',time='$ntime',zt=2 WHERE zid=$zid" );
        //mysql_query ( "UPDATE z_txt SET txt='$rtxt' WHERE zid=$zid" );
        //mysql_query ( "UPDATE z_txt SET time='$ntime' WHERE zid='$zid'" );
        //mysql_query ( "UPDATE z_txt SET zt=2 WHERE zid='$zid'" );
        $out="<font color='green'>修改成功。</font>";
        $change_ok=1;
    }
}
//更新完成后的输入框显示内容
if($change_ok==1)
{
$form="";
}
else
{
$ohead=htmlspecialchars_decode($list2[head]);
    $otxt=htmlspecialchars_decode($list2['txt']);
//表单
$form="<div id=\"face_show\"></div>
<div class='form-box margin-t-5 form-box'>
<form method='post' action='?zid=${zid}&do=1'  enctype=\"multipart/form-data\">
标题：<input type='text' name='head' maxlength='30' value='${ohead}'><br/>
<textarea name='txt' cols='20' rows='10'id=\"s\" class=\"s\" >${otxt}</textarea><br/>
<div id=\"vfile\">1)<input type=\"file\" name=\"file1\" id=\"file1\" />
<br/>说明:<input type=\"text\" name=\"ftext1\" id=\"file1\" /></div>
<input class='btn-s' type='submit' value='完 成' /> 
<!--     <span id=\"doingface\" onclick=\"showFace(this.id,'s');\">+表情</span>     -->
<span id=\"doingface\" onclick=\"showFace(this.id,'s');\">+表情</span>
 <span id=\"doingface\" onclick=\"addf()\">+附件</span>
</form></div>";
}
//页面显示
$title="修改帖子";
include("html.php");
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
</head>
<body>${head}<p>${out}</p><span class='txt-fade'>『 修 改 帖 子 』</span>--<a href=\"read.php?zid=${zid}\">返回查看帖子</a><p>${form}</p>${foot}";