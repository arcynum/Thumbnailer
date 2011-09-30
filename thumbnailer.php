<?php
	function getThumbnail($folder, $image, $w, $h) {
		$max_width  = $w;
		$max_height = $h;
		global $img;
		
		if( !$image || $image == "" ) { die("No file found"); }
		
		$final_thumb_folder = $folder."thumbs/";
		if (!is_dir($final_thumb_folder)) { mkdir($final_thumb_folder); }
		
		$final_thumb = $final_thumb_folder.$max_width."x".$max_height."_".$image;
		if (file_exists($final_thumb)) { return $final_thumb; }
		
		if( !$max_width || $max_width == "" ) { $max_width = "100"; }
		if( !$max_height || $max_height == "" ) { $max_height = "100"; }      
		
		$image_path = $folder.$image;
		
		$img = null;
		$ext = explode(".", $image_path);
		$ext = strtolower(end($ext));
		if ($ext == 'jpg' || $ext == 'jpeg') { $img = @imagecreatefromjpeg($image_path); }
		else if ($ext == 'png') { $img = @imagecreatefrompng($image_path); }
		else if ($ext == 'gif') { $img = @imagecreatefromgif($image_path); }
		else { die("File type not supported"); }
		
		if ($img) {
			$width = imagesx($img);
			$height = imagesy($img);
			$scale = min($max_width / $width, $max_height / $height);
			
			if ($scale < 1) {
				$new_width = floor($scale * $width);
				$new_height = floor($scale * $height);
				
				$tmp_img = imagecreatetruecolor($new_width, $new_height);
				
				imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagedestroy($img);
				$img = $tmp_img;
			}
			else {
				return $image_path;
			}
		}
		
		if (!$img) {
			$img = imagecreate($max_width, $max_height);
			imagecolorallocate($img, 255, 255, 255);
			$c = imagecolorallocate($img, 255, 0, 0);
			imageline($img, 0, 0, $max_width, $max_height, $c2);
			imageline($img, $max_width, 0, 0, $max_height, $c2);
		}
		
		imagejpeg($img, $final_thumb);
		imagedestroy($img);
		
		return $final_thumb;
	}
?>