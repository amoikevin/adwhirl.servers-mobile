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
require_once('includes/inc_global_no_session.php');
require_once ("includes/amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Util.php");

//We are subtracting a day
$cutoff = Amazon_SimpleDB_Util::encodeDate(microtime() - 86400000000);

$aaa = 'itemName()';
$sdb->select(DOMAIN_USERS_UNVERIFIED, $aaa, "where `createdAt` < '$cutoff'");
foreach($aaa as $aa) {
  $sdb->delete(DOMAIN_USERS_UNVERIFIED, $aa['itemName()']);
}

$aaa = 'itemName()';
$sdb->select(DOMAIN_USERS_FORGOT, $aaa, "where `createdAt` < '$cutoff'");
foreach($aaa as $aa) {
  $sdb->delete(DOMAIN_USERS_FORGOT, $aa['itemName()']);
}

?>
