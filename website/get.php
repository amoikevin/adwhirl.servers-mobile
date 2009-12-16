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
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/style_fade.css" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />
        <link rel="stylesheet" href="css/codesamples/textmate.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery.lightbox-0.5.css" type="text/css" />

		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="ibox/ibox.js"></script>
		<script type="text/javascript">iBox.setPath('ibox/'); iBox.default_width = 600;</script>
		<script type="text/javascript" src="scripts/contentfader.js"></script>

<?
require_once("includes/inc_global.php");
require_once("includes/inc_head.html");
?>
			
            <!--Top Panel Close--><!--Main Lower Panel-->
            <div id="mainlowerPan">
                <div id="lowerPan">
                    
                <div id="wufoshell">
                        <img id="top" src="http://adrollo-images.s3.amazonaws.com/top.png" alt="" />
                        <div id="container">
                            <form id="form2" name="form2" class="wufoo ">
                            
                                <div class="info">
                                    <h2>AdWhirl  Open Source Client SDK and Server</h2>

                                </div>
									<div class="download_content">
We've developed an open source mediation solution so that advertisers and publishers have an open, transparent ad serving option. The open source solution is available for all iPhone app developers and advertising networks, whether they currently work with AdMob directly or not.
</div>
<br />
<div class="download_content">
The source code and all files are hosted on Google Code along with discussion forums and other community tools.  You can also review an online step-by-step configuration tutorial and download the installation guide below before beginning your application development.
</div>
                                
                                
<a href="http://code.google.com/p/adwhirl">                                	<div class="download_headers">Download AdWhirl Open Source Distribution</div></a>
<br />
<div class="download_headers1">
AdWhirl Client SDK Instructions
</div>
<div class="download_content">
The AdWhirl Open Source Client SDK contains the code for your iPhone application to display ads from different ad networks. To install the client SDK, you must have version 2.2.1 or later of the iPhone SDK, which includes the Xcode IDE, iPhone simulator, and a suite of additional tools. 
</div>
<br />
<div class="download_content">
During the SDK implementation you will be asked to implement your preferred ad networks. You must have the SDKs for the ad networks that you plan to support.  
</div>
<!--
<div class="download_headers2">
Step-by-Step Configuration Tutorial 
</div>
<div class="download_content">
<a href="setup/1">View Now</a>
</div>
-->
<a href="AdWhirl_OpenSourceSDK_Setup_Instructions-3.0.pdf">
<div class="download_headers2">
Download AdWhirl Client SDK Configuration Guide for 3.0+ (PDF)
</div>
</a>

<a href="AdWhirl_OpenSourceSDK_Setup_Instructions-2.2.1.pdf">
<div class="download_headers2">
Download AdWhirl Client SDK Configuration Guide for 2.2.1 (PDF)
</div>
</a>

<br />
<div class="download_headers1">
AdWhirl Server Instructions
</div>
<div class="download_content">
Developers have two options for implementing the mediation server component.
</div>
<br />
<div class="download_content">
Option 1: AdWhirl hosted.  As part of the AdWhirl service we provide a hosting solution for configuring traffic allocation of your included ad networks as well as reviewing basic stats.  This option requires registering for an account on the AdWhirl website and  configuration of the open source SDK.  
</div>
<br />
<div class="download_content">
Option 2: Host your own server and configuration website.  For developers who prefer to host their own configuration server and website, we've released the source code for both.
</div>
<br />
<div class="download_content">
The server runs in Amazon's web cloud. To set up the server, you need an Amazon Web Services (AWS) account and the AMI (Amazon Machine Image) for the AdWhirl server, which contains all the software, including the operating system and associated configuration settings, applications, and libraries.
</div>

<a href="AdWhirl_OpenSourceServer_Setup_Instructions.pdf">
<div class="download_headers2">
Download AdWhirl Server Configuration Guide (PDF)
</div>
</a>
                                
                                
                            </form>
                        </div>
                        <!--container--><img id="bottom" src="http://adrollo-images.s3.amazonaws.com/bottom.png" alt="" />
                    </div>
                </div>
            </div><!--Main Lower Panel Close-->
			
<?
require_once("includes/inc_tail.html");
?>
