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
require_once('includes/inc_login.php');
require('includes/inc_head.html');

if(isset($_GET['AppID'])) {
	$appId = $_GET['AppID'];
}

if(isset($_GET['EditAccount']) && $_GET['EditAccount'] == 0)
	$errMsg = '<div id="errMsg" class="errorMsg">
		<b>Error:</b> Your old password is not correct.
		</div><br/>';
else
	$errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
		<b>Error:</b> Please make sure you entered an email address, and if you changed your password, your new and confirm passwords match.
		</div><br/>';

$successMsg = '<div id="successMsg" class="successMsg">
			Your changes have been saved.
			</div>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/ico" href="favicon.ico"></link>
<link rel="shortcut icon" href="favicon.ico"></link>
<title>AdWhirl: Account</title>
<!-- CSS -->
<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<link rel="stylesheet" href="css/style_fade.css" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="scripts/wufoo.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/contentfader.js"></script>
<div id="appForm">
	<form name="selectForm" action="main" method="GET">
		<a href="main">Back to All Ads</a> <font color="#cccccc">|</font> 
		<?php 
			echo $email;
		?>
	</form>
</div>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
		
		<div id="wufoshell">
			<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
			<div id="container">
			
			<form id="form2" name="form2" class="wufoo " autocomplete="off"
				enctype="multipart/form-data" method="post" action="processEditAccount" onsubmit="return checkform(this);">
			
	<?php if(isset($_GET['EditAccount']) && $_GET['EditAccount'] == 1) echo $successMsg; ?>
			<?=$errMsg?>
			
			<div class="info">
				<h2>Edit Account Information</h2>
				<div>Here are the current account details.</div>
			</div>
			
			<ul>
				
				<li id="foli1" 		class="   ">
				<label class="desc" id="title1" for="Field1">
					Email
							<span id="req_1" class="req">*</span>
						</label>
				<div>
					<input id="email" 			name="email" 			type="text" 			class="field text medium" 			value="<?=$email?>" 			maxlength="255" 			tabindex="1" 						/>
						</div>
				</li>
			
				<li id="foli0" 		class="   ">
				<label class="desc" id="title1" for="Field1">
					<a href="#" onclick="showPass(); return false;">Change Password</a>
				</label>
				</li>
			
			<li id="foli2" 		class="   " style="display:none">
				<label class="desc" id="title2" for="Field2">
					Old Password
						</label>
				<div>
					<input id="oldpass" 			name="oldpass" 			type="password" 			class="field text medium" 			value="" 			maxlength="255" 			tabindex="2" 						/>
						</div>
				</li>
				
				<li id="foli3" 		class="   " style="display:none">
				<label class="desc" id="title2" for="Field2">
					New Password
						</label>
				<div>
					<input id="newpass" 			name="newpass" 			type="password" 			class="field text medium" 			value="" 			maxlength="255" 			tabindex="2" 						/>
						</div>
				</li>
				
				<li id="foli4" 		class="   " style="display:none">
				<label class="desc" id="title2" for="Field2">
					Confirm New Password
						</label>
				<div>
					<input id="confirmpass" 			name="confirmpass" 			type="password" 			class="field text medium" 			value="" 			maxlength="255" 			tabindex="2" 						/>
						</div>
				</li>
				
				<li id="foli21" 		class="   ">
	<label class="desc" id="title21" for="Field21">
			</label>
	<div class="column">
		<input id="allowemails" 	name="allowemails" 		type="checkbox" 		class="field checkbox"		tabindex="4" 				<?php if($_SESSION['AllowEmails']==1) echo 'checked'; ?>	/>
	<label class="choice" for="Field21">Notify me of AdWhirl updates</label>
		</div>
		<p class="instruct" id="instruct21"><small>We only send updates on new ad networks or new monetizing features for developers.</small></p>
	</li>
			
				
				<li class="buttons">
							<input id="saveForm" class="btTxt" type="submit" value="Save Changes" />
						</li>
			
				<li style="display:none">
					<label for="comment">Do Not Fill This Out</label>
					<textarea name="comment" id="comment" rows="1" cols="1"></textarea>
				</li>
			</ul>
			</form>
			
			</div><!--container-->
			<img id="bottom" src="<?=IMAGE_DIRECTORY?>bottom.png" alt="" />
		</div>
		<!-- wufoo shell-->
		
	</div>
</div>
<!--Main Lower Panel Close-->
  


<?php
require('includes/inc_tail.html');
?>

<script type="text/javascript" src="scripts/validate.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function showPass ()
{
  var form = document.getElementById("form2");
  var listElementStyle1 = document.getElementById("foli2").style;
  var listElementStyle2 = document.getElementById("foli3").style;
  var listElementStyle3 = document.getElementById("foli4").style;
  
  if(listElementStyle1.display == "none")
  {
	  listElementStyle1.display="block";
	  listElementStyle2.display="block";
	  listElementStyle3.display="block";
  }
  else
  {
  	  form.oldpass.value = "";
  	  form.newpass.value = "";
  	  form.confirmpass.value = "";
  	  listElementStyle1.display="none";
	  listElementStyle2.display="none";
	  listElementStyle3.display="none";
  }
}

function checkform ( form )
{
  var why = " ";
  if(form.email.value.length == 0)
  	why += "Email field is required.<br/>";
  why += checkEmail(form.email.value);
  
  // Didn't enter a password and email checks out
  if (why.length == 1) {
  	if(form.oldpass.value.length == 0 && form.newpass.value.length == 0 && form.confirmpass.value.length == 0)
  		return true;
  }
  
  if(form.oldpass.value.length != 0 || form.newpass.value.length != 0 || form.confirmpass.value.length != 0)
  {
	  why += checkPassword(form.newpass.value);
	  if(form.newpass.value != form.confirmpass.value)
	  	why += "Please make sure that your passwords match.<br/>";
	  if(form.oldpass.value.length == 0)
	  	why += "Old password is required to change password.<br/>";
	  if(form.newpass.value.length == 0 || form.confirmpass.value.length == 0)
	  	why += "Please make sure you enter a new password and confirm it.<br/>";
	  		
	  if(why.length == 1)
	  {
		return true;
	  }
  }
  
  var ErrorElement = document.getElementById("errMsg");
  <?php if(isset($_GET['EditAccount']) && $_GET['EditAccount'] == 1) { ?>
  var successListElementStyle = document.getElementById("successMsg").style;
  <?php } ?>
  
  ErrorElement.style.display="block";
  ErrorElement.innerHTML = "<b>Error:</b>" + why;
  
  <?php if(isset($_GET['EditAccount']) && $_GET['EditAccount'] == 1) { ?>
  successListElementStyle.display="none";	
  <?php } ?>
  
  ErrorElement.focus();

  return false;
}
//-->
</script>
