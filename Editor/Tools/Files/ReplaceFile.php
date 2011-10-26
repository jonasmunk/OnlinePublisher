<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';

$id = Request::getInt('id');
if (!$id) {
	Log::debug('No id provided');
	Response::badRequest();
	exit;
}

$response = FileService::replaceUploadedFile($id);

Log::debugJSON($response);

if ($response['success']==true) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>