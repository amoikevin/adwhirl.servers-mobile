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
<title>AdWhirl: FAQ</title>
<!-- CSS -->
<link href="css/style_inside.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<link rel="stylesheet" href="css/style_fade.css" type="text/css" />


<!-- JavaScript -->
<script type="text/javascript" src="scripts/wufoo.js"></script>
<script type="text/javascript" src="scripts/jquery.js"> </script>
<script type="text/javascript">
$(document).ready(function() {									
	$("a[name^='faq-']").each(function() {
		$(this).click(function() {
			if( $("#" + this.name).is(':hidden') ) {
				$("#" + this.name).fadeIn('slow');
			} else {
				$("#" + this.name).fadeOut('slow');
			}			
			return false;
		});
	});
});
</script>

<style type="text/css">
.faq-answer {
display:none;
}

#qa	{
	display: block;
	margin:0;
	padding:4px 5px 2px 9px;
	clear:both;
}
</style>

<?
require_once("includes/inc_global.php");
require_once("includes/inc_head.html");
?>

<!--Main Lower Panel-->
<div id="mainlowerPan">
	<div id="lowerPan">
	
<div id="wufoshell">
<img id="top" src="http://adrollo-images.s3.amazonaws.com/top.png" alt="" />
<div id="container">

<form id="form2" name="form2" class="wufoo " autocomplete="off"
	enctype="multipart/form-data" method="post" action="#">

<div class="info">
	<h2>Frequently Asked Questions</h2>
	<div>You've got questions? We've got answers!</div>
</div>

<ul>

<h2>General Questions</h2>

<div id="qa">
<b>1. </b><a href="#" name="faq-1">
What is AdWhirl and why should I use it?
</a><br />
<div class="faq-answer" id="faq-1">
AdWhirl enables you to monetize your app inventory by allowing you to use multiple ad networks from within one client SDK.  AdWhirl makes it easy to allocate a percentage of your inventory to the various ad networks and update your allocations in real time.  By utilizing multiple ad networks, you can select the ad networks that perform best for your app and also make sure you have a high fill rate on your inventory.
<br /><br />
</div>
</div>

<div id="qa">
<b>2. </b><a href="#" name="faq-2">
Can I use AdWhirl to run my own custom ads?
</a><br />
<div class="faq-answer" id="faq-2">
Yes.  Simply click on the "Custom Ad" tab in the AdWhirl website after you are signed in. You will be able to create and manage your custom ads and allocate any percentage of your available inventory to them.
<br /><br />
</div>
</div>

<div id="qa">
<b>3. </b><a href="#" name="faq-3">
Which ad networks does AdWhirl support? 
</a><br />
<div class="faq-answer" id="faq-3">
AdWhirl allows you to serve ads from any ad network.  Detailed instructions on how to include AdMob, JumpTap, Millennial, Quattro Wireless, and VideoEgg are included in the instruction manual. You can include additional ad networks in the AdWhirl Client SDK by using the Generic Notifications feature.  
<br /><br />
</div>
</div>

<div id="qa">
<b>4. </b><a href="#" name="faq-4">
Can I turn ads on and off once I launch my application?
</a><br />
<div class="faq-answer" id="faq-4">
Yes. You can turn ads on or off by using the switch on the top right of the My AdWhirl page, which affects all ads for the app that is currently being viewed.
<br /><br />
</div>
</div>

<div id="qa">
<b>5. </b><a href="#" name="faq-5">
Why do I see so many more ads from a specific ad network even though I didn't assign such a high percentage to that network?
</a><br />
<div class="faq-answer" id="faq-5">
Ad networks are sometimes unable to fill all ad requests. If your app requests an ad from a network that is unable to return an ad, AdWhirl will request an ad from the next prioritized ad network in the AdWhirl Client SDK until the request is filled.  This ensures you nearly always have an ad to show and maximizes your fill rate.
<br /><br />
</div>
</div>

<div id="qa">
<b>6. </b><a href="#" name="faq-6">
Do I have to pay to use AdWhirl?
</a><br />
<div class="faq-answer" id="faq-6">
No. Both the AdWhirl Client SDK and Server code are free to download and use. 
<br /><br />
</div>
</div>


<div id="qa">
<b>7. </b><a href="#" name="faq-7">
Does AdWhirl take a cut out of the revenue that I get from the ad networks?
</a><br />
<div class="faq-answer" id="faq-7">
No. AdWhirl is a free service to the mobile community and we believe that it is a necessary component of a healthy mobile ecosystem.
<br /><br />
</div>
</div>

<div id="qa">
<b>8. </b><a href="#" name="faq-8">
Do I get all the features of an ad network when I include it in the AdWhirl Client SDK?
</a><br />
<div class="faq-answer" id="faq-8">
Yes, all features of the ad network libraries are enabled within the AdWhirl Client SDK. s
<br /><br />
</div>
</div>

<div id="qa">
<b>9. </b><a href="#" name="faq-9">
How will I get paid?
</a><br />
<div class="faq-answer" id="faq-9">
You will receive payments directly from the ad networks you work with.  AdWhirl does not have any insight into your business relationships with ad networks and does not have access to your eCPMs or earnings on the ad networks you use. 
<br /><br />
</div>
</div>

<div id="qa">
<b>10. </b><a href="#" name="faq-10">
How is AdWhirl maintained and why did you decide to open source the AdWhirl Client SDK?
</a><br />
<div class="faq-answer" id="faq-10">
AdWhirl was acquired by AdMob in 2009. We decided to release it to the open source community so that ad mediation can be transparent and open.  Developers can review the code and then decide which ad networks to include in their own custom build. AdMob has dedicated resources focused on improving the AdWhirl product and looks forward to working with the developer community at large to improve the product.  
<br /><br />
</div>
</div>

<li id="foli11" 		class="   ">
<div></div>
</li>

<h2>Getting Started</h2>


<div id="qa">
<b>1. </b><a href="#" name="faq-101">
How long does it take to setup AdWhirl on my iPhone app?
</a><br />
<div class="faq-answer" id="faq-101">
Most developers can setup AdWhirl in less than an hour. We have created a step-by-step guide that you can review before getting started, which you can download <a href="get">here</a>.
<br /><br />
</div>
</div>

<div id="qa">
<b>2. </b><a href="#" name="faq-102">
Do I need to get and install the ad libraries or SDKs for all the ad networks that I want to run within the AdWhirl Open Source Client SDK?
</a><br />
<div class="faq-answer" id="faq-102">
Yes. You will need to retrieve the SDKs from each ad network you would like to include in the AdWhirl Open Source Client SDK.  We have tested and verified that the SDK libraries for all supported ad networks are compatible with the AdWhirl SDK and have included detailed instructions to make integration easy for these ad networks.  
<br /><br />
</div>
</div>

<div id="qa">
<b>3. </b><a href="#" name="faq-103">
Do I need to create accounts with the ad networks that I want to run inside the AdWhirl SDK?
</a><br />
<div class="faq-answer" id="faq-103">
Yes. You will need to register and create accounts with all of the ad networks you want to run within the AdWhirl SDK.  You will receive your payment checks and reporting of revenue metrics directly from the individual ad networks.   
<br /><br />
</div>
</div>

<div id="qa">
<b>4. </b><a href="#" name="faq-104">
I just signed up for AdWhirl but didn't get a verification message. What should I do?
</a><br />
<div class="faq-answer" id="faq-104">
If you didn't receive a sign-up verification message in your email inbox, please check your junk/spam boxes, especially if you've applied very strict spam filtering options. If you still don't see a verification email, please contact us at support@adwhirl.com.
<br /><br />
</div>
</div>

<div id="qa">
<b>5. </b><a href="#" name="faq-105">
Can I add another ad network once I've submitted my app to the App Store? Will I need to post an update to the App Store?
</a><br />
<div class="faq-answer" id="faq-105">
You can "turn on" an ad network after the application is already in the App Store for any ad network whose library you included in your AdWhirl build. That means you don't have to go through the hurdles of updating the application to the App Store.  All you have to provide is your application key that you obtained from the new ad network, log into AdWhirl, and reconfigure your traffic allocation percentages.
<br /><br />
</div>
</div>

<li id="foli11" 		class="   ">
<div></div>
</li>

<h2>Technical Questions</h2>


<div id="qa">
<b>1. </b><a href="#" name="faq-201">
What delegate methods do I have to implement to request ads using AdWhirl?
</a><br />
<div class="faq-answer" id="faq-201">
You only have to return the AdWhirl application key. You can optionally implement methods to know when ads are received, when the in-app webview is visible when users tap on a banner ad (so you can pause games, for example), any many others, including the ability to pass in information so we can provide target ads with better CPMs - the methods are there for your benefit.  
<br /><br />
</div>
</div>


<div id="qa">
<b>2. </b><a href="#" name="faq-202">
How can I include other ad networks besides AdMob, JumpTap, Millenial, Quattro Wireless, and VideoEgg?
</a><br />
<div class="faq-answer" id="faq-202">
You can include additional ad networks in the AdWhirl Client SDK by using the Generic Notifications feature.  You can learn more about how to use that feature by downloading the AdWhirl Client SDK Configuration Guide on the Instructions page. 
<br /><br />
</div>
</div>


<div id="qa">
<b>3. </b><a href="#" name="faq-203">
Can I put the ad somewhere else, and not on top of the screen?
</a><br />
<div class="faq-answer" id="faq-203">
Yes. You can change the origin of the rectangle frame to any spot you wish. You have full control over that frame and its size, but all of the ad content fits nicely in a 320x50 px frame size.  
<br /><br />
</div>
</div>


<div id="qa">
<b>4. </b><a href="#" name="faq-204">
Does the AdWhirl SDK check network connectivity before attempting to request ads and prevent battery drainage?
</a><br />
<div class="faq-answer" id="faq-204">
Yes. The client SDK checks to see if network connectivity is available before allowing any ad requests to run. If no network connectivity is available, no ad requests are made. Therefore, note that since no ad requests are made, no callbacks will be made.
<br /><br />
</div>
</div>


<div id="qa">
<b>5. </b><a href="#" name="faq-205">
I've set my refresh interval to X seconds. I don't see an ad every X seconds. Is there something else I need to do?
</a><br />
<div class="faq-answer" id="faq-205">
When a refresh kicks in, a getNextAd call is made automatically. The call will choose an ad network based on the traffic allocation percentages you've applied. An ad request is then triggered. If the ad request fails, the backup ad network sources will then be used. If all of the backup sources are used and all ad requests still fail, the roller will stop fetching ads. As a result, you will not see an ad refresh. If you feel this isn't the case, please let us know about this issue right away.  
<br /><br />
</div>
</div>

<div id="qa">
<b>6. </b><a href="#" name="faq-206">
My app uses TouchJSON (or TouchXML), and I get a linker error when building my project because you've already compiled TouchJSON into your library. What should I do?
</a><br />
<div class="faq-answer" id="faq-206">
The AdMob SDK includes compiled TouchJSON (and Quattro Wireless compiled TouchXML). Therefore TouchJSON (and TouchXML) are also included in the AdWhirl SDK. Version 1.0 is being used, so the way around this is to just include the TouchJSON headers (or TouchXML headers) without the class files so that you don't produce object files that will cause a linker error when the linker detects that TouchJSON (TouchXML) components are already defined. You can find the TouchJSON headers that they provided here.  
<br /><br />
</div>
</div>

<div id="qa">
<b>7. </b><a href="#" name="faq-207">
I use Pinch Media Analytics. I get a linker error when building my project regarding TouchJSON.
</a><br />
<div class="faq-answer" id="faq-207">
If you're having issues with Pinch Media, it may be because TouchJSON is already included in the library (see question above). In that case, you can simply just include Pinch Media's Analytics headers (which can be downloaded here) and make analytics calls through the Pinch Media ad library that we've included. Otherwise, the other option is for us to give you a custom AdWhirl SDK client library with Pinch Media's ad library removed.  Please let us know if you prefer to have this option instead.
<br /><br />
</div>
</div>

<div id="qa">
<b>8. </b><a href="#" name="faq-208">
How do I load the AdWhirl demo onto my iPhone so that I can test on an actual device?
</a><br />
<div class="faq-answer" id="faq-208">
Pretty simple. First, click on Info.plist and change the "Bundle Identifier" value to something that your provisioning profile supports (e.g. com.yourcompany.sampleapp). Then, go to the build tab after double-clicking on your application's target and choose a provisioning file + development certificate combo. Lastly, change your Active SDK platform on the upper-left section on XCode to Device - iPhone [2.0/2.1/2.2/2.2.1]. You may have to reopen XCode if you're still having issues during the code-sign process or application load process.  
<br /><br />
</div>
</div>

<div id="qa">
<b>9. </b><a href="#" name="faq-209">
I have an app that produces a lot of traffic. Can you guys handle a lot of traffic? 
</a><br />
<div class="faq-answer" id="faq-209">
Yes.  AdWhirl was built to handle massive amounts of traffic, and many apps that use AdWhirl generate millions of impressions per dayYes.  AdWhirl was built to handle massive amounts of traffic, and many apps that use AdWhirl generate millions of impressions per day, so you shouldn't worry about scalability issues with AdWhirl.  
<br /><br />
</div>
</div>

<div id="qa">
<b>10. </b><a href="#" name="faq-210">
Can I use my version of TouchJSON, TouchXML, or FMDB since the ad network libraries already compiled them (AdMob, Quattro)? 
</a><br />
<div class="faq-answer" id="faq-210">
Yes. You can refactor the class names of your version of TouchJSON, TouchXML or FMDB and make calls to your refactored classes. In that case, for example, you would no longer run calls to CJSON or CXML, but you'd make calls to your own classes instead (e.g. ARJSON or ARXML). In the meantime, we're providing this feedback to ad networks and urging them to refactor these classes.  
<br /><br />
</div>
</div>

<div id="qa">
<b>11. </b><a href="#" name="faq-211">
What iPhone OS versions does AdWhirl support?
</a><br />
<div class="faq-answer" id="faq-211">
AdWhirl currently supports iPhone OS versions 2.2.1 and above.  You can download instructions on how to configure AdWhirl for specific iPhone OS versions by visiting the <a href="get">instructions</a> page.
<br /><br />
</div>
</div>

<div id="qa">
<b>12. </b><a href="#" name="faq-212">
How can I resize the roller view's frame size after rotating into landscape mode? 
</a><br />
<div class="faq-answer" id="faq-212">
You have full control over the roller's view frame. You will also notice that if you adjust the frame's size to any CGSize (such as 480x50), the roller's frame will adjust accordingly. However, the ad content will still stay at 320x50. Therefore, we recommend that you simply center the roller view in landscape mode. To do so, adjust the roller's view resizing mask to pad the left and right margins of the roller view (rollerView.autoresizingMask = UIViewAutoresizingFlexibleLeftMargin | UIViewAutoresizingFlexibleRightMargin). Also, don't forget to set the clipsToBounds property to YES so that the animation happens within the 320x50 frame and doesn't leak out of that frame.   
<br /><br />
</div>
</div>

<div id="qa">
<b>13. </b><a href="#" name="faq-213">
My app is trying to use FBConnect, but I keep getting a linker issue!
</a><br />
<div class="faq-answer" id="faq-213">
That is because Pinch Media's advertising library contains FBConnect. The workaround is to either refactor your version of the FBConnect classes, or make calls directly into the FBConnect object code that's been compiled into their library. Please refer to the AdWhirl Client SDK Configuration Guide on the Instructions page for more detail on how to do that.  
<br /><br />
</div>
</div>


<div id="qa">
<b>14. </b><a href="#" name="faq-214">
I keep getting a warning for the libAdWhirlSimulator_1.X.X when compiling for device. 
</a><br />
<div class="faq-answer" id="faq-214">
AdWhirl supports both the x86 (simulator via intel platform) and ARM (iPhone/iPod device) processors, which is why there are two static libraries available. The warning is not an issue, and is the result of the linker's complaint that it can't link to a library that is supported for a different platform. But the linker will automatically link with the correct library before deploying the app onto your phone. There are thousands of free apps running AdWhirl as of today, so please rest assured that this warning is not an issue. 
<br /><br />
</div>
</div>



<li id="foli11" 		class="   ">
<div></div>
</li>

<li id="foli13" 		class="section   ">
		<h3 id="title13">Questions we missed?</h3>
		<div id="instruct13">For further inquiries, please email us at support@adwhirl.com and we'll respond as soon as possible.</div>
	</li>

	<li style="display:none">
		<label for="comment">Do Not Fill This Out</label>
		<textarea name="comment" id="comment" rows="1" cols="1"></textarea>
	</li>
</ul>
</form>

</div><!--container-->
<img id="bottom" src="http://adrollo-images.s3.amazonaws.com/bottom.png" alt="" />
</div>

<!-- wufoo shell-->

	</div>
</div>
<!--Main Lower Panel Close-->

<?
require_once("includes/inc_tail.html");
?>
