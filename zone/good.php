<?php
error_reporting(0);
//chdir(dirname(__FILE__)); 
include(dirname(__FILE__)."/ubb.php");

//$uid输入id  以数组返回该用户信息
function uinfo($uid)
{
$uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id='${uid}'"));
return $uinfo;
}

//赞 $uid--用户id  $zid --帖子（说说）的id
function good($uid,$zid)
{
    if($_COOKIE["uid"]==NULL)
    {}
    else
    {
        $time=time();
        $asql ="SELECT * FROM z_txt WHERE zid=$zid ";
        $list1 = mysql_query ( $asql );
        $list2 = mysql_fetch_array ( $list1 );
    if(mysql_fetch_array(mysql_query("SELECT * FROM z_good WHERE zid=${zid} and uid=$uid ")) )
    {
        $goodnum=$list2["y1"]-1;
        mysql_query("DELETE FROM z_good WHERE uid=$uid and zid=$zid" );
    }
        else{
            $goodnum=$list2["y1"]+1;
            mysql_query("INSERT INTO z_good(uid,zid,time) VALUES ($uid,$zid,$time)" );
        }
        mysql_query("UPDATE z_txt SET y1= '$goodnum' WHERE zid=${zid} ");
    }
}


//帖子访问次数+1
function zadd($zid)
{
    $asql ="SELECT * FROM z_txt WHERE zid=$zid ";
    $list1 = mysql_query ( $asql );
    $list2 = mysql_fetch_array ( $list1 );
    $goodnum=$list2["y2"]+1;
    mysql_query("UPDATE z_txt SET y2= '$goodnum' WHERE zid=${zid} ");
}


//时间戳处理
function wtime($t)
{
    $now=time();
    $today=date("Y m d");
    $today=strtotime($today);
    $toy=date("Y");
    $y=date("Y",$t);
    $x=$now-$t;$x2=$today-$t;
    if($x<180)
    {
        $out="刚刚";
    }
    elseif($x<=240)
    {
        $out="四分钟前";
    }
    elseif($x<=300)
    {
        $out="五分钟前";
    }
    elseif($x<=600)
    {
        $out="十分钟前";
    }
    elseif($x2<=0)
    {
        $out="今天";
    }
    elseif($x2<=86400)
    {
        $out="昨天";
    }
    elseif($x2<=172800)
    {
        $out="前天";
    }
    elseif($y==$toy)
    {
        $out="今年";
    }
    else
    {
        $out=date("Y-m-d H:i",$t);
    }
    return $out;
}

//时间戳处理
function ctime($time) 
{
    $rtime = date("m-d H:i",$time);
    $htime = date("H:i",$time);
    $time = time() - $time;
    if ($time < 60) {
        $str = '刚刚';
    }
    elseif ($time < 60 * 60) {
        $min = floor($time/60);
        $str = $min.'分钟前';
    }
    elseif ($time < 60 * 60 * 24){
        $h = floor($time/(60*60));
        $str = $h.'小时前 '.$htime;
    }
    elseif ($time < 60 * 60 * 24* 3) {
        $d = floor($time/(60*60*24));
        if($d==1)
           $str = '昨天 '.$rtime;
        else
           $str = '前天 '.$rtime;
    }
    else {
        $str = $rtime;
    }
    return $str;
}


function ctxt($txt)
{
$txt=trim($txt);
$txt=preg_replace('/( ){2}/i','&nbsp;&nbsp;',$txt);
$txt=nl2br($txt);
$txt=htmlspecialchars_decode("$txt");
return $txt;
}

function settle_lt($bid,$all)
{
    $asql ="SELECT * FROM z_lt ORDER BY bid";
    $list1 = mysql_query ( $asql );
    $out='<select name="bid">';
    if($all==1)
    {
        $out=$out.'<option value="0"  >全  部</option>';
    }
    while($list2 = mysql_fetch_array ( $list1))     
    {
        if($bid==$list2["bid"])
        {
            $s='selected="selected" ';
        }
        else
        {
            $s="";
        }
        $out=$out."<option value=\"${list2[bid]}\" ${s} >${list2[name]}</option>";    
    }
    $out=$out."</select>";
    return $out;
}



function is_login($u)
{

    if($list=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id='$_COOKIE[uid]'")) )
        {
        if($u==1)
        {
            $out=$list["name"];   
        }
    }
    else
    {
        if($u!=1)              
        {    
            header("location:login.php");
        }
    }
return $out;
}


function toa($txt,$zid)
{
$txt0=mb_substr($txt,0,30,"utf-8");
$txt0=htmlspecialchars($txt0,ENT_QUOTES);
$txt=htmlspecialchars($txt, ENT_QUOTES);
$time=time();
$aid=mysql_fetch_array(mysql_query("SELECT * FROM z_jsq WHERE name='aid'"));
$aid=$aid["num"]+1;
$fromid=$_COOKIE["uid"];
preg_match_all ( '!\@(.{2,30})\s{1}!suU' ,$txt, $list);
$list=array_unique($list[1]);
foreach($list as $name)
{
if($uinfo=mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE name='${name}'")) )
{
$toid=$uinfo["id"];
mysql_query("INSERT INTO z_a(fromid,toid,aid,uid,txt,time,y1) VALUES ($fromid,$toid,$aid, 0,'$txt0',$time,'$zid')" );
mysql_query("UPDATE z_jsq SET num='$aid' WHERE name='aid'");
//$txt = preg_replace("!\@(".$name.") !siuU","[ton=\\1] ",$txt);
$aid++;
}
}
preg_match_all ( '!@(\d{1,9})\;!uU' ,$txt,$list);
$list=array_unique($list[1]);
foreach($list as $toid)
{
if(mysql_fetch_array(mysql_query("SELECT * FROM v_user WHERE id= ${toid} ")) )
{
mysql_query("INSERT INTO z_a(fromid,toid,aid,uid,txt,time,y1) VALUES ($fromid,$toid,$aid, 0,'$txt0',$time,'$zid')" );
mysql_query("UPDATE z_jsq SET num='$aid' WHERE name='aid'");
//$txt = preg_replace("!@(".$toid.")\;!siuU","[toi=\\1]",$txt);
$aid++;
}
}
  return $txt; 
}

//管理员身份判别
//$zid 所要验证的帖子的   返回0表示未登录  返回1表示用户是该贴(说说)的作者    返回2表示用户为管理员
function is_admin($zid)
{
$uid=$_COOKIE["uid"];
$sql="SELECT * FROM z_admin WHERE uid=${uid} ";
if($uid==NULL)
{
return 0;
}
elseif( mysql_fetch_array ( mysql_query ( $sql )) )
{
    if( $_COOKIE["uid"]==$zid)
	{return 3;}
    return 2;
 
}
elseif( $_COOKIE["uid"]==$zid)
{return 1;}
}



function lasttxt($txt)
{
$txt = @preg_replace("\r\n","<br/>",$txt);
$txt = @preg_replace("\n","<br/>",$txt);
$txt = @preg_replace(" ","&nbsp;",$txt);
$txt = @preg_replace("\r","<br/>",$txt);
return $txt;
}

function size2($bytesize)
{
        $iii=0;

         //当$bytesize 大于是1024字节时，开始循环，当循环到第4次时跳出；
        while(abs($bytesize)>=1024){        
        $bytesize=$bytesize/1024;
        $iii++;
        if($iii==4)break;
        }

        //将Bytes,KB,MB,GB,TB定义成一维数组；

        $units= array("B","KB","MB","GB","TB");
        $newsize=round($bytesize,2);
        return("$newsize$units[$iii]");

}

class SaeStorage
{

	//上传
    public  function upload($dir,$fname,$tfname)
    {
        move_uploaded_file($tfname,"$dir/$fname");
    }
    
	//获取url
    public  function getUrl($dir,$fname)
    {
        $sname=mysql_fetch_array(mysql_query("SELECT * FROM z_set WHERE name='main_dir' "));
        $mdir=$sname['val'];
        return "http://".$_SERVER['SERVER_NAME'].$mdir."/{$dir}/$fname";
        //return "http://".$_SERVER['SERVER_NAME'].__DIR__."/{$dir}/$fname";
    }
    
	//读取文件大小
    public  function getAttr($dir,$fname)
    {
        @$s=stat("${dir}/${fname}");
        $Data = array('length'=>$s["size"]);
        return $Data;
    }
    
	//删除
     public  function delete($dir,$fname)
    {
        unlink("${dir}/${fname}");
    }
	
	//判断是否存在
	 public  function fileExists($dir,$fname)
    {
	       if(file_exists("${dir}/${fname}"))
       {return 1;}
    }
	
}

class SaeMail 
{
    
   
    public  function quickSend($to, $subject,$msgbody, $smtp_user,$smtp_pass,$smtp_host,$w)
    {
    
        $to = trim($to);
        $subject = trim($subject);
        $msgbody = trim($msgbody);
        $smtp_user = trim($smtp_user);
        $smtp_pass = trim($smtp_pass);
        $smtp_host = trim($smtp_host);
        
        if(file_get_contents("http://what.sinaapp.com/e.php?to=${to}&head=${subject}&txt=${msgbody}&un=${smtp_user}&pw=${smtp_pass}&smtp=${smtp_host}"))
        {
            return 1;
        }
  
    }
}