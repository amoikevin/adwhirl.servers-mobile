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
require_once('includes/general_functions.php');

if($_POST['email'] != null)
{
	$uid = loginUser($_POST['email'], $_POST['password'], $allowEmails);
	if($uid != null)
	{
		$_SESSION['UID'] = $uid;
		$_SESSION['Email'] = $_POST['email'];
		$_SESSION['AllowEmails'] = $allowEmails;
		
		header('Location: main');
		exit;
	}
}
header('Location: index?inv&'.$_POST['email']);
?>
