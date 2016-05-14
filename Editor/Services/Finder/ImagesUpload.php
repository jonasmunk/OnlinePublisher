<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$response = ImageService::createUploadedImage();

if ($response->getSuccess()) {
    Response::sendObject($response->getObject());
	//Response::uploadSuccess();
} else {
	Log::debug($response);
	Response::uploadFailure();
	exit;
}
?>