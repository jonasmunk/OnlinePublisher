<?php
require_once '../../../Editor/Include/Public.php';

$type = Request::getString('type');
$id = Request::getInt('id');
$pageId = Request::getInt('pageId');

if ($controller = PartService::getController($type)) {
	if ($part = PartService::load($type,$id)) {
		header("Content-Type: text/html; charset=UTF-8");
		$context = new PartContext();
		$context->setSynchronize(Request::getBoolean('synchronize'));
		$context->setLanguage(PageService::getLanguage($pageId));
		echo $controller->render($part,$context);
	}
}
?>