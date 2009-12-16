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
require_once('includes/inc_global.php');
require_once('includes/inc_login.php');
require_once('includes/network_functions_get.php');
require_once('includes/network_functions.php');

// AWS
if (!class_exists('S3')) require_once 'includes/S3.php';

if(!isset($_POST['category']) || $_POST['category'] == null)
	exit;
	
// so we can delete on Amazon S3
$nid = $_POST['nid'];
$customAds = getAppNetworksCustom($_SESSION['UID'], $_GET['AppID']);
$oldImageLink = $customAds[$nid]['ImageLink'];
// end info needed to delete from Amazon S3
	
$link = $_POST['extra'];
	
// IMAGE CODE
if($_POST['category'] == CUSTOM_ICON)
{
	$newwidth = 38;
	$newheight = 38;
}
elseif ($_POST['category'] == CUSTOM_BANNER)
{
	$newwidth = 320;
	$newheight = 50;
}
else {
		exit;
	}

// This is the temporary file created by PHP 
$uploadedfile = $_FILES['uploadimage']['tmp_name'];

if($uploadedfile != null)
{
	// Create an Image from it so we can do the resize
	if($_FILES["uploadimage"]["type"]=="image/jpg" || $_FILES["uploadimage"]["type"]=="image/jpeg")
	{
		$src = imagecreatefromjpeg($uploadedfile);
		$imgType = 'jpg';
	}
	elseif($_FILES["uploadimage"]["type"]=="image/gif")
	{
		$src = imagecreatefromgif($uploadedfile);
		$imgType = 'gif';
	}
	elseif($_FILES["uploadimage"]["type"]=="image/png")
	{
		$src = imagecreatefrompng($uploadedfile);
		$imgType = 'png';
	}
	else 
		exit;
	
	// Capture the original size of the uploaded image
	list($width,$height)=getimagesize($uploadedfile);
	
	
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
	imagedestroy($tmp);
	// END IMAGE CODE
		
	
	$imageLink = "/imagestemp/".$randToken.'.jpg';
	
	
	// S3 MAGIC
	$uploadFile = dirname(__FILE__).$imageLink; // File to upload
	$bucketName = AMAZON_BUCKET_CUSTOM; // S3 bucket
	// Check if our upload file exists
	if (!file_exists($uploadFile) || !is_file($uploadFile))
	{
		exit;
	}
	
	// Check for CURL
	if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	{
		exit;
	}
	
	// Instantiate the class
	$s3 = new S3(awsAccessKey, awsSecretKey);
	
	// Put our file (also with public read access)
	if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
		
		$s3->deleteObject($bucketName, basename($oldImageLink)); // delete old image (no longer used)
		$imageLink = AMAZON_PREFIX.$bucketName.'/'.basename($uploadFile);
		
	} else {
		exit;
	}
}
else 
	$imageLink = null;

changeCustomNetwork($_SESSION['UID'], $_GET['AppID'], $_POST['category'], $_POST['name'], $imageLink, $link, $_POST['adtext'], $_POST['cftype'], $_POST['nid']);

header('Location: editCustom?EditCustom=1&NID='.$_POST['nid'].'&AppID=' . $_GET['AppID']);
?>
