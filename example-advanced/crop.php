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

require "gd_image.php";
$gd = new GdImage();

foreach($_POST['imgcrop'] as $k => $v) {
	
	/*
		1) delete the resized image from upload, we will only be working with the full size
		2) compute new coordinates of full size image
		3) crop full size image
		4) resize the cropped image to what ever size we need
	*/
	
	// 1) delete resized, move to full size
	$filePath = UPLOAD_DIR . $v['filename'];
	$fullSizeFilePath = UPLOAD_DIR . $gd->createName($v['filename'], '_FULLSIZE');
	unlink($filePath);
	rename($fullSizeFilePath, $filePath);

	// 2) compute the new coordinates
	$scaledSize = $gd->getProperties($filePath);
	$percentChange = $scaledSize['w'] / 500; // we know we scaled by width of 500 in upload
	$newCoords = array(
		'x' => $v['x'] * $percentChange,
		'y' => $v['y'] * $percentChange,
		'w' => $v['w'] * $percentChange,
		'h' => $v['h'] * $percentChange
	);

	// 3) crop the full size image
	$gd->crop($filePath, $newCoords['x'], $newCoords['y'], $newCoords['w'], $newCoords['h']);

	// 4) resize the cropped image to whatever size we need (lets go with 200 wide)
	$ar = $gd->getAspectRatio($newCoords['w'], $newCoords['h'], 200, 0);
	$gd->resize($filePath, $ar['w'], $ar['h']);
	
}

echo "1";
