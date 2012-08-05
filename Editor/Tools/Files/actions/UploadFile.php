<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$response = FileService::createUploadedFile('',InternalSession::getToolSessionVar('files','group'));

if ($response->getSuccess()) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>