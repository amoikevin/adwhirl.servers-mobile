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

require_once("general_functions.php");

function getAppNetworks($uid, $appId, $wantHash=false, $verify = true)
{
  if($verify)
    verifyOrDie($uid, $appId);

  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->get(MEMCACHE_APPNETWORKS . $appId);
	
  if($appNetworks == null)
    {
      $aaa = array('type', 'key', 'weight', 'priority', 'adsOn');

      $sdb->select(DOMAIN_NETWORKS, $aaa, "where `aid` = '$appId' and `type` != '9'");

      foreach($aaa as $aa) {
	$info = array('NID' => $aa['itemName()'],
		      'Type' => $aa['type'],
		      'NKey' => $aa['key'],
		      'Percent' => $aa['weight'],
		      'Priority' => $aa['priority'],
		      'AdsOn' => $aa['adsOn']);
	$appNetworks[] = $info;
      }

      $memcache->set(MEMCACHE_APPNETWORKS . $appId, $appNetworks, 0, 86400); // 60 * 60 * 24 = 86400 = 1 day in seconds
    }
  $memcache->close();
	
  if($wantHash)
    {
      $list = array();
      if($appNetworks != null) {
	foreach ($appNetworks as $oneNetwork)
	  {
	    $list[$oneNetwork['Type']] = $oneNetwork;
	  }
      }
    }
  else 
    $list = $appNetworks;
	
  return $list;
}

//This actually gets all the external networks and only one custom network
function getAllAppNetworks($uid, $appId, $wantHash=false)
{
  verifyOrDie($uid, $appId);

  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->get(MEMCACHE_APPNETWORKS_ALL . $appId);

  $needCustom = true;

  if($appNetworks == null)
    {
      $aaa = array('type', 'key', 'weight', 'priority', 'adsOn');

      $sdb->select(DOMAIN_NETWORKS, $aaa, "where `aid` = '$appId'");

      foreach($aaa as $aa) {
	$info = array('NID' => $aa['itemName()'],
		      'Type' => $aa['type'],
		      'NKey' => $aa['key'],
		      'Percent' => $aa['weight'],
		      'Priority' => $aa['priority'],
		      'AdsOn' => $aa['adsOn']);

	if($type == '9') {
	  if($needCustom) {
	    $needCustom = false;
	  }
	  else {
	    continue;
	  }
	}

	$appNetworks[] = $info;
      }
    }
	
  if($wantHash)
    {
      $list = array();
      if($appNetworks != null) {
	foreach ($appNetworks as $oneNetwork)
	  {
	    $list[$oneNetwork['Type']] = $oneNetwork;
	  }
      }
    }
  else 
    $list = $appNetworks;

  $memcache->close();

  return $list;
}

function getAppNetworksCustom($uid, $appId)
{
  verifyOrDie($uid, $appId);

  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->get(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  
  if($appNetworks == null)
    {
      $aaa = array('type', 'key', 'weight', 'priority', 'adsOn');

      $sdb->select(DOMAIN_NETWORKS, $aaa, "where `aid` = '$appId' and `type` = '9'");
  
      $appNetworks = array();
      foreach($aaa as $aa) {
	$bbb = array('type', 'imageLink', 'link', 'description', 'name', 'linkType', 'linkShare');

	$nid = $aa['itemName()'];
	$sdb->select(DOMAIN_CUSTOMS, $bbb, "where itemName() = '$nid' limit 1");

	foreach($bbb as $bb) {
	  $info = array('NID' => $aa['itemName()'],
			'UID' => $aa['UID'],
			'Type' => $aa['type'],
			'CustomType' => $bb['type'],
			'ImageLink' => $bb['imageLink'],
			'Link' => $bb['link'],
			'Description' => isset($bb['description']) ? $bb['description']: '',
			'Name' => $bb['name'],
			'Percent' => $aa['weight'],
			'Priority' => $aa['priority'],
			'LinkType' => $bb['linkType'],
			'LaunchType' => ($bb['linkType'] == AD_WEBSITE ? LAUNCH_TYPE_CANVAS : LAUNCH_TYPE_SAFARI)); // launch type
	  $appNetworks[$nid] = $info;
	}
      }
      $memcache->set(MEMCACHE_APPNETWORKS_CUSTOM . $appId, $appNetworks, 0, 86400); // 60 * 60 * 24 = 86400 = 1 day in seconds
    }
  $memcache->close();
  
  return $appNetworks;
}

function getUserNetworksCustom($uid, $includeDuplicates = false)
{
  global $sdb;

  $appNetworks = array();
  $usedNames = array();

  $aaa = 'itemName()';
  $sdb->select(DOMAIN_APPS, $aaa, "where `uid` = '$uid'");

  foreach($aaa as $aa) {
    $aid = $aa['itemName()'];
    $bbb = array('type', 'weight');
    $sdb->select(DOMAIN_NETWORKS, $bbb, "where `aid` = '$aid' and `type` = '9'");

    foreach($bbb as $bb) {
      $nid = $bb['itemName()'];
      $ccc = array('type', 'imageLink', 'link', 'description', 'name', 'linkType');
      $sdb->select(DOMAIN_CUSTOMS, $ccc, "where itemName() = '$nid' limit 1");

      if(empty($ccc)) {
	return null;
      }

      $info = array('NID' => $nid,
		    'Type' => 9,
		    'CustomType' => isset($ccc[0]['type']) ? $ccc[0]['type'] : 0,
		    'ImageLink' => isset($ccc[0]['imageLink']) ? $ccc[0]['imageLink'] : '',
		    'Link' => isset($ccc[0]['link']) ? $ccc[0]['link'] : '',
		    'Description' => isset($ccc[0]['description']) ? $ccc[0]['description'] : '',
		    'Name' => isset($ccc[0]['name']) ? $ccc[0]['name'] : '',
		    'Percent' => isset($bb['weight']) ? $bb['weight'] : 0,
		    'LinkType' => isset($ccc[0]['linkType']) ? $ccc[0]['linkType'] : 0,
		    'LaunchType' => (isset($ccc[0]['linkType']) && $ccc[0]['linkType'] == AD_WEBSITE ? LAUNCH_TYPE_CANVAS : LAUNCH_TYPE_SAFARI)); // launch type
      
      if($includeDuplicates || !isset($usedNames[$ccc[0]['name']]))
	{
	  $appNetworks[$nid] = $info;
	  $usedNames[$ccc[0]['name']] = 1;
	}
    }
  }
  
  return $appNetworks;
}
