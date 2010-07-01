<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Classes/Page.php';
require_once '../../../Editor/Classes/BumbleBee.php';
require_once '../../../Editor/Include/Functions.php';
require_once '../../../Editor/Classes/FileSystemUtil.php';



buildPreview();

function buildPreview() {
	global $basePath,$baseUrl;
	$id = requestGetNumber('id');
	$format = requestGetText('format');
	$print = requestGetBoolean('print');
	$width = requestGetNumber('width');
	
	// Load the page
	$page = Page::load($id);
	if (!$page) {
		error_log('Page not found!');
		return;
	}
	
	// Render
	render($id,$format,$print);
	
	// Convert image
	if ($width>0) {
		$file = convertImage($id,$width,$print);
	}
	
	// Redirection
	$file = '../../../local/cache/pagepreview/'.$id;
	if ($print) {
		$file.='_print';
	}
	if ($width) {
		$file.='_width'.$width;
	}
	if ($format=='png') {
		$file.='.png';
	} elseif ($format=='pdf') {
		$file.='.pdf';
	}
	redirect($file);
}

function render($id,$format,$print) {
	global $basePath,$baseUrl;
	
	$bee = new BumbleBee();
	if ($format=='png') {
		$path = $basePath.'local/cache/pagepreview/'.$id.'.png';
		if (!file_exists($path)) {
			$bee->renderWebPage($baseUrl."?id=".$id."&preview=code",$path,'png');
		}
		if ($print) {
			$path = $basePath.'local/cache/pagepreview/'.$id.'_print.png';
			if (!file_exists($path)) {
				$bee->renderWebPage($baseUrl."?id=".$id."&print=true&preview=code",$path,'png');
			}
		}
	}
	if ($format=='pdf') {
		$path = $basePath.'local/cache/pagepreview/'.$id.'.pdf';
		if (!file_exists($path)) {
			$bee->renderWebPage($baseUrl."?id=".$id."&preview=code",$path,'pdf');
		}
		if ($print) {
			$path = $basePath.'local/cache/pagepreview/'.$id.'_print.pdf';
			if (!file_exists($path)) {
				$bee->renderWebPage($baseUrl."?id=".$id."&print=true&preview=code",$path,'pdf');
			}
		}
	}
}

function convertImage($id,$width,$print) {
	global $basePath;
	
	$path = $basePath.'local/cache/pagepreview/'.$id.($print ? '_print' : '').'.png';
	$image = loadImage($path);
	
	$origWidth = imagesx($image);
	$origHeight = imagesy($image);
	
	$height = ($width/$origWidth)*$origHeight;

	$thumb = imagecreatetruecolor ($width, $height);
	$white = imagecolorallocate($thumb, 255, 255, 255);
	imagefill($thumb,0,0,$white);

	if (function_exists("imageCopyResampled")) {
		if (!@ImageCopyResampled($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight)) {
			ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
		}
	}
	else {
		ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
	}
	imagepng($thumb, $basePath.'local/cache/pagepreview/'.$id.($print ? '_print' : '').'_width'.$width.'.png');
}

/*
function sendImage() {
	global $basePath;
	
	//set_time_limit(120);
	$id = requestGetNumber('id');
	$timestamp = requestGetText('timestamp');
	$width = requestGetNumber('width');
	$height = requestGetNumber('height');
	$max = requestGetNumber('max');
	$maxwidth = requestGetNumber('maxwidth');
	$maxheight = requestGetNumber('maxheight');
	$percent = requestGetNumber('percent');
	$rotate = requestGetNumber('rotate');
	$greyscale = requestGetBoolean('greyscale');
	
	$nocache = requestGetBoolean('nocache');

	$cache = 'local/cache/images/'.$id.($max>0 ? 'm'.$max : '').($maxwidth>0 ? 'mw'.$maxwidth : '').($maxheight>0 ? 'mh'.$maxheight : '').($width>0 ? 'w'.$width : '').($height>0 ? 'h'.$height : '').($percent>0 ? 'p'.$percent : '').($rotate>0 ? 'r'.$rotate : '').($greyscale ? 'G' : '').'.png';
	
	if (!file_exists($basePath.$cache)) {
		$sql = 'select * from image where object_id='.$id;
		if ($row = Database::selectFirst($sql)) {
			
			// If nothing to do just redirect to the image
			if ($width==0 && $height==0 && $max==0 && $maxwidth==0 && $maxheight==0 && $percent==0 && $rotate==0 && !$greyscale) {
				redirect('../../images/'.$row['filename']);
				exit;
			}
			
			$filename = $basePath.'images/'.$row['filename'];
			
			$image = loadImage($filename);
			if ($rotate>0) {
				$white = imagecolorallocate($image, 255, 255, 255);
				$image = imagerotate ($image, $rotate, $white);
			}

			$size = Array(imagesx($image),imagesy($image));
			if ($percent>0) {
				$width=round($size[0]*$percent/100);
				$height=round($size[1]*$percent/100);
			}
			else if ($width>0 && $height==0) {
				$height=($width/$size[0])*$size[1];
			}
			else if ($height>0 && $width==0) {
				$width=($height/$size[1])*$size[0];
			}
			else if ($max>0) {
				if ($size[0]>$size[1]) { // if width > height
					$width = $max;
					$height = ($size[1]/$size[0])*$max;
				} elseif ($size[0]<$size[1]) {  // if width < height
					$width = ($size[0]/$size[1])*$max;
					$height = $max;
				} else {
					$width = $max;
					$height = $max;
				}
			}
			else if ($maxheight>0 && $maxwidth==0) {
				$height=$maxheight;
				$width=($maxheight/$size[1])*$size[0];
			}
			else if ($maxwidth>0 && $maxheight==0) {
				$width=$maxwidth;
				$height=($maxwidth/$size[0])*$size[1];
			}
			else if ($maxwidth>0 && $maxheight>0) {
				if ($maxheight/$maxwidth > $size[1]/$size[0]) {
					$width=$maxwidth;
					$height=($maxwidth/$size[0])*$size[1];
				} else {
					$height=$maxheight;
					$width=($maxheight/$size[1])*$size[0];
				}
			}
			else if ($height==0 && $width==0) {
				$width=$size[0];
				$height=$size[1];
			}
			// make sure width & height > 1
			if ($width<1) {
				$width=1;
			}
			if ($height<1) {
				$height=1;
			}

			$thumb = imagecreatetruecolor ($width, $height);
			$white = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb,0,0,$white);

			if (function_exists("imageCopyResampled")) {
				if (!@ImageCopyResampled($thumb, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1])) {
					ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
				}
			}
			else {
				ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}
			if ($greyscale) {
				greyscale($thumb);
				//convertToGrayscale($image);
			}
			if ($nocache) {
				imagepng($thumb);
			} else {
				imagepng($thumb, $basePath.$cache);
				header('Content-Type: image/png');
				readfile($basePath.$cache);
//				redirect('../../'.$cache);
			}
		} else {
			redirect('ImageNotFound.gif');
			//TODO: Do something about missing images
			// Inconsistency between old cached images and replaced images!
		}
	} else {
		header('Content-Type: image/png');
		readfile($basePath.$cache);
		//redirect('../../'.$cache.(strlen($timestamp)>0 ? '?timestamp='.$timestamp : ''));
	}
}
*/
function &loadImage($path) {
	$ext = strtolower(FileSystemUtil::getFileExtension($path));
	// create an image of the given filetype
	if ($ext=="jpg" || $ext == "jpeg") {
		$image = ImageCreateFromJpeg($path);
	}
	elseif ($ext=="png") {
		$image = ImageCreateFromPng($path);
	}
	elseif ($ext=="gif") {
		$image = ImageCreateFromGif($path);
	}
	return $image;
}
/*
function greyscale(&$image) {
    $imagex = imagesx($image);
    $imagey = imagesy($image);

    for ($x = 0; $x <$imagex; ++$x) {
        for ($y = 0; $y <$imagey; ++$y) {
            $rgb = imagecolorat($image, $x, $y);
            $red = ($rgb >> 16) & 255;
            $green = ($rgb >> 8) & 255;
            $blue = $rgb & 255;
            $grey = (int)(($red+$green+$blue)/3);
            $newcol = imagecolorallocate($image, $grey,$grey,$grey);
            imagesetpixel($image, $x, $y, $newcol);
        }
    }
}

function duotone (&$image, $rplus, $gplus, $bplus) {
    $imagex = imagesx($image);
    $imagey = imagesy($image);

    for ($x = 0; $x <$imagex; ++$x) {
        for ($y = 0; $y <$imagey; ++$y) {
            $rgb = imagecolorat($image, $x, $y);
            $red = ($rgb >> 16) & 0xFF;
            $green = ($rgb >> 8) & 0xFF;
            $blue = $rgb & 0xFF;
            $red = (int)(($red+$green+$blue)/3);
            $green = $red + $gplus;
            $blue = $red + $bplus;
            $red += $rplus;

            if ($red > 255) $red = 255;
            if ($green > 255) $green = 255;
            if ($blue > 255) $blue = 255;
            if ($red < 0) $red = 0;
            if ($green < 0) $green = 0;
            if ($blue < 0) $blue = 0;

            $newcol = imagecolorallocate ($image, $red,$green,$blue);
            imagesetpixel ($image, $x, $y, $newcol);
        }
    }
}
*/
?>