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
require_once ('includes/inc_global.php');
require_once ('includes/network_functions_get.php');
require_once ('includes/app_functions_get.php');
require_once ('includes/inc_login.php');

function cmp($a, $b)
{
  return strcmp($a["Name"], $b["Name"]);
}
	
$appId = $_GET['AppID'];
 
for($i=0; $i<=16; $i++) {
  $allNetworks[$i]['AdsOn'] = 0;
  $allNetworks[$i]['NID'] = 'NO-NID';
  $allNetworks[$i]['NKey'] = 0;
  $allNetworks[$i]['Percent'] = 0;
  $allNetworks[$i]['Priority'] = 0;
 }

$tempNetworks = getAppNetworks($uid, $appId, true);
if($tempNetworks != null || empty($tempNetworks)) {
  foreach($tempNetworks as $network) {
    $allNetworks[$network['Type']] = $network;
  }
 }

$info = getAppInfo($appId);

$allCustom = getAppNetworksCustom($uid, $appId);

if($allCustom != null) {
	      foreach($allCustom as $customNetwork) {
				    $info['CustomPriority'] = $customNetwork['Priority'];
				    break;
				    }
}


$errMsg = (isset($_GET['Err']) && $_GET['Err'] == 1) ? '<div id="errMsg" class="errorMsg">
				<b>Error:</b> Please make sure there are no missing or repeated priorities.
				</div>' : '<div id="errMsg" class="errorMsg" style="display:none;"></div>';
				


      	$priorities = array();
	$customPriorities = array();
	foreach ($allNetworks as $myNetwork) {
		if ($myNetwork['NKey'] && $myNetwork['AdsOn'] == 1)
			$priorities[] = $myNetwork['Priority'];
	}
	
	if (count($allCustom) > 0) {
		$priorities[] = $info['CustomPriority'];
	}
	
	$prioritiesLength = count($priorities);
	if (array_sum($priorities) != ($prioritiesLength * $prioritiesLength + $prioritiesLength) / 2) {
		$errMsg = '<div id="errMsg" class="errorMsg">
				<b>Error:</b> Please make sure priorities are numbered from 1 to '.$prioritiesLength.' and are not repeated
				</div>';
	}
	    
	if (isset($_GET['Success']) && $_GET['Success'] == 1) {
		$successMsg = '<div id="successMsg" class="successMsg">
				Your changes have been saved.
				</div>';
	}

if ($prioritiesLength == 0) {
	$errMsg = '<div id="errMsg" class="errorMsg">
				<b>Note:</b> You currently have no ads being displayed. <a href="main?AppID='.$appId.'">Enter in your api keys</a> or <a href="addCustom?AppID='.$appId.'">create a custom ad</a> and you can edit the rollover priorities here.
				</div>';
 }

$appId = $_GET['AppID'];
$allApps = getApps($_SESSION['UID']);
if(count($allApps) == 0)
  {	
    header('Location: addApplication');
    exit;
  }
if($appId == null)
  $appId = $allApps[0]['AppID'];

$selectText = '';
$found = false;
usort($allApps, 'cmp');
foreach($allApps as $oneApp)
{
  if($oneApp['AppID'] == $appId)
    {
      $found = true;
      $selected = 'SELECTED';
      $customPriority = $oneApp['CustomPriority'];
      $appName = $oneApp['Name'];
      $adsOn = ($oneApp['AdsOn'] == 1);
    }
  else 
    $selected = '';
		
  $selectText .= '<option value="'.$oneApp['AppID'].'" '. $selected .'>'.$oneApp['Name'].'</option>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="icon" type="image/ico" href="favicon.ico">
	</link>
	<link rel="shortcut icon" href="favicon.ico">
	</link>
	<title>AdWhirl: Mobile Advertising | iPhone | Monetize Traffic</title>
	<!-- CSS -->
	<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/structure.css" type="text/css" />
	<link rel="stylesheet" href="css/form.css" type="text/css" />
	<link rel="stylesheet" href="css/theme.css" type="text/css" />
	<!-- JavaScript -->
	<script type="text/javascript" src="scripts/wufoo.js">
	</script>
	<script type="text/javascript" src="scripts/jquery.js">
	</script>

<?
require ('includes/inc_head.html');
?>




<div id="appForm">

<?
require_once('includes/appFormLeft.php');
?>

</div>
<br />

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
	<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">
			<div id="tab3">
			<ul id="tabnav">
				<li class="tab1"><a href="main?AppID=<?=$appId?>">All Ads</a></li>
				<!--<li class="tab2"><a href="customView?AppID=<?=$appId?>">Custom Ads</a></li>-->
				<li class="tab3"><a href="rollover?AppID=<?=$appId?>">Rollover Priorities</a></li>
				<li class="tab5"><a href="editApplication?AppID=<?=$appId?>">Edit App Info</a></li>
				<li class="tab6"><a href="reporting?AppID=<?=$appId?>">Reporting</a></li>
				<li class="tab7"><a href="addCustom?AppID=<?=$appId?>">Custom Ad</a></li>
			</ul>
			</div>

	<? if(isset($errMsg)) { echo $errMsg; } ?>
	<? if(isset($successMsg)) { echo $successMsg; }?>
	<form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processNetworkChanges?AppID=<?=$appId?>" onsubmit="return checkform(this);">
	<div>
<br />
	<h2>Rollover Priorities</h2>
	<div>
	Rollover priorities specify the order in which AdWhirl will try to retrieve the next ad if a request to a particular ad network fails (why you never see 100% fill rates for your ad networks). #1 is the highest priority (the first ad network requested from).
		</div>
		</div>

	  <table>
	  <tr valign="bottom">
	  <td width="440px">&nbsp;</td>
	  <td width="120px">&nbsp;</td>
	  <td width="320px">&nbsp;</td>
	  </tr>

		<?php 
		$hasAtLeastOneKey = false;
foreach ($allNetworksGlobal as $oneNetwork) {
	$apikey = $allNetworks[$oneNetwork['ID']]['NKey'] == '' ? null : $allNetworks[$oneNetwork['ID']]['NKey'];
	if ($apikey == null || $allNetworks[$oneNetwork['ID']]['AdsOn'] != 1)
		continue;
						    
	if ($oneNetwork['ID'] == 3) { //VideoEgg
		$keys = explode(KEY_SPLIT, $apikey);
		if (count($keys) != 2) {
			$keys = array();
			$keys[0] = $keys[1] = '';
		}
		$apikey = $keys[0].' / '.$keys[1];
	} elseif ($oneNetwork['ID'] == 8) { //Quattro
		$keys = explode(KEY_SPLIT, $apikey);
		if (count($keys) != 2) {
			$keys = array();
			$keys[0] = $keys[1] = '';
		}
		$apikey = $keys[0].' / '.$keys[1];
	} elseif ($oneNetwork['ID'] == 11) { //Mobclix
		$keys = explode(KEY_SPLIT, $apikey);
		if (count($keys) != 2) {
			$keys = array();
			$keys[0] = $keys[1] = '';
		}
		$apikey = $keys[0].' / '.$keys[1];
	} elseif ($oneNetwork['ID'] == 16) { //Generic
		$apikey = 'N/A';
	}

	$priorityNum = $allNetworks[$oneNetwork['ID']]['Priority'] == null ? 0 : $allNetworks[$oneNetwork['ID']]['Priority'];

	$networkFont = '<font color="#003366">';
	$networkFontEnd = '</font>';
	if ($apikey == null) {
		$priority = '<i><font color="#cc0033">Key not set</font></i>
													<input type="hidden" name="priority[]" value="0">';
	} else {
		$hasAtLeastOneKey = true;
		$priority = 'Priority: <input name="priority[]" type="text" class="text small" value="'.$priorityNum.'" maxlength="255"/>';
	}

	echo '<input type="hidden" name="NID[]" value="'.$allNetworks[$oneNetwork['ID']]['NID'].'">';
	echo '<tr><td class="networkNameTD" colspan="100%"><div class="networkNameRoll"><strong>'.$networkFont.$oneNetwork['Name'].$networkFontEnd.'</strong></div></td></tr>';
	echo '<tr><td style="padding-bottom: 5px">API Key: <strong>'.$apikey.'</strong></td>
	  <td style="padding-bottom: 5px">'.$priority.'</td>
	  <td align="right" style="padding-bottom: 5px"><a href="'.$oneNetwork['Website'].'" target="_blank"><small>View Website</small></td>
	  </tr>';
}
if (count($allCustom) > 0) {
	$priorityNum = $info['CustomPriority'];
	$networkFont = '<font color="#003366">';
	$networkFontEnd = '</font>';
	$hasAtLeastOneKey = true;
	$priority = 'Priority: <input name="priority[]" type="text" class="text small" value="'.$priorityNum.'" maxlength="255"/>';

	foreach($allCustom as $customNetwork) {
	echo '<input type="hidden" name="NID[]" value="'.$customNetwork['NID'].'">';

	echo '<tr><td class="networkNameTD" colspan="100%"><div class="networkNameRoll"><strong>'.$networkFont.'Custom Ads'.$networkFontEnd.'</strong></div></td></tr>';
	echo '<tr><td style="padding-bottom: 5px">Note: Nothing rolls over past custom ads, since they will always be served</td>
	  <td style="padding-bottom: 5px">'.$priority.'</td>
  <td align="right" style="padding-bottom: 5px"></td>
  </tr>';

break;
	}	

}

echo '<tr><td colspan="100%" class="networkNameTD">&nbsp;</td></tr>';
?>
</table>

	<?php if (count($allNetworks) > 0 && $hasAtLeastOneKey) { ?>
										 <ul>
										 <li class="buttons">
										 <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Save Changes" />
										 </li>
										 </ul>
										 <?php } ?>
										 </form>
										 </div><!--container--><img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
	</div><!-- wufoo shell-->
	</div>
	</div><!--Main Lower Panel Close-->
	<?php 
	require ('includes/inc_tail.html');
?>
										 <script language="JavaScript" type="text/javascript">
	<!--
	    
	function checkform ( form )
										 {
											 var why = " ";
											 var sum = 0;
											 var toAdd = 0;
											 var isValid = 1;
		
											 var arr = document.getElementsByName("priority[]");
											 var arr_length = arr.length;
											 for(var i = 0; i < arr_length; ++i)
											 {
												 toAdd = parseInt(arr[i].value);
												 if(toAdd < 0 && isValid == 1)
												 {
													 why += 'Negative priorities are not valid.<br/>';
													 isValid = 0;
												 }
												 sum += toAdd;
											 }
		
											 if((isValid==1) && (sum == (arr_length*arr_length+arr_length)/2))
											 {
												 return true;
											 }
		
											 why += 'Please make sure priorities are numbered from 1 to '+arr_length+' and are not repeated';
		
											 if(why == ' ')
												 why += 'Invalid input - please make sure priorities are positive integers';
		
											 var ErrorElement = document.getElementById("errMsg");
											 <?php if(isset($_GET['Success']) && $_GET['Success']==1) { ?>
												 var successListElementStyle = document.getElementById("successMsg").style;
												 <?php } ?>
											 // see http://www.thesitewizard.com/archive/validation.shtml
											 // for an explanation of this script and how to use it on your
											 // own website
		
											 <?php if(isset($_GET['Success']) && $_GET['Success']==1) { ?>
															 successListElementStyle.display="none";
															 <?php } ?>
		
											 ErrorElement.style.display="block";
											 ErrorElement.innerHTML = "<b>Error:</b>" + why;
		
											 ErrorElement.focus(); 
	    
		
											 // ** END **
											 return false;
										 }
	    
//-->
</script>
