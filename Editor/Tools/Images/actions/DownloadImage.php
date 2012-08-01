<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$file = Image::load($id);
if ($file) {
	$path = $basePath.'/images/'.$file->getFilename();
	if (file_exists($path) && is_readable($path)) {
		header("Content-Disposition: attachment; filename=".$file->getFilename());
		header("Content-Type: ".$file->getMimeType());
		header("Content-Length: " . filesize($path));
		readfile($path);			
	} else {
		Response::internalServerError();
	}
} else {
	Response::notFound();
}
?>