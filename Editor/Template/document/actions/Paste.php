<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('column');
$index = Request::getInt('index');

$clipboard = ClipboardService::getClipboard();

if ($clipboard && $clipboard['type']=='section') {
	$section = DocumentTemplateEditor::getSection($clipboard['id']);
	$part = PartService::load($section['part_type'],$section['part_id']);
	Log::debug($section);
	if ($part) {
		Log::debug('Old id: '.$part->getId());
		$part->setId(null);
		$part->save();
		Log::debug('New id: '.$part->getId());
		$sectionId = DocumentTemplateEditor::addSectionFromPart($columnId,$index,$part);
		
		if ($clipboard['action']=='cut') {
			DocumentTemplateEditor::deleteSection($clipboard['id']);
			ClipboardService::clear();
		}
		Response::sendObject(array('sectionId'=>$sectionId));
		exit;
	}
}

Response::badRequest();


?>