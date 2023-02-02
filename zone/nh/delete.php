<?php
header("Content-Type: text/html; charset=utf-8"); 
include("admin.php");
$n=$_GET["n"];
$do=$_GET["do"];
if($do==1 && ($n!="css" || $n!="defined" || $n!="foot"))
{
$sql = "DELETE FROM htmls WHERE name= '$n'";
mysql_query($sql);
$out=$n."删除成功。";
}
else{
$out="确定要删除 ${n} ？<br/><a href=\"list.php\">不了，再看看。</a><br/><a href=\"delete.php?n=${n}&do=1\">嗯，是的。</a>";
}
?>
<html>
    <head>
        <link href="css2.css"   rel="stylesheet" type="test/css" />
        <title>Delete Html</title>
    </head>
    <body>
        <div class="hr1">Delete Html</div>
        <p><?php echo $out; ?></p>
        <div class="hr2"><a href="list.php">返回列表</a></div>
    </body>
</html>