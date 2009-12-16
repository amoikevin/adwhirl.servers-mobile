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

$appid = isset($_POST['appid']) ? $_POST['appid'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$category = isset($_POST['category']) ? $_POST['category'] : null;
$storeurl = isset($_POST['storeurl']) ? $_POST['storeurl'] : null;
$description = isset($_POST['description']) ? $_POST['description'] : null;
$bgcolor = isset($_POST['bgcolor']) ? $_POST['bgcolor'] : null;
$txtcolor = isset($_POST['txtcolor']) ? $_POST['txtcolor'] : null;
$refresh = isset($_POST['refresh']) ? $_POST['refresh'] : null;
$animation = isset($_POST['animation']) ? $_POST['animation'] : null;
$allowLocation = isset($_POST['allowlocation']) ? 1 : 0;

changeApp($uid, $appid, $name, $category, $storeurl, $description, $bgcolor, $txtcolor, $refresh, $animation, $allowLocation);

header('Location: editApplication?EditApp=1&AppID=' . $appid);
