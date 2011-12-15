<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Uber Upload Cropper -v2</title>
		
		<link href="../css/default.css" rel="stylesheet" type="text/css" />
		<link href="../scripts/fileuploader/fileuploader.css" rel="stylesheet" type="text/css" />
		<link href="../scripts/Jcrop/jquery.Jcrop.css" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" src="../scripts/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery-impromptu.js"></script>
		<script type="text/javascript" src="../scripts/fileuploader/fileuploader.js"></script>
		<script type="text/javascript" src="../scripts/Jcrop/jquery.Jcrop.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery-uberuploadcropper.js"></script>
		
		<script type="text/javascript">
			$(function() {
				
				$('#UploadImages').uberuploadcropper({
					//---------------------------------------------------
					// uploadify options..
					//---------------------------------------------------
					'debug'		: true,
					'action'	: 'upload.php',
					'params'	: {},
					'allowedExtensions': ['jpg','jpeg','png','gif'],
					//'sizeLimit'	: 0,
					//'multiple'	: true,
					//---------------------------------------------------
					//now the cropper options..
					//---------------------------------------------------
					//'aspectRatio': 1, 
					'allowSelect': false,			//can reselect
					'allowResize' : false,			//can resize selection
					'setSelect': [ 0, 0, 200, 200 ],	//these are the dimensions of the crop box x1,y1,x2,y2
					//'minSize': [ 100, 100 ],		//if you want to be able to resize, use these
					//'maxSize': [ 100, 100 ],
					//---------------------------------------------------
					//now the uber options..
					//---------------------------------------------------
					'folder': 'uploads/',			// only used in uber, not passed to server
					'cropAction': 'crop.php',		// server side request to crop image
					'onComplete': function(imgs,data){ 
						var $PhotoPrevs = $('#PhotoPrevs');

						for(var i=0,l=imgs.length; i<l; i++){
							$PhotoPrevs.append('<img src="uploads/'+ imgs[i].filename +'?d='+ (new Date()).getTime() +'" />');
						}
					}
				});
				
			});
		</script>
	</head>

	<body>
		<div id="wrapper">
			<h1>UberUploadCropper</h1>
			<p>Basic Example which demonstrates the very basic upload and crop.</p>
			<div id="UploadImages">
				<noscript>Please enable javascript to upload and crop images.</noscript>
			</div>

			<div id="PhotoPrevs">
				<!-- The cropped images will be populated here -->
			</div>
		</div>
	</body>
</html>
