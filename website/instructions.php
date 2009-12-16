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
        <title>AdWhirl: Instructions</title>
        <!-- CSS -->
        <link href="css/style_inside.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/style_fade.css" type="text/css" />
        <link rel="stylesheet" href="css/structure.css" type="text/css" />
        <link rel="stylesheet" href="css/form.css" type="text/css" />
        <link rel="stylesheet" href="css/theme.css" type="text/css" />
        <link rel="stylesheet" href="css/codesamples/textmate.css" type="text/css" />
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/contentfader.js"></script>

    <script>
    <!--  
        
    function showOptional(){
        var ElementStyle = document.getElementById("optional").style;
        
        if (ElementStyle.display == "none") {
            ElementStyle.display = "block";
        }
        else {
            ElementStyle.display = "none";
        }
    }
    
	//SYNTAX: fadecontentviewer.init("maincontainer_id", "content_classname", "togglercontainer_id", selectedindex, fadespeed_miliseconds)
	fadecontentviewer.init("whatsnew", "fadecontent", "whatnewstoggler", <?= isset($_GET['p']) && $_GET['p']?$_GET['p']:0 ?>, 400)
        
    //-->
    </script>

<?
    require_once("includes/inc_head.html");
?>			
            <!--Main Lower Panel-->
            <div id="mainlowerPan">
                <div id="lowerPan">
                    
                <div id="wufoshell">
                        <img id="top" src="http://adrollo-images.s3.amazonaws.com/top.png" alt="" />
                        <div id="container">
                            <form id="form2" name="form2" class="wufoo ">
                            
                                <div class="info">
                                    <h2>Setting up the AdWhirl SDK in 5 Steps</h2>
                                    <div>
                                        You'll be serving ads and making revenue in a flash.
                                    </div>
                                </div>
                                
                                
                                <div>
                                	Download the latest version of the AdWhirl SDK under your application section after <a href="start">registering</a> or <a href="main">logging in</a>.
                                     <br/>
                                </div>
                                <br/>
                                <br/>
                                
                                <div id="whatnewstoggler" class="fadecontenttoggler" style="width:750px;">
								 <a href="#" class="prev"><< Prev</a> <a href="#" class="toc">Step 1</a> <a href="#" class="toc">Step 2</a> <a href="#" class="toc">Step 3</a> <a href="#" class="toc">Step 4</a> <a href="#" class="toc">Step 5</a> <a href="#" class="toc">Help</a> <a href="#" class="toc">Generic Notifications</a> <a href="#" class="toc">What's NEW</a> <a href="#" class="next">Next >></a> 
								</div>
								
								<div id="whatsnew" class="fadecontentwrapper" style="height:2000px;">
					
								
								<!-- ***************SECTION 1*************** -->
								
								<div class="fadecontent">
									<p id="numheadline">
                                        1. Add the AdWhirl SDK libraries into your project.
                                    </p>
                                    <p id="description">
                                        You'll find all 4 files (ARRollerProtcol.h, ARRollerView.h, libAdWhirlDevice.a, libAdWhirlSimulator.a) in the "AdWhirl" folder.
                                    </p>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/1.gif"/>
								</div>
								
								
								<!-- ***************SECTION 2*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
                                        2. Add supporting frameworks required by all supported Ad Networks.
                                    </p>
                                    <p id="description">
                                        First, go to the targets section and double-click on your target.
                                    </p>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/2.gif" />
                                    <p id="description">
                                        Then, click on the General tab and click on the "+" icon at the bottom of the window.
                                    </p>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/3.gif" />
                                    <p id="description" style="width:840px;">
                                        You're almost there! Compare your list of added frameworks against the one in this picture to ensure that you included all of the required frameworks (you must include all frameworks regardless of what ad libraries you plan to use - but don't worry, although the frameworks are large, the libraries use such a small subset of them that, when compiled, the added size to your app is trivial ~100kb).
                                    </p>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/4.gif"/>
								</div>
								
								
								<!-- ***************SECTION 3*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
                                        3. One extra configuration step for Quattro Wireless.
                                    </p>
                                    <p id="description">
                                        Last configuration step!  Quattro Wireless requires that the ObjC flag is included under the "Other Linkers Flag" setting, so <br />	add that as well.
                                        <br/>
                                        <b>
                                        This step is VERY important.  Please do the following on the Build tab:
                                        <br/>
                                        3a) Set the Configuration mode to "All Configurations" so that this setting is applied across all configurations, and not just Debug, Release or Distribution mode..
                                        </li>
                                        <br/>
                                        3b) ObjC is CASE-SENSITIVE.  Capital-O, lowercase-b, lowercase-j, and Capital-C. <p/>You must complete these steps correctly, 
                                        <u>
                                            otherwise you will NOT be able to run Quattro Wireless ads!
                                        </u>
                                    </b>
                                    </p>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/6.gif" />
								</div>
								
								<!-- ***************SECTION 4*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
                                        4. Add the Protocol and View Header files, and then ensure that your class conforms to the formal protocol.
                                    </p>
									<p />
									<div class="step-code">
        		<pre class="textmate-source"><span class="source source_c++"><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>  AdWhirlSimpleViewController.h
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>  AdWhirl_Sample
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>  Created by The AdWhirl Team on 3/23/09.
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>  Copyright 2009 AdWhirl Inc. All rights reserved.
</span><span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>
</span>
<span class="meta meta_preprocessor meta_preprocessor_c meta_preprocessor_c_include">#<span class="keyword keyword_control keyword_control_import keyword_control_import_include keyword_control_import_include_c">import</span> <span class="string string_quoted string_quoted_other string_quoted_other_lt-gt string_quoted_other_lt-gt_include string_quoted_other_lt-gt_include_c"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_c">&lt;</span>UIKit/UIKit.h<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_c">&gt;</span></span></span>
<span class="meta meta_preprocessor meta_preprocessor_c meta_preprocessor_c_include">#<span class="keyword keyword_control keyword_control_import keyword_control_import_include keyword_control_import_include_c">import</span> <span class="string string_quoted string_quoted_double string_quoted_double_include string_quoted_double_include_c"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_c">"</span>ARRollerView.h<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_c">"</span></span></span>
<span class="meta meta_preprocessor meta_preprocessor_c meta_preprocessor_c_include">#<span class="keyword keyword_control keyword_control_import keyword_control_import_include keyword_control_import_include_c">import</span> <span class="string string_quoted string_quoted_double string_quoted_double_include string_quoted_double_include_c"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_c">"</span>ARRollerProtocol.h<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_c">"</span></span></span>

@interface AdWhirlSimpleViewController : UIViewController&lt;ARRollerDelegate&gt; <span class="meta meta_block meta_block_c">{

}</span>

@end</span></pre>
</div>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/14.gif"/>
                                    <br/>
                                    <div id="optionalabove">
                                    </div>
                                    <p id="numheadline">
                                        <a href="#optionalabove" onclick="showOptional();">(Optional) You can hardcode the API keys of the ad networks you know won't change.</a>
                                    </p>
                                    <div id="optional" style="display:none;">
                                        <p id="description">
                                            <br/>
                                            We don't recommend hardcoding these values since you lose the flexibility of adding/removing/changing these keys whenever you'd like to from the AdWhirl website. Leave these methods unimplemented (or return nils) to keep this flexibility.
                                            <br/>
                                            Otherwise, hardcode the value by returning a non-nil value. <img src="http://adrollo-images.s3.amazonaws.com/instructions/8.gif"/>
                                            <br/>
                                        </p>
                                        <p id="description">
                                            <b>Important Note</b>: Once the key is hardcoded, there is no flexibility to change this value from AdWhirl's website.
                                        </p>
                                    </div>
								</div>
								
								<!-- ***************SECTION 5*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
                                        5. Finally, return your AdWhirl application key from the required delegate method and add the AdWhirl view to your view!
                                    </p>
									<p/>
									<div class="step-code">
									<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc"><span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_block meta_block_c">
    ARRollerView* rollerView = <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>ARRollerView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">requestRollerViewWithDelegate<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="variable variable_language variable_language_objc">self</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span><span class="variable variable_language variable_language_objc">self</span>.view <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">addSubview<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>rollerView</span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
}</span></span>

<span class="meta meta_section"><span class="meta meta_preprocessor meta_preprocessor_c">#<span class="keyword keyword_control keyword_control_import keyword_control_import_pragma keyword_control_import_pragma_c">pragma mark</span> <span class="meta meta_toc-list meta_toc-list_pragma-mark meta_toc-list_pragma-mark_c">ARRollerDelegate required delegate method implementation</span></span></span>
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="support support_class support_class_cocoa">NSString</span>*<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">adWhirlApplicationKey</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="keyword keyword_control keyword_control_c">return</span> <span class="string string_quoted string_quoted_double string_quoted_double_objc"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_objc">@"</span>bxce197f7fa0102cb1e3cdce9eae8cb5<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_objc">"</span></span>;  <span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>Return your AdWhirl application key here
</span>}</span></span>

</span></span></span></pre>
</div>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/9.gif"/>
                                    <br/><br/><br/><br/><p id="description">
                                        You can also refer to the provided sample app for a comprehensive implementation of the AdWhirl library. Enjoy!
                                    </p><br/>
                                    <img src="http://adrollo-images.s3.amazonaws.com/instructions/10.gif"/><img src="http://adrollo-images.s3.amazonaws.com/instructions/11.gif"/><img src="http://adrollo-images.s3.amazonaws.com/instructions/12.gif"/><img src="http://adrollo-images.s3.amazonaws.com/instructions/13.gif"/>
                                    <br/>
                                    <p id="numheadline" style="font-size: 36px; width: 17%; margin-left: auto; margin-right: auto;">
                                        <a href="developers">Start Now</a>
                                    </p>
								</div>
									
								<!-- ***************SECTION 6*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
                                        If you're running into linker issues:
                                    </p>
                                    <br/><p id="description">
                                        Some ad network libraries have these compiled shared classes that you may be using: TouchXML (Quattro), FMDB (Quattro), and Facebook Connect (Pinch Media). <br />
										As a result, if you run into these problems, you can use one of these workarounds:<br />
										<br />1) Refactor your version of these classes.  To do so, just rename these classes.  For example, TouchJSON's class names CJSON can be refactored to ARCJSON (AR prefix for AdWhirl Roller).
										<br />2) Remove the .m class files from your project (to prevent double compiling and confusing the linker), but include the .h header files so that you can still make calls to these shared classes via the ad network library that has compiled these classes. 
										<br /><br />Another issue relates to FBConnect.  If you're compiling FBConnect into your project, or if you're using a platform that 
										<br />requires FBConnect, try using the workarounds above.  
										<br /><b>Updated: </b>ThinkBulbs has instructions on another solution <a href="http://blog.thinkbulbs.com/2009/07/proper-way-to-fix-linker-conflict.html">here</a>.  Otherwise, since the Pinch Media library is the conflicting library, 
										<br />if you decide that you never want to run JumpTap ads via Pinch Media, then you can opt to download a version of our SDK 
										<br />_without_ the Pinch Media library here:
										<br /><a href="http://adrollo-binaries.s3.amazonaws.com/AdWhirlSDK_1.2.0_NoPinch.zip">AdWhirl 1.2.0 w/o Pinch Media (OS 2.X)</a> 
										<br /><a href="http://adrollo-binaries.s3.amazonaws.com/AdWhirlSDK_1.2.0_OS3.0_NoPinch.zip">AdWhirl 1.2.0 w/o Pinch Media (OS 3.0)</a>
										<br /><br />If you're looking to make analytics calls yourself via the AdWhirl SDK, you can download the headers here:
										<br /><a href="http://adrollo-binaries.s3.amazonaws.com/AdWhirl_AnalyticsHeaders.zip">Analytics header files (Mobclix/Pinch)</a>
										<br /><br />For FBConnect:
										<br /><a href="http://adrollo-binaries.s3.amazonaws.com/AdWhirl_FBConnectHeaders.zip">FBConnect header files</a>
										<br /><br />If you'd like to fetch big banner ad sizes (for example, 300x250px), you can grab these ads directly via the SDK that has been integrated into AdWhirl.  For example, if you want to fetch big ads from Mobclix, use these header files:
										<br /><a href="http://adrollo-binaries.s3.amazonaws.com/MobclixHeaders_2.0.X.zip">Mobclix 2.0.X header files</a>
										<br /> <br />If you're still running into issues, please email us right away at support-at-adwhirl-dot-com and we'll get back to you <br />ASAP!
                                    </p><br/><br />
                                    <p id="numheadline" style="font-size: 36px; width: 17%; margin-left: auto; margin-right: auto;">
                                        <a href="developers">Start Now</a>
                                    </p>
								</div>
								
								<div class="fadecontent">
									<p id="numheadline">
                                        New Generic Notifications!
                                    </p>
                                    <br/><p class="description">
                                        As of version 1.1.0, the AdWhirl SDK can generate generic notifications so that you can apply your own client-side logic dynamically.  
										With generic notifications, you can now use AdWhirl to dynamically allocate traffic to a section of your code to do anything: showing an alert, 
										unlocking new app features, or simply making ad calls to
										an ad network library that AdWhirl has yet to integrate.  For instance, upon receiving this generic notification callback, you can make an ad request call directly to the
										Google SDK, Greystripe SDK, or another ad network's SDK.  Then, when the ad request completes successfully, you can replace the roller view
										with the ad view that you requested.  We suggest that you use the provided helper method [rollerView replaceBannerViewWith:newAdView] instead of detaching the roller view via removeFromSuperview.  If the ad request fails, you can force the roller view to perform a rollover so that it can fetch an ad from the next ad 
										network in the queue--if the roller view is still on screen.  And it will be, if you used [rollerView replaceBannerViewWith:].  
										Also, if you prefer to do so, you may turn off the auto refresh timer with two new helper methods to
										ignore and resume roller ad refreshes:  
										<br /><br />
										<div class="step-code">
											
										<pre class="textmate-source"><span class="source source_c++">
-(<span class="storage storage_type storage_type_c">void</span>)ignoreAutoRefreshTimer;
-(<span class="storage storage_type storage_type_c">void</span>)doNotIgnoreAutoRefreshTimer;

</span></pre>
</div>
										<br /><p class="description"/>To explain generic notifications even further, let's show you an example:
										First, allocate some traffic to the generic notification directly:
										<br /><br /><center><img style="border:1px dotted blue;" src="http://adrollo-images.s3.amazonaws.com/instructions/generic_traffic.gif"></center>
										<br /><p class="description"/>When the roller notices that an ad request is dynamically allocated to this generic notification, instead of making an ad request on your behalf,
										it will send you the generic notification callback.  From here, you can define/implement code that you want to dynamically execute:
										<br />
										<div class="step-code">
											<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">rollerReceivedRequestForDeveloperToFulfill</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>ARRollerView*<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">rollerView</span></span> 
</span><span class="meta meta_block meta_block_c">{
    <span class="comment comment_line comment_line_double-slash comment_line_double-slash_c++"><span class="punctuation punctuation_definition punctuation_definition_comment punctuation_definition_comment_c">//</span>Perform the logic that you want to dynamically execute here.  For instance, make an ad request here
</span>}</span></span>

</span></span></span></pre>
										</div>
										<p class="description"/>One perfect use-case for these notifications is to fetch ads from an ad network that AdWhirl has not integrated.  We'll use Quattro Wireless's SDK as an example ad library here--even though Quattro _is_ already integrated into AdWhirl.  But you can easily modify this to utilize the Google SDK, Greystripe SDK, Millennial SDK, and etc:
										<p />
										<div class="step-code">
											<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">rollerReceivedRequestForDeveloperToFulfill</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>ARRollerView*<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">rollerView</span></span> 
</span><span class="meta meta_block meta_block_c">{
    <span class="support support_class support_class_cocoa">NSString</span>* publisherID = <span class="string string_quoted string_quoted_double string_quoted_double_objc"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_objc">@"</span>yourPublisherID<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_objc">"</span></span>;
    <span class="support support_class support_class_cocoa">NSString</span>* siteID = <span class="string string_quoted string_quoted_double string_quoted_double_objc"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_objc">@"</span>yourSiteID<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_objc">"</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>qwAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">release</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    qwAdView = <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span><span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>QWAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">adViewWithType<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>QWAdTypeBanner 
                             <span class="support support_function support_function_any-method support_function_any-method_name-of-parameter support_function_any-method_name-of-parameter_objc">publisherID<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>publisherID
                                  <span class="support support_function support_function_any-method support_function_any-method_name-of-parameter support_function_any-method_name-of-parameter_objc">siteID<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>siteID
                             <span class="support support_function support_function_any-method support_function_any-method_name-of-parameter support_function_any-method_name-of-parameter_objc">orientation<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>UIInterfaceOrientationPortrait 
                                <span class="support support_function support_function_any-method support_function_any-method_name-of-parameter support_function_any-method_name-of-parameter_objc">delegate<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="variable variable_language variable_language_objc">self</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span> <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">retain</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>qwAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setBackgroundColor<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>UIColor <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">clearColor</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>qwAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setFrame<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="meta meta_function-call meta_function-call_c"><span class="support support_function support_function_any-method support_function_any-method_c">CGRectMake</span>(</span><span class="constant constant_numeric constant_numeric_c">10.0f</span>, <span class="constant constant_numeric constant_numeric_c">0.0f</span>, <span class="constant constant_numeric constant_numeric_c">300.0f</span>, <span class="constant constant_numeric constant_numeric_c">50.0f</span>)</span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>qwAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setDisplayMode<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>QWDisplayModeStatic</span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>qwAdView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">displayNewAd</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;

}</span></span>

</span></span></span></pre>
										</div>
										<p class="description"/>Then finally, you can replace the roller's view entirely with a simple method that was recently provided in 1.1.0 (which simply removes the roller view and puts your banner view in place):
										<p />
										</p><div class="step-code">
											<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">adView</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>QWAdView *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">adView</span></span> <span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc">didDisplayAd</span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>QWAd *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">ad</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>adWhirlView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">replaceBannerViewWith<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span>adView</span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
}</span></span>

</span></span></span></pre>
										</div>
										<p class="description"> <b>//New in 1.2.0</b><br /> In the event that your ad request attempt fails, you can command the roller to perform a rollover to the next ad network in the queue.  If the roller's view is still attached, since you didn't remove the roller view, just make a call to "rollOver":
										<p /><div class="step-code">
<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">adView</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>QWAdView *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">adView</span></span> <span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc">failedWithError</span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="support support_class support_class_cocoa">NSError</span> *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">error</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>adWhirlView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">rollOver</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>notificationView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setBackgroundColor<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>UIColor <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">cyanColor</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>notificationLabel <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setText<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span> <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span><span class="support support_class support_class_cocoa">NSString</span> <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">stringWithFormat<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="string string_quoted string_quoted_double string_quoted_double_objc"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_objc">@"</span>Not-AdWhirl:<span class="constant constant_character constant_character_escape constant_character_escape_objc">\n</span>Ad request failed. Calling rollOver!<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_objc">"</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
}</span></span>

</span></span></span></pre>
</div>
										<p class="description"/>You can also remove the roller view entirely ([rollerView removeFromSuperview]) and add the new ad view yourself, or use both banners at the same time, or do anything you wish.  The possibilities are endless.
										We're particularly excited about this new offering, and if you need any help setting up new ad networks, please let us know at developers-at-adwhirl-dot-com.
										We've helped a ton of developers set up other SDKs, especially the Greystripe SDK, and the Google SDK since the early beta stages of the SDK and even the most recent version at version 2.0.  
										Our team would love to help you ramp up w/ those ad networks and offer you our expertise on setup--all for free, of course.  :)  
                                    </p><br/>
                                    <p id="numheadline" style="font-size: 36px; width: 17%; margin-left: auto; margin-right: auto;">
                                        <a href="developers">Start Now</a>
                                    </p>
								</div>
								
								
								<!-- ***************SECTION 7*************** -->
								
								
								<div class="fadecontent">
									<p id="numheadline">
										<b>What's NEW!</b>
                                    </p>
									<br />
                                    <p class="description" /> 
									You can now force a rollover instead of a getNextAd call.  The rollover call will utilize the backup priority queue and make an ad request to the next ad network in the queue (in contrast, a getNextAd call will use the traffic allocation information).  Backup priority queue rollovers were previously performed automatically in the event of a failed ad request and did not allow you to trigger the call manually.  That is, until now.  You'll typically want to use this rollOver method to roll over to a new ad network when you've received a failed ad request to a generic ad network (if you're using generic notifications for this purpose, for example).
                                    	<br /><br />
										<div class="step-code">
                                        <pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">adView</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>QWAdView *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">adView</span></span> <span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc">failedWithError</span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="support support_class support_class_cocoa">NSError</span> *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">error</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>adWhirlView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">rollOver</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
}</span></span>

</span></span></span></pre>
										</div>
										
										
										<p class="description" > 
										To increase performance and save a round-trip delay for ad configuration information, you can now prefetch the configuration data by calling startPreFetchingConfigurationDataWithDelegate, preferably under applicationDidFinish launching.  Just make sure that you provide the adWhirlApplicationKey since the configuration information is tied to your key.
										</p>
										<br />
										<div class="step-code">
                                       	<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">applicationDidFinishLaunching</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>UIApplication *<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">application</span></span> </span><span class="meta meta_block meta_block_c">{
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>ARRollerView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">startPreFetchingConfigurationDataWithDelegate<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="variable variable_language variable_language_objc">self</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;</span></span></span></span></span></pre>
										</div>
																			
										<br />
										<div class="step-code">
											
											<pre class="textmate-source"><span class="source source_c++">
<span class="meta meta_preprocessor meta_preprocessor_c meta_preprocessor_c_include">#<span class="keyword keyword_control keyword_control_import keyword_control_import_include keyword_control_import_include_c">import</span> <span class="string string_quoted string_quoted_other string_quoted_other_lt-gt string_quoted_other_lt-gt_include string_quoted_other_lt-gt_include_c"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_c">&lt;</span>UIKit/UIKit.h<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_c">&gt;</span></span></span>
<span class="meta meta_preprocessor meta_preprocessor_c meta_preprocessor_c_include">#<span class="keyword keyword_control keyword_control_import keyword_control_import_include keyword_control_import_include_c">import</span> <span class="string string_quoted string_quoted_double string_quoted_double_include string_quoted_double_include_c"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_c">"</span>ARRollerProtocol.h<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_c">"</span></span></span>
@interface AdWhirl_SampleAppDelegate : NSObject &lt;UIApplicationDelegate, ARRollerDelegate&gt; <span class="meta meta_block meta_block_c">{
    UIWindow *window;</span></span></pre>
										</div>
										<br />
										<div class="step-code">
											<pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_section"><span class="meta meta_preprocessor meta_preprocessor_c">#<span class="keyword keyword_control keyword_control_import keyword_control_import_pragma keyword_control_import_pragma_c">pragma mark</span> <span class="meta meta_toc-list meta_toc-list_pragma-mark meta_toc-list_pragma-mark_c">ARRollerView required delegate method implementation</span></span></span>
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">-<span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="support support_class support_class_cocoa">NSString</span>*<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">adWhirlApplicationKey</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="keyword keyword_control keyword_control_c">return</span> <span class="constant constant_other constant_other_variable constant_other_variable_mac-classic constant_other_variable_mac-classic_c">kAdWhirlApplicationKey</span>;
}</span></span>

</span></span></span></pre>
										</div>
										
										
										<p class="description" /> 
										A few developers have asked to be informed when ads are off.  So this notification is created to alert you when ads are off. Quite straightforward--just have your delegate implement this delegate method so that you can intercept the callback.
										<p />
										<br />
										<div class="step-code">
                                        <pre class="textmate-source"><span class="source source_objc"><span class="meta meta_implementation meta_implementation_objc"><span class="meta meta_scope meta_scope_implementation meta_scope_implementation_objc">
<span class="meta meta_function-with-body meta_function-with-body_objc"><span class="meta meta_function meta_function_objc">- <span class="meta meta_return-type meta_return-type_objc"><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span><span class="storage storage_type storage_type_c">void</span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="entity entity_name entity_name_function entity_name_function_objc">rollerReceivedNotificationAdsAreOff</span></span><span class="meta meta_argument-type meta_argument-type_objc"><span class="entity entity_name entity_name_function entity_name_function_name-of-parameter entity_name_function_name-of-parameter_objc"><span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">(</span>ARRollerView*<span class="punctuation punctuation_definition punctuation_definition_type punctuation_definition_type_objc">)</span><span class="variable variable_parameter variable_parameter_function variable_parameter_function_objc">rollerView</span></span>
</span><span class="meta meta_block meta_block_c">{
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>notificationView <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setBackgroundColor<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>UIColor <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">magentaColor</span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
    <span class="meta meta_bracketed meta_bracketed_objc"><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_begin punctuation_section_scope_begin_objc">[</span>notificationLabel <span class="meta meta_function-call meta_function-call_objc"><span class="support support_function support_function_any-method support_function_any-method_objc">setText<span class="punctuation punctuation_separator punctuation_separator_arguments punctuation_separator_arguments_objc">:</span></span><span class="string string_quoted string_quoted_double string_quoted_double_objc"><span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_begin punctuation_definition_string_begin_objc">@"</span>AdWhirl:<span class="constant constant_character constant_character_escape constant_character_escape_objc">\n</span>Received notification Ads are OFF!<span class="punctuation punctuation_definition punctuation_definition_string punctuation_definition_string_end punctuation_definition_string_end_objc">"</span></span></span><span class="punctuation punctuation_section punctuation_section_scope punctuation_section_scope_end punctuation_section_scope_end_objc">]</span></span>;
}</span></span>

</span></span></span></pre>
										</div>
										
										<p class="description" /> 
										Several developers have asked for a way to control the auto refreshing events after configuring the firing interval via AdWhirl.com.  Now, you can force the roller to ignore auto refresh timer events.  You can use ignoreAutoRefreshTimer to prevent the roller from refreshing the ads.  This is helpful when your controller's view has disappeared and you don't want the ads to refresh in the background, which can dampen your CTR when ad impressions are left unused. 
										<p />
										<br />
										<div class="step-code">
										<pre class="textmate-source"><span class="source source_c++">
-(<span class="storage storage_type storage_type_c">void</span>)ignoreAutoRefreshTimer;
-(<span class="storage storage_type storage_type_c">void</span>)doNotIgnoreAutoRefreshTimer;

</span></pre>
										</div>
										<p class="description" /> 
										You can also ignore BOTH auto refreshes and manual refreshes (via getNextAd and rollOver calls) altogether by calling ignoreNewAdRequests.
										<p />
										
										<br />
										<div class="step-code">
										<pre class="textmate-source"><span class="source source_c++">
-(<span class="storage storage_type storage_type_c">void</span>)ignoreNewAdRequests;
-(<span class="storage storage_type storage_type_c">void</span>)doNotIgnoreNewAdRequests;

</span></pre>
										</div>
										
									</p>
								</div>
								</div>
                                
                            </form>
                        </div>
                        <!--container--><img id="bottom" src="http://adrollo-images.s3.amazonaws.com/bottom.png" alt="" />
                    </div>
                </div>
            </div><!--Main Lower Panel Close-->
<?
    require_once("includes/inc_tail.html");

?>
