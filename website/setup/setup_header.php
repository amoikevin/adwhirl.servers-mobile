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
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Instructions</title>
        <!-- CSS -->
        <link href="../css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../css/style_fade.css" type="text/css" />
        <link rel="stylesheet" href="../css/structure.css" type="text/css" />
        <link rel="stylesheet" href="../css/form.css" type="text/css" />
        <link rel="stylesheet" href="../css/theme.css" type="text/css" />
        <link rel="stylesheet" href="../css/codesamples/textmate.css" type="text/css" />
		<script type="text/javascript" src="../scripts/jquery.js"></script>
		<script type="text/javascript" src="../scripts/contentfader.js"></script>


<?
chdir("../");
require_once("includes/inc_global.php");
?>

		</head>
        <body>
                <div id="topPan">
  	<?php if($_SESSION['UID']) { ?>
  	<div id="loginInfo"><?=$email?> | <a href="account"><small>my account</small></a> | <a href="logout"><small>log out</small></a></div>
  	<?php } ?>

                <a href="../index"><img src="http://adrollo-images.s3.amazonaws.com/logo_adwhirl.gif" title="Mobile Advertising | iPhone | Monetize Traffic | AdWhirl" alt="Mobile Advertising | iPhone | Monetize Traffic | AdWhirl" width="265" height="39" border="0" /></a>
                <ul class="">
                    <li><a href="../main">My AdWhirl</a></li>
		    <li><a href="../get">Instructions</a></li>
		    <li><a href="../help">FAQs</a></li>
		    <li><a href="../blog">Blog</a></li>
                </ul>
            </div>
