<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('columnId');
$sectionIndex = Request::getInt('sectionIndex');

if (ImageService::isUploadedFileValid()) {
	$response = ImageService::createUploadedImage();
	if ($response->getSuccess()) {
		$image = $response->getObject();
		$ctrl = new ImagePartController();
		$part = $ctrl::createPart();

		$sectionId = DocumentTemplateEditor::addSectionFromPart($columnId,$sectionIndex,$part);
	}
} else {

	$response = FileService::createUploadedFile();
	if ($response->getSuccess()) {
		$file = $response->getObject();
	
		$ctrl = new FilePartController();
		$part = $ctrl::createPart();
	
		$sectionId = DocumentTemplateEditor::addSectionFromPart($columnId,$sectionIndex,$part);
	}	
}


?>