<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Interface/In2iGui.php';

$response = FileService::createUploadedFile('',InternalSession::getToolSessionVar('files','group'));

//sleep(3);

if ($response['success']==true) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>