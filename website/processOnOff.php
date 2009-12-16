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
require_once('includes/inc_login.php');

if($_GET['AppID'] == null)
  exit;
	
$appId = $_GET['AppID'];
$adsOn = $_GET['On'];

changeOnOff($_SESSION['UID'], $appId, $adsOn);

header('Location: main?AppID='.$appId);

function changeOnOff($uid, $appId, $adsOn)
{
  global $sdb;

  $memcache = new Memcache;
  $memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or memcache_error();
  $memcache->delete(MEMCACHE_APP_INFO . $appId);
  $memcache->close();

  $aa = array('adsOn' => $adsOn);
  if(!$sdb->put(DOMAIN_APPS, $appId, $aa, true)) {
    return false;
  }

  return true;
}
