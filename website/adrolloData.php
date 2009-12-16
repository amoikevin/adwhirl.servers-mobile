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
  //header("Content-Type: text/xml");

require_once('includes/inc_global.php');
require_once('includes/network_functions_get.php');
require_once('includes/reporting_functions.php');
require_once('includes/inc_login.php');

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;
$startDate = isset($_GET['txtStartDate']) ? strtotime($_GET['txtStartDate']) : null;
$endDate = isset($_GET['txtEndDate']) ? strtotime($_GET['txtEndDate']) : null;
$chartType = isset($_GET['viewtype']) ? $_GET['viewtype'] : null;
$isAllApps = (isset($_GET['allApps']) && $_GET['allApps'] == 1);

if($chartType == REPORT_ALL || $chartType == REPORT_CUSTOM_ALL)
  {
    if($isAllApps) {
      $reporting = getReportingCustomAggregate($uid, $appId, date('Y-m-d',$startDate), date('Y-m-d',$endDate), true);
    }
    else {
      $reporting = getReportingCustomAggregate($uid, $appId, date('Y-m-d',$startDate), date('Y-m-d',$endDate), false);
    }
  }
 else 
   {
     $reporting = getReportingCustomAggregate($uid, $appId, date('Y-m-d',$startDate), date('Y-m-d',$endDate), false);
   }

if($chartType == REPORT_ALL)
  $caption = 'Daily Activity Summary (Impressions Only)';
 else 
   $caption = 'Daily Activity Summary (Impressions and Clickthroughs)';

echo '<chart labelDisplay=\'ROTATE\' slantLabels=\'1\' showBorder=\'0\' caption=\''.$caption.'\' subcaption=\'('.date('m/d/Y',$startDate).' to '.date('m/d/Y',$endDate).')\' lineThickness=\'1\' showValues=\'0\' formatNumberScale=\'0\' anchorRadius=\'2\'   divLineAlpha=\'20\' divLineColor=\'CC3300\' divLineIsDashed=\'1\' showAlternateHGridColor=\'1\' alternateHGridColor=\'CC3300\' shadowAlpha=\'40\' labelStep="2" numvdivlines=\'5\' chartRightMargin="35" bgColor=\'FFFFFF,CC3300\' bgAngle=\'270\' bgAlpha=\'10,10\'>
<categories >';

if($chartType == REPORT_ALL)
  {
    if($isAllApps)
      $networkReporting = getReportingNetworks($uid, $appId, date('Y-m-d',$startDate), date('Y-m-d',$endDate), true);
    else
      $networkReporting = getReportingNetworks($uid, $appId, date('Y-m-d',$startDate), date('Y-m-d',$endDate));
    $totalImpressions = array();
    $customImpressions = array();
    $networkImpressions = array();
    $counter = 0;
    $startCounting = false;
    for($i=$startDate; $i<=$endDate; $i=strtotime('+1 day', $i))
      {
	$keyDate = date('Y-m-d',$i);
		
	if(!$startCounting)
	  {
	    if(isset($reporting['$keyDate']) && $reporting[$keyDate]['impressions'] > 0)
	      $startCounting = true;
	    else
	      {
		foreach ($allNetworksGlobal as $oneNetwork)
		  {
		    if(isset($networkReporting[$keyDate]) && $networkReporting[$keyDate][$oneNetwork['ID']]['impressions'] > 0)
		      {
			$startCounting = true;
			break;
		      }
		  }
	      }
	  }
		
	if($startCounting) 
	  {
	    echo "<category label='".date('m/d/Y',$i)."' />";
	    $customImpressions[] = isset($reporting[$keyDate]) && $reporting[$keyDate]['impressions'] ? $reporting[$keyDate]['impressions'] : 0;
	    $total = isset($reporting[$keyDate]) ? $reporting[$keyDate]['impressions'] : 0;

	    foreach ($allNetworksGlobal as $oneNetwork)
	      {
		$networkImpressions[$oneNetwork['ID']]['impressions'][] = 
		  isset($networkReporting[$keyDate][$oneNetwork['ID']]['impressions']) ? $networkReporting[$keyDate][$oneNetwork['ID']]['impressions'] : 0;

		if(isset($networkReporting[$keyDate][$oneNetwork['ID']]['impressions'])) {
		  $total += $networkReporting[$keyDate][$oneNetwork['ID']]['impressions'];
		}
	      }

	    $totalImpressions[] = $total ? $total : 0;
	  }
		
	if(++$counter > 1000)
	  break;
      }

    echo '</categories>	<dataset seriesName=\'Total\' color=\'006600\' anchorBorderColor=\'006600\' anchorBgColor=\'006600\'>';

    foreach($totalImpressions as $impression)
      {
	echo "<set value='$impression' />";
      }
    echo '</dataset>';

    /*
    echo '<dataset seriesName=\'Custom\' color=\'cc0000\' anchorBorderColor=\'cc0000\' anchorBgColor=\'cc0000\'>';
	
    foreach($customImpressions as $impression)
      {
	echo "<set value='$impression' />";
      }
    echo '</dataset>';
    */
	
    $nColor = 0;
    $colors = array('3333cc','33ffff','ff33ff','ff9933','ffff00','999999');
    foreach ($allNetworksGlobal as $oneNetwork)
      {
	if(isset($networkImpressions[$oneNetwork['ID']]) && array_sum($networkImpressions[$oneNetwork['ID']]['impressions']) > 0)
	  {
	    echo '<dataset seriesName=\''.$oneNetwork['Name'].'\' color=\''.$colors[$nColor].'\' anchorBorderColor=\''.$colors[$nColor].'\' anchorBgColor=\''.$colors[$nColor++].'\'>';
	    foreach($networkImpressions[$oneNetwork['ID']]['impressions'] as $impression)
	      {
		echo "<set value='$impression' />";
	      }
	    echo '</dataset>';
	  }
      }
	
  }
 else
   {
     $customImpressions = array();
     $customClicks = array();
     $counter = 0;
     $startCounting = false;
     for($i=$startDate; $i<=$endDate; $i=strtotime('+1 day', $i))
       {
	 $keyDate = date('Y-m-d',$i);
		
	 if(!$startCounting)
	   {
	     if($reporting[$keyDate]['impressions'] > 0 || $reporting[$keyDate]['clicks'] > 0)
	       $startCounting = true;
	   }
		
	 if($startCounting)
	   {
	     echo "<category label='".date('m/d/Y',$i)."' />".'
			';
			
	     $customImpressions[] = $reporting[$keyDate]['impressions'] ? $reporting[$keyDate]['impressions'] : 0;
	     $customClicks[] = $reporting[$keyDate]['clicks'] ? $reporting[$keyDate]['clicks'] : 0;
	   }
		
	 if(++$counter > 1000)
	   break;
       }
	
     echo '</categories>
	
	<dataset seriesName=\'Impressions\' color=\'cc0000\' anchorBorderColor=\'cc0000\' anchorBgColor=\'cc0000\'>';
	
     foreach($customImpressions as $impression)
       {
	 echo "<set value='$impression' />";
       }
     echo '</dataset>
	
	<dataset seriesName=\'Clickthroughs\' color=\'2AD62A\' anchorBorderColor=\'2AD62A\' anchorBgColor=\'2AD62A\'>';
	
     foreach($customClicks as $click)
       {
	 echo "<set value='$click' />";
       }
     echo '</dataset>';
   }

echo '	<styles>                
		<definition>
                         
			<style name=\'CaptionFont\' type=\'font\' size=\'12\'/>
		</definition>
		<application>

			<apply toObject=\'CAPTION\' styles=\'CaptionFont\' />
			<apply toObject=\'SUBCAPTION\' styles=\'CaptionFont\' />
		</application>
	</styles>

</chart>';
?>
