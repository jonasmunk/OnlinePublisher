<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$type = Request::getString('part_type');
$id = Request::getInt('id');
$section = Request::getInt('section');
$top = Request::getString('top');
$left = Request::getString('left');
$bottom = Request::getString('bottom');
$right = Request::getString('right');
$float = Request::getString('float');
$width = Request::getString('width');

$sql = "select page_id from document_section where id=".Database::int($section);
if ($sectionRow = Database::selectFirst($sql)) {
	$pageId = intval($sectionRow['page_id']);

	// update the section
	$sql="update document_section set".
	" `left`=".Database::text($left).
	",`right`=".Database::text($right).
	",`top`=".Database::text($top).
	",`bottom`=".Database::text($bottom).
	",`float`=".Database::text($float).
	",`width`=".Database::text($width).
	" where id=".Database::int($section);
	Database::update($sql);

	$controller = PartService::getController($type);
	if ($controller && method_exists($controller,'getFromRequest')) {
		$part = $controller->getFromRequest($id);
		$part->save();
		$controller->updateAdditional($part);
	}

	// Mark the page as changed
	PageService::markChanged($pageId);

}



Response::redirect('../Editor.php?section=0');
?>