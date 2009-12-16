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
<?

if(isset($_GET['AppID'])) {
  $appFormLeft_appId = $_GET['AppID'];
 }
 else {
   $appFormLeft_appId = null;
 }

$appFormLeft_allApps = getApps($_SESSION['UID']);

$appFormLeft_selectText = '';
$appFormLeft_found = false;
usort($appFormLeft_allApps, 'cmp');
foreach($appFormLeft_allApps as $oneApp)
{
  if($oneApp['AppID'] == $appFormLeft_appId)
    {
      $selected = 'SELECTED';
     }
  else 
    $selected = '';
		
  $appFormLeft_selectText .= '<option value="'.$oneApp['AppID'].'" '. $selected .'>'.$oneApp['Name'].'</option>';
}
?>
							  <div id="appFormLeft">
							  <form name="selectForm" method="GET">
							  Select Application: 
							  <select name="AppID" onchange="this.form.submit()">
							  <?=$appFormLeft_selectText?>
							  </select>
							  <noscript><input type="submit" class="formbutton" value="Go" /></noscript>
							  <font color="#cccccc">|</font> 
							  <small><a href="addApplication?AppID=<?=$appFormLeft_appId?>">Add New App</a></small>
							  </form>
							  </div>
