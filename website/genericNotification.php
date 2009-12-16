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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/ico" href="favicon.ico"></link>
<link rel="shortcut icon" href="favicon.ico"></link>
<title>AdWhirl: Generic Notifications</title>
<!-- CSS -->
<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<link rel="stylesheet" href="css/style_fade.css" type="text/css" />

<?
require_once("includes/inc_global.php");
require_once("includes/inc_head.html");
?>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
	
<div id="wufoshell">
<img id="top" src="http://adrollo-images.s3.amazonaws.com/top.png" alt="" />
<div id="container">

<form id="form2" name="form2" class="wufoo " autocomplete="off"
	enctype="multipart/form-data" method="post" action="#">

<div class="info">
	<h2>Generic Notifications</h2>
	<div>What can I do with them as a developer?</div>
</div>

<ul>

Generic Notifications are a flexible way for developers to notify their apps of an event.<br/><br/>

You can use generic notifications to disable ads a certain percentage of the time, independently integrate in ad networks that we have not partnered with (Google Adsense, Greystripe, and/or LiveRail, for example), send occassional notifications to your users, or literally do anything you want to.
<br/><br/>
Unfortunately, we cannot help optimize ad networks integrated with generic notifications as we have no visibility into them, but we have provided this tool as a convenience for developers.
<br/><br/>
For information on how to setup generic notifications, please visit our <a href="instructions?p=6">instructions</a> page. Or just send us an <a href="mailto:support@adwhirl.com">email</a>!


<li id="foli11" 		class="   ">
<div></div>
</li>

<li id="foli13" 		class="section   ">
		<h3 id="title13">Something we missed?</h3>
		<div id="instruct13">Email us at <a href="mailto:support@adwhirl.com">support@adwhirl.com</a> and we'll respond as soon as possible!</div>
	</li>

	<li style="display:none">
		<label for="comment">Do Not Fill This Out</label>
		<textarea name="comment" id="comment" rows="1" cols="1"></textarea>
	</li>
</ul>
</form>

</div><!--container-->
<img id="bottom" src="http://adrollo-images.s3.amazonaws.com/bottom.png" alt="" />
</div>
<!-- wufoo shell-->

	</div>
</div>
<!--Main Lower Panel Close-->

<?
require_once("includes/inc_tail.html");
?>
