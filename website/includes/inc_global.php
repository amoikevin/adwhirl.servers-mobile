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
ini_set("session.gc_maxlifetime", "18000");
session_start();
header("Cache-control: private");

setcookie('adwhirlzeus', '1', time()+3600*24*7);

$uid = isset($_SESSION['UID']) ? $_SESSION['UID'] : null;
$email = isset($_SESSION['Email']) ? $_SESSION['Email'] : null;

require_once('includes/inc_global_no_session.php');
