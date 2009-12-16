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
<link rel="icon" type="image/ico" href="favicon.ico"></link>
<link rel="shortcut icon" href="favicon.ico"></link>
<title>AdWhirl: Developer Login</title>
<!-- CSS -->
<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="scripts/wufoo.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<?php
require_once('includes/inc_global.php');
require_once('includes/general_functions.php');

if(isset($_SESSION['UID']))
{
	header('Location: main');
	exit;
}

require('includes/inc_head.html');

$errMsg = '<div id="errMsg" class="errorMsg" style="display:none">
		</div><br/>';
?>

<!--Main Lower Panel-->
<div id="mainlowerPan">
<div id="wufoshell">
<img id="top" src="<?=IMAGE_DIRECTORY?>top.png" alt="" />
<div id="container">

<form id="form2" name="form2" class="wufoo " autocomplete="off"
	enctype="multipart/form-data" method="post" action="processLogin" onsubmit="return checkform(this);">

<?=$errMsg?>

<div class="info">
	<h2>Login or <a href="developers">Register</a></h2>
</div>

<ul>
		
	
<li id="foli11" 		class="   ">
	<label class="desc" id="title11" for="Field11">
		Email
				<span id="req_11" class="req">*</span>
			</label>
	<div>
		<input id="email" 			name="email" 			type="text" 			class="field text medium" 			value="" 			maxlength="255" 			tabindex="1" 			/> 
	</div>
	</li>


<li id="foli18" 		class="   ">
	<label class="desc" id="title18" for="Field18">
		Password
				<span id="req_18" class="req">*</span>
			</label>
	<div>
		<input id="password" 			name="password" 			type="password" 			class="field text medium" 			value="" 			maxlength="255" 			tabindex="2" 						/>
			</div>
	</li>


         </li>
	
	<li class="buttons">
				<input id="saveForm" class="btTxt" type="submit" value="Submit" />
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
function checkform ( form )
{
  var why = " ";
  if(form.email.value.length == 0 || form.password.value.length == 0)
  	why += "Not all required fields are filled out.<br/>";
  why += checkEmail(form.email.value);
  why += checkPassword(form.password.value);
  if(form.password.value != form.confirmpassword.value)
  	why += "Please make sure that your passwords match.<br/>";
  if(!(form.agree.checked))
  	why += "Please agree to the terms of service.<br/>";
  
  // ** START **
  if (why.length == 1) {
    return true;
  }
  
  var ErrorElement = document.getElementById("errMsg");
  
  ErrorElement.style.display="block";
  ErrorElement.innerHTML = "<b>Error:</b>" + why;
  
  // ** END **
  return false;
}
//-->
</script>
