<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Parts
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/PartService.php';

$type = Request::getString('type');
$id = Request::getInt('id');

if ($controller = PartService::getController($type)) {
	if ($part = $controller->getFromRequest($id)) {
		header("Content-Type: text/html; charset=UTF-8");
		echo $controller->render($part,new PartContext());
	}
}
?>