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

$result = $uploader->handleUpload(UPLOAD_DIR, false, ''); //handleUpload($uploadDirectory, $replaceOldFile=FALSE, $filename='')
// when filename is not provided the actual file name will be used.  Consider passing something like uuid: md5(uniqid())
// $result = a string for error or an array(success=>'success', filename=>'myfile123.jpg', originalFilename=>'myfile.jpg)


/* 
For your crop you should:
- Make a copy of the full size original
- Scale down or up this image so it fits in the browser nicely
- When the crop occurs we will take the coordinates (in relation 
  to the scaled image) and perform the same crop on the full size.
  This helps maintain the quality of the image.  The more an image 
  is resized the worse the quality becomes.
*/


// to pass data through iframe you will need to encode all html tags
echo json_encode($result);exit();

?>
