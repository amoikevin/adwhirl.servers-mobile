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
require_once('includes/network_functions_get.php');
require_once('includes/network_functions.php');
require_once('includes/inc_login.php');

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;
if($appId == null) {
  exit;
}

$nid = isset($_GET['NID']) ? $_GET['NID'] : null;
$adsOn = isset($_GET['On']) ? $_GET['On'] : null;

turnNetworkOnOff($uid, $appId, $nid, $adsOn);

header('Location: main?AppID='.$appId);

function turnNetworkOnOff($uid, $appId, $nid, $adsOn)
{
  global $sdb;

  $aa = array('priority' => '99', 'adsOn' => $adsOn);
  $sdb->put(DOMAIN_NETWORKS, $nid, $aa, true);
	
  // Now fix priorities
  $priorities = array();
  $finalPriorities = array();
  $finalNids = array();
  $networks = getAllAppNetworks($uid, $appId);

  foreach($networks as $oneNetwork)
    {
      if($oneNetwork['NID'] == $nid)
	$oneNetwork['AdsOn'] = $adsOn;
			
      if($oneNetwork['NKey'] != null && $oneNetwork['AdsOn'] == 1)
	{
	  $finalPriorities[] = $oneNetwork['Priority'];
	  $finalNids[] = $oneNetwork['NID'];
	  $priorities[$oneNetwork['NID']] = $oneNetwork['Priority'];
	}
    }

  // custom priority
  $finalNids[] = -1;
	
  // Makes sure all priorities in correct order
  $prioritiesLength = count($priorities);
  if(array_sum($priorities) != ($prioritiesLength*$prioritiesLength+$prioritiesLength)/2)
    {
      $start = 1;
      $finalPriorities = array();
      $finalNids = array();
      asort($priorities); // this keeps the order correct
      foreach ($priorities as $key => $priority)
	{
	  $finalPriorities[] = $start;
	  $finalNids[] = $key;
	  $start++;
	}
    }

  changeNetworkPriorities($uid, $appId, $finalNids, $finalPriorities);
}
?>
