<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Uber Upload Cropper -v3</title>
				
		<link href="../css/default.css" rel="stylesheet" type="text/css" />
		<link href="../scripts/jQuery-Impromptu/jquery-impromptu.css" rel="stylesheet" type="text/css" />
		<link href="../scripts/fineuploader/fineuploader.css" rel="stylesheet" type="text/css" />
		<link href="../scripts/Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" src="../scripts/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="../scripts/jQuery-Impromptu/jquery-impromptu.js"></script>
		<script type="text/javascript" src="../scripts/fineuploader/jquery.fineuploader-3.0.min.js"></script>
		<script type="text/javascript" src="../scripts/Jcrop/jquery.Jcrop.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery-uberuploadcropper.js"></script>
		
		<script type="text/javascript">
			$(function() {
				
				$('#UploadImages').uberuploadcropper({
					//---------------------------------------------------
					// uploadify options..
					//---------------------------------------------------
					fineuploader: {
						//debug : true,
						request	: { 
							// params: {}
							endpoint: 'upload.php' 
						},						
						validation: {
							//sizeLimit	: 0,
							allowedExtensions: ['jpg','jpeg','png','gif']
						}
					},
					//---------------------------------------------------
					//now the cropper options..
					//---------------------------------------------------
					jcrop: {
						aspectRatio  : 1, 
						allowSelect  : false, //can reselect
						allowResize  : true,  //can resize selection
						setSelect    : [ 0, 0, 200, 200 ], //these are the dimensions of the crop box x1,y1,x2,y2
						minSize      : [ 200, 200 ], //if you want to be able to resize, use these
						maxSize      : [ 500, 500 ]
					},
					//---------------------------------------------------
					//now the uber options..
					//---------------------------------------------------
					folder           : 'uploads/', // only used in uber, not passed to server
					cropAction       : 'crop.php', // server side request to crop image
					onComplete       : function(e,imgs,data){ 
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
			<p>Advanced Example which demonstrates the uploading a large image, scaling a smaller copy to fit the browser, crop, and resize the original full size image.  This method maintains better image quality.</p>
			
			<div id="UploadImages">
				<noscript>Please enable javascript to upload and crop images.</noscript>
			</div>

			<div id="PhotoPrevs">
				<!-- The cropped images will be populated here -->
			</div>
		</div>
	</body>
</html>
