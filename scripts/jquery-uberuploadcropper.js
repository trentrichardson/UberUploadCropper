/*
 * jQuery UberUploadCropper-v0.3
 * By: Trent Richardson [http://trentrichardson.com]
 * Version 0.3
 * Last Modified: 12/10/2012
 * 
 * Copyright 2011 Trent Richardson
 * Dual licensed under the MIT and GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 * 
 */
(function($){

	$.fn.uberuploadcropper = function(options){
		options = $.extend(true, {}, { 
				fineuploader: {  }, // fineuploader options
				jcrop: { setSelect: [0,0,100,100] }, // jcrop options
				impromptu: {}, // impromptu options 
				folder: '',
				cropAction: '',
				onComplete:function(){}
			},
			options);
		
		var $t = $(this),		
			imgdata = [];
		
		// When each state of the prompt is submitted
		var cropPromptSubmit = function(e,v,m,f){
		
			if(v == 'Previous'){
				$.prompt.prevState();
				return false;
			}
			if(v == 'Next'){
				$.prompt.nextState();
				return false;
			}
			if(v == 'Cancel'){
				$.prompt.close();
				imgdata = [];
				return false;
			}
			if(v == 'Done'){ //process crop..
				$.prompt.goToState('waitState');
				
				$.post(options.cropAction,f,function(data){
					$.prompt.close();

					// trigger a custom event
					$t.bind('uberOnComplete', options.onComplete)
						.trigger('uberOnComplete',[imgdata,data]);
					
					imgdata = [];
				});
				
				return false;
			}
	
		};
		
		var cropPromptClose = function(e,v,m,f){
			imgdata = [];
		};
		
		//keep our coords up to date
		var jcropOnChange = function(c){
			$currstate = $.prompt.getCurrentState();
			
			$currstate.find('.imgcrop_x').val(c.x);
			$currstate.find('.imgcrop_y').val(c.y);
			$currstate.find('.imgcrop_w').val(c.w);
			$currstate.find('.imgcrop_h').val(c.h);
		};
		
		// upload is done.. crop these puppies!
		var allUploadsComplete = function(){
			var states = {};
						
			$.each(imgdata,function(i,obj){
				var btn = {};
				var str = '<div style="text-align: center;"><img src="'+ options.folder + obj.filename +'?d='+ (new Date()).getTime() +'" class="ubercropimage" id="ubercropimage_'+ i +'" style="margin: 0 auto;" /></div>'+
					'<input type="hidden" name="imgcrop['+ i +'][filename]" id="imgcrop_'+ i +'_filename" value="'+obj.filename+'" class="imgcrop_filename" />'+
					'<input type="hidden" name="imgcrop['+ i +'][originalFilename]" id="imgcrop_'+ i +'_originalFilename" value="'+obj.originalFilename+'" class="imgcrop_originalFilename" />'+
					'<input type="hidden" name="imgcrop['+ i +'][x]" id="imgcrop_'+ i +'_x" value="0" class="imgcrop_x" />'+
					'<input type="hidden" name="imgcrop['+ i +'][y]" id="imgcrop_'+ i +'_y" value="0" class="imgcrop_y" />'+
					'<input type="hidden" name="imgcrop['+ i +'][w]" id="imgcrop_'+ i +'_w" value="'+ (options.jcrop.setSelect[2]-options.jcrop.setSelect[0]) +'" class="imgcrop_w" />'+
					'<input type="hidden" name="imgcrop['+ i +'][h]" id="imgcrop_'+ i +'_h" value="'+ (options.jcrop.setSelect[3]-options.jcrop.setSelect[1]) +'" class="imgcrop_h" />'+
					'<input type="hidden" name="imgcrop['+ i +'][folder]" id="imgcrop_'+ i +'_folder" value="'+ options.folder +'" class="imgcrop_folder" />';
				
				if(imgdata.length == 1)
					btn = { Done:'Done' };
				else if(i == imgdata.length-1)
					btn = { Previous:'Previous', Done:'Done' };
				else if(i > 0)
					btn = { Previous:'Previous', Next:'Next' };
				else btn = { Next:'Next' };
				
				
				states['state'+ i] = {
					title: obj.originalFilename,
					html: str,
					buttons: btn,
					submit: cropPromptSubmit
				};
				
			});			
			states['waitState'] = { html: 'Processing...', buttons:{} };
			
			var biggestwidth = 0,
				imp = $.prompt(states,options.impromptu)
						.bind('promptclose', cropPromptClose);
			
			$('.ubercropimage').each(function(i){ 
				var $img = $(this);
				$img.Jcrop(options.jcrop);

				// we might get a huge image, so resize the prompt..
				$img.load(function(){
					if($img.width() > biggestwidth){
						biggestwidth = $img.width() + 40;
						imp.find('.jqi').width(biggestwidth+40).css('marginLeft',((imp.find('.jqi').outerWidth()/2)*-1));
					}
				});
			});
		};
		
		options = $.extend(true, options,{ jcrop:{onChange: jcropOnChange, onSelect: jcropOnChange} });
		
		$t.fineUploader(options.fineuploader)
			//.on('error', function(event, id, filename, reason){
			//		console.log('Error: ', filename, reason);
			//	})
			.on('complete', function(event, id, fileName, responseJson){
					imgdata.push(responseJson);
					
					$t.trigger('uberSingleUploadComplete',[responseJson]);

					if ($t.fineUploader('getInProgress') == 0) {
						
						// trigger a custom event
						$t.bind('uberAllUploadsComplete',allUploadsComplete)
							.trigger('uberAllUploadsComplete',[imgdata]);
					} 
				});
		
	}

})(jQuery);
