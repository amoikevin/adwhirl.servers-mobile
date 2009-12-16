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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Confirm Account</title>
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
   <?php        
        require ('includes/inc_head.html');
        
        ?>
        <!--Main Lower Panel-->
        <div id="mainlowerPan">
            <div id="lowerPan">
                <br/>
    <?php if (isset($_GET['err']) && $_GET['err'] == 1) { ?>
                <div id="container" style="border:1px solid #66ccff;">
                    <div style="width: 90%; height:500px; margin-left: auto; margin-right: auto; padding:20px;">
                        <br/>
                        <h2>Error during registration:</h2>
                        <br>
                        <strong>Sorry!</strong>
                        There seems to be an error with the registered email address (it looks like it already exists).
                        <br/>
                        Please try registering again with another email address, or email us at <a href="mailto:support@adwhirl.com">support@adwhirl.com</a>
                        and we'll help out ASAP!
                        <?php } else { ?>
                        <div id="container" style="border:1px solid #66ccff;">
                            <div style="width: 90%; height:500px; margin-left: auto; margin-right: auto; padding:20px;">
                                <br/>
                                <h2>
                                    <font color="#66ccff">
                                        AdWhirl
                                    </font>
                                    Email Confirmation:
                                </h2>
                                <br>
                                <strong>Thanks!</strong>
                                We're excited to have you try out AdWhirl, and we're positive that you'll agree that it really is
                                <br/>
                                the <strong>best iPhone ad solution</strong>
                                out there!
                                One final step - we will send an email in a few moments to
                                <br/>
                                <br/>
                                <font color="Gray" size="+2">
                                    <i><?= $_GET['em']?></i>
                                </font>
                                <br/>
                                <br/>
                                asking that you <strong>verify your email address</strong>. Just click on the link in the email and you'll be good to go!
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                If you're having trouble finding the email:
                                <br/>
                                <br/>
                                <ul id="navlist" style="line-height: 22px;">
                                    <li>
                                        Check your bulk or junk mail folder
                                    </li>
                                    <li>
                                        Double check that the email address you registered with is the correct one.
                                    </li>
                                    <li>
                                        If you're still having trouble registering, please email <a href="mailto:support@adwhirl.com">support@adwhirl.com</a>
                                        and we'll help out ASAP!
                                    </li>
                                </ul>
                                <br/>
                                <br/>
                                Best,
                                <br/>
                                <strong>AdWhirl Team</strong>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Main Lower Panel Close-->
                <?php 
                require ('includes/inc_tail.html');
                ?>
