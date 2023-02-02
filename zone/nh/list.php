<?php
header("Content-Type: text/html; charset=utf-8"); 
include("admin.php");
$result = mysql_query("SELECT * FROM htmls ORDER BY name");
while($row = mysql_fetch_array($result))
{ 
    if($row["name"]=="css" || $row["name"]=="defined" || $row["name"]=="foot")
    {
        $do1=$do1."【 ${row[name]} 】<a href=\"change.php?n=${row[name]}\">修改</a><br/>";
    }
    else
    {
        $do2=$do2."【 ${row[name]} 】<a href=\"htmls.php?n=${row[name]}\">查看</a>.<a href=\"change.php?n=${row[name]}\">修改</a>.<a href=\"delete.php?n=${row[name]}\">删除</a><br/>";
    }
   
}
$list="——页面插件——<br/>${do1}<hr/>——自定义页面——<br/>${do2}";
?>
<html>
    <head>
        <link href="css2.css"   rel="stylesheet" type="test/css" />
        <title>HTML LIST</title>
    </head>
        <body>
            <div class="hr1">Html List</div>
            
            <?php echo $list; ?>
            <p>
                注意：链接中请使用双引号
            </p>
            
            <div class="hr2"><a href="add.php">新增页面</a>.<a href="../admin">回总后台</a></div>
        </body>
    
    </html>