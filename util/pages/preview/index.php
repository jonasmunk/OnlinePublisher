<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once '../../../Editor/Classes/Page.php';
require_once '../../../Editor/Classes/Request.php';
require_once '../../../Editor/Classes/Response.php';
require_once '../../../Editor/Classes/BumbleBee.php';
require_once '../../../Editor/Classes/FileSystemUtil.php';



buildPreview();

function buildPreview() {
	global $basePath,$baseUrl;
	$id = Request::getInt('id');
	$format = Request::getString('format');
	$print = Request::getBoolean('print');
	$width = Request::getInt('width');
	
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
	Response::redirect($file);
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
?>