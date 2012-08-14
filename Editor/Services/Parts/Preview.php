<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Parts
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');
$id = Request::getInt('id');
$pageId = Request::getInt('pageId');

if ($controller = PartService::getController($type)) {
	if ($part = $controller->getFromRequest($id)) {
		header("Content-Type: text/html; charset=UTF-8");
		$context = new PartContext();
		$context->setLanguage(PageService::getLanguage($pageId));
		echo $controller->render($part,$context);
	}
}
?>