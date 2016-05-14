<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../Include/Private.php';

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