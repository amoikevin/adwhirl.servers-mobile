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

chdir('../');
require_once('includes/inc_global_no_session.php');
require_once('includes/SDB.php');

$to      = 'CHANGEME';

$today      = date('Y-m-d');

$year = date('Y');
$month = date('m');
$day = date('d');

$period = 5;

global $sdb;

$points = array();
while($period > 0) {
  $current = "$year-$month-$day";
  
  $aaa = array('clicks', 'impressions', 'dateTime');
  $sdb->select(DOMAIN_STATS, $aaa, "where `dateTime` = '$current'");

  $clicks_today = 0;
  $impressions_today = 0;
  foreach($aaa as $aa) {
    $clicks_today += $aa['clicks'];
    $impressions_today += $aa['impressions'];
  }

  $points[$current] = array($clicks_today, $impressions_today);

  if($day > 1) {
    $day--;
  }
  else {
    if($month > 1) {
      $month--;
    }
    else {
      $year--;
      $month = 12;
    }
    $day = date('d', mktime(12,0,0,$month+1,0,$year));
  }

  $period--;
 }


$headers = 'From: noreply@adwhirl.com' . "\r\n" . 
    'Reply-To: noreply@adwhirl.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$output = array(); 
$output[] = '==================================';
$output[] = '=============SDB STATS============';
$output[] = '==================================';
$output[] = 'Day, Clicks, Impressions (All networks)';

foreach($points as $date => $stats) {
  $output[] = sprintf('%s, %d, %d', $date, $stats[0], $stats[1]);
}


$year = date('Y');
$month = date('m');
$day = date('d');
$points = array();
$period = 5;
while($period > 0) {
  $current = "$year-$month-$day";
  
  $aaa = array('clicks', 'impressions', 'dateTime');
  $sdb->select(DOMAIN_STATS, $aaa, "where `type` != '9' and `dateTime` = '$current'");

  $clicks_today = 0;
  $impressions_today = 0;
  foreach($aaa as $aa) {
    $clicks_today += $aa['clicks'];
    $impressions_today += $aa['impressions'];
  }

  $points[$current] = array($clicks_today, $impressions_today);

  if($day > 1) {
    $day--;
  }
  else {
    if($month > 1) {
      $month--;
    }
    else {
      $year--;
      $month = 12;
    }
    $day = date('d', mktime(12,0,0,$month+1,0,$year));
  }

  $period--;
 }

$output[] = '==================================';
$output[] = 'Day, Clicks, Impressions (External networks)';

foreach($points as $date => $stats) {
  $output[] = sprintf('%s, %d, %d', $date, $stats[0], $stats[1]);
}

$today = date("F j, Y");
$subject = "Daily AdWhirl Traffic And App Report - " . $today; 
$message = implode("\n", $output);

mail($to, $subject, $message."\n", $headers);

?>
