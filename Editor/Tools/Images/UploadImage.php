<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../Include/Private.php';

if (ZipService::uploadIsZipFile()) {
	$group = InternalSession::getToolSessionVar('images','group');
	$zip = ZipService::getUploadedZip();
	$folder = $zip->extractToTemporaryFolder();
	$files = $folder->getFiles();
	foreach ($files as $file) {
		$result = ImageService::createImageFromFile($file,null,null,$group);
		if (!$result->getSuccess()) {
			Log::debug($result);
		}
	}
	$folder->remove();
	In2iGui::respondUploadSuccess();
	exit;
}

$response = ImageService::createUploadedImage();

if ($response->getSuccess()) {
	$group = InternalSession::getToolSessionVar('images','group');
	if ($group) {
		ImageService::addImageToGroup($response->getObject()->getId(),$group);
	}
	In2iGui::respondUploadSuccess();
} else {
	Log::debug($response);
	In2iGui::respondUploadFailure();
	exit;
}
?>