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

require_once ('includes/inc_global.php');
require_once ('includes/app_functions_get.php');
require_once ('includes/inc_login.php');
require ('includes/inc_head.html');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Create Application</title>
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
        
  if(isset($_GET['AppID'])) {
    $appId = $_GET['AppID'];
  }

        $allApps = getApps($_SESSION['UID']);
        
        $errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
        		<b>Error:</b> Please fill in all required fields.
        		</div><br/>';
        		
        if (count($allApps) > 0) {
            
        ?>
        <div id="appForm">
            <form name="selectForm" action="main" method="GET">
                <a href="main?AppID=<?=$appId?>">Go Back</a>
                <font color="#cccccc">
                    |
                </font>
                Select Application: 
                <select name="AppID" onchange="this.form.submit()">
                    <? 
                    foreach ($allApps as $oneApp) {
                        if ($oneApp['AppID'] == $appId)
                            $selected = 'SELECTED';
                        else
                            $selected = '';
                            
                        echo '<option value="'.$oneApp['AppID'].'" '.$selected.'>'.$oneApp['Name'].'</option>';
                    }
                    
                    $subject = 'Add a New Application';
                    ?>
                </select>
                <noscript>
                    <input type="submit" class="formbutton" value="Go" />
                </noscript>
            </form>
        </div>
        <?php 
        } else {
            $subject = 'Welcome! You Haven\'t Registered Any Apps Yet.';
        }
        ?>
        <!--Main Lower Panel-->
        <div id="mainlowerPan">
            <div id="lowerPan">
                <div id="wufoshell">
                    <img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
                    <div id="container">
                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processNewApplication" onsubmit="return checkform(this);">
                            <?= $errMsg?>
                            <div class="info">
                                <h2><?= $subject?></h2>
                                <div>
                                    Add one now! It can be an app that you're still developing or one that's already in the appstore!
                                    <br/>
                                    Don't worry about what you type here, you can always change your info at any time.
                                </div>
                            </div>
                            <ul>
                                <li id="foli1" class="   ">
                                    <label class="desc" id="title1" for="Field1">
                                        App Name<span id="req_1" class="req">*</span>
                                    </label>
                                    <div>
                                        <input id="name" name="name" type="text" class="field text medium" value="" maxlength="255" tabindex="1" />
                                    </div>
                                </li>
                                <li id="foli2" class="   ">
                                    <label class="desc" id="title2" for="Field2">
                                        App Store URL
                                    </label>
                                    <div>
                                        <input id="storeurl" name="storeurl" type="text" class="field text medium" value="" maxlength="255" tabindex="2" />
                                    </div>
                                    <p class="instruct" id="instruct2">
                                        <small>
                                            Optional, but this will be useful if you ever want to cross promote this app.
                                        </small>
                                    </p>
                                </li>
                                <li id="foli103" class="   ">
                                    <label class="desc" id="title103" for="Field103">
                                        App Category<span id="req_103" class="req">*</span>
                                    </label>
                                    <div>
                                        <select id="category" name="category" class="field select medium" tabindex="3">
                                            <?php 
                                            for ($i = 0; $i < count($allAppCategories); $i++) {
                                                $categoryValue = $i + 1;
                                                if ($categoryValue == 4)
                                                    $selectedText = 'selected="selected"';
                                                else
                                                    $selectedText = '';
                                                    
                                                echo "<option value=\"$allAppCategories[$i]\" $selectedText>{$allAppCategories[$i]}</option>";
                                            }
                                            
                                            ?>
                                        </select>
                                    </div>
                                </li>
                                <li id="foli104" class="   ">
                                    <label class="desc" id="title104" for="Field104">
                                        App Description
                                    </label>
                                    <div>
                                        <textarea id="description" name="description" class="field textarea small" rows="10" cols="50" tabindex="4"></textarea>
                                    </div>
                                    <p class="instruct" id="instruct104">
                                        <small>
                                            Optional, just a brief description of what your app is or does.
                                        </small>
                                    </p>
                                </li>
                                <br/>
                                <li id="foli0" class="   ">
                                    <label class="desc" id="title1" for="Field1">
                                        <a href="#" onclick="showExtra(); return false;" style="font-size:1.5em">(Optional) Settings for Ads</a>
                                    </label>
                                </li>
                                <li id="extra1" class="   " style="display:none">
                                    <label class="desc" id="title2" for="Field2">
                                        Background Color
                                    </label>
                                    <div>
                                        <input id="bgcolor" name="bgcolor" type="text" class="field text medium" value="#000000" maxlength="255" tabindex="5" />
                                    </div>
                                    <p class="instruct" id="instruct104">
                                        <small>
                                            This affects all AdWhirl custom ads and, when possible, modifies background colors of ads from other ad networks, too.
                                        </small>
                                    </p>
                                </li>
                                <li id="extra2" class="   " style="display:none">
                                    <label class="desc" id="title2" for="Field2">
                                        Text Color
                                    </label>
                                    <div>
                                        <input id="txtcolor" name="txtcolor" type="text" class="field text medium" value="#FFFFFF" maxlength="255" tabindex="6" />
                                    </div>
                                    <p class="instruct" id="instruct104">
                                        <small>
                                            This affects all AdWhirl custom ads and, when possible, modifies text colors of ads from other ad networks, too.
                                        </small>
                                    </p>
                                </li>
                                <li id="extra3" class="   " style="display:none">
                                    <label class="desc" id="title2" for="Field2">
                                        Automatic Refresh
                                    </label>
                                    <div>
                                        <select id="refresh" name="refresh" class="field select medium" tabindex="7">
                                            <option value="30000">Disabled</option>
                                            <option value="15">15 seconds</option>
                                            <option value="30" selected>30   seconds</option>
                                            <option value="45">45 seconds</option>
                                            <option value="60">1 minutes</option>
                                            <option value="120">2 minutes</option>
                                            <option value="180">3 minutes</option>
                                            <option value="240">4 minutes</option>
                                            <option value="300">5 minutes</option>
                                            <option value="600">10 minutes</option>
                                        </select>
                                    </div>
                                    <p class="instruct" id="instruct104">
                                        <small>
                                            AdWhirl can automatically fetch a new ad to display at set intervals - alternatively, you can have this disabled and manually call the method to grab a new ad directly from the AdWhirl library.
                                        </small>
                                    </p>
                                </li>
                                <li id="extra4" class="   " style="display:none">
                                    <label class="desc" id="title2" for="Field2">
                                        Ad Transition Animation
                                    </label>
                                    <div>
                                        <select id="animation" name="animation" class="field select medium" tabindex="8">
                                            <option value="<?=ANIMATION_SLIDE_FROM_RIGHT?>">Slide From Right</option>
                                            <option value="<?=ANIMATION_SLIDE_FROM_LEFT?>">Slide From Left</option>
                                            <option value="<?=ANIMATION_FLIP_FROM_RIGHT?>">Flip from Right</option>
                                            <option value="<?=ANIMATION_FLIP_FROM_LEFT?>">Flip from Left</option>
                                            <option value="<?=ANIMATION_FADE_IN?>">Fade In</option>
                                            <option value="<?=ANIMATION_CURL_UP?>">Curl Up</option>
                                            <option value="<?=ANIMATION_CURL_DOWN?>">Curl Down</option>
                                            <option value="<?=ANIMATION_RANDOM?>" selected>Random</option>
                                            <option value="<?=ANIMATION_NONE?>">None</option>
                                        </select>
                                    </div>
                                    <p class="instruct" id="instruct104">
                                        <small>
                                            Specify the animation that happens when a new ad comes in.
                                        </small>
                                    </p>
                                </li>
                                <li id="extra5" class="   " style="display:none">
                                    <label class="desc" id="title21" for="Field21">
                                    </label>
                                    <div class="column">
                                        <input id="allowlocation" name="allowlocation" type="checkbox" class="field checkbox" tabindex="9" />
                                        <label class="choice" for="Field21">
                                            Allow Location Access
                                        </label>
                                    </div>
                                    <p class="instruct" id="instruct21">
                                        <small>
                                            Location access allows ad networks (such as admob) to further target your users and increase CPM, but if your app doesn't already use the iPhone location API, users will be prompted for permission to access their locations.
                                        </small>
                                    </p>
                                </li>
                                <br/>
                                <br/>
                                <li class="buttons">
                                    <input id="saveForm" class="btTxt" type="submit" value="Add Application" /><strong>OR</strong>
                                    <a href="main">Cancel</a>
                                </li>
                                <li style="display:none">
                                    <label for="comment">
                                        Do Not Fill This Out
                                    </label>
                                    <textarea name="comment" id="comment" rows="1" cols="1">
                                    </textarea>
                                </li>
                            </ul>
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
            function showExtra(){
                var form = document.getElementById("form2");
                var listElementStyle1 = document.getElementById("extra1").style;
                var listElementStyle2 = document.getElementById("extra2").style;
                var listElementStyle3 = document.getElementById("extra3").style;
                var listElementStyle4 = document.getElementById("extra4").style;
                var listElementStyle5 = document.getElementById("extra5").style;
                
                if (listElementStyle1.display == "none") {
                    listElementStyle1.display = "block";
                    listElementStyle2.display = "block";
                    listElementStyle3.display = "block";
                    listElementStyle4.display = "block";
                    listElementStyle5.display = "block";
                }
                else {
                    listElementStyle1.display = "none";
                    listElementStyle2.display = "none";
                    listElementStyle3.display = "none";
                    listElementStyle4.display = "none";
                    listElementStyle5.display = "none";
                }
            }
            
            function checkform(form){
                var listElementStyle = document.getElementById("errMsg").style;
                // see http://www.thesitewizard.com/archive/validation.shtml
                // for an explanation of this script and how to use it on your
                // own website
                
                // ** START **
                if (form.name.value != "" && form.bgcolor.value != "" && form.txtcolor.value != "") {
                    return true;
                }
                
                listElementStyle.display = "block";
                
                // ** END **
                return false;
            }
            
            //-->
        </script>
