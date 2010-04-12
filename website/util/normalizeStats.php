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

chdir('../');
require_once('includes/inc_global_no_session.php');
require_once('includes/SDB.php');

$current      = '2010-04-12';
$normalize    = 0.0190294241;

global $sdb;

$aaa = array('clicks', 'impressions');

$sdb->select(DOMAIN_STATS, $aaa, "where `dateTime` = '$current'");

foreach($aaa as $aa) {
  $sid = $aa['itemName()'];
  $clicks = $aa['clicks'];
  $impressions = $aa['impressions'];

  $normalized_clicks = ceil($clicks * $normalize);
  $normalized_impressions = ceil($impressions * $normalize);

  $bb = array('clicks' => $normalized_clicks,
	      'impressions' => $normalized_impressions);


  print "Normalizing <sid:$sid>, <$clicks, $impressions> to <$normalized_clicks, $normalized_impressions>\n"; 

  $sdb->put(DOMAIN_STATS, $sid, $bb, true);
}

?>
