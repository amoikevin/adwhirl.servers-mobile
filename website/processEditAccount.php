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
require_once('includes/general_functions.php');

$email = isset($_POST['email']) ? $_POST['email'] : null;
$oldpass = isset($_POST['oldpass']) ? $_POST['oldpass'] : null;
$newpass = isset($_POST['newpass']) ? $_POST['newpass'] : null;

if($email == null || $oldpass == null || $newpass == null) {
  exit;
}

$allowEmails = isset($_POST['allowemails']) ? 0 : 1;

$numAffected = changeUser($uid, $email, $oldpass, $newpass, $allowEmails);

if($numAffected != 0)
{
	$_SESSION['UID'] = $uid;
	$_SESSION['Email'] = $email;
	$_SESSION['AllowEmails'] = $allowEmails;
}

header('Location: account?EditAccount='.$numAffected);
