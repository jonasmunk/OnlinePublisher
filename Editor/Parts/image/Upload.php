<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/ImageService.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Log.php';
require_once 'image.php';

// hide warnings
error_reporting(E_ERROR);

$response = ImageService::createUploadedImage();

if ($response['success']) {
	PartImage::setLatestUploadId($response['id']);
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>