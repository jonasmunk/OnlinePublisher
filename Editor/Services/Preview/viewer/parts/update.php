<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';
//usleep(2000000);
$id = Request::getInt('id');
$pageId = Request::getInt('pageId');
$type = Request::getString('type');

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