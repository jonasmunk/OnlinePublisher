<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once '../../Editor/Include/Functions.php';
require_once '../../Editor/Classes/FileSystemUtil.php';
require_once '../../Editor/Classes/Request.php';

if (!function_exists('ImageCreateFromJpeg')) {
	$id = Request::getInt('id');
	$sql = 'select * from image where object_id='.$id;
	if ($row = Database::selectFirst($sql)) {
		$filename = $basePath.'images/'.$row['filename'];
		redirect('../../images/'.$row['filename']);
	}
} else {
	sendImage();
}


function sendImage() {
	global $basePath;
	
	//set_time_limit(120);
	$id = Request::getInt('id');
	$timestamp = requestGetText('timestamp');
	$width = Request::getInt('width');
	$height = Request::getInt('height');
	$max = Request::getInt('max');
	$maxwidth = Request::getInt('maxwidth');
	$maxheight = Request::getInt('maxheight');
	$percent = Request::getInt('percent');
	$rotate = Request::getInt('rotate');
	$quality = Request::getInt('quality');
	$greyscale = Request::getBoolean('greyscale');
	$blur = Request::getBoolean('blur');
	$format = Request::getString('format');
	if ($format=='') $format='png';
	$nocache = Request::getBoolean('nocache');

	$cache = 'local/cache/images/'.$id.($max>0 ? 'm'.$max : '').($maxwidth>0 ? 'mw'.$maxwidth : '').($maxheight>0 ? 'mh'.$maxheight : '').($width>0 ? 'w'.$width : '').($height>0 ? 'h'.$height : '').($percent>0 ? 'p'.$percent : '').($rotate>0 ? 'r'.$rotate : '').($quality>0 ? 'q'.$quality : '').($greyscale ? 'G' : '').($blur ? 'B' : '').'.'.$format;
	
	if (!file_exists($basePath.$cache) || $nocache) {
		$sql = 'select * from image where object_id='.$id;
		if ($row = Database::selectFirst($sql)) {
			// If nothing to do just redirect to the image
			if ($width==0 && $height==0 && $max==0 && $maxwidth==0 && $maxheight==0 && $percent==0 && $rotate==0 && !$greyscale) {
				redirect('../../images/'.$row['filename']);
				exit;
			}
			
			$filename = $basePath.'images/'.$row['filename'];
			$ext = strtolower(FileSystemUtil::getFileExtension($filename));
			
			if (!file_exists($filename)) {
				header("HTTP/1.0 404 Not Found");
				exit;
			}
			
			$image = loadImage($filename,$ext);
			if ($rotate>0) {
				$white = imagecolorallocate($image, 255, 255, 255);
				$image = imagerotate ($image, $rotate, $white);
			}

			$origHeight = imagesy($image);
			$origWidth = imagesx($image);
			
				
			if ($percent>0) {
				$width=round($origWidth*$percent/100);
				$height=round($origHeight*$percent/100);
			}
			else if ($width>0 && $height==0) {
				$height=($width/$origWidth)*$origHeight;
			}
			else if ($height>0 && $width==0) {
				$width=($height/$origHeight)*$origWidth;
			}
			else if ($max>0) {
				if ($origWidth>$origHeight) { // if width > height
					$width = $max;
					$height = ($origHeight/$origWidth)*$max;
				} elseif ($origWidth<$origHeight) {  // if width < height
					$width = ($origWidth/$origHeight)*$max;
					$height = $max;
				} else {
					$width = $max;
					$height = $max;
				}
			}
			else if ($maxheight>0 && $maxwidth==0) {
				$height=$maxheight;
				$width=($maxheight/$origHeight)*$origWidth;
			}
			else if ($maxwidth>0 && $maxheight==0) {
				$width=$maxwidth;
				$height=($maxwidth/$origWidth)*$origHeight;
			}
			else if ($maxwidth>0 && $maxheight>0) {
				if ($maxheight/$maxwidth > $origHeight/$origWidth) {
					$width=$maxwidth;
					$height=($maxwidth/$origWidth)*$origHeight;
				} else {
					$height=$maxheight;
					$width=($maxheight/$origHeight)*$origWidth;
				}
			}
			else if ($height==0 && $width==0) {
				$width=$origWidth;
				$height=$origHeight;
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
				if (!@ImageCopyResampled($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight)) {
					ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
				}
			}
			else {
				ImageCopyResized($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
			}
			if ($greyscale) {
				greyscale($thumb);
				//convertToGrayscale($image);
			}
			if ($blur) {
				blur($thumb);
				//convertToGrayscale($image);
			}
			if ($nocache) {
				if ($format=='png') {
					header('Content-Type: image/png');
					imagepng($thumb);
				} else {
					header('Content-Type: image/jpeg');
					if ($quality>0) {
						imagejpeg($thumb,null,$quality);
					} else {
						imagejpeg($thumb);
					}
				}
			} else {
				if ($format=='png') {
					imagepng($thumb, $basePath.$cache);
					header('Content-Type: image/png');
				} else {
					if ($quality>0) {
						imagejpeg($thumb,$basePath.$cache,$quality);
					} else {
						imagejpeg($thumb, $basePath.$cache);
					}
					header('Content-Type: image/jpeg');
				}
				header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($basePath.$cache)).' GMT');
				header("Content-Length: " . filesize($basePath.$cache));
				header('Expires: '.gmdate('D, d M Y H:i:s',time()+(60*60)) . ' GMT');
				header('Date: '.gmdate('D, d M Y H:i:s') . ' GMT');
				readfile($basePath.$cache);
			}
		} else {
			redirect('ImageNotFound.gif');
			//TODO: Do something about missing images
			// Inconsistency between old cached images and replaced images!
		}
	} else {
		//echo $basePath.$cache;
		if ($format=='png') {
			header('Content-Type: image/png');
		} else {
			header('Content-Type: image/jpeg');
		}
		$fp = fopen($basePath.$cache, 'rb');

		header("Content-Length: " . filesize($basePath.$cache));
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($basePath.$cache)).' GMT');
		header('Expires: '.gmdate('D, d M Y H:i:s',time()+(60*60)) . ' GMT');
		header('Date: '.gmdate('D, d M Y H:i:s') . ' GMT');
		readfile($basePath.$cache);
		//redirect('../../'.$cache.(strlen($timestamp)>0 ? '?timestamp='.$timestamp : ''));
	}
}

function &loadImage($path,$ext) {
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

function blur(&$image) {
	$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
	ImageConvolution($image, $gaussian, 16, 0);
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
?>