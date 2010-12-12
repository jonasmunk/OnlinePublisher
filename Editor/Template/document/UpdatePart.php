<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Services/PartService.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Include/XmlWebGui.php';

$type = Request::getString('part_type');
$pageId = InternalSession::getPageId();
$id = Request::getInt('id');
$section = Request::getInt('section');
$top = Request::getString('top');
$left = Request::getString('left');
$bottom = Request::getString('bottom');
$right = Request::getString('right');
$float = Request::getString('float');
$width = Request::getString('width');


// update the section
$sql="update document_section set".
" `left`=".Database::text($left).
",`right`=".Database::text($right).
",`top`=".Database::text($top).
",`bottom`=".Database::text($bottom).
",`float`=".Database::text($float).
",`width`=".Database::text($width).
" where id=".$section;
Database::update($sql);

$controller = PartService::getController($type);
if ($controller && method_exists($controller,'getFromRequest')) {
	$part = $controller->getFromRequest($id);
	$part->save();
	$controller->updateAdditional($part);
}

// Mark the page as changed
Page::markChanged(InternalSession::getPageId());


Response::redirect('Editor.php?section=0');
?>