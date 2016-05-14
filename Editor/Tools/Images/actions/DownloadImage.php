<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$image = Image::load($id);
if ($image) {
	$path = ConfigurationService::getImagePath($image->getFilename());
	if (file_exists($path) && is_readable($path)) {
		header("Content-Disposition: attachment; filename=" . $image->getFilename());
		header("Content-Type: ".$image->getMimeType());
		header("Content-Length: " . filesize($path));
		readfile($path);			
	} else {
		Response::internalServerError();
	}
} else {
	Response::notFound();
}
?>