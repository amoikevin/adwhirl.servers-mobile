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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Forgot Password</title>
        <!-- CSS -->
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />

    <script type="text/javascript" src="scripts/validate.js">
    </script>
    <script language="JavaScript" type="text/javascript">
        <!--
        function checkform(form){
            var ErrorElement = document.getElementById("errMsg");
            
            var why = " ";
            if (form.email.value.length == 0) 
                why += "Email field is required.<br/>";
            why += checkEmail(form.email.value);
            
            if (why.length == 1) {
                sendRequest(form.email.value);
                ErrorElement.style.display = "none";
                return false;
            }
            
            ErrorElement.style.display = "block";
            ErrorElement.innerHTML = "<b>Error:</b>" + why;
            
            ErrorElement.focus();
            
            // ** END **
            return false;
        }
        
        // Get the HTTP Object
        function getHTTPObject(){
            if (window.ActiveXObject) 
                return new ActiveXObject("Microsoft.XMLHTTP");
            else 
                if (window.XMLHttpRequest) 
                    return new XMLHttpRequest();
                else {
                    alert("Your browser does not support AJAX.");
                    return null;
                }
        }
        
        // Turn ads on or off
        function sendRequest(email){
            httpObject = getHTTPObject();
            if (httpObject != null) {
                httpObject.open("GET", "processForgotPass?email=" + email, true);
                httpObject.send(null);
                httpObject.onreadystatechange = setOutput;
            }
        }
        
        // Change the value of the outputText field
        function setOutput(){
            if (httpObject.readyState == 4) {
                document.getElementById('successMsg').innerHTML = httpObject.responseText;
                document.getElementById('successMsg').style.display = "block";
            }
        }
        
        var httpObject = null;
        
        //-->
    </script>

<?
require_once("includes/inc_head.html");
?>

            <!--Top Panel Close--><!--Main Lower Panel-->
            <div id="mainlowerPan">
                <div id="lowerPan">
                    <div id="wufoshell">
                        <img id="top" src="http://adrollo-images.s3.amazonaws.com/top.png" alt="" />
                        <div id="container">
                            <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processForgotPass" onsubmit="return checkform(this);">
                                <div id="errMsg" class="errorMsg" style="display:none">
                                </div>
                                <div id="successMsg" class="successMsg" style="display:none">
                                </div>
                                <br/>
                                <div class="info">
                                    <h2>Forgot Password Request</h2>
                                    <div>
                                        Please enter your email address and we'll send you an email where you can reset your password.
                                    </div>
                                </div>
                                <ul>
                                    <li id="foli1" class="   ">
                                        <label class="desc" id="title1" for="Field1">
                                            Email<span id="req_1" class="req">*</span>
                                        </label>
                                        <div>
                                            <input id="email" name="email" type="text" class="field text medium" value="" maxlength="255" tabindex="1" />
                                        </div>
                                    </li>
                                    <li class="buttons">
                                        <input id="saveForm" class="btTxt" type="submit" value="Submit" />
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
                        <!--container--><img id="bottom" src="http://adrollo-images.s3.amazonaws.com/bottom.png" alt="" />
                    </div><!-- wufoo shell-->
                </div>
            </div><!--Main Lower Panel Close-->
<?
require_once("includes/inc_tail.html");
?>