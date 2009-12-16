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

define('REPORT_ALL', '1');
define('REPORT_CUSTOM_ALL', '2');
define('REPORT_CUSTOM_ONE', '3');

define('REPORT_TYPE_IMPRESSION', 1);
define('REPORT_TYPE_CLICK', 2);
define('REPORT_TYPE_ACQUIRE', 3);

function getReportingCustomAggregate($uid, $appId, $startDate, $endDate, $isAllApps = false)
{
  if(!empty($appId)) {
    verifyOrDie($uid, $appId);
  }

  global $sdb;

  if($isAllApps) {
      $aaa = 'itemName()';
      $sdb->select(DOMAIN_APPS, $aaa, "where `uid` = '$uid'");

      $bbb = array();			      

      if(empty($aaa)) {
	return null;
      }

      foreach($aaa as $aa) {
	$aid = $aa['itemName()'];
	$ccc = array('type', 'clicks', 'impressions', 'dateTime');
	$sdb->select(DOMAIN_STATS, $ccc, "where `aid` = '$aid' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");
	if(!empty($ccc)) {
	  foreach($ccc as $cc) {
	    $bbb[] = $cc;
	  }
	}
      }
  }
  else {
    $bbb = array('type', 'clicks', 'impressions', 'dateTime');
    $sdb->select(DOMAIN_STATS, $bbb, "where `aid` = '$appId' and `type` = '9' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");
  }

  $list = array();
  foreach($bbb as $bb) {
    $dateTime = substr($bb['dateTime'], 0, 10);

    if(isset($list[$dateTime][$bb['type']])) {
      $list[$dateTime]['clicks'] += $bb['clicks'];
      $list[$dateTime]['impressions'] += $bb['impressions'];
    }
    else {
      $list[$dateTime]['clicks'] = $bb['clicks'];
      $list[$dateTime]['impressions'] = $bb['impressions'];
    }
  }

  return $list;
}

function getReportingNetworks($uid, $appId, $startDate, $endDate, $isAllApps = false)
{
  if(!empty($appId)) {
    verifyOrDie($uid, $appId);
  }

  global $sdb;

  $bbb = array('type', 'clicks', 'impressions', 'dateTime');

  if($isAllApps)
    {
      $aaa = 'itemName()';
      $sdb->select(DOMAIN_APPS, $aaa, "where `uid` = '$uid'");

      $bbb = array();			      

      if(empty($aaa)) {
	return null;
      }

      foreach($aaa as $aa) {
	$aid = $aa['itemName()'];
	$ccc = array('type', 'clicks', 'impressions', 'dateTime');
	$sdb->select(DOMAIN_STATS, $ccc, "where `aid` = '$aid' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");
	if(!empty($ccc)) {
	  foreach($ccc as $cc) {
	    $bbb[] = $cc;
	  }
	}
      }
    }
  else {
    $sdb->select(DOMAIN_STATS, $bbb, "where `aid` = '$appId' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");
  }

  $list = array();
  if(empty($bbb)) {
    return null;
  }

  foreach($bbb as $bb) {
    $dateTime = $bb['dateTime'];

    if(isset($list[$dateTime][$bb['type']])) {
      $list[$dateTime][$bb['type']]['clicks'] += $bb['clicks'];
      $list[$dateTime][$bb['type']]['impressions'] += $bb['impressions'];
    }
    else {
      $list[$dateTime][$bb['type']]['clicks'] = $bb['clicks'];
      $list[$dateTime][$bb['type']]['impressions'] = $bb['impressions'];
    }
  }
  
  return $list;
}
