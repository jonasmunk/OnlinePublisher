<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$pageId = Request::getInt('pageId');
$type = Request::getString('type');

$section = Request::getObject('section');


$sql="update document_section set".
" `left`=".Database::text($section->left).
",`right`=".Database::text($section->right).
",`top`=".Database::text($section->top).
",`bottom`=".Database::text($section->bottom).
" where id=".Database::int($section->id);
Database::update($sql);

if ($ctrl = PartService::getController($type)) {
	$part = $ctrl->getFromRequest($id);
	$part->save();

	PageService::markChanged($pageId);

	header("Content-Type: text/html; charset=UTF-8");
	$context = PartService::buildPartContext($pageId);
	echo $ctrl->render($part,$context);
} else {
	Log::debug("Unable to find controller for $type");
}
?>