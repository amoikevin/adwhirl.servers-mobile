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
require_once ('includes/general_functions.php');

if (isset($_SESSION['UID'])) {
  header('Location: main');
  exit;
 }

$errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
        		</div><br/>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" type="image/ico" href="favicon.ico">
        </link>
        <link rel="shortcut icon" href="favicon.ico">
        </link>
        <title>AdWhirl: Developers Signup Now</title>
        <!-- CSS -->
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />
        <!-- JavaScript -->
        <script type="text/javascript" src="scripts/wufoo.js">
        </script>
        <script type="text/javascript" src="scripts/jquery.js">
        </script>

<?
require ('includes/inc_head.html');
?>

        <!--Main Lower Panel-->
        <div id="mainlowerPan">
            <div id="lowerPan">
                  <div id="wufoshell">
                    <img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
                    <div id="container">
                        <form id="form2" name="form2" class="wufoo " autocomplete="off" enctype="multipart/form-data" method="post" action="processRegistration" onsubmit="return checkform(this);">
                            <?= $errMsg?>
                            <div class="info">
                                <h2>Get Started or <a href="login">Login</a></h2>
                            </div>
                            <ul>
                                <li id="foli11" class="   ">
                                    <label class="desc" id="title11" for="Field11">
                                        Email<span id="req_11" class="req">*</span>
                                    </label>
                                    <div>
                                        <input id="email" name="email" type="text" class="field text medium" value="" maxlength="255" tabindex="1" />
                                    </div>
                                </li>
                                <li id="foli18" class="   ">
                                    <label class="desc" id="title18" for="Field18">
                                        Password<span id="req_18" class="req">*</span>
                                    </label>
                                    <div>
                                        <input id="password" name="password" type="password" class="field text medium" value="" maxlength="255" tabindex="2" />
                                    </div>
                                </li>
                                <li id="foli19" class="   ">
                                    <label class="desc" id="title19" for="Field19">
                                        Confirm Password<span id="req_19" class="req">*</span>
                                    </label>
                                    <div>
                                        <input id="confirmpassword" name="confirmpassword" type="password" class="field text medium" value="" maxlength="255" tabindex="3" />
                                    </div>
                                </li>
                                <li id="foli13" class="   ">
                                    <span class="column2">
                                        <textarea name="tos" cols="50" rows="5" readonly="readonly">AdMob Terms Of Service
                                        PLEASE READ THIS USER AGREEMENT ("AGREEMENT") CAREFULLY BEFORE USING THE SERVICES OFFERED BY ADWHIRL, INC. ("COMPANY"). BY CLICKING THE "SUBMIT" BOX, YOU AGREE TO BECOME BOUND BY THE TERMS AND CONDITIONS OF THIS AGREEMENT. IF YOU DO NOT AGREE TO ALL THE TERMS AND CONDITIONS OF THIS AGREEMENT, CLICK ON THE "CANCEL" BUTTON AND YOU WILL NOT HAVE ANY RIGHT TO USE THE SERVICES OFFERED BY COMPANY. COMPANY'S ACCEPTANCE IS EXPRESSLY CONDITIONED UPON YOUR ASSENT TO ALL THE TERMS AND CONDITIONS OF THIS AGREEMENT, TO THE EXCLUSION OF ALL OTHER TERMS; IF THESE TERMS AND CONDITIONS ARE CONSIDERED AN OFFER BY COMPANY, ACCEPTANCE IS EXPRESSLY LIMITED TO THESE TERMS. 
                                        DISCLAIMERS 
                                        User acknowledges and agrees that Company has no special relationship with or fiduciary duty to User and that Company has no control over, and no duty to take any action regarding: which users gains access to the Site or Services; what Content User accesses or receives via the Site or Services; what Content other Users may make available, publish or promote in connection with the Services; what effects any Content may have on User or its users or customers; how User or its users or customers may interpret, view or use the Content; what actions User or its users or customers may take as a result of having been exposed to the Content, or whether Content is being displayed properly in connection with the Services.  
                                        Further, (i) if User is a publisher, User specifically acknowledges and agrees that Company has no control over (and is merely a passive conduit with respect to) any Content that may be submitted or published by any advertiser, and that User is solely responsible (and assumes all liability and risk) for determining whether or not such Content is appropriate or acceptable to User, and (ii) if User is an advertiser, User specifically acknowledges and agrees that Company has no control over any Content that may be available or published on any publisher website (or otherwise), and that User is solely responsible (and assumes all liability and risk) for determining whether or not such Content is appropriate or acceptable to User. 
                                        User releases Company from all liability in any way relating to User's acquisition (or failure to acquire), provision, use or other activity with respect to Content in connection with the Site or Services. The Site may contain, or direct User to sites containing, information that some people may find offensive or inappropriate. Company makes no representations concerning any Content contained in or accessed through the Site or Services, and Company will not be responsible or liable for the accuracy, copyright compliance, legality or decency of material contained in or accessed through the Site or Services. Company makes no guarantee regarding the level of impressions of or clicks on any Advertisement, or the timing of delivery of such impressions and/or clicks.  
                                        THE SERVICES, CONTENT AND SITE ARE PROVIDED ON AN "AS IS" BASIS, WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. COMPANY DOES NOT WARRANT THE RESULTS OF USE OF THE SERVICES, INCLUDING, WITHOUT LIMITATION, THE RESULTS OF ANY ADVERTISING CAMPAIGN, AND USER ASSUMES ALL RISK AND RESPONSIBILITY WITH RESPECT THERETO. SOME STATES DO NOT ALLOW LIMITATIONS ON HOW LONG AN IMPLIED WARRANTY LASTS, SO THE ABOVE LIMITATIONS MAY NOT APPLY TO USER. 
                                        Electronic Communications Privacy Act Notice (18 USC 2701-2711): COMPANY MAKES NO GUARANTY OF CONFIDENTIALITY OR PRIVACY OF ANY COMMUNICATION OR INFORMATION TRANSMITTED ON THE SITE OR ANY WEBSITE LINKED TO THE SITE OR THROUGH ANY USE OF THE SERVICES. Company will not be liable for the privacy of e-mail addresses, phone or communication device numbers, registration and identification information, disk space, communications, confidential or trade-secret information, or any other Content stored on its equipment and transmitted over networks accessed by the Site, or otherwise connected with User's use of the Site or Services.  
                                        REGISTRATION AND SECURITY. As a condition to using Services, User may be required to register with Company and select a password and enter User's email address ("Company User ID"). User shall provide Company with accurate, complete, and updated registration information. Failure to do so shall constitute a breach of this Agreement, which may result in immediate termination of User's account. User may not (i) select or use as a Company User ID a name of another person with the intent to impersonate that person; (ii) use as a Company User ID a name subject to any rights of a person other than User without appropriate authorization. Company reserves the right to refuse registration of, or cancel a Company User ID in its discretion. User shall be responsible for maintaining the confidentiality of User's Company password. 
                                        EQUIPMENT AND ANCILLARY SERVICES. User shall be responsible for obtaining and maintaining any equipment or ancillary services needed to connect to, access the Site or otherwise use the Services, including, without limitation, hardware devices, software, and other Internet, wireless, broadband, phone or other communication device connection services. User shall be responsible for ensuring that such equipment or ancillary services are compatible with the Site and any Services and User shall be responsible for all charges incurred in connection with all such equipment and ancillary services, including any fees charged for airtime usage and/or sending and receiving messages or related notifications.  
                                        INDEMNITY. User will indemnify and hold Company, its parents, subsidiaries, affiliates, officers and employees, harmless, including costs and attorneys' fees, from any claim or demand made by any third party due to or arising out of User's access to the Site, use of the Services, the violation of this Agreement by User, or the infringement by User, or any third party using the User's account, of any intellectual property or other right of any person or entity. 
                                        LIMITATION OF LIABILITY. IN NO EVENT SHALL COMPANY BE LIABLE WITH RESPECT TO THE SITE OR THE SERVICES (I) FOR ANY AMOUNT IN THE AGGREGATE IN EXCESS OF THE FEES PAID BY USER THEREFOR; OR (II) FOR ANY INDIRECT, INCIDENTAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES OF ANY KIND WHATSOEVER. SOME STATES DO NOT ALLOW THE EXCLUSION OR LIMITATION OF INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO THE ABOVE LIMITATIONS AND EXCLUSIONS MAY NOT APPLY TO USER.
                                        </p>
                                        TERMINATION. Either party may terminate the Services at any time by notifying the other party by any means. Company may also terminate or suspend any and all Services and access to the Site immediately, without prior notice or liability, if User breaches any of the terms or conditions of this Agreement. Any fees paid hereunder are non-refundable and non-cancelable. Upon termination of the User's account, User's right to use the Services will immediately cease and User will remove all Company code from User's Mobile Properties. All provisions of this Agreement which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, and limitations of liability.
                                    </textarea>
                                    </span>
                                </li>
                                <li id="foli21" class="   ">
                                    <label class="desc" id="title21" for="Field21">
                                    </label>
                                    <div class="column">
                                        <input id="agree" name="agree" type="checkbox" class="field checkbox" value="" tabindex="4" />
                                        <label class="choice" for="Field21">
                                            I agree to the following
                                            <br/>
                                            <small>
                                                - I accept the <a href="tos" target="_blank">Terms of Service</a>
                                            </small>
                                            <br/>
                                            <!--	<small>- I may receive communications from AdMob and I understand that I can change my notification preferences at any time in My Account.</small>-->
                                        </label>
                                    </div>
                                </li>
                                <li id="foli13" class="section   ">
                                    <h3 id="title13">That's it!</h3>
                                    <div id="instruct13">
                                        No seriously, that's it. Now hit submit!
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
                    <!--container--><img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
                </div><!-- wufoo shell-->
            </div>
        </div><!--Main Lower Panel Close-->
        <?php 
        require ('includes/inc_tail.html');
        ?>
        <script type="text/javascript" src="scripts/validate.js">
        </script>
        <script language="JavaScript" type="text/javascript">
            <!--
            function checkform(form){
                var why = " ";
                if (form.email.value.length == 0 || form.password.value.length == 0) 
                    why += "Not all required fields are filled out.<br/>";
                why += checkEmail(form.email.value);
                why += checkPassword(form.password.value);
                if (form.password.value != form.confirmpassword.value) 
                    why += "Please make sure that your passwords match.<br/>";
                if (!(form.agree.checked)) 
                    why += "Please agree to the terms of service.<br/>";
                
                // ** START **
                if (why.length == 1) {
                    return true;
                }
                
                var ErrorElement = document.getElementById("errMsg");
                
                ErrorElement.style.display = "block";
                ErrorElement.innerHTML = "<b>Error:</b>" + why;
                
                // ** END **
                return false;
            }
            
            //-->
        </script>
