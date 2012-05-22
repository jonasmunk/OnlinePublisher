<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Parts/FilePartController.php';
require_once '../../Classes/Core/Log.php';

$response = FileService::createUploadedFile();

if ($response->getSuccess()) {
	FilePartController::setLatestUploadId($response->getObject()->getId());
	In2iGui::respondUploadSuccess();
} else {
	FilePartController::setLatestUploadId(null);
	Log::debug('Unable to upload file');
	In2iGui::respondUploadFailure();
}
?>