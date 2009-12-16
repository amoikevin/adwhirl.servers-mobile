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

function getApps($uid)
{		
  global $sdb;

  $aaa = array('name', 'adsOn');
  $sdb->select(DOMAIN_APPS, $aaa, "where `uid` = '$uid'");

  $list = array();
  foreach ($aaa as $aa) {
    $bbb = array('priority');
    $aid = $aa['itemName()'];
    $sdb->select(DOMAIN_CUSTOMS, $bbb, "where `aid` = '$aid' and `type` = '9' limit 1");
	
    if(!empty($bbb)) {
      $customPriority = $bbb[0]['priority'];
    }
    else {
      $customPriority = 0;
    }

    $info = array('AppID' => $aa['itemName()'],
		  'Name' => $aa['name'],
		  'AdsOn' => $aa['adsOn'],
		  'CustomPriority' => $customPriority);
	
    $list[] = $info;
  }
 
  return $list;
}

function getAppInfo($appId)
{
  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $appInfo = $memcache->get(MEMCACHE_APP_INFO . $appId);

  if($appInfo == null)
    {
      $aaa = array('name', 'cycleTime', 'transition', 'bgColor', 'fgColor', 'locationOn', 'adsOn', 'uid');
      $sdb->select(DOMAIN_APPS, $aaa, "where itemName() = '$appId'");

      foreach ($aaa as $aa) {
	$bbb = array('priority');
	$sdb->select(DOMAIN_NETWORKS, $bbb, "where `aid` = '$appId' and `type` = '9' limit 1");
	
	if(!empty($bbb)) {
	  $customPriority = $bbb[0]['priority'];
	}
	else {
	  $customPriority = 0;
	}

	$appInfo = array('Name'=>$aa['name'],
			 'RefreshInterval'=>$aa['cycleTime'],
			 'Animation'=>$aa['transition'],
			 'BGColor'=>$aa['bgColor'],
			 'TxtColor'=>$aa['fgColor'],
			 'AllowLocation'=>$aa['locationOn'],
			 'AdsOn' => $aa['adsOn'],
			 'UID' => $aa['uid'],
			 'CustomPriority' => $customPriority);

	$memcache->set(MEMCACHE_APP_INFO . $appId, $appInfo, 0, 86400); // 60 * 60 * 24 = 86400 = 1 day in seconds
      }
    }
  $memcache->close();
	
  return $appInfo;
}

function getAppExtendedInfo($appId)
{
  global $sdb;

  $aaa = array('storeUrl', 'description');
  
  if($sdb->select(DOMAIN_APPS, $aaa, "where itemName() = '$appId'")) {
    if(!empty($aaa)) {
      $storeUrl = isset($aaa[0]['storeUrl']) ? $aaa[0]['storeUrl'] : null;
      $description = isset($aaa[0]['description']) ? $aaa[0]['description'] : null;
      return array('Url'=> $storeUrl, 'Description'=> $description);
    }
  }

  return null;
}

function getAppCategory($appId)
{
  global $sdb;
 
  $aaa = array('category');
  if($sdb->select(DOMAIN_APPS, $aaa, "where itemName() = '$appId'")) {
    if(!empty($aaa)) {
      return $aaa[0]['category'];
    }
  }
 
  return null;
}
