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

require_once("SDB.php");
require_once("general_functions.php");
require_once("network_functions_get.php");

function createNetwork($uid, $network, $apikey, $appId, $on = 0)
{		
  verifyOrDie($uid, $appId);

  global $sdb;

  $networks = getAllAppNetworks($uid, $appId);
  if(empty($networks)) {
    $priority = 1;
  }
  else {
    $priority = count($networks) + 1;
  }

  $uuid = uuid();
  $aa = array('aid' => $appId,
	      'type' => $network,
	      'key' => $apikey,
	      'weight' => 0,
	      'priority' => $priority,
	      'adsOn' => $on);
  $sdb->put(DOMAIN_NETWORKS, $uuid, $aa, true);
	
  // instead of setting all the memcache to new values, just delete it (and appinfo for custom priorities)
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
	
  return $uuid;
}

function deleteNetwork($uid, $network, $appId, $nid)
{		
  verifyOrDie($uid, $appId, $nid);

  global $sdb;

  $sdb->delete(DOMAIN_NETWORKS, $nid);
	
  // instead of setting all the memcache to new values, just delete it (and appinfo for custom priorities)
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
}

//Called indirectly
function deleteNetworksByAID($aid) {
  global $sdb;

  $appNetworks = getAppNetworks(null, $aid);
  if($appNetworks != null && !empty($appNetworks)) {
    foreach($appNetworks as $network) {
      deleteNetwork(null, null, $aid, $network['NID']);
    }
  }

  $customNetworks = getAppNetworksCustom(null, $aid);
  if($customNetworks != null && !empty($customNetworks)) {
    foreach($customNetworks as $network) {
      deleteNetwork(null, null, $aid, $network['NID']);
    }
  }
}

function createCustomNetwork($uid, $appId, $type, $adname, $imageLink, $extra, $text, $linkType)
{
  verifyOrDie($uid, $appId);

  global $sdb;

  if($linkType == AD_WEBSITE && !(stripos($extra, 'http') === 0))
    {
      $extra = 'http://'.$extra;
    }
	
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
		
  $uuid = createNetwork($uid, '9', '__CUSTOM__', $appId, 1);

  $aa = array('type' => $type,
	      'imageLink' => $imageLink,
	      'link' => $extra,
	      'description' => $text,
	      'name' => $adname,
	      'linkType' => $linkType);
  $sdb->put(DOMAIN_CUSTOMS, $uuid, $aa, true);
	
  return $uuid;
}

function changeCustomNetwork($uid, $appId, $type, $adname, $imageLink, $extra, $text, $linkType, $nid)
{
  verifyOrDie($uid, $appId, $nid);

 global $sdb;

  if($linkType == AD_WEBSITE && !(stripos($extra, 'http') === 0))
    {
      $extra = 'http://'.$extra;
    }
	
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
	
  if($imageLink == null) {
    $aa = array('type' => $type,
		'link' => $extra,
		'description' => $text,
		'name' => $adname,
		'linkType' => $linkType);
    $sdb->put(DOMAIN_CUSTOMS, $nid, $aa, true);
  }
  else {
    $aa = array('type' => $type,
		'imageLink' => $imageLink,
		'link' => $extra,
		'description' => $text,
		'name' => $adname,
		'linkType' => $linkType);
    $sdb->put(DOMAIN_CUSTOMS, $nid, $aa, true);
  }
}

function deleteCustomNetwork($uid, $appId, $nid)
{
  verifyOrDie($uid, $appId, $nid);

  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
	
  $sdb->delete(DOMAIN_NETWORKS, $nid);
  $sdb->delete(DOMAIN_CUSTOMS, $nid);
}

function imageDoesExist($imageLink)
{		
  global $sdb;

  $aaa = 'itemName()';
  $sdb->select(DOMAIN_CUSTOMS, $aaa, "where `imageLink` = '$imageLink'");
  
  if(is_array($aaa)) {
    return true;
  }
  else {
    return false;
  }
}
function changeNetwork($uid, $appId, $nids, $apikeys, $percents, $types, $prioritiesHash, $hasKeys)
{
  verifyOrDie($uid, $appId, $nids);

  global $sdb;

  for($i=0; $i<count($nids); $i++)
    {
      $nid = $nids[$i];
      $apikey = $apikeys[$i];

      if(isset($apikeys[$i])) {
	$apikey = str_replace(' ','',$apikeys[$i]);
      }
      $percent = $percents[$i];
      $type = $types[$i];
      if(isset($prioritiesHash[$i])) {
	$priority = $prioritiesHash[$i];
      }
		
      //We only want to update things that are alive
      if(!empty($apikey) && !empty($type)) {
	if((empty($nid) || $nid == "NO-NID")) /* && ($type != 9 && $type != 16)) */
	  {
	    $nid = createNetwork($uid, $type, $apikey, $appId, 1);
	  }

	if($nid != "NO-NID") {
	  $aa = array('weight' => $percent,
		      'priority' => $priority,
		      'key' => $apikey);
	  $sdb->put(DOMAIN_NETWORKS, $nid, $aa, true);
	}
      }
      else {
	//If it was on, and was either a generic notification or now has no key...
	if($hasKeys[$i]) {
	  deleteNetwork($uid, $type, $appId, $nid);
	}
      }
    }		

  if(isset($prioritiesHash['CustomPriority']) && $prioritiesHash['CustomPriority'] != null)
    {
      $customPriority = $prioritiesHash['CustomPriority'];
	  
      $aa = array('priority' => $priority);
      $sdb->put(DOMAIN_NETWORKS, $nid, $aa, true);
    }
	
  // instead of setting all the memcache to new values, just delete it (and appinfo for custom priorities)
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
}

function changeNetworkPriorities($uid, $appId, $nids, $priorities)
{
  verifyOrDie($uid, $appId, $nids);

  global $sdb;

  $customAdjust = 0;
  $needsCustom = true;

  for($i=0; $i<count($nids); $i++)
    {
      $nid = $nids[$i];
      $priority = $priorities[$i] - $customAdjust;

      $bbb = 'type';
      $sdb->select(DOMAIN_NETWORKS, $bbb, "where itemName() = '$nid' limit 1");
      $type = $bbb[0]['type'];
      if($type == '9') {
	if($needsCustom) {
	  changeCustomPriority($uid, $appId, $priority);
	  $needsCustom = false;
	}
	else {
	  $customAdjust++;
	  continue;
	}
      }
		
      $aa = array('priority' => $priority);
      $sdb->put(DOMAIN_NETWORKS, $nid, $aa, true);
    }
	
  // instead of setting all the memcache to new values, just delete it (and appinfo for custom priorities)
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_CUSTOM . $appId);
  $appNetworks = $memcache->delete(MEMCACHE_APPNETWORKS_ALL . $appId);
  $memcache->close();
}

function changeCustomPriority($uid, $appId, $priority)
{
  verifyOrDie($uid, $appId);

  global $sdb;

  $aaa = 'itemName()';
  $sdb->select(DOMAIN_NETWORKS, $aaa, "where `aid` = '$appId' and `type` = '9'");
  
  foreach($aaa as $aa) {
    $bb = array('priority' => $priority);
    $sdb->put(DOMAIN_NETWORKS, $aa['itemName()'], $bb, true);
  }
	
  // instead of setting all the memcache to new values, just delete it (and appinfo for custom priorities)
  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appNetworks = $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $memcache->close();
}
