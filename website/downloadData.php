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
require_once('includes/reporting_functions.php');
require_once('includes/inc_login.php');

$appId = isset($_GET['AppID']) ? $_GET['AppID'] : null;
$startDate = isset($_GET['txtStartDate']) ? strtotime($_GET['txtStartDate']) : null;
$endDate = isset($_GET['txtEndDate']) ? strtotime($_GET['txtEndDate']) : null;
$chartType = isset($_GET['viewtype']) ? $_GET['viewtype'] : null;
$isAllApps = isset($_GET['AppId']) ? $_GET['allApps'] == 1 : null;

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".date('Y-m-d',$startDate)."_to_".date('Y-m-d',$endDate).".csv\"");


if($chartType == REPORT_ALL || $chartType == REPORT_CUSTOM_ALL)
{
	if($isAllApps)
		$customNids = getUserNetworksCustom($uid);
	else
		$customNids = getAppNetworksCustom(SECRET_PASS, $appId);
	$customNidsArray = array();
	foreach($customNids as $oneCustom)
	{
		$customNidsArray[] = "'".$oneCustom['NID']."'";
	}
	$reporting = getReportingCustomAggregate($uid, $appId, implode(',',$customNidsArray), date('Y-m-d',$startDate), date('Y-m-d',$endDate), true);
}
else 
{
	$reporting = getReportingCustomAggregate($uid, $appId, $chartType, date('Y-m-d',$startDate), date('Y-m-d',$endDate));
}

if($chartType == REPORT_ALL)
{
	echo 'Date,Custom,';
	foreach ($allNetworksGlobal as $oneNetwork)
	{
		echo $oneNetwork['Name'];
		echo ',';
	}
	echo "\n";
	
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
			if(isset($reporting[$keyDate]) && $reporting[$keyDate]['impressions'] > 0)
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
			echo date('m/d/Y',$i) . ',';
			
			echo isset($reporting[$keyDate]['impressions']) ? $reporting[$keyDate]['impressions'] : 0;
			echo ',';
			
			foreach ($allNetworksGlobal as $oneNetwork)
			{
				echo isset($networkReporting[$keyDate][$oneNetwork['ID']]['impressions']) ? $networkReporting[$keyDate][$oneNetwork['ID']]['impressions'] : 0;
				echo ',';
			}
		}
		
		if(++$counter > 1000)
			break;
			
		echo "\n";
	}
	
}
else
{
	echo "Date,Impressions,Clicks,\n";

	$customImpressions = array();
	$customClicks = array();
	$counter = 0;
	$startCounting = false;
	for($i=$startDate; $i<=$endDate; $i=strtotime('+1 day', $i))
	{
		$keyDate = date('Y-m-d',$i);
		
		if(!$startCounting)
		{
			if(isset($reporting['keyDate']) && ($reporting[$keyDate]['impressions'] > 0 || $reporting[$keyDate]['clicks'] > 0))
				$startCounting = true;
		}
		
		if($startCounting)
		{
			echo date('m/d/Y',$i) . ',';
			
			echo isset($reporting[$keyDate]['impressions']) ? $reporting[$keyDate]['impressions'] : 0;
			echo ',';
			echo isset($reporting[$keyDate]['clicks']) ? $reporting[$keyDate]['clicks'] : 0;
			echo ",\n";
		}
		
		if(++$counter > 1000)
			break;
	}
}
