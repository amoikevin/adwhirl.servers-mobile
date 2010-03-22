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
require_once ('includes/reporting_functions.php');
require_once ('includes/app_functions_get.php');
require_once ('includes/inc_login.php');
require ('includes/inc_head_reporting.html');

// GET TYPES: txtStartDate, txtEndDate, viewtype (refer to inc_head_reporting)

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;
$allApps = getApps($uid);
if (count($allApps) == 0) {
  header('Location: addApplication');
  exit;
 }
if ($appId == null)
  $appId = $allApps[0]['AppID'];
	           
$allNetworks = getAppNetworks($uid, $appId, true);
$allCustom = getAppNetworksCustom($uid, $appId);
//$allUserCustom = getUserNetworksCustom($uid);
if ($chartType != REPORT_ALL)
  $ctr = getCTR($appId, date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)), $chartType, $allCustom);
		         
$rand = rand();


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


<div id="appForm">
  <div id="appFormLeft">
  <form name="selectForm" action="reporting" method="GET">
  Select Application: 
  <select name="AppID" onchange="this.form.submit()">
  <option value="" default>--Select--</option>
  <?=$selectText?>
  </select>
  <noscript><input type="submit" class="formbutton" value="Go" /></noscript>
  <font color="#cccccc">|</font>
  <small><a href="addApplication?AppID=<?=$appId?>">Add New App</a></small>
		<font color="#cccccc">|</font> <small><strong>All Apps Reporting</strong></small>
  </form>
  </div>
</div>
<br />

<?	
		usort($allApps, 'cmp');
		foreach($allApps as $oneApp)
		{
			if($oneApp['AppID'] == $appId)
			{
				$selected = 'SELECTED';
				$customPriority = $oneApp['CustomPriority'];
				$appName = $oneApp['Name'];
			}
			else 
				$selected = '';
		}
		
		$flashUrl = "adrolloData?nocache=$rand%26txtStartDate=$startDate%26txtEndDate=$endDate%26viewtype=$chartType%26allApps=1";
		$downloadUrl = "downloadData?nocache=$rand&txtStartDate=$startDate&txtEndDate=$endDate&viewtype=$chartType&allApps=1";
		?>
	
<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
	<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">
			<div id="tab6">
			<ul id="tabnav">
				<li class="tab1"><a href="main?AppID=<?=$appId?>">All Ads</a></li>
				<!--<li class="tab2"><a href="customView?AppID=<?=$appId?>">Custom Ads</a></li>-->
				<li class="tab3"><a href="rollover?AppID=<?=$appId?>">Rollover Priorities</a></li>
				<li class="tab5"><a href="editApplication?AppID=<?=$appId?>">Edit App Info</a></li>
				<li class="tab6"><a href="reporting?AppID=<?=$appId?>">Reporting</a></li>
				<li class="tab7"><a href="addCustom?AppID=<?=$appId?>">Custom Ad</a></li>
			</ul>
			</div>
  <div>
  <form class="wufoo " enctype="multipart/form-data" method="GET">
  <input type="hidden" id="AppID" name="AppID" value="<?=$appId?>"/>Date Start: <input type="text" class="datepicker" id="txtStartDate" name="txtStartDate" size="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!-- hackiest thing ever -->Date End: <input type="text" class="datepicker" id="txtEndDate" name="txtEndDate" size="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
View: 
<select name="viewtype" id="viewtype" style="width: 170px" onchange="this.form.submit()">
  <option value="<?=REPORT_ALL?>"<?= ($chartType == REPORT_ALL) ? 'selected' : ''?>>All Ads Summary</option>
  <option value="<?=REPORT_CUSTOM_ALL?>"<?= ($chartType == REPORT_CUSTOM_ALL) ? 'selected' : ''?>>Custom Ads Summary</option>
  </select>
  <input type="submit" class="formbutton" value="Go" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$downloadUrl?>">Download Data as CSV</a>
												   </form>
												   </div>
												   <div>
												   <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="900" height="600" id="MSLine" /><param name="movie" value="FusionCharts/MSLine.swf" /><param name="FlashVars" value="&dataURL=<?=$flashUrl?>" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed wmode="transparent" src="FusionCharts/MSLine.swf" flashVars="&dataURL=<?=$flashUrl?>" quality="high" width="900" height="600" name="MSLine" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
												   </div>
												   <div align="center" style="height:35px;">
												   <ul>
												   <h2>
												   <?php 
												   if ($chartType != REPORT_ALL)
												     echo 'CTR = '.number_format($ctr, 3, '.', '').'%';
?>
  </h2>
  </ul>
  </div>
  </div>
  <!--container--><img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
												   </div><!-- wufoo shell-->
												   </div>
												   </div><!--Main Lower Panel Close-->
												   <?php 
												   require ('includes/inc_tail.html');
function cmp($a, $b) {
  return strcmp($a["Name"], $b["Name"]);
}

function getCTR($appId, $startDate, $endDate, $chartType, $customNids) {
  global $sdb;

  $impressions = 0;
  $clicks = 0;

  if ($chartType == REPORT_ALL || $chartType == REPORT_CUSTOM_ALL) {
    $nidsString = "`type` = '9'";
  } else {
    $nidsString = "`nid` = '".$chartType."'";
  }

  if(empty($nidsString)) {
    return 0;
  }
  $aaa = array('clicks', 'impressions');
  $sdb->select(DOMAIN_STATS, $aaa, "where `dateTime` >= '$startDate' and `dateTime` <= '$endDate' and $nidsString");

  foreach($aaa as $aa) {
    $clicks += $aa['clicks'];
    $impressions += $aa['impressions'];
  }

  if($impressions != 0) {
    return $clicks * 100.0 / $impressions;
  }   else {
    return 0;
  }
}
