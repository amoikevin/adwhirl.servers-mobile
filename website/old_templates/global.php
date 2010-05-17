<?php
  /*
   -----------------------------------------------------------------------
   Copyright 2009-2010 AdMob, Inc.
 
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
<?

function outputHeader($title) {
  global $uid;
  global $email;

echo<<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="Description" content="Dynamically change between ad networks for your iPhone/Android apps and create and display custom ads to cross-promote your own apps.">
  <link rel="icon" type="image/ico" href="favicon.ico" />
  <link rel="shortcut icon" href="favicon.ico" />
  <title>$title</title>

  <!-- CSS -->
  <link href="css/styles.css" rel="stylesheet" type="text/css" />

  <!-- JavaScript -->
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

  <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
    try {
      var pageTracker = _gat._getTracker("UA-11870556-1");
      pageTracker._trackPageview();
    } catch(err) {}
  </script>
 </head>
 <body>
  <div id="header">
   <a href="/main"><img src="http://adrollo-images.s3.amazonaws.com/logo_adwhirl.gif" alt="AdWhirl" /></a>
END;

if(isset($uid)) { 
echo<<<END
   <div id="account">
    $email | 
    <a href="account">Account Settings</a> | 
    <a href="help">Help</a> |
    <a href="http://groups.google.com/group/adwhirl-users?pli=1" target="_blank">Forum</a> |
    <a href="logout">Logout</a>
   </div>
END;
}

echo<<<END
  </div>
END;
}

function outputFooter() {
echo<<<END
  <div id="footer">
   <ul>
    <li><a href="#">&copy; 2009 AdMob, Inc</a> |</li>
    <li><a href="tos">Terms of Service</a> |</li>
    <li><a href="privacy">Privacy Policy</a> |</li>
    <li><a href="http://www.admob.com/home/contact">Contact Us</a></li>
   </ul>
  </div>
 </body>
</html>
END;
}
