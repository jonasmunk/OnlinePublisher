<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$url = Request::getString('url');

$response = ImageService::createImageFromUrl($url);
if ($response->getSuccess()) {
	$group = InternalSession::getToolSessionVar('images','group');
	if ($group) {
		ImageService::addImageToGroup($response->getObject()->getId(),$group);
	}
}
Response::sendUnicodeObject($response);
?>