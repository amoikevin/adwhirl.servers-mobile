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
require_once('includes/app_functions.php');

$name = isset($_POST['name']) ? $_POST['name'] : null;
$category = isset($_POST['category']) ? $_POST['category'] : null;
$storeurl = isset($_POST['storeurl']) ? $_POST['storeurl'] : null;
$description = isset($_POST['description']) ? $_POST['description'] : null;
$bgcolor = isset($_POST['bgcolor']) ? $_POST['bgcolor'] : null;
$txtcolor = isset($_POST['txtcolor']) ? $_POST['txtcolor'] : null;
$refresh = isset($_POST['refresh']) ? $_POST['refresh'] : null;
$animation = isset($_POST['animation']) ? $_POST['animation'] : null;

if(isset($_POST['allowlocation'])) {
  $allowLocation = ($_POST['allowlocation'] ? 1 : 0);
 }
 else {
   $allowLocation = 0;
 }

$appId = createApp($uid, $name, $category, $storeurl, $description, $bgcolor, $txtcolor, $refresh, $animation, $allowLocation);

header('Location: main?AppID=' . $appId);
?>
