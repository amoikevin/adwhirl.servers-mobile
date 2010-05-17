
<?php

require_once('SDBObject.php');
require_once('UserUtil.php');

//$app = new App('2f606d3e3723102d840c2e1e0de86337');

//$networks = $app->getNetworks();
//var_dump($networks);

//$houseAds = $app->getHouseAds();
//var_dump($houseAds);

$user = UserUtil::getUser('jpincar@admob.com', 'PASSWORD_HERE');

var_dump($user);
