<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../Include/Private.php';

$response = FileService::createUploadedFile();

if ($response->getSuccess()) {
	FilePartController::setLatestUploadId($response->getObject()->getId());
	Response::uploadSuccess();
} else {
	FilePartController::setLatestUploadId(null);
	Log::debug('Unable to upload file');
	Response::uploadFailure();
}
?>