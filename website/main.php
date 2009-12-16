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
require_once('includes/app_functions_get.php');
require_once('includes/network_functions_get.php');
require_once('includes/inc_login.php');

if(isset($_GET['AppID'])) {
  $appId = $_GET['AppID'];
 }
 else {
   $appId = null;
 }

$allApps = getApps($uid);
if(count($allApps) == 0)
  {	
    header('Location: addApplication');
    exit;
  }
if($appId == null)
  $appId = $allApps[0]['AppID'];
		
for($i=0; $i<=16; $i++) {
  $allNetworks[$i]['AdsOn'] = 0;
  $allNetworks[$i]['NID'] = 'NO-NID';
  $allNetworks[$i]['NKey'] = '';
  $allNetworks[$i]['Percent'] = 0;
  $allNetworks[$i]['Priority'] = 0;
 }

$tempNetworks = getAppNetworks($uid, $appId, true, false);

if($tempNetworks != null || empty($tempNetworks)) {
  foreach($tempNetworks as $network) {
    $allNetworks[$network['Type']] = $network;
  }
 }

$allCustom = getAppNetworksCustom($uid, $appId);
$allUserCustom = getUserNetworksCustom($uid);
//$allUserCustom = array_diff_assoc($allUserCustom, $allCustom);

$errMsg = (isset($_GET['Err']) && $_GET['Err'] == 1) ? '<div class="errorMsg" id="errMsg">
			<b>Error:</b> Please make sure that all percentages sum to exactly 100.
			</div>' : '<div class="errorMsg" id="errMsg" style="display:none">
			</div>';

$sum = 0; $customSum = 0; $numKeysSet = 0;
foreach($allNetworks as $myNetwork)
{
  if(isset($myNetwork['NKey']) && $myNetwork['NKey'] != null && isset($myNetwork['Percent']))
    {
      $sum += $myNetwork['Percent'];
      $numKeysSet++;
    }
}

foreach($allCustom as $myNetwork)
{
  $sum += $myNetwork['Percent'];
  $customSum += $myNetwork['Percent'];
}
	
if($sum == 0)
  {
    $errMsg = '<div class="errorMsg" id="errMsg">
			<b>Note:</b> You are currently not displaying any ads (all of your percentages are 0).
			</div>';
  }
elseif($sum != 100)
{
  $errMsg = '<div class="errorMsg" id="errMsg">
			<b>Error:</b> Your percentages do not sum to 100. Current sum: ' . $sum . '
			</div>';
}
elseif(isset($_GET['Success']) && $_GET['Success'] == 1)
{
  $successMsg = '<div class="successMsg" id="successMsg">
			Your changes have been saved.
			</div>';
}

if(isset($_GET['DeleteApp']) && $_GET['DeleteApp'] == 1)
  {
    $successMsg = '<div class="successMsg" id="successMsg">
			You have successfully deleted an application.
			</div>';
  }
elseif(isset($_GET['DeleteCustom']) && $_GET['DeleteCustom'] == 1)
{
  $successMsg = '<div class="successMsg" id="successMsg">
			You have successfully deleted a custom ad.
			</div>';
}
elseif(isset($_GET['EditCustom']) && $_GET['EditCustom'] == 1)
{
  $successMsg = '<div class="successMsg" id="successMsg">
			You have successfully edited a custom ad.
			</div>';
}

$selectText = '';
$found = false;
usort($allApps, 'cmp');
foreach($allApps as $oneApp)
{
  if($oneApp['AppID'] == $appId)
    {
      $found = true;
      $selected = 'SELECTED';
      $appName = $oneApp['Name'];
      $adsOn = ($oneApp['AdsOn'] == 1);
    }
  else 
    $selected = '';
		
  $selectText .= '<option value="'.$oneApp['AppID'].'" '. $selected .'>'.$oneApp['Name'].'</option>';
}

// don't let sneaky people try to look at weird AppIDs
if($appId != '' && !$found)
  {
    header('Location: main');
    exit;
  }

if($adsOn)
  $onOff = '<a href="processOnOff?AppID='.$appId.'&On=0"><img class="on" src="images/onoff.gif"/></a>';
 else
   $onOff = '<a href="processOnOff?AppID='.$appId.'&On=1"><img class="off" src="images/onoff.gif"/></a>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link rel="icon" type="image/ico" href="favicon.ico"></link>
  <link rel="shortcut icon" href="favicon.ico"></link>
  <title>AdWhirl: Mobile Advertising | iPhone | Monetize Traffic</title>
  <!-- CSS -->
  <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="css/structure.css" type="text/css" />
  <link rel="stylesheet" href="css/form.css" type="text/css" />
  <link rel="stylesheet" href="css/theme.css" type="text/css" />
  <link rel="stylesheet" href="css/jquery.lightbox-0.5.css" type="text/css" />

  <!-- JavaScript -->
  <script type="text/javascript" src="scripts/wufoo.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.lightbox-0.5.pack.js"></script>
  <script type="text/javascript" src="ibox/ibox.js"></script>
  <script type="text/javascript">iBox.setPath('ibox/'); iBox.default_width = 600;</script>

  <?
  require('includes/inc_head.html');
?>

  <div id="appForm">
							  <div id="appFormRight">
							  <div id="onOffMainDiv"><div class="onOffImg"><?=$onOff?></div></div><div id="adStatus">Ads:</div>
							  </div>
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
							  <div id="tab1">
							  <ul id="tabnav">
							  <li class="tab1"><a href="main?AppID=<?=$appId?>">All Ads</a></li>
							  <!--<li class="tab2"><a href="customView?AppID=<?=$appId?>">Custom Ads</a></li>-->
							  <li class="tab3"><a href="rollover?AppID=<?=$appId?>">Rollover Priorities</a></li>
							  <li class="tab5"><a href="editApplication?AppID=<?=$appId?>">Edit App Info</a></li>
							  <li class="tab6"><a href="reporting?AppID=<?=$appId?>">Reporting</a></li>
							  <li class="tab7"><a href="addCustom?AppID=<?=$appId?>">Custom Ad</a></li>
							  </ul>
							  </div>

							  <? if(isset($successMsg)) { echo $successMsg; } ?>
							  <? if(isset($errMsg)) { echo $errMsg; } ?>

							  <form id="customForm" name="customForm" action="processExistingCustomAd?AppID=<?php echo $appId; ?>" method="POST">
							  <input type="hidden" id="customNID" name="customNID" value="">
							  </form>
							  <form id="form2" name="form2" class="wufoo " autocomplete="off"
							  enctype="multipart/form-data" method="post" action="processNetworkChanges?AppID=<?=$appId?>" onsubmit="return checkform(this);">
							  <table width="100%">
							  <?php if($numKeysSet == 0 && count($allCustom) == 0) { ?>

														 <tr><td align="center"><font size="5"><strong>Hi there. <a class="lightbox" href="<?=IMAGE_DIRECTORY?>now_what.jpg">Now what?</a></strong></font></td></tr>
														 <tr><td height="14"/></tr>
														 <?php } ?>

														 <strong><?=$appName?> SDK Key:</strong> <?=$appId?> 
							  <br /><a href="get">Download SDK</a> <!-- | <a href="setup/1">Setup SDK</a> -->

							  <table>
							  <tr valign="bottom">
							  <td width="125px">&nbsp;</td>
							  <td width="350px">&nbsp;</td>
							  <td width="120px">&nbsp;</td>
							  <td width="350px">&nbsp;</td>
							  </tr>
							  <?php
							  foreach ($allNetworksGlobal as $oneNetwork) {
							  if($allNetworks[$oneNetwork['ID']]['AdsOn'] == 1 && $adsOn == 1)
							    $networkOnOff = '<a href="processOnOffNetwork?AppID='.$appId.'&NID='.$allNetworks[$oneNetwork['ID']]['NID'].'&On=0"><div class="onOffImg"><img class="on" style="border: none;" src="images/onoff.gif"/></div></a>';
							  else
							    $networkOnOff = '<a href="processOnOffNetwork?AppID='.$appId.'&NID='.$allNetworks[$oneNetwork['ID']]['NID'].'&On=1"><div class="onOffImg"><img class="off" style="border: none;" src="images/onoff.gif"/></div></a>';

$apikey = $allNetworks[$oneNetwork['ID']]['NKey'] == '' ? null : $allNetworks[$oneNetwork['ID']]['NKey'];
$percentNum = $allNetworks[$oneNetwork['ID']]['Percent'] == null ? 0 : $allNetworks[$oneNetwork['ID']]['Percent'];
if($apikey == null)
  {
    $networkOnOff = '<div class="onOffImg"><img class="disabled" style="border: none;" src="images/onoff.gif"/></div>';
    $percent = '<input type="hidden" name="percent[]" value="0">';
    $disabledMsg = '<i><font color="#cc0033"><small>Disabled.<br />Key not set</small></font></i>';
    $networkHasKey = '<input type="hidden" name="hasKey[]" value="0" />';
  }
elseif((($allNetworks[$oneNetwork['ID']]['AdsOn'] != 1) || $adsOn != 1) && $oneNetwork['ID'] != 16)
{
  $percent = '<input type="hidden" name="percent[]" value="0">';
  $disabledMsg = '<i><font color="#cc0033"><small>Disabled.</small></font></i>';
  $networkHasKey = '<input type="hidden" name="hasKey[]" value="1" />';
}
 else
   {
     $disabledMsg = '';
     $percent = 'Allocate: <input name="percent[]" type="text" class="text small" value="'.$percentNum.'" maxlength="255"/> %';
     if($allNetworks[$oneNetwork['ID']]['Type'] == 16 && $allNetworks[$oneNetwork['ID']]['Percent'] == 0) {
       $networkHasKey = '<input type="hidden" name="hasKey[]" value="0" />';
     }
     else {
       $networkHasKey = '<input type="hidden" name="hasKey[]" value="1" />';
     }
   }
							
$priorityNum = ($allNetworks[$oneNetwork['ID']]['Priority'] == null || $apikey == null || $allNetworks[$oneNetwork['ID']]['AdsOn'] != 1) ? MAX_PRIORITY : $allNetworks[$oneNetwork['ID']]['Priority'];
echo '<input type="hidden" name="priority[]" value="'.$priorityNum.'">'.$networkHasKey;
							
if($oneNetwork['ID'] == 1) // AdMob
  {
    $apiText = '<tr><td width="100">PublisherID: </td><td><input name="apikey[]" type="text" class="text" value="'.$apikey.'" maxlength="255"/></td></tr>';								
  }
 else if($oneNetwork['ID'] == 2) // Pinch Media
   {
     $apiText = '<tr><td width="100">AppCode: </td><td><input name="apikey[]" type="text" class="text" value="'.$apikey.'" maxlength="255"/></td></tr>';
   }
 else if($oneNetwork['ID'] == 3) // VideoEgg
   {
     $keys = explode(KEY_SPLIT,$apikey);
     if(count($keys) != 2)
       {
	 $keys = array();
	 $keys[0] = $keys[1] = '';
       }
     $apiText = '<tr><td width="100">PartnerID: </td><td><input name="apikey[]" type="text" class="text" value="'.$keys[0].'" maxlength="255"/></td></tr>
  <tr><td width="100">SiteID: </td><td><input name="videoegg_key" type="text" class="text" value="'.$keys[1].'" maxlength="255"/></td></tr>';
   }
elseif ($oneNetwork['ID'] == 8) // Quattro
{
  $keys = explode(KEY_SPLIT,$apikey);
  if(count($keys) != 2)
    {
      $keys = array();
      $keys[0] = $keys[1] = '';
    }
  $apiText = '<tr><td width="100">SiteID: </td><td><input name="apikey[]" type="text" class="text" value="'.$keys[0].'" maxlength="255"/></td></tr>
  <tr><td width="100">PublisherID: </td><td><input name="quattro_key" type="text" class="text" value="'.$keys[1].'" maxlength="255"/></td></tr>';

}
//Only display MobClix if they have a legacy key
elseif ($oneNetwork['ID'] == 11 && !empty($apikey)) // MobClix
{
  $keys = explode(KEY_SPLIT,$apikey);
  if(count($keys) != 2)
    {
      $keys = array();
      $keys[0] = $keys[1] = '';
    }
  $apiText = '<tr><td width="100">ApplicationID: </td><td><input name="apikey[]" type="text" class="text" value="'.$keys[0].'" maxlength="255"/></td></tr>
  <tr><td width="100">AdCode: </td><td><input name="mobclix_key" type="text" class="text" value="'.$keys[1].'" maxlength="255"/></td></tr>';
}
elseif($oneNetwork['ID'] == 16) // Generic Notifications
{
  $apiText = '<tr><td colspan="2">Google Adsense, Greystripe, personal ad server, etc.  <input name="apikey[]" type="hidden" class="text" value="'.$apikey.'" maxlength="255"/>
  </td></tr>';								}
 else {
   $apiText = '<tr><td width="100">API Key: </td><td><input name="apikey[]" type="text" class="text" value="'.$apikey.'" maxlength="255"/></td></tr>';
 }

echo '<input type="hidden" name="NID[]" value="'.$allNetworks[$oneNetwork['ID']]['NID'].'">
								  <input type="hidden" name="type[]" value="'.$oneNetwork['ID'].'">';
if($oneNetwork['Show'] || (isset($oneNetwork['EmailWhiteList']) && in_array(strtolower($email),$oneNetwork['EmailWhiteList'])))
  {
    if($oneNetwork['ID'] == 2)
      $networkName = $oneNetwork['Name'] . ' <small><a href="#inner_content_pinch" rel="ibox" title="OS 3.0 Note" >(OS 3.0 Note)</a></small>';
    else
      $networkName = $oneNetwork['Name'];
								
    echo '<tr><td class="networkNameTD" colspan="100%"><div class="networkName"><strong>'.$networkName.'</strong></div></td></tr>';

    echo '<tr>';
    if($oneNetwork['ID'] != 16) {
      echo '<td valign="top" style="padding-bottom: 5px">'.$networkOnOff.'<div class="disabledMsg">'.$disabledMsg.'</div></td>';    
      echo '<td style="padding-bottom: 5px"><table class="apiTextNotGeneric">'.$apiText.'</table></td>';
    }
    else {
      echo '<td colspan="2" style="padding-bottom: 5px"><table>'.$apiText.'</table></td>';
    }
								    
    echo '<td style="padding-bottom: 5px">'.$percent.'</td>';
    echo '<td valign="bottom" align="right" style="padding-bottom: 5px"><a href="'.$oneNetwork['Website'].'" target="_blank"><small><b>View '.$oneNetwork['Name'].'\'s Website</b></small></td>';
    echo '</tr>';
								    
  }
 else // don't show these networks (not ready yet)
   {
     echo '<input name="apikey[]" type="hidden" class="text" value="'.$apikey.'" maxlength="255"/>
								<input type="hidden" name="percent[]" value="0">';
   }
}

foreach ($allCustom as $oneNetwork)
{
  $percentNum = $oneNetwork['Percent'] == null ? 0 : $oneNetwork['Percent'];
  $percent = 'Allocate: <input name="percent[]" type="text" class="text small" value="'.$percentNum.'" maxlength="255"/>
							<input type="hidden" name="custompercent[]" value="'.$percentNum.'"> %'; //  we do custompercent just for javascript checking
						
  echo '<input type="hidden" name="NID[]" value="'.$oneNetwork['NID'].'"/>';
  echo '<input type="hidden" name="hasKey[]" value="1" />';
  echo '<input type="hidden" name="priority[]" value="'.$oneNetwork['Priority'].'">';
  echo '<input name="apikey[]" type="hidden" class="text" value="__CUSTOM__" maxlength="255"/>';
  echo '<input type="hidden" name="type[]" value="9">';

echo '<tr><td colspan="3" class="networkNameTD"><div class="networkName"><strong>'.$oneNetwork['Name'].'</strong></div></td>
<td class="networkNameTD" rowspan="2" align="right" valign="bottom">

								  <a href="editCustom?AppID='.$appId.'&NID='.$oneNetwork['NID'].'"><small>Edit Custom Ad</small><br />
						  		<a class="delete" href="deleteCustom?AppID='.$appId.'&NID='.$oneNetwork['NID'].'"><small>Delete Custom Ad</small>

</td>

</tr>
									<tr>
<td colspan="2" style="padding-bottom: 5px">&nbsp;<strong>Custom Ad</strong></td>
								  <td style="padding-bottom: 5px">'.$percent.'</td>
								  </tr>';

}

echo '<tr><td colspan="100%" class="networkNameTD">&nbsp;</td></tr>';
?>

</table></td>
</tr>
<tr><td height="10px"/></tr>
  </table>
				
  <ul>
  <li class="buttons">
  <input name="saveForm" id="saveForm" class="btTxt" type="submit" value="Save Changes" />
  </li>
  </ul>
						
  </form>
  </div><!--container-->
  <img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
  </div>
  <!-- wufoo shell-->
	
  </div>
  </div>
  <!--Main Lower Panel Close-->
  

  <div id="inner_content" style="display:none;">
  <div style="padding:5px;margin:5px;">	
  <h3>Latest Version: 1.2.6 (08/18/2009)</h3><br/>
  Changelog<br/>
  ==========
  <br />1.2.6 for iPhone OS 2.X and iPhone OS 3.0 target [08/18/09]
  <br />- Re-Added the AdMob SDK and re-added AdMob support
  <br />- Updated Millennial SDK to 08/06/09 version
  <br />
  <br />1.2.5 for iPhone OS 2.X and iPhone OS 3.0 target [07/18/09]
  <br />- Optimized custom ads for memory and performance
  <br />- Updated Quattro's SDK to version 2.0
<br />- Added a few fixes and workarounds to ad network SDKs
<br />
<br />1.2.0 for iPhone OS 2.X and iPhone OS 3.0 target [07/13/09]
<br />- Added support for Millennial Media.
<br />- Added glue-code support for Google AdSense and Google DoubleClick.
<br />- New Features:
<br />ADDED
<br />- (void)rollerReceivedNotificationAdsAreOff:     //Detect when ads are off
<br />
<br />+ (void)startPreFetchingConfigurationDataWithDelegate:(id< ARRollerDelegate >)delegate; //prefetch roller configuration data to save a round-trip delay
<br />- (void)rollOver; //Use the backup priority queue instead of the traffic percentage allocator (e.g. getNextAd) to fetch ads from a backup ad network 
<br />
<br />-(void)ignoreAutoRefreshTimer; //Ignore the auto refresh timer events (firing interval configured on AdWhirl.com).  Special thanks to Optime Software.
<br />-(void)doNotIgnoreAutoRefreshTimer; //Continue responding to the auto refresh timer events
<br />
<br />-(void)ignoreNewAdRequests; //Ignore both auto refresh timer events _and_ manual refreshes (via getNextAd or rollOver)
<br />-(void)doNotIgnoreNewAdRequests; //Continue responding to auto refresh timer events _and_ manual refreshes
<br />
<br />-(void)replaceBannerViewWith:(UIView*)bannerView; //replaceBannerView has been deprecated (but still supported) in favor of replaceBannerViewWith
<br />
<br />- No longer terminates Mobclix analytics if AdWhirl detects that Mobclix analytics has been activated by the developer.  
<br />- Added exception handling to Analytics start/end on behalf of a developer if AdWhirl detects that analytics is not turned on.
<br />- Added exception handling to Reachability queries.
<br />- Fixed a memory leak in replaceBannerViewWith, thanks to Anthony Mundson for the feedback.
<br />- Branding effect is now placed at the lower right corner and is now smaller
<br />
<br />1.1.0 for iPhone OS 2.X targets and iPhone OS 3.0 target [06/25/09]
<br />- Renamed 2 delegate methods and added 1 delegate method for generic notification support.  Please RENAME the delegate methods if you defined these delegate methods.
<br /><font color="Red"><strong>REMOVED</strong></font>
<br /> - (void)didReceiveAd:(ARRollerView*)adWhirlView; 
<br /> - (void)didFailToReceiveAd:(ARRollerView*)adWhirlView usingBackup:(BOOL)YesOrNo; 
<br /><font color="Green"><strong>ADDED</strong></font>
<br /> - (void)rollerDidReceiveAd:(ARRollerView*)adWhirlView; 
<br /> - (void)rollerDidFailToReceiveAd:(ARRollerView*)adWhirlView usingBackup:(BOOL)YesOrNo; 
<br /> - (void)rollerReceivedRequestForDeveloperToFulfill:(ARRollerView*)adWhirlView;  
<br /> - Timer refresh events can now be ignored and then resumed so that you can prevent refreshes from occurring in the background.  Thanks to Kazuho of Naan Studio, Inc. (Twitterfon) for this suggestion.
<br /> - Unobtrusive branding support has been added to distinguish Ads via AdWhirl from ads that are _not_ running via AdWhirl.
<br /> - Removed the AdMob library, which in turn reduced the AdWhirl SDK significantly and also removed the TouchJSON 1.0.6 library that was compiled into their library.
<br /> - Updated sample code w/ an example of generic notification support.
<br /> - Sample code now shows how you can use this generic notification to implement Google Adsense or Greystripe ads.
<br />
<br />1.0.7 for iPhone OS 2.X targets and iPhone OS 3.0 target [06/17/09]
<br />- Updated AdMob's OS 2.X/3.0 library to 06/17/2009 version
  <br />- Updated Quattro SDK to 1.0.7 w/ stability improvements
  <br />- Ad refresh timer events are now ignored while a webview canvas is displayed.  Special thanks to Matt of Mundue.net for this suggestion.
  <br />
  <br />1.0.6 for iPhone OS 2.X/OS 3.0 [06/14/2009]
  <br />- New search and social type ads (Facebook and Twitter) now available
  <br />- Updated Quattro's OS 2.X/3.0 library to 1.0.7
<br />- Updated AdMob's OS 2.X/3.0 library to 06/10/2009 version
  <br />
  <br />1.0.5 for iPhone OS 3.0 [06/01/2009]
  <br />- Added Quattro Wireless's latest library at 1.0.7 for OS 3.0
<br />- Added Mobclix's latest library with OS 3.0 target support @ 2.0.2
  <br />
  <br />1.0.5 for iPhone OS 2.X [05/30/2009]
  <br />- Updated Pinch's library to r40 
<br />- Multiple application keys now supported
<br />- Custom ad icon improvement
<br />- Custom ads now highlight upon a touchesBegan event
<br />- UIWebViews can now launch phobos/itunes urls
<br />- In the event that the delegate (and datasource) is dealloced, roller instances can set their delegates to nil to avoid sending messages to a dangling pointer 
<br />- Click/Tap events are now logged for all ad networks and not just custom ads 
<br />-Improved rollover ability in the event that traffic is allocated to an ad network that isn't supported (due to an older version of AdWhirl)
  <br />
  <br />
  1.0.4 for iPhone OS 3.0 <b>Targets</b> [05/09/2009] <br />
  - These static libraries are built specifically for the iPhone OS 3.0 target so that you may integrate AdWhirl for iPhone OS 3.0 targets.  Note, however, that Apple will reject any applications built for the iPhone OS 3.0 target, so do not attempt to submit your applications that are built for the iPhone OS 3.0 target, yet - for submissions to the app store, we still recommend our standard 1.0.4 library and building against the OS 2.X target. <br />
  - Also note that ad network libraries that do not support the iPhone OS 3.0 target have been removed.  Therefore, please do not allocate any traffic to these ad networks while testing on our OS 3.0 library.  If you do allocate traffic to these ad networks, then ads will not appear for them.  These libraries have been removed:<br />
  - Mobclix<br />
  - Quattro Wireless<br />
  - Pinch Media<br />
  - As soon as these ad networks provide us libraries that are stable under the iPhone OS 3.0 target, we will update the AdWhirl static library to include their latest stable static libraries.  Please stay tuned.<br />
  <br />
  1.0.4 [04/19/2009]<br/>
  - Updated Mobclix library to the latest version @ 2.0.1, with analytics launch support if analytics has not started.  This will allow you to drive Mobclix ads on-the-fly even if you did not run analytics with Mobclix in your app from the start.  Ideally, if you plan to run Mobclix ads, then you should consider using Mobclix as your analytics company and run analytics within your app.  Thanks to Inedible Software for providing feedback regarding Mobclix.<br/>
  - Updated Pinch Media's library to the latest version as of 04/20/09.  Just like Mobclix, Pinch also requires analytics to be run.  If you plan to use Pinch as your primary source of ads, then you should run their analytics in your app.  Otherwise, AdWhirl will start analytics for you automatically when Pinch Media's ads are fetched.<br/>
  - You can now drive text colors and background colors from your application, instead of relying solely on dynamic AdWhirl configuration via www.AdWhirl.com.  Thanks to Optime Software for this suggestion.<br/>
  - Removed Greystripe from AdWhirl since fullscreen banner ads that appear spontaneously do not fit with our current banner ad roller paradigm.<br/>
											       - Also note that Mobclix's ad view classes are subclassed from UIWebView, and their scrolling enabled state is left alone.<br/>
- We suggest that you update to this version since the new ad network libraries that weâve just integrated into AdWhirl are much more stable, especially in situations where a device autojoins a wifi hotspot that requires a login. Â <br /><br />
1.0.3 [04/09/2009]<br/>
- Updated VideoEgg's library to the latest version that they provided for us @ 1.0.4<br/>
  - Updated AdMob's library to the latest version 04/02/09. From their changeset: Dismissing an embedded browser when the keyboard is up no longer causes a crash.<br/>
- <strong>Note:</strong> <font color="Red">AdMob now requires the AddressBook framework to be included for click-to-contacts ads.  Therefore, if you are updating your AdWhirl library, you must include the AddressBook framework, as the updated <a href="instructions">instructions</a> (on step 2) now indicate.</font><br/>
- Added AdMob-specific delegate methods that are relayed only to AdMob.<br/>
- Added Quattro-specific demographic delegate methods that are relayed only to Quattro.<br/>
- ARRollerView instances now allow you to get the most recent ad network name that an ad request was made to.  Refer to the sample code for example use-cases.<br /><br />   

    </div>
    </div>

    
    
    
    
    <div id="inner_content_admob" style="display:none;">
    <div style="padding:5px;margin:5px;">	
    	<h3>What is AdMob Legacy Support?</h3><br/>
    	Due to AdMob's new policy, developers can no longer integrate their library into AdWhirl's optimization process, nor can AdWhirl have any visibility into AdMob's library.
  As a result, AdMob Legacy Support provides developers with a way to pass the PublisherID back to older clients who haven't upgraded to AdWhirl's new libraries yet (ver 1.1.0 or greater,
																				      which are in compliance with AdMob's new policy). If you wish to use AdMob's library, you may now integrate it independently of AdWhirl as we have removed their library from our SDK.
																				      Remember, at minimum, developers should set AdWhirl to request an ad if the AdMob library fails an ad request through delgate - (void)didFailToReceiveAd:(AdMobView *)adView, so that you don't waste opportunities to monetize ads.
    	Please email us at <a href="mailto:admob-inquiry@adwhirl.com">admob-inquiry@adwhirl.com</a> with any questions on the matter.
    	<br/><br/>
    	Thanks!<br/>
    	The AdWhirl Team
    </div>
    </div>
    
    <div id="inner_content_pinch" style="display:none;">
    <div style="padding:5px;margin:5px;">	
    	<h3>Note for AdWhirl OS 3.0 SDK Developers</h3><br/>
    	Currently Pinch Media's ad library is not compatible when BUILDING against OS 3.0 (the 2.X library is still compatible when running on 3.0 devices), and we are awaiting a new library from them. In the meantime, however, we have had to remove Pinch from our OS 3.0 SDK, meaning you cannot run Pinch ads if you are building against OS 3.0.
																				      Please email us at <a href="mailto:support@adwhirl.com">support@adwhirl.com</a> with any questions on the matter.
																				      <br/><br/>
																				      Thanks!<br/>
																				      The AdWhirl Team
																				      </div>
																				      </div>
    
    
    
    
																				      <div id="inner_content_faq" style="display:none;">
																				      <div style="padding:5px;margin:5px;">
																				      <h3><a href="help">(Or see all FAQ by clicking here)</a></h3> <br />

																				      <div id="qa">
																				      <b>21. </b>
																				      <a href="#" name="faq-221">I need to use FBConnect, but I'm getting a linker issue!</a>
    <br/>
    <div class="faq-answer" id="faq-221">
		This is because Pinch Media's advertising library contains FBConnect.  The workaround is to either refactor your version of the FBConnect classes, or make calls directly into the FBConnect object code that's been compiled into their library.  Please refer to <a href="instructions?p=5">this help page</a> for more info.
	</div>
	<br />
	<br />
</div>

<div id="qa">
    <b>22. </b>
    <a href="#" name="faq-222">Where can I find analytics header files for Pinch Media and Mobclix so that I can run analytics?</a>
    <br/>
    <div class="faq-answer" id="faq-222">
		Please refer to <a href="instructions?p=5">this help page</a> for direct download links to these header files.
	</div>
	<br />
	<br />
</div>

<div id="qa">
    <b>23. </b>
    <a href="#" name="faq-223">I'd like to fetch ads of 300x250 in size from Mobclix.  How do I do that?</a>
												     <br/>
												     <div class="faq-answer" id="faq-223">
												     AdWhirl currently only supports banner ad sizes of 320x50 and 300x50 sizes, but anticipate supporting bigger banner sizes when demand kicks in for these types of ads.  When we tested out big banner ad sizes, we only retrieved 300x50 banners at that time (perhaps due to lack of such inventory) and the frame size took up half of our app's screen size so it didn't make any sense at that time to integrate those ad sizes.  What you can do for now is to fetch these banner ads yourselves.  You'll need to include the header files so that you can make these calls yourself.  Therefore, please refer to <a href="instructions?p=5">this help page</a> for direct download links to these header files (Mobclix, Quattro).
	</div>
	<br />
	<br />
</div>

<div id="qa">
    <b>24. </b>
    <a href="#" name="faq-224">I get a warning for the libAdWhirlSimulator_1.X.X when compiling for device.</a>
    <br/>
    <div class="faq-answer" id="faq-224">
		AdWhirl supports both the x86 (simulator via intel platform) and ARM (iPhone/iPod device) processors, which is why we have two static libraries available.  The warning is not an issue, and is the result of the linker's complaint that it can't link to a library that is supported for a different platform.  But the linker will automatically link w/ the correct library before deploying the app onto your phone.  There are thousands of free apps running AdWhirl as of today, so please rest assurred that this warning is not an issue.
	</div>
</div>

<div id="qa">
    <b>20. </b>
    <a href="#" name="faq-220">I'm concerned about the AddressBook requirement.  Why is it needed?</a>
																				      <br/>
																				      <div class="faq-answer" id="faq-220">
																				      The AddressBook framework is used by AdMob for click-to-call ads.  We haven't seen any AdMob ads of that nature, but the only ad network that uses the AddressBook framework is AdMob.  AdWhirl never references that framework. In fact, AdWhirl simply just routes traffic, performs failover, and relays information that you want to relay to specific ad networks.  At the end of the day, we're iPhone developers and we opened up this side project into a solution so that all iPhone developers can enjoy the same control over their own ad content space.  
																				      <br />
																				      <br />
																				      </div>
																				      </div>

																				      <div id="qa">
																				      <b>19. </b>
																				      <a href="#" name="faq-219">How can I resize the roller view's frame size after rotating into landscape mode?</a>
    <br/>
    <div class="faq-answer" id="faq-219">
		You have full control over the roller's view frame.  You will also notice that if you adjust the frame's size to any CGSize (such as 480x50), the roller's frame will adjust accordingly.  However, the ad content will still stay at 320x50.  Therefore, we recommend that you simply center the roller view in landscape mode.  To do so, adjust the roller's view resizing mask to pad the left and right margins of the roller view (rollerView.autoresizingMask = UIViewAutoresizingFlexibleLeftMargin | UIViewAutoresizingFlexibleRightMargin).  Also, don't forget to set the clipsToBounds property to YES so that the animation happens within the 320x50 frame and doesn't leak out of that frame.
	<br />
	<br />
	</div>
</div>

<div id="qa">
	<b>10. </b><a href="#" name="faq-109">Where/What is the Mobclix adCode?</a><br />
	<div class="faq-answer" id="faq-109">The adCode key is another key (a Guid) that you must request from Mobclix, after you obtain an analytics key called the applicationID.  The adCode key looks similar to the analytics key, but is a different key.  Also note that if you plan to use Mobclix as your primary source of ads, we highly recommend that you use them as your analytics provider.  Mobclix <b>requires</b> their analytics to run within your app prior to any ad request.  As a result, if you did not run Mobclix analytics in your application prior to launching on the appstore, our library will have to activate analytics for you on the fly.  However, the analytics data recorded from this method may not be entirely accurate, so we highly recommend that you run Mobclix analytics if you expect to run Mobclix ads!
		<br />
		<br />
	</div>
</div>

<div id="qa">
    <b>18. </b>
    <a href="#" name="faq-218">Apple just enforced a strict policy requiring applications to run on iPhone OS 3.0.  <br />Does AdWhirl pass this test?</a>
    <br/>
    <div class="faq-answer" id="faq-218">
				In short, <b>if you're building with a non-OS 3.0 target</b>, then AdWhirl will still run fine even on iPhone OS 3.0 devices (refer to Tech FAQ #17 for more info).  To our understanding, Apple just wants to make sure that applications can run on iPhones w/ iPhone OS 3.0.  They do <b>NOT</b> require you to build applications with the iPhone OS 3.0 target--which is why they still offer the older targets in the XCode SDK.  <br />Some ad network libraries (AdMob, Quattro, Pinch Media and Mobclix) do not support the iPhone OS 3.0 target, so you cannot integrate their libraries (and as a direct result, cannot use AdWhirl) for this target.  In the meantime, we are approaching ad networks for Stable SDKS under the iPhone OS 3.0 target so that developers can build with the OS 3.0 target and utilize new OS 3.0 functionality.  Once we have these stable ad network SDKs integrated into AdWhirl, you'll be able to build apps with the AdWhirl library for iPhone OS 3.0 targets.
		<br />
		<br />
	</div>
</div>

 <div id="qa">
<b>8. </b><a href="#" name="faq-8">I didn't get a verification message!  What gives?</a><br />
																				      <div class="faq-answer" id="faq-8">If you didn't see the verification message in your inbox, please check your junk/spam boxes, especially if you've applied very strict spam filtering options.  If you still don't see a verification email, please email us right away at support-at-adwhirl-dot-com!.<br /><br /></div>
</div>
			
<div id="qa">
    <b>17. </b>
    <a href="#" name="faq-217">Is your library supported for the iPhone OS 3.0 target?</a>
    <br/>
    <div class="faq-answer" id="faq-217">
		Our custom ad library, which allows you to show custom ads that you create, work fine under the iPhone OS 3.0 target.  However, some ad network libraries will not run successfully w/ the iPhone OS 3.0 target.  In fact, under the iPhone OS 3.0 target some ad network libraries throw an exception and will subsequently crash an app since there's no uncaught exception handler.  Because of this, we urge you to only build for the production-ready versions, which are the OS 2.2.1 target and previous version targets (2.2, 2.1, 2.0).
																				      <br />
																				      <br />
																				      </div>
																				      </div>

																				      <div id="qa">
																				      <b>15. </b>
																				      <a href="#" name="faq-215">Can I use my version of TouchJSON, TouchXML, or FMDB since the ad network libraries already compiled them (AdMob, Quattro)?</a>
																				      <br/>
																				      <div class="faq-answer" id="faq-215">
																				      Yes, Definitely.  You can refactor the class names of your version of TouchJSON, TouchXML or FMDB and make calls to your refactored classes.  In that case, for example, you would no longer run calls to CJSON or CXML, but you'd make calls to your own classes instead (e.g. ARJSON or ARXML).  In the meantime, we're providing this feedback to ad networks and urging them to refactor these classes. 
																				      <br />
																				      <br />
																				      </div>
																				      </div>
			
																				      <div id="qa">
																				      <b>16. </b>
																				      <a href="#" name="faq-216">Does the name change affect older library versions?</a>
																				      <br/>
																				      <div class="faq-answer" id="faq-216">
																				      The older libraries under the AdRollo name works just as fine--so not to worry.  The name change actually doesn't affect anything, and the functions are the same.  In fact, try changing the traffic allocation values from here and open up your application--you'll see those on-the-fly changes and realize that everything is fine. 	
																				      <br />
																				      <br />
																				      </div>
																				      </div>
			
																				      <div id="qa">
																				      <b>9. </b>
																				      <a href="#" name="faq-108">Google has been active and has been providing a beta library.  Do you guys have any plans for Google adsense?</a>
																				      <br/>
																				      <div class="faq-answer" id="faq-108">
																				      <div class="faq-answer" id="faq-108">Our position has always been to integrate as many ad networks as possible, for our developers' benefit.  We've actually been looking into the integration of their library.  However, because they're in beta mode, they don't want AdWhirl to integrate them until they're ready.  In the meantime, we're also working with other ad networks, such as Millennial Media, to get you new ad networks to work with.
																				      <br/>
																				      <br/>
																				      </div>
																				      </div>
																				      <div id="qa">
																				      <b>10. </b>
																				      <a href="#" name="faq-210">I've set my refresh interval to X seconds.  I don't see an ad every X seconds.  What gives?</a>
																				      <br/>
																				      <div class="faq-answer" id="faq-210">
																				      When a refresh kicks in, a getNextAd call is made automatically.  The call will choose an ad network based on the traffic allocation percentages you've applied.  An ad request is then triggered.  If the ad request fails, the backup ad network sources will then be used.  If all of the backup sources are used and all ad requests still fail, the roller will stop fetching ads.  As a result, you will not see an ad refresh.  If you feel this isn't the case, please let us know about this issue right away.
																				      <br/>
																				      <br/>
																				      </div>
																				      </div>
																				      <div id="qa">
																				      <b>11. </b>
																				      <a href="#" name="faq-211">I use TouchJSON (or TouchXML).  I get a linker error when building my project because you've already compiled TouchJSON (or TouchXML) into your library!</a>
                <br/>
                <div class="faq-answer" id="faq-211">
                    We feel your pain :)  But actually, we didn't compile TouchJSON.  AdMob compiled TouchJSON (and Quattro Wireless compiled TouchXML) into their own client library.  Therefore, when we included their library into ours, TouchJSON (and TouchXML) was also included.  Version 1.0 is being used, so the way around this is to just include the TouchJSON headers (or TouchXML headers) without the class files so that you don't produce object files that will cause a linker error when the linker detects that TouchJSON (TouchXML) components are already defined.  You can find the <a href="http://adrollo-binaries.s3.amazonaws.com/AdMobTouchJSON.zip">TouchJSON headers that they provided here</a>.
																																																										<br/>
																																																										<br/>
																																																										</div>
																																																										</div>
																																																										<div id="qa">
																																																										<b>12. </b>
																																																										<a href="#" name="faq-212">I use Pinch Media Analytics.  I get a linker error when building my project regarding TouchJSON.</a>
																																																										<br/>
																																																										<div class="faq-answer" id="faq-212">
																																																										If you're having issues with Pinch Media, it may be because TouchJSON is already included in the library (refer to the FAQ above).  In that case, you can simply just include Pinch Media's Analytics headers (can be <a href="http://adrollo-binaries.s3.amazonaws.com/PinchMediaBeacon.h.zip">downloaded here</a>) and make analytics calls through the Pinch Media ad library that we've included.  Otherwise, the other option is for us to give you a custom AdWhirl SDK client library with Pinch Media's ad library removed.  Please let us know if you prefer to have this option instead.
																																																										<br/>
																																																										<br/>
																																																										</div>
																																																										</div>
																																																										<div id="qa">
																																																										<b>13. </b>
																																																										<a href="#" name="faq-213">How do I load the AdWhirl demo onto my iPhone so that I can test on an actual device?</a>
																																																														    <br/>
																																																														    <div class="faq-answer" id="faq-213">
																																																														    Pretty simple.  First, click on Info.plist and change the "Bundle Identifier" value to something that your provisioning profile supports (e.g. com.yourcompany.sampleapp).  Then, go to the build tab after double-clicking on your application's target and choose a provisioning file + development certificate combo.  Lastly, change your Active SDK platform on the upper-left section on XCode to Device - iPhone [2.0/2.1/2.2/2.2.1/3.0].  You may have to reopen XCode if you're still having issues during the code-sign process or application load process.
																																																										<br/>
																																																										<br/>
																																																										</div>
																																																										</div>
																																																										<div id="qa">
																																																										<b>14. </b>
																																																										<a href="#" name="faq-214">I have an app that produces a lot of traffic.  Can you guys handle a lot of traffic?</a>
																																																										<br/>
																																																										<div class="faq-answer" id="faq-214">
																																																										AdWhirl was built to scale, as we had numerous apps generating several million impressions daily before we even launched. Here are a few reasons why you can rest assured of our traffic handling capacity:
																																																										<br/>
																																																										1) We have load balanced servers w/ failover support
																																					    <br/>
																																					    2) There is web proxy caching and memcached caching (mainly for the db layer) as well as a cache-writethrough policy so that your changes expire the cache and therefore changes are reflected immediately
							  <br/>
							  3) Our memory usage is at 1% capacity. Â We are running at under-capacity right now even though we're serving 75M impressions monthly, especially since we've just upgraded our servers.
							  <br/>
							  4) Binary assets are hosted on Amazon's AWS S3, and as a result our servers only deal with JSON responses which are very light-weight.
																				      <br/>
																				      5) There is also client side caching in the client sdk library itself. Â A singleton for server-side interaction ensures that only 1 connection is made to the server per application session regardless of the number of roller instances created.
  <br/>
  6) Furthermore, the configuration JSON response is cached on the client as well that is used when the server appears to have gone dark (if DNS resolution is not working to resolve www.adwhirl.com, or in the worst case if our servers and backup servers go down or if the colo itself goes down).
      <br/>
      7) We can continue to scale out by adding more server nodes to our server farm.
      <br/>
      <br/>
      </div>
      </div>
      <h3>Getting Started:</h3>
      <br/>
      <div id="qa">
	  <b>5. </b>
	  <a href="#" name="faq-5">Do you take a cut out of our revenue that we get from an ad network, like VideoEgg, Quattro Wireless, AdMob, Pinch Media or Mobclix?</a>
	  <br/>
	  <div class="faq-answer" id="faq-5">
	  No.
	  <br/>
	  <br/>
	  </div>
	  </div>
	  <div id="qa">
	  <b>6. </b>
	  <a href="#" name="faq-6">If you don't take any revenue cuts, then how do you make any money?</a>
											   <br/>
											   <div class="faq-answer" id="faq-6">
											   We're all iPhone developers and we build free apps and monetize with ads.  Ultimately, we want to provide you the same flexibility and control over ads that we have, and it's our philosophy that developers should fully own their own ad space. Stay tuned as we'll be introducing new ways to monetize, too.
	  <br/>
	  <br/>
	  </div>
	  </div>
	  <div id="qa">
	  <b>7. </b>
	  <a href="#" name="faq-7">How is AdWhirl maintained?  I'm afraid that you guys may disappear.</a>
											   <br/>
											   <div class="faq-answer" id="faq-7">
											   AdWhirl stemmed as a side project maintained by several iPhone developers and then evolved into a real company maintained by full-time cofounders.  We do not have day jobs and are dedicated to AdWhirl.  In fact, we make our living with the ad revenue stream that comes through our free apps, optimized by AdWhirl.  We won't disappear because we use AdWhirl to control ad content for ourselves, our iPhone dev friends use AdWhirl, and hopefully you now use AdWhirl.  Failover scenarios have been extremely important for us (especially when you're serving several million ad impressions daily), so rest assured that even if all our servers vanished, clients would revert to their cached allocation values and continue serving ads without skipping a beat.
																																																																																					     <br/>
																																																																																					     <br/>
																																																																																					     </div>
																																																																																					     </div>
																																																																																					     </div>
																																																																																					     </div>
																																																																																					     </div>

																																																																																					     <script language="JavaScript" type="text/javascript">
																																																																																					     <!--

																																																																																					     $(function() {
																																																																																						 $('a.lightbox').lightBox();
																																																																																					       });

function submitCustom()
{
  var customSelect = document.getElementById("customSelect");
  document.getElementById('customNID').value = customSelect.options[customSelect.selectedIndex].value;
  //nid.value = nidValue;
  document.customForm.submit();
  return false;
}

<!-- This is handled server-side too. -->
function checkform ( form )
{
  return true;

  var why = " ";
  var sum = 0;
  var toAdd = 0;
  var isValid = 1;
	
    var arr = document.getElementsByName("percent[]");
    var arr_length = arr.length;
    for(var i = 0; i < arr_length; ++i)
      {
	toAdd = parseInt(arr[i].value);
	if(toAdd < 0 && isValid == 1)
	  {
	    why += 'Negative percents are not valid.<br/>';
	    isValid = 0;
	  }
	sum += toAdd;
      }
	
      if((isValid==1) && (sum == 100 || sum == 0))
	{
	  return true;
	}
      else if(sum > 100)
	{
	  why += 'Percentages cannot sum to greater than 100. Your sum is ' + sum + '.<br/>';
	}
      else
	{
	    why += 'Percentages must sum to exactly 100. Your sum is ' + sum + '.<br/>';
      }

      if(why == ' ')
	why += 'Invalid input - please make sure percentages are positive integers';
	
      var ErrorElement = document.getElementById("errMsg");
      <?php if((isset($_GET['DeleteApp']) && $_GET['DeleteApp']==1) || (isset($_GET['DeleteCustom']) && $_GET['DeleteCustom']==1) || (isset($_GET['EditCustom']) && $_GET['EditCustom']==1) || (isset($_GET['Success']) && $_GET['Success']==1)) { ?>
	var successListElementStyle = document.getElementById("successMsg").style;
	<?php } ?>
	  // see http://www.thesitewizard.com/archive/validation.shtml
	  // for an explanation of this script and how to use it on your
	  // own website
	
	  <?php if((isset($_GET['DeleteApp']) && $_GET['DeleteApp']==1) || (isset($_GET['DeleteCustom']) && $_GET['DeleteCustom']==1) || (isset($_GET['EditCustom']) && $_GET['EditCustom']==1) || (isset($_GET['Success']) && $_GET['Success']==1)) { ?>
	successListElementStyle.display="none";
	<?php } ?>
	
	      ErrorElement.style.display="block";
	      ErrorElement.innerHTML = "<b>Error:</b>" + why;
  	
	      ErrorElement.focus(); 

	      return false;
}

//-->
</script>

<?php
require('includes/inc_tail.html');
function cmp($a, $b)
{
  return strcmp($a["Name"], $b["Name"]);
}
?>
