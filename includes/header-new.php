<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
$path = $_SERVER['PHP_SELF'];
$page = basename($path);
$page = basename($path, '.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
 <title>Event name | Organisation name Drivers of Change</title>
<link rel="shortcut icon" id="favicon" type="image/png" href="assets/images/favicon.png" />
<!-- Apple iOS Web App Settings -->
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-icon" href="assets/images/apple-touch-logo.png"/>
<script type="text/javascript">
/* <![CDATA[ */
	(function () {
		var filename = navigator.platform === 'iPad' ?
	   		'splash-screen-768x1004.png' : 'splash-screen-640x920.png';
	  	document.write(
	    	'<link rel="apple-touch-startup-image" '+'href="assets/images/'+filename+'" />' );
	})();
    /* ]]> */
     </script>
<!-- END Apple iOS Web App Settings -->
<link rel="stylesheet" href="assets/css/master.css" type="text/css" media="screen" />
<!--	Load the "Chosen" stylesheet. You can remove this if your
		select boxes aren't going to make use of the awesome Chosen script. -->
<link rel="stylesheet" href="assets/js/chosen/chosen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css" type="text/css" media="all" />
<!--	Load the Fancybox stylesheet. You can remove this if you
		are not going to be lightboxing any images. -->
<link rel="stylesheet" href="assets/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<link rel="stylesheet" href="assets/css/atooltip.css" type="text/css" media="screen" />
<!--	Load the jQuery Library - We're loading in the header because there are quite a few dependencies that require
		The library while the rest of the page loads. These include Highcharts and the Tablesorter scripts. -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script> -->
<script src="assets/js/jquery.jeditable.mini.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/js/jquery.jeditable.ajaxupload.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
    $('.editable').editable('http://www.example.com/save.php',{});
    $('.edit_area').editable('http://www.example.com/save.php',{type: "textarea"});
    $(".editable_select").editable("<?php print $url ?>save.php", { 
        indicator : '<img src="img/indicator.gif" alt="" />',
        data   : "{'social':'Social','technological':'Technological','economic':'Economic','environmental':'Environmental','political':'Political'}",
        type   : "select",
        submit : "OK",
        style  : "inherit",
        submitdata : function() {
          return {id : 2};
        }
      });
     $(".ajaxupload").editable("<?php print $url ?>upload.php", { 
             indicator : "<img src='img/indicator.gif' alt="" />",
             type      : 'ajaxupload',
             submit    : 'Upload',
             cancel    : 'Cancel',
             tooltip   : "Click to upload..."
    });
});
/* ]]> */
 </script>
<!--	Load the Tablesorter script. You can remove this if you will not be displaying any sortable tables. -->
<script src="assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
<!--	Load the Chosen script. You can remove this if you will not be displaying any custom select boxes. -->
<script src="assets/js/chosen/chosen.jquery.js" type="text/javascript"></script>
<!--	Load the Fancybox script. You can remove this if you will not be displaying any image lightboxes. -->
<script src="assets/js/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
<!--	Load the AdminPro custom script. -->
<script src="assets/js/custom.js" type="text/javascript"></script>
<script src="assets/js/jquery.atooltip.min" type="text/javascript"></script>
<!--	Set up the responsive design sizes. -->
<script type="text/javascript">
	var ADAPT_CONFIG = {
	  	path: 'assets/css/',
	  	dynamic: true,
		callback: resizeGalleries,
	 	range: [
	    	'0px    to 420px  /// mobile_portrait.css',
	    	'420px  to 600px  /// mobile_landscape.css',
	    	'600px  to 900px  /// 720.css',
	    	'900px  to 1200px /// 960.css',
	    	'1200px			  /// 1400.css'
	  	]
	};
		$(function(){ 
			$('a.clue').aToolTip();
		}); 
</script>
<!--	Load the Adapt script -->
<script src="assets/js/adapt.min.js" type="text/javascript"></script>
</head>
<body>
    <div id="wrapper">
    <!-- EYEBROW NAVIGATION -->
	<div id="eyebrow-navigation">
	    <a href="http://www.driversofchange.com">driversofchange.com</a>
		<a href="#" class="settings">My Account</a>
		<a href="index.html" class="signout">Sign Out</a>
	</div>	
<!-- BEGIN HEADER --> 		
<div class="container_4">
	<div id="header" class="header-wrap clearfix">
		<!-- LOGO -->
		<div id="logo" class="">
		<h1><a href="index.php"><img alt="drivers of change" src="assets/images/logo.png" /></a></h1>
		<h2>San Francisco School of the Arts (organisation)</h2>
	    </div>
	<?php include('includes/main_navigation.php'); ?>
	</div>
	<!-- END HEADER -->
</div>
<!-- END CONTAINER_4 - HEADER-WRAP -->