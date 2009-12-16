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
require_once('includes/inc_global.php');
require_once('includes/general_functions.php');

if($_POST['email'] == null || $_POST['password'] == null || $_POST['confirmpassword'] == null)
	exit;
	
// always allow emails now, they can change from account settings
$allowEmails = 1;//isset($_POST['allowemails']) ? 1 : 0;

$uid = registerUser($_POST['email'], $_POST['password'], $allowEmails);
if($uid == null)
{
	header('Location: preconfirmation?err=1');
	exit;
}

$activationLink = 'http://'.$_SERVER['HTTP_HOST'].'/confirmRegistration?ver='.$uid;

$to      = $_POST['email'];
$subject = 'AdWhirl Account Registration';
$message = 'Hello iPhone Developer,

Thanks for registering with AdWhirl. Click on the link below to validate your email address and activate your account.

'.$activationLink.'

We\'re sure you\'ll see that AdWhirl\'s ad solution is the best available, as you can simultaneously run as many or as few
ad networks as you\'d like, and you\'re also able to create and run your own custom ads whenever you want to - all for free.

We\'ve spent a lot of time perfecting the library and giving you, the developer, as many hooks as possible to optimize
revenue and track analytics, but we\'d love to hear from you with suggestions, feedback, anything!

Also, if you have any questions or are having trouble registering, please email us at support@adwhirl.com with the issue.

Best,
AdWhirl Team
';
$headers = 'From: noreply@adwhirl.com' . "\r\n" .
    'Reply-To: noreply@adwhirl.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

header('Location: preconfirmation?em='.urlencode($to));
?>
