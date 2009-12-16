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

//For AWS SimpleDB
define('AWS_ACCESS_KEY_ID', 'CHANGEME');
define('AWS_SECRET_ACCESS_KEY', 'CHANGEME');  

//For AWS S3
define('awsAccessKey', 'CHANGEME');
define('awsSecretKey', 'CHANGEME');

/*
 define('awsAccessKey', AWS_ACCESS_KEY_ID);
 define('awsSecretKey', AWS_SECRET_ACCESS_KEY);
*/

require_once 'includes/SDB.php';

$sdb = new SDB();

define("MEMCACHE_HOST", "localhost");
define("MEMCACHE_PORT", 11211);

define("IMAGE_DIRECTORY", 'http://adrollo-images.s3.amazonaws.com/');

define("PASSWORD_SALT", "CHANGEME");
define("KEY_SPLIT", "|;|");

define("MAX_PRIORITY", 10000);
define("SECRET_PASS", "CHANGEME");

//CHANGEME
define('AMAZON_PREFIX','http://s3.amazonaws.com/');
define('AMAZON_BUCKET_CUSTOM','adrollo-custom-images');

define('MEMCACHE_OPTIMIZE_RATIONS','optimizerations');
define('MEMCACHE_APPNETWORKS','appnetworks');
define('MEMCACHE_APPNETWORKS_CUSTOM','appnetworkscustom');
define('MEMCACHE_APPNETWORKS_ALL','appnetworksall');
define('MEMCACHE_APP_INFO','appinfo');
define('MEMCACHE_CUSTOMIMPRESSION','customimp');
define('MEMCACHE_CUSTOMIMPRESSION_SET','customimpset');
define('MEMCACHE_CUSTOMIMPRESSION_LIST','customimplist');
define('MEMCACHE_CUSTOMCLICK','customclick');
define('MEMCACHE_CUSTOMCLICK_SET','customclickset');
define('MEMCACHE_CUSTOMCLICK_LIST','customclicklist');
define('MEMCACHE_EXTERNALIMPRESSION','extimp');
define('MEMCACHE_EXTERNALIMPRESSION_SET','extimpset');
define('MEMCACHE_EXTERNALIMPRESSION_LIST','extimplist');
define('MEMCACHE_EXTERNALCLICK','extclick');
define('MEMCACHE_EXTERNALCLICK_SET','extclickset');
define('MEMCACHE_EXTERNALCLICK_LIST','extclicklist');
define('MEMCACHE_LINKSHARE_EXMET','lsexmet');

define('AD_WEBSITE', 1);
define('AD_APP', 2);
define('AD_CALL', 3);
define('AD_VIDEO', 4);
define('AD_AUDIO', 5);
define('AD_ITUNES', 6);
define('AD_MAP', 7);

define('LAUNCH_TYPE_SAFARI', 1);
define('LAUNCH_TYPE_CANVAS', 2);
define('LAUNCH_TYPE_LS', 3);

/*
 1 - banner ad redirects outside
 2 - icon plus text redirects outside
 3 - banner ad webkit view
 4 - icon plus text webkit view
*/
define('CUSTOM_BANNER', 1);
define('CUSTOM_ICON', 2);
define('CUSTOM_BANNER_FULL', 3);
define('CUSTOM_ICON_FULL', 4);
define('CUSTOM_SEARCH', 5);

//$banner_animation_enum = array("flip_from_left" => 0, "flip_from_right" => 1, "curl_up" => 2, "curl_down" => 3, "slide_from_left" => 4, "slide_from_right" => 5, "fade_in" => 6, "random" => 7);
define('ANIMATION_NONE', 0);
define('ANIMATION_FLIP_FROM_LEFT', 1);
define('ANIMATION_FLIP_FROM_RIGHT', 2);
define('ANIMATION_CURL_UP', 3);
define('ANIMATION_CURL_DOWN', 4);
define('ANIMATION_SLIDE_FROM_LEFT', 5);
define('ANIMATION_SLIDE_FROM_RIGHT', 6);
define('ANIMATION_FADE_IN', 7);
define('ANIMATION_RANDOM', 8);

define('WEBVIEW_ANIMATION_NONE', 0);
define('WEBVIEW_ANIMATION_MODAL', 1);
define('WEBVIEW_ANIMATION_CURL_DOWN', 2);
define('WEBVIEW_ANIMATION_FADE_IN', 3);
define('WEBVIEW_ANIMATION_RANDOM', 4);

$allNetworksGlobal = array(
		
			   array(	'ID' => 1,
					'Name' => 'AdMob',
					'Website' => 'http://www.admob.com',
					'MobilePrefix' => 'admob_',
					'Show' => true,
					'IsServer' => false),
			  	
			   array(	'ID' => 2,
					'Name' => 'JumpTap',
					'Website' => 'http://www.jumptap.com',
					'MobilePrefix' => 'jumptap_',
					'Show' => true,
					'IsServer' => false),


			   array(	'ID' => 13,
					'Name' => '4thScreen',
					'Website' => 'http://www.4th-screen.com/',
					'MobilePrefix' => 'adrollo_',
					'Show' => false,
					'IsServer' => true,
					'EmailWhiteList' => array('support@adwhirl.com')),
			  	

			   array(	'ID' => 4,
					'Name' => 'Medialets',
					'Website' => 'http://www.medialets.com',
					'MobilePrefix' => 'medialets_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 5,
					'Name' => 'LiveRail',
					'Website' => 'http://www.liverail.com',
					'MobilePrefix' => 'liverail_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 6,
					'Name' => 'Millennial Media',
					'Website' => 'http://www.millennialmedia.com',
					'MobilePrefix' => 'millennial_',
					'Show' => true,
					'IsServer' => false,
					'EmailWhiteList' => array('support@adwhirl.com')),
			  	
			   array(	'ID' => 7,
					'Name' => 'Greystripe',
					'Website' => 'http://www.greystripe.com',
					'MobilePrefix' => 'greystripe_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 8,
					'Name' => 'Quattro',
					'Website' => 'http://www.quattrowireless.com/affiliates/adwhirl?promocode=aff-adwhirl-ja',
					'MobilePrefix' => 'quattro_',
					'Show' => true,
					'IsServer' => false),
			  	
			   array(	'ID' => 9,
					'Name' => 'Custom',
					'Website' => 'http://www.adwhirl.com',
					'MobilePrefix' => 'custom_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 10,
					'Name' => 'AdWhirl',
					'Website' => 'http://www.adwhirl.com',
					'MobilePrefix' => 'adrollo_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 11,
					'Name' => 'MobClix',
					'Website' => 'http://www.mobclix.com',
					'MobilePrefix' => 'mobclix_',
					'Show' => false,
					'IsServer' => false),
			  	
			   array(	'ID' => 12,
					'Name' => 'MDotM',
					'Website' => 'http://www.mdotm.com/page/register/',
					'MobilePrefix' => 'adrollo_',
					'Show' => false,
					'IsServer' => true,
			                'EmailWhiteList' => array('support@adwhirl.com')),
			  	


			   array(	'ID' => 3,
					'Name' => 'Videoegg',
					'Website' => 'http://developer.videoegg.com/',
					'MobilePrefix' => 'videoegg_',
					'Show' => true,
					'IsServer' => false),
			  	
			   array(	'ID' => 14,
					'Name' => 'Google AdSense',
					'Website' => 'http://www.google.com/ads/mobileapps/',
					'MobilePrefix' => 'google_adsense_',
					'Show' => false,
					'IsServer' => false,
					'EmailWhiteList' => array('support@adwhirl.com')),
			  		
			   array(	'ID' => 15,
					'Name' => 'Google DoubleClick',
					'Website' => 'http://www.google.com/ads/mobileapps/',
					'MobilePrefix' => 'google_doubleclick_',
					'Show' => false,
					'IsServer' => false,
					'EmailWhiteList' => array('support@adwhirl.com')),
			  	
			   array(	'ID' => 16,
					'Name' => 'Generic Notifications',
					'Website' => 'genericNotification',
					'MobilePrefix' => 'generic_',
					'Show' => true,
					'IsServer' => false),
			  					  	
			   );
			  	
$allAppCategories = array(
			  'Book','Business','Education','Entertainment','Finance','Games','Healthcare and Fitness','Lifestyle','Music','Navigation',
			  'News','Photography','Productivity','Reference','Search Tools','Social Networking','Sports','Travel','Utilities','Weather'
			  );

function memcache_error()
{
  //Do nothing for now
}
