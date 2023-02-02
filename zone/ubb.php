<?php

function ubb2html($html)

{

$html = preg_replace(
'!\[br/\]!siuU',
'<br/>',
$html);

$html = preg_replace(
'!\[br\]!siuU',
'<br/>',
$html);

$html = preg_replace(
'!///!siuU',
'<br/>',
$html);

$html = preg_replace(
'!\[hr\]!siuU',
'<hr/>',
$html);

$html = preg_replace(
'!\[kg\]!siuU',
'&nbsp',
$html);

$html = preg_replace(
'!\[p\](.*)\[/p\]!siuU',
'<p>\\1</p>',
$html);

$html = preg_replace(
'!\[u\](.*)\[/u\]!siuU',
'<u>\\1</u>',
$html);

$html = preg_replace(
'!\[url=(.*)\](.*)\[/url\]!siuU',
'<a href="\\1">\\2</a>',
$html);

$html = preg_replace('!\[url\](.*)\[/url\]!siuU','<a href="\\1">\\1</a>',$html);  
    
$html = preg_replace(
'!\[url=(.*)\]\[/url\]!siuU',
'<a href="\\1">\\1</a>',
$html);        
    

$html = preg_replace('!\[img=(.*)\](.*)\[/img\]!siuU','<div id="tt"><a href="\\1"><img src="http://what.sinaapp.com/t.php?h=320&w=240&purl=\\1" alt="\\2"   class="img"/></a><br/>
    <span class="txt-fade">点击图片可查看原图</span></div>',$html);

$html = preg_replace('!\[img\](.*)\[/img\]!siuU','<div id="tt"><a href="\\1"><img src="http://what.sinaapp.com/t.php?h=320&w=240&purl=\\1" alt=""   class="img"/></a><br/>
    <span class="txt-fade">点击图片可查看原图</span></div>',$html);

    
$html = preg_replace(
'!\[b\](.*)\[/b\]!siuU',
'<b>\\1</b>',
$html);

$html = preg_replace(
'!\[i\](.*)\[/i\]!siuU',
'<i>\\1</i>',
$html);

$html = preg_replace(
'!\[center\](.*)\[/center\]!siuU',
'<div align="center">\\1</div>',
$html);

$html = preg_replace(
'!\[right\](.*)\[/right\]!siuU',
'<div align="right">\\1</div>',
$html);

$html = preg_replace(
'!\[left\](.*)\[/left\]!siuU',
'<div align="left">\\1</div>',
$html);

$html = preg_replace(
'!\[color=(.*)\](.*)\[/color\]!siuU',
'<font color="\\1">\\2</font>',
$html);

$html = preg_replace('!@(\d{1,9})\;!uU','<a href="user/info.php?uid=\\1">@\\1;</a>',$html);
$html = preg_replace('!@(\w{2,30})\s{1}!uU','<a href="user/info.php?uname=\\1">@\\1&nbsp;</a>',$html);


preg_match( '!\[time=(.*)\]!siuU' ,$html , $x );

$t=date("$x[1]");

$html=preg_replace(
'!\[time=(.*)\]!siuU',$t,
$html);
$html=preg_replace('!\[f(\d{1,3})\]!iU','<img src="pic/face/\\1.gif"></img>',$html);
return $html;

}

function ubb2img($ubb)
{
/**$html = preg_replace(
'!\[/\](.*)\[/img\]!siuU',
'<img src="\\1" alt="\\2" />',
$html);
**/

}