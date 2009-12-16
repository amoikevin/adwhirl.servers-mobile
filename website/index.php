<?php
/*
 -----------------------------------------------------------------------
Copyright 2009 AdMob, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
------------------------------------------------------------------------
*/
?>
<?php
ini_set("session.gc_maxlifetime", "18000");
session_start();
header("Cache-control: private");
require_once('includes/inc_global_no_session.php');

if(isset($_SESSION['UID']))
{
	header('Location: main');
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Description" content="Dynamically change between ad networks for your iPhone apps and create and display custom ads to cross-promote your own apps.">
<meta name="verify-v1" content="cA7JYQ2t5Lzk3mIKzoKY6xL2eO36xGsjKrXQp37FH+Y=" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="icon" type="image/ico" href="favicon.ico"></link>
<link rel="shortcut icon" href="favicon.ico"></link>
<title>AdWhirl: Mobile Advertising | iPhone | Monetize Traffic | AdRollo</title>

<!--
<script type="text/javascript">
if(document.domain != 'www.adwhirl.com' && document.domain != 'staging.adwhirl.com')
	window.location = "http://www.adwhirl.com"
</script>
-->

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8105904-1");
pageTracker._trackPageview();
} catch(err) {}</script>


<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript">
</script>

<script type="text/javascript">
/***********************************************
* Ultimate Fade-In Slideshow (v1.51): � Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/
 
//var fadeimages=new Array()
//fadeimages[0]=["http://adrollo-images.s3.amazonaws.com/why.gif", "", ""]
//fadeimages[1]=["http://adrollo-images.s3.amazonaws.com/why2.gif", "", ""]

var logoset1=new Array()
logoset1[0]=["http://adrollo-images.s3.amazonaws.com/logos/2.gif", "", ""]
logoset1[1]=["http://adrollo-images.s3.amazonaws.com/logos/9.gif", "", ""]
logoset1[2]=["http://adrollo-images.s3.amazonaws.com/logos/4.gif", "", ""]

var logoset2=new Array()
logoset2[0]=["http://adrollo-images.s3.amazonaws.com/logos/4.gif", "", ""]
logoset2[1]=["http://adrollo-images.s3.amazonaws.com/logos/8.gif", "", ""]
logoset2[2]=["http://adrollo-images.s3.amazonaws.com/logos/7.gif", "", ""]

var logoset3=new Array()
logoset3[0]=["http://adrollo-images.s3.amazonaws.com/logos/8.gif", "", ""]
logoset3[1]=["http://adrollo-images.s3.amazonaws.com/logos/2.gif", "", ""]
logoset3[2]=["http://adrollo-images.s3.amazonaws.com/logos/9.gif", "", ""]

////NO need to edit beyond here/////////////
 
var fadearray=new Array() //array to cache fadeshow instances
var fadeclear=new Array() //array to cache corresponding clearinterval pointers
 
var dom=(document.getElementById) //modern dom browsers
var iebrowser=document.all
 
function fadeshow(theimages, fadewidth, fadeheight, borderwidth, delay, pause, displayorder){
this.pausecheck=pause
this.mouseovercheck=0
this.delay=delay
this.degree=10 //initial opacity degree (10%)
this.curimageindex=0
this.nextimageindex=1
fadearray[fadearray.length]=this
this.slideshowid=fadearray.length-1
this.canvasbase="canvas"+this.slideshowid
this.curcanvas=this.canvasbase+"_0"
if (typeof displayorder!="undefined")
theimages.sort(function() {return 0.5 - Math.random();}) //thanks to Mike (aka Mwinter) :)
this.theimages=theimages
this.imageborder=parseInt(borderwidth)
this.postimages=new Array() //preload images
for (p=0;p<theimages.length;p++){
this.postimages[p]=new Image()
this.postimages[p].src=theimages[p][0]
}
 
var fadewidth=fadewidth+this.imageborder*2
var fadeheight=fadeheight+this.imageborder*2
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers (ie: Firefox)
document.write('<div id="master'+this.slideshowid+'" style="position:relative;width:'+fadewidth+'px;height:'+fadeheight+'px;overflow:hidden;"><div id="'+this.canvasbase+'_0" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div><div id="'+this.canvasbase+'_1" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div></div>')
else
document.write('<div><img name="defaultslide'+this.slideshowid+'" src="'+this.postimages[0].src+'"></div>')
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers such as Firefox
this.startit()
else{
this.curimageindex++
setInterval("fadearray["+this.slideshowid+"].rotateimage()", this.delay)
}
}

function fadepic(obj){
if (obj.degree<100){
obj.degree+=10
if (obj.tempobj.filters&&obj.tempobj.filters[0]){
if (typeof obj.tempobj.filters[0].opacity=="number") //if IE6+
obj.tempobj.filters[0].opacity=obj.degree
else //else if IE5.5-
obj.tempobj.style.filter="alpha(opacity="+obj.degree+")"
}
else if (obj.tempobj.style.MozOpacity)
obj.tempobj.style.MozOpacity=obj.degree/101
else if (obj.tempobj.style.KhtmlOpacity)
obj.tempobj.style.KhtmlOpacity=obj.degree/100
else if (obj.tempobj.style.opacity&&!obj.tempobj.filters)
obj.tempobj.style.opacity=obj.degree/101
}
else{
clearInterval(fadeclear[obj.slideshowid])
obj.nextcanvas=(obj.curcanvas==obj.canvasbase+"_0")? obj.canvasbase+"_0" : obj.canvasbase+"_1"
obj.tempobj=iebrowser? iebrowser[obj.nextcanvas] : document.getElementById(obj.nextcanvas)
obj.populateslide(obj.tempobj, obj.nextimageindex)
obj.nextimageindex=(obj.nextimageindex<obj.postimages.length-1)? obj.nextimageindex+1 : 0
setTimeout("fadearray["+obj.slideshowid+"].rotateimage()", obj.delay)
}
}
 
fadeshow.prototype.populateslide=function(picobj, picindex){
var slideHTML=""
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML='<a href="'+this.theimages[picindex][1]+'" target="'+this.theimages[picindex][2]+'">'
slideHTML+='<img src="'+this.postimages[picindex].src+'" border="'+this.imageborder+'px">'
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML+='</a>'
picobj.innerHTML=slideHTML
}
 
 
fadeshow.prototype.rotateimage=function(){
if (this.pausecheck==1) //if pause onMouseover enabled, cache object
var cacheobj=this
if (this.mouseovercheck==1)
setTimeout(function(){cacheobj.rotateimage()}, 100)
else if (iebrowser&&dom||dom){
this.resetit()
var crossobj=this.tempobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
crossobj.style.zIndex++
fadeclear[this.slideshowid]=setInterval("fadepic(fadearray["+this.slideshowid+"])",50)
this.curcanvas=(this.curcanvas==this.canvasbase+"_0")? this.canvasbase+"_1" : this.canvasbase+"_0"
}
else{
var ns4imgobj=document.images['defaultslide'+this.slideshowid]
ns4imgobj.src=this.postimages[this.curimageindex].src
}
this.curimageindex=(this.curimageindex<this.postimages.length-1)? this.curimageindex+1 : 0
}
 
fadeshow.prototype.resetit=function(){
this.degree=10
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
if (crossobj.filters&&crossobj.filters[0]){
if (typeof crossobj.filters[0].opacity=="number") //if IE6+
crossobj.filters(0).opacity=this.degree
else //else if IE5.5-
crossobj.style.filter="alpha(opacity="+this.degree+")"
}
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=this.degree/101
else if (crossobj.style.KhtmlOpacity)
crossobj.style.KhtmlOpacity=this.degree/100
else if (crossobj.style.opacity&&!crossobj.filters)
crossobj.style.opacity=this.degree/101
}
 
 
fadeshow.prototype.startit=function(){
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
this.populateslide(crossobj, this.curimageindex)
if (this.pausecheck==1){ //IF SLIDESHOW SHOULD PAUSE ONMOUSEOVER
var cacheobj=this
var crossobjcontainer=iebrowser? iebrowser["master"+this.slideshowid] : document.getElementById("master"+this.slideshowid)
crossobjcontainer.onmouseover=function(){cacheobj.mouseovercheck=1}
crossobjcontainer.onmouseout=function(){cacheobj.mouseovercheck=0}
}
this.rotateimage()
}

// TO HIGHLIGHT LOGIN FIRST
var formInUse = false;

function setFocus()
{
	var mainURL = window.location.search;
	var URLparts = mainURL.split('?');
	if(URLparts.length > 1)
	{
		var Arguments = URLparts[1].split('&');
		if(Arguments[0] == "inv")
		{
			var ErrorElement = document.getElementById("errMsg");
			ErrorElement.style.display="block";
			if(Arguments.length > 1)
			{
				document.form1.email.value = Arguments[1];
			}
			$("#middlerightPan").height(235);
			$("#middleleftPan").height(225);
			$("#mainmiddlePan").height(275);
		}
	}
  	
	if(!formInUse) {
  		document.form1.email.focus();
  	}
}
</script>

<link href="css/style_home.css?nocache=1" rel="stylesheet" type="text/css" />
</head>

<body onload="setFocus()">
	
	  

  <!--Top Panel-->
  <div id="topPan">
  
  <!--<div id="logoSet1">
	  	<script type="text/javascript">
		new fadeshow(logoset1, 170, 64, 0, 3200, 0)
		</script>
	  </div>
	  <div id="logoSet2">
	  	<script type="text/javascript">
		new fadeshow(logoset2, 170, 64, 0, 3200, 0)
		</script>
	  </div>
	  <div id="logoSet3">
	  	<script type="text/javascript">
		new fadeshow(logoset3, 170, 64, 0, 3200, 0)
		</script>
	  </div>-->
  <?php if(isset($_SESSION['UID'])) { ?>
  	<div id="loginInfo"><?=$_SESSION['Email']?> | <a href="account"><small>my account</small></a> | <a href="logout"><small>log out</small></a></div>
  	<?php } ?>
  	<a href="index"><img class="bg" src="http://adrollo-images.s3.amazonaws.com/logo4.gif" title="Mobile Advertising | iPhone | Monetize Traffic | AdWhirl" alt="Mobile Advertising | iPhone | Monetize Traffic | AdWhirl" width="265" height="39" border="0" /></a>
	<ul>
		<li><a href="get">Instructions</a></li>
		<li><a href="help">FAQs</a></li>
		<li><a href="blog">Blog</a></li>
	</ul>
    <h1><span></span></h1>
  </div>
  <!--Top Panel Close-->
  
  <!--Main Middle Panel-->
  <div id="mainmiddlePan">
  <!--Middle Panel-->
  <div id="middlePan">
  <!--Middle Left Panel-->
  <div id="middleleftPan">

  <ul>
  <li class="title_bullet">Switch networks without requiring your users to update</li>
  <li class="title_bullet">Choose which ad networks are included in your SDK</li>
  <li class="title_bullet">Cross promote your apps by creating custom ads whenever you want</li>
  </ul>
  <center><a class="dl_button_home" id="start_button" href="start">Get Started</a></center>
  </div>
  

  <!--Middle Left Panel Close-->
  
    <div id="middlerightPan">
  <h2>Log In</h2>
     <form id="form1" name="form1" method="post" action="processLogin">
     	<div class="errorMsg" id="errMsg" style="display:none">
		<b>Error:</b> Incorrect email/password combination
		</div>
		<?php 
  if(isset($_SESSION['UID']))
			{
				echo '<div align="center" style="margin-left: auto; margin-right: auto;"><a href="main"><img src="'.IMAGE_DIRECTORY.'enter.gif"/></a></div>
				<div align="center" style="margin-left: auto; margin-right: auto;">'.$_SESSION['Email'].'</div>
				<div align="center" style="margin-left: auto; margin-right: auto;">Not you? <a href="logout">Log out</a>.</div>';
			}
			else
				echo '<label>Email Address:
<input name="email" type="text" id="email" onfocus="formInUse = true;" tabindex="1"/>
					<label>Password: <a href="forgotPass" style="font-size:12px;">(forgot password?)</a></label>
					<input name="password" type="password" id="password" onfocus="formInUse = true;" tabindex="2" />
					<input name="login" type="submit" class="button" value="Login" tabindex="3" /><br />
					<div id="register">Not Registered? <a href="start">Sign Up Now</a></div>';

		?>
     </form>
    </div>
   
  </div>
  <!--Middle Panel Close-->
  </div>
  <!--Main Middle Panel Close-->
  
<style type="text/css">

#overlay {
     visibility: hidden;
position: absolute;
background-color: #dddddd;
left: 0px;
top: 0px;
width:100%;
height:100%;
text-align:center;
z-index: 1000;
}

#overlay div {
width:300px;
margin: 100px auto;
background-color: #fff;
border:1px solid #000;
padding:15px;
text-align:center;
}

</style>

<div id="overlay">
     <div>
          <p>The AdWhirl website is currently unavailable due to scheduled maintenance.<br /><br />All mobile endpoints are still functional.</p>
     </div>
</div>


<? 
     require_once("includes/inc_tail.html"); 
?>
