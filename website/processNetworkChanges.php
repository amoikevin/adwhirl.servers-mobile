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
require_once('includes/network_functions.php');
require_once('includes/inc_login.php');

$aid = isset($_GET['AppID']) ? $_GET['AppID'] : null;
$nids = isset($_POST['NID']) ? $_POST['NID'] : null;
$apikeys = isset($_POST['apikey']) ? $_POST['apikey'] : null;
$percents = isset($_POST['percent']) ? $_POST['percent'] : null;
$priorities = isset($_POST['priority']) ? $_POST['priority'] : null;
$types = isset($_POST['type']) ? $_POST['type'] : null;
$hasKeys = isset($_POST['hasKey']) ? $_POST['hasKey'] : null;

if($aid == null || $nids == null) {
  exit;
 }

$videoegg_key = isset($_POST['videoegg_key']) ? $_POST['videoegg_key'] : null;
$quattro_key = isset($_POST['quattro_key']) ? $_POST['quattro_key'] : null;
$mobclix_key = isset($_POST['mobclix_key']) ? $_POST['mobclix_key'] : null;

function swap(&$a, &$b) {
  $temp = $a;
  $a = $b;
  $b = $temp;
}

function swap_all($a, $b) {
  global $nids;
  global $apikeys;
  global $percents;
  global $priorities;
  global $types;

  swap($nids[$a], $nids[$b]);
  swap($apikeys[$a], $apikeys[$b]);
  swap($percents[$a], $percents[$b]);
  swap($priorities[$a], $priorities[$b]);
  swap($types[$a], $types[$b]);
}


$prioritiesLength = count($priorities);

if(count($percents) > 0)
  {
    swap_all(2, 12);

    if(!empty($videoegg_key) && $apikeys[2]) // VideoEgg
      {
	$apikeys[2] = $apikeys[2] . KEY_SPLIT . $videoegg_key;
      }
    else 
      $apikeys[2] = null;
		
    if(!empty($quattro_key) && $apikeys[7]) // Quattro
      {
	$apikeys[7] = $apikeys[7] . KEY_SPLIT . $quattro_key;
      }
    else 
      $apikeys[7] = null;
		
    if(!empty($mobclix_key)) // MobClix
      {
	$apikeys[10] = $apikeys[10] . KEY_SPLIT . $mobclix_key;
      }
    else 
      $apikeys[10] = null;
							
    $tempPriorities = array();
    $percentSum = array_sum($percents);
    if($percentSum != 100 && $percentSum != 0)
      {
	header('Location: main?Err=1&AppID=' . $aid);
	exit;
      }
	
    for($i=0; $i<count($apikeys); $i++)
      {
	if(!$hasKeys[$i] && (empty($apikeys[$i]) /* || ($apikeys[$i] == "__GENERIC__" && $percents[$i] == 0) */ )) {
	  continue;
	}

	$apikey = $apikeys[$i];
	if(!empty($apikey)) {
	  $tempPriorities[$i] = $priorities[$i];
	}
      }
    
    $start = 1;
    $finalPriorities = array();
    asort($tempPriorities); // this keeps the order correct
    foreach ($tempPriorities as $key => $priority)
      {
	$finalPriorities[$key] = $start;

	if($key <= 16) {
	  $start++;
	}
      }

    //   echo 'nids:<br />'; print_r($nids); echo '<br/>apikeys<br />'; print_r($apikeys);  echo '<br/>percents<br/>'; print_r($percents); echo '<br/>haskeys<br />'; print_r($hasKeys); echo '<br/>priorities<br />'; print_r($finalPriorities); die;

    changeNetwork($uid, $aid, $nids, $apikeys, $percents, $types, $finalPriorities, $hasKeys);
  }
elseif($prioritiesLength > 0)
{
  // Makes sure all priorities in correct order
  if(array_sum($priorities) != ($prioritiesLength*$prioritiesLength+$prioritiesLength)/2)
    {
      header('Location: rollover?Err=1&AppID=' . $aid);
      exit;
    }

  changeNetworkPriorities($uid, $aid, $nids, $priorities);

  header('Location: rollover?Success=1&AppID=' . $aid);
  exit;
}

header('Location: main?Success=1&AppID=' . $aid);
