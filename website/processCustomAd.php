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
<title>AdWhirl: Confirm Custom Ad</title>
<!-- CSS -->
<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<link rel="stylesheet" href="css/style_fade.css" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="scripts/wufoo.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/contentfader.js"></script>
<?php
require_once('includes/inc_global.php');
require_once('includes/app_functions_get.php');
require_once('includes/inc_login.php');
require('includes/inc_head.html');

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;

// branch on the basis of 'calculate' value 
switch ($_POST['saveForm']) {
      case 'Add Website Ad':
            $submitType = AD_WEBSITE;
            break;
      case 'Add App Store Ad':
            $submitType = AD_APP;
            break;
      case 'Add Call Ad':
            $submitType = AD_CALL;
            break;
      case 'Add Video Ad':
            $submitType = AD_VIDEO;
            break;
      case 'Add Audio Ad':
            $submitType = AD_AUDIO;
            break;
      case 'Add iTunes Ad':
            $submitType = AD_ITUNES;
            break;
      case 'Add Maps Ad':
            $submitType = AD_MAP;
            break;
      default:
      		exit;
}

$category = $_POST['category'.$submitType];
$adName = $_POST['name'.$submitType];
$adText = $_POST['adtext'.$submitType];
$extra = $_POST['extra'.$submitType];

if($category == CUSTOM_ICON)
{
	$newwidth = 38;
	$newheight = 38;
}
elseif ($category == CUSTOM_BANNER)
{
	$newwidth = 320;
	$newheight = 50;
}
else 
	exit;

// This is the temporary file created by PHP 
$uploadedfile = $_FILES['uploadimage'.$submitType]['tmp_name'];

// Create an Image from it so we can do the resize
if($_FILES["uploadimage".$submitType]["type"]=="image/jpg" || $_FILES["uploadimage".$submitType]["type"]=="image/jpeg")
{
	$src = imagecreatefromjpeg($uploadedfile);
	$imgType = 'jpg';
}
elseif($_FILES["uploadimage".$submitType]["type"]=="image/gif")
{
	$src = imagecreatefromgif($uploadedfile);
	$imgType = 'gif';
}
elseif($_FILES["uploadimage".$submitType]["type"]=="image/png")
{
	$src = imagecreatefrompng($uploadedfile);
	$imgType = 'png';
}
else 
	exit;

// Capture the original size of the uploaded image
list($width,$height)=getimagesize($uploadedfile);



/*if($width > $maxwidth)
{
	$newwidth=$maxwidth;
	$newheight=($height/$width)*$newwidth;
}
else 
{
	$newwidth = $width;
	$newheight = $height;
}

if($newheight > $maxheight)
{
	$newheight=$maxheight;
	$newwidth=($width/$height)*$newheight;
}*/

$tmp=imagecreatetruecolor($newwidth,$newheight);

// this line actually does the image resizing, copying from the original
// image into the $tmp image
imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

$randToken = md5(uniqid());
// now write the resized image to disk. I have assumed that you want the
// resized, uploaded image file to reside in the ./images subdirectory.
$filename = "imagestemp/".$randToken.'.jpg';
imagejpeg($tmp,$filename,100);

imagedestroy($src);
imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request has completed.

//header('Location: main?Success=1&AppID=' . $_GET['AppID']);
?>

<div id="appForm">
	<form name="selectForm" action="main" method="GET">
		<a href="main?AppID=<?=$appId?>">Go Back</a> <font color="#cccccc">|</font> 
		<?php $info = getAppInfo($appId); echo $info['Name']; ?>
	</form>
</div>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
		
		<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">
			
			<form id="form2" name="form2" class="wufoo " autocomplete="off"
				enctype="multipart/form-data" method="post" action="confirmCustomAd?AppID=<?=$appId?>">
			
			<input type="hidden" name="cfcategory" value="<?=$category?>">
			<input type="hidden" name="cfadname" value="<?=$adName?>">
			<input type="hidden" name="cfadtext" value="<?=$adText?>">
			<input type="hidden" name="cfextra" value="<?=$extra?>">
			<input type="hidden" name="cfimage" value="<?=$randToken?>">
			<input type="hidden" name="cftype" value="<?=$submitType?>">
			
			<div class="info">
				<h2>Please Confirm This Ad</h2>
				<div><strong>You can always adjust background and text colors from the "Edit App Info" tab.</strong> Also note that the example provided is just an approximation and your ad will actually appear better on the iPhone than below.</div>
			</div>
			
			<div class="adtop"></div>
			<?php if($category==CUSTOM_BANNER) { ?>
			<div class="admid"><span><img src="<?=$filename?>"/></span></div>
			<?php } else { ?>
			  <div id="preview_content">
		      <div id="preview_ad" style="background-color:#<?=$info['BGColor']?>;">
		        <img style="background: transparent url(<?=$filename?>) no-repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" src="<?=IMAGE_DIRECTORY?>ad_frame.gif" class="logo" id="logo_image"/>
		        <table cellspacing="0" cellpadding="0"><tbody><tr valign="middle"><td><p style="color:#<?=$info['TxtColor']?>;"><?=$adText?></p></td></tr></tbody></table>
		        <!--<img src="http://www.admob.com/img/create_iphone_ad/action_download.png" class="action_img" id="action_image"/>-->
		      </div>
			  </div>
		   <?php } ?>
			<div class="adbot"></div>
			
			<li class="buttons">
				<input name="saveForm" id="saveForm" class="btTxtBold" type="submit" value="Confirm" /> <strong>OR</strong> <a href="main?AppID=<?=$appId?>">Cancel</a>
			</li>
			
			
			</form>
			
			</div><!--container-->
			<img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
		</div>
		<!-- wufoo shell-->
		
	</div>
</div>
<!--Main Lower Panel Close-->
  


<?php
require('includes/inc_tail.html');
?>
