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
        <title>AdWhirl: Create Custom Ad</title>
        <!-- CSS -->
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />
        <link rel="stylesheet" href="css/style_fade.css" type="text/css" />
        <!-- JavaScript -->
        <script type="text/javascript" src="scripts/wufoo.js">
        </script>
        <script type="text/javascript" src="scripts/jquery.js">
        </script>
        <script type="text/javascript" src="scripts/contentfader.js">
        </script>
        <?php 
        require_once ('includes/inc_global.php');
        require_once ('includes/app_functions_get.php');
        require_once ('includes/inc_login.php');
        require ('includes/inc_head.html');

function cmp($a, $b)
{
  return strcmp($a["Name"], $b["Name"]);
}

        
        if (!isset($_GET['AppID']))
            exit;
            
        $appId = $_GET['AppID'];
        
        $errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
        		<b>Error:</b> Please fill in all required fields.
        		</div><br/>';



$appId = $_GET['AppID'];
$allApps = getApps($_SESSION['UID']);
if(count($allApps) == 0)
  {	
    header('Location: addApplication');
    exit;
  }
if($appId == null)
  $appId = $allApps[0]['AppID'];

$selectText = '';
$found = false;
usort($allApps, 'cmp');
foreach($allApps as $oneApp)
{
  if($oneApp['AppID'] == $appId)
    {
      $found = true;
      $selected = 'SELECTED';
      $customPriority = $oneApp['CustomPriority'];
      $appName = $oneApp['Name'];
      $adsOn = ($oneApp['AdsOn'] == 1);
    }
  else 
    $selected = '';
		
  $selectText .= '<option value="'.$oneApp['AppID'].'" '. $selected .'>'.$oneApp['Name'].'</option>';
}

?>



<div id="appForm">

<?
require_once('includes/appFormLeft.php');
?>

</div>
<br />

<?php $info = getAppInfo($appId); ?>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
	
		<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">

  <div id="tab7">
  <ul id="tabnav">
  <li class="tab1"><a href="main?AppID=<?=$appId?>">All Ads</a></li>
  <!--<li class="tab2"><a href="customView?AppID=<?=$appId?>">Custom Ads</a></li>-->
  <li class="tab3"><a href="rollover?AppID=<?=$appId?>">Rollover Priorities</a></li>
  <li class="tab5"><a href="editApplication?AppID=<?=$appId?>">Edit App Info</a></li>
  <li class="tab6"><a href="reporting?AppID=<?=$appId?>">Reporting</a></li>
  <li class="tab7"><a href="addCustom?AppID=<?=$appId?>">Custom Ad</a></li>
  </ul>
  </div>

                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processCustomAd?AppID=<?=$appId?>" onsubmit="return checkform(this);">
                            <?= $errMsg?>
                            <div class="info">
                                <h2>Add a New Custom Ad</h2>
                                <div>
                                    Custom ads allow you to cross-promote websites, other iphone apps, or promote your paid apps through your free ones - and you can put all these up on your own apps for free.
                                </div>
                            </div>
                            <div id="whatnewstoggler" class="fadecontenttoggler">
                                <a href="#" class="toc">App Store</a>
                                <a href="#" class="toc">Website</a>
                                <a href="#" class="toc">Call</a>
                                <a href="#" class="toc">Video</a>
                                <a href="#" class="toc">Audio</a>
                                <a href="#" class="toc">iTunes</a>
                                <a href="#" class="toc">Maps</a>
                            </div>
                            <div id="whatsnew" class="fadecontentwrapper">
                                <!-- ***************SECTION 1*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_app.jpg"/></span>
                                    <ul>
                                        <h2 style="font-size:12px; color:#0099ff; margin-left: auto; margin-right: auto; text-align:center;">Please confirm you've selected the correct ad type above (Website, App Store, etc.) before filling out the below.</h2>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category2" name="category2" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category2, 2);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage2" name="uploadimage2" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name2" name="name2" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext2" name="adtext2" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                App Store URL<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra2" name="extra2" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the link to your download page in the App Store, e.g. http://phobos.apple.com/WebObjects/MZStore .woa/wa/viewSoftware?id=295438909&mt=8
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add App Store Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 2*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_web.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category1" name="category1" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category1, 1);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage1" name="uploadimage1" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads (we'll resize automatically).
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name1" name="name1" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli1" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext1" name="adtext1" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Website URL<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra1" name="extra1" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    The ideal website is an iphone formatted website.
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add Website Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 3*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_call.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category3" name="category3" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category3, 3);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage3" name="uploadimage3" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name3" name="name3" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli3" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext3" name="adtext3" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Phone Number<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra3" name="extra3" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    The phone number that users will call when they click on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add Call Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 4*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_video.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category4" name="category4" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category4, 4);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage4" name="uploadimage4" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name4" name="name4" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli4" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext4" name="adtext4" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Video URL<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra4" name="extra4" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    YouTube videos must be playable on the iPhone. Other videos must be playable in Quicktime.
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add Video Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 5*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_audio.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category5" name="category5" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category5, 5);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage5" name="uploadimage5" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name5" name="name5" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli5" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext5" name="adtext5" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Audio URL<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra5" name="extra5" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Audio must be playable in Quicktime.
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add Audio Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 6*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_itunes.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category6" name="category6" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category6, 6);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage6" name="uploadimage6" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name6" name="name6" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli6" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext6" name="adtext6" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                iTunes URL (<a href="http://www.apple.com/itunes/linkmaker/faq/">what is this?</a>)<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra6" name="extra6" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Link to an iTunes page. <a href="http://www.apple.com/itunes/linkmaker/faq/" target="_blank">Learn More</a>
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add iTunes Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                                <!-- ***************SECTION 7*************** -->
                                <div class="fadecontent">
                                    <span class="fadeimage"><img src="<?=IMAGE_DIRECTORY?>custom_map.jpg"/></span>
                                    <ul>
                                        <li id="foli103" class="smaller">
                                            <label class="desc" id="title103" for="Field103">
                                                Ad Type<span id="req_103" class="req">*</span>
                                            </label>
                                            <div>
                                                <select id="category7" name="category7" class="field select" tabindex="3" onchange="OnChangeHide(this.form.category7, 7);">
                                                    <option value="<?=CUSTOM_ICON?>">Image and Text</option>
                                                    <option value="<?=CUSTOM_BANNER?>">Just Image (Banner Ad)</option>
                                                </select>
                                                <p class="instruct" id="instruct2">
                                                    <small>
                                                        Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                    </small>
                                                </p>
                                            </div>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Image<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="uploadimage7" name="uploadimage7" type="file" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Please upload a 320x50 image for banner ads, or a 38x38 image for text ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli1" class="smaller">
                                            <label class="desc" id="title1" for="Field1">
                                                Ad Name<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="name7" name="name7" type="text" class="field text" value="" maxlength="255" tabindex="1" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is just your own name you use to keep track of your ads.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="adtextli7" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Ad Text<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="adtext7" name="adtext7" type="text" class="field text large" value="" maxlength="255" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    This is the text that users will see on your ad.
                                                </small>
                                            </p>
                                        </li>
                                        <li id="foli2" class="smaller">
                                            <label class="desc" id="title2" for="Field2">
                                                Map Keywords<span id="req_1" class="req">*</span>
                                            </label>
                                            <div>
                                                <input id="extra7" name="extra7" type="text" class="field text large" value="" maxlength="510" tabindex="2" />
                                            </div>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Your keywords will be used to search Google Maps for results in the local area of the user.
                                                </small>
                                            </p>
                                        </li>
                                        <li class="buttons">
                                            <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Add Maps Ad" />
                                        </li>
                                        <li style="display:none">
                                            <label for="comment">
                                                Do Not Fill This Out
                                            </label>
                                            <textarea name="comment" id="comment" rows="1" cols="1">
                                            </textarea>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <script type="text/javascript">
                                //SYNTAX: fadecontentviewer.init("maincontainer_id", "content_classname", "togglercontainer_id", selectedindex, fadespeed_miliseconds)
                                fadecontentviewer.init("whatsnew", "fadecontent", "whatnewstoggler", 0, 400)
                            </script>
                        </form>
                    </div>
                    <!--container--><img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
                </div><!-- wufoo shell-->
            </div>
        </div><!--Main Lower Panel Close-->
        <?php 
        require ('includes/inc_tail.html');
        ?>
        <script language="JavaScript" type="text/javascript">
            <!--
            function checkform(form){
                var listElementStyle = document.getElementById("errMsg").style;
                // see http://www.thesitewizard.com/archive/validation.shtml
                // for an explanation of this script and how to use it on your
                // own website
                
                // ** START **
                if ((form.name1.value != "" && form.extra1.value != "" && form.uploadimage1.value != "") ||
                (form.name2.value != "" && form.extra2.value != "" && form.uploadimage2.value != "") ||
                (form.name3.value != "" && form.extra3.value != "" && form.uploadimage3.value != "") ||
                (form.name4.value != "" && form.extra4.value != "" && form.uploadimage4.value != "") ||
                (form.name5.value != "" && form.extra5.value != "" && form.uploadimage5.value != "") ||
                (form.name6.value != "" && form.extra6.value != "" && form.uploadimage6.value != "") ||
                (form.name7.value != "" && form.extra7.value != "" && form.uploadimage7.value != "")) {
                    return true;
                }
                
                listElementStyle.display = "block";
                
                // ** END **
                return false;
            }
            
            //-->
        </script>
