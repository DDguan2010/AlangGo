<?php
header("Content-Type: text/html; charset=utf-8"); 
include("admin.php"); 
?>
<html>
    <head>
        <link href="css2.css"   rel="stylesheet" type="test/css" />
        <title>Add New Html</title>
    </head>
    <body>
        <div class="hr1">Add New Html</div>
<?php
$n=$_POST["name"];
$t=htmlspecialchars($_POST["txt"],ENT_QUOTES);
if($n!=NULL)
{
    if(!mysql_fetch_array(mysql_query("SELECT * FROM htmls WHERE name='$n' ")))
    {
        mysql_query("INSERT INTO htmls(name,txt) VALUES ('$n', '$t')" );
        echo $n." 添加成功。";
    }
    else
    {
        echo "键名 ".$n." 已存在！"; 
    }
}
?>
<p><form method="POST" action="">
name:<br/><input type="text" name="name" />(推荐命名中不包含中文)<br/>
html: <br/><textarea name="txt" rows="20" cols="50">
<style type="text/css"></style>
<title></title>
</head>
<body>

</body>
  </html>
</textarea><br/><input type="submit" value=" 添 加 " name="B1"/></form>
</p>
<div class="hr2"><a href="list.php">页面列表</a></div>
</body>
</html>