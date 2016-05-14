<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$response = FileService::createUploadedFile('',InternalSession::getToolSessionVar('files','group'));

if ($response->getSuccess()) {
	Response::uploadSuccess();
} else {
	Response::uploadFailure();
}
?>