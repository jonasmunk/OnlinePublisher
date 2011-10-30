<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/ImageService.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Log.php';

Log::debug('Starting upload');

$response = ImageService::createUploadedImage();

Log::debug('Got response');
Log::debug($response);

if ($response['success']==true) {
	//if (InternalSession::getToolSessionVar('images','uploadAddToGroup',true)) {
		$group = InternalSession::getToolSessionVar('images','group');
		error_log($group.' / '.$response['id']);
		if ($group) {
			ImageService::addImageToGroup($response['id'],$group);
		}
	//}
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
	exit;
}
?>