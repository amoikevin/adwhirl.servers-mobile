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

$dir = getcwd();

chdir('../');
require_once('includes/inc_global_no_session.php');
require_once('includes/SDB.php');
chdir($dir);

$to = 'jpincar@admob.com,pfernandez@admob.com';

$today = date('Y-m-d');
$file = "adwhirl-emails_$today.csv";

$fh = fopen($file, 'w');

global $sdb;

$aaa = array('email', 'allowEmail');
$sdb->select(DOMAIN_USERS, $aaa, "");

fputcsv($fh, array('email'));
foreach($aaa as $aa) {
  $email = $aa['email'];

  fputcsv($fh, array($email));
}
fclose($fh);

$random_hash = md5(date('r', time())); 

$headers = 'From: noreply@adwhirl.com' . "\n" . 
  'Reply-To: noreply@adwhirl.com' . "\n" .
  'Content-Type: multipart/mixed; boundary="PHP-mixed-'.$random_hash.'"'; 

$attachment = chunk_split(base64_encode(file_get_contents($file))); 

$output[] = "--PHP-mixed-$random_hash";
$output[] = "Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"";

$output[] = "--PHP-alt-$random_hash";
$output[] = "Content-Type: text/plain; charset=\"iso-8859-1\"";
$output[] = "Content-Transfer-Encoding: 7bit";
$output[] = "";
$output[] = "AdWhirl Email List is attached.";
$output[] = "";
$output[] = "--PHP-alt-$random_hash";
$output[] = "Content-Type: text/html; charset=\"iso-8859-1\"";
$output[] = "Content-Transfer-Encoding: 7bit";
$output[] = "";
$output[] = "AdWhirl Email List is attached.<br />";
$output[] = "<br />";
$output[] = "--PHP-alt-$random_hash--";

$output[] = "--PHP-mixed-$random_hash";
$output[] = "Content-Type: text/csv; name=\"$file\"";
$output[] = "Content-Transfer-Encoding: base64";
$output[] = "Content-Disposition: attachment";
$output[] = "";
$output[] = "$attachment";
$output[] = "--PHP-mixed-$random_hash--";

$message = implode("\n", $output);

$today = date("F j, Y");
$subject = "AdWhirl Email List - " . $today; 

if($argc > 1) {
  mail($to, $subject, $message, $headers);
 }
 else {
   echo $headers."\n\n";
   echo $message."\n";
 }

unlink($file);

?>
