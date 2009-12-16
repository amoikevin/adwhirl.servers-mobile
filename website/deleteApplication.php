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
require_once ('includes/inc_global.php');
require_once ('includes/app_functions_get.php');
require_once ('includes/inc_login.php');
require ('includes/inc_head.html');

$appId = $_GET['AppID'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Delete Application</title>
        <!-- CSS -->
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />
        <link rel="stylesheet" href="css/style_fade.css" type="text/css" />
        <!-- JavaScript -->
        <script type="text/javascript" src="scripts/wufoo.js">
        </script>
        <script type="text/javascript" src="scripts/jquery.js">
        </script>
        <script type="text/javascript" src="scripts/contentfader.js">
        </script>
        <div id="appForm">
            <form name="selectForm" action="main" method="GET">
                <a href="main?AppID=<?=$appId?>">Back to All Ads</a>
                <font color="#cccccc">
                    |
                </font>
                <?php 
                $info = getAppInfo($appId);
                echo $info['Name'];
                ?>
            </form>
        </div>
        <!--Main Lower Panel-->
        <div id="mainlowerPan">
            <div id="lowerPan">
                <div id="wufoshell">
                    <img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
                    <div id="container">
                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processDeleteApplication">
                            <input type="hidden" name="appid" value="<?=$appId?>">
                            <div class="info">
                                <h2>
                                    <font color="Red">
                                        Delete Application <?= $info['Name']?>
                                    </font>
                                </h2>
                                <div>
                                    Please confirm you want to delete <?= $info['Name']?>.  <strong>You cannot undo this action.</strong>
                                </div>
                                <div>
                                    <img src="<?=IMAGE_DIRECTORY?>warning.gif"/>
                                </div>
                            </div>
                            <ul>
                                <li class="buttons">
                                    <input id="saveForm" class="btTxt" type="submit" value="Yes, Delete" /><strong>OR</strong>
                                    <a href="main?AppID=<?=$appId?>">Cancel</a>
                                </li>
                                <li style="display:none">
                                    <label for="comment">
                                        Do Not Fill This Out
                                    </label>
                                    <textarea name="comment" id="comment" rows="1" cols="1">
                                    </textarea>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <!--container--><img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
                </div><!-- wufoo shell-->
            </div>
        </div><!--Main Lower Panel Close-->
        <?php 
        require ('includes/inc_tail.html');
        ?>
