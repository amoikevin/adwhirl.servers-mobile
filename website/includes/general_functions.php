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

require_once ("inc_global_no_session.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Util.php");
require_once ("SDB.php");

function sql_error($error)
{
  //do nothing
}

function doesUserExist($email, &$uid)
{
  global $sdb;

  if($email == null)
    return false;
		
  $aaa = 'itemName()';
  if(!$sdb->select(DOMAIN_USERS, $aaa, "where itemName() = '$uid' and `email` = '$email' limit 1")) {
    sql_error();
  }
	
  if($aaa[0]['uid'])
    {
      $uid = $aaa[0]['uid'];
      return true;
    }
	
  return false;
}

function mydate($format, $date)
{
  $year = substr("$date", 0, 4);
  $month = substr("$date", 5, 2);
  $day = substr("$date", 8, 2);
  $hour = substr("$date", 11, 2);
  $min = substr("$date", 14, 2);
  $sec = substr("$date", 17, 2);
  // had to adjust hour by one
  return date($format, mktime($hour-8, $min, $sec, $month, $day, $year));
}

function loginUser($email, $password, &$allowEmails)
{		
  global $sdb;
  $aaa = array('allowEmail');
	
  $password = md5($password.PASSWORD_SALT);
  $result = $sdb->select(DOMAIN_USERS, $aaa, "where `email` = '$email' and `password` = '$password' limit 1");

  if (!$result) {
    sql_error(null);
    return null;
  }

  if(!empty($aaa)) {
    $allowEmails = $aaa[0]['allowEmail'];

    return $aaa[0]['itemName()'];
  }

  return null;
}

function registerUser($email, $password, $allowEmails = 0)
{
  global $sdb;

  $uuid = null;
	
  $password = md5($password.PASSWORD_SALT);

  $aaa = 'itemName()';

  if ($sdb->select(DOMAIN_USERS, $aaa, "where `email` = '$email' limit 1")) {
    if(!empty($aaa)) {
      return null;
    }

    $uuid = uuid();

    $aa = array('email' => $email,
		'password' => $password,
		'createdAt' => Amazon_SimpleDB_Util::encodeDate(microtime()),
		'allowEmail' => $allowEmails);


    $sdb->put(DOMAIN_USERS_UNVERIFIED, $uuid, $aa, true);
  }
  
  return $uuid;
}

function confirmUser($uid, &$email, &$allowEmails)
{		
  global $sdb;
  $aaa = array('email', 'password', 'allowEmail');
  $sdb->select(DOMAIN_USERS_UNVERIFIED, $aaa, "where itemName() = '$uid' limit 1");
  
  $aa = array('email' => $aaa[0]['email'],
	      'password' => $aaa[0]['password'],
	      'allowEmail' => $aaa[0]['allowEmail']);


  if($sdb->put(DOMAIN_USERS, $uid, $aa, true)) {
    $sdb->delete(DOMAIN_USERS_UNVERIFIED, $uid);
  }
  else {
    return false;
  }

  $email = $aa['email'];
  $allowEmails = $aa['allowEmail'];

  return true;
}

function changeUser($uid, $email, $oldpassword, $newpassword, $allowEmails = 0)
{
  global $sdb;
  if(empty($oldpassword)) {
    $aa = array('email' => $email,
		'allowEmail' => $allowEmails);

    $sdb->put(DOMAIN_USERS, $uid, $aa, true);
  }
  else {
    $oldpassword = md5($oldpassword.PASSWORD_SALT);
    $newpassword = md5($newpassword.PASSWORD_SALT);
    
    $aaa = 'itemName()';

    if($sdb->select(DOMAIN_USERS, $aaa, "where itemName()='$uid' and `password` = '$oldpassword' limit 1")) {
      if(!empty($aaa)) {
	if($aaa[0]['itemName()'] != $uid) {
	  return false;
	}
      }

      $aa = array('email' => $email,
		  'allowEmail' => $allowEmails,
		  'password' => $newpassword);
      $sdb->put(DOMAIN_USERS, $uid, $aa, true);
    }
  }
	
  return true;
}

function getPassword($user)
{

  global $sdb;
  if($user == null)
    return null;

  $aaa = array('password');
  if($sdb->select(DOMAIN_USERS, $aaa, "where itemName() = '$user' limit 1")) {
    return $aaa[0]['password'];
  }

  return null;
}

function uuid()
{
  return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
		  mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		  mt_rand( 0, 0x0fff ) | 0x4000,
		  mt_rand( 0, 0x3fff ) | 0x8000,
		  mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
}

function verifyOrDie() {
  if(empty($uid)) 
    return;

  global $sdb;

  $num_args = func_num_args();
  $args = func_get_args();
  $uid = $args[0];

  //This is because the charts swf doesn't have any session data.
  if($uid == SECRET_PASS) {
    return;
  }

  $aid = $args[1];

  $aaa = 'itemName()';
  $sdb->select(DOMAIN_APPS, $aaa, "where itemName() = '$aid' and `uid` = '$uid' limit 1");
  if(empty($aaa)) {
    die("Unable to verify uid($uid)<->aid($aid)");
  }

  if($num_args == 2) {
    return;
  }

  $nid = $args[2];
  $aaa = 'itemName()';
  if(is_array($nid)) {
    $sdb->select(DOMAIN_NETWORKS, $aaa, "where `aid` = '$aid'");

    $nids = array();
    foreach($aaa as $aa) {
      array_push($nids, $aa['itemName()']);
    }

    foreach($nid as $item) {
      if($item == "NO-NID" || $item == "-1") {
	continue;
      }

      if(!in_array($item, $nids)) {
	die("Unable to verify aid($aid)<->nid($item)");
      }
    }
  }
  else {
    if($nid == "NO-NID" || $nid == "-1") {
      return;
    }

    $sdb->select(DOMAIN_NETWORKS, $aaa, "where itemName() = '$nid' and `aid` = '$aid' limit 1");

    if(empty($aaa)) {
      die("Unable to verify aid($aid)<->nid($nid)");
    }
  }
}
