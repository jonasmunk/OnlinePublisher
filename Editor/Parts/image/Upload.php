<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/ImageService.php';
require_once '../../Classes/Parts/ImagePartController.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Log.php';

// hide warnings
error_reporting(E_ERROR);

$response = ImageService::createUploadedImage();

if ($response['success']) {
	ImagePartController::setLatestUploadId($response['id']);
	In2iGui::respondUploadSuccess();
} else {
	Log::debug($response);
	In2iGui::respondUploadFailure();
}
?>