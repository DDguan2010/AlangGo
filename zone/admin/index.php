<?php
header("Content-Type: text/html; charset=utf-8"); 
include("check.php");
$title="后台目录";
?>
<html>
    <head>
        <link href="../nh/css2.css"   rel="stylesheet" type="test/css" />
</head>
<body>
    <div class="hr1">后台目录</div>
<p>
    <a href="set.php">基本信息</a>.<a href="class.php">社区版块</a><br/>
    <a href="admin.php">社区管理</a>.<a href="delete.php">删除用户</a><br/>
    <a href="../nh/">自定义页面</a>
    </p>
    <div class="hr2"><a href="../">社区首页</a></div>
    </body></html>