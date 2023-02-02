<?php
header ( 'Content-Type:text/html;charset=utf-8 ');
include("../conn.php");
include("user.php");
include("good.php");
$sf = new SaeStorage();
$uid=$_COOKIE["uid"];
$d=$_GET["d"];
$n=$_GET["n"];
$zid=$_GET["zid"];
if($sf->fileExists($d,$n))
{
    $asql="SELECT uid  FROM z_up WHERE name='${n}' ";
    $list1=mysql_query($asql);
    $list1=mysql_fetch_array($list1);
    if($uid!=$list1[0])
    {
        header("location:all_list.php");
    }
    if($_GET["ok"]==1)
    {
        $sf->delete($d,$n);
        mysql_query("delete from z_up where name='$n' ");
        mysql_query("delete from z_down where name= '$n'");
        $back="<div style='color:green'>成功删除了附件 $n 。<br/><a href='read.php?zid=${zid}'>返回帖子</a></div>";
    }
    else
 	{
        $back="<div style=''>确定删除附件 $n ?<br/><a href='read.php?zid=${zid}'>算了，再看看</a><br/><a href='?d=${d}&n=${n}&zid=${zid}&ok=1'>确定，以及肯定</a></div>";
    }
    
   
    

}
else
{
    $back="<div style='color:red'>文件不存在！</div>";
}
$title="删除附件";
include("html.php");
echo "
</head><body>${head}${out}".$back."<div class='module-title'>${allhf}</div>${hf_form}
${foot}";
?>