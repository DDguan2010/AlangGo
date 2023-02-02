<?php
error_reporting(0);
header ( 'Content-Type:text/html;charset=utf-8 ');
?>
<html>
<head>
    <title>易社区（为易社区）2.1 安装</title></head>
<body>
<h1>易社区2.1</h1>
<?php
date_default_timezone_set("Asia/Shanghai");
//include_once("zone/good.php");
if($_POST["host"]!=NULL){
$datetime=date("Y.m.d H:i");
$mysql_host=$_POST["host"];
$mysql_user=$_POST["user"];
$mysql_password= $_POST["password"];
$mysql_database= $_POST["database"];
$conn=@mysql_connect("$mysql_host","$mysql_user","$mysql_password");
if($conn && @mysql_select_db("$mysql_database",$conn))
{
    echo "<font color=\"red\">数据库连接成功！</font><br/>";
$t='<?php
date_default_timezone_set("Asia/Shanghai");
//include_once("zone/good.php");
$datetime=date("Y.m.d H:i");
$mysql_host="'.$mysql_host.'";
$mysql_user="'.$mysql_user.'";
$mysql_password="'.$mysql_password.'";
$mysql_database="'.$mysql_database.'";
$conn=mysql_connect("$mysql_host","$mysql_user","$mysql_password");
if(!$conn){ die("连接数据库失败：" . mysql_error());}
mysql_select_db("$mysql_database",$conn);
mysql_query("set character set \'utf-8\'");
mysql_query("set names \'utf-8\'");
include_once(dirname(__FILE__)."/user.php");
?>';
file_put_contents("conn.php",$t);


    
    //自定义页面
    if(
        mysql_query("CREATE TABLE htmls(name varchar(100),txt mediumtext,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8")
       && mysql_query("INSERT INTO htmls(name,txt) VALUES ('css', '')") 
        && mysql_query("INSERT INTO htmls(name,txt) VALUES ('defined', '<p>我是页面插件defined -.-</p>')" )
        && mysql_query("INSERT INTO htmls(name,txt) VALUES ('foot', '<a href=\"http://www.51.la/?16018732\" target=\"_blank\"><img src=\"http://img.users.51.la/16018732.asp\" style=\"border:none\" /></a>
<a href=\"http://m.moonsn.com/click.php?687\"><img src=\"http://m.moonsn.com/image.php?687,small\" alt=\"\" /></a>')" )
      )
    {
    echo "htmls 建表成功!<br/>";
    }
    else{
    
        if(mysql_query("SELECT * FROM htmls"))
        {
            echo "htmls 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">htmls 建表失败!</font><br/>";    
        }        
    
    }
   
   $main_dir=dirname($_SERVER['REQUEST_URI'])."/zone";
    //网站设置
    if(
        mysql_query("CREATE TABLE z_set(name varchar(100),val varchar(900),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('sname', '|易社区（为易小站）')") 
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('kword', '为易,易社区,为易小站,网络,记事,记事本,原创,在线,工具,社区,兴趣,wapxz.tk')" )
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('set_mail_txt', '来自易社区(http://what.sinaapp.com)的账号密码找回服务')")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('set_mail_head', '账号密码找回—易社区')")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('set_mail_un', '')")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('set_mail_pw', '')")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('set_mail_smtp', 'smtp.qq.com')")
        && mysql_query("INSERT INTO z_set(name,val) VALUES ('main_dir', '${main_dir}')")
      )
    {
    echo "z_set 建表成功!<br/>";
    }
    else{
     
        if(mysql_query("SELECT * FROM z_set"))
        {
            echo "z_set 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_set 建表失败!</font><br/>";    
        }        
     
    }
    
    
    
    //帖子，说说记录
    /*  
    zid 说说或帖子的id
    uid 发布者id
    lb 类别 1——帖子 0——说说
    time 发帖时间戳
    zt 帖子状态（暂无作用）
    tip  1——帖子置顶 0——非置顶
    head 标题（说说则为空）
    txt 内容
    txt2 摘要 （暂无）
    y1 被赞次数
    y2 帖子查看次数  属性为int要注意
    y3 所属 板块 id 
    */
     if(mysql_query("CREATE TABLE z_txt(zid int,uid int,lb int,time int,zt int,tip int,head varchar(200),
     txt mediumtext,txt2 varchar(9000),y1 varchar(300),y2 int,y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_txt 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_txt"))
        {
            echo "z_txt 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_txt 建表失败!</font><br/>";    
        }           
    }
    
    
    
    
    //总计数器 
    /*
    zid 帖子，说说的id
    hid 回复的id
    aid @消息的id
    mid 内信id
    bid 论坛板块id
    cid 聊天消息id
	lin 友链的id
    */
    if(mysql_query("CREATE TABLE z_jsq(name varchar(100),num int,y1 varchar(300),y2 varchar(300),y3 varchar(300)) DEFAULT CHARSET=utf8") 
       &&mysql_query("INSERT INTO z_jsq (name,num)VALUES ('zid', 1000)") 
       && mysql_query(" INSERT INTO z_jsq (name,num)VALUES ('hid', 0)") 
       && mysql_query(" INSERT INTO z_jsq (name,num)VALUES ('aid', 0)") 
       && mysql_query("INSERT INTO z_jsq (name,num)VALUES ('mid', 0)") 
       && mysql_query(" INSERT INTO z_jsq (name,num)VALUES ('bid', 0)")
       && mysql_query(" INSERT INTO z_jsq (name,num)VALUES ('cid', 0)")
	   && mysql_query(" INSERT INTO z_jsq (name,num)VALUES ('lid', 0)")
	   )
    {
        echo "z_jsq 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_jsq"))
        {
            echo "z_jsq 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_jsq建表失败!</font><br/>";    
        }           
    }
    
    
    //回复内容记录
    /*
    hid 回复的id
    zid 所回复的帖子或说说的id
    uid 回复的用户id
    time 时间（时间戳）
    txt 回复的内容
    y1——y3  预留
    */

    if(mysql_query("CREATE TABLE z_hf(hid int,zid int,uid int,time int,txt mediumtext,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_hf 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_hf"))
        {
            echo "z_hf 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_hf 建表失败!</font><br/>";    
        }           
    }
    
    
    //论坛版块记录
    /*
    bid 板块的id
    name 板块的名称
    y1——y3 预留
    */
    if(mysql_query("CREATE TABLE z_lt(bid int,name varchar(900),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_lt 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_lt"))
        {
            echo "z_lt 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_lt 建表失败!</font><br/>";    
        }           
    }
    
    
    
    
    //內信记录
    /*
    fromid 内信的用户id
    toid 接收者id
    mid 该条内信的id
    uid 1——已查看 0——未查看
    time 时间戳
    txt 内容
    y1——y3 预留
    */
    if(mysql_query("CREATE TABLE z_mail(fromid int,toid int,mid int,uid int,time int,txt mediumtext,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_mail 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_mail"))
        {
            echo "z_mail 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_mail 建表失败!</font><br/>";    
        }           
    }
    
    
    
    
    
    //@记录
    /*
     同内信记录
        y1——y3 预留
    */
    if(mysql_query("CREATE TABLE z_a(fromid int,toid int,aid int,uid int,time int,txt mediumtext,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_a 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_a"))
        {
            echo "z_a 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_a 建表失败!</font><br/>";    
        }           
    }
    
    
    //管理员目录   
    /*
    bid 管理的板块id  为空则有全部板块管理权
    uid 用户id
        y1——y3 预留
    */
    if(mysql_query("CREATE TABLE z_admin(bid int,uid int,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8") && 
       mysql_query(" INSERT INTO z_admin (uid)VALUES (1)"))
     {
        echo "z_admin 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_admin"))
        {
            echo "z_admin 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_admin 建表失败!</font><br/>";    
        }           
    }
    
    
    
    
    //注册用户计数器
    /*
    
        y1——y3 预留
    */
    if(mysql_query("CREATE TABLE v_jsq(name varchar(30),number int,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8") && 
      mysql_query("INSERT INTO v_jsq (name,number)VALUES ('id', 0)"))
    {
        echo "v_jsq 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM v_jsq"))
        {
            echo "v_jsq 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">v_jsq 建表失败!</font><br/>";    
        }           
    }
    
    
    
    //用户注册记录
    /*
    id 用户id
    name 昵称
    email 邮箱
    password 密码
    reg_time 注册的时间
    
    y1——y3 预留
    */
    if(mysql_query("CREATE TABLE v_user(id int, name varchar(30),email varchar(50),password varchar(30),
    reg_time date,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
     {
        echo "v_user 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM v_user"))
        {
            echo "v_user 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">v_user 建表失败!</font><br/>";    
        }           
    }
    
    
    
    //上传文件（图片）记录
    if(mysql_query("CREATE TABLE z_up(zid int,uid int,time int,name varchar(300),type int,file varchar(50),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_up 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_up"))
        {
            echo "z_up 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_up 建表失败!</font><br/>";    
        }           
    }
    
	//文件下载记录
    if(mysql_query("CREATE TABLE z_down(num int,name varchar(300),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_down 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_down"))
        {
            echo "z_down 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_down 建表失败!</font><br/>";    
        }           
    }
    
    //赞 记录
    if(mysql_query("CREATE TABLE z_good(zid int,uid int,time int,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_good 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_good"))
        {
            echo "z_good 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_good 建表失败!</font><br/>";    
        }           
    }
    
    //关注 attention 
    if(mysql_query("CREATE TABLE z_att(frid int,beid int,time int,num int,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_att 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_att"))
        {
            echo "z_att 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_att 建表失败!</font><br/>";    
        }           
    }
    
    
    //聊天记录
     if(mysql_query("CREATE TABLE z_chat(cid int,uid int,time int,txt mediumtext,txt2 varchar(9000),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_chat 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_chat"))
        {
            echo "z_chat 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_chat 建表失败!</font><br/>";    
        }           
    }
    
     if(mysql_query("CREATE TABLE z_search(t varchar(50),zid int,time int,ztime int,y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "z_search 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM z_search"))
        {
            echo "z_search 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">z_search 建表失败!</font><br/>";    
        }           
    }
    
	//友链
	if(mysql_query("CREATE TABLE flinks(lid int,un varchar(90),pw varchar(90),time int,audit int,great int,url varchar(200),
     sname varchar(90),lname varchar(9000),y1 varchar(300),y2 varchar(300),y3 varchar(300))DEFAULT CHARSET=utf8"))
    {
        echo "flinks 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM flinks"))
        {
            echo "flinks 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">flinks 建表失败!</font><br/>";    
        }           
    }

    //安全验证
    if(mysql_query("CREATE TABLE user_c(id bigint(20),i_code bigint(20))DEFAULT CHARSET=utf8 "))
    {
        echo "user_c 建表成功!<br/>";
    }
    else
    {
        if(mysql_query("SELECT * FROM user_c"))
        {
            echo "user_c 已存在!<br/>";   
        }
        else
        {
            echo "<font color=\"red\">user_c 建表失败!</font><br/>";    
        }           
    }
    
    echo "<p>建表过程结束，若无表单建立失败，就请进入<a href='index.php'>首页</a>看看吧！<br/>*<font color=\"red\">请立即注册账号，id为1的用户为默认管理员！</font><br/>
    请手动删除install.php或将其改名！</p>";

    $ok=1;
}
    else
    {
        echo "数据库连接失败！";
    }
}

if($ok!=1)
{
    echo " <form method='POST' action=''>
    数据库主机名:<input type='text' name='host' /><br/> 数据库用户名: <input type='text' name='user' /><br/> 用 户 密 码: <input type='text' name='password' /><br/> 
    数 据 库 名:<input type='text' name='database' /><br/> <input type='submit' value=' 确 定 安 装 ' /> </form> ";
}
?>
<p>关注<a href="http://what.sinaapp.com">易社区</a>，让我们共同进步！</p>
</body>
</html>