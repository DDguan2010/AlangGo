<?php
header("Content-Type: text/html; charset=utf-8"); 
include("admin.php");
$n=$_GET["n"];
$rn=$_POST["rname"];
$rt=htmlspecialchars($_POST["rtxt"],ENT_QUOTES);
$hinfo=mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='${n}'"));
if($_GET["do"]==1 && mysql_query("UPDATE htmls SET txt='$rt' WHERE name='$n'") )
{
    if(($rn!=$n && !mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='${rn}'")) ) ||$rn==$n )
    {
        mysql_query("UPDATE htmls SET name='$rn' WHERE name='$n'");
        $out="修改成功。";
    }
    else
    {
         $out="键名重复";
    }
}
else{
$out="</div><form method='POST' action='?n=${n}&do=1'>
键名:<input type='text' name='rname' value='${hinfo['name']}' />
<br/>
HTML: <br/><textarea name='rtxt' rows='20' cols=\"50\">${hinfo['txt']}</textarea><br/>
<input type='submit' value=' 完 成 ' /></form>"; 
}
?>
<html>
    <head>
        <link href="css2.css"   rel="stylesheet" type="test/css" />
        <title>Change Html</title>
    </head>
    <body>
        <div class="hr1">Change Html</div>
        <p>
        <?php echo $out; ?>
        </p>
        <div class="hr2"><a href="add.php">添加页面</a>.<a href="list.php">页面列表</a></div>
    </body>
</html>