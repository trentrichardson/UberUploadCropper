UberUploadCropper
========================

By: Trent Richardson [http://trentrichardson.com]

Version 0.4

Last Modified: 03/26/2013

Copyright 2011 Trent Richardson

Dual licensed under the MIT and GPL licenses.

http://trentrichardson.com/Impromptu/GPL-LICENSE.txt

http://trentrichardson.com/Impromptu/MIT-LICENSE.txt

Libraries Used
--------------
- [jQuery Impromptu](http://trentrichardson.com/Impromptu/) for modal resizing window
- [Jcrop](https://github.com/tapmodo/Jcrop) to crop the images
- [Fine Uploader](http://fineuploader.com/) to upload images

Usage
------
** THIS example is not for production use.  It is only an example implementation! **

Please see examples provided.  These are minimal examples with comments.  Have a look at the comments.

There is a bit of resizing and crop work to be done on your part, this is just an 
example of how to get the script coordinating with fineuploader, Impromptu, and jcrop.

When you begin your own implementation you will need to do some image prep work in 
upload.php, as well as crop.php (or where ever you decide to place it.)


Common Issues
-------------
- To use the examples you MUST give the upload directories write permissions.
- Your max post size or max upload size is too small (for php see your php.ini)
- Use Firebug or Developer console to view network requests to see if there were errors
