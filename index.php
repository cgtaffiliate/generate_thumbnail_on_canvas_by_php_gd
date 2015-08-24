<?php 
function create_thumb_on_canvas($s_image='',$save_path='',$max_width=100, $max_height=100){
	if(empty($s_image)){ return "Please pass source image path."; }
	if(empty($save_path)){ return "Please pass destination image path."; }
	$quality = 100;

	list($width, $height) = getimagesize($s_image);
	$ratiow = $width/$max_width ; 
	$ratioh = $height/$max_height;
	$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
	if($width > $max_width || $height > $max_height) { 
		$new_width = round($width * $ratio); 
		$new_height = round($height * $ratio); 
	} else {
		$new_width = round($width); 
		$new_height = round($height);
	} 

	$start_pos_dest_x = 0;
	$start_pos_dest_y = 0;
	if($max_width > $new_width){
		$start_pos_dest_x = round(($max_width - $new_width)/2);
	}
	if($max_height > $new_height){
		$start_pos_dest_y = round(($max_height - $new_height)/2);
	}

	if (preg_match("/.jpg/i","$s_image") or preg_match("/.jpeg/i","$s_image")) {
		
		$image_p = imagecreatetruecolor($max_width, $max_height);
		$image = imagecreatefromjpeg($s_image); 
		$background = imagecolorallocate($image_p, 255, 255, 255);
		imagefill($image_p,0,0,$background);

		imagecopyresampled($image_p, $image, $start_pos_dest_x, $start_pos_dest_y, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_p, $save_path, $quality); 
		imagedestroy($image_p); 
		
	} elseif (preg_match("/.png/i", "$s_image")) {
		
		$image_p = imagecreatetruecolor($max_width, $max_height);
		$background = imagecolorallocate($image_p, 0, 0, 0);
		
		$image = imagecreatefrompng($s_image); 
		imagecolortransparent($image_p,$background) ;
		imagealphablending($image_p, false);
		imagesavealpha($image_p, false);

		imagecopyresampled($image_p, $image, $start_pos_dest_x, $start_pos_dest_y, 0, 0, $new_width, $new_height, $width, $height);
		imagepng($image_p,$save_path); 
		imagedestroy($image_p); 
		
	} elseif (preg_match("/.gif/i", "$s_image")) {
		
		$image_p = imagecreatetruecolor($max_width, $max_height);
		$image = imagecreatefromgif($s_image); 
		$bgc = imagecolorallocate ($image_p, 255, 255, 255);
		imagefilledrectangle ($image_p, 0, 0, $max_width, $max_height, $bgc);
		imagecopyresampled($image_p, $image, $start_pos_dest_x, $start_pos_dest_y, 0, 0, $new_width, $new_height, $width, $height);
		imagegif($image_p, $save_path, $quality);
		imagedestroy($image_p); 
		
	} else {
		echo 'Please pass image type file';
	}
}


//How to call
	
	// JPG
	create_thumb_on_canvas('483x624.jpg','output/jpg-500x500.jpg',500,500);

	// PNG
	create_thumb_on_canvas('381x277.png','output/png-500x500.png',500,500);

	// GIF
	create_thumb_on_canvas('365x360.gif','output/gif-500x500.gif',500,500);
?>