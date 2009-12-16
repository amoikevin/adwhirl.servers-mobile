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

//AWS
if (!class_exists('S3')) require_once 'includes/S3.php';

$nid = isset($_POST['nid']) ? $_POST['nid'] : null;

if($nid == null) {
  exit;
 }

$appId = isset($_POST['appid']) ? $_POST['appid'] : null;

$customAds = getAppNetworksCustom($uid, $appId);

$imageLink = $customAds[$nid]['ImageLink'];

deleteCustomNetwork($uid, $appId, $nid);

if(!imageDoesExist($imageLink))
{
	// STILL HAVE TO DELETE IMAGE FROM AMAZON S3
	$bucketName = AMAZON_BUCKET_CUSTOM; // S3 bucket
	// Check for CURL
	if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	{
		exit;
	}
	// Instantiate the class
	$s3 = new S3(awsAccessKey, awsSecretKey);
	$s3->deleteObject($bucketName, basename($imageLink));
	// FINISH DELETING THE IMAGE FROM AMAZON S3
}

header('Location: main?DeleteCustom=1&AppID='.$appId);
