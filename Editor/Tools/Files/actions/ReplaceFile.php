<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
if (!$id) {
	Log::debug('No id provided');
	Response::badRequest();
	exit;
}

$response = FileService::replaceUploadedFile($id);

if ($response['success']==true) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>