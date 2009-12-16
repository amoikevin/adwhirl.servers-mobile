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
require_once('includes/network_functions.php');
require_once('includes/network_functions_get.php');
require_once('includes/app_functions_get.php');
if (!class_exists('S3')) require_once 'includes/S3.php';
// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', '1KAHH456H7AE6EDPAVR2');
if (!defined('awsSecretKey')) define('awsSecretKey', 'koszMVm5q/mBOOU1EDJPpSiJu3TjE7eSaiYsZZ4U');

if($_POST['cfcategory'] == null)
	exit;

$imageLink = "/imagestemp/".$_POST['cfimage'].'.jpg';
$link = $_POST['cfextra'];


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
if ($s3->putObjectFile($uploadFile, $bucketName, basename($uploadFile), S3::ACL_PUBLIC_READ)) {
	
	$imageLink = AMAZON_PREFIX.$bucketName.'/'.basename($uploadFile);
	
} else {
	exit;
}

createCustomNetwork($_SESSION['UID'], $_GET['AppID'], $_POST['cfcategory'], $_POST['cfadname'], $imageLink, $link, $_POST['cfadtext'], $_POST['cftype']);

$appInfo = getAppInfo($_GET['AppID']);
if($appInfo['CustomPriority'] == 0)
{
	$customPriority = 1;
	
	$allNetworks = getAppNetworks($uid, $_GET['AppID']);
	foreach($allNetworks as $myNetwork)
	{
		if($myNetwork['AdsOn'] == 1 && ($myNetwork['NKey'] != '__CUSTOM__' || $myNetwork['Type'] == 16 ))
			$customPriority++;
	}
	// set priority to what it should be
	changeCustomPriority($uid, $_GET['AppID'], $customPriority);
}
 else {
   changeCustomPriority($uid, $_GET['AppID'], $appInfo['CustomPriority']);
 }

header('Location: main?Success=1&AppID=' . $_GET['AppID']);
?>
