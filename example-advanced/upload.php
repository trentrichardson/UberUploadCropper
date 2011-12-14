<?php
/*
This is not a thorough file upload.  Proper validation is not performed here. 
This code is for example purposes only.  You MUST complete this on your own!
*/
date_default_timezone_set('UTC');

//ini_set('display_errors',1);
//ini_set('log_errors',1);
//error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('CURR_DIR', dirname(__FILE__) . DS);
define('UPLOAD_DIR', CURR_DIR . "uploads". DS);

/*
Here we use the fileuploader's provided utility to upload files.
Why? Because if it passes the file via xhr, it will use $_GET[], 
otherwise the fallback is standard $_FILE[].

I have altered the fileuploader.php to pass back the filename 
which was set and the original filename
*/
require "../scripts".DS."fileuploader".DS."fileuploader.php";

$allowedExtensions = array('jpeg','jpg','gif','png');
$sizeLimit = 2 * 1024 * 1024; // max file size in bytes
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

$result = $uploader->handleUpload(UPLOAD_DIR, false, md5(uniqid())); //handleUpload($uploadDirectory, $replaceOldFile=FALSE, $filename='')

/* 
For your crop you should:
1) Make a copy of the full size original
2) Scale down or up this image so it fits in the browser nicely
3) When the crop occurs we will manipulate the full size image
*/
require "gd_image.php";
$gd = new GdImage();

// step 1: make a copy of the original
$filePath = UPLOAD_DIR . $result['filename'];
$copyName = $gd->createName($result['filename'], '_FULLSIZE');
$gd->copy($filePath, UPLOAD_DIR.$copyName);

// step 2: Scale down or up this image so it fits in the browser nicely, lets say 500px is safe
$oldSize = $gd->getProperties($filePath);
$newSize = $gd->getAspectRatio($oldSize['w'], $oldSize['h'], 500, 0);
$gd->resize($filePath, $newSize['w'], $newSize['h']);

// step 3: handled in crop.php!

// to pass data through iframe you will need to encode all html tags
echo json_encode($result);exit();

?>
