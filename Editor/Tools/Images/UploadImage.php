<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/ImageService.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/In2iGui.php';

$response = ImageService::createUploadedImage();

if ($response['success']==true) {
	if (InternalSession::getToolSessionVar('images','uploadAddToGroup',true)) {
		$group = InternalSession::getToolSessionVar('images','group');
		error_log($group.' / '.$response['id']);
		if ($group) {
			ImageService::addImageToGroup($response['id'],$group);
		}
	}
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>