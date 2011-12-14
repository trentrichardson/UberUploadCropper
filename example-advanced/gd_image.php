<?php

class GdImage{
	
	public function __construct()
	{
	}
	
	/**
	* resize($img, $x, $y, $w, $h, $quailty)
	*	resize an image given starting x/y point and width/height
	*
	*	$img = path to the image
	*	$w = desired image width
	*	$h = desired image height
	*	$quality = 0 worst -> 100 best
	*/
	public function resize($img,$neww,$newh,$quality=100)
	{
		$sizes = $this->getProperties($img);
		$oldw = $sizes['w'];
		$oldh = $sizes['h'];
		
		switch($sizes['type']){
			//resize jpg
			case 'jpg':
				$src_img=imagecreatefromjpeg($img);
				$dst_img=ImageCreateTrueColor($neww,$newh);
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$neww, $newh, $oldw, $oldh);
				imagejpeg($dst_img,$img,$quality); 
				break;
			//resize png
			case 'png':
				$quality = ceil($quality / 10); // for png quality is compression, 0-9, 9 full compression
				$quality = ($quality == 0)? 9 : (($quality - 1) % 9);
				
				$src_img=imagecreatefrompng($img);			
				$dst_img=ImageCreateTrueColor($neww,$newh);
				imagealphablending($dst_img, false);
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$neww, $newh, $oldw, $oldh);
				imagesavealpha($dst_img, true);
				imagepng($dst_img,$img, $quality); 
				
				break;
			//resize gif
			case 'gif':
				$src_img=imagecreatefromgif($img);
				$dst_img=ImageCreateTrueColor($neww,$newh);
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$neww, $newh, $oldw, $oldh);
				imagegif($dst_img,$img); //no quality option available
				break;
		}
		imagedestroy($dst_img); 
		imagedestroy($src_img);
	}
	
	/**
	* crop($img, $x, $y, $w, $h)
	*	crops an image given starting x/y point and width/height
	*
	*	$img = path to the image
	*	$x = x coord to start cropping
	*	$y = y coord to start cropping
	*	$w = desired image width
	*	$h = desired image height
	*	$quality = 0 worst -> 100 best
	*/
	public function crop($img,$x,$y,$w,$h,$quality=100)
	{
		$sizes = $this->getProperties($img);
		
		switch($sizes['type']){
			//crop jpg
			case 'jpg':
				$src_img=imagecreatefromjpeg($img);
				$dst_img=ImageCreateTrueColor($w,$h);
				imagecopyresampled($dst_img,$src_img,0,0,$x, $y, $w, $h, $w, $h);
				imagejpeg($dst_img,$img,$quality); 
				break;
			//crop png
			case 'png':
				$quality = ceil($quality / 10); // for png quality is compression, 0-9, 9 full compression
				$quality = ($quality == 0)? 9 : (($quality - 1) % 9);
				
				$src_img=imagecreatefrompng($img);			
				$dst_img=ImageCreateTrueColor($w,$h);
				imagealphablending($dst_img, false);
				imagecopyresampled($dst_img,$src_img,0,0,$x, $y, $w, $h, $w, $h);
				imagesavealpha($dst_img, true);
				imagepng($dst_img,$img,$quality); 
				break;
			//crop gif
			case 'gif':
				$src_img=imagecreatefromgif($img);
				$dst_img=ImageCreateTrueColor($w,$h);
				imagecopyresampled($dst_img,$src_img,0,0,$x, $y, $w, $h, $w, $h);
				imagegif($dst_img,$img); //no quality option available
				break;
		}
		
		imagedestroy($dst_img); 
		imagedestroy($src_img);
	}
	
	/**
	* copy($src, $dest)
	*	create a copy of an image
	*
	*	$src = source image
	*	$dest = destination of image
	*/
	public function copy($src,$dest)
	{
		copy($src,$dest);
	}
	
	/**
	* getProperties($src)
	*	returns array with image dimensions [w,h]
	*
	*	$src = image location
	*/
	public function getProperties($src)
	{
		$sizes = getimagesize($src);
		$imgtypes = array('1'=>'gif', '2'=>'jpg', '3'=>'png', '4'=>'png');
		return array("w"=>$sizes[0],"h"=>$sizes[1], 'type'=>$imgtypes[$sizes[2]], 'mime'=>$sizes['mime']);
	}
	
	/**
	* getAspectRatio($w, $h, $to_w, $to_h, $before_crop)
	*	returns the aspect ratio given known dimensions
	*
	*	$w = original iamge width
	*	$h = orginal image height
	*	$to_w = desired image width
	*	$to_h = desired image height
	*	$before_crop = true/false if this is preparing to crop
	*		if before_crop then we will force the smaller of 
	*		the two dimensions and let you crop the other.
	*/
	public function getAspectRatio($w,$h,$to_w,$to_h,$before_crop=false)
	{
		$sizes = array("w"=>0,"h"=>0);
		
		//if we're going to crop it we want the smaller dimension fitted
		if($before_crop){ 
			if(($w-$to_w) >= ($h-$to_h))
				$to_w=0;
			else $to_h=0;
		}
		
		if($to_w != 0 && $to_h != 0){//set height, width
			$sizes["w"] = $to_w;
			$sizes["h"] = $to_h;
		}
		elseif($to_w != 0){//set width, compute height aspect ratio
			$sizes["w"] = $to_w;
			$sizes["h"] = intval(($to_w * $h) / $w);
		}
		elseif($to_h != 0){//set height, compute width aspect ratio
			$sizes["w"] = intval(($to_h * $w) / $h);
			$sizes["h"] = $to_h;				
		}
		else{
			$sizes["w"] = $w;
			$sizes["h"] = $h;
		}
		
		return $sizes;
	}
	
	/**
	* createName($curr, $alias);
	*	creates a new image name
	*
	*	ex: $this->createImageName("myimage.jpg","_thumb");
	*	gives: myimage_thumb.jpg
	*/
	public function createName($curr,$alias)
	{
		$parts = explode(".",$curr);
		$suffix = array_pop($parts);		
		return implode(".",$parts) . $alias .".". $suffix;
	}
	
	public function __destruct(){
		
	}
}
?>
