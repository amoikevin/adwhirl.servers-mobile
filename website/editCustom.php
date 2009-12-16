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
        <title>AdWhirl: Edit Custom Ad</title>
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
        require_once ('includes/network_functions_get.php');
        require_once ('includes/inc_login.php');
        require ('includes/inc_head.html');
        
        $appId = $_GET['AppID'];
        $nid = $_GET['NID'];
        
        $networksCustom = getAppNetworksCustom($uid, $appId);

        $submitType = $networksCustom[$nid]['LinkType'];
        
        $customType = $networksCustom[$nid]['CustomType'];
        
        $adName = $networksCustom[$nid]['Name'];
        $adText = $networksCustom[$nid]['Description'];
        $extra = $networksCustom[$nid]['Link'];
        $imageLink = $networksCustom[$nid]['ImageLink'];
        
        
        $errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
        		<b>Error:</b> Please fill in all required fields.
        		</div><br/>';
        		
        $successMsg = '<div id="successMsg" class="successMsg">
        			Your changes have been saved.
        			</div>';
        ?>
        <div id="appForm">
            <form name="selectForm" action="main" method="GET">
                <a href="main?AppID=<?=$appId?>">Go Back</a>
                <font color="#cccccc">
                    |
                </font>
                <?php $info = getAppInfo($appId); echo $info['Name']; ?>
            </form>
        </div>
        <!--Main Lower Panel-->
        <div id="mainlowerPan">
            <div id="lowerPan">
                <div id="wufoshell">
                    <img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
                    <div id="container">
                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processEditCustom?AppID=<?=$appId?>" onsubmit="return checkform(this);">
                            <?php if(isset($_GET['EditCustom']) && $_GET['EditCustom'] == 1) echo $successMsg; ?>
                            <?= $errMsg?>
                            <div class="info">
                                <h2>Edit Custom Ad</h2>
                                <div>
                                    Custom ads allow you to cross-promote websites, other iphone apps, or promote your paid apps through your free ones - and you can put all these up on your own apps for free. <strong>You can always adjust background and text colors from the <a href="editApplication?AppID=<?=$appId?>">"Edit App Info"</a> tab.</strong>
                                </div>
                            </div>
                            <div class="adtop">
                            </div>
                            <?php if ($customType == CUSTOM_BANNER) { ?>
                            <div class="admid">
                                <span><img src="<?=$imageLink?>"/></span>
                            </div>
                            <?php } else { ?>
                            <div id="preview_content">
                                <div id="preview_ad" style="background-color:#<?=$info['BGColor']?>;">
                                    <img style="background: transparent url(<?=$imageLink?>) no-repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" src="<?=IMAGE_DIRECTORY?>ad_frame.gif" class="logo" id="logo_image"/>
                                    <table cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr valign="middle">
                                                <td>
                                                    <p style="color:#<?=$info['TxtColor']?>;">
<?= $adText?>
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--<img src="http://www.admob.com/img/create_iphone_ad/action_download.png" class="action_img" id="action_image"/>-->
                                </div>
                            </div>
                            <?php } ?>
                            <div class="adbot">
                            </div>
                            <!-- ***************SECTION 1*************** --><input type="hidden" name="nid" value="<?=$nid?>">
                            <div>
                                <ul>
                                    <li id="foli101" class="   ">
                                        <label class="desc" id="title101" for="Field101">
                                            Destination Type<span id="req_101" class="req">*</span>
                                        </label>
                                        <div>
                                            <select id="cftype" name="cftype" class="field select medium" tabindex="3">
                                                <option value="2"<?= ($submitType == 2) ? 'selected="selected"' : ''?>>App Store</option>
                                                <option value="1"<?= ($submitType == 1) ? 'selected="selected"' : ''?>>Website</option>
                                                <option value="3"<?= ($submitType == 3) ? 'selected="selected"' : ''?>>Call</option>
                                                <option value="4"<?= ($submitType == 4) ? 'selected="selected"' : ''?>>Video</option>
                                                <option value="5"<?= ($submitType == 5) ? 'selected="selected"' : ''?>>Audio</option>
                                                <option value="6"<?= ($submitType == 6) ? 'selected="selected"' : ''?>>iTunes</option>
                                                <option value="7"<?= ($submitType == 7) ? 'selected="selected"' : ''?>>Maps</option>
                                            </select>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    The Destination Type affects the behavior of the ad when users click on it. For instance, web ads show up in a canvas view, while App Store ads take the user to the App page.
                                                </small>
                                            </p>
                                        </div>
                                    </li>
                                    <li id="foli103" class="   ">
                                        <label class="desc" id="title103" for="Field103">
                                            Ad Type<span id="req_103" class="req">*</span>
                                        </label>
                                        <div>
                                            <select id="category" name="category" class="field select medium" tabindex="3" onchange="OnChangeHide(this.form.category, 1);">
                                                <option value="<?=CUSTOM_ICON?>"<?= ($customType == CUSTOM_ICON) ? 'selected="selected"' : ''?>>Image and Text</option>
                                                <option value="<?=CUSTOM_BANNER?>"<?= ($customType == CUSTOM_BANNER) ? 'selected="selected"' : ''?>>Just Image (Banner   Ad) </option>
                                            </select>
                                            <p class="instruct" id="instruct2">
                                                <small>
                                                    Image and Text Ads allow you to put custom text next to a logo, while Banner Ads allow you to put up a custom banner image that spans the width of the iphone.
                                                </small>
                                            </p>
                                        </div>
                                    </li>
                                    <li id="foli1" class="   ">
                                        <label class="desc" id="title1" for="Field1">
                                            Image (Only upload if you intend to change)
                                        </label>
                                        <div>
                                            <input id="uploadimage" name="uploadimage" type="file" />
                                        </div>
                                        <p class="instruct" id="instruct2">
                                            <small>
                                                Please upload a 320x50 image for banner ads, or a 38x38 image for text ads (we'll resize automatically).
                                            </small>
                                        </p>
                                    </li>
                                    <li id="foli1" class="   ">
                                        <label class="desc" id="title1" for="Field1">
                                            Ad Name<span id="req_1" class="req">*</span>
                                        </label>
                                        <div>
                                            <input id="name" name="name" type="text" class="field text medium" maxlength="255" tabindex="1" value="<?=$adName?>" />
                                        </div>
                                        <p class="instruct" id="instruct2">
                                            <small>
                                                This is just your own name you use to keep track of your ads.
                                            </small>
                                        </p>
                                    </li>
                                    <li id="adtextli1" class="   "<?= ($customType == CUSTOM_BANNER) ? 'style="display:none"' : ''?>>
                                        <label class="desc" id="title2" for="Field2">
                                            Ad Text<span id="req_1" class="req">*</span>
                                        </label>
                                        <div>
                                            <input id="adtext" name="adtext" type="text" class="field text medium" maxlength="255" tabindex="2" value="<?=$adText?>" />
                                        </div>
                                        <p class="instruct" id="instruct2">
                                            <small>
                                                This is the text that users will see on your ad.
                                            </small>
                                        </p>
                                    </li>
                                    <li id="foli2" class="   ">
                                        <label class="desc" id="title2" for="Field2">
                                            URL, Phone Number, or Keywords<span id="req_1" class="req">*</span>
                                        </label>
                                        <div>
                                            <input id="extra" name="extra" type="text" class="field text medium" maxlength="500" tabindex="2" value="<?=$extra?>" />
                                        </div>
                                        <p class="instruct" id="instruct2">
                                            <small>
                                                Where users go when they click on your ad.
                                            </small>
                                        </p>
                                    </li>
                                    <li class="buttons">
                                        <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Save Changes" /><strong>OR</strong>
                                        <a href="main?AppID=<?=$appId?>">Cancel</a>
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
            function checkform ( form )
            {
              var listElementStyle = document.getElementById("errMsg").style;
              
              <?php if($_GET['EditCustom'] == 1) { ?>
              var successListElementStyle = document.getElementById("successMsg").style;
              <?php } ?>
              
              // ** START **
              if (form.name.value != "" && form.extra.value != "") {
                return true;
              }
              
              <?php if($_GET['EditCustom'] == 1) { ?>
              successListElementStyle.display="none";
              <?php } ?>
              listElementStyle.display="block";
              
              // ** END **
              return false;
            }
            //-->
        </script>
