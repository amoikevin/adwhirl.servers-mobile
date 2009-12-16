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

function cmp($a, $b)
{
  return strcmp($a["Name"], $b["Name"]);
}

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;

$errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
        		<b>Error:</b> Please fill in all required fields.
        		</div><br/>';

$successMsg = '<div id="successMsg" class="successMsg">
        			Your changes have been saved.
        			</div>';

$appId2 = $_GET['AppID'];
$allApps2 = getApps($_SESSION['UID']);
if(count($allApps2) == 0)
  {	
    header('Location: addApplication');
    exit;
  }
if($appId2 == null)
  $appId2 = $allApps2[0]['AppID'];

$selectText = '';
$found = false;
usort($allApps2, 'cmp');
foreach($allApps2 as $oneApp)
{
  if($oneApp['AppID'] == $appId2)
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Edit Application</title>
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
<?
require ('includes/inc_head.html');
?>


<div id="appForm">

<?
require_once('includes/appFormLeft.php');
?>

</div>
<br />


    		<?php 
			$info = getAppInfo($appId);
			$category = getAppCategory($appId);
			$extendedInfo = getAppExtendedInfo($appId);
		   ?>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
		
		<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">
			<div id="tab5">
			<ul id="tabnav">
				<li class="tab1"><a href="main?AppID=<?=$appId?>">All Ads</a></li>
				<!--<li class="tab2"><a href="customView?AppID=<?=$appId?>">Custom Ads</a></li>-->
				<li class="tab3"><a href="rollover?AppID=<?=$appId?>">Rollover Priorities</a></li>
				<li class="tab5"><a href="editApplication?AppID=<?=$appId?>">Edit App Info</a></li>
				<li class="tab6"><a href="reporting?AppID=<?=$appId?>">Reporting</a></li>
				<li class="tab7"><a href="addCustom?AppID=<?=$appId?>">Custom Ad</a></li>
			</ul>
			</div>


                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processChangeApplication" onsubmit="return checkform(this);">
			<?php if (isset($_GET['EditApp']) && $_GET['EditApp'] == 1) echo $successMsg; ?>
	    <? if(isset($errMsg)) { echo $errMsg; } ?>
                            <input type="hidden" name="appid" value="<?=$appId?>">
                            <div class="info">
				<h2>Edit Application  <font size="1"><a class="delete" href="deleteApplication?AppID=<?=$appId?>">(Delete Application)</a></font></h2>
                                <div>
                                    Here are the current application details.
                                </div>
                            </div>
                            <ul>
                                <li id="foli1" class="   ">
                                    <label class="desc" id="title1" for="Field1">
                                        App Name<span id="req_1" class="req">*</span>
                                    </label>
                                    <div>
                                        <input id="name" name="name" type="text" class="field text medium" value="<?=$info['Name']?>" maxlength="255" tabindex="1" />
                                    </div>
                                </li>
                                <li id="foli2" class="   ">
                                    <label class="desc" id="title2" for="Field2">
                                        App Store URL
                                    </label>
                                    <div>
                                        <input id="storeurl" name="storeurl" type="text" class="field text medium" value="<?=$extendedInfo['Url']?>" maxlength="255" tabindex="2" />
                                    </div>
                                </li>
                                <li id="foli103" class="   ">
                                    <label class="desc" id="title103" for="Field103">
                                        App Category<span id="req_103" class="req">*</span>
                                    </label>
                                    <div>
                                        <select id="category" name="category" class="field select medium" tabindex="3">
                                            <?php 
                                            for ($i = 0; $i < count($allAppCategories); $i++) {
						    if ($allAppCategories[$i] == $category)
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
                                        <textarea id="description" name="description" class="field textarea small" rows="10" cols="50" tabindex="4">
<?= $extendedInfo['Description']?></textarea>
                                    </div>
                                </li>
                                <br/><br />
                                    <div class="info">
				    <h2>Settings for Ads (Optional)</h2>
                                    </div>
                                <li id="extra1" class="   " style="display:block">
                                    <label class="desc" id="title2" for="Field2">
                                        Background Color
                                    </label>
                                    <div>
                                        <input id="bgcolor" name="bgcolor" type="text" class="field text medium" value="#<?=$info['BGColor']?>" maxlength="255" tabindex="5" />
                                    </div>
                                </li>
                                <li id="extra2" class="   " style="display:block">
                                    <label class="desc" id="title2" for="Field2">
                                        Text Color
                                    </label>
                                    <div>
                                        <input id="txtcolor" name="txtcolor" type="text" class="field text medium" value="#<?=$info['TxtColor']?>" maxlength="255" tabindex="6" />
                                    </div>
                                </li>
                                <li id="extra3" class="   " style="display:block">
                                    <label class="desc" id="title2" for="Field2">
                                        Automatic Refresh
                                    </label>
                                    <div>
                                        <select id="refresh" name="refresh" class="field select medium" tabindex="7">
                                            <option value="30000"<?= ($info['RefreshInterval'] == 30000) ? 'selected' : ''?>>Disabled</option>
                                            <option value="15"<?= ($info['RefreshInterval'] == 15) ? 'selected' : ''?>>15   seconds</option>
                                            <option value="30"<?= ($info['RefreshInterval'] == 30) ? 'selected' : ''?>>30   seconds</option>
                                            <option value="45"<?= ($info['RefreshInterval'] == 45) ? 'selected' : ''?>>45   seconds</option>
                                            <option value="60"<?= ($info['RefreshInterval'] == 60) ? 'selected' : ''?>>1   minutes</option>
                                            <option value="120"<?= ($info['RefreshInterval'] == 120) ? 'selected' : ''?>>2   minutes</option>
                                            <option value="180"<?= ($info['RefreshInterval'] == 180) ? 'selected' : ''?>>3   minutes</option>
                                            <option value="240"<?= ($info['RefreshInterval'] == 240) ? 'selected' : ''?>>4   minutes</option>
                                            <option value="300"<?= ($info['RefreshInterval'] == 300) ? 'selected' : ''?>>5   minutes</option>
                                            <option value="600"<?= ($info['RefreshInterval'] == 600) ? 'selected' : ''?>>10   minutes</option>
                                        </select>
                                    </div>
                                </li>
                                <li id="extra4" class="   " style="display:block">
                                    <label class="desc" id="title2" for="Field2">
                                        Ad Transition Animation
                                    </label>
                                    <div>
                                        <select id="animation" name="animation" class="field select medium" tabindex="8">
                                            <option value="<?=ANIMATION_SLIDE_FROM_RIGHT?>"<?= ($info['Animation'] == ANIMATION_SLIDE_FROM_RIGHT) ? 'selected' : ''?>>Slide From Right</option>
                                            <option value="<?=ANIMATION_SLIDE_FROM_LEFT?>"<?= ($info['Animation'] == ANIMATION_SLIDE_FROM_LEFT) ? 'selected' : ''?>>Slide From Left</option>
                                            <option value="<?=ANIMATION_FLIP_FROM_RIGHT?>"<?= ($info['Animation'] == ANIMATION_FLIP_FROM_RIGHT) ? 'selected' : ''?>>Flip from Right</option>
                                            <option value="<?=ANIMATION_FLIP_FROM_LEFT?>"<?= ($info['Animation'] == ANIMATION_FLIP_FROM_LEFT) ? 'selected' : ''?>>Flip from Left</option>
                                            <option value="<?=ANIMATION_FADE_IN?>"<?= ($info['Animation'] == ANIMATION_FADE_IN) ? 'selected' : ''?>>Fade In</option>
                                            <option value="<?=ANIMATION_CURL_UP?>"<?= ($info['Animation'] == ANIMATION_CURL_UP) ? 'selected' : ''?>>Curl Up</option>
                                            <option value="<?=ANIMATION_CURL_DOWN?>"<?= ($info['Animation'] == ANIMATION_CURL_DOWN) ? 'selected' : ''?>>Curl Down</option>
                                            <option value="<?=ANIMATION_RANDOM?>"<?= ($info['Animation'] == ANIMATION_RANDOM) ? 'selected' : ''?>>Random</option>
                                            <option value="<?=ANIMATION_NONE?>"<?= ($info['Animation'] == ANIMATION_NONE) ? 'selected' : ''?>>None</option>
                                        </select>
                                    </div>
                                </li>
                                <li id="extra5" class="   " style="display:block">
                                    <label class="desc" id="title21" for="Field21">
                                    </label>
                                    <div class="column">
                                        <input id="allowlocation" name="allowlocation" type="checkbox" class="field checkbox" tabindex="9"<?php if ($info['AllowLocation'] == 1) echo 'checked'; ?>/>
                                        <label class="choice" for="Field21">
                                            Allow Location Access
                                        </label>
                                    </div>
                                </li>
                                <br/>
                                <br/>
                                <li class="buttons">
                                    <input id="saveForm" class="btTxt" type="submit" value="Save Changes" />
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
            function showExtra ()
            {
              var form = document.getElementById("form2");
              var listElementStyle1 = document.getElementById("extra1").style;
              var listElementStyle2 = document.getElementById("extra2").style;
              var listElementStyle3 = document.getElementById("extra3").style;
              var listElementStyle4 = document.getElementById("extra4").style;
              var listElementStyle5 = document.getElementById("extra5").style;
              
              if(listElementStyle1.display == "none")
              {
            	  listElementStyle1.display="block";
            	  listElementStyle2.display="block";
            	  listElementStyle3.display="block";
            	  listElementStyle4.display="block";
            	  listElementStyle5.display="block";
              }
              else
              {
              	  listElementStyle1.display="none";
            	  listElementStyle2.display="none";
            	  listElementStyle3.display="none";
            	  listElementStyle4.display="none";
            	  listElementStyle5.display="none";
              }
            }
            
            function checkform ( form )
            {
              var listElementStyle = document.getElementById("errMsg").style;
              <?php if(isset($_GET['EditApp']) && $_GET['EditApp'] == 1) { ?>
              var successListElementStyle = document.getElementById("successMsg").style;
              <?php } ?>
              // see http://www.thesitewizard.com/archive/validation.shtml
              // for an explanation of this script and how to use it on your
              // own website
            
              // ** START **
              if (form.name.value != "" && form.bgcolor.value != "" && form.txtcolor.value != "") {
                return true;
              }
              
              <?php if(isset($_GET['EditApp']) && $_GET['EditApp'] == 1) { ?>
              successListElementStyle.display="none";
              <?php } ?>
              listElementStyle.display="block";
              
              // ** END **
              return false;
            }
            //-->
        </script>
