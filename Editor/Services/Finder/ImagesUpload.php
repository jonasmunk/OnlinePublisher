<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$response = ImageService::createUploadedImage();

if ($response->getSuccess()) {
    Response::sendObject($response->getObject());
	//In2iGui::respondUploadSuccess();
} else {
	Log::debug($response);
	In2iGui::respondUploadFailure();
	exit;
}
?>