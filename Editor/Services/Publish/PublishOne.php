<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Model/Object.php';
require_once '../../Classes/Services/PublishingService.php';

$kind = Request::getString('kind');
$id = Request::getInt('id');

if ($kind=='page') {
	PublishingService::publishPage($id);
}
else if ($kind=='object') {
	$object = Object::load($id);
	if ($object) {
		$object->publish();
	}
}
else if ($kind=='hierarchy') {
	$object = Hierarchy::load($id);
	if ($object) {
		$object->publish();
	}
}
?>