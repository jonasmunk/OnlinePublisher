<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Object.php';
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