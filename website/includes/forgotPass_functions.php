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

require_once("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Util.php");
require_once("general_functions.php");

function setupPasswordRecovery($email)
{
  global $sdb;

  if($email == null)
    return false;
	
  $aaa = 'itemName()';
  if($sdb->select(DOMAIN_USERS, $aaa, "where `email` = '$email'")) {
    if(empty($aaa)) {
      return false;
    }

    $uid = $aaa[0]['itemName()'];
		
    $uuid = uuid();

    $aa = array('uid' => $uid,
		'createdAt' => Amazon_SimpleDB_Util::encodeDate(microtime()));
    $sdb->put(DOMAIN_USERS_FORGOT, $uuid, $aa, true);
  }
  else {
    return false;
  }
	
  $activationLink = 'http://'.$_SERVER['HTTP_HOST'].'/passwordReset?sec='.$uuid;

  $to      = $email;
  $subject = 'AdWhirl Password Reset';
  $message = 'Hello AdWhirl User,
	
	We received a request to reset your password. Click on the link below to set up a new password for your account.
	
	'.$activationLink.'
	
	If you did not request to reset your password, ignore this email - the link will expire on its own.
	
	Best,
	AdWhirl Team
	';
  $headers = 'From: noreply@adwhirl.com';
  mail($to, $subject, $message, $headers);
	
  return true;
}

function processPasswordRecovery($secretid, $password)
{
  global $sdb;

  if($secretid == null)
    return false;

  $aaa = 'uid';
  if($sdb->select(DOMAIN_USERS_FORGOT, $aaa, "where itemName() = '$secretid'")) {
    $uid = $aaa[0]['uid'];
    $password = md5($password.PASSWORD_SALT);

    $aa = array('password' => $password);
    $sdb->put(DOMAIN_USERS, $uid, $aa, true);

    $sdb->delete(DOMAIN_USERS_FORGOT, $secretid);
  }
  else {
    return false;
  }
  
  return true;
}
