<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Request.php';
require_once '../../../../Classes/Page.php';
require_once '../../../../Classes/Log.php';

$pageId = Request::getInt('pageId');
$type = Request::getString('type');

if ($ctrl = PartService::getController($type)) {
	$part = $ctrl->getFromRequest();
	$part->save();

	Page::markChanged($pageId);

	header("Content-Type: text/html; charset=UTF-8");
	$context = PartService::buildPartContext($pageId);
	echo $ctrl->render($part,$context);
} else {
	Log::debug("Unable to find controller for $type");
}
?>