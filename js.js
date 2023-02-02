function setMenuPosition(showid, offset) {
var showobj = $Obj(showid);
var menuobj = $Obj(showid + '_menu');
if(isUndefined(offset)) offset = 0;
if(showobj) {
   showobj.pos = fetchOffset(showobj);
   showobj.X = showobj.pos['left'];
   showobj.Y = showobj.pos['top'];
   showobj.w = showobj.offsetWidth;
   showobj.h = showobj.offsetHeight;
   menuobj.w = menuobj.offsetWidth;
   menuobj.h = menuobj.offsetHeight;
   if(offset != -1) {
    menuobj.style.left = (showobj.X + menuobj.w > document.body.clientWidth) && (showobj.X + showobj.w - menuobj.w >= 0) ? showobj.X + showobj.w - menuobj.w + 'px' : showobj.X + 'px';
    menuobj.style.top = offset == 1 ? showobj.Y + 'px' : (offset == 2 || ((showobj.Y + showobj.h + menuobj.h > document.documentElement.scrollTop + document.documentElement.clientHeight) && (showobj.Y - menuobj.h >= 0)) ? (showobj.Y - menuobj.h) + 'px' : showobj.Y + showobj.h + 'px');
   } else if(offset == -1) {
    menuobj.style.left = (document.body.clientWidth-menuobj.w)/2 + 'px';
    var divtop = document.documentElement.scrollTop + (document.documentElement.clientHeight-menuobj.h)/2;
    if(divtop > 100) divtop = divtop - 100;
    menuobj.style.top = divtop + 'px';
   }
   if(menuobj.style.clip && !is_opera) {
    menuobj.style.clip = 'rect(auto, auto, auto, auto)';
   }
}
}
function fetchOffset(obj) {
var left_offset = obj.offsetLeft;
var top_offset = obj.offsetTop;
while((obj = obj.offsetParent) != null) {
   left_offset += obj.offsetLeft;
   top_offset += obj.offsetTop;
}
return { 'left' : left_offset, 'top' : top_offset };
}
function $Obj(id) {
return document.getElementById(id);
}
function isUndefined(variable) {
return typeof variable == 'undefined' ? true : false;
}
function strlen(str) {
    var ie
return (ie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}
function insertContent(target, text) {
var obj = $Obj(target);
selection = document.selection;
if(!obj.hasfocus) {
   obj.focus();
}
if(!isUndefined(obj.selectionStart)) {
   var opn = obj.selectionStart + 0;
   obj.value = obj.value.substr(0, obj.selectionStart) + text + obj.value.substr(obj.selectionEnd);
} else if(selection && selection.createRange) {
   var sel = selection.createRange();
   sel.text = text;
   sel.moveStart('character', -strlen(text));
} else {
   obj.value += text;
}
}
//显示表情菜单
function showFace(showid, target) {
var div = $Obj('face_bg');
if(div) {
   div.parentNode.removeChild(div);
}
div = document.createElement('div');
div.id = 'face_bg';
div.style.position = 'absolute';
div.style.left = div.style.top = '0px';
div.style.width = '100%';
div.style.height = document.body.scrollHeight + 'px';
div.style.backgroundColor = '#000';
div.style.zIndex = 10000;
div.style.display = 'none';
div.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=0,finishOpacity=100,style=0)';
div.style.opacity = 0;
div.onclick = function() {
   $Obj(showid+'_menu').style.display = 'none';
   $Obj('face_bg').style.display = 'none';
}
$Obj('face_show').appendChild(div);

if($Obj(showid + '_menu') != null) {
   $Obj(showid+'_menu').style.display = '';
} else {
   var faceDiv = document.createElement("div");
   faceDiv.id = showid+'_menu';
   faceDiv.className = 'facebox';
   faceDiv.style.position = 'absolute';
   var faceul = document.createElement("ul");
   for(i=1; i<47; i++) {
    var faceli = document.createElement("li");
    faceli.innerHTML = '<img src="pic/face/'+i+'.gif" onclick="insertFace(\''+showid+'\','+i+', \''+ target +'\')" style="cursor:pointer; position:relative;" />';
    faceul.appendChild(faceli);
   }
   faceDiv.appendChild(faceul);
   $Obj('face_show').appendChild(faceDiv)
}
//定位菜单
setMenuPosition(showid, 0);
div.style.display = '';
}
//插入表情
function insertFace(showid, id, target) {
    var faceText = '[f'+id+']';
if($Obj(target) != null) {
   insertContent(target, faceText);
}
$Obj(showid+'_menu').style.display = 'none';
$Obj('face_bg').style.display = 'none';
}

function textCounter(obj, showid, maxlimit) {
var len = strLen(obj.value);
var showobj = $Obj(showid);
if(len > maxlimit) {
   obj.value = getStrbylen(obj.value, maxlimit);
   showobj.innerHTML = '0';
} else {
   showobj.innerHTML = maxlimit - len;
}
if(maxlimit - len > 0) {
   showobj.parentNode.style.color = "";
} else {
   showobj.parentNode.style.color = "red";
}

}
function getStrbylen(str, len) {
var num = 0;
var strlen = 0;
var newstr = "";
var obj_value_arr = str.split("");
for(var i = 0; i < obj_value_arr.length; i ++) {
   if(i < len && num + byteLength(obj_value_arr[i]) <= len) {
    num += byteLength(obj_value_arr[i]);
    strlen = i + 1;
   }
}
if(str.length > strlen) {
   newstr = str.substr(0, strlen);
} else {
   newstr = str;
}
return newstr;
}
function byteLength (sStr) {
aMatch = sStr.match(/[^\x00-\x80]/g);
return (sStr.length + (! aMatch ? 0 : aMatch.length));
}
function strLen(str) {
var charset = document.charset; 
var len = 0;
for(var i = 0; i < str.length; i++) {
   len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset == "utf-8" ? 3 : 2) : 1;
}
return len;
}




//wodejs我的js
var x,y="",i=1,old;
function adda(t)
{
var a=document.getElementById("s");
document.getElementById("s").value=a.value+"@"+t+" ";
showa();
}

function outa()
{
document.getElementById("div1").innerHTML="";

}

function showa(nn)
{
    
    jsgo(nn);

if(i==1)
{
document.getElementById("div1").innerHTML=z;
i=2;
}
else
{
document.getElementById("div1").innerHTML="";
i=1;
}
}

                  
var old;
var x2;
var arr2 = new Array(" ");
var is,z;

function jsgo(nn)
{    
for(var n=0;n<=nn;n++)
{
is=0;
 if(x=document.getElementById("aa"+n))
 {
x=x.innerHTML;
  for (x2 in arr2)
  {
  
    if(arr2[x2]==x)
    {   
       is=1;
       break;
    }       
  }
   
   if(is!=1)
   {
       y=y+"<li><span style='cursor:pointer;  position:relative;' onclick=adda('"+x+"')>@"+x+"</li>"; 
   }
   arr2.push(x);
 }
}
    z="<div id='face_show'><div  class='abox' style='position: absolute;'><span style='position:absolute;left:85%;top:3px;'onclick=outa()><img src='pic/close.jpg'/></span><ul>"+y+"</ul></div><div id='face_bg' style='position: absolute; top: 0px; left: 0px; width: 100%;  background-color: rgb(0, 0, 0); z-index: 10000; opacity: 0;'></div></div>";

}
