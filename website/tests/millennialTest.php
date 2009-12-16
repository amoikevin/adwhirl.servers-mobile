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
/*--------------------------------------------------------------*/
/* Millennial Media PHP Ad Coding, v.7.4.20                     */
/* Copyright Millennial Media, Inc. 2006                        */
/*                                                              */
/* The following code requires PHP >= 4.3.0 and                 */
/* allow_url_fopen 1 set in php.ini file.                       */
/*                                                              */
/* NOTE:                                                        */
/* It is recommended that you lower the default_socket_timeout  */
/* value in the php.ini file to 5 seconds.                      */
/* This will prevent network connectivity from affecting        */
/* page loading.                                                */
/*--------------------------------------------------------------*/

/*------- Publisher Specific Section -------*/
$mm_placementid = 8470;
$mm_adserver = "mydas.millennialmedia.com";

/* The default response will be echo'd on the page     */
/* if no Ad is returned, so any valid WML/XHTML string */
/* is acceptable.                                      */
$mm_default_response = "";

/*------------------------------------------*/

/*----------- BEGIN AD INITIALIZATION ----------*/
/*----- PLEASE DO NOT EDIT BELOW THIS LINE -----*/
$mm_id = "NONE";
$mm_ua = "NONE";
@$mm_ip = $_SERVER['REMOTE_ADDR'];

if (isset($_SERVER['HTTP_USER_AGENT'] )){
     $mm_ua = $_SERVER['HTTP_USER_AGENT'];
}

if (isset($_SERVER['HTTP_X_UP_SUBNO'])) {
          $mm_id = $_SERVER['HTTP_X_UP_SUBNO'];
} elseif (isset($_SERVER['HTTP_XID'])) {
          $mm_id = $_SERVER['HTTP_XID'];
} elseif (isset($_SERVER['HTTP_CLIENTID'])) {
          $mm_id = $_SERVER['HTTP_CLIENTID'];
} else {
          $mm_id = $_SERVER['REMOTE_ADDR'];
}

$mm_url = "http://$mm_adserver/getAd.php5?apid=$mm_placementid&auid="
          . urlencode($mm_id) . "&uip=" . urlencode($mm_ip) . "&ua="
          . urlencode($mm_ua);
echo $mm_url;
/*------------ END AD INITIALIZATION -----------*/
?>

<?php
/* Place this code block where you want the ad to appear */
/*------- Reusable Ad Call -------*/
@$mm_response = file_get_contents($mm_url);
echo $mm_response != FALSE ? $mm_response : $mm_default_response;
$components = explode("\"", $mm_response);

//print_r($components);
echo "img_url: ".$components[3];
echo "click_url: ".$components[1];
/*--------- End Ad Call ----------*/
?>

