<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../Include/Private.php';

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