<?php

// ideally one day this can do more than one image... 
// they would be stacked up to crop all at once in 
// Impromptu.. thus returning an array
date_default_timezone_set('UTC');

ini_set('display_errors',1);
ini_set('log_errors',1);
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('CURR_DIR', dirname(__FILE__) . DS);
define('UPLOAD_DIR', CURR_DIR . "uploads". DS);

foreach($_POST['imgcrop'] as $k => $v) {

	$targetPath = UPLOAD_DIR;
	$targetFile =  str_replace('//','/',$targetPath) . $v['filename'];
	
	//crop our image..	
	require "gd_image.php";
	$gd = new GdImage();

	$gd->crop($targetFile, $v['x'], $v['y'], $v['w'], $v['h']);
	
	//generate thumb or whatever else you like...
}

echo "1";
