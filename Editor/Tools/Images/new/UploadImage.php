<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Services/ImageService.php';
require_once '../../../Classes/Response.php';
require_once '../../../Classes/In2iGui.php';

$response = ImageService::createUploadedImage();

if ($response['success']==true) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>